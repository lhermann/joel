<?php
namespace Tonik\Theme\App\Adminpage;

use function Tonik\Theme\App\config;

if( is_admin() ) new \Tonik\Theme\App\Adminpage\LivestramControllPage();

/**
 * Admin Page for livestream monitoring and controlls
 */
class LivestramControllPage {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_admin_page() {
        add_menu_page(
            'Livestream Kontrollzentrum',   //string $page_title,
            'Livestream',                   //string $menu_title,
            'edit_pages',                   //string $capability,
            'livestream',                   //string $menu_slug,
            [$this, 'create_admin_page'],   //callable $function = '',
            'dashicons-rss',                //string $icon_url = '',
            2                               //int $position = null
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {

        ?>
        <div class="wrap jm-tools">
            <h2>Livestream Kontrollzentrum</h2>
            <section>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">

                        <!-- main content -->
                        <div id="post-body-content">

<!-- Livestream -->
<div class="embed-iframe embed-lifestream">
    <iframe src="<?= config('url-prefix')['embed'].'0livestream' ?>" frameborder="0" allowfullscreen></iframe>
</div>

<!-- Monitor -->
<h3>Livestream Monitor</h3>
<div id="vue-checkstream">
    <div v-if="!streams.length" class="c-spinner u-m"></div>
    <div v-for="stream in streams"
        :key="stream.id"
        class="notice u-p-"
        :class="{'notice-success': stream.live, 'notice-error': !stream.live}"
    >
        <div>
            <strong>{{stream.id}}</strong>
            <small>
                <span v-if="stream.live" class="c-badge c-badge--green">on air</span>
                <span v-else class="c-badge">offline</span>
            </small>
        </div>
        <div v-for="(url, i) in stream.stream_url" :key="i" class="u-textmuted">
            <strong>{{stream.stream_key[i]}}:</strong>
            <em>{{url}}</em>
            <span class="c-dot c-dot--small"
                :class="{'c-dot--green': stream.stream_live[i]}"></span>
        </div>
        <div class="u-smaller u-yellow u-mt--">Last checked {{ distance(stream.updated) }} ago</div>
    </div>
</div>

                        </div><!-- post-body-content -->

                        <!-- sidebar -->
                        <div id="postbox-container-1" class="postbox-container">

<!-- Chat -->
<div id="side-sortables-2" class="meta-box-sortables ui-sortable">
    <div class="postbox">
        <h3 class="hndle" style="cursor: default;"><span>Livestream Chat</span></h3>
        <div id="tlkio" data-channel="jmm-live-chat" style="width:100%; height:600px;"></div>
        <script async src="//tlk.io/embed.js" type="text/javascript"></script>
    </div>
</div>

                        </div><!-- #postbox-container-1 .postbox-container -->

                    </div><!-- #post-body -->
                </div><!-- #poststuff -->
                <script>
                    var app = new Vue({
                        el: '#vue-checkstream',
                        name: "Streamcheck",
                        data() {
                            return {
                                streams: [],
                                interval: null
                            };
                        },
                        methods: {
                            request() {
                                jQuery.get( "//streamcheck.joelmedia.de/", response => {
                                    this.streams = response
                                });
                            },
                            distance(date) {
                                return dateFns.distanceInWordsToNow(date)
                            }
                        },
                        beforeMount() {
                            this.request();
                            this.interval = setInterval(() => {
                                this.request();
                            }, 30000);
                        },
                        destroyed() {
                            clearInterval(this.interval);
                        }
                    })
                </script>
            </section>
            <div class="clear"></div>
            <hr>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
    }
}
