<?php

namespace JoshuaEstes\Component\FeatureToggle\Toggle;

use JoshuaEstes\Component\FeatureToggle\FeatureInterface;

/**
 * Used to create composite toggles of multiple child toggles.
 * 
 * @author Anthony Vanden Bossche <toonevdb@gmail.com>
 */
class FeatureToggleComposite implements FeatureToggleInterface
{
    /**
     * Feature toggles array.
     *
     * @var array
     */
    protected $toggles;

    /**
     * Determines whether all or any of the child toggles must be enabled.
     */
    protected $requireAllEnabled = true;
    
    /**
     * Constructor.
     *
     * @param array $toggles Array of child toggles
     */
    public function __construct(array $toggles)
    {
        foreach ($toggles as $toggle) {
            if (!$toggle instanceof FeatureToggleInterface) {
                throw new \Exception('A FeatureToggleComposite class can only be constructed with an array of objects implementing the FeatureToggleInterface');
            }
        }

        $this->toggles = $toggles;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled(FeatureInterface $feature)
    {
        foreach ($this->toggles as $toggle) {
            if ($this->requireAllEnabled && !$toggle->isEnabled($feature)) {
                return false;
            } elseif (!$this->requireAllEnabled && $toggle->isEnabled($feature)) {
                return true;
            }
        }

        return $this->requireAllEnabled;
    }

    /**
     * Set the flag that determines if any or all child toggles must be enabled.
     *
     * @param bool $value Flag value
     */
    public function setRequireAllEnabled(bool $value)
    {
        $this->requireAllEnabled = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        $data = [
            'toggles'           => $this->toggles,
            'requireAllEnabled' => $this->requireAllEnabled,
        ];
        
        return serialize($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($data)
    {
        $unserialized = unserialize($data);

        $this->toggles = $unserialized['toggles'];
        $this->requireAllEnabled = $unserialized['requireAllEnabled'];
    }
}
