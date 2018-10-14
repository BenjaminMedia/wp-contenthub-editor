<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Textarea extends AbstractField
{
    /** @var string */
    protected $defaultValue;
    /** @var string */
    protected $placeholder;
    /** @var string|int */
    protected $maxLength;
    /** @var string|int */
    protected $rows;
    /** @var string */
    protected $newLines;

    public function __construct(string $key)
    {
        parent::__construct($key, 'textarea');
        $this->defaultValue = '';
        $this->placeholder = '';
        $this->maxLength = '';
        $this->rows = '';
        $this->newLines = '';
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
     * @return Textarea
     */
    public function setDefaultValue(string $defaultValue): Textarea
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
     * @return Textarea
     */
    public function setPlaceholder(string $placeholder): Textarea
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param int|string $maxLength
     * @return Textarea
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int|string $rows
     * @return Textarea
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewLines(): string
    {
        return $this->newLines;
    }

    /**
     * @param string $newLines
     * @return Textarea
     */
    public function setNewLines(string $newLines): Textarea
    {
        $this->newLines = $newLines;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'default_value' => $this->defaultValue,
            'placeholder' => $this->placeholder,
            'maxlength' => $this->maxLength,
            'rows' => $this->rows,
            'new_lines' => $this->newLines,
        ]);
    }
}
