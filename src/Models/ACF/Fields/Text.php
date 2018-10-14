<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Text extends AbstractField
{
    /** @var string */
    protected $defaultValue;
    /** @var string */
    protected $placeholder;
    /** @var string */
    protected $prepend;
    /** @var string */
    protected $append;
    /** @var string|int */
    protected $maxLength;
    /** @var int */
    protected $readOnly;
    /** @var int */
    protected $disabled;

    public function __construct(string $key)
    {
        parent::__construct($key, 'text');
        $this->defaultValue = '';
        $this->placeholder = '';
        $this->prepend = '';
        $this->append = '';
        $this->maxLength = '';
        $this->readOnly = 0;
        $this->disabled = 0;
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
     * @return Text
     */
    public function setDefaultValue(string $defaultValue): Text
    {
        $this->defaultValue = $defaultValue;
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
     * @return Text
     */
    public function setPlaceholder(string $placeholder): Text
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrepend(): string
    {
        return $this->prepend;
    }

    /**
     * @param string $prepend
     * @return Text
     */
    public function setPrepend(string $prepend): Text
    {
        $this->prepend = $prepend;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppend(): string
    {
        return $this->append;
    }

    /**
     * @param string $append
     * @return Text
     */
    public function setAppend(string $append): Text
    {
        $this->append = $append;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaxLength(): string
    {
        return $this->maxLength;
    }

    /**
     * @param string $maxLength
     * @return Text
     */
    public function setMaxLength(string $maxLength): Text
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getReadOnly(): int
    {
        return $this->readOnly;
    }

    /**
     * @param int $readOnly
     * @return Text
     */
    public function setReadOnly(int $readOnly): Text
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisabled(): int
    {
        return $this->disabled;
    }

    /**
     * @param int $disabled
     * @return Text
     */
    public function setDisabled(int $disabled): Text
    {
        $this->disabled = $disabled;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'default_value' => $this->defaultValue,
            'placeholder' => $this->placeholder,
            'prepend' => $this->prepend,
            'append' => $this->append,
            'maxlength' => $this->maxLength,
            'readonly' => $this->readOnly,
            'disabled' => $this->disabled
        ]);
    }
}
