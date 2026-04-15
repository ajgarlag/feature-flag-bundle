<?php

namespace Ajgarlag\FeatureFlagBundle\Provider;

final class InMemoryProvider implements ProviderInterface
{
    /**
     * @param array<string, (callable(): mixed)> $features
     */
    public function __construct(
        private readonly array $features,
    ) {
    }

    public function get(string $featureName): ?callable
    {
        return $this->features[$featureName] ?? null;
    }
}
