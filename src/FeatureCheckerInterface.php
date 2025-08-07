<?php

namespace Ajgarlag\FeatureFlagBundle;

interface FeatureCheckerInterface
{
    public function isEnabled(string $featureName): bool;

    public function getValue(string $featureName): mixed;
}
