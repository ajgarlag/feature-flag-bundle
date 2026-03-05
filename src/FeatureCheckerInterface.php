<?php

namespace Ajgarlag\FeatureFlagBundle;

use Symfony\Component\FeatureFlag\Provider\ProviderInterface;

/**
 * Checks if a feature is enabled or retrieves its value.
 *
 * This is the main entry point to interact with the feature flag
 * system. It uses the configured {@see ProviderInterface} to determine
 * whether a feature is active and what its current value is.
 */
interface FeatureCheckerInterface
{
    public function isEnabled(string $featureName): bool;

    public function getValue(string $featureName): mixed;
}
