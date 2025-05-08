<?php

namespace App\Tests\Api\Profiles;

use App\Entity\Profile;
use App\Service\MailService;
use App\Tests\Api\ECampApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
class UpdateProfileTest extends ECampApiTestCase {
    public function testPatchProfileIsDeniedForAnonymousProfile() {
        $user = static::getFixture('user1manager');
        static::createBasicClient()->request('PATCH', '/profiles/'.$user->getId(), ['json' => [
            'nickname' => 'Linux',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testPatchProfileIsDeniedForRelatedProfile() {
        $user2 = static::getFixture('user2member');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$user2->getId(), ['json' => [
            'nickname' => 'Linux',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testPatchProfileIsDeniedForUnrelatedProfile() {
        $user2 = static::getFixture('user4unrelated');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$user2->getId(), ['json' => [
            'nickname' => 'Linux',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testPatchProfileIsAllowedForSelf() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'nickname' => 'Linux',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'nickname' => 'Linux',
            '_links' => [
                'self' => [
                    'href' => '/profiles/'.$profile->getId(),
                ],
                'user' => [
                    'href' => $this->getIriFor('user1manager'),
                ],
            ],
        ]);
    }

    public function testPatchProfileIsAllowedForSelfIfSelfHasNoCampCollaborations() {
        $profile = static::getFixture('profileWithoutCampCollaborations');
        static::createClientWithCredentials(['email' => $profile->user->getEmail()])
            ->request(
                'PATCH',
                '/profiles/'.$profile->getId(),
                [
                    'json' => ['nickname' => 'Linux'],
                    'headers' => ['Content-Type' => 'application/merge-patch+json'],
                ]
            )
        ;
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'nickname' => 'Linux',
            '_links' => [
                'self' => [
                    'href' => '/profiles/'.$profile->getId(),
                ],
                'user' => [
                    'href' => $this->getIriFor('userWithoutCampCollaborations'),
                ],
            ],
        ]);
    }

    public function testPatchProfileDisallowsChangingEmail() {
        /** @var Profile $profile */
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'email' => 'e@mail.com',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'detail' => 'Extra attributes are not allowed ("email" is unknown).',
        ]);
    }

    public function testPatchProfileValidatesChangedEmail() {
        $client = static::createClientWithCredentials();
        // Disable resetting the database between the two requests
        $client->disableReboot();

        $untrustedEmailKey = null;
        $mailServiceMock = $this->createMock(MailService::class);
        $mailServiceMock->expects($this->once())->method('sendEmailVerificationMail')->willReturnCallback(function($user, $profile) use(&$untrustedEmailKey) {
            $untrustedEmailKey = $profile->untrustedEmailKey;
        });
        $this->getContainer()->set(MailService::class, $mailServiceMock);

        /** @var Profile $profile */
        $profile = static::getFixture('profile1manager');

        $client->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'newEmail' => 'new@example.com',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);

        // when
        $client->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'untrustedEmailKey' => $untrustedEmailKey,
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);

        // then
        $this->assertResponseStatusCodeSame(200);
        $profile = $this->getEntityManager()->find(Profile::class, $profile->getId());
        $this->assertEquals('new@example.com', $profile->email);
    }

    public function testPatchProfileTrimsFirstname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'firstname' => "\tHello ",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'firstname' => 'Hello',
        ]);
    }

    public function testPatchProfileCleansForbiddenCharactersFromFirstname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'firstname' => "\n\tHello",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'firstname' => 'Hello',
        ]);
    }

    public function testPatchProfileValidatesFirstnameMaxLength() {
        /** @var Profile $profile */
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'firstname' => str_repeat('a', 65),
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'firstname: This value is too long. It should have 64 characters or less.',
        ]);
    }

    public function testPatchProfileTrimsSurname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'surname' => "\tHello ",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'surname' => 'Hello',
        ]);
    }

    public function testPatchProfileCleansForbiddenCharactersFromSurname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'surname' => "\n\tHello",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'surname' => 'Hello',
        ]);
    }

    public function testPatchProfileValidatesSurnameMaxLength() {
        /** @var Profile $profile */
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'surname' => str_repeat('a', 65),
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'surname: This value is too long. It should have 64 characters or less.',
        ]);
    }

    public function testPatchProfileTrimsNickname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'nickname' => "\tHello ",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'nickname' => 'Hello',
        ]);
    }

    public function testPatchProfileCleansForbiddenCharactersFromNickname() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'nickname' => "\n\tHello",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'nickname' => 'Hello',
        ]);
    }

    public function testPatchProfileValidatesNicknameMaxLength() {
        /** @var Profile $profile */
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'nickname' => str_repeat('a', 33),
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'detail' => 'nickname: This value is too long. It should have 32 characters or less.',
        ]);
    }

    public function testPatchProfileTrimsLanguage() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'language' => "\tde ",
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'language' => 'de',
        ]);
    }

    public function testPatchProfileValidatesInvalidColor() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'color' => 'red',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'color',
                    'message' => 'This value is not valid.',
                ],
            ],
        ]);
    }

    #[DataProvider('invalidAbbreviations')]
    public function testPatchCampCollaborationValidatesInvalidAbbreviation($abbreviation) {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'abbreviation' => $abbreviation,
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'abbreviation',
                    'message' => 'This value is too long. It should have 2 characters or less.',
                ],
            ],
        ]);
    }

    #[DataProvider('validAbbreviations')]
    public function testPatchCampCollaborationValidatesValidAbbreviation($abbreviation) {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'abbreviation' => $abbreviation,
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'abbreviation' => $abbreviation,
        ]);
    }

    public function testPatchProfileValidatesInvalidLanguage() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request('PATCH', '/profiles/'.$profile->getId(), ['json' => [
            'language' => 'französisch',
        ], 'headers' => ['Content-Type' => 'application/merge-patch+json']]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'language',
                    'message' => 'The value you selected is not a valid choice.',
                ],
            ],
        ]);
    }

    public function testPatchProfileDoesNotAllowPatchingUser() {
        $profile = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request(
            'PATCH',
            '/profiles/'.$profile->getId(),
            [
                'json' => [
                    'user' => [
                        'password' => 'an 8 digit long password',
                    ],
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Extra attributes are not allowed ("user" is unknown).',
        ]);
    }

    #[DataProvider('notWriteableProfileProperties')]
    public function testNotWriteableProperties(string $property) {
        $user = static::getFixture('profile1manager');
        static::createClientWithCredentials()->request(
            'PATCH',
            '/profiles/'.$user->getId(),
            [
                'json' => [
                    $property => 'something',
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "Extra attributes are not allowed (\"{$property}\" is unknown).",
        ]);
    }

    public static function notWriteableProfileProperties(): array {
        return [
            'untrustedEmailKeyHash' => ['untrustedEmailKeyHash'],
            'googleId' => ['googleId'],
            'pbsmidataId' => ['pbsmidataId'],
            'roles' => ['roles'],
            'user' => ['user'],
        ];
    }

    public static function invalidAbbreviations(): array {
        return [
            ['ABC'],
            ['D3C'],
            ['🧑🏿‍🚀🙋🏼‍♀️😊'],
        ];
    }

    public static function validAbbreviations(): array {
        return [
            ['AB'],
            ['33'],
            ['X4'],
            ['✅😊'],
            ['🧑🏿‍🚀🧑🏼‍🔧'],
        ];
    }
}
