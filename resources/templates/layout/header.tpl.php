<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Legacy\print_open_graph;
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php print_open_graph(); ?>

        <?php if(config('favicon')): ?>
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#061375">
            <meta name="msapplication-TileColor" content="#061375">
            <meta name="theme-color" content="#061375">
        <?php endif ?>

        <!--[if IE]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flexibility/2.0.1/flexibility.js"></script>
        <![endif]-->

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php template('vue-components/main', [
            'component' => 'JoCookieConsent',
            'id' => 'cookie-consent',
            'options' => [
                'matomo' => true,
                'page-name' => get_bloginfo('name'),
                'privacy-policy-link' => home_url('/datenschutzerklaerung/'),
            ],
        ]) ?>

        <div id="siteWrapper" class="c-site-wrapper">

            <?php template('partials/header') ?>
