<?php

namespace Ajgarlag\FeatureFlagBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class FeatureFlagPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('ajgarlag.feature_flag.feature_checker')) {
            return;
        }

        $features = [];
        foreach ($container->findTaggedServiceIds('ajgarlag.feature_flag.feature') as $serviceId => $tags) {
            $className = $this->getServiceClass($container, $serviceId);
            $r = $container->getReflectionClass($className);

            if (null === $r) {
                throw new RuntimeException(\sprintf('Invalid service "%s": class "%s" does not exist.', $serviceId, $className));
            }

            foreach ($tags as $tag) {
                $featureName = ($tag['feature'] ?? '') ?: $className;
                if (\array_key_exists($featureName, $features)) {
                    throw new RuntimeException(\sprintf('Feature "%s" already defined in the "ajgarlag.feature_flag.provider.in_memory" provider.', $featureName));
                }

                $method = $tag['method'] ?? '__invoke';
                if (!$r->hasMethod($method)) {
                    throw new RuntimeException(\sprintf('Invalid feature method "%s": method "%s::%s()" does not exist.', $serviceId, $r->getName(), $method));
                }
                if (!$r->getMethod($method)->isPublic()) {
                    throw new RuntimeException(\sprintf('Invalid feature method "%s": method "%s::%s()" must be public.', $serviceId, $r->getName(), $method));
                }

                $features[$featureName] = (new Definition(\Closure::class))
                    ->setLazy(true)
                    ->setFactory([\Closure::class, 'fromCallable'])
                    ->setArguments([[new Reference($serviceId), $method]]);
            }
        }

        $container->getDefinition('ajgarlag.feature_flag.provider.in_memory')
            ->setArgument('$features', $features)
        ;

        if ($container->hasDefinition('profiler')) {
            $this->loadDebugDefinitions($container);
        }
    }

    private function getServiceClass(ContainerBuilder $container, string $serviceId): ?string
    {
        while (true) {
            $definition = $container->findDefinition($serviceId);

            if (!$definition->getClass() && $definition instanceof ChildDefinition) {
                $serviceId = $definition->getParent();

                continue;
            }

            return $container->getParameterBag()->resolveValue($definition->getClass());
        }
    }

    private function loadDebugDefinitions(ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/../../config'));

        $loader->load('feature_flag_debug.php');
    }
}
