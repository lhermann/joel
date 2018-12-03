<?php
use Tonik\Theme\App\Store;
if( Store::isset_then_set('vue/livestream/streamcheck') ) return;
?>

<!-- template for the navigation component -->
<?= '<script type="text/x-template" id="streamcheck-component">' ?>
    <span>

        <slot :live="live" :loading="loading"></slot>

    </span>
<?= '</script>' ?>
