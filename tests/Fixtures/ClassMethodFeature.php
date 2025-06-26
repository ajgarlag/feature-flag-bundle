<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature(method: 'resolve')]
class ClassMethodFeature
{
    public function resolve(): bool
    {
        return true;
    }
}
