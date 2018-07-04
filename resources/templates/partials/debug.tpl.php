<?php
    if( !WP_DEBUG ) return;
    global $wp_query;
?>

<div class="o-wrapper u-p u-mv+ u-box-shadow">
    <p>
        <?php
            // display execution time
            $time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
            // display theme root php file
            $template = '';
            foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $value) {
                if( $value['function'] == 'get_footer' ) {
                    $template = basename( $value['file'] );
                }
            }
        ?>
        Execution Time:  <strong><?= $time ?></strong>
        &bull; Template: <strong><?= $template ?></strong>
        &bull; Post ID:  <strong><?= get_the_ID() ?></strong>
    </p>
    <p>
        <strong>WP Query:</strong>
        <?php print_r( $wp_query->query ) ?>
        <br>
        <strong>Post Count:</strong> <?= count($wp_query->posts) ?>
        <br>
        <strong>query_vars:</strong>
        <?php print_r($wp_query->query_vars) ?>
    </p>
</div>
