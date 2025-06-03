<?php

/*
 * This file is part of the ajgl/feature-flag-bundle package.
 *
 * It has been borrowed from https://github.com/symfony/symfony/pull/53213.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgarlag\FeatureFlagBundle\Tests\Provider;

use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class InMemoryProviderTests extends TestCase
{
    private InMemoryProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new InMemoryProvider([
            'first' => fn () => true,
            'second' => fn () => 42,
            'exception' => fn () => throw new \LogicException('Should not be called.'),
        ]);
    }

    public function testGet()
    {
        $feature = $this->provider->get('first');
        $this->assertIsCallable($feature);
        $this->assertTrue($feature());

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
