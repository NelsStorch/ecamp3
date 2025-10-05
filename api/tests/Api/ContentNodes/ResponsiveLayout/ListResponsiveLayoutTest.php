<?php

namespace App\Tests\Api\ContentNodes\ResponsiveLayout;

use App\Tests\Api\ContentNodes\ListContentNodeTestCase;

/**
 * @internal
 */
class ListResponsiveLayoutTest extends ListContentNodeTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->endpointBase = '/content_node/responsive_layouts';

        $this->contentNodesCamp1 = [
            $this->getIriFor('responsiveLayout1'),
        ];

        $this->contentNodesCamp2 = [
            // none
        ];

        $this->contentNodesCampUnrelated = [
            $this->getIriFor('responsiveLayoutCampUnrelated'),
        ];

        $this->contentNodesCampPrototype = [
            $this->getIriFor('responsiveLayoutCampPrototype'),
        ];

        $this->contentNodesCampShared = [
            $this->getIriFor('responsiveLayoutCampShared'),
        ];
    }
}
