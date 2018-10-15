<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Taxonomy extends AbstractField
{
    /** @var string */
    protected $taxonomy;
    /** @var string */
    protected $fieldType;
    /** @var int */
    protected $allowNull;
    /** @var int */
    protected $addTerm;
    /** @var int */
    protected $saveTerms;
    /** @var int */
    protected $loadTerms;
    /** @var string */
    protected $returnFormat;
    /** @var int */
    protected $multiple;

    public function __construct(string $key)
    {
        parent::__construct($key, 'taxonomy');
        $this->taxonomy = '';
        $this->fieldType = '';
        $this->allowNull = 0;
        $this->addTerm = 0;
        $this->saveTerms = 0;
        $this->loadTerms = 0;
        $this->returnFormat = 'object';
        $this->multiple = 0;
    }

    /**
     * @return string
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * @param string $taxonomy
     * @return Taxonomy
     */
    public function setTaxonomy(string $taxonomy): Taxonomy
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     * @return Taxonomy
     */
    public function setFieldType(string $fieldType): Taxonomy
    {
        $this->fieldType = $fieldType;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowNull(): int
    {
        return $this->allowNull;
    }

    /**
     * @param int $allowNull
     * @return Taxonomy
     */
    public function setAllowNull(int $allowNull): Taxonomy
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @return int
     */
    public function getAddTerm(): int
    {
        return $this->addTerm;
    }

    /**
     * @param int $addTerm
     * @return Taxonomy
     */
    public function setAddTerm(int $addTerm): Taxonomy
    {
        $this->addTerm = $addTerm;
        return $this;
    }

    /**
     * @return int
     */
    public function getSaveTerms(): int
    {
        return $this->saveTerms;
    }

    /**
     * @param int $saveTerms
     * @return Taxonomy
     */
    public function setSaveTerms(int $saveTerms): Taxonomy
    {
        $this->saveTerms = $saveTerms;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoadTerms(): int
    {
        return $this->loadTerms;
    }

    /**
     * @param int $loadTerms
     * @return Taxonomy
     */
    public function setLoadTerms(int $loadTerms): Taxonomy
    {
        $this->loadTerms = $loadTerms;
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
     * @return Taxonomy
     */
    public function setReturnFormat(string $returnFormat): Taxonomy
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiple(): int
    {
        return $this->multiple;
    }

    /**
     * @param int $multiple
     * @return Taxonomy
     */
    public function setMultiple(int $multiple): Taxonomy
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'taxonomy' => $this->taxonomy,
            'field_type' => $this->fieldType,
            'allow_null' => $this->allowNull,
            'add_term' => $this->addTerm,
            'save_terms' => $this->saveTerms,
            'load_terms' => $this->loadTerms,
            'return_format' => $this->returnFormat,
            'multiple' => $this->multiple,
        ]);
    }
}
