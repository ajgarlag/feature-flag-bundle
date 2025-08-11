# FeatureFlag Bundle

The FeatureFlag Bundle allows you to split the code execution flow by enabling features depending on context.

It provides a service that checks if a feature is enabled. Each feature is defined by a callable function that returns a
value.
The feature is enabled if the value matches the expected one (mostly a boolean but not limited to).

**This bundle code has been borrowed from https://github.com/symfony/symfony/pull/53213**.

> [!IMPORTANT]  
> The purpose of this bundle is to allow you to test the code proposed in the PR.

## 🚀 Getting Started

Follow these steps to install and use the bundle in your Symfony application.

### Step 1: Download the bundle

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this bundle:

```bash
composer require ajgarlag/feature-flag-bundle
```

### Step 2: Enable the bundle (for non-Flex applications)

Then, enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Ajgarlag\FeatureFlagBundle\FeatureFlagBundle::class => ['all' => true],
];
```

## ✨ Declaring features with attributes

You can declare features using the `#[AsFeature]` attribute. This allows you to autoconfigure your features as services.

### On a class

You can use the attribute on an invokable class:

```php
namespace App\Feature;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature('xmas')]
final class XmasFeature
{
    public function __invoke(): bool
    {
        return date('m-d') === '12-25';
    }
}
```

The feature will be named `xmas`. If you don't provide a name, the FQCN of the class will be used.

You can also use the `method` property of the attribute to specify a method to call on the service.

```php
namespace App\Feature;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

#[AsFeature('xmas', method: 'isXmas')]
final class XmasFeature
{
    public function isXmas(): bool
    {
        return date('m-d') === '12-25';
    }
}
```

### On a method

You can also use the attribute on a method of a service. The method must be public.

```php
namespace App\Feature;

use Ajgarlag\FeatureFlagBundle\Attribute\AsFeature;

final class FeatureService
{
    #[AsFeature('weekend')]
    public function isWeekend(): bool
    {
        return date('N') >= 6;
    }

    #[AsFeature] // The feature will be named "App\Feature\FeatureService::isAnotherFeature"
    public function isAnotherFeature(): bool
    {
        return true;
    }
}
```

## 🗺️ Routing integration

The bundle provides two functions to use in route conditions: `feature_is_enabled` and `feature_get_value`.

You can use them to enable or disable routes based on features.

```php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;

class SomeController
{
    #[Route('/some/path', condition: "feature_is_enabled('some_feature')")]
    public function index()
    {
        // ...
    }
}
```

You can also use `feature_get_value` to check for a specific value.

```php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;

class SomeController
{
    #[Route('/some/path', condition: "feature_get_value('some_feature') == 'some_value'")]
    public function index()
    {
        // ...
    }
}
```

## 🧩 Providers

Providers are responsible for returning the feature closures. You can create your own provider by implementing the
`ProviderInterface`.

Any service that implements `ProviderInterface` is automatically registered as a provider. The bundle comes with a
`ChainProvider` that allows you to combine multiple providers. The first provider that returns a feature wins.

### The ProviderInterface

To create your own provider, you need to implement the `ProviderInterface`.

```php
namespace App\FeatureProvider;

use Ajgarlag\FeatureFlagBundle\Provider\ProviderInterface;

class MyProvider implements ProviderInterface
{
    public function get(string $featureName): ?\Closure
    {
        // ...
    }

    public function getNames(): array
    {
        // ...
    }
}
```

The `get` method must return a `\Closure` if the provider has the feature, or `null` otherwise.

The `getNames` method should return an array of all feature names provided by this provider.

<details>
<summary>Doctrine example</summary>

```php
namespace App\FeatureProvider;

use App\Repository\FeatureAssignmentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\FeatureFlag\Provider\ProviderInterface;

final class DoctrineProvider implements ProviderInterface
{
    public function __construct(
        private readonly FeatureAssignmentRepository $featureAssignementRepository,
    ) {
    }

    public function get(string $featureName): ?\Closure
    {
        // Set context. Example: user identifier, IP, hostname, etc. 
        $context = [];
        
        return function () use ($featureName) {
            return $this->featureAssignementRepository->featureIsEnabled($featureName, $context);
        };
    }

    public function getNames(): array
    {
        return $this->featureAssignementRepository->featureNames();
    }
}
```

</details>

<details>
<summary>Gitlab example</summary>

First, declare a service to interact with the [Unleash](https://www.getunleash.io/) API.

```php
// config/services.php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Cache\Psr16Cache;
use Unleash\Client\Unleash;
use Unleash\Client\UnleashBuilder;

return function(ContainerConfigurator $container): void {
    
    // Application service definition

    $services->set('gitlab.client_factory')
        ->class(UnleashBuilder::class)
        ->factory([UnleashBuilder::class, 'createForGitlab'])
        ->call('withGitlabEnvironment', [env('GITLAB_ENVIRONMENT')], true)
        ->call('withAppUrl', [env('GITLAB_URL')], true)
        ->call('withInstanceId', [env('GITLAB_INSTANCE_ID')], true)
        ->call('withHttpClient', [service('psr18.http_client')], true)
        // Using a cache is recommended to limit API calls (named "cache.unleash" in this example)
        ->call('withCacheHandler', [inline_service(Psr16Cache::class)->args([service('cache.unleash')])], true)
    ;

    $services->set('gitlab.client')
        ->class(Unleash::class)
        ->factory([service('gitlab.client_factory'), 'build'])
    ;
};
```

```php
namespace App\FeatureProvider;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\FeatureFlag\Provider\ProviderInterface;
use Unleash\Client\Configuration\Context;
use Unleash\Client\Configuration\UnleashContext;
use Unleash\Client\Unleash;

class GitlabProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'gitlab.client')] private readonly Unleash $unleash,
        private readonly Security $security,
    ) {
    }

    public function get(string $featureName): ?\Closure
    {
        // Set context. Example: user identifier, IP, hostname, etc. 
        $context = new UnleashContext(
            currentUserId: $this->security->getUser()?->getUserIdentifier()
        );
        
        return fn () => $this->unleash->isEnabled($featureName, $context);
    }

    public function getNames(): array
    {
        return [];
    }
}
```

</details>

### Priority

You can control the order of the providers in the chain using the `priority` attribute on the
`ajgarlag.feature_flag.provider` tag.

You can use the `#[AutoconfigureTag]` attribute to set the priority of your provider.

```php
namespace App\FeatureProvider;

use Ajgarlag\FeatureFlagBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('ajgarlag.feature_flag.provider', ['priority' => 10])]
class MyProvider implements ProviderInterface
{
    // ...
}
```

Providers with a higher priority will be checked first.

## 🎨 Twig extension

The bundle provides two functions to use in Twig templates: `feature_is_enabled` and `feature_get_value`.

```twig
{% if feature_is_enabled('some_feature') %}
    {# ... #}
{% endif %}

{% if feature_get_value('some_feature') == 'some_value' %}
    {# ... #}
{% endif %}
```
