<?php
use function Tonik\Theme\App\template;
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php template('vue-components/cookie-consent-init', [
            'id' => 'co0kie-consent',
            'options' => ['matomo']
        ]) ?>

        <div id="siteWrapper" class="c-site-wrapper">
