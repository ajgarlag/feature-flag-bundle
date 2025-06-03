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

use Ajgarlag\FeatureFlagBundle\FeatureFlagBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;

return [
    new FrameworkBundle(),
    new TestBundle(),
    new FeatureFlagBundle(),
];
