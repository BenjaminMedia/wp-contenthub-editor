<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Radio extends AbstractField
{
    /** @var array */
    protected $choices;
    /** @var int */
    protected $allowNull;
    /** @var int */
    protected $otherChoice;
    /** @var int */
    protected $saveOtherChoice;
    /** @var string */
    protected $defaultValue;
    /** @var string */
    protected $layout;
    /** @var string */
    protected $returnFormat;

    public function __construct(string $key)
    {
        parent::__construct($key, 'radio');
        $this->allowNull = 0;
        $this->otherChoice = 0;
        $this->saveOtherChoice = 0;
        $this->defaultValue = '';
        $this->layout = 'vertical';
        $this->returnFormat = 'value';
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
     * @return Radio
     */
    public function setChoices(array $choices): Radio
    {
        $this->choices = $choices;
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
     * @return Radio
     */
    public function setAllowNull(int $allowNull): Radio
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @return int
     */
    public function getOtherChoice(): int
    {
        return $this->otherChoice;
    }

    /**
     * @param int $otherChoice
     * @return Radio
     */
    public function setOtherChoice(int $otherChoice): Radio
    {
        $this->otherChoice = $otherChoice;
        return $this;
    }

    /**
     * @return int
     */
    public function getSaveOtherChoice(): int
    {
        return $this->saveOtherChoice;
    }

    /**
     * @param int $saveOtherChoice
     * @return Radio
     */
    public function setSaveOtherChoice(int $saveOtherChoice): Radio
    {
        $this->saveOtherChoice = $saveOtherChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     * @return Radio
     */
    public function setDefaultValue(string $defaultValue): Radio
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return Radio
     */
    public function setLayout(string $layout): Radio
    {
        $this->layout = $layout;
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
     * @return Radio
     */
    public function setReturnFormat(string $returnFormat): Radio
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'choices' => $this->choices,
            'allow_null' => $this->allowNull,
            'other_choice' => $this->otherChoice,
            'save_other_choice' => $this->saveOtherChoice,
            'default_value' => $this->defaultValue,
            'layout' => $this->layout,
            'return_format' => $this->returnFormat,
        ]);
    }
}
