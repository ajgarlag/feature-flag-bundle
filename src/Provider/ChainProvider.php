<?php

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
