<?php

namespace Pixelavo;

/**
 * Exit if accessed directly
 * */
if (!defined('ABSPATH')) {
    exit;
}

class AddPixel{
    
    private static $_instance = null;

    /**
     * Instance
     * 
     * Initializes a singleton instance
     * 
     * @return self self class
     */
    static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('wp_head', [ $this, 'add_pixels']);
    }
    
    /**
     * Add pixel to the site.
     *
     * @return void
     */
    function add_pixels() {
        $pixels = pixelavo_get_pixel_list(apply_filters('pixelavo_pixels_limit', 1));

        $pixelInitCode = '';
        $pixelNoScriptCode = '';
        $advanced_matching = apply_filters('pixelavo_advanced_matching', []);
        $advanced_matching_hashed = apply_filters('pixelavo_advanced_matching_hashed', []);

        if(!empty($pixels)) {
            
            /** 
             * Print facebook pixel code to the browser.
             */
            if(pixelavo_pixel_status() && !pixelavo_check_exclude_roles()) { ?>
                <script>
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                    <?php
                        foreach ($pixels as $pixel) {
                            if($pixel['active'] == 'on' && pixelavo_check_pixel_page($pixel)) {
                                if(!empty($advanced_matching)) {
                                    echo "fbq('init', ".esc_attr($pixel['pixel_id']).", JSON.parse(".wp_json_encode($advanced_matching)."));" . "\n";
                                } else {
                                    echo "fbq('init', ".esc_attr($pixel['pixel_id']).");" . "\n";
                                }
                            }
                        }
                    ?>
                    fbq('track', 'PageView');
                </script>
                <noscript>
                    <?php
                        foreach ($pixels as $pixel) {
                            if($pixel['active'] == 'on' && pixelavo_check_pixel_page($pixel)) {
                                if(!empty($advanced_matching)) {
                                    echo "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=".esc_attr($pixel['pixel_id'])."&ev=PageView&noscript=1&ud[em]=".esc_attr($advanced_matching_hashed['em'])."&ud[fn]=".esc_attr($advanced_matching_hashed['fn'])."&ud[ln]=".esc_attr($advanced_matching_hashed['ln'])."&ud[ph]=".esc_attr($advanced_matching_hashed['ph'])."&ud[ct]=".esc_attr($advanced_matching_hashed['ct'])."&ud[st]=".esc_attr($advanced_matching_hashed['st'])."&ud[zp]=".esc_attr($advanced_matching_hashed['zp'])."&ud[country]=".esc_attr($advanced_matching_hashed['country'])."'/>" . "\n";
                                } else {
                                    echo "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=".esc_attr($pixel['pixel_id'])."&ev=PageView&noscript=1'/>" . "\n";
                                }
                            }
                        }
                    ?>
                </noscript>
                <?php
            }

        }
    }
}

/**
 * Initialize AddPixel Class
 */
AddPixel::instance();
