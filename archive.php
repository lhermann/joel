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

template( [ 'archive', get_query_var( 'archive' ) ] );
