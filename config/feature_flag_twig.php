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

use Ajgarlag\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use Ajgarlag\FeatureFlagBundle\Twig\Extension\FeatureFlagRuntime;

return static function (ContainerConfigurator $container) {
    $container->services()

        ->set('twig.runtime.feature_flag', FeatureFlagRuntime::class)
            ->args([service('feature_flag.feature_checker')])
            ->tag('twig.runtime')

        ->set('twig.extension.feature_flag', FeatureFlagExtension::class)
            ->tag('twig.extension')
    ;
};
