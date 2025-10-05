<?php

namespace App\Tests\Api\ContentNodes\ColumnLayout;

use App\Tests\Api\ContentNodes\ListContentNodeTestCase;

/**
 * @internal
 */
class ListColumnLayoutTest extends ListContentNodeTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->endpoint = '/content_node/column_layouts'.'?camp=/camps/'.static::$fixtures['campPrototype']->getId();

        $this->contentNodesCamp1and2 = [
//            $this->getIriFor('columnLayout1'),
//            $this->getIriFor('columnLayout2'),
//            $this->getIriFor('columnLayoutChild1'),
//            $this->getIriFor('columnLayout2Child1'),
//            $this->getIriFor('columnLayout3'),
//            $this->getIriFor('columnLayout4'),
//            $this->getIriFor('columnLayout5'),
//            $this->getIriFor('columnLayout1camp2'),
//            $this->getIriFor('columnLayout2camp2'),
        ];

        $this->contentNodesCampUnrelated = [
//            $this->getIriFor('columnLayout1campUnrelated'),
//            $this->getIriFor('columnLayout2campUnrelated'),
        ];

        $this->contentNodesPublicCamps = [
            $this->getIriFor('columnLayout1campPrototype'),
//            $this->getIriFor('columnLayout2campPrototype'),
            $this->getIriFor('columnLayout3campPrototype'),
//            $this->getIriFor('columnLayout1campShared'),
//            $this->getIriFor('columnLayout2campShared'),
//            $this->getIriFor('columnLayout3campShared'),
        ];
    }
}
