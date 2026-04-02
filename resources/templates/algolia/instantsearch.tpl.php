<?php
/**
 * Algolia InstantSearch template — Vue 3 implementation
 *
 * The WP Search with Algolia plugin loads this template on search pages.
 * The Vue component reads the initial query from the URL (?s=...) via routing.
 */

$settings = new Algolia_Settings();
?>

<?php get_header(); ?>

<main role="main" class="max-w-screen-xl mx-auto px-4 md:px-8 u-pv+">
    <div
        data-vue="JoSearchMain"
        data-options='<?= json_encode([
            'application_id' => $settings->get_application_id(),
            'search_api_key' => $settings->get_search_api_key(),
        ]) ?>'
    ></div>
</main>

<?php get_footer() ?>
