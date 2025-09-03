<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\Camp;
use App\Entity\CampCollaboration;
use App\Entity\User;
use App\State\Util\AbstractPersistProcessor;
use App\State\Util\PropertyChangeListener;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @template-extends AbstractPersistProcessor<CampCollaboration>
 */
class CampUpdateProcessor extends AbstractPersistProcessor {
    public function __construct(
        ProcessorInterface $decorated,
        private Security $security,
    ) {
        $sharingChangeListener = PropertyChangeListener::of(
            extractProperty: fn (Camp $data) => $data->isShared,
            beforeAction: fn (Camp $data) => $this->onBeforeSharingStatusChange($data),
        );

        parent::__construct(
            $decorated,
            propertyChangeListeners: [
                $sharingChangeListener,
            ]
        );
    }

    public function onBeforeSharingStatusChange(Camp $data): Camp {
        if ($data->isShared) {
            $data->sharedSince = new \DateTime();

            /** @var User $user */
            $user = $this->security->getUser();
            $data->sharedBy = $user;
        }

        return $data;
    }
}
