<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
;
