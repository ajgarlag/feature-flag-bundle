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

namespace Ajgarlag\FeatureFlagBundle\DependencyInjection;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;
use Ajgarlag\FeatureFlagBundle\Provider\ProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\Router;
use Twig\Environment;

class FeatureFlagExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/../config'));

        $loader->load('feature_flag.php');

        $container->registerForAutoconfiguration(ProviderInterface::class)
            ->addTag('ajgarlag.feature_flag.provider')
        ;

        $container->registerAttributeForAutoconfiguration(
            AsFeature::class,
            static function (ChildDefinition $definition, AsFeature $attribute, \ReflectionClass|\ReflectionMethod $reflector): void {
                $featureName = $attribute->name;

                if ($reflector instanceof \ReflectionClass) {
                    $className = $reflector->getName();
                    $method = $attribute->method;

                    $featureName ??= $className;
                } else {
                    $className = $reflector->getDeclaringClass()->getName();
                    if (null !== $attribute->method && $reflector->getName() !== $attribute->method) {
                        throw new \LogicException(\sprintf('Using the #[%s(method: "%s")] attribute on a method is not valid. Either remove the method value or move this to the top of the class (%s).', AsFeature::class, $attribute->method, $className));
                    }

                    $method = $reflector->getName();
                    $featureName ??= "{$className}::{$method}";
                }

                $definition->addTag('ajgarlag.feature_flag.feature', [
                    'feature' => $featureName,
                    'method' => $method,
                ]);
            },
        );

        if (ContainerBuilder::willBeAvailable('symfony/routing', Router::class, ['symfony/framework-bundle', 'symfony/routing'])) {
            $loader->load('feature_flag_routing.php');
        }

        if (ContainerBuilder::willBeAvailable('symfony/twig', Environment::class, ['symfony/framework-bundle', 'symfony/twig'])) {
            $loader->load('feature_flag_twig.php');
        }
    }
}
