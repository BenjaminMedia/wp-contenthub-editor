<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Select extends AbstractField
{
    /** @var array */
    protected $choices;
    /** @var array */
    protected $defaultValue;
    /** @var int */
    protected $allowNull;
    /** @var int */
    protected $multiple;
    /** @var int */
    protected $userInterface;
    /** @var int */
    protected $ajax;
    /** @var string */
    protected $returnFormat;
    /** @var string */
    protected $placeholder;

    public function __construct(string $key)
    {
        parent::__construct($key, 'select');
        $this->defaultValue = [];
        $this->allowNull = 0;
        $this->multiple = 0;
        $this->userInterface = 0;
        $this->ajax = 0;
        $this->returnFormat = 'value';
        $this->placeholder = '';
    }

    /**
     * @return array
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    /**
     * @param array $choices
     * @return Select
     */
    public function setChoices(array $choices): Select
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param array $defaultValue
     * @return Select
     */
    public function setDefaultValue(array $defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowNull(): int
    {
        return $this->allowNull;
    }

    /**
     * @param int $allowNull
     * @return Select
     */
    public function setAllowNull(int $allowNull): Select
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiple(): int
    {
        return $this->multiple;
    }

    /**
     * @param int $multiple
     * @return Select
     */
    public function setMultiple(int $multiple): Select
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserInterface(): int
    {
        return $this->userInterface;
    }

    /**
     * @param int $userInterface
     * @return Select
     */
    public function setUserInterface(int $userInterface): Select
    {
        $this->userInterface = $userInterface;
        return $this;
    }

    /**
     * @return int
     */
    public function getAjax(): int
    {
        return $this->ajax;
    }

    /**
     * @param int $ajax
     * @return Select
     */
    public function setAjax(int $ajax): Select
    {
        $this->ajax = $ajax;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnFormat(): string
    {
        return $this->returnFormat;
    }

    /**
     * @param string $returnFormat
     * @return Select
     */
    public function setReturnFormat(string $returnFormat): Select
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     * @return Select
     */
    public function setPlaceholder(string $placeholder): Select
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'choices' => $this->choices,
            'default_value' => $this->defaultValue,
            'allow_null' => $this->allowNull,
            'multiple' => $this->multiple,
            'ui' => $this->userInterface,
            'ajax' => $this->ajax,
            'return_format' => $this->returnFormat,
            'placeholder' => $this->placeholder,
        ]);
    }
}
