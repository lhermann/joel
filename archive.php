<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Archive Controller
|------------------------------------------------------------------
|
|
*/

use function AppTheme\template;

/**
 * Reroute taxonomy archive to single-<taxonomy>.tpl.php
 */
if( get_query_var('series') )   template( [ 'single', 'series' ] );
if( get_query_var('speakers') ) template( [ 'single', 'speakers' ] );
if( get_query_var('topics') )   template( [ 'single', 'topics' ] );

/**
 * Custom archive routes and normal archives to to archive-<key>.tpl.php
 */
$key = get_query_var( 'archive' ) ?: get_query_var( 'post_type' );

template( [ 'archive', $key ] );
