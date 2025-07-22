<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ClaimInvitationService;
use App\Service\MailService;
use App\State\Util\AbstractPersistProcessor;
use App\Util\IdGenerator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * @template-extends AbstractPersistProcessor<Profile>
 */
class ProfileUpdateProcessor extends AbstractPersistProcessor {
    private $emailAddressVerificationPerformed = false;

    public function __construct(
        ProcessorInterface $decorated,
        private PasswordHasherFactoryInterface $pwHasherFactory,
        private MailService $mailService,
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly ClaimInvitationService $claimInvitationService,
    ) {
        parent::__construct($decorated);
    }

    /**
     * @param Profile $data
     */
    public function onBefore($data, Operation $operation, array $uriVariables = [], array $context = []): Profile {
        $this->emailAddressVerificationPerformed = false;

        /** @var Profile $data */
        if (isset($data->newEmail)) {
            $verificationKey = IdGenerator::generateRandomHexString(64);
            $data->untrustedEmail = $data->newEmail;
            $data->untrustedEmailKey = $verificationKey;
            $data->untrustedEmailKeyHash = $this->getResetKeyHasher()->hash($verificationKey);
        } elseif (isset($data->untrustedEmailKey)) {
            if (!isset($data->untrustedEmailKeyHash)) {
                throw new HttpException(422, 'Email verification failed A');
            }

            if (!$this->getResetKeyHasher()->verify($data->untrustedEmailKeyHash, $data->untrustedEmailKey)) {
                throw new HttpException(422, 'Email verification failed B');
            }

            $data->email = $data->untrustedEmail;
            $data->untrustedEmail = null;
            $data->untrustedEmailKey = null;
            $data->untrustedEmailKeyHash = null;

            $this->emailAddressVerificationPerformed = true;
        }

        return $data;
    }

    public function onAfter($data, Operation $operation, array $uriVariables = [], array $context = []): void {
        /** @var Profile $data */
        if (isset($data->untrustedEmailKey)) {
            $this->mailService->sendEmailVerificationMail($data->user, $data);
            $data->untrustedEmailKey = null;
        }

        if ($this->emailAddressVerificationPerformed) {
            $user = $this->getUser();
            $this->claimInvitationService->claimInvitations($user, $data->email);
        }
    }

    private function getResetKeyHasher(): PasswordHasherInterface {
        return $this->pwHasherFactory->getPasswordHasher('EmailVerification');
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function getUser(): ?User {
        $user = $this->security->getUser();
        if (null == $user) {
            // This should never happen because it should be caught earlier by our security settings
            // on all API operations using this processor.
            throw new AccessDeniedHttpException();
        }

        return $this->userRepository->loadUserByIdentifier($user->getUserIdentifier());
    }
}
