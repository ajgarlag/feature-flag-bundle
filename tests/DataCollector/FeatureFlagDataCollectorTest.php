<?php

namespace Ajgarlag\FeatureFlagBundle\Tests\DataCollector;

use Ajgarlag\FeatureFlagBundle\DataCollector\FeatureFlagDataCollector;
use Ajgarlag\FeatureFlagBundle\Debug\TraceableFeatureChecker;
use Ajgarlag\FeatureFlagBundle\FeatureChecker;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use PHPUnit\Framework\TestCase;

class FeatureFlagDataCollectorTest extends TestCase
{
    public function testLateCollect()
    {
        $featureRegistry = new InMemoryProvider([
            'feature_true' => static fn () => true,
            'feature_false' => static fn () => false,
            'feature_integer' => static fn () => 42,
            'feature_random' => static fn () => random_int(1, 42),
        ]);
        $traceableFeatureChecker = new TraceableFeatureChecker(new FeatureChecker($featureRegistry));
        $dataCollector = new FeatureFlagDataCollector($featureRegistry, $traceableFeatureChecker);

        $traceableFeatureChecker->isEnabled('feature_true');
        $traceableFeatureChecker->isEnabled('feature_false');
        $traceableFeatureChecker->isEnabled('feature_unknown');
        $traceableFeatureChecker->getValue('feature_integer');
        $traceableFeatureChecker->getValue('feature_integer');

        $this->assertSame([], $dataCollector->getFeatures());

        $dataCollector->lateCollect();

        $data = array_map(
            static function (array $a): array {
                $a['value'] = $a['value']->getValue();

                return $a;
            },
            $dataCollector->getFeatures(),
        );
        $this->assertSame(
            [
                'feature_true' => [
                    'status' => 'enabled',
                    'value' => true,
                    'calls' => 1,
                ],
                'feature_false' => [
                    'status' => 'disabled',
                    'value' => false,
                    'calls' => 1,
                ],
                'feature_unknown' => [
                    'status' => 'not_found',
                    'value' => false,
                    'calls' => 1,
                ],
                'feature_integer' => [
                    'status' => 'resolved',
                    'value' => 42,
                    'calls' => 2,
                ],
            ],
            $data,
        );
    }
}
