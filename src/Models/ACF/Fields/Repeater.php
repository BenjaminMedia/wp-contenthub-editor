<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

use Illuminate\Support\Collection;

class Repeater extends AbstractField
{
    /** @var string */
    protected $collapsed;
    /** @var int */
    protected $min;
    /** @var int */
    protected $max;
    /** @var string */
    protected $layout;
    /** @var string */
    protected $buttonLabel;
    /** @var Collection */
    protected $subFields;

    public function __construct(string $key)
    {
        parent::__construct($key, 'repeater');
        $this->collapsed = '';
        $this->min = 0;
        $this->max = 0;
        $this->layout = 'table';
        $this->buttonLabel = '';
        $this->subFields = new Collection();
    }

    /**
     * @return string
     */
    public function getCollapsed(): string
    {
        return $this->collapsed;
    }

    /**
     * @param string $collapsed
     * @return Repeater
     */
    public function setCollapsed(string $collapsed): Repeater
    {
        $this->collapsed = $collapsed;
        return $this;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     * @return Repeater
     */
    public function setMin(int $min): Repeater
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     * @return Repeater
     */
    public function setMax(int $max): Repeater
    {
        $this->max = $max;
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
     * @return Repeater
     */
    public function setLayout(string $layout): Repeater
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function getButtonLabel(): string
    {
        return $this->buttonLabel;
    }

    /**
     * @param string $buttonLabel
     * @return Repeater
     */
    public function setButtonLabel(string $buttonLabel): Repeater
    {
        $this->buttonLabel = $buttonLabel;
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
     * @return Repeater
     */
    public function setSubFields(Collection $subFields): Repeater
    {
        $this->subFields = $subFields;
        return $this;
    }

    /**
     * @param array $field
     * @return Repeater
     */
    public function addSubField(array $field): Repeater
    {
        $this->subFields->push($field);
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'collapsed' => $this->collapsed,
            'min' => $this->min,
            'max' => $this->max,
            'layout' => $this->layout,
            'button_label' => $this->buttonLabel,
            'sub_fields' => $this->subFields->toArray(),
        ]);
    }
}
