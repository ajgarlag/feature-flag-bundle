UPGRADE FROM 0.1 to 0.2
=======================

* `ProviderInterface::getNames` method has been removed.
* `ChainProvider::getNames` method has been removed.
* `InMemoryProvider::getNames` method has been removed.
* `FeatureFlagDataCollector::getResolved` method has been removed.
* `FeatureFlagDataCollector::getNotResolved` method has been removed.
* Remove implementing `Symfony\Contracts\Service\ResetInterface` from `TraceableFeatureChecker`.
