<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Illuminate\Support\Collection;

class FlexibleContent extends AbstractField
{
    /** @var string */
    protected $buttonLabel;
    /** @var string|int */
    protected $min;
    /** @var string|int */
    protected $max;
    /** @var Collection */
    protected $layouts;

    public function __construct(string $key)
    {
        parent::__construct($key, 'flexible_content');
        $this->buttonLabel = '';
        $this->min = '';
        $this->max = '';
        $this->layouts = new Collection();
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
     * @return FlexibleContent
     */
    public function setButtonLabel(string $buttonLabel): FlexibleContent
    {
        $this->buttonLabel = $buttonLabel;
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
     * @return FlexibleContent
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
     * @return FlexibleContent
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLayouts(): Collection
    {
        return $this->layouts;
    }

    /**
     * @param Collection $layouts
     * @return FlexibleContent
     */
    public function setLayouts(Collection $layouts): FlexibleContent
    {
        $this->layouts = $layouts;
        return $this;
    }

    public function addLayout(WidgetContract $widget): FlexibleContent
    {
        $this->layouts->push($widget);
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'button_label' => 'Add Widget',
            'min' => '',
            'max' => '',
            'layouts' => $this->layouts->map(function (WidgetContract $widget) {
                return $widget->getLayout()->toArray();
            })->toArray(),
        ]);
    }
}
