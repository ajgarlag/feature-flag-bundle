<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('ajgarlag.feature_flag.routing_expression_language_function.is_enabled', \Closure::class)
            ->factory([\Closure::class, 'fromCallable'])
            ->args([
                [service('ajgarlag.feature_flag.feature_checker'), 'isEnabled'],
            ])
            ->tag('routing.expression_language_function', ['function' => 'feature_is_enabled'])

        ->set('ajgarlag.feature_flag.routing_expression_language_function.get_value', \Closure::class)
            ->factory([\Closure::class, 'fromCallable'])
            ->args([
                [service('ajgarlag.feature_flag.feature_checker'), 'getValue'],
            ])
            ->tag('routing.expression_language_function', ['function' => 'feature_get_value'])

        ->get('ajgarlag.feature_flag.feature_checker')
            ->tag('routing.condition_service', ['alias' => 'feature'])
    ;
};
