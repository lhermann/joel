<?php
use function Tonik\Theme\App\config;
?>

<?php get_header(); ?>

<main role="main" class="o-wrapper u-pv+">

    <div class="o-layout o-layout--large u-break-wrapper@until-tablet">

        <aside class="o-layout__item u-border-right u-pr u-1/3 u-hidden-until@tablet">
            <section class="ais-facets" id="facet-post-types"></section>
            <section class="ais-facets" id="facet-speakers"></section>
            <section class="ais-facets" id="facet-series"></section>
            <section class="ais-facets" id="facet-topics"></section>
        </aside>

        <section class="o-layout__item u-2/3@tablet u-1/1">


            <header class="u-mb+">
                <div id="algolia-search-box">
                    <div id="algolia-stats"></div>
                    <div class="algolia-search-icon">
                        <span class="u-ic u-ic-search"></span>
                    </div>
                </div>
            </header>

            <div class="c-medialist u-mb+" id="algolia-hits">
            </div>

            <nav id="algolia-pagination" role="navigation" aria-label="Pagination Navigation"></nav>

        </section>
    </div>



    <script type="text/html" id="tmpl-instantsearch-hit">
        <# if ( data.post_type == "recordings" ) { #>
        <article class="c-medialist__item">
            <div class="o-media c-mediaitem c-mediaitem--video">
                <a class="c-mediaitem__link" href="{{ data.permalink }}" title="{{ data.post_title }}"></a>
                <div class="o-media__img c-mediaitem__img">
                    <a class="c-mediaitem__imglink" href="{{ data.permalink }}">
                        <# if ( data.thumbnail ) { #>
                        <img src="{{ data.thumbnail }}" alt="{{ data.post_title }}" itemprop="image">
                        <# } #>
                        <# if ( data.length ) { #>
                        <div class="c-mediaitem__length"><div>{{ data.length }}</div></div>
                        <# } #>
                    </a>
                </div>
                <div class="o-media__body c-mediaitem__body">
                    <h3 class="c-mediaitem__title" itemprop="name headline">
                        <a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a>
                    </h3>
                    <ul class="c-mediaitem__meta u-truncate">
                        <# if ( data.speakers ) { #><li>{{{ data.speakers }}} Klicks</li><# } #>
                        <# if ( data.views ) { #><li>{{ data.views }} Klicks</li><# } #>
                        <# if ( data.date_human ) { #><li>{{ data.date_human }}</li><# } #>
                    </ul>
                </div>
            </div>
        </article>
        <# } else { #>
        <article class="c-medialist__item" itemtype="http://schema.org/Article">
            <div class="o-media c-mediaitem c-mediaitem--post">
                <a class="c-mediaitem__link" href="{{ data.permalink }}" title="{{ data.post_title }}"></a>
                <div class="o-media__img c-mediaitem__img">
                    <a class="c-mediaitem__imglink" href="{{ data.permalink }}">
                        <# if ( data.images.thumbnail ) { #>
                        <img src="{{ data.images.thumbnail.url }}" alt="{{ data.post_title }}" itemprop="image">
                        <# } #>
                        <div class="c-mediaitem__length"><div>{{ data.post_date_formatted }}</div></div>
                    </a>
                </div>
                <div class="o-media__body c-mediaitem__body">
                    <h3 class="c-mediaitem__title" itemprop="name headline">
                        <a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a>
                    </h3>
                    <# if ( data._snippetResult['content'] ) { #>
                    <div class="c-mediaitem__content">
                          {{{ data._snippetResult['content'].value }}}
                    </div>
                    <# } #>
                </div>
            </div>
        </article>
        <# } #>
    </script>

</main>

<?php get_footer() ?>
