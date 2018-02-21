<?php

namespace AppTheme;

/*
|------------------------------------------------------------------
| Index Controller
|------------------------------------------------------------------
|
| Think about theme template files as some sort of controllers
| from MVC design pattern. They should link application
| logic with your theme view templates files.
|
*/

use function AppTheme\template;

/**
 * Renders index page.
 *
 * @uses resources/templates/index.tpl.php
 */
template('index');
