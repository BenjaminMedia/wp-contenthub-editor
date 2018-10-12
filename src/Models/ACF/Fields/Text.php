<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Text extends AbstractField
{
    protected $defaultValue;
    protected $placeholder;
    protected $prepend;
    protected $append;
    protected $maxLength;

    public function __construct(string $key)
    {
        parent::__construct($key, 'text');
        $this->defaultValue = '';
        $this->placeholder = '';
        $this->prepend = '';
        $this->append = '';
        $this->maxLength = '';
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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'default_value' => $this->defaultValue,
            'placeholder' => $this->placeholder,
            'prepend' => $this->prepend,
            'append' => $this->append,
            'maxlength' => $this->maxLength,
        ]);
    }
}
