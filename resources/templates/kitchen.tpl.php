<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main class="o-wrapper u-pv+">
    <h1>Kitchen</h1>

    <h2>Colors</h2>
    <table class="c-table c-table--small u-mr">
        <tr><th>$c-brand-1</th><td><div class="c-color u-bg-brand-1"></div></td></tr>
        <tr><th>$c-brand-2</th><td><div class="c-color u-bg-brand-2"></div></td></tr>
        <tr><th>$c-brand-3</th><td><div class="c-color u-bg-brand-3"></div></td></tr>
        <tr><th>$c-brand-4</th><td><div class="c-color u-bg-brand-4"></div></td></tr>
        <tr><th>$c-brand-5</th><td><div class="c-color u-bg-brand-5"></div></td></tr>
        <tr><th>$c-brand-6</th><td><div class="c-color u-bg-brand-6"></div></td></tr>
        <tr><th>$c-brand-7</th><td><div class="c-color u-bg-brand-7"></div></td></tr>
        <tr><th>$c-brand-8</th><td><div class="c-color u-bg-brand-8"></div></td></tr>
        <tr><th>$c-brand-9</th><td><div class="c-color u-bg-brand-9"></div></td></tr>
    </table>

</main>

<style>
.c-color {
    width: 48px;
    height: 48px;
    border-radius: 3px;
}
</style>

<?php get_footer() ?>
