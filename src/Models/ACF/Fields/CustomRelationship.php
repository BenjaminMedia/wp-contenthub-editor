<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class CustomRelationship extends AbstractField
{
    /** @var string|array */
    protected $postType;
    /** @var string|array */
    protected $taxonomy;
    /** @var string|array */
    protected $tag;
    /** @var string|array */
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
        parent::__construct($key, \Bonnier\WP\ContentHub\Editor\ACF\CustomRelationship::NAME);
        $this->postType = '';
        $this->taxonomy = '';
        $this->tag = '';
        $this->filters = '';
        $this->elements = '';
        $this->min = '';
        $this->max = '';
        $this->returnFormat = 'object';
    }

    /**
     * @return array|string
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @param array|string $postType
     * @return CustomRelationship
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @param array|string $taxonomy
     * @return CustomRelationship
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param array|string $tag
     * @return CustomRelationship
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array|string $filters
     * @return CustomRelationship
     */
    public function setFilters($filters)
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
     * @return CustomRelationship
     */
    public function setElements(string $elements): CustomRelationship
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
     * @return CustomRelationship
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
     * @return CustomRelationship
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
     * @return CustomRelationship
     */
    public function setReturnFormat(string $returnFormat): CustomRelationship
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'post_type' => $this->postType,
            'taxonomy' => $this->taxonomy,
            'post_tag' => $this->tag,
            'filters' => $this->filters,
            'elements' => $this->elements,
            'min' => $this->min,
            'max' => $this->max,
            'return_format' => $this->returnFormat,
        ]);
    }
}
