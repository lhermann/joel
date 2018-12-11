<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php if(config('favicon')): ?>
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#061375">
            <meta name="msapplication-TileColor" content="#061375">
            <meta name="theme-color" content="#061375">
        <?php endif ?>

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php template('vue-components/cookie-consent-init', [
            'id' => 'co0kie-consent',
            'options' => ['matomo']
        ]) ?>

        <div id="siteWrapper" class="c-site-wrapper">
