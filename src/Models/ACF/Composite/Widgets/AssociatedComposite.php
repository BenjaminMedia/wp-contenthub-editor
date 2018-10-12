<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Relationship;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

class AssociatedComposite implements WidgetContract
{
    const KEY = '58e393a7128b3';
    const COMPOSITE_KEY = 'field_58e393e0128b4';
    const LOCKED_KEY = 'field_5922be585cda6';

    public function getLayout(): array
    {
        $associatedContent = new ACFLayout(self::KEY);
        $associatedContent->setName('associated_composite')
            ->setLabel('Sub Content')
            ->addSubField($this->getComposite())
            ->addSubField($this->getLockedContent());

        return $associatedContent->toArray();
    }

    private function getComposite()
    {
        $composite = new Relationship(self::COMPOSITE_KEY);
        $composite->setPostType([WpComposite::POST_TYPE])
            ->setTaxonomy([])
            ->setFilters(['search', 'taxonomy'])
            ->setReturnFormat('object')
            ->setMax(1)
            ->setLabel('Content')
            ->setName('composite');

        return $composite->toArray();
    }

    private function getLockedContent()
    {
        $lockedContent = new Checkbox(self::LOCKED_KEY);
        $lockedContent->setLabel('Locked Content')
            ->setName('locked_content')
            ->setConditionalLogic([
                [
                    [
                        'field' => CompositeContentFieldGroup::LOCKED_CONTENT_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);
        return $lockedContent->toArray();
    }
}
