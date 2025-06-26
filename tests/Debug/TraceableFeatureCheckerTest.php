<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\Debug;

use Ajgarlag\FeatureFlagBundle\Debug\TraceableFeatureChecker;
use Ajgarlag\FeatureFlagBundle\FeatureChecker;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class TraceableFeatureCheckerTest extends TestCase
{
    public function testTraces()
    {
        $featureChecker = new FeatureChecker(new InMemoryProvider([
            'feature_true' => fn () => true,
            'feature_false' => fn () => false,
            'feature_integer' => fn () => 42,
            'feature_random' => fn () => random_int(1, 42),
        ]));
        $traceableFeatureChecker = new TraceableFeatureChecker($featureChecker);

        $this->assertTrue($traceableFeatureChecker->isEnabled('feature_true'));
        $this->assertFalse($traceableFeatureChecker->isEnabled('feature_false'));
        $this->assertSame(42, $traceableFeatureChecker->getValue('feature_integer'));
        $this->assertSame(42, $traceableFeatureChecker->getValue('feature_integer'));

        $this->assertSame(
            [
                'feature_true' => ['status' => 'enabled', 'value' => true, 'calls' => 1],
                'feature_false' => ['status' => 'disabled', 'value' => false, 'calls' => 1],
                'feature_integer' => ['status' => 'resolved', 'value' => 42, 'calls' => 2],
            ],
            $traceableFeatureChecker->getResolvedValues(),
        );
    }
}
