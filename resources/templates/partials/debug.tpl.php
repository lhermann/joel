<?php
    if( !WP_DEBUG_DISPLAY ) return;
    global $wp_query;

    $time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
    $template = '';
    foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $value) {
        if( $value['function'] == 'get_footer' ) {
            $template = basename( $value['file'] );
        }
    }
?>

<!-- DEBUG
  Execution Time: <?= $time ?>

  Template: <?= $template ?>

  Post ID: <?= get_the_ID() ?>

  WP Query: <?php print_r( $wp_query->query ) ?>

  Post Count: <?= count($wp_query->posts ?? []) ?>

-->
