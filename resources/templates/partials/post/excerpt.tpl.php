<?php
use function Tonik\Theme\App\config;
?>
<article class="c-excerpt <?= $style_modifier ?>">

    <div class="c-excerpt__image">
        <?= get_the_post_thumbnail(null, '360p') ?>
    </div>

    <a class="c-excerpt__clickspace"
        href="<?php the_permalink(); ?>">
        <?php __('Read article', config('textdomain')) ?>
    </a>

    <div class="c-excerpt__body <?= $hero ? '' : 'u-pv-' ?>">

        <header class="c-excerpt__content">
            <a class="c-link c-link--white c-link--dotted"
                href="<?php the_permalink(); ?>"
                title="<?php the_title_attribute(); ?>">
                <h2 class="<?= $hero ? 'u-mv-' : 'u-h5 u-mv--' ?>">
                    <?php the_title(); ?>
                </h2>
            </a>
        </header>

        <ul class="c-excerpt__meta u-small u-truncate">
            <li><?php the_author(); ?></li>
            <li><span class="u-ic-folder"></span> <?php the_category(', '); ?></li>
            <li><span class="u-ic-comment"></span> <?= get_comments_number() ?></li>
        </ul>

        <?php if ($hero): ?>
            <div class="c-excerpt__content u-mt- u-hidden-until@tablet">
                <?php the_excerpt(); ?>
            </div>
        <?php endif ?>

    </div>

    <time class="c-excerpt__date
        <?= $hero ? 'c-excerpt__date--big' : '' ?> u-center"
        datetime="<?php the_time('c'); ?>">
        <span>
            <?php the_time('M'); ?>
            <br><strong><?php the_time('d'); ?></strong>
            <br><?php the_time('Y'); ?>
        </span>
    </time>

</article>
