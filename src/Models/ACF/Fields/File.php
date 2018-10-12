<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class File extends AbstractField
{
    /** @var string */
    protected $returnFormat;
    /** @var string */
    protected $library;
    /** @var string|int */
    protected $minSize;
    /** @var string|int */
    protected $maxSize;
    /** @var string */
    protected $mimeTypes;

    public function __construct(string $key)
    {
        parent::__construct($key, 'file');
        $this->returnFormat = 'id';
        $this->library = 'all';
        $this->minSize = '';
        $this->maxSize = '';
        $this->mimeTypes = '';
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
     * @return File
     */
    public function setReturnFormat(string $returnFormat): File
    {
        $this->returnFormat = $returnFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibrary(): string
    {
        return $this->library;
    }

    /**
     * @param string $library
     * @return File
     */
    public function setLibrary(string $library): File
    {
        $this->library = $library;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getMinSize()
    {
        return $this->minSize;
    }

    /**
     * @param int|string $minSize
     * @return File
     */
    public function setMinSize($minSize)
    {
        $this->minSize = $minSize;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * @param int|string $maxSize
     * @return File
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeTypes(): string
    {
        return $this->mimeTypes;
    }

    /**
     * @param string $mimeTypes
     * @return File
     */
    public function setMimeTypes(string $mimeTypes): File
    {
        $this->mimeTypes = $mimeTypes;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'return_format' => $this->returnFormat,
            'library' => $this->library,
            'min_size' => $this->minSize,
            'max_size' => $this->maxSize,
            'mime_types' => $this->mimeTypes,
        ]);
    }
}
