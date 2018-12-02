<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Helper\formatbytes;
use function Tonik\Theme\App\Legacy\get_download_files;
use function Tonik\Theme\App\Legacy\trac_permalink;
?>

<div class="o-flex o-flex--middle o-flex--between">

    <!-- Date -->
    <div class="o-flex__item">
        <span class="u-hidden-until@desktop">Veröffentlicht am</span>
        <time class="u-bolder entry-date updated"
            datetime="<?= esc_attr( get_the_date( 'c' ) ); ?>">
            <?= esc_attr( get_the_date('j. F Y') ); ?>
        </time>
    </div>

    <!-- Klicks -->
    <?php if(function_exists('wpp_get_views')): ?>
        <div class="o-flex__item">
            <span class="u-ic-visibility"></span>
            <?= wpp_get_views( get_the_ID() ) ?>
            <span class="u-hidden-until@mobile">Klicks</span>
        </div>
    <?php endif ?>


    <!-- Download -->
    <div id="download"
        class="o-flex__item u-text-right"
        data-vue="dropdown"
    >
            <button id="download-btn"
                class="c-btn c-btn--small c-btn--secondary"
                v-on:click="toggleDropdown"
                ref="button"
                aria-haspopup="true"
                :aria-expanded="visible"
            >
                <span class="u-ic-download"></span>
                Download
                <span v-show="!visible"
                    class="u-ic-keyboard_arrow_down"></span>
                <span v-show="visible" v-cloak
                    class="u-ic-keyboard_arrow_up"></span>
            </button>

            <ul class="c-dropdown c-dropdown--round"
                v-show="visible" v-cloak
                aria-labelledby="download-btn"
                data-placement="bottom-end"
                ref="dropdown"
            >

                <?php foreach (get_download_files(get_the_ID()) as $file):
                    $permalink = trac_permalink(
                        $file->post_id,
                        "videodl",
                        config('url-prefix')['download'].$file->relative_url
                    ); ?>

                        <li class="c-dropdown__item ">
                            <a class="c-link c-link--block c-link--secondary"
                                href="<?= $permalink ?>">
                                <?php if ($file->type === 'video'): ?>
                                    <span class="u-ic-videocam"></span>
                                <?php else: ?>
                                    <span class="u-ic-headset"></span>
                                <?php endif ?>
                                <strong><?php
                                    if ($file->type === 'video') {
                                        echo $file->resolution === '720p' ? 'HD' : 'SD';
                                    } else {
                                        echo $file->bitrate >= 96 ? 'HQ' : 'LQ';
                                    }
                                ?></strong>
                                <?= formatbytes($file->size) ?>
                                <small class="u-muted">
                                    <?php if ($file->type === 'video'): ?>
                                        – MP4 <?= $file->resolution ?>
                                    <?php else: ?>
                                        - MP3 <?= $file->bitrate ?>kb/s
                                    <?php endif ?>
                                </small>
                            </span></span></a>
                        </li>

                <?php endforeach; ?>

            </ul>
    </div>

    <!-- Share -->
    <div id="share"
        class="o-flex__item u-text-right u-hidden-until@tablet"
        data-vue="dropdown"
    >
            <button id="share-btn"
                class="c-btn c-btn--small c-btn--secondary"
                v-on:click="toggleDropdown"
                ref="button"
                aria-haspopup="true"
                :aria-expanded="visible"
            >
                <span class="u-ic-share"></span>
                Teilen
                <span v-show="!visible"
                    class="u-ic-keyboard_arrow_down"></span>
                <span v-show="visible" v-cloak
                    class="u-ic-keyboard_arrow_up"></span>
            </button>

            <ul class="c-dropdown c-dropdown--round u-text-left"
                v-show="visible" v-cloak
                aria-labelledby="share-btn"
                data-placement="bottom-end"
                ref="dropdown">

                <li class="c-dropdown__item ">
                    <a class="c-link c-link--block c-link--secondary"
                        href="http://www.facebook.com/sharer/sharer.php?u=<?= get_permalink() ?>"
                        target="_blank">
                        <span class="u-ic-facebook u-mr--"></span>
                        Auf Facebook teilen
                    </a>
                </li>
                <li class="c-dropdown__item u-ph u-pv-">
                    <p class="u-mb--">Video-Player in die eigene Website einbetten:</p>
                    <input class="u-1/1"
                        type="text"
                        onclick="this.select()"
                        readonly="readonly"
                        value="<?=
                            htmlentities(sprintf(
                                '<iframe width="560" height="315" src="%s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
                                config('url-prefix')['embed'].'1'.get_the_ID()
                            ))
                        ?>">
                </li>

            </ul>
    </div>
</div>
