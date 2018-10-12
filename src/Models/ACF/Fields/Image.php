<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

class Image extends File
{
    /** @var string */
    protected $previewSize;
    /** @var string */
    protected $minWidth;
    /** @var string */
    protected $minHeight;
    /** @var string */
    protected $maxWidth;
    /** @var string */
    protected $maxHeight;

    public function __construct(string $key)
    {
        parent::__construct($key);
        $this->setType('image');
        $this->previewSize = 'thumbnail';
        $this->minWidth = '';
        $this->minHeight = '';
        $this->maxWidth = '';
        $this->maxHeight = '';
    }

    /**
     * @return string
     */
    public function getPreviewSize(): string
    {
        return $this->previewSize;
    }

    /**
     * @param string $previewSize
     * @return Image
     */
    public function setPreviewSize(string $previewSize): Image
    {
        $this->previewSize = $previewSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinWidth(): string
    {
        return $this->minWidth;
    }

    /**
     * @param string $minWidth
     * @return Image
     */
    public function setMinWidth(string $minWidth): Image
    {
        $this->minWidth = $minWidth;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinHeight(): string
    {
        return $this->minHeight;
    }

    /**
     * @param string $minHeight
     * @return Image
     */
    public function setMinHeight(string $minHeight): Image
    {
        $this->minHeight = $minHeight;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaxWidth(): string
    {
        return $this->maxWidth;
    }

    /**
     * @param string $maxWidth
     * @return Image
     */
    public function setMaxWidth(string $maxWidth): Image
    {
        $this->maxWidth = $maxWidth;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaxHeight(): string
    {
        return $this->maxHeight;
    }

    /**
     * @param string $maxHeight
     * @return Image
     */
    public function setMaxHeight(string $maxHeight): Image
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'preview_size' => $this->previewSize,
            'min_width' => $this->minWidth,
            'min_height' => $this->minHeight,
            'max_width' => $this->maxWidth,
            'max_height' => $this->maxHeight,
        ]);
    }
}
