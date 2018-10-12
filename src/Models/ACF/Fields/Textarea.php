<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Textarea extends AbstractField
{
    protected $defaultValue;
    protected $placeholder;
    protected $maxLength;
    protected $rows;
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
     * @return string
     */
    public function getMaxLength(): string
    {
        return $this->maxLength;
    }

    /**
     * @param string $maxLength
     * @return Textarea
     */
    public function setMaxLength(string $maxLength): Textarea
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return string
     */
    public function getRows(): string
    {
        return $this->rows;
    }

    /**
     * @param string $rows
     * @return Textarea
     */
    public function setRows(string $rows): Textarea
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
