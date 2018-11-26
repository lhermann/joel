<?php
use function Tonik\Theme\App\template;
use Tonik\Theme\App\Store;
if( Store::isset_then_set('vue-medialist-component') ) return;
?>

<!-- template for the medialist component -->
<?= '<script type="text/x-template" id="medialist-component">' ?>
    <div>

        <header v-if="title || tabs || sorting" class="u-pv">
            <div class="o-flex o-flex--middle o-flex--between">
                <div v-if="title" class="o-flex__item">
                    <h2 class="u-mb0">{{ title }}</h2>
                </div>
                <div class="o-flex__item">
                    <div class="c-spinner" v-show="isLoading"></div>
                </div>
                <div v-if="sorting"
                    class="o-flex__item u-text-right">

                    <sorting-component
                        :options="sortingOptions"
                        :current-option="currentSortingOption"
                        v-on:select="onSelectOption"
                    />

                </div>
            </div>
        </header>

        <ul class="c-medialist" :class="medialistClass">
            <li v-for="(item, i) in items" class="c-medialist__item">

                <mediaitem-component
                    :key="i"
                    :item="item"
                />

            </li>
        </ul>

        <!-- pagination -->
        <pagination-component v-if="pagination &amp;&amp; totalPages > 1"
            class="u-mv+"
            :total="total"
            :per-page="perPage"
            :total-pages="totalPages"
            :current-page="currentPage"
            :verbosity="pagination"
            :is-loading="isLoading"
            v-on:to-page="onChangePage"
        />

    </div>
<?= '</script>' ?>

<!-- dependency components -->
<?php template('vue-components/medialist/mediaitem') ?>
<?php template('vue-components/medialist/pagination') ?>
<?php template('vue-components/medialist/sorting') ?>





