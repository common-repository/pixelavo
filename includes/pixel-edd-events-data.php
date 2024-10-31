<?php

namespace Pixelavo;

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    exit;
}

class PixelEddEventsData{
    
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
            if(pixelavo_edd_event_exists('edd_search')) {
                add_action('wp_footer', [$this, 'search_data']);
            }

            /** View Category */
            if(pixelavo_edd_event_exists('edd_view_category')) {
                add_action('wp_footer', [$this, 'view_category_data']);
            }

            /** View Content */
            if(pixelavo_edd_event_exists('edd_view_content')) {
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
                'visibility' => 'visible',
                'fields'    => 'ids',
                'post_type' => 'download',
                'posts_per_page'   => -1,
            ];
            $download_ids = get_posts($args);
            
            $ids = [];
            $category = [];
            $values = 0;
            $event_id = 'Search.'.time();
            
            foreach ($download_ids as $id) {
                $ids[] = $id;
                foreach (get_the_terms($id, 'download_category') as $cat) {
                    if(!in_array($cat->name, $category)) {
                        $category[] = $cat->name;
                    }
                }
                $values = $values + floatval(edd_get_download($id)->get_price());
            }

            $data = [
                'content_category' => implode(', ', $category),
                'content_ids' => $ids,
                'currency' => edd_get_option('currency', 'USD'),
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
        if(pixelavo_pixel_status() && function_exists('is_tax') && is_tax('download_category')) {
            global $pixelavoEventsLocalizedData;

            $id = get_queried_object_id();
            if(term_exists($id)) {
                $term = get_term($id);
                $args = [
                    'fields'    => 'ids',
                    'post_type' => 'download',
                    'posts_per_page'   => -1,
                    'tax_query' => [
                        [
                            'taxonomy' => $term->taxonomy,
                            'terms' => $id
                        ],
                    ],
                ];
                $download_ids = get_posts($args);

                $download = edd_get_download($id);

                $ids = [];
                $titles = [];
                $contents = [];
                $values = 0;
                $event_id = 'ViewCategory.'.trim(strtolower(str_replace(' ', '', $term->name))).'.'.time();

                foreach ($download_ids as $download_id) {
                    $download = edd_get_download($download_id);
                    $ids[] = $download_id;
                    $titles[] = $download->post_title;
                    $contents[] = ['id' => $download_id, 'quantity' => 1];
                    $values = $values + floatval($download->get_price());
                }

                $data = [
                    'content_ids' => $ids,
                    'content_category' => $term->name,
                    'content_name' => implode(', ', $titles),
                    'content_type' => 'product',
                    'contents' => $contents,
                    'currency' => edd_get_option('currency', 'USD'),
                    'value' => number_format($values, 2)
                ];

                $data = array_merge($data, apply_filters('pixelavo_additional_user_info', [], $data['content_ids'] ));

                if(!empty($data['content_ids'])) {
                    $pixelavoEventsLocalizedData[] = ['track' => 'trackCustom', 'event' => 'ViewCategory', 'data' => $data, 'eventID' => $event_id];
                    pixelavo_run_conversions_api('ViewCategory', $data, $event_id);
                }
            }
        }
    }
    
    /**
     * View Content data.
     * Run when user to go `single product` page.
     * @return void
     */
    function view_content_data() {
        if(pixelavo_pixel_status() && function_exists('is_singular') && is_singular('download')) {
            global $pixelavoEventsLocalizedData;

            $id = get_queried_object_id();
            $download = edd_get_download($id);
            
            $category = [];
            $event_id = 'ViewContent.'.$id.'.'.time();
            if(has_term('', 'download_category', $id)) {
                foreach (get_the_terms($id, 'download_category') as $cat) {
                    if(!in_array($cat->name, $category)) {
                        $category[] = $cat->name;
                    }
                }
            }
            if ( $download ) {
                $content_type = $download->has_variable_prices() ? 'product_group' : 'product';
                $data = [
                    'content_ids' => [$id],
                    'content_category' => implode(', ', $category),
                    'content_name' => $download->post_title,
                    'content_type' => $content_type,
                    'currency' => edd_get_option('currency', 'USD'),
                    'value' => number_format($download->get_price()),
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
 * Initialize PixelEddEventsData Class
 */
PixelEddEventsData::instance();
