<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

class DifferentMethodFeature
{
    #[AsFeature(method: 'different')]
    public function resolve(): bool
    {
        return true;
    }
}
