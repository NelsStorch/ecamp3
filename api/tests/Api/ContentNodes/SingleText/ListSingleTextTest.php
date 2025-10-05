<?php

namespace App\Tests\Api\ContentNodes\SingleText;

use App\Tests\Api\ContentNodes\ListContentNodeTestCase;

/**
 * @internal
 */
class ListSingleTextTest extends ListContentNodeTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->endpoint = '/content_node/single_texts?camp=/camps/'.static::$fixtures['campPrototype']->getId();

        $this->contentNodesCamp1and2 = [
            //            $this->getIriFor('singleText1'),
            //            $this->getIriFor('singleText2'),
            //            $this->getIriFor('safetyConsiderations1'),
        ];

        $this->contentNodesCampUnrelated = [
            //            $this->getIriFor('singleTextCampUnrelated'),
        ];

        $this->contentNodesPublicCamps = [
            $this->getIriFor('singleTextCampPrototype'),
            //            $this->getIriFor('singleTextCampShared'),
        ];
    }
}
