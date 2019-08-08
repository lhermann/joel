<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\Helper\idHash;
use function Tonik\Theme\App\config;
if(!isset($args)) $args = [];
?>

<section class="c-section u-pv">
  <div class="o-wrapper">

    <div class="o-layout o-layout--large">
        <div class="o-layout__item u-1/3@desktop u-1/1 u-mb">

            <h2 class="u-h3 u-mb-">
              <?php if ($args['col1']['url']): ?>
                <a class="c-link c-link--dotted"
                    href="<?= $args['col1']['url'] ?>">
                    <?= $args['col1']['title'] ?>
                </a>
              <?php else: ?>
                <?= $args['col1']['title'] ?>
              <?php endif; ?>
            </h2>


            <?php template(
                'vue-components/medialist',
                array_merge(
                    [ 'id' => idHash($args['col1']) ],
                    $args['col1']
                )
            ); ?>

        </div>
        <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-mb">

            <h2 class="u-h3 u-mb-">
              <?php if ($args['col2']['url']): ?>
                <a class="c-link c-link--dotted"
                    href="<?= $args['col2']['url'] ?>">
                    <?= $args['col2']['title'] ?>
                </a>
              <?php else: ?>
                <?= $args['col2']['title'] ?>
              <?php endif; ?>
            </h2>

            <?php template(
                'vue-components/medialist',
                array_merge(
                    [ 'id' => idHash($args['col2']) ],
                    $args['col2']
                )
            ); ?>

        </div>
        <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-1/1 u-mb">

            <h2 class="u-h3 u-mb-">
              <?php if ($args['col3']['url']): ?>
                <a class="c-link c-link--dotted"
                    href="<?= $args['col3']['url'] ?>">
                    <?= $args['col3']['title'] ?>
                </a>
              <?php else: ?>
                <?= $args['col3']['title'] ?>
              <?php endif; ?>
            </h2>

            <?php template(
                'vue-components/medialist',
                array_merge(
                    [ 'id' => idHash($args['col3']) ],
                    $args['col3']
                )
            ); ?>

        </div>
    </div>

  </div>
</section>
