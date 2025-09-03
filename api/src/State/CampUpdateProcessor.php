<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\Camp;
use App\Entity\CampCollaboration;
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
            beforeAction: fn (Camp $data) => $this->onBeforeStatusChange($data),
        );

        parent::__construct(
            $decorated,
            propertyChangeListeners: [
                $sharingChangeListener,
            ]
        );
    }

    public function onBeforeStatusChange(Camp $data): Camp {
        if (true == $data->isShared) {
            $data->sharedSince = new \DateTime();
            $data->sharedBy = $this->security->getUser();
        }

        return $data;
    }
}
