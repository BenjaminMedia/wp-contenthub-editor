<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;

/**
 * Class AttachmentGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class AttachmentGroup
{
    const KEY = 'group_5b45e49cc3d58';
    const CAPTION_FIELD_NAME = 'attachment_caption';
    const CAPTION_FIELD_KEY = 'field_5b45e57fe6b14';

    public static function register()
    {
        static::create_acf_field_group();
    }

    public static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) :
            acf_add_local_field_group(self::getFieldGroup());

        endif;
    }

    public static function getFieldGroup()
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Attachment')
            ->addField(self::getCaption())
            ->setLocation([
                [
                    [
                        'param' => 'attachment',
                        'operator' => '==',
                        'value' => 'all',
                    ],
                ],
            ])
            ->setPosition('normal')
            ->setStyle('default')
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    private static function getCaption()
    {
        $caption = new MarkdownEditor(self::CAPTION_FIELD_KEY);
        $caption->setConfig('simple')
            ->setLabel('Caption')
            ->setName(self::CAPTION_FIELD_NAME);

        return $caption;
    }
}
