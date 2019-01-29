import algoliasearch from "algoliasearch/lite";
import algoliaAutocomplete from "autocomplete.js";

jQuery(function() {
    /* init Algolia client */
    const client = algoliasearch(
        algolia.application_id,
        algolia.search_api_key
    );

    /* setup default sources */
    var sources = [];
    jQuery.each(algolia.autocomplete.sources, function(i, config) {
        var suggestion_template = wp.template(config["tmpl_suggestion"]);
        sources.push({
            source: algoliaAutocomplete.sources.hits(
                client.initIndex(config["index_name"]),
                {
                    hitsPerPage: config["max_suggestions"],
                    attributesToSnippet: ["content:10"],
                    highlightPreTag: "__ais-highlight__",
                    highlightPostTag: "__/ais-highlight__"
                }
            ),
            templates: {
                header: function() {
                    return wp.template("autocomplete-header")({
                        label: _.escape(config["label"])
                    });
                },
                suggestion: function(hit) {
                    if (hit.escaped === true) {
                        return suggestion_template(hit);
                    }
                    hit.escaped = true;

                    for (let key in hit._highlightResult) {
                        /* We do not deal with arrays. */
                        if (
                            typeof hit._highlightResult[key].value !== "string"
                        ) {
                            continue;
                        }
                        hit._highlightResult[key].value = _.escape(
                            hit._highlightResult[key].value
                        );
                        hit._highlightResult[key].value = hit._highlightResult[
                            key
                        ].value
                            .replace(/__ais-highlight__/g, "<em>")
                            .replace(/__\/ais-highlight__/g, "</em>");
                    }

                    for (let key in hit._snippetResult) {
                        /* We do not deal with arrays. */
                        if (typeof hit._snippetResult[key].value !== "string") {
                            continue;
                        }

                        hit._snippetResult[key].value = _.escape(
                            hit._snippetResult[key].value
                        );
                        hit._snippetResult[key].value = hit._snippetResult[
                            key
                        ].value
                            .replace(/__ais-highlight__/g, "<em>")
                            .replace(/__\/ais-highlight__/g, "</em>");
                    }

                    return suggestion_template(hit);
                }
            }
        });
    });

    /* Setup dropdown menus */
    jQuery(algolia.autocomplete.input_selector).each(function(i) {
        const $searchInput = jQuery(this);

        const config = {
            debug: algolia.debug,
            hint: false,
            openOnFocus: true,
            appendTo: "body",
            templates: {
                empty: wp.template("autocomplete-empty")
            },
            autoWidth: false
        };

        if (algolia.powered_by_enabled) {
            config.templates.footer = wp.template("autocomplete-footer");
        }

        /* Instantiate autocomplete.js */
        const autocomplete = algoliaAutocomplete(
            $searchInput[0],
            config,
            sources
        ).on("autocomplete:selected", function(e, suggestion) {
            /* Redirect the user when we detect a suggestion selection. */
            window.location.href = suggestion.permalink;
        });

        /* Force the dropdown to be re-drawn on scroll to handle fixed containers. */
        jQuery(window).scroll(function() {
            if (
                autocomplete.autocomplete.getWrapper().style.display === "block"
            ) {
                autocomplete.autocomplete.close();
                autocomplete.autocomplete.open();
            }
        });
    });

    jQuery(document).on("click", ".algolia-powered-by-link", function(e) {
        e.preventDefault();
        window.location =
            "https://www.algolia.com/?utm_source=WordPress&utm_medium=extension&utm_content=" +
            window.location.hostname +
            "&utm_campaign=poweredby";
    });
});
