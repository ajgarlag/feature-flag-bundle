<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature(method: 'resolve')]
class InvalidMethodVisibilityFeature
{
    protected function resolve(): bool
    {
        return true;
    }
}
