<?php

namespace App\Doctrine\DBAL\Schema;

use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;

class CustomPostgreSQLSchemaManager extends PostgreSQLSchemaManager {
    protected function _getPortableTableIndexesList(array $rows, string $tableName): array {
        $indexes = parent::_getPortableTableIndexesList($rows, $tableName);

        return array_filter($indexes, function ($key) {
            return 0 !== strpos($key, 'unmanaged_');
        }, ARRAY_FILTER_USE_KEY);
    }
}
