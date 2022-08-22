<?php
use function Tonik\Theme\App\config;
?>
<article class="c-article">

    <header class="c-article__header">

        <h1 class="u-roboto mb-1"><?php the_title(); ?></h1>

        <div class="c-article__meta mb-12">
            <time datetime="<?php the_time('c'); ?>"><?php the_date(); ?></time>
        </div>

    </header>


    <div class="c-article__body c-wp-styles">

        <?php the_content(); ?>

    </div>

</article>
