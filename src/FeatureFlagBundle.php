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

namespace Ajgarlag\FeatureFlagBundle;

use Ajgarlag\FeatureFlagBundle\DependencyInjection\Compiler\FeatureFlagPass;
use Ajgarlag\FeatureFlagBundle\DependencyInjection\FeatureFlagExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class FeatureFlagBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new FeatureFlagPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $extension = new FeatureFlagExtension();
        $extension->load($config, $builder);
    }
}
