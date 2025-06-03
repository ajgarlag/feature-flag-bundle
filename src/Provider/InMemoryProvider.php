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
final class InMemoryProvider implements ProviderInterface
{
    /**
     * @param array<string, (\Closure(): mixed)> $features
     */
    public function __construct(
        private readonly array $features,
    ) {
    }

    public function get(string $featureName): ?\Closure
    {
        return $this->features[$featureName] ?? null;
    }

    public function getNames(): array
    {
        return array_keys($this->features);
    }
}
