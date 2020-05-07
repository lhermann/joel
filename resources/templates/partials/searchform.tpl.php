<?php
use function Tonik\Theme\App\config;
?>

<form class="c-search-bar <?= $style_modifier ?>" method="get" action="/">
    <input class="c-search-bar__input" type="text" name="s" value="<?php the_search_query(); ?>" placeholder="<?= __('Search archive...', config('textdomain')) ?>">
    <button class="c-btn c-search-bar__btn" type="submit" for="search" value="Suche"><span class="u-ic u-ic-search"></span></button>
</form>
