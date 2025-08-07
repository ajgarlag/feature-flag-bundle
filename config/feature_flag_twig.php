<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ajgarlag\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use Ajgarlag\FeatureFlagBundle\Twig\Extension\FeatureFlagRuntime;

return static function (ContainerConfigurator $container) {
    $container->services()

        ->set('twig.runtime.ajgarlag.feature_flag', FeatureFlagRuntime::class)
            ->args([service('ajgarlag.feature_flag.feature_checker')])
            ->tag('twig.runtime')

        ->set('twig.extension.ajgarlag.feature_flag', FeatureFlagExtension::class)
            ->tag('twig.extension')
    ;
};
