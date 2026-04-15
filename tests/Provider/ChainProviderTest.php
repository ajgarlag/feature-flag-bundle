<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Provider;

use Ajgarlag\FeatureFlagBundle\Provider\ChainProvider;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class ChainProviderTest extends TestCase
{
    private ChainProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new ChainProvider([
            new InMemoryProvider([
                'first' => static fn () => true,
            ]),
            new InMemoryProvider([
                'second' => static fn () => 42,
            ]),
            new InMemoryProvider([
                'exception' => static fn () => throw new \LogicException('Should not be called.'),
            ]),
        ]);
    }

    public function testGet(): void
    {
        $feature = $this->provider->get('first');

        $this->assertIsCallable($feature);
        $this->assertTrue($feature());
    }

    public function testGetFallback(): void
    {
        $feature = $this->provider->get('second');

        $this->assertIsCallable($feature);
        $this->assertSame(42, $feature());
    }

    public function testGetLazy(): void
    {
        $this->assertIsCallable($this->provider->get('exception'));
    }

    public function testGetNotFound(): void
    {
        $feature = $this->provider->get('unknown');

        $this->assertNull($feature);
    }
}
