<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class URL extends AbstractField
{
    /** @var string */
    protected $defaultValue;
    /** @var string */
    protected $placeholder;

    public function __construct(string $key)
    {
        parent::__construct($key, 'url');
        $this->defaultValue = '';
        $this->placeholder = '';
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
     * @return URL
     */
    public function setDefaultValue(string $defaultValue): URL
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
     * @return URL
     */
    public function setPlaceholder(string $placeholder): URL
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'default_value' => $this->defaultValue,
            'placeholder' => $this->placeholder,
        ]);
    }
}
