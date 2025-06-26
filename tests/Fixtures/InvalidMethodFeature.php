<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature(method: 'invalid_method')]
class InvalidMethodFeature
{
    public function resolve(): bool
    {
        return true;
    }
}
