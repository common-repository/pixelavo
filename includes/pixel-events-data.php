<?php

namespace Pixelavo;

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    exit;
}

class PixelEventsData{
    
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
            /** Search */
            if(pixelavo_pixel_event_exists('search')) {
                add_action('wp_footer', [$this, 'search_data']);
            }
            /** View Category */
            if(pixelavo_pixel_event_exists('view_category')) {
                add_action('wp_footer', [$this, 'view_category_data']);
            }
            /** View Content */
            if(pixelavo_pixel_event_exists('view_content')) {
                add_action('wp_footer', [$this, 'view_content_data']);
            }
        }
    }
    
    /**
     * Search data.
     * Run when user search for any product and go to `search` page.
     * @return void
     */
    function search_data() {
        if(pixelavo_pixel_status() && function_exists('is_search') && is_search()) {
            global $pixelavoEventsLocalizedData;

            $search_query = get_search_query();
            $args = [
                's' => $search_query,
                'status' => 'publish',
                'visibility' => 'visible'
            ];
            $products = wc_get_products($args);
            
            $ids = [];
            $category = [];
            $values = 0;
            $event_id = 'Search.'.time();
            
            foreach ($products as $product) {
                $ids[] = $product->get_id();
                foreach (get_the_terms($product->get_id(), 'product_cat') as $cat) {
                    if(!in_array($cat->name, $category)) {
                        $category[] = $cat->name;
                    }
                }
                $values = $values + floatval($product->get_price());
            }

            $data = [
                'content_category' => implode(', ', $category),
                'content_ids' => $ids,
                'currency' => get_woocommerce_currency(),
                'search_string' => $search_query,
                'content_type' => 'product',
                'value' => number_format($values, 2)
            ];

            $data = array_merge($data, apply_filters('pixelavo_additional_user_info', [], $data['content_ids'] ));

            if(!empty($data['content_ids'])) {
                $pixelavoEventsLocalizedData[] = ['track' => 'track', 'event' => 'Search', 'data' => $data, 'eventID' => $event_id];
                pixelavo_run_conversions_api('Search', $data, $event_id);
            }
        }
    }
    
    /**
     * View Category data.
     * Run when user to go `product category` page.
     * @return void
     */
    function view_category_data() {
        if(pixelavo_pixel_status() && function_exists('is_product_category') && is_product_category()) {
            global $pixelavoEventsLocalizedData;

            $category_slug = get_queried_object()->slug;
            $category_name = get_queried_object()->name;
            $args = array(
                'status' => 'publish',
                'visibility' => 'visible',
                'category' => [$category_slug]
            );
            $products = wc_get_products($args);

            $ids = [];
            $titles = [];
            $contents = [];
            $values = 0;
            $event_id = 'ViewCategory.'.strtolower($category_name).'.'.time();

            foreach ($products as $product) {
                $ids[] = $product->get_id();
                $titles[] = $product->get_title();
                $contents[] = ['id' => $product->get_id(), 'quantity' => 1];
                $values = $values + floatval($product->get_price());
            }

            $data = [
                'content_ids' => $ids,
                'content_category' => $category_name,
                'content_name' => implode(', ', $titles),
                'content_type' => 'product',
                'contents' => $contents,
                'currency' => get_woocommerce_currency(),
                'value' => number_format($values, 2)
            ];

            $data = array_merge($data, apply_filters('pixelavo_additional_user_info', [], $data['content_ids'] ));
    
            if(!empty($data['content_ids'])) {
                $pixelavoEventsLocalizedData[] = ['track' => 'trackCustom', 'event' => 'ViewCategory', 'data' => $data, 'eventID' => $event_id];
                pixelavo_run_conversions_api('ViewCategory', $data, $event_id);
            }
        }
    }
    
    /**
     * View Content data.
     * Run when user to go `single product` page.
     * @return void
     */
    function view_content_data() {
        if(pixelavo_pixel_status() && function_exists('is_product') && is_product()) {
            global $pixelavoEventsLocalizedData;

            $product = wc_get_product(get_the_ID());
            $category = [];
            $event_id = 'ViewContent.'.get_the_ID().'.'.time();
            foreach (get_the_terms($product->get_id(), 'product_cat') as $cat) {
                if(!in_array($cat->name, $category)) {
                    $category[] = $cat->name;
                }
            }
            if ( $product ) {
                $content_type = $product->get_type() === 'variable' ? 'product_group' : 'product';
                $data = [
                    'content_ids' => [$product->get_id()],
                    'content_category' => implode(', ', $category),
                    'content_name' => $product->get_title(),
                    'content_type' => $content_type,
                    'currency' => get_woocommerce_currency(),
                    'value' => wc_get_price_to_display($product)
                ];

                $data = array_merge($data, apply_filters('pixelavo_additional_user_info', [], $data['content_ids'] ));

                
                if(!empty($data['content_ids'])) {
                    if(apply_filters('pixelavo_view_content_delay', false)) {
                        $pixelavoEventsLocalizedData[] = ['track' => 'track', 'event' => 'ViewContent', 'data' => $data, 'eventID' => $event_id, 'isEventDelay' => true, 'eventDelay' => pixelavo_get_option('view_content_delay', 'pixelavo_settings')];
                    } else {
                        $pixelavoEventsLocalizedData[] = ['track' => 'track', 'event' => 'ViewContent', 'data' => $data, 'eventID' => $event_id];
                        pixelavo_run_conversions_api('ViewContent', $data, $event_id);
                    }
                }
            }
        }
    }
}

/**
 * Initialize PixelEventsData Class
 */
PixelEventsData::instance();
