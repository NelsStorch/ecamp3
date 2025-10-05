<?php

namespace App\Tests\Api\ContentNodes\ResponsiveLayout;

use App\Tests\Api\ContentNodes\ListContentNodeTestCase;

/**
 * @internal
 */
class ListResponsiveLayoutTest extends ListContentNodeTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->endpoint = '/content_node/responsive_layouts'.'?camp=/camps/'.static::$fixtures['campPrototype']->getId();

        $this->contentNodesCamp1and2 = [
//            $this->getIriFor('responsiveLayout1'),
        ];

        $this->contentNodesCampUnrelated = [
//            $this->getIriFor('responsiveLayoutCampUnrelated'),
        ];

        $this->contentNodesPublicCamps = [
            $this->getIriFor('responsiveLayoutCampPrototype'),
//            $this->getIriFor('responsiveLayoutCampShared'),
        ];
    }
}
