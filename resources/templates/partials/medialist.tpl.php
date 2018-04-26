<?php
use function AppTheme\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

    <!--
        :exec2="setParams(<?= $params ?>)"
        :exec3="setOptions(<?= $options ?>)"
    -->
<!-- Vue medialist root component -->
<section id="<?= $id ?>"
    class="<?= $style_modifier ?> jsMedialist"
    :init="init(<?= $options ?>, <?= $params ?>)">

    <header v-if="header" class="u-mv">
        <div class="o-pack o-pack--auto o-pack--middle">
            <div class="o-pack__item">
                <h2 class="u-mb0">{{ header }}</h2>
            </div>
            <div class="o-pack__item u-text-right u-hidden-until@tablet">

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





