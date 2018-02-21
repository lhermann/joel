<?php
use function AppTheme\config;
?>

<form class="c-search-bar <?= $style_modifier ?>" method="get" id="searchform" action="/">
    <input class="c-search-bar__input" type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="<?= __('Search archive...', config('textdomain')) ?>">
    <button class="c-btn c-search-bar__btn" type="submit" for="search" value="Suche"><span class="u-ic u-ic-search"></span></button>
</form>
