import instantsearch from 'instantsearch.js/es'
import searchBox from 'instantsearch.js/es/widgets/search-box/search-box'
import stats from 'instantsearch.js/es/widgets/stats/stats'
import hits from 'instantsearch.js/es/widgets/hits/hits'
import pagination from 'instantsearch.js/es/widgets/pagination/pagination'
import menu from 'instantsearch.js/es/widgets/menu/menu'
import hierarchicalMenu from 'instantsearch.js/es/widgets/hierarchical-menu/hierarchical-menu'

jQuery(function () {
  if (jQuery('#algolia-search-box').length > 0) {
    if (
      algolia.indices.searchable_posts === undefined &&
            jQuery('.admin-bar').length > 0
    ) {
      alert(
        "It looks like you haven't indexed the searchable posts index. Please head to the Indexing page of the Algolia Search plugin and index it.",
      )
    }

    const search = instantsearch({
      appId: algolia.application_id,
      apiKey: algolia.search_api_key,
      indexName: algolia.indices.searchable_posts.name,
      urlSync: {
        mapping: { q: 's' },
        trackedParameters: ['query'],
      },
      searchParameters: {
        facetingAfterDistinct: true,
        highlightPreTag: '__ais-highlight__',
        highlightPostTag: '__/ais-highlight__',
      },
    })

    /* Search box widget */
    search.addWidget(
      searchBox({
        container: '#algolia-search-box',
        placeholder: 'Search for...',
        wrapInput: false,
        poweredBy: algolia.powered_by_enabled,
        magnifier: false,
        reset: false,
      }),
    )

    /* Stats widget */
    search.addWidget(
      stats({
        container: '#algolia-stats',
        templates: {
          body: function (data) {
            return (
              data.nbHits +
                            ' Treffer in ' +
                            data.processingTimeMS +
                            'ms'
            )
          },
        },
      }),
    )

    /* Hits widget */
    search.addWidget(
      hits({
        container: '#algolia-hits',
        hitsPerPage: 10,
        templates: {
          empty:
                        'F&uuml;r "<strong>{{query}}</strong>" wurden keine Ergebnisse gefunden.',
          item: wp.template('instantsearch-hit'),
        },
        transformData: {
          item: function (hit) {
            function replace_highlights_recursive (item) {
              if (
                item instanceof Object &&
                                item.hasOwnProperty('value')
              ) {
                item.value = _.escape(item.value)
                item.value = item.value
                  .replace(/__ais-highlight__/g, '<em>')
                  .replace(/__\/ais-highlight__/g, '</em>')
              } else {
                for (var key in item) {
                  item[key] = replace_highlights_recursive(
                    item[key],
                  )
                }
              }
              return item
            }

            hit._highlightResult = replace_highlights_recursive(
              hit._highlightResult,
            )
            hit._snippetResult = replace_highlights_recursive(
              hit._snippetResult,
            )

            return hit
          },
        },
      }),
    )

    /* Pagination widget */
    search.addWidget(
      pagination({
        container: '#algolia-pagination',
        cssClasses: {
          root: 'o-list-inline o-list-inline--1px',
          item:
                        'o-list-inline__item c-btn c-btn--secondary c-btn--small c-btn--edgy c-btn--square',
          first: 'c-btn--left',
          last: 'c-btn--right',
          active: 'is-active',
        },
      }),
    )

    const showMore = {
      templates: {
        active:
                    '<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-minus"></span> <small>verbergen</small></button>',
        inactive:
                    '<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-plus"></span> <small>mehr anzeigen</small></button>',
      },
      limit: 20,
    }

    /* Post types refinement widget */
    search.addWidget(
      menu({
        container: '#facet-post-types',
        attributeName: 'post_type_label',
        sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
        templates: {
          header: '<h3 class="u-h4 u-mb-">Filter</h3>',
        },
        cssClasses: {
          link: 'u-truncate',
        },
        limit: 10,
        showMore: showMore,
      }),
    )

    /* Speakers refinement widget */
    search.addWidget(
      menu({
        container: '#facet-speakers',
        attributeName: 'taxonomies.speakers',
        sortBy: ['count'],
        templates: {
          // <?= __('Speakers', config('textdomain')) ?>
          header: '<h3 class="u-h4 u-mb-">Sprecher</h3>',
        },
        cssClasses: {
          link: 'u-truncate',
        },
        limit: 10,
        showMore: showMore,
      }),
    )

    // <span class="u-ic-minus"></span><small>verbergen</small>

    /* Speakers refinement widget */
    search.addWidget(
      menu({
        container: '#facet-series',
        attributeName: 'taxonomies.series',
        sortBy: ['count'],
        templates: {
          // <?= __('Series', config('textdomain')) ?>
          header: '<h3 class="u-h4 u-mb-">Serien</h3>',
        },
        cssClasses: {
          link: 'u-truncate',
        },
        limit: 10,
        showMore: showMore,
      }),
    )

    /* Topics refinement widget */
    search.addWidget(
      hierarchicalMenu({
        container: '#facet-topics',
        separator: ' > ',
        sortBy: ['count'],
        attributes: [
          'taxonomies_hierarchical.topics.lvl0',
          'taxonomies_hierarchical.topics.lvl1',
          'taxonomies_hierarchical.topics.lvl2',
        ],
        templates: {
          // <?= __('Topics', config('textdomain')) ?>
          header: '<h3 class="u-h4 u-mb-">Themen</h3>',
        },
        cssClasses: {
          link: 'u-truncate',
        },
        limit: 10,
        showMore: showMore,
      }),
    )

    /* Categories refinement widget */
    // search.addWidget(
    //  hierarchicalMenu({
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
    //  refinementList({
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
    search.start()

    jQuery('#algolia-search-box input')
      .attr('type', 'search')
      .select()
  }
})
