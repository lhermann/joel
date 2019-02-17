<?php

namespace Tonik\Theme\App\ACF;

/*
|-----------------------------------------------------------
| Advanced Custom Fields
|-----------------------------------------------------------
|
| The ACF settings for slides
|
*/

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;


if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_5a8eaf3ddd05c',
    'title' => 'Slide',
    'fields' => array(


        /* Meta */
        array(
            'key' => 'field_5a8ff51423fe9',
            'label' => __('Show Title', config('textdomain')),
            'name' => 'show_title',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'ui' => 1,
            'ui_on_text' => __('Yes', config('textdomain')),
            'ui_off_text' => __('No', config('textdomain')),
        ),
        array(
            'key' => 'field_5a8ebbab2a387',
            'label' => __('Button Text', config('textdomain')),
            'name' => 'button_text',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '<span class="dashicons dashicons-arrow-right-alt"> </span>',
            'maxlength' => 30,
        ),
        array(
            'key' => 'field_5c68ed48ac801',
            'label' => __('Link', config('textdomain')),
            'name' => 'link',
            'type' => 'link',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
        ),

        /* Slide Content */
        array(
            'key' => 'field_5a8ebb2553dbe',
            'label' => __('Content', config('textdomain')),
            'name' => 'content',
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),

        /* Slide Type */
        array(
            'key' => 'field_5a8eb2f599038',
            'label' => __('Slide Type', config('textdomain')),
            'name' => 'slide_type',
            'type' => 'button_group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'text-left'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-text-left.png')),
                'text-center'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-text-center.png')),
                'text-right'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-text-right.png')),
                'media-right'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-media-right.png')),
                'media-left'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-media-left.png')),
                'teaser'
                    => sprintf('<img src="%s">', asset_path('images/slide-type-teaser.png')),
            ),
            'allow_null' => 0,
            'default_value' => 'center',
            'layout' => 'horizontal',
            'return_format' => 'value',
        ),

        /* Media Flexible Content */
        array(
            'key' => 'field_5a8ecfb5a802c',
            'label' => __('Media Content', config('textdomain')),
            'name' => 'media_content',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5a8eb2f599038',
                        'operator' => '==',
                        'value' => 'media-right',
                    ),
                ),
                array(
                    array(
                        'field' => 'field_5a8eb2f599038',
                        'operator' => '==',
                        'value' => 'media-left',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array(
                '5a8ecfb57713c' => array(
                    'key' => '5a8ecfb57713c',
                    'name' => 'video',
                    'label' => '<span class="dashicons dashicons-video-alt2"></span> ' . __('Video', config('textdomain')),
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5a8ecf65a802b',
                            'label' => 'Video',
                            'name' => 'video',
                            'type' => 'post_object',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'recordings',
                            ),
                            'taxonomy' => array(
                            ),
                            'allow_null' => 0,
                            'multiple' => 0,
                            'return_format' => 'id',
                            'ui' => 1,
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                '5a8ed0542c2ea' => array(
                    'key' => '5a8ed0542c2ea',
                    'name' => 'image',
                    'label' => '<span class="dashicons dashicons-format-image"></span> ' . __('Image', config('textdomain')),
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5a8eceb814a26',
                            'label' => __('Image', config('textdomain')),
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => '108p',
                            'library' => 'all',
                            'min_width' => 1280,
                            'min_height' => 720,
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'button_label' => __('Choose Media', config('textdomain')),
            'min' => '',
            'max' => 1,
        ),

        /* Slide Colors */
        array(
            'key' => 'field_5a8eaf6292f5d',
            'label' => __('Slide Colors', config('textdomain')),
            'name' => 'colors',
            'type' => 'button_group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                asset_path('images/slide-dark-blue.svg')
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-dark-blue.png')),
                asset_path('images/slide-teal.svg')
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-teal.png')),
                asset_path('images/slide-red.svg')
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-red.png')),
                asset_path('images/slide-green.jpg')
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-green.png')),
                asset_path('images/slide-blue.jpg')
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-blue.png')),
                'image'
                    => sprintf( '<img src="%s">', asset_path('images/slide-color-custom.png')),
            ),
            'allow_null' => 0,
            'default_value' => asset_path('images/slide-dark-blue.svg'),
            'layout' => 'horizontal',
            'return_format' => 'value',
        ),

        /* Custom Background */
        array(
            'key' => 'field_5a8eb03ac3cb6',
            'label' => __('Background Image', config('textdomain')),
            'name' => 'background_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5a8eaf6292f5d',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '60',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => '180p',
            'library' => 'all',
            'min_width' => 1200,
            'min_height' => 400,
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array(
            'key' => 'field_5a8eec1065915',
            'label' => __('Text Color', config('textdomain')),
            'name' => 'text_color',
            'type' => 'button_group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5a8eaf6292f5d',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '40',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                '#333' => __('Black', config('textdomain')),
                '#fff' => __('White', config('textdomain')),
            ),
            'allow_null' => 0,
            'default_value' => 'black',
            'layout' => 'horizontal',
            'return_format' => 'value',
        ),
        // array(
        //     'key' => 'field_5a8ff55823fea',
        //     'label' => 'Use Custom HTML',
        //     'name' => 'use_custom_html',
        //     'type' => 'true_false',
        //     'instructions' => '',
        //     'required' => 0,
        //     'conditional_logic' => 0,
        //     'wrapper' => array(
        //         'width' => '30',
        //         'class' => '',
        //         'id' => '',
        //     ),
        //     'message' => '',
        //     'default_value' => 0,
        //     'ui' => 1,
        //     'ui_on_text' => '',
        //     'ui_off_text' => '',
        // ),
        // array(
        //     'key' => 'field_5a8ff30b23fe7',
        //     'label' => 'Lead Text',
        //     'name' => 'lead_text',
        //     'type' => 'textarea',
        //     'instructions' => '',
        //     'required' => 0,
        //     'conditional_logic' => array(
        //         array(
        //             array(
        //                 'field' => 'field_5a8ff55823fea',
        //                 'operator' => '!=',
        //                 'value' => '1',
        //             ),
        //         ),
        //     ),
        //     'wrapper' => array(
        //         'width' => '',
        //         'class' => '',
        //         'id' => '',
        //     ),
        //     'default_value' => '',
        //     'placeholder' => '',
        //     'maxlength' => 300,
        //     'rows' => 4,
        //     'new_lines' => '',
        // ),
        // array(
        //     'key' => 'field_5a8ff36123fe8',
        //     'label' => 'Custom HTML',
        //     'name' => 'custom_html',
        //     'type' => 'textarea',
        //     'instructions' => '',
        //     'required' => 0,
        //     'conditional_logic' => array(
        //         array(
        //             array(
        //                 'field' => 'field_5a8ff55823fea',
        //                 'operator' => '==',
        //                 'value' => '1',
        //             ),
        //         ),
        //     ),
        //     'wrapper' => array(
        //         'width' => '',
        //         'class' => '',
        //         'id' => '',
        //     ),
        //     'default_value' => '',
        //     'placeholder' => '',
        //     'maxlength' => '',
        //     'rows' => '',
        //     'new_lines' => '',
        // ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'slides',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'permalink',
        1 => 'the_content',
        2 => 'excerpt',
        3 => 'custom_fields',
        4 => 'discussion',
        5 => 'comments',
        6 => 'revisions',
        7 => 'slug',
        8 => 'author',
        9 => 'format',
        10 => 'page_attributes',
        11 => 'featured_image',
        12 => 'categories',
        13 => 'tags',
        14 => 'send-trackbacks',
    ),
    'active' => 1,
    'description' => '',
));

endif;
