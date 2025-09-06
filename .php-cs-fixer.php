<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'method_chaining_indentation' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );
