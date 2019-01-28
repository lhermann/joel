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



    <script defer type="text/javascript">
        jQuery(function() {
            if(jQuery('#algolia-search-box').length > 0) {

                if (algolia.indices.searchable_posts === undefined && jQuery('.admin-bar').length > 0) {
                    alert('It looks like you haven\'t indexed the searchable posts index. Please head to the Indexing page of the Algolia Search plugin and index it.');
                }

                /* Instantiate instantsearch.js */
                var search = instantsearch({
                    appId: algolia.application_id,
                    apiKey: algolia.search_api_key,
                    indexName: algolia.indices.searchable_posts.name,
                    urlSync: {
                        mapping: {'q': 's'},
                        trackedParameters: ['query']
                    },
                    searchParameters: {
                        facetingAfterDistinct: true,
                        highlightPreTag: '__ais-highlight__',
                        highlightPostTag: '__/ais-highlight__'
                    }
                });

                /* Search box widget */
                search.addWidget(
                    instantsearch.widgets.searchBox({
                        container: '#algolia-search-box',
                        placeholder: 'Search for...',
                        wrapInput: false,
                        poweredBy: algolia.powered_by_enabled
                    })
                );

                /* Stats widget */
                search.addWidget(
                    instantsearch.widgets.stats({
                        container: '#algolia-stats',
                        templates: {
                            body: function(data) {
                                return data.nbHits + ' Treffer in ' + data.processingTimeMS + 'ms'
                            }
                        }
                    })
                );

                /* Hits widget */
                search.addWidget(
                    instantsearch.widgets.hits({
                        container: '#algolia-hits',
                        hitsPerPage: 10,
                        templates: {
                            empty: 'F&uuml;r "<strong>{{query}}</strong>" wurden keine Ergebnisse gefunden.',
                            item: wp.template('instantsearch-hit')
                        },
                        transformData: {
                            item: function (hit) {

                                function replace_highlights_recursive (item) {
                                  if( item instanceof Object && item.hasOwnProperty('value')) {
                                      item.value = _.escape(item.value);
                                      item.value = item.value.replace(/__ais-highlight__/g, '<em>').replace(/__\/ais-highlight__/g, '</em>');
                                  } else {
                                      for (var key in item) {
                                          item[key] = replace_highlights_recursive(item[key]);
                                      }
                                  }
                                  return item;
                                }

                                hit._highlightResult = replace_highlights_recursive(hit._highlightResult);
                                hit._snippetResult = replace_highlights_recursive(hit._snippetResult);

                                return hit;
                            }
                        }
                    })
                );

                /* Pagination widget */
                search.addWidget(
                    instantsearch.widgets.pagination({
                        container: '#algolia-pagination',
                        cssClasses: {
                            root: "o-list-inline o-list-inline--1px",
                            item: "o-list-inline__item c-btn c-btn--secondary c-btn--small c-btn--edgy c-btn--square",
                            first: "c-btn--left",
                            last: "c-btn--right",
                            active: "is-active"
                        },
                    })
                );

                var showMore = {
                    templates: {
                        active: '<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-minus"></span> <small>verbergen</small></button>',
                        inactive: '<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-plus"></span> <small>mehr anzeigen</small></button>'
                    },
                    limit: 20
                };

                /* Post types refinement widget */
                search.addWidget(
                    instantsearch.widgets.menu({
                        container: '#facet-post-types',
                        attributeName: 'post_type_label',
                        sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
                        templates: {
                            header: '<h3 class="u-h4 u-mb-">Filter</h3>'
                        },
                        cssClasses: {
                            link: "u-truncate"
                        },
                        limit: 10,
                        showMore: showMore
                    })
                );

                /* Speakers refinement widget */
                search.addWidget(
                    instantsearch.widgets.menu({
                        container: '#facet-speakers',
                        attributeName: 'taxonomies.speakers',
                        sortBy: ['count'],
                        templates: {
                            header: '<h3 class="u-h4 u-mb-"><?= __('Speakers', config('textdomain')) ?></h3>'
                        },
                        cssClasses: {
                            link: "u-truncate"
                        },
                        limit: 10,
                        showMore: showMore
                    })
                );

                // <span class="u-ic-minus"></span><small>verbergen</small>

                /* Speakers refinement widget */
                search.addWidget(
                    instantsearch.widgets.menu({
                        container: '#facet-series',
                        attributeName: 'taxonomies.series',
                        sortBy: ['count'],
                        templates: {
                            header: '<h3 class="u-h4 u-mb-"><?= __('Series', config('textdomain')) ?></h3>'
                        },
                        cssClasses: {
                            link: "u-truncate"
                        },
                        limit: 10,
                        showMore: showMore
                    })
                );

                /* Topics refinement widget */
                search.addWidget(
                    instantsearch.widgets.hierarchicalMenu({
                        container: '#facet-topics',
                        separator: ' > ',
                        sortBy: ['count'],
                        attributes: ['taxonomies_hierarchical.topics.lvl0', 'taxonomies_hierarchical.topics.lvl1', 'taxonomies_hierarchical.topics.lvl2'],
                        templates: {
                            header: '<h3 class="u-h4 u-mb-"><?= __('Topics', config('textdomain')) ?></h3>'
                        },
                        cssClasses: {
                            link: "u-truncate"
                        },
                        limit: 10,
                        showMore: showMore
                    })
                );

                /* Categories refinement widget */
                // search.addWidget(
                //  instantsearch.widgets.hierarchicalMenu({
                //      container: '#facet-categories',
                //      separator: ' > ',
                //      sortBy: ['count'],
                //      attributes: ['taxonomies_hierarchical.category.lvl0', 'taxonomies_hierarchical.category.lvl1', 'taxonomies_hierarchical.category.lvl2'],
                //      templates: {
                //          header: '<h3 class="u-h4 u-mb-">Categories</h3>'
                //      }
                //  })
                // );

                /* Tags refinement widget */
                // search.addWidget(
                //  instantsearch.widgets.refinementList({
                //      container: '#facet-tags',
                //      attributeName: 'taxonomies.post_tag',
                //      operator: 'and',
                //      limit: 15,
                //      sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
                //      templates: {
                //          header: '<h3 class="u-h4 u-mb-">Tags</h3>'
                //      }
                //  })
                // );

                /* Start */
                search.start();

                jQuery('#algolia-search-box input').attr('type', 'search').select();
            }
        });
    </script>

</main>

<?php get_footer() ?>
