<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature(name: 'custom_name')]
class NamedFeature
{
    public function __invoke(): bool
    {
        return true;
    }
}
