<?php

namespace Pixelavo;

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    exit;
}

class PixelExtraEventsData{
    
    private static $_instance = null;

    /**
     * Instance.
     * Initializes a singleton instance.
     * @return self class
     */
    static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {

        if(!pixelavo_check_exclude_roles()) {
            /** Extra/Other Event */
            add_action('wp_ajax_pixelavo_fire_other_events', [$this, 'pixelavo_fire_other_events']);
            add_action('wp_ajax_nopriv_pixelavo_fire_other_events', [$this, 'pixelavo_fire_other_events']);
        }
        
    }
    
    /**
     * Add To Cart ajax data.
     * Ajax call for shop/archive page `add to cart` action.
     * @return void
     */
    function pixelavo_fire_other_events() {
        check_ajax_referer('pixelavo_ajax_event_nonce', 'ajax_nonce');
        $data = pixelavo_data_clean($_POST['data']);
        $event_id = pixelavo_data_clean($_POST['event_id']);
        $event_name = pixelavo_data_clean($_POST['event_name']);
        pixelavo_run_conversions_api($event_name, $data, $event_id);
        /*
        * translators: %s: Event Name
        */
        wp_send_json_success( ["message" => sprintf( esc_html__( '%s server event run successfully', 'pixelavo' ), $event_name) ] );
        wp_die();
    }
}

/**
 * Initialize PixelExtraEventsData Class
 */
PixelExtraEventsData::instance();
