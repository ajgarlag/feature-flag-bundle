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
                '$features' => abstract_arg('Defined in FeatureFlagPass.'),
            ])
            ->tag('ajgarlag.feature_flag.provider')

        ->set('ajgarlag.feature_flag.provider', ChainProvider::class)
            ->args([
                '$providers' => tagged_iterator('ajgarlag.feature_flag.provider'),
            ])
            ->alias(ProviderInterface::class, 'ajgarlag.feature_flag.provider')

        ->set('ajgarlag.feature_flag.feature_checker', FeatureChecker::class)
            ->args([
                '$provider' => service('ajgarlag.feature_flag.provider'),
            ])
            ->alias(FeatureCheckerInterface::class, 'ajgarlag.feature_flag.feature_checker')
    ;
};
