<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Taxonomy Controller
|------------------------------------------------------------------
|
|
*/

use function AppTheme\template;

$term = get_queried_object();

template( [ 'taxonomy', $term->taxonomy ] );
