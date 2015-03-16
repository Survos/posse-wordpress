<?php
/**
 *
 */
function posse_create_custom_fields()
{
    if (function_exists("register_field_group")) {
        register_field_group([
                                 'id'         => 'acf_survey-fields',
                                 'title'      => 'Survey fields',
                                 'fields'     => [
                                     [
                                         'key'           => 'field_550314338c2f7',
                                         'label'         => 'survos_id',
                                         'name'          => 'survos_id',
                                         'type'          => 'number',
                                         'default_value' => '',
                                         'placeholder'   => '',
                                         'prepend'       => '',
                                         'append'        => '',
                                         'min'           => '',
                                         'max'           => '',
                                         'step'          => '',
                                     ],
                                     [
                                         'key'           => 'field_5503144d8c2f8',
                                         'label'         => 'survos_code',
                                         'name'          => 'survos_code',
                                         'type'          => 'text',
                                         'default_value' => '',
                                         'placeholder'   => '',
                                         'prepend'       => '',
                                         'append'        => '',
                                         'formatting'    => 'html',
                                         'maxlength'     => '',
                                     ],
                                 ],
                                 'location'   => [
                                     [
                                         [
                                             'param'    => 'post_type',
                                             'operator' => '==',
                                             'value'    => 'survos-survey',
                                             'order_no' => 0,
                                             'group_no' => 0,
                                         ],
                                     ],
                                 ],
                                 'options'    => [
                                     'position'       => 'normal',
                                     'layout'         => 'no_box',
                                     'hide_on_screen' => [
                                     ],
                                 ],
                                 'menu_order' => 0,
                             ]);
    }

}