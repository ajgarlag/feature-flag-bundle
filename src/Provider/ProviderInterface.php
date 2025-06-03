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

namespace Ajgarlag\FeatureFlagBundle\Provider;

/**
 * @experimental
 */
interface ProviderInterface
{
    /**
     * @return ?\Closure(): mixed
     */
    public function get(string $featureName): ?\Closure;

    /**
     * @return list<string>
     */
    public function getNames(): array;
}
