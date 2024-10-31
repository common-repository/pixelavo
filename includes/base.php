<?php

namespace Pixelavo;

/**
 * Exit if accessed directly
 * */
if (!defined('ABSPATH')) {
    exit;
}

/**
* Base
*/
final class Base {

    private static $_instance = null;

    /**
     * Instance
     * 
     * Initializes a singleton instance
     * 
     * @return self class
     */
    static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Base class constructor
     * @return void
     */
    private function __construct() {
        if (!function_exists('is_plugin_active')) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
        /**
         * Register Plugin Active Hook
         */
        register_activation_hook( PIXELAVO_PL_ROOT, [ $this, 'plugin_activate_hook' ] );
    }

    /**
     * i18n
     * 
     * Load text domain
     * @return void
     */
    function i18n() {
        load_plugin_textdomain('pixelavo', false, dirname(plugin_basename(PIXELAVO_PL_ROOT)) . '/languages');
    }

    /**
     * init
     * 
     * Plugins loaded init hook
     * @return void
     */
    function init() {

        /**
         * Include required files.
         */
        $this->include_files();
        
        /**
         * Redirect to option page after plugin activate
         */
        $this->plugin_redirect_option_page();

        /**
         * Add plugin setting link to the plugin.
         */
        add_filter('plugin_action_links_' . PIXELAVO_PLUGIN_BASE, [$this, 'plugins_setting_links']);

    }

    /**
     * Include Require Files
     */
    function include_files() {
        require_once (PIXELAVO_PL_PATH . 'includes/helper-functions.php');
        require_once (PIXELAVO_PL_PATH . 'admin/admin-setting.php');
        require_once (PIXELAVO_PL_PATH . 'includes/add-pixel.php');
        if(pixelavo_is_woocommerce_active()) {
            require_once (PIXELAVO_PL_PATH . 'includes/pixel-events-data.php');
            if(pixelavo_get_option('product_feed', 'pixelavo_settings') == 'on') {
                require_once (PIXELAVO_PL_PATH . 'includes/pixel-feed.php');
            }
        }
        if(pixelavo_is_edd_active()) {
            require_once (PIXELAVO_PL_PATH . 'includes/pixel-edd-events-data.php');
            if(pixelavo_get_option('edd_product_feed', 'pixelavo_settings') == 'on') {
                require_once (PIXELAVO_PL_PATH . 'includes/pixel-edd-feed.php');
            }
        }
        if(!empty(pixelavo_get_custom_events())) {
            require_once (PIXELAVO_PL_PATH . 'includes/pixel-custom-events-data.php');
        }
        if(!empty(pixelavo_get_other_events())) {
            require_once (PIXELAVO_PL_PATH . 'includes/pixel-extra-events-data.php');
        }

        if( is_admin() ){
            require_once (PIXELAVO_PL_PATH . 'admin/class-diagnostic-data.php');
            require_once (PIXELAVO_PL_PATH . 'admin/class-trial.php');
            require_once (PIXELAVO_PL_PATH . 'admin/class-rating-notice.php');
        }
        add_action('wp_footer', [$this, 'localizedEventsData'], 11);
    }

    function localizedEventsData() {
        global $pixelavoEventsLocalizedData;
        if(count($pixelavoEventsLocalizedData) > 0) {
            wp_localize_script('pixelavo', 'pixelavo_event', $pixelavoEventsLocalizedData);
        }
    }

    /**
     * Scripts
     * 
     * Enqueue style and scripts for frontend part
     * @return void
     */
    function scripts() {
        if(pixelavo_pixel_status()) {
            wp_enqueue_script('pixelavo', PIXELAVO_DIR_URL . 'assets/public/js/main.js', ['jquery'], PIXELAVO_VERSION, true);
            wp_localize_script('pixelavo', 'pixelavo', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('pixelavo_ajax_event_nonce'),
                'other_events' => wp_json_encode(array_merge(pixelavo_get_other_events(), ['data' => getExtraEventData()])),
                'custom_events' => wp_json_encode(pixelavo_get_custom_events()),
                'additional_user_info' => apply_filters('pixelavo_additional_user_info', [], [] ),
            ]);
            wp_localize_script('pixelavo', 'pixelavo_event', []);
        }
    }

    /**
    * Plugins setting links

    * @param  array plugin default action links
    * @return array plugin action link
    */
    function plugins_setting_links($links) {
        $link = sprintf(
            '<a href="%1$s">%2$s</a>',
            admin_url('admin.php?page=pixelavo#/pixels'),
            __( 'Settings', 'pixelavo' )
        );
        array_unshift($links, $link);
        return $links; 
    }

    /**
     * Plugin Active Hook
     * Run when plugin is activated
     */
    public function plugin_activate_hook() {
        $events = get_option('pixelavo_events', [
            'search' => 'on',
            'view_content' => 'on',
            'view_category' => 'on',
        ]);
        $edd_events = get_option('pixelavo_edd_events', [
            'edd_search' => 'on',
            'edd_view_content' => 'on',
            'edd_view_category' => 'on',
        ]);
        update_option('pixelavo_events', $events);
        update_option('pixelavo_edd_events', $edd_events);
        add_option('pixelavo_do_activation_redirect', true);
        flush_rewrite_rules();

        if ( ! get_option( 'pixelavo_installed' ) ) {
            update_option( 'pixelavo_installed', time() );
        }
    }

    /**
     * After Active the plugin then redirect to option page
     * @return void
     */
    public function plugin_redirect_option_page() {
        if ( get_option( 'pixelavo_do_activation_redirect', false ) ) {
            delete_option('pixelavo_do_activation_redirect');
            if( !isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url("admin.php?page=pixelavo#/pixels") );
            }
        }
    }

}

/**
 * Initialize Base Class
 */
Base::instance();
