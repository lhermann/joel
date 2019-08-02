<?php
use function Tonik\Theme\App\config;
?>
<article class="c-article">

    <header class="c-article__header">

        <h1 class="u-roboto"><?php the_title(); ?></h1>

        <div class="c-article__meta u-mb">
            <?php the_author(); ?>
            <br><time datetime="<?php the_time('c'); ?>"><?php the_date(); ?></time>
        </div>

    </header>


    <div class="c-article__body c-wp-styles">

        <?php the_content(); ?>


        <div class="c-article__meta u-mt++">
            <span class="u-ic-folder"></span> <?php the_category(', '); ?>
        </div>

    </div>

    <footer class="c-article__footer u-mt+">
        <hr>

        <?php $author_id = get_the_author_meta('ID') ?>
        <div class="o-flag u-mv">
            <div class="o-flag__img">
                <?= get_avatar( $author_id, 80, null, 'Image of Author', ['class' => 'u-rounded']); ?>
            </div>
            <div class="o-flag__body u-text-">
                <p class="u-text+ u-mb--">
                    <?= __('By', config('textdomain')) ?>
                    <strong>
                        <a class="c-link c-link--dotted" href="<?= get_author_posts_url($author_id) ?>">
                            <?php the_author_meta( 'display_name' ); ?>
                        </a>
                    </strong>
                </p>
                <p class="u-textmuted">
                    <?php the_author_meta( 'description' ); ?>
                </p>
            </div>
        </div>

        <hr class="u-mb0">
    </footer>

</article>
