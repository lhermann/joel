<?php
use function Tonik\Theme\App\template;

global $wp_query;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pv+">

    <div class="u-center u-mb+">
        <a class="c-btn c-btn--secondary" href="<?= get_permalink( get_page_by_path( 'artikel' ) ) ?>">
            <span class="u-ic-arrow_back"></span>
            Alle Artikel anzeigen
        </a>
    </div>

    <header class="u-mb+">
        <?php
            the_archive_title( '<h1 class="u-mb0">', '</h1>' );
            the_archive_description( '<div class="u-muted">', '</div>' );
        ?>
    </header>

    <div class="o-layout u-mv+@tablet">

        <?php if (have_posts()): $i = 0; ?>
        <?php while (have_posts()): the_post(); $i++ ?>

            <div class="o-layout__item u-pb u-1/2@tablet">

                <?php template('partials/post/excerpt', ['hero' => false]) ?>

            </div>

        <?php endwhile ?>
        <?php endif ?>

    </div>

    <!-- Pagination -->
    <?php template('vue-components/pagination-init', [
        'id' => 'pagination',
        'style_modifier' => '',
        'options' => [
            'total'         => (int) $wp_query->found_posts,
            'perPage'       => get_query_var('posts_per_page'),
            'totalPages'    => $wp_query->max_num_pages,
            'currentPage'   => max( 1, get_query_var('paged') ),
            'verbosity'     => 'verbose',
            'baseUrl'       => substr(get_pagenum_link(2), 0, -2)
        ]
    ]) ?>

</main>

<?php get_footer() ?>
