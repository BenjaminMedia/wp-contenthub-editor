<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

/**
 * Class Relationship
 * @package Bonnier\WP\ContentHub\Editor\Models\ACF\Fields
 * @method setName(string $name) : Relationship
 */
class Relationship extends AbstractField
{
    /** @var array */
    protected $postType;
    /** @var array */
    protected $taxonomy;
    /** @var array */
    protected $filters;
    /** @var string */
    protected $elements;
    /** @var string|int */
    protected $min;
    /** @var string|int */
    protected $max;
    /** @var string */
    protected $returnFormat;

    public function __construct(string $key)
    {
        parent::__construct($key, 'relationship');
        $this->postType = [];
        $this->taxonomy = [];
        $this->filters = [];
        $this->elements = '';
        $this->min = '';
        $this->max = '';
        $this->returnFormat = '';
    }

    /**
     * @return array
     */
    public function getPostType(): array
    {
        return $this->postType;
    }

    /**
     * @param array $postType
     * @return Relationship
     */
    public function setPostType(array $postType): Relationship
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @return array
     */
    public function getTaxonomy(): array
    {
        return $this->taxonomy;
    }

    /**
     * @param array $taxonomy
     * @return Relationship
     */
    public function setTaxonomy(array $taxonomy): Relationship
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return Relationship
     */
    public function setFilters(array $filters): Relationship
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return string
     */
    public function getElements(): string
    {
        return $this->elements;
    }

    /**
     * @param string $elements
     * @return Relationship
     */
    public function setElements(string $elements): Relationship
    {
        $this->elements = $elements;
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
     * @return Relationship
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
     * @return Relationship
     */
    public function setMax($max)
    {
        $this->max = $max;
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
     * @return Relationship
     */
    public function setReturnFormat(string $returnFormat): Relationship
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'post_type' => $this->postType,
            'taxonomy' => $this->taxonomy,
            'filters' => $this->filters,
            'elements' => $this->elements,
            'min' => $this->min,
            'max' => $this->max,
            'return_format' => $this->returnFormat,
        ]);
    }
}
