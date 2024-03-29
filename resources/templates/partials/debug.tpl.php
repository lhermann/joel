<?php
    if( !WP_DEBUG_DISPLAY ) return;
    global $wp_query;
?>

<div class="o-wrapper u-p u-mv+ u-shadow">
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
    </p>
    <div>query_vars:</div>
    <div style="overflow: scroll; max-height: 12em; border: 1px solid lightgray; padding: 0.5rem;">
        <pre><?php print_r($wp_query->query_vars) ?></pre>
    </div>
</div>
