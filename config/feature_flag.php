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

use Ajgarlag\FeatureFlagBundle\FeatureChecker;
use Ajgarlag\FeatureFlagBundle\FeatureCheckerInterface;
use Ajgarlag\FeatureFlagBundle\Provider\ChainProvider;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;
use Ajgarlag\FeatureFlagBundle\Provider\ProviderInterface;

return static function (ContainerConfigurator $container) {
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
