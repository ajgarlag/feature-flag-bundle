<?php

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
