(function(e){function t(t){for(var i,s,o=t[0],r=t[1],c=t[2],p=0,h=[];p<o.length;p++)s=o[p],Object.prototype.hasOwnProperty.call(n,s)&&n[s]&&h.push(n[s][0]),n[s]=0;for(i in r)Object.prototype.hasOwnProperty.call(r,i)&&(e[i]=r[i]);u&&u(t);while(h.length)h.shift()();return l.push.apply(l,c||[]),a()}function a(){for(var e,t=0;t<l.length;t++){for(var a=l[t],i=!0,o=1;o<a.length;o++){var r=a[o];0!==n[r]&&(i=!1)}i&&(l.splice(t--,1),e=s(s.s=a[0]))}return e}var i={},n={vanilla:0},l=[];function s(t){if(i[t])return i[t].exports;var a=i[t]={i:t,l:!1,exports:{}};return e[t].call(a.exports,a,a.exports,s),a.l=!0,a.exports}s.m=e,s.c=i,s.d=function(e,t,a){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},s.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(s.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)s.d(a,i,function(t){return e[t]}.bind(null,i));return a},s.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/wp-content/themes/joel/dist/";var o=window["webpackJsonp"]=window["webpackJsonp"]||[],r=o.push.bind(o);o.push=t,o=o.slice();for(var c=0;c<o.length;c++)t(o[c]);var u=r;l.push([1,"chunk-vendors"]),a()})({1:function(e,t,a){e.exports=a("38c2")},"38c2":function(e,t,a){"use strict";a.r(t);a("e260"),a("e6cf"),a("cca6"),a("a79d"),a("b0c0"),a("ac1f"),a("5319");var i=a("4caa"),n=a("508a"),l=a("8a25"),s=a("1d02"),o=a("4ce1"),r=a("9cd9"),c=a("2663");"undefined"!==typeof jQuery&&"undefined"!==typeof algolia&&jQuery((function(){if(jQuery("#algolia-search-box").length>0){void 0===algolia.indices.searchable_posts&&jQuery(".admin-bar").length>0&&alert("It looks like you haven't indexed the searchable posts index. Please head to the Indexing page of the Algolia Search plugin and index it.");var e=Object(i["a"])({appId:algolia.application_id,apiKey:algolia.search_api_key,indexName:algolia.indices.searchable_posts.name,urlSync:{mapping:{q:"s"},trackedParameters:["query"]},searchParameters:{facetingAfterDistinct:!0,highlightPreTag:"__ais-highlight__",highlightPostTag:"__/ais-highlight__"}});e.addWidget(Object(n["a"])({container:"#algolia-search-box",placeholder:"Search for...",wrapInput:!1,poweredBy:algolia.powered_by_enabled,magnifier:!1,reset:!1})),e.addWidget(Object(l["a"])({container:"#algolia-stats",templates:{body:function(e){return e.nbHits+" Treffer in "+e.processingTimeMS+"ms"}}})),e.addWidget(Object(s["a"])({container:"#algolia-hits",hitsPerPage:10,templates:{empty:'F&uuml;r "<strong>{{query}}</strong>" wurden keine Ergebnisse gefunden.',item:wp.template("instantsearch-hit")},transformData:{item:function(e){function t(e){if(e instanceof Object&&e.hasOwnProperty("value"))e.value=_.escape(e.value),e.value=e.value.replace(/__ais-highlight__/g,"<em>").replace(/__\/ais-highlight__/g,"</em>");else for(var a in e)e[a]=t(e[a]);return e}return e._highlightResult=t(e._highlightResult),e._snippetResult=t(e._snippetResult),e}}})),e.addWidget(Object(o["a"])({container:"#algolia-pagination",cssClasses:{root:"o-list-inline o-list-inline--1px",item:"o-list-inline__item c-btn c-btn--secondary c-btn--small c-btn--edgy c-btn--square",first:"c-btn--left",last:"c-btn--right",active:"is-active"}}));var t={templates:{active:'<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-minus"></span> <small>verbergen</small></button>',inactive:'<button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-mt-"><span class="u-ic-plus"></span> <small>mehr anzeigen</small></button>'},limit:20};e.addWidget(Object(r["a"])({container:"#facet-post-types",attributeName:"post_type_label",sortBy:["isRefined:desc","count:desc","name:asc"],templates:{header:'<h3 class="u-h4 u-mb-">Filter</h3>'},cssClasses:{link:"u-truncate"},limit:10,showMore:t})),e.addWidget(Object(r["a"])({container:"#facet-speakers",attributeName:"taxonomies.speakers",sortBy:["count"],templates:{header:'<h3 class="u-h4 u-mb-">Sprecher</h3>'},cssClasses:{link:"u-truncate"},limit:10,showMore:t})),e.addWidget(Object(r["a"])({container:"#facet-series",attributeName:"taxonomies.series",sortBy:["count"],templates:{header:'<h3 class="u-h4 u-mb-">Serien</h3>'},cssClasses:{link:"u-truncate"},limit:10,showMore:t})),e.addWidget(Object(c["a"])({container:"#facet-topics",separator:" > ",sortBy:["count"],attributes:["taxonomies_hierarchical.topics.lvl0","taxonomies_hierarchical.topics.lvl1","taxonomies_hierarchical.topics.lvl2"],templates:{header:'<h3 class="u-h4 u-mb-">Themen</h3>'},cssClasses:{link:"u-truncate"},limit:10,showMore:t})),e.start(),jQuery("#algolia-search-box input").attr("type","search").select()}}));var u=a("bb29"),p=a.n(u),h=a("c423"),g=a.n(h);"undefined"!==typeof jQuery&&"undefined"!==typeof algolia&&jQuery((function(){var e=p()(algolia.application_id,algolia.search_api_key),t=[];jQuery.each(algolia.autocomplete.sources,(function(a,i){var n=wp.template(i.tmpl_suggestion);t.push({source:g.a.sources.hits(e.initIndex(i.index_name),{hitsPerPage:i.max_suggestions,attributesToSnippet:["content:10"],highlightPreTag:"__ais-highlight__",highlightPostTag:"__/ais-highlight__"}),templates:{header:function(){return wp.template("autocomplete-header")({label:_.escape(i.label)})},suggestion:function(e){if(!0===e.escaped)return n(e);for(var t in e.escaped=!0,e._highlightResult)"string"===typeof e._highlightResult[t].value&&(e._highlightResult[t].value=_.escape(e._highlightResult[t].value),e._highlightResult[t].value=e._highlightResult[t].value.replace(/__ais-highlight__/g,"<em>").replace(/__\/ais-highlight__/g,"</em>"));for(var a in e._snippetResult)"string"===typeof e._snippetResult[a].value&&(e._snippetResult[a].value=_.escape(e._snippetResult[a].value),e._snippetResult[a].value=e._snippetResult[a].value.replace(/__ais-highlight__/g,"<em>").replace(/__\/ais-highlight__/g,"</em>"));return n(e)}}})})),jQuery(algolia.autocomplete.input_selector).each((function(e){var a=jQuery(this),i={debug:algolia.debug,hint:!1,openOnFocus:!0,appendTo:"body",templates:{empty:wp.template("autocomplete-empty")},autoWidth:!1};algolia.powered_by_enabled&&(i.templates.footer=wp.template("autocomplete-footer"));var n=g()(a[0],i,t).on("autocomplete:selected",(function(e,t){window.location.href=t.permalink}));jQuery(window).scroll((function(){"block"===n.autocomplete.getWrapper().style.display&&(n.autocomplete.close(),n.autocomplete.open())}))})),jQuery(document).on("click",".algolia-powered-by-link",(function(e){e.preventDefault(),window.location="https://www.algolia.com/?utm_source=WordPress&utm_medium=extension&utm_content="+window.location.hostname+"&utm_campaign=poweredby"}))}));var d=a("e409"),m=a.n(d),f=document.getElementById("siteWrapper"),b=!0;function y(e){var t=m()(this).attr("data-target");"open"==m()(this).attr("data-action")?(m()("#"+t).addClass("c-flyin--open"),m()(f).addClass("c-site-wrapper--faded"),b=!1):(m()("#"+t).removeClass("c-flyin--open"),m()(f).removeClass("c-site-wrapper--faded"),b=!0)}m()(".jsFlyinBtn").on("click",y),f&&(f.ontouchmove=function(e){if(b)return!0;e.preventDefault()})}});