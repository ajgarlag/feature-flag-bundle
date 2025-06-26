<?php

use Ajgarlag\FeatureFlagBundle\FeatureFlagBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;

return [
    new FrameworkBundle(),
    new TestBundle(),
    new FeatureFlagBundle(),
];
