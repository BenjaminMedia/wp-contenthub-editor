<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Fields;

use Illuminate\Support\Collection;

class Group extends AbstractField
{
    /** @var string */
    protected $layout;
    /** @var Collection */
    protected $subFields;

    public function __construct(string $key)
    {
        parent::__construct($key, 'group');
        $this->layout = 'block';
        $this->subFields = new Collection();
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return Group
     */
    public function setLayout(string $layout): Group
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSubFields(): Collection
    {
        return $this->subFields;
    }

    /**
     * @param Collection $subFields
     * @return Group
     */
    public function setSubFields(Collection $subFields): Group
    {
        $this->subFields = $subFields;
        return $this;
    }

    /**
     * @param AbstractField $field
     * @return Group
     */
    public function addSubField(AbstractField $field): Group
    {
        $this->subFields->push($field);
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'layout' => 'block',
            'sub_fields' => $this->subFields->map(function (AbstractField $field) {
                return $field->toArray();
            })->toArray()
        ]);
    }
}
