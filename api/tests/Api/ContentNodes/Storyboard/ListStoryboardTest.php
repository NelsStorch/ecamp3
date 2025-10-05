<?php

namespace App\Tests\Api\ContentNodes\Storyboard;

use App\Tests\Api\ContentNodes\ListContentNodeTestCase;

/**
 * @internal
 */
class ListStoryboardTest extends ListContentNodeTestCase {
    public function setUp(): void {
        parent::setUp();

        $this->endpoint = '/content_node/storyboards'.'?camp=/camps/'.static::$fixtures['campPrototype']->getId();

        $this->contentNodesCamp1and2 = [
//            $this->getIriFor('storyboard1'),
//            $this->getIriFor('storyboard2'),
        ];

        $this->contentNodesCampUnrelated = [
//            $this->getIriFor('storyboardCampUnrelated'),
        ];

        $this->contentNodesPublicCamps = [
            $this->getIriFor('storyboardCampPrototype'),
//            $this->getIriFor('storyboardCampShared'),
        ];
    }
}
