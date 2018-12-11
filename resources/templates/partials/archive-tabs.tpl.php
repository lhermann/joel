<?php
use function Tonik\Theme\App\config;
$request_uri = $_SERVER['REQUEST_URI'];
$tabs = [
    [
        'url' => '/'._x('recordings', 'http route', config('textdomain')).'/',
        'label' => _x('Recordings', 'post type general name', config('textdomain'))
    ],
    [
        'url' => '/'._x('series', 'http route', config('textdomain')).'/',
        'label' => _x('Series', 'taxonomy general name', config('textdomain'))
    ],
    [
        'url' => '/'._x('speakers', 'http route', config('textdomain')).'/',
        'label' => _x('Speakers', 'taxonomy general name', config('textdomain'))
    ],
    [
        'url' => '/'._x('topics', 'http route', config('textdomain')).'/',
        'label' => _x('Topics', 'taxonomy general name', config('textdomain'))
    ]
]
?>

<nav class="c-tabs <?= $style_modifier ?>">

    <ul class="o-list-bare o-layout o-layout--flush">

        <?php foreach ($tabs as $tab): ?>
            <li class="o-layout__item u-1/4 c-tabs__item
                <?= $request_uri === $tab['url'] ? 'is-active' : '' ?>"
            >
                <a class="c-tabs__link u-text-center" href="<?= $tab['url'] ?>">
                    <?= $tab['label'] ?>
                </a>
            </li>
        <?php endforeach ?>

    </ul><!--end c-primary-nav__list-->

</nav>
