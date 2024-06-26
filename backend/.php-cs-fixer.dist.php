<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'yoda_style' => false,
    ])
    ->setCacheFile('var/cache/dev/.php-cs-fixer.cache')
    ->setFinder($finder)
;
