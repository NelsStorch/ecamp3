<?php

namespace App\Tests\Api\Comments;

use App\Tests\Api\ECampApiTestCase;

/**
 * @internal
 */
class ListCommentsTest extends ECampApiTestCase {
    public function testListCommentsIsDeniedForAnonymousUser() {
        static::createBasicClient()->request('GET', '/comments');

        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }

    public function testListCommentsIsAllowedForLoggedInUser() {
        $response = static::createClientWithCredentials()->request('GET', '/comments');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'totalItems' => 3,
            '_embedded' => [
                'items' => [],
            ],
        ]);
    }

    public function testListCommentsFilteredByActivity() {
        $activity = static::getFixture('activity1');
        $response = static::createClientWithCredentials()->request('GET', '/comments?activity='.$this->getIriFor($activity));

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'totalItems' => 2,
        ]);
    }

    public function testListCommentsActivitySubresource() {
        $activity = static::getFixture('activity1');
        $response = static::createClientWithCredentials()->request('GET', $this->getIriFor($activity).'/comments');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'totalItems' => 2,
        ]);
    }

    public function testListCommentsActivitySubresourceIsDeniedForUnrelatedUser() {
        $activity = static::getFixture('activity1');
        $response = static::createClientWithCredentials(['email' => static::$fixtures['user4unrelated']->getEmail()])->request('GET', $this->getIriFor($activity).'/comments');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Relation for link security not found.',
        ]);
    }
}
