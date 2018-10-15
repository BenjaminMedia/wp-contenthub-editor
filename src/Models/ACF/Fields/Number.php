<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Number extends AbstractField
{
    /** @var int */
    protected $defaultValue;
    /** @var string|int */
    protected $placeholder;
    /** @var string|int */
    protected $prepend;
    /** @var string|int */
    protected $append;
    /** @var string|int */
    protected $min;
    /** @var string|int */
    protected $max;
    /** @var string|int */
    protected $step;

    public function __construct(string $key)
    {
        parent::__construct($key, 'number');
        $this->defaultValue = 0;
        $this->placeholder = '';
        $this->prepend = '';
        $this->append = '';
        $this->min = '';
        $this->max = '';
        $this->step = '';
    }

    /**
     * @return int
     */
    public function getDefaultValue(): int
    {
        return $this->defaultValue;
    }

    /**
     * @param int $defaultValue
     * @return Number
     */
    public function setDefaultValue(int $defaultValue): Number
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param int|string $placeholder
     * @return Number
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getPrepend()
    {
        return $this->prepend;
    }

    /**
     * @param int|string $prepend
     * @return Number
     */
    public function setPrepend($prepend)
    {
        $this->prepend = $prepend;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getAppend()
    {
        return $this->append;
    }

    /**
     * @param int|string $append
     * @return Number
     */
    public function setAppend($append)
    {
        $this->append = $append;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int|string $min
     * @return Number
     */
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int|string $max
     * @return Number
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int|string $step
     * @return Number
     */
    public function setStep($step)
    {
        $this->step = $step;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'default_value' => $this->defaultValue,
            'placeholder' => $this->placeholder,
            'prepend' => $this->prepend,
            'append' => $this->append,
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
        ]);
    }
}
