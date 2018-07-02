<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Archive Controller
|------------------------------------------------------------------
|
|
*/

use function Tonik\Theme\App\template;

$key = get_query_var( 'archive' ) ?: get_query_var( 'post_type' );

template( [ 'archive', $key ] );
