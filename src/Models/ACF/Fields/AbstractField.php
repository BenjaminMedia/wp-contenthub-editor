<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

abstract class AbstractField
{
    /** @var string */
    protected $key;
    /** @var string */
    protected $label;
    /** @var string */
    protected $name;
    /** @var string */
    protected $type;
    /** @var string */
    protected $instructions;
    /** @var int */
    protected $required;
    /** @var int|array */
    protected $conditionalLogic;
    /** @var array */
    protected $wrapper;

    public function __construct(string $key, string $type)
    {
        $this->key = $key;
        $this->type = $type;
        $this->name = '';
        $this->instructions = '';
        $this->required = 0;
        $this->conditionalLogic = 0;
        $this->wrapper = [
            'width' => '',
            'class' => '',
            'id' => '',
        ];
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
     * @return AbstractField
     */
    public function setKey(string $key): AbstractField
    {
        $this->key = $key;
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
     * @return AbstractField
     */
    public function setLabel(string $label): AbstractField
    {
        $this->label = $label;
        return $this;
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
     * @return AbstractField
     */
    public function setName(string $name): AbstractField
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AbstractField
     */
    public function setType(string $type): AbstractField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructions(): string
    {
        return $this->instructions;
    }

    /**
     * @param string $instructions
     * @return AbstractField
     */
    public function setInstructions(string $instructions): AbstractField
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequired(): int
    {
        return $this->required;
    }

    /**
     * @param int $required
     * @return AbstractField
     */
    public function setRequired(int $required): AbstractField
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return array|int
     */
    public function getConditionalLogic()
    {
        return $this->conditionalLogic;
    }

    /**
     * @param array|int $conditionalLogic
     * @return AbstractField
     */
    public function setConditionalLogic($conditionalLogic)
    {
        $this->conditionalLogic = $conditionalLogic;
        return $this;
    }

    /**
     * @return array
     */
    public function getWrapper(): array
    {
        return $this->wrapper;
    }

    /**
     * @param array $wrapper
     * @return AbstractField
     */
    public function setWrapper(array $wrapper): AbstractField
    {
        $this->wrapper = $wrapper;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'name' => $this->name,
            'type' => $this->type,
            'instructions' => $this->instructions,
            'required' => $this->required,
            'conditional_logic' => $this->conditionalLogic,
            'wrapper' => $this->wrapper,
        ];
    }
}
