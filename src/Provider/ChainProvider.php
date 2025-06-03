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
final class ChainProvider implements ProviderInterface
{
    public function __construct(
        /** @var list<ProviderInterface> */
        private readonly iterable $providers = [],
    ) {
    }

    public function get(string $featureName): ?\Closure
    {
        foreach ($this->providers as $provider) {
            if ($feature = $provider->get($featureName)) {
                return $feature;
            }
        }

        return null;
    }

    public function getNames(): array
    {
        $names = [];
        foreach ($this->providers as $provider) {
            foreach ($provider->getNames() as $name) {
                $names[$name] = true;
            }
        }

        return array_keys($names);
    }
}
