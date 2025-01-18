<?php

namespace App\Doctrine\Orm\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

final class FilterEagerLoadingsExtension implements QueryCollectionExtensionInterface {
    public function __construct(private QueryCollectionExtensionInterface $decorated) {}

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        ?string $resourceClass = null,
        ?Operation $operation = null,
        array $context = []
    ): void {
        // Manipulates $queryBuilder

        // Orig:
        //  select
        //  from        category c0_
        //  inner join  camp c1_       ON c0_.campId = c1_.id
        //  ...
        //  where       c1_.id = ?

        // New:
        //  select      ...
        //  from        category c0_
        //  inner join  camp c1_       ON c0_.campId = c1_.id
        //  ...
        //  where       c0_.id IN (
        //                  SELECT c9_.id
        //                  FROM category c9_
        //                  INNER JOIN camp c10_ ON c9_.campId = c10_.id
        //                  WHERE c10_.id = ?
        //              )

        // Not clear, why ApiPlatform is doing this.
        // Orig-Verison performs better.
        // FilterEagerLoadingExtension is disabled.

        $this->decorated->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operation, $context);
    }
}
