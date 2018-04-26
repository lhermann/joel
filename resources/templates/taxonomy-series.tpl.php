<?php
use function AppTheme\template;
use function AppTheme\config;
use function AppTheme\asset_path;
use function AppTheme\Helper\get_terms_associated_with_term;
$term = get_queried_object();
$speakers = get_terms_associated_with_term( $term, 'speakers' );
?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="u-mb u-mb++@tablet">

    <?php //var_dump($term); ?>
    <?php //var_dump($speakers); ?>

    <div id="background-box"
        class="c-header-bg u-white u-pt+ u-pb++"
        style="background-image: url('<?= asset_path('/images/header-blue.svg') ?>');"
    >

        <div class="o-wrapper">
            <div class="u-box-center u-3/4@tablet u-2/3@desktop">

                <a class="c-link c-link--dotted c-link--white" href="/<?= $term->taxonomy ?>/">
                    <span class="u-ic-arrow_back"></span> <?= __('Show all series', config('textdomain')) ?>
                </a>

                <div class="u-mt">
                    <div class="o-flag o-flag--large">
                        <div class="o-flag__img u-hidden-until@tablet">
                            <img class="u-rounded u-box-shadow u-320"
                                src="<?= wp_get_attachment_image_src(get_field( 'image', $term ), '360p')[0] ?>"
                                alt="series">
                        </div>
                        <div class="o-flag__body">
                            <h1 class="u-responsive u-mb0"><?= $term->name ?></h1>
                            <p class="u-bolder u-muted"><?= _x('Series', 'taxonomy singular name', config('textdomain')) ?>
                        </p></div>
                    </div>

                    <div class="u-mt u-hidden-from@tablet">
                        <img class="u-rounded u-box-shadow u-1/1"
                            src="<?= wp_get_attachment_image_src(get_field( 'image', $term ), '360p')[0] ?>"
                            alt="Thumbnail for <?= $term->name ?>">
                    </div>

                    <p class="u-mt"><?= $term->description ?></p>

                    <p>
                        <span>
                            <span class="u-ic-videocam"></span>
                            <strong><?= $term->count ?></strong> <?= __('Videos', config('textdomain')) ?>
                        </span>
                        <span class="u-ml">
                            <?= _x('Speaker', 'taxonomy singular name', config('textdomain')) ?>:
                            <?php foreach ($speakers as $key => $speaker): ?>
                                <?= $key ? ', ' : '' ?>
                                <a class="c-link c-link--dotted c-link--white"
                                    href="<?= get_term_link( $speaker ) ?>"
                                >
                                    <?= $speaker->name ?>
                                </a>
                            <?php endforeach ?>
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="o-wrapper u-mt">
        <div class="u-box-center u-3/4@tablet u-2/3@desktop">

            <?php template('partials/medialist', [
                'id' => 'medialist',
                'style_modifier' => '',
                'options' => [
                    'route' => 'recordings',
                    'header' => __('Videos of this series', config('textdomain')),
                    'pagination' => 'normal'
                ],
                'params' => [
                    'per_page' => 20,
                    'series' => $term->term_id
                ]
            ]) ?>

        </div>
    </div>

</main>

<?php get_footer() ?>
