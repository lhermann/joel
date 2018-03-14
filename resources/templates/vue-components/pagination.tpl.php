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
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--left" disabled="">
                        <span class="u-ic-keyboard_arrow_left"></span>
                        <span class="u-hidden-until@tablet">Vorherige Seite</span>
                    </button>
                </li>

                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square is-active">
                        1
                    </button>
                </li>
                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square ">
                        2
                    </button>
                </li>
                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square ">
                        3
                    </button>
                </li>
                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square ">
                        4
                    </button>
                </li>
                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square ">
                        ...
                    </button>
                </li>
                <li class="o-list-inline__item u-hidden-until@tablet">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                        c-btn--square ">
                        23
                    </button>
                </li>

                <li class="o-list-inline__item">
                    <button class="c-btn c-btn--secondary c-btn--small c-btn--right">
                        <span class="u-hidden-until@tablet">Nächste Seite</span>
                        <span class="u-ic-keyboard_arrow_right"></span>
                    </button>
                </li>

            </ol><!--end c-primary-nav__list-->
        </div>

        <div class="o-layout__item u-pb-">
            1 - 60 von 300
        </div>

    </nav>

</script>
