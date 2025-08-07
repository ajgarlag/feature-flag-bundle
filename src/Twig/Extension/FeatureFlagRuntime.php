<?php

namespace Ajgarlag\FeatureFlagBundle\Twig\Extension;

use Ajgarlag\FeatureFlagBundle\FeatureCheckerInterface;

final class FeatureFlagRuntime
{
    public function __construct(
        private readonly FeatureCheckerInterface $featureChecker,
    ) {
    }

    public function isEnabled(string $featureName): bool
    {
        return $this->featureChecker->isEnabled($featureName);
    }

    public function getValue(string $featureName): mixed
    {
        return $this->featureChecker->getValue($featureName);
    }
}
