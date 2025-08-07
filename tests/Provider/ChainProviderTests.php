<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Provider;

use Ajgarlag\FeatureFlagBundle\Provider\ChainProvider;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class ChainProviderTests extends TestCase
{
    private ChainProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new ChainProvider([
            new InMemoryProvider([
                'first' => fn () => true,
            ]),
            new InMemoryProvider([
                'second' => fn () => 42,
            ]),
            new InMemoryProvider([
                'exception' => fn () => throw new \LogicException('Should not be called.'),
            ]),
        ]);
    }

    public function testGet()
    {
        $feature = $this->provider->get('first');

        $this->assertIsCallable($feature);
        $this->assertTrue($feature());
    }

    public function testGetFallback()
    {
        $feature = $this->provider->get('second');

        $this->assertIsCallable($feature);
        $this->assertSame(42, $feature());
    }

    public function testGetLazy()
    {
        $this->assertIsCallable($this->provider->get('exception'));
    }

    public function testGetNotFound()
    {
        $feature = $this->provider->get('unknown');

        $this->assertNull($feature);
    }

    public function testGetNames()
    {
        $this->assertSame(['first', 'second', 'exception'], $this->provider->getNames());
    }
}
