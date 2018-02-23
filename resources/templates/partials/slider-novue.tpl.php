<?php
use function AppTheme\theme;
use function AppTheme\config;

if( !$slides = theme('slides') ) return;
$has_teaser = false;
?>


<?php //var_dump($slides); var_dump($slides[0]->media_content[0]['image']); ?>

<div id="<?= $id ?>" class="c-slider <?= $style_modifier ?> is-automatic">

    <div class="c-slider__control c-slider__control--left">
        <button class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--right c-slider__btn jsSliderBtn" data="previous">
            <span class="u-ic-keyboard_arrow_left"></span>
        </button>
    </div>

    <div class="c-slider__control c-slider__control--right">
        <button class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--left c-slider__btn jsSliderBtn" data="next">
            <span class="u-ic-keyboard_arrow_right"></span>
        </button>
    </div>

    <ul class="c-slider__nav jsSliderNav">

        <?php foreach ($slides as $i => $slide): ?>

            <li id="<?= $id . '-nav-' . $i ?>" data="<?= $i ?>">
                <a class="c-btn c-btn--dark c-slider__btn" href="<?= '#slide' . $i ?>">
                    <div class="c-slider__btn__fill"></div>
                </a>
            </li>

        <?php endforeach ?>

    </ul>

    <ul class="c-slider__list jsSliderList">

        <?php foreach ($slides as $i => $slide): ?>

            <?php
                $has_teaser = $slide->slide_type === 'teaser' ? true : $has_teaser;
                $background = $slide->background === 'image'
                    ? wp_get_attachment_image_src($slide->background_image, 'bg3x1')[0]
                    : $slide->background
            ?>

            <li id="<?= $id . '-slide-' . $i ?>" class="c-slider__item">

                <div class="c-slide <?= 'c-slide--'.$slide->slide_type ?> <?= 'c-slide--'.$slide->color_scheme ?>"
                    style="background-image: url('<?= $background ?>')">

                    <a href="<?= $slide->url ?>" class="c-slide__link"></a>

                    <div class="o-wrapper c-slide__wrapper">

                        <div class="c-slide__body u-1/2@tablet u-1/1">

                            <?php if ($slide->show_title): ?>
                                <h1 class="u-mb-"><?= $slide->post_title ?></h1>
                            <?php endif ?>

                            <?php if ($slide->use_custom_html): ?>
                                <?= $slide->custom_html ?>
                            <?php else: ?>
                                <p class="u-lead u-muted"><?= $slide->lead_text ?></p>
                            <?php endif ?>

                            <?php if ($slide->button_text): ?>
                                <a class="c-btn c-btn--dark c-slide__btn" href="<?= $slide->url ?>">
                                    <?= $slide->button_text ?> <span class="u-ic-arrow_forward"></span>
                                </a>
                            <?php endif ?>

                        </div>

                        <?php if ( in_array($slide->slide_type, ['media-left', 'media-right'])): ?>
                            <div class="c-slide__media u-1/2@tablet u-hidden-until@tablet">
                                <div class="o-ratio o-ratio--16:9">
                                    <a class="o-ratio__content" href="<?= $slide->url ?>">
                                        <?= wp_get_attachment_image($slide->media_content[0]['image'], '360p') ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif ?>

                    </div>

                    <div class="o-wrapper c-slide--wrapper">
                        <div class="c-slide__body">
                            <!-- <h1 class="u-mb-">{{ heading }}</h1><p class="u-lead u-muted">{{{ text }}}</p> -->
                            <?= $slide->text ?>
                            <button
                                class="c-btn c-btn--dark c-slide__btn">
                                <?= $slide->button_text ?>
                                <span class="u-ic-arrow_forward"></span>
                            </button>
                        </div>
                    </div>

                </div>

            </li>

        <?php endforeach ?>

    </ul>

    <?php if ($has_teaser): ?>
        <div class="c-slider__teaser-container u-hidden-until@tablet">
            <div class="o-wrapper u-h-100">
                <div id="collapse-teaser" class="c-slider__teaser c-collapsible">
                    <div class="c-collapsible__header">
                        <div class="c-collapsible__title u-h5">
                            <?= __('New Recordings', config('textdomain')) ?>
                        </div>
                        <div class="c-collapsible__btn">
                            <button class="c-btn c-btn--blocky c-btn--square jsToggle"
                                data-target="#collapse-teaser"
                                data-class="is-collapsed">
                                <span class="u-ic-unfold_less"></span>
                                <span class="u-ic-unfold_more u-hidden"></span>
                            </button>
                        </div>
                    </div>
                    <div class="c-collapsible__body u-center">
                        organisms-medialist:u-1/1
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

