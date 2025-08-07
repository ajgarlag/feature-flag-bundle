<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Fixtures;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature]
class ClassFeature
{
    public function __invoke(): bool
    {
        return true;
    }
}
