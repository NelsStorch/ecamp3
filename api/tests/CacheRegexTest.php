<?php

use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function PHPUnit\Framework\assertDoesNotMatchRegularExpression;
use function PHPUnit\Framework\assertMatchesRegularExpression;

/**
 * @internal
 */
class CacheRegexTest extends KernelTestCase {
    private string $cacheRegex;

    protected function setUp(): void {
        $this->cacheRegex = '{'.$this->getContainer()->getParameter('app.httpCache.matchPath').'}';
    }

    #[TestWith(name: '', data: [''])]
    #[TestWith(name: '/', data: ['/'])]
    #[TestWith(name: '/index.jsonhal', data: ['index.jsonhal'])]
    #[TestWith(name: '/content_types', data: ['/content_types'])]
    #[TestWith(name: '/content_types/25a82375a0b6', data: ['/content_types/25a82375a0b6'])]
    #[TestWith(name: '/camps/25a82475e0b7/categories', data: ['/camps/25a82475e0b7/categories'])]
    public function testIncludesUrls(string $url) {
        assertMatchesRegularExpression($this->cacheRegex, $url);
    }

    #[TestWith(name: '/camps/25a82475e0b7/categories/c53dd7917e63', data: ['/camps/25a82475e0b7/categories/c53dd7917e63'])]
    public function testAlsoIncludesUrls(string $url) {
        assertMatchesRegularExpression($this->cacheRegex, $url);
    }

    #[TestWith(name: '/invitations', data: ['/invitations'])]
    #[TestWith(name: '/personal_invitations', data: ['/personal_invitations'])]
    #[TestWith(name: '/activity_progress_labels', data: ['/activity_progress_labels'])]
    #[TestWith(name: '/activity_responsibles', data: ['/activity_responsibles'])]
    #[TestWith(name: '/camp_collaborations', data: ['/camp_collaborations'])]
    #[TestWith(name: '/categories', data: ['/categories'])]
    #[TestWith(name: '/checklists', data: ['/checklists'])]
    #[TestWith(name: '/checklist_items', data: ['/checklist_items'])]
    #[TestWith(name: '/content_nodes', data: ['/content_nodes'])]
    #[TestWith(name: '/content_node/checklist_nodes', data: ['/content_node/checklist_nodes'])]
    #[TestWith(name: '/content_node/column_layouts', data: ['/content_node/column_layouts'])]
    #[TestWith(name: '/content_node/material_nodes', data: ['/content_node/material_nodes'])]
    #[TestWith(name: '/content_node/multi_selects', data: ['/content_node/multi_selects'])]
    #[TestWith(name: '/content_node/storyboards', data: ['/content_node/storyboards'])]
    #[TestWith(name: '/days', data: ['/days'])]
    #[TestWith(name: '/day_responsibles', data: ['/day_responsibles'])]
    #[TestWith(name: '/material_items', data: ['/material_items'])]
    #[TestWith(name: '/material_lists', data: ['/material_lists'])]
    #[TestWith(name: '/periods', data: ['/periods'])]
    #[TestWith(name: '/profiles', data: ['/profiles'])]
    #[TestWith(name: '/schedule_entries', data: ['/schedule_entries'])]
    #[TestWith(name: '/users', data: ['/users'])]
    public function testDoesNotIncludeUrls(string $url) {
        assertDoesNotMatchRegularExpression($this->cacheRegex, $url);
    }
}
