<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\Doctrine\Bundle230\Rector\Class_\AddAnnotationToRepositoryRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\CodeQuality\Rector\ClassMethod\AddInstanceofAssertForNullableInstanceRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEmptyNullableObjectToAssertInstanceofRector;
use Rector\Privatization\Rector\Class_\FinalizeTestCaseClassRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

// @noinspection PhpUnhandledExceptionInspection
return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/public',
        __DIR__.'/config',
        __DIR__.'/migrations',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withComposerBased(doctrine: true, phpunit: true, symfony: true)
    ->withPreparedSets(deadCode: true, codeQuality: true, privatization: true, rectorPreset: true, phpunitCodeQuality: true, symfonyCodeQuality: true)
    ->withAttributesSets(all: true)
    ->withConfiguredRule(RenameFunctionRector::class, [
        'implode' => 'join',
        'join' => 'join',
    ])
    ->withSkip([
        AddAnnotationToRepositoryRector::class,
        AddInstanceofAssertForNullableInstanceRector::class,
        AssertEmptyNullableObjectToAssertInstanceofRector::class,
        CombineIfRector::class,
        DeclareStrictTypesRector::class,
        DisallowedEmptyRuleFixerRector::class,
        ExplicitBoolCompareRector::class,
        FinalizeTestCaseClassRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        PreferPHPUnitThisCallRector::class,
        RemoveDeadInstanceOfRector::class,
        SimplifyIfElseToTernaryRector::class,
        SimplifyIfReturnBoolRector::class,
        ThrowWithPreviousExceptionRector::class,
        UseIdenticalOverEqualWithSameTypeRector::class,
    ])
;
