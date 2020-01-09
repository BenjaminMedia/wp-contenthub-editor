<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Newsletter implements WidgetContract
{
    const  FIELD_CHECKBOX = 'field_5e15a823c1913';
    const  FIELD_SOURCE_CODE = 'field_5e144f59a2ac8';

    public function __construct()
    {
        add_filter('acf/validate_value/key='.self::FIELD_SOURCE_CODE, [$this, 'validateSourceCodeLength'], 10, 4);
    }

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5bbc54bacaf10',
            'name' => AcfName::WIDGET_NEWSLETTER,
            'label' => 'Newsletter',
            'display' => 'block',
            'sub_fields' => [
                $this->getCustomSourceCodeCheckbox(),
                $this->getSourceCodeField(),
                $this->getPermissionTextField(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getCustomSourceCodeCheckbox()
    {
        return [
            'key' => self::FIELD_CHECKBOX,
            'label' => 'Manual Source Code and Permission Text',
            'name' => AcfName::FIELD_MANUAL_SOURCE_CODE,
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => 'Enable manual input of source code and permission text.',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];
    }

    private function getSourceCodeField()
    {
        return [
            'key' => self::FIELD_SOURCE_CODE,
            'label' => 'Source Code',
            'name' => AcfName::FIELD_SOURCE_CODE,
            'type' => 'number',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => self::FIELD_CHECKBOX,
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => 'Source Code',
            'prepend' => '',
            'append' => '',
            'min' => '100000',
            'max' => '999999',
        ];
    }

    private function getPermissionTextField()
    {
        return [
            'key' => 'field_5e144fbda2ac9',
            'label' => 'Permission Text',
            'name' => AcfName::FIELD_PERMISSION_TEXT,
            'type' => 'markdown-editor',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => self::FIELD_CHECKBOX,
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'simple_mde_config' => 'simple',
            'font_size' => 14,
        ];
    }

    public function validateSourceCodeLength($valid, $value)
    {
        if (!$valid) {
            return $valid;
        }

        if (!empty($value) && strlen($value) !== 6) {
            $valid = 'Please make sure your source code is in the right format';
        }

        return $valid;
    }
}
