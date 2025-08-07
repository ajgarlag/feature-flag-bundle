<?php

namespace Ajgarlag\FeatureFlagBundle\Attribute;

/**
 * Service tag to autoconfigure feature flags.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class AsFeature
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $method = null,
    ) {
    }
}
