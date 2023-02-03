<?php

declare(strict_types=1);

namespace IkonizerCore\FormBuilder\Type;

use IkonizerCore\FormBuilder\FormExtensionTypeInterface;

class ColorType extends InputType implements FormExtensionTypeInterface
{

    /** @var string - this is the text type extension */
    protected string $type = 'color';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param mixed $options
     * @param array $settings
     */
    public function __construct(array $fields, $options = null, array $settings = [])
    {
        /* Assigned arguments to parent InputType constructor */
        parent::__construct($fields, $options, $settings);
    }

    /**
     * @inheritdoc
     *
     * @param array $options
     * @return void
     */
    public function configureOptions(array $options = []): void
    {
        $this->defaults = [ /* No current defaults available */
        ];

        parent::configureOptions($this->defaults);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getExtensionDefaults() : array
    {
        return $this->defaults;
    }

    /**
     * Publicize the default object options to the base class
     *
     * @return array
     */
    public function getOptions() : array
    {
        return parent::getOptions();
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper
     *
     * @return array
     */
    public function getSettings() : array
    {
        return parent::getSettings();
    }


}