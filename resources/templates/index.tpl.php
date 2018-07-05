<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;

global $wp_query;

?>
<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pt+">

    <header>
        <h1>Artikel</h1>
    </header>

    <div class="o-layout u-mv+@tablet">

        <?php if (have_posts()): $i = 0; ?>
        <?php while (have_posts()): the_post(); $i++ ?>

            <div class="o-layout__item u-pb <?= $i > 1 ? 'u-1/2@tablet' : '' ?>">

                <?php template('partials/post/excerpt', ['hero' => $i === 1]) ?>

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
