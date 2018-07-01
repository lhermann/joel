<?php
use function AppTheme\template;
use function AppTheme\config;
use function AppTheme\asset_path;
use function AppTheme\Helper\count_terms_associated_with_term;
$term = get_queried_object();
$series_count = count_terms_associated_with_term( $term, 'series' );
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
                    <span class="u-ic-arrow_back"></span>
                    <?= __('Show all speakers', config('textdomain')) ?>
                </a>

                <div class="o-media o-media--stacked@mobile u-mt">
                    <div class="o-media__img u-160">
                        <img class="u-rounded u-box-shadow u-1/1"
                            src="<?= wp_get_attachment_image_src(get_field( 'image', $term ), 'square320')[0] ?>"
                            alt="Image of <?= htmlentities($term->name) ?>"
                        >
                    </div>
                    <div class="o-media__body">
                        <h1 class="u-responsive u-mb0"><?= $term->name ?></h1>
                        <p class="u-bolder u-muted">
                            <?= _x('Speaker', 'taxonomy singular name', config('textdomain')) ?>
                        </p>
                        <p><?= $term->description ?></p>
                        <ul class="o-list-inline o-list-inline--large o-list-inline--nowrap">
                            <li>
                                <span class="u-ic-videocam"></span>
                                <strong><?= $term->count ?></strong>
                                <?= __('Videos', config('textdomain')) ?>
                            </li>
                            <li>
                                <span class="u-ic-subscriptions"></span>
                                <strong><?= $series_count ?></strong>
                                <?= _x('Series', 'taxonomy singular name', config('textdomain')) ?>
                            </li>
                            <?php if ($website = get_field( 'website', $term )): ?>
                                <li>
                                    <?= __('Website', config('textdomain')) ?>:
                                    <a class="c-link c-link--dotted c-link--white u-bolder"
                                        href="http://<?= $website ?>"
                                    >
                                        <span class="u-ic-open_in_new"></span>
                                        <?= $website ?>
                                    </a>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- end #background-box -->

    <div class="o-wrapper u-mt">
        <div class="u-box-center u-3/4@tablet u-2/3@desktop">

            <?php template('vue-components/medialist-init', [
                'id' => 'medialist',
                'style_modifier' => '',
                'options' => [
                    'route' => 'recordings',
                    // 'tabs' => true,
                    'sorting' => true,
                    'pagination' => 'normal'
                ],
                'params' => [
                    'per_page' => 20,
                    'speakers' => $term->term_id
                ]
            ]) ?>

        </div>
    </div>

</main>

<?php get_footer() ?>
