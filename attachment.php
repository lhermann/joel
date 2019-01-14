<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Archive Controller
|------------------------------------------------------------------
|
| Serve attachments directly, don't show a attachment page
|
*/

// use function Tonik\Theme\App\template;

header("Location: $post->guid");
