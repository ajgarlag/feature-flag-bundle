<?php

namespace Ajgarlag\FeatureFlagBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FeatureFlagExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('feature_is_enabled', [FeatureFlagRuntime::class, 'isEnabled']),
            new TwigFunction('feature_get_value', [FeatureFlagRuntime::class, 'getValue']),
        ];
    }
}
