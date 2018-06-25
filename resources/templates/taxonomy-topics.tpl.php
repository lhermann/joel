<?php
use function AppTheme\template;
use function AppTheme\config;
use function AppTheme\asset_path;
$term = get_queried_object();
$subtopics = get_terms([ 'taxonomy' => 'topics', 'parent' => $term->term_id ]);
?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="u-mb u-mb++@tablet">

    <div id="background-box"
        class="c-header-bg u-white u-pt+ u-pb++"
        style="background-image: url('<?= asset_path('/images/header-blue.svg') ?>');"
    >

        <div class="o-wrapper">
            <div class="u-box-center u-3/4@tablet u-2/3@desktop">

                <a class="c-link c-link--dotted c-link--white" href="/<?= $term->taxonomy ?>/">
                    <span class="u-ic-arrow_back"></span> <?= __('Show all topics', config('textdomain')) ?>
                </a>

                <div class="u-mt">

                    <div class="o-media">
                        <div class="o-media__img u-hidden-until@tablet">
                            <span class="u-ic-folder u-ic--huge"></span>
                        </div>
                        <div class="o-media__body u-pt-">
                            <h1 class="u-responsive u-mb0"><?= $term->name ?></h1>
                            <ul class="o-list-inline o-list-inline--large o-list-inline--nowrap">
                                <li class="u-bolder u-muted">
                                    <?= _x('Topic', 'taxonomy singular name', config('textdomain')) ?>
                                </li>
                                <li>
                                    <span class="u-ic-videocam"></span>
                                    <strong><?= $term->count ?></strong>
                                    <?= __('Videos', config('textdomain')) ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="u-mt+">
                        <h2 class="u-h5 u-mb-">
                            <a href="<?= $term->parent ? get_term_link($term->parent) : '/'.$term->taxonomy.'/' ?>"
                                class="c-btn c-btn--light c-btn--ghost c-btn--small c-btn--square u-mr-">
                                <span class="u-ic-arrow_back"></span>
                            </a>
                            <span>
                                <?= (count($subtopics) ?: 'No').' '.
                                _n( 'Subtopic', 'Subtopics', count($subtopics), config('textdomain') ) ?>
                            </span>
                        </h2>
                        <ul class="c-medialist c-medialist--white c-medialist--inline">
                            <?php foreach ($subtopics as $topic): ?>
    <li class="c-medialist__item">
        <div class="o-media c-mediaitem c-mediaitem--topics c-mediaitem--simple">
            <a class="c-mediaitem__link" href="<?= get_term_link($topic) ?>"></a>
            <div class="o-media__img c-mediaitem__img">
                <a class="c-mediaitem__imglink" href="<?= get_term_link($topic) ?>">
                    <span class="u-ic-folder"></span>
                </a>
            </div>
            <div class="o-media__body c-mediaitem__body">
                <a class="c-mediaitem__title u-truncate" href="<?= get_term_link($topic) ?>">
                    <?= $topic->name ?>
                </a>
                <ul class="c-mediaitem__meta u-truncate">
                    <?php if ($sub = get_terms( 'topics', [ 'parent' => $topic->term_id ])): ?>
                    <li>
                        <?= count($sub).' '._n( 'Subtopic', 'Subtopics', count($sub), config('textdomain') ) ?>
                    </li>
                    <?php endif ?>
                    <li><?= $topic->count.' '.__('Videos', config('textdomain')) ?></li>
                </ul>
            </div>
        </div>
    </li>
                            <?php endforeach ?>
                        </ul>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="o-wrapper u-mt">
        <div class="u-box-center u-3/4@tablet u-2/3@desktop">

            <?php template('vue-components/medialist-instantiator', [
                'id' => 'medialist',
                'style_modifier' => '',
                'options' => [
                    'route' => 'recordings',
                    'sorting' => true,
                    'pagination' => 'normal'
                ],
                'params' => [
                    'per_page' => 20,
                    'topics' => $term->term_id
                ]
            ]) ?>

        </div>
    </div>

</main>

<?php get_footer() ?>
