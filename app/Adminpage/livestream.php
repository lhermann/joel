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
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Joel Media Livestream</h3>
                                    <div class="inside activity-block embed-lifestream-wrap">
                                        <div class="embed-iframe embed-lifestream">
                                            <iframe src="<?= config('url-prefix')['embed'].'0livestream' ?>" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Monitor -->
                            <div id="side-sortables-3" class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;"><span>Livestream Monitor</span></h3>
                                    <div id="ajax-livestream-active">
                                        <div class="inside">
                                            <div class="c-spinner u-m"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="meta-box-sortables ui-sortable">
                                <div id="streamerStatistics" class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Streamer Stats</h3>
                                    <iframe src="https://live.stream.joelmediatv.de/stat" frameborder="0"
                                        style="height: 420px; width: 100%;"></iframe>
                                    <div id="video-status-timer">Zuletzt aktualisiert vor <span class="updateSeconds">0</span> Sekunden.</div>
                                </div>
                            </div> -->

                        </div><!-- post-body-content -->

                        <!-- sidebar -->
                        <div id="postbox-container-1" class="postbox-container">

                            <div id="side-sortables-2" class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;"><span>Livestream Chat</span></h3>
                                    <div id="tlkio" data-channel="jmm-live-chat" style="width:100%;height:400px;"></div>
                                    <script async src="//tlk.io/embed.js" type="text/javascript"></script>
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables -->

                            <!-- <div id="side-sortables-1" class="meta-box-sortables ui-sortable">
                                <div id="livestreamStatistics" class="postbox">
                                    <h3 class="hndle" style="cursor: default;"><span>Livestream Statistik</span></h3>
                                    <iframe src="<?= EMBED_URL ?>stats.php" frameborder="0"
                                        style="height: 230px; width: 100%;"></iframe>
                                    <div id="video-status-timer">Zuletzt aktualisiert vor <span class="updateSeconds">0</span> Sekunden.</div>
                                </div>
                            </div> -->

                        </div><!-- #postbox-container-1 .postbox-container -->

                    </div><!-- #post-body -->
                </div><!-- #poststuff -->
                <script>

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
