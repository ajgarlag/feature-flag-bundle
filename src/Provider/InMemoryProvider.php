<?php

namespace Ajgarlag\FeatureFlagBundle\Provider;

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
