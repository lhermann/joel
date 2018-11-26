<?php
use function Tonik\Theme\App\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
?>

<!-- Vue root component -->
<div v-cloak
    id="<?= $id ?>"
    class="<?= $style_modifier ?>"
    data-vue="pagination"
    :init="init(<?= $options ?>)"
>

    <pagination-component
        class="u-mv+"
        :total="options.total"
        :per-page="options.perPage"
        :total-pages="options.totalPages"
        :current-page="options.currentPage"
        :verbosity="options.verbosity"
        @to-page="onPageClick"
    />

</div>

<!-- dependency components -->
<?php template('vue-components/medialist/pagination') ?>

