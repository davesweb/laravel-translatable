<?php

$finder =
    PhpCsFixer\Finder::create()
        ->in([__DIR__ . '/src'])
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12'                    => true,
    '@PhpCsFixer'               => true,
    'strict_param'              => true,
    'array_syntax'              => ['syntax' => 'short'],
    'ordered_imports'           => ['sort_algorithm' => 'length'],
    'binary_operator_spaces'    => ['default' => 'align'],
    'return_assignment'         => false,
    'concat_space'              => ['spacing' => 'one'],
    'single_line_comment_style' => ['comment_types' => ['hash']],
])->setFinder($finder);
