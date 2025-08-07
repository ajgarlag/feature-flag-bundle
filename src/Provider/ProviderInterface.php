<?php

namespace Ajgarlag\FeatureFlagBundle\Provider;

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
