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

$key = get_query_var( 'archive' ) ?: get_query_var( 'post_type' );

template( [ 'archive', $key ] );
