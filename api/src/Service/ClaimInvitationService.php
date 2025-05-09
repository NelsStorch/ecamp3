<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CampCollaborationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClaimInvitationService {
    public function __construct(
        private readonly CampCollaborationRepository $campCollaborationRepository,
        private readonly EntityManagerInterface $em,
    ) {}

    public function claimInvitations(User $user, string $email): void {
        $personalInvitationsForNewEmail = $this->campCollaborationRepository->findAllByInviteEmailAndInvited($email);
        foreach ($personalInvitationsForNewEmail as $invitation) {
            // Convert all invitations who specifically invited this email address to
            // personal invitations, which the invited user will be able to see and
            // accept / reject in the UI, even without receiving the invitation email.
            // This is done by setting the user field instead of the inviteEmail field.
            $invitation->inviteEmail = null;
            $invitation->user = $user;
            $this->em->persist($invitation);
            $this->em->flush();
        }
    }
}
