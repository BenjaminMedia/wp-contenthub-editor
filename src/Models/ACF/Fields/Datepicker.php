<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Datepicker extends AbstractField
{
    /** @var string */
    protected $displayFormat;
    /** @var string */
    protected $returnFormat;
    /** @var int */
    protected $firstDay;

    public function __construct(string $key)
    {
        parent::__construct($key, 'date_picker');
        $this->displayFormat = 'F j, Y';
        $this->returnFormat = 'Y-m-d';
        $this->firstDay = 0;
    }

    /**
     * @return string
     */
    public function getDisplayFormat(): string
    {
        return $this->displayFormat;
    }

    /**
     * @param string $displayFormat
     * @return Datepicker
     */
    public function setDisplayFormat(string $displayFormat): Datepicker
    {
        $this->displayFormat = $displayFormat;
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
     * @return Datepicker
     */
    public function setReturnFormat(string $returnFormat): Datepicker
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    /**
     * @return int
     */
    public function getFirstDay(): int
    {
        return $this->firstDay;
    }

    /**
     * @param int $firstDay
     * @return Datepicker
     */
    public function setFirstDay(int $firstDay): Datepicker
    {
        $this->firstDay = $firstDay;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'display_format' => $this->displayFormat,
            'return_format' => $this->returnFormat,
            'first_day' => $this->firstDay,
        ]);
    }
}
