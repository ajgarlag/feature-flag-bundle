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

namespace Ajgarlag\FeatureFlagBundle\Tests\Functional;

use Ajgarlag\FeatureFlagBundle\FeatureCheckerInterface;
use Ajgarlag\FeatureFlagBundle\Tests\Fixtures\ClassFeature;
use Ajgarlag\FeatureFlagBundle\Tests\Fixtures\ClassMethodFeature;
use Ajgarlag\FeatureFlagBundle\Tests\Fixtures\NamedFeature;
use Ajgarlag\FeatureFlagBundle\Tests\Functional\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\AbstractWebTestCase;

class FeatureFlagTest extends AbstractWebTestCase
{
    protected static function getKernelClass(): string
    {
        require_once __DIR__.'/app/AppKernel.php';

        return AppKernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::deleteTmpDir();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn () => null);

            restore_exception_handler();

            if (null === $previousHandler) {
                break;
            }

            restore_exception_handler();
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    public function testFeatureFlagAssertions()
    {
        static::bootKernel(['test_case' => 'FeatureFlag', 'root_config' => 'config.yml']);
        /** @var FeatureCheckerInterface $featureChecker */
        $featureChecker = static::getContainer()->get('feature_flag.feature_checker');

        // With default behavior
        $this->assertTrue($featureChecker->isEnabled(ClassFeature::class));
        $this->assertTrue($featureChecker->isEnabled(ClassMethodFeature::class));

        // With a custom name
        $this->assertTrue($featureChecker->isEnabled('custom_name'));
        $this->assertFalse($featureChecker->isEnabled(NamedFeature::class));

        // With an unknown feature
        $this->assertFalse($featureChecker->isEnabled('unknown'));

        // Get values
        $this->assertSame('green', $featureChecker->getValue('method_string'));
        $this->assertSame(42, $featureChecker->getValue('method_int'));
    }

    public function testFeatureFlagAssertionsWithInvalidMethod()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid feature method "Ajgarlag\FeatureFlagBundle\Tests\Fixtures\InvalidMethodFeature": method "Ajgarlag\FeatureFlagBundle\Tests\Fixtures\InvalidMethodFeature::invalid_method()" does not exist.');

        static::bootKernel(['test_case' => 'FeatureFlag', 'root_config' => 'config_with_invalid_method.yml']);
    }

    public function testFeatureFlagAssertionsWithInvalidMethodVisibility()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid feature method "Ajgarlag\FeatureFlagBundle\Tests\Fixtures\InvalidMethodVisibilityFeature": method "Ajgarlag\FeatureFlagBundle\Tests\Fixtures\InvalidMethodVisibilityFeature::resolve()" must be public.');

        static::bootKernel(['test_case' => 'FeatureFlag', 'root_config' => 'config_with_invalid_method_visibility.yml']);
    }

    public function testFeatureFlagAssertionsWithDifferentMethod()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Using the #[Ajgarlag\FeatureFlagBundle\Attribute\AsFeature(method: "different")] attribute on a method is not valid. Either remove the method value or move this to the top of the class (Ajgarlag\FeatureFlagBundle\Tests\Fixtures\DifferentMethodFeature).');

        static::bootKernel(['test_case' => 'FeatureFlag', 'root_config' => 'config_with_different_method.yml']);
    }

    public function testFeatureFlagAssertionsWithDuplicate()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Feature "Ajgarlag\FeatureFlagBundle\Tests\Fixtures\ClassFeature" already defined in the "feature_flag.provider.in_memory" provider.');

        static::bootKernel(['test_case' => 'FeatureFlag', 'root_config' => 'config_with_duplicate.yml']);
    }
}
