<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
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

                <article class="c-excerpt">

                    <div class="c-excerpt__image">
                        <?= get_the_post_thumbnail(null, '360p') ?>
                    </div>

                    <a class="c-excerpt__clickspace"
                        href="<?php the_permalink(); ?>">
                        <?php __('Read article', config('textdomain')) ?>
                    </a>

                    <div class="c-excerpt__body <?= $i > 1 ? 'u-pb-' : '' ?>">

                        <header class="c-excerpt__content">
                            <a class="c-link c-link--white c-link--dotted"
                                href="<?php the_permalink(); ?>"
                                title="<?php the_title_attribute(); ?>">
                                <h2 class="<?= $i > 1 ? 'u-h5 u-mv--' : 'u-mv-' ?>">
                                    <?php the_title(); ?>
                                </h2>
                            </a>
                        </header>

                        <ul class="c-excerpt__meta u-small u-truncate">
                            <li><?php the_author(); ?></li>
                            <li><span class="u-ic-folder"></span> <?php the_category(', '); ?></li>
                            <li><span class="u-ic-comment"></span> <?= get_comments_number() ?></li>
                        </ul>

                        <?php if ($i <= 1): ?>
                            <div class="c-excerpt__content u-mt- u-hidden-until@tablet">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif ?>

                    </div>

                    <time class="c-excerpt__date
                        <?= $i === 1 ? 'c-excerpt__date--big' : '' ?> u-center"
                        datetime="<?php the_time('c'); ?>">
                        <span>
                            <?php the_time('M'); ?>
                            <br><strong><?php the_time('d'); ?></strong>
                            <br><?php the_time('Y'); ?>
                        </span>
                    </time>

                </article>

            </div>

        <?php endwhile ?>
        <?php endif ?>

    </div>

    <!-- TODO: Pagination -->

</main>

<?php get_footer() ?>
