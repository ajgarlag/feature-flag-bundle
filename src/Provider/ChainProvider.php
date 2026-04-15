<?php

namespace Ajgarlag\FeatureFlagBundle\Provider;

final class ChainProvider implements ProviderInterface
{
    public function __construct(
        /** @var iterable<ProviderInterface> */
        private readonly iterable $providers = [],
    ) {
    }

    public function get(string $featureName): ?callable
    {
        foreach ($this->providers as $provider) {
            if ($feature = $provider->get($featureName)) {
                return $feature;
            }
        }

        return null;
    }
}
