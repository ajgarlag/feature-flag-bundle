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

namespace Ajgarlag\FeatureFlagBundle\Twig\Extension;

use Ajgarlag\FeatureFlagBundle\FeatureCheckerInterface;

final class FeatureFlagRuntime
{
    public function __construct(
        private readonly FeatureCheckerInterface $featureChecker,
    ) {
    }

    public function isEnabled(string $featureName): bool
    {
        return $this->featureChecker->isEnabled($featureName);
    }

    public function getValue(string $featureName): mixed
    {
        return $this->featureChecker->getValue($featureName);
    }
}
