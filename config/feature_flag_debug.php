<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ajgarlag\FeatureFlagBundle\DataCollector\FeatureFlagDataCollector;
use Ajgarlag\FeatureFlagBundle\Debug\TraceableFeatureChecker;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('debug.ajgarlag.feature_flag.feature_checker', TraceableFeatureChecker::class)
            ->decorate('ajgarlag.feature_flag.feature_checker')
            ->args([
                '$decorated' => service('debug.ajgarlag.feature_flag.feature_checker.inner'),
            ])
            ->tag('kernel.reset', ['method' => 'reset'])

        ->set('ajgarlag.feature_flag.data_collector', FeatureFlagDataCollector::class)
            ->args([
                '$provider' => service('ajgarlag.feature_flag.provider'),
                '$featureChecker' => service('debug.ajgarlag.feature_flag.feature_checker'),
            ])
            ->tag('data_collector', ['template' => '@FeatureFlag/Collector/feature_flag.html.twig', 'id' => 'ajgarlag.feature_flag'])
            ->tag('kernel.reset', ['method' => 'reset'])
    ;
};
