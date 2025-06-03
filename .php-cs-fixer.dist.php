<?php

$header = <<<'EOS'
This file is part of the ajgl/feature-flag-bundle package.

It has been borrowed from https://github.com/symfony/symfony/pull/53213.

(c) Fabien Potencier <fabien@symfony.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOS;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('tests/bin')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP81Migration' => true,
        'header_comment' => ['header' => $header]
    ])
    ->setFinder($finder)
;
