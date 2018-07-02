<?php
use function Tonik\Theme\App\asset_path;
$promo_list = array(
    (object) [
        'title' => 'Prophetie<br>&amp; Zeitgeschehen',
        'img' => asset_path('images/promo-prophecy-480x270.jpg'),
        'url' => get_page_link(6782)
    ],
    (object) [
        'title' => 'Erweckung<br>&amp; Evangelium',
        'img' => asset_path('images/promo-health-480x270.jpg'),
        'url' => get_page_link(6782)
    ],
    (object) [
        'title' => 'Gesundheit<br>&amp; Familie',
        'img' => asset_path('images/promo-family-480x270.jpg'),
        'url' => get_page_link(6782)
    ],
    (object) [
        'title' => 'Tägliche<br> Andachten',
        'img' => asset_path('images/promo-devotional-480x270.jpg'),
        'url' => get_page_link(6782)
    ],
    (object) [
        'title' => 'Fragen<br> zur Bibel',
        'img' => asset_path('images/promo-bible-480x270.jpg'),
        'url' => get_page_link(6782)
    ]
);
?>

<div class="o-overflow <?= $style_modifier ?> is-left">

    <ul class="o-layout o-layout--overflow o-overflow__content" data-space-between="21">

        <?php foreach ($promo_list as $item): ?>
            <li class="o-layout__item u-min-220 u-1/5 u-transition jsFadeSiblings">
                <div class="c-media-promo ">

                    <div class="c-media-promo__head">
                        <a class="c-media-promo__title" href="<?= $item->url ?>">
                            <?= $item->title ?>
                        </a>
                        <a class="c-btn c-btn--square c-media-promo__btn" href="<?= $item->url ?>">
                            <span class="u-ic-keyboard_arrow_right"></span>
                        </a>
                    </div>

                    <a class="c-media-promo__img" href="<?= $item->url ?>">
                        <img src="<?= $item->img ?>" alt="">
                    </a>

                </div>
            </li>
        <?php endforeach ?>

    </ul><!--end tout-list-->

    <div class="o-overflow__nav o-overflow__nav--left">
        <button class="c-btn c-btn--icon c-btn--dark c-btn--small c-btn--right c-btn--wall jsNavLeft">
            <span class="u-ic-keyboard_arrow_left"></span>
        </button>
    </div>

    <div class="o-overflow__nav o-overflow__nav--right">
        <button class="c-btn c-btn--icon c-btn--dark c-btn--small c-btn--left c-btn--wall jsNavRight">
            <span class="u-ic-keyboard_arrow_right"></span>
        </button>
    </div>

</div>
