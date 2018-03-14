<?php
use function AppTheme\template;
$params = str_replace('"', "'", json_encode($params));
?>

<!-- Vue medialist root component -->
<div id="<?= $id ?>"
    class="<?= $style_modifier ?> jsMedialist"
    :params="setParams(<?= $params ?>)">

    <ul class="c-medialist">

        <li class="c-medialist__item" v-for="(item, i) in recordings">

            <mediaitem-component
                :key="i"
                :item="item"
            />

        </li>

    </ul>

    <!-- pagination -->
    <!-- <pagination-component /> -->

</div>

<?php template('vue-components/mediaitem') ?>
<?php template('vue-components/mediaitem') ?>
<?php template('vue-components/mediaitem') ?>
<?php template('vue-components/pagination') ?>





