<?php

namespace AppTheme\ACF;

/*
|-----------------------------------------------------------
| Advanced Custom Fields
|-----------------------------------------------------------
|
| The ACF settings for recordings
|
*/

use function AppTheme\config;

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_acf_videodatei',
    'title' => __('Recordings', config('textdomain')),
    'fields' => array(
        array(
            'key' => 'field_52c9deb0d3c39',
            'label' => __('Select Recording', config('textdomain')),
            'name' => 'select_video',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'Gold Play Buddon.mp4' => 'Gold Play Buddon.mp4 [5 MB]',
                'logo-assembly-cut.mp4' => 'logo-assembly-cut.mp4 [5 MB]',
                'trailer-ausweg-2012.mp4' => 'trailer-ausweg-2012.mp4 [59 MB]',
            ),
            'default_value' => array(
            ),
            'allow_null' => 1,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ),
        array(
            'key' => 'field_53dfb75328fc8',
            'label' => __('Status', config('textdomain')),
            'name' => '',
            'type' => 'message',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ),
        array(
            'key' => 'field_4fb10184a8596',
            'label' => __('Thumbnail', config('textdomain')),
            'name' => 'thumbnail',
            'type' => 'image',
            'instructions' => 'Erforderliche Aufl&ouml;sung in Pixel: 1920x1080',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'id',
            'preview_size' => '360p',
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
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'recordings',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'hide_on_screen' => array(),
    'description' => '',
    'hide_on_screen' => array(
        0 => 'excerpt',
        1 => 'custom_fields',
        2 => 'discussion',
        3 => 'comments',
        4 => 'slug',
        5 => 'author',
        6 => 'format',
        7 => 'featured_image',
        8 => 'categories',
        9 => 'tags',
        10 => 'send-trackbacks',
    ),
));

acf_add_local_field_group(array(
    'key' => 'group_59de0a51d9c79',
    'title' => 'Taxonomy Podcast',
    'fields' => array(
        array(
            'key' => 'field_59de0a67baf08',
            'label' => __('Image', config('textdomain')),
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Artwork must be a minimum size of 1400 x 1400 pixels and a maximum size of 3000 x 3000 pixels, in JPEG or PNG format, 72 dpi, with appropriate file extensions (.jpg, .png), and in the RGB colorspace.',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'id',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => 1400,
            'min_height' => 1400,
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array(
            'key' => 'field_59de0abf106de',
            'label' => __('Author', config('textdomain')),
            'name' => 'autor',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'sprecher',
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
        ),
        array(
            'key' => 'field_59de0b32b3aa1',
            'label' => __('Categories', config('textdomain')),
            'name' => 'categorien',
            'type' => 'checkbox',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'Health' => 'Health',
                'Health/Alternative Health' => 'Health / Alternative Health',
                'Health/Fitness &amp; Nutrition' => 'Health / Fitness & Nutrition',
                'Kids &amp; Family' => 'Kids & Family',
                'News &amp; Politics' => 'News & Politics',
                'Religion &amp; Spirituality' => 'Religion & Spirituality',
                'Religion &amp; Spirituality/Christianity' => 'Religion & Spirituality / Christianity',
                'Science &amp; Medicine' => 'Science & Medicine',
                'Science &amp; Medicine/Medicine' => 'Science & Medicine / Medicine',
                'Society &amp; Culture' => 'Society & Culture',
                'Society &amp; Culture/History' => 'Society & Culture / History',
            ),
            'allow_custom' => 0,
            'save_custom' => 0,
            'default_value' => array(
                0 => 'Religion & Spirituality',
                1 => 'Religion & Spirituality: Christianity',
            ),
            'layout' => 'vertical',
            'toggle' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_59e44c58b3706',
            'label' => __('iTunes Link', config('textdomain')),
            'name' => 'itunes_link',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        array(
            'key' => 'field_59e79635e0444',
            'label' => __('Stitcher Link', config('textdomain')),
            'name' => 'stitcher_link',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        array(
            'key' => 'field_59e3aaa4c51dc',
            'label' => __('Website', config('textdomain')),
            'name' => 'website_link',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'podcasts',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));


acf_add_local_field_group(array(
    'key' => 'group_59ddf45ff376f',
    'title' => 'Taxonomy Serien',
    'fields' => array(
        array(
            'key' => 'field_59ddf48a5960c',
            'label' => 'Titelbild für Serien',
            'name' => __('Image', config('textdomain')),
            'type' => 'image',
            'instructions' => 'Bild sollte mindestens 1280x720 Pixel haben.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => 1280,
            'min_height' => 720,
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array(
            'key' => 'field_59e5a5c30a4fd',
            'label' => __('Podcast', config('textdomain')),
            'name' => 'podcast',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'podcasts',
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'series',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));


acf_add_local_field_group(array(
    'key' => 'group_59ddf5713cb1d',
    'title' => 'Taxonomy Sprecher',
    'fields' => array(
        array(
            'key' => 'field_59ddf586d5c6d',
            'label' => __('Image', config('textdomain')),
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Sollte <strong>quadratisch</strong> sein, wird ansonnsten automatisch ausgeschnitten. Mindestens <strong>300x300 Pixel</strong>.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'id',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => 300,
            'min_height' => 300,
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array(
            'key' => 'field_59ddf623f1beb',
            'label' => __('Website', config('textdomain')),
            'name' => 'website',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'speakers',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_acf_choose-taxonomies',
    'title' => __('Choose taxonomies', config('textdomain')),
    'fields' => array(
        array(
            'key' => 'field_53dfb0355292e',
            'label' => __('Speakers', config('textdomain')),
            'name' => 'speakers',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'speakers',
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array(
            'key' => 'field_53dfaf955292d',
            'label' => __('Series', config('textdomain')),
            'name' => 'series',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'series',
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array(
            'key' => 'field_59dcf1a261753',
            'label' => __('Topics', config('textdomain')),
            'name' => 'topics',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'topics',
            'field_type' => 'checkbox',
            'allow_null' => 0,
            'add_term' => 1,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array(
            'key' => 'field_59dcf1e0346a5',
            'label' => __('Podcast', config('textdomain')),
            'name' => 'podcast',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'podcasts',
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array(
            'key' => 'field_59dcf417389a4',
            'label' => __('Download', config('textdomain')),
            'name' => 'download',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'ui' => 1,
            'ui_on_text' => __('permit', config('textdomain')),
            'ui_off_text' => __('deny', config('textdomain')),
        )
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'recording',
            ),
        ),
    ),
    'menu_order' => 1,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_acf_bibelantworten',
    'title' => 'Bibel.Antworten Kategorie',
    'fields' => array(
        array(
            'key' => 'field_54b66d16a979b',
            'label' => 'Bibel.Antworten Kategorie',
            'name' => 'bibel_antworten_kategorie',
            'type' => 'select',
            'instructions' => 'Diese Kategorien sind nur für Videos der Serie "Bibel.Antworten" von Bedeutung.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                0 => 'Keine Kategorie (wird nicht aufgelistet)',
                1 => '1. Lesen, hören, bewahren – Fragen zur Bibel',
                2 => '2. „Ich bin“ – Fragen zur Gottheit',
                3 => '3. „gewaschen durch sein Blut“ – Fragen zur Erlösung',
                4 => '4. „Könige und Priester“ – Fragen zum Leben als Christ',
                5 => '5. „Siehe, er kommt“ – Fragen zur Wiederkunft',
                6 => '6. „Tag des Herrn“ – Fragen zum Sabbat',
                7 => '7. „sieben goldene Leuchter“ – Fragen zum Heiligtum',
                8 => '8. „die Schlüssel des Totenreiches“ – Fragen zum Tod',
                9 => '9. „was ist und was geschehen soll“ – Fragen zur Prophetie',
                10 => '10. „sieben Gemeinden“ – Fragen zum Volk Gottes',
            ),
            'default_value' => array(
                0 => 0,
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 1,
            'return_format' => 'value',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'recordings',
            ),
        ),
    ),
    'menu_order' => 9,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

endif;
