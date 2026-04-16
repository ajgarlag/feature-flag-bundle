<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Provider;

use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class InMemoryProviderTest extends TestCase
{
    private InMemoryProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new InMemoryProvider([
            'first' => static fn () => true,
            'second' => static fn () => 42,
            'exception' => static fn () => throw new \LogicException('Should not be called.'),
        ]);
    }

    public function testGet(): void
    {
        $feature = $this->provider->get('first');
        $this->assertIsCallable($feature);
        $this->assertTrue($feature());

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
