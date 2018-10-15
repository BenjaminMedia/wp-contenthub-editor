<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Datepicker;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Select;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class TranslationStateFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class TranslationStateFieldGroup
{
    const KEY = 'group_5940debee7ae2';
    const STATE_KEY = 'field_5940df2d4eff9';
    const DEADLINE_KEY = 'field_59885bce3d421';

    const TRANSLATION_STATES = [
        'ready' => 'Ready For Translation',
        'progress' => 'In Progress',
        'translated' => 'Translated',
    ];

    public static function register()
    {
        static::register_field_group();
    }

    private static function register_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(self::getFieldGroup());
        }
    }

    public static function getFieldGroup()
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Translation State')
            ->addField(self::getTranslationState())
            ->addField(self::getTranslationDeadline())
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ])
            ->setPosition('side')
            ->setStyle('default')
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    private static function getTranslationState()
    {
        $state = new Select(self::STATE_KEY);
        $state->setChoices([
                'ready' => 'Ready For Translation',
                'progress' => 'In Progress',
                'translated' => 'Translated',
            ])
            ->setAllowNull(1)
            ->setName('translation_state');

        return $state;
    }

    private static function getTranslationDeadline()
    {
        $deadline = new Datepicker(self::DEADLINE_KEY);
        $deadline->setFirstDay(1)
            ->setLabel('Translation deadline')
            ->setName('translation_deadline');

        return $deadline;
    }
}
