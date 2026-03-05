<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ajgarlag\FeatureFlagBundle\FeatureChecker;
use Ajgarlag\FeatureFlagBundle\FeatureCheckerInterface;
use Ajgarlag\FeatureFlagBundle\Provider\ChainProvider;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use Ajgarlag\FeatureFlagBundle\Provider\ProviderInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('ajgarlag.feature_flag.provider.in_memory', InMemoryProvider::class)
            ->args([
                abstract_arg('Closures collected from "ajgarlag.feature_flag.feature" tag'),
            ])
            ->tag('ajgarlag.feature_flag.provider')

        ->set('ajgarlag.feature_flag.provider', ChainProvider::class)
            ->args([
                tagged_iterator('ajgarlag.feature_flag.provider'),
            ])
            ->alias(ProviderInterface::class, 'ajgarlag.feature_flag.provider')

        ->set('ajgarlag.feature_flag.feature_checker', FeatureChecker::class)
            ->args([
                service('ajgarlag.feature_flag.provider'),
            ])
            ->tag('kernel.reset', ['method' => 'reset'])
            ->alias(FeatureCheckerInterface::class, 'ajgarlag.feature_flag.feature_checker')
    ;
};
