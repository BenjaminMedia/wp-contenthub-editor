<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\AbstractField;
use Illuminate\Support\Collection;

class ACFGroup
{
    /** @var string */
    protected $key;
    /** @var string */
    protected $title;
    /** @var Collection */
    protected $fields;
    /** @var array|int */
    protected $location;
    /** @var int */
    protected $menuOrder;
    /** @var string */
    protected $position;
    /** @var string */
    protected $style;
    /** @var string */
    protected $labelPlacement;
    /** @var string */
    protected $instructionPlacement;
    /** @var array */
    protected $hideOnScreen;
    /** @var int */
    protected $active;
    /** @var string */
    protected $description;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->title = '';
        $this->fields = new Collection();
        $this->location = 0;
        $this->menuOrder = 0;
        $this->position = '';
        $this->style = '';
        $this->labelPlacement = 'top';
        $this->instructionPlacement = 'label';
        $this->hideOnScreen = [];
        $this->active = 0;
        $this->description = '';
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ACFGroup
     */
    public function setKey(string $key): ACFGroup
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ACFGroup
     */
    public function setTitle(string $title): ACFGroup
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @param Collection $fields
     * @return ACFGroup
     */
    public function setFields(Collection $fields): ACFGroup
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param AbstractField $field
     * @return ACFGroup
     */
    public function addField(AbstractField $field): ACFGroup
    {
        $this->fields->push($field);
        return $this;
    }

    /**
     * @return array|int
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param array|int $location
     * @return ACFGroup
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return int
     */
    public function getMenuOrder(): int
    {
        return $this->menuOrder;
    }

    /**
     * @param int $menuOrder
     * @return ACFGroup
     */
    public function setMenuOrder(int $menuOrder): ACFGroup
    {
        $this->menuOrder = $menuOrder;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @return ACFGroup
     */
    public function setPosition(string $position): ACFGroup
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style
     * @return ACFGroup
     */
    public function setStyle(string $style): ACFGroup
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelPlacement(): string
    {
        return $this->labelPlacement;
    }

    /**
     * @param string $labelPlacement
     * @return ACFGroup
     */
    public function setLabelPlacement(string $labelPlacement): ACFGroup
    {
        $this->labelPlacement = $labelPlacement;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionPlacement(): string
    {
        return $this->instructionPlacement;
    }

    /**
     * @param string $instructionPlacement
     * @return ACFGroup
     */
    public function setInstructionPlacement(string $instructionPlacement): ACFGroup
    {
        $this->instructionPlacement = $instructionPlacement;
        return $this;
    }

    /**
     * @return array
     */
    public function getHideOnScreen(): array
    {
        return $this->hideOnScreen;
    }

    /**
     * @param array $hideOnScreen
     * @return ACFGroup
     */
    public function setHideOnScreen(array $hideOnScreen): ACFGroup
    {
        $this->hideOnScreen = $hideOnScreen;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     * @return ACFGroup
     */
    public function setActive(int $active): ACFGroup
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ACFGroup
     */
    public function setDescription(string $description): ACFGroup
    {
        $this->description = $description;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'fields' => $this->fields->map(function (AbstractField $field) {
                return $field->toArray();
            })->toArray(),
            'location' => $this->location,
            'menu_order' => $this->menuOrder,
            'position' => $this->position,
            'style' => $this->style,
            'label_placement' => $this->labelPlacement,
            'instruction_placement' => $this->instructionPlacement,
            'hide_on_screen' => $this->hideOnScreen,
            'active' => $this->active,
            'description' => $this->description,
        ];
    }
}
