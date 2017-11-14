<?php

use JoshuaEstes\Component\FeatureToggle\FeatureInterface;
use JoshuaEstes\Component\FeatureToggle\Toggle\FeatureToggleGeneric;
use JoshuaEstes\Component\FeatureToggle\Toggle\FeatureToggleComposite;

class FeatureToggleCompositeTest extends \PHPUnit_Framework_TestCase
{
    public function testRequireAllEnabledTrue()
    {
        $toggle = new FeatureToggleGeneric(['enabled' => true]);
        $toggle2 = new FeatureToggleGeneric(['enabled' => true]);

        $composite = new FeatureToggleComposite([$toggle, $toggle2]);

        $feature = $this->getMockBuilder(FeatureInterface::class)
            ->getMock();

        $this->assertTrue($composite->isEnabled($feature));
    }

    public function testRequireAnyEnabledTrue()
    {
        $toggle = new FeatureToggleGeneric(['enabled' => true]);
        $toggle2 = new FeatureToggleGeneric(['enabled' => false]);

        $composite = new FeatureToggleComposite([$toggle, $toggle2]);
        $composite->setRequireAllEnabled(false);

        $feature = $this->getMockBuilder(FeatureInterface::class)
            ->getMock();

        $this->assertTrue($composite->isEnabled($feature));
    }

    public function testRequireAllEnabledFalse()
    {
        $toggle = new FeatureToggleGeneric(['enabled' => true]);
        $toggle2 = new FeatureToggleGeneric(['enabled' => false]);

        $composite = new FeatureToggleComposite([$toggle, $toggle2]);

        $feature = $this->getMockBuilder(FeatureInterface::class)
            ->getMock();

        $this->assertFalse($composite->isEnabled($feature));
    }

    public function testRequireAnyEnabledFalse()
    {
        $toggle = new FeatureToggleGeneric(['enabled' => false]);
        $toggle2 = new FeatureToggleGeneric(['enabled' => false]);

        $composite = new FeatureToggleComposite([$toggle, $toggle2]);
        $composite->setRequireAllEnabled(false);

        $feature = $this->getMockBuilder(FeatureInterface::class)
            ->getMock();

        $this->assertFalse($composite->isEnabled($feature));
    }
}
