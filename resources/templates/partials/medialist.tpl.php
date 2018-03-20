<?php
use function AppTheme\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue medialist root component -->
<div id="<?= $id ?>"
    class="<?= $style_modifier ?> jsMedialist"
    :params="setParams(<?= $params ?>)"
    :options="setOptions(<?= $options ?>)">

    <div v-show="isLoading &amp;&amp; !recordings.length" class="u-m">
        <div class="c-spinner c-spinner--large"></div>
    </div>

    <ul class="c-medialist">

        <li class="c-medialist__item" v-for="(item, i) in recordings">

            <mediaitem-component
                :key="i"
                :item="item"
            />

        </li>

    </ul>

    <!-- pagination -->
    <pagination-component v-if="pagination"
        :total="total"
        :per-page="perPage"
        :total-pages="totalPages"
        :current-page="currentPage"
        :verbosity="pagination"
        :is-loading="isLoading"
        v-on:to-page="changePage"
    />

</div>

<!-- dependency components -->
<?php template('vue-components/mediaitem') ?>
<?php template('vue-components/pagination') ?>





