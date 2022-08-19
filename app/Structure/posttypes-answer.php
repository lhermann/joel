<?php

namespace Tonik\Theme\App\Structure;

/*
|-----------------------------------------------------------
| Theme Custom Post Types
|-----------------------------------------------------------
|
| This file is for registering your theme post types.
| Custom post types allow users to easily create
| and manage various types of content.
|
*/

use function Tonik\Theme\App\config;

/**
 * Registers `answer` custom post type.
 *
 * @return void
 */
add_action('init', 'Tonik\Theme\App\Structure\register_answer_post_type');
function register_answer_post_type () {
  register_post_type('answer', [
    'description'        => __('Question and answer posts', config('textdomain')),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'menu_icon'          => 'dashicons-yes-alt',
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 9,
    'show_in_rest'       => true,
    'rest_base'          => 'answer',
    'supports'           => ['title', 'editor'],
    'rewrite'            => ['slug' => _x('answer', 'http route', config('textdomain'))],
    'labels' => [
      'name' => _x('Boogle Answers', 'post type general name', config('textdomain')),
      'singular_name' => _x('Boogle Answer', 'post type singular name', config('textdomain')),
      'menu_name' => _x('Boogle', 'admin menu', config('textdomain')),
      'name_admin_bar' => _x('Boogle Answer', 'add new on admin bar', config('textdomain')),
      'add_new' => _x('Add New', 'book', config('textdomain')),
      'add_new_item' => __('Add New Answer', config('textdomain')),
      'new_item' => __('New Answer', config('textdomain')),
      'edit_item' => __('Edit Answer', config('textdomain')),
      'view_item' => __('View Answer', config('textdomain')),
      'all_items' => __('All Answers', config('textdomain')),
      'search_items' => __('Search Answers', config('textdomain')),
      'parent_item_colon' => __('Parent Answer:', config('textdomain')),
      'not_found' => __('No answers found.', config('textdomain')),
      'not_found_in_trash' => __('No answers found in Trash.', config('textdomain')),
    ],
  ]);
}
