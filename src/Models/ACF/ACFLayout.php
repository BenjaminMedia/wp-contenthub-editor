<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF;

use Illuminate\Support\Collection;

class ACFLayout
{
    /** @var string */
    protected $key;
    /** @var string */
    protected $name;
    /** @var string */
    protected $label;
    /** @var string */
    protected $display;
    /** @var Collection */
    protected $subFields;
    /** @var string */
    protected $min;
    /** @var string */
    protected $max;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->subFields = new Collection();
        $this->display = 'block';
        $this->min = '';
        $this->max = '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ACFLayout
     */
    public function setName(string $name): ACFLayout
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return ACFLayout
     */
    public function setLabel(string $label): ACFLayout
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplay(): string
    {
        return $this->display;
    }

    /**
     * @param string $display
     * @return ACFLayout
     */
    public function setDisplay(string $display): ACFLayout
    {
        $this->display = $display;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSubFields(): Collection
    {
        return $this->subFields;
    }

    /**
     * @param Collection $subFields
     * @return ACFLayout
     */
    public function setSubFields(Collection $subFields): ACFLayout
    {
        $this->subFields = $subFields;
        return $this;
    }

    public function addSubField(array $field): ACFLayout
    {
        $this->subFields->push($field);
        return $this;
    }

    /**
     * @return string
     */
    public function getMin(): string
    {
        return $this->min;
    }

    /**
     * @param string $min
     * @return ACFLayout
     */
    public function setMin(string $min): ACFLayout
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return string
     */
    public function getMax(): string
    {
        return $this->max;
    }

    /**
     * @param string $max
     * @return ACFLayout
     */
    public function setMax(string $max): ACFLayout
    {
        $this->max = $max;
        return $this;
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'label' => $this->label,
            'display' => $this->display,
            'sub_fields' => $this->subFields->toArray(),
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
