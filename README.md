FeatureFlag Bundle
==================

The FeatureFlag Bundle allows you to split the code execution flow by
enabling features depending on context.

It provides a service that checks if a feature is enabled. Each feature is
defined by a callable function that returns a value.
The feature is enabled if the value matches the expected one (mostly a boolean
but not limited to).

**This bundle code has been borrowed from https://github.com/symfony/symfony/pull/53213**.

The pourpose of this bundle is to allow you to test the code proposed in the PR.

Getting Started
---------------

```bash
composer require ajgarlag/feature-flag-bundle
```

```php
use Ajgarlag\FeatureFlagBundle\FeatureChecker;
use Ajgarlag\FeatureFlagBundle\Provider\InMemoryProvider;

// Declare features
final class XmasFeature
{
    public function __invoke(): bool
    {
        return date('m-d') === '12-25';
    }
}

$provider = new InMemoryProvider([
    'weekend' => fn() => date('N') >= 6,
    'xmas' => new XmasFeature(), // could be any callable
    'universe' => fn() => 42,
    'random' => fn() => random_int(1, 3),
];

// Create the feature checker
$featureChecker = new FeatureChecker($provider);

// Check if a feature is enabled
$featureChecker->isEnabled('weekend'); // returns true on weekend

// Check a not existing feature
$featureChecker->isEnabled('not_a_feature'); // returns false

// Retrieve a feature value
$featureChecker->getValue('random'); // returns 1, 2 or 3
$featureChecker->getValue('random'); // returns the same value as above
```
