<?php
use function AppTheme\template;
use function AppTheme\config;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

    <!--
        :exec2="setParams(<?= $params ?>)"
        :exec3="setOptions(<?= $options ?>)"
    -->
<!-- Vue medialist root component -->
<section v-cloak id="<?= $id ?>"
    class="<?= $style_modifier ?> jsMedialist"
    :init="init(<?= $options ?>, <?= $params ?>)">

    <header v-if="title || tabs || sorting" class="u-mv">
        <div class="o-pack o-pack--auto o-pack--middle">
            <div v-if="title" class="o-pack__item u-1/2@tablet">
                <h2 class="u-mb0">{{ title }}</h2>
            </div>
            <nav v-if="tabs"
                class="o-pack__item u-1/2@tablet"
                role="navigation" aria-label="Tabs Navigation">
                <!-- TODO: Being able to query series of speaker -->

                <ul class="c-button-group c-button-group--stretch@tablet">
                    <li class="c-button-group__item">
                        <button class="c-btn c-btn--secondary c-btn--small c-btn--left is-active">
                            <?= __('Videos', config('textdomain')) ?>
                        </button>
                    </li>
                    <li class="c-button-group__item">
                        <button class="c-btn c-btn--secondary c-btn--small c-btn--right">
                            <?= _x('Series', 'taxonomy general name', config('textdomain')) ?>
                        </button>
                    </li>
                </ul>

            </nav>
            <div v-if="sorting"
                class="o-pack__item u-1/2@tablet u-text-right">

                <sorting-component
                    :options="sortingOptions"
                    :current-option="currentSortingOption"
                    v-on:select="onSelectOption"
                />

            </div>
        </div>
    </header>

    <div v-show="isLoading &amp;&amp; !items.length" class="u-m">
        <div class="c-spinner c-spinner--large"></div>
    </div>

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
        :total="total"
        :per-page="perPage"
        :total-pages="totalPages"
        :current-page="currentPage"
        :verbosity="pagination"
        :is-loading="isLoading"
        v-on:to-page="onChangePage"
    />

</section>

<!-- dependency components -->
<?php template('vue-components/mediaitem') ?>
<?php template('vue-components/pagination') ?>
<?php template('vue-components/sorting') ?>





