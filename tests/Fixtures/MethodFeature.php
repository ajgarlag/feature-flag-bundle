<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

class MethodFeature
{
    #[AsFeature(name: 'method_string')]
    public function string(): string
    {
        return 'green';
    }

    #[AsFeature(name: 'method_int')]
    public function int(): int
    {
        return 42;
    }
}
