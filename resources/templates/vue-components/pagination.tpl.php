<?php
use AppTheme\Store;
if( Store::isset_then_set('vue-pagination-component') ) return;
?>

<!-- template for the pagination component -->
<script type="text/x-template" id="pagination-component">

    <nav class="o-layout o-layout--auto o-layout--middle u-mt+" role="navigation" aria-label="Pagination Navigation">

        <div class="o-layout__item u-pb-">
            <ol class="o-list-inline o-list-inline--1px">

                <li class="o-list-inline__item">
                    <button
                        class="c-btn c-btn--secondary c-btn--small c-btn--left"
                        :class="{'c-btn--square': !verbose}"
                        :disabled="currentPage <= 1 || isLoading"
                        v-on:click="previousPage"
                    >
                        <span class="u-ic-keyboard_arrow_left"></span>
                        <span v-if="verbose" class="u-hidden-until@tablet">
                            Vorherige Seite
                        </span>
                    </button>
                </li>
                <li v-if="!minimal"
                    v-for="n in buttons"
                    class="o-list-inline__item u-hidden-until@tablet">
                    <button
                        v-if="n == 'left' || n == 'right'"
                        class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square"
                        :class="{'is-active': n === currentPage}"
                        :disabled="isLoading"
                        v-on:click="changeRange(n)"
                    >
                        ...
                    </button>
                    <button
                        v-else
                        class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square"
                        :class="{'is-active': n === currentPage}"
                        :disabled="isLoading"
                        v-on:click="toPage(n)"
                    >
                        {{ n }}
                    </button>
                </li>
                <li class="o-list-inline__item">
                    <button
                        class="c-btn c-btn--secondary c-btn--small c-btn--right"
                        :class="{'c-btn--square': !verbose}"
                        :disabled="currentPage >= totalPages || isLoading"
                        v-on:click="nextPage"
                    >
                        <span v-if="verbose" class="u-hidden-until@tablet">
                            Nächste Seite
                        </span>
                        <span class="u-ic-keyboard_arrow_right"></span>
                    </button>
                </li>

            </ol><!--end c-primary-nav__list-->
        </div>

        <div class="o-layout__item u-pb-" v-show="isLoading">
            <div class="c-spinner"></div>
        </div>

        <div class="o-layout__item u-pb-" v-if="!minimal">
            {{ 1 + (currentPage - 1) * perPage }} - {{ currentPage * perPage }} von {{ total }}
        </div>

    </nav>

</script>