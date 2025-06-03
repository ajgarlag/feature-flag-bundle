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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ajgarlag\FeatureFlagBundle\DataCollector\FeatureFlagDataCollector;
use Ajgarlag\FeatureFlagBundle\Debug\TraceableFeatureChecker;

return static function (ContainerConfigurator $container) {
    $container->services()

        ->set('debug.feature_flag.feature_checker', TraceableFeatureChecker::class)
            ->decorate('feature_flag.feature_checker')
            ->args([
                '$decorated' => service('debug.feature_flag.feature_checker.inner'),
            ])

        ->set('feature_flag.data_collector', FeatureFlagDataCollector::class)
            ->args([
                '$provider' => service('feature_flag.provider'),
                '$featureChecker' => service('debug.feature_flag.feature_checker'),
            ])
            ->tag('data_collector', ['template' => '@FeatureFlag/Collector/feature_flag.html.twig', 'id' => 'feature_flag'])

    ;
};
