<?php

/*
 * This file is part of the ajgl/feature-flag-bundle package.
 *
 * It has been borrowed from https://github.com/symfony/symfony/pull/53213.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature(name: 'custom_name')]
class NamedFeature
{
    public function __invoke(): bool
    {
        return true;
    }
}
