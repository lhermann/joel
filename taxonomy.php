<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Taxonomy Controller
|------------------------------------------------------------------
|
|
*/

use function Tonik\Theme\App\template;

$term = get_queried_object();

template( [ 'taxonomy', $term->taxonomy ] );
