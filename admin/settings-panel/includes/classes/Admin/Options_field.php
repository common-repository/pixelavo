<?php
namespace PixelavoOpt\Admin;

class Options_Field {

    private $woocommerce_active = false;
    private $edd_active = false;
    private $event_list_desc_wc = '';
    private $event_list_desc_edd = '';

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    

	function __construct(){
        $this->event_list_desc_wc = __('Select pixel events from the list below.', 'pixelavo');
        $this->event_list_desc_edd = __('Select pixel events from the list below.', 'pixelavo');
		$this->woocommerce_active = pixelavo_is_woocommerce_active();
        if(!$this->woocommerce_active) {
            $this->event_list_desc_wc = __('The below events require the "WooCommerce" plugin to be active. Please Make sure that WooCommerce has been installed and Activated.', 'pixelavo');
        }
		$this->edd_active = pixelavo_is_edd_active();
        if(!$this->edd_active) {
            $this->event_list_desc_edd = __('The below events require the "Easy Digital Downloads" plugin to be active. Please Make sure that Easy Digital Downloads has been installed and Activated.', 'pixelavo');
        }
	}

    public function get_settings_tabs(){

        $list_svg = PIXELAVO_ASSETS . '/images/icons/list.svg';
        $woocommerce_svg = PIXELAVO_ASSETS . '/images/icons/woocommerce.svg';
        $edd_svg = PIXELAVO_ASSETS . '/images/icons/edd.svg';
        $puzzle_svg = PIXELAVO_ASSETS . '/images/icons/puzzle.svg';
        $slider_svg = PIXELAVO_ASSETS . '/images/icons/slider.svg';
        $other_svg = PIXELAVO_ASSETS . '/images/icons/other.svg';
        
        $list_icon_res = wp_remote_get($list_svg);
        if ( is_array( $list_icon_res ) && ! is_wp_error( $list_icon_res ) ) {
            $list_icon = $list_icon_res['body'];
        }
        
        $woocommerce_icon_res = wp_remote_get($woocommerce_svg);
        if ( is_array( $woocommerce_icon_res ) && ! is_wp_error( $woocommerce_icon_res ) ) {
            $woocommerce_icon = $woocommerce_icon_res['body'];
        }
            
        $edd_icon_res = wp_remote_get($edd_svg);
        if ( is_array( $edd_icon_res ) && ! is_wp_error( $edd_icon_res ) ) {
            $edd_icon = $edd_icon_res['body'];
        }
            
        $puzzle_icon_res = wp_remote_get($puzzle_svg);
        if ( is_array( $puzzle_icon_res ) && ! is_wp_error( $puzzle_icon_res ) ) {
            $puzzle_icon = $puzzle_icon_res['body'];
        }
            
        $slider_icon_res = wp_remote_get($slider_svg);
        if ( is_array( $slider_icon_res ) && ! is_wp_error( $slider_icon_res ) ) {
            $slider_icon = $slider_icon_res['body'];
        }
        $other_icon_res = wp_remote_get($other_svg);
        if ( is_array( $other_icon_res ) && ! is_wp_error( $other_icon_res ) ) {
            $other_icon = $other_icon_res['body'];
        }
            
        $tabs = array(
            'pixels' => [
                'id'    => 'pixelavo_pixels_list',
                'title' =>  esc_html__( 'Pixel List', 'pixelavo' ),
                'icon'  => wp_json_encode($list_icon),
                'showInNav' => true,
                'content' => [
                    'extraClass' => 'pixels',
                    'savebtn' => false,
                    'enableall' => false,
                    'title' => __( 'Pixels', 'pixelavo' ),
                    'desc'  => __( 'Add pixels to your site', 'pixelavo' ),
                    'desc_class'  => '',
                    'footer' => false,
                ],
            ],
            'events' => [
                'id'    => 'pixelavo_events',
                'title' =>  esc_html__( 'WooCommerce Events', 'pixelavo' ),
                'icon'  => wp_json_encode($woocommerce_icon),
                'showInNav' => true,
                'content' => [
                    'extraClass' => 'events',
                    'title' => __( 'WooCommerce Events', 'pixelavo' ),
                    'desc'  => $this->event_list_desc_wc,
                    'desc_class'  => !$this->woocommerce_active ? 'warning' : '',
                    'footer' => false,
                ],
            ],
            'edd_events' => [
                'id'    => 'pixelavo_edd_events',
                'title' =>  esc_html__( 'EDD Events', 'pixelavo' ),
                'icon'  => wp_json_encode($edd_icon),
                'showInNav' => $this->edd_active,
                'content' => [
                    'extraClass' => 'events',
                    'title' => __( 'Easy Digital Downloads Events', 'pixelavo' ),
                    'desc'  => $this->event_list_desc_edd,
                    'desc_class'  => !$this->edd_active ? 'warning' : '',
                    'footer' => false,
                ],
            ],
            'other_events' => [
                'id'    => 'pixelavo_other_events',
                'title' =>  esc_html__( 'Other Events', 'pixelavo' ),
                'icon'  => wp_json_encode($other_icon),
                'showInNav' => true,
                'content' => [
                    'extraClass' => 'events',
                    'title' => __( 'Other Events', 'pixelavo' ),
                    'desc'  => __( 'Enable events from the list below.', 'pixelavo' ),
                    'desc_class'  => '',
                    'footer' => false,
                ],
            ],
            'custom_events' => [
                'id'    => 'pixelavo_custom_events',
                'title' =>  esc_html__( 'Custom Events', 'pixelavo' ),
                'icon'  => wp_json_encode($puzzle_icon),
                'showInNav' => true,
                'content' => [
                    'savebtn' => false,
                    'enableall' => false,
                    'extraClass' => 'custom_events',
                    'title' => __( 'Custom Events', 'pixelavo' ),
                    'desc'  => __( 'Add custom events to your site', 'pixelavo' ),
                    'desc_class'  => '',
                    'footer' => false,
                ],
            ],
            'settings' => [
                'id'    => 'pixelavo_settings',
                'title' =>  esc_html__( 'Settings', 'pixelavo' ),
                'icon'  => wp_json_encode($slider_icon),
                'showInNav' => true,
                'content' => [
                    'enableall' => false,
                    'extraClass' => 'settings',
                    'title' => __( 'Advance Settings', 'pixelavo' ),
                    'desc'  => __( 'Select pixel settings from the list below.', 'pixelavo' ),
                    'desc_class'  => '',
                    'footer' => false,
                ],
            ],
        );

        return apply_filters( 'pixelavo_admin_fields_sections', $tabs );
    }

    public function get_settings_subtabs(){

        $subtabs = array();

        return apply_filters( 'pixelavo_admin_fields_sub_sections', $subtabs );
    }

    public function get_registered_settings(){
        $settings = array(
            'pixelavo_pixels_list' => array(
                array(
                    'id' => "pixel_lists",
                    'type' => 'table',
                    'table_title' => esc_html__( 'Pixel List', 'pixelavo' ),
                    'modal_open_button_text' => esc_html__( 'Add New Pixel' , 'pixelavo' ),
                    'modal_fields' => array(
                        array(
                            'id'  => 'pixel_name',
                            'name' => __( 'Pixel Name', 'pixelavo' ),
                            'desc'  => __( 'This name will help you to recognize your pixel.', 'pixelavo' ),
                            'type' => 'text',
                            'required' => true
                        ),
                        array(
                            'id'  => 'pixel_id',
                            'name' => __( 'Pixel ID', 'pixelavo' ),
                            'desc'  => __( 'Enter your Facebook pixel ID here', 'pixelavo' ),
                            'type' => 'text',
                            'required' => true
                        ),
                        array(
                            'id'  => 'pixel_page',
                            'name' => __( 'Select Page', 'pixelavo' ),
                            'desc'  => __( 'Select the page type', 'pixelavo' ),
                            'type' => 'radio',
                            'required' => true,
                            'radio_inputs' => array(
                                array(
                                    'label' => esc_html__('All Pages'),
                                    'value' => "allpages",
                                    'default' => 'on',
                                ),
                                array(
                                    'label' => esc_html__('Specific Pages'),
                                    'value' => "specificpage",
                                )
                            )
                        ),
                        array(
                            'id'  => 'specificpage_list',
                            'name' => __( 'Page Lists', 'pixelavo' ),
                            'desc'  => __( 'Select specifi pages form the list', 'pixelavo' ),
                            'toggle' => array(
                                'key' => 'pixel_page',
                                'operator' => '==',
                                'value' => 'specificpage'
                            ),
                            'type' => 'multiselect',
                            'default' => '0',
                            'required' => true,
                            'options' => pixelavo_page_list()
                        ),
                        array(
                            'id'  => 'conversion_api_status',
                            'name'  => __( 'Conversion Api Status', 'pixelavo' ),
                            'type'  => 'element',
                            'default' => 'off',
                            'label_on' => __( 'On', 'pixelavo' ),
                            'label_off' => __( 'Off', 'pixelavo' ),
                        ),
                        array(
                            'id'  => 'access_token',
                            'name' => __( 'Access Token', 'pixelavo' ),
                            'desc'  => __( 'Enter access token here', 'pixelavo' ),
                            'toggle' => array(
                                'key' => 'conversion_api_status',
                                'operator' => '==',
                                'value' => 'on'
                            ),
                            'type' => 'textarea',
                            'required' => true
                        ),
                        array(
                            'id'  => 'event_code',
                            'name' => __( 'Test Event Code', 'pixelavo' ),
                            'desc'  => __( 'Enter test code here (Remove the test code after testing events)', 'pixelavo' ),
                            'toggle' => array(
                                'key' => 'conversion_api_status',
                                'operator' => '==',
                                'value' => 'on'
                            ),
                            'type' => 'text',
                        ),
                    ),
                    'table_column' => array(
                        'pixel_name' => __( 'Pixel Name', 'pixelavo' ),
                        'pixel_id'   => __( 'Pixel ID', 'pixelavo' ),
                        'status'     => array(
                            'title' => __( 'Status', 'pixelavo' ),
                            'type'  => 'switcher'
                        ),
                        'action'     => array(
                            'title'  =>  __( 'Action', 'pixelavo' ),
                            'button_edit' => __( 'Edit', 'pixelavo' ),
                            'button_delete' => __( 'Delete', 'pixelavo' )
                        )
                    ),
                    'pixel_limit' => apply_filters('pixelavo_pixels_limit', 1)
                )
            ),
            'pixelavo_events' => array(
                array(
                    'id'  => 'view_content',
                    'name'  => __( 'Track View Content', 'pixelavo' ),
                    'desc'  => __( 'When a person visit a product details page.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'view_category',
                    'name'  => __( 'Track View Category', 'pixelavo' ),
                    'desc'  => __( 'When a person visit a product category page.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'search',
                    'name'  => __( 'Track Search', 'pixelavo' ),
                    'desc'  => __( 'When a search is made using WordPress built-in search feature.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'customize_product',
                    'name'  => __( 'Track Customize Product', 'pixelavo' ),
                    'desc'  => __( 'When a person customizes a product.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'add_to_cart',
                    'name'  => __( 'Track Add To Cart', 'pixelavo' ),
                    'desc'  => __( 'When a product is added to the shopping cart.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'initiate_checkout',
                    'name'  => __( 'Track Initiate Checkout', 'pixelavo' ),
                    'desc'  => __( 'When a person enters the checkout flow prior to completing the checkout flow.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'purchase',
                    'name'  => __( 'Track Purchase', 'pixelavo' ),
                    'desc'  => __( 'When a purchase is made or checkout flow is completed.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->woocommerce_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active
                )
            ),
            'pixelavo_edd_events' => array(
                array(
                    'id'  => 'edd_view_content',
                    'name'  => __( 'Track View Content', 'pixelavo' ),
                    'desc'  => __( 'When a person visit a product details page.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_view_category',
                    'name'  => __( 'Track View Category', 'pixelavo' ),
                    'desc'  => __( 'When a person visit a product category page.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->edd_active
                ),
                
                array(
                    'id'  => 'edd_search',
                    'name'  => __( 'Track Search', 'pixelavo' ),
                    'desc'  => __( 'When a search is made using WordPress built-in search feature.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? 'on' : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => false,
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_add_to_cart',
                    'name'  => __( 'Track Add To Cart', 'pixelavo' ),
                    'desc'  => __( 'When a product is added to the shopping cart.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_initiate_checkout',
                    'name'  => __( 'Track Initiate Checkout', 'pixelavo' ),
                    'desc'  => __( 'When a person enters the checkout flow prior to completing the checkout flow.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_purchase',
                    'name'  => __( 'Track Purchase', 'pixelavo' ),
                    'desc'  => __( 'When a purchase is made or checkout flow is completed.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => $this->edd_active ? apply_filters('pixelavo_pro_event_default', 'off') : 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->edd_active
                )
            ),
            'pixelavo_other_events' => array(
                array(
                    'id'  => 'youtube_video',
                    'name'  => __( 'Track YouTube Video', 'pixelavo' ),
                    'desc'  => __( 'When a YouTube embed video is played, this event will be triggered.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                ),
                array(
                    'id'  => 'vimeo_video',
                    'name'  => __( 'Track Vimeo Video', 'pixelavo' ),
                    'desc'  => __( 'When a Vimeo embed video is played, this event will be triggered.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                ),
                array(
                    'id'  => 'self_hosted_video',
                    'name'  => __( 'Track Self Hosted Video', 'pixelavo' ),
                    'desc'  => __( 'When a Self Hosted video is played, this event will be triggered.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                ),
                array(
                    'id'  => 'page_scroll',
                    'name'  => __( 'Track Page Scroll', 'pixelavo' ),
                    'desc'  => __( 'Fires when the website visitor scrolls the page.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', false),
                    'child' => ['page_scroll_value']
                ),
                array(
                    'id'  => 'page_scroll_value',
                    'name'  => __( 'Trigger for scroll position.', 'pixelavo' ),
                    'desc'  => __( 'Fires the event when scroll position reaches the specified value in percentage. Default: 30%.', 'pixelavo' ),
                    'type'  => 'text',
                    'default' => '30',
                    'is_pro' => apply_filters('pixelavo_pro_feature', false),
                    'toggle' => array(
                        'key' => 'page_scroll',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'time_on_page',
                    'name'  => __( 'Track Time on Page', 'pixelavo' ),
                    'desc'  => __( 'Fires when a visitor stays on a page for a specified amount of time.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', false),
                    'child' => ['time_on_page_value']
                ),
                array(
                    'id'  => 'time_on_page_value',
                    'name'  => __( 'Trigger for time on page.', 'pixelavo' ),
                    'desc'  => __( 'Fires the event when time reaches the specified value in seconds. Default: 30 seconds.', 'pixelavo' ),
                    'type'  => 'text',
                    'default' => '30',
                    'is_pro' => apply_filters('pixelavo_pro_feature', false),
                    'toggle' => array(
                        'key' => 'time_on_page',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'internal_links',
                    'name'  => __( 'Track Internal Links', 'pixelavo' ),
                    'desc'  => __( 'When a link is clicked that leads to another page within the same domain.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                ),
            ),
            'pixelavo_custom_events' => [
                array(
                    'id' => "custom_events_lists",
                    'type' => 'event-table',
                    'table_title' => esc_html__( 'Custom Event List', 'pixelavo' ),
                    'modal_open_button_text' => esc_html__( 'Add New Event' , 'pixelavo' ),
                    'modal_fields' => [
                        [
                            'id'  => 'event_name',
                            'name' => __( 'Event Name', 'pixelavo' ),
                            'desc'  => __( 'This name will help you to recognize your event.', 'pixelavo' ),
                            'type' => 'text',
                            'required' => true
                        ],
                        [
                            'id'  => 'event_trigger',
                            'name' => __( 'Trigger', 'pixelavo' ),
                            'type' => 'select',
                            'required' => true,
                            'default' => 0,
                            'options' => [
                                [
                                    'id' => 'page_visit',
                                    'text' => 'Page Visit'
                                ],
                                [
                                    'id' => 'click_on_element',
                                    'text' => apply_filters('pixelavo_pro_feature', true) ? 'Click on Element (Pro)' : 'Click on Element',
                                    'disabled' => apply_filters('pixelavo_pro_feature', true)
                                ],
                                [
                                    'id' => 'hover_on_element',
                                    'text' => apply_filters('pixelavo_pro_feature', true) ? 'Hover on Element (Pro)' : 'Hover on Element',
                                    'disabled' => apply_filters('pixelavo_pro_feature', true)
                                ],
                                [
                                    'id' => 'url_click',
                                    'text' => apply_filters('pixelavo_pro_feature', true) ? 'URL Click (Pro)' : 'URL Click',
                                    'disabled' => apply_filters('pixelavo_pro_feature', true)
                                ],
                            ]
                        ],
                        [
                            'id'  => 'event_trigger_page_list',
                            'name' => __( 'Target Pages', 'pixelavo' ),
                            'desc'  => __( 'Choose where on your site to trigger this event.', 'pixelavo' ),
                            'toggle' => [
                                'key' => 'event_trigger',
                                'operator' => '==',
                                'value' => 'page_visit'
                            ],
                            'type' => 'multiselect',
                            'default' => '0',
                            'required' => true,
                            'options' => pixelavo_page_list()
                        ],
                        [
                            'id'  => 'event_trigger_element',
                            'name' => __( 'Target CSS Selector', 'pixelavo' ),
                            'desc'  => __( 'Enter the css class or id that will trigger the event on click.', 'pixelavo' ),
                            'toggle' => [
                                'key' => 'event_trigger',
                                'operator' => '==',
                                'value' => 'click_on_element'
                            ],
                            'type' => 'text',
                            'placeholder' => '#element-id',
                            'required' => true,
                        ],
                        [
                            'id'  => 'event_trigger_hover',
                            'name' => __( 'Target CSS Selector', 'pixelavo' ),
                            'desc'  => __( 'Enter the css class or id that will trigger the event on hover.', 'pixelavo' ),
                            'toggle' => [
                                'key' => 'event_trigger',
                                'operator' => '==',
                                'value' => 'hover_on_element'
                            ],
                            'type' => 'text',
                            'placeholder' => '#element-id',
                            'required' => true,
                        ],
                        [
                            'id'  => 'event_trigger_url',
                            'name' => __( 'Target URL', 'pixelavo' ),
                            'desc'  => __( 'Enter the url that will trigger the event on click.', 'pixelavo' ),
                            'toggle' => [
                                'key' => 'event_trigger',
                                'operator' => '==',
                                'value' => 'url_click'
                            ],
                            'type' => 'text',
                            'placeholder' => 'https://hasthemes.com',
                            'required' => true,
                        ],
                        [
                            'id'  => 'event_params',
                            'name' => __( 'Parameters', 'pixelavo' ),
                            'desc'  => __( 'Add custom parameters here.', 'pixelavo' ),
                            'type' => 'repeater',
                            'fields' => ['name', 'value']
                        ],
                    ],
                    'table_column' => [
                        'event_name' => __( 'Event', 'pixelavo' ),
                        'event_trigger'   => __( 'Trigger', 'pixelavo' ),
                        'action'     => __( 'Action', 'pixelavo' )
                    ],
                    'custom_event_limit' => apply_filters('pixelavo_custom_event_limit', 1)
                )
            ],
            'pixelavo_settings' => array(
                array(
                    'id'  => 'exclude_roles',
                    'name'  => __( 'Exclude Roles', 'pixelavo' ),
                    'desc'  => __( 'Users who are logged in and have roles selected here will not trigger your pixel events.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'options' => pixelavo_editable_roles(),
                ),
                array(
                    'id'  => 'product_feed',
                    'name'  => __( 'Product Feed (WooCommerce).', 'pixelavo' ),
                    'desc'  => __( 'A Product Feed is required to use Facebook Dynamic Product Ads.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    "active" => $this->woocommerce_active
                ),
                array(
                    'id'  => 'include_variations',
                    'name'  => __( 'Include Variations', 'pixelavo' ),
                    'desc'  => __( 'Having a lot of product variations can cause load issues with your feed, disable to exclude variations from the feed.', 'pixelavo' ),
                    'type' => 'switcher',
                    'default' => 'on',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'product_feed_url',
                    'name'  => __( 'Feed Url', 'pixelavo' ),
                    'desc'  => __( 'You\'ll need above URL when setting up your Facebook Product Catalog.', 'pixelavo' ),
                    'type' => 'text',
                    'default' => get_site_url() . '?feed=pixelavo',
                    'readonly' => true,
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'product_feed_brand',
                    'name'  => __( 'Default Product Brand', 'pixelavo' ),
                    'desc'  => __( 'If a product does not have a brand name, we will assign a placeholder brand using the name you provide.', 'pixelavo' ),
                    'type' => 'text',
                    'default' => 'pixelavo',
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'product_feed_gpc',
                    'name'  => __( 'Google Product Category', 'pixelavo' ),
                    'desc'  => __( 'Enter your numeric Google Product Category ID here. For instance, if your category is "Apparel & Accessories > Clothing > Dresses," enter 2271. You can find a current spreadsheet of all categories and IDs <a href="http://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.xls">here</a>.', 'pixelavo' ),
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => 'e.g. 2271',
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'exclude_categories',
                    'name'  => __( 'Exclude Categories', 'pixelavo' ),
                    'desc'  => __( 'Selected product categories will not be included in your feed.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'options' => get_product_categories(),
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'exclude_tags',
                    'name'  => __( 'Exclude Tags', 'pixelavo' ),
                    'desc'  => __( 'Selected product tags will not be included in your feed.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'options' => get_product_tags(),
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'product_identifier',
                    'name'  => __( 'Product Identifier', 'pixelavo' ),
                    'desc'  => __( 'Set how to identify your product using the Facebook Pixel (content_id) and the feed (g:id). Please make sure to have SKUs set on your products if you choose WooCommerce SKU.', 'pixelavo' ),
                    'type' => 'select',
                    'options' => [
                        [
                            'id' => 'post_id',
                            'text' => 'WordPress Post ID (recommended)'
                        ],
                        [
                            'id' => 'sku',
                            'text' => 'WooCommerce SKU'
                        ]
                    ],
                    'default' => 'post_id',
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'description_field',
                    'name'  => __( 'Description Field', 'pixelavo' ),
                    'desc'  => __( 'Set the field to use as your product description for the Facebook product catalog.', 'pixelavo' ),
                    'type' => 'select',
                    'options' => [
                        [
                            'id' => 'description',
                            'text' => 'Product Content'
                        ],
                        [
                            'id' => 'short',
                            'text' => 'Product Short Description'
                        ]
                    ],
                    'default' => 'description',
                    'toggle' => array(
                        'key' => 'product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                ),
                array(
                    'id'  => 'edd_product_feed',
                    'name'  => __( 'Product Feed (Easy Digital Downloads).', 'pixelavo' ),
                    'desc'  => __( 'A Product Feed is required to use Facebook Dynamic Product Ads.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_product_feed_url',
                    'name'  => __( 'Feed Url', 'pixelavo' ),
                    'desc'  => __( 'You\'ll need above URL when setting up your Facebook Product Catalog.', 'pixelavo' ),
                    'type' => 'text',
                    'default' => get_site_url() . '?feed=pixelavo-edd',
                    'readonly' => true,
                    'toggle' => array(
                        'key' => 'edd_product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_product_feed_brand',
                    'name'  => __( 'Default Product Brand', 'pixelavo' ),
                    'desc'  => __( 'If a product does not have a brand name, we will assign a placeholder brand using the name you provide.', 'pixelavo' ),
                    'type' => 'text',
                    'default' => 'pixelavo',
                    'required' => true,
                    'toggle' => array(
                        [
                            'key' => 'edd_product_feed',
                            'operator' => '==',
                            'value' => 'on'
                        ]
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_exclude_categories',
                    'name'  => __( 'Exclude Categories', 'pixelavo' ),
                    'desc'  => __( 'Selected product categories will not be included in your feed.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'options' => get_download_categories(),
                    'toggle' => array(
                        'key' => 'edd_product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_exclude_tags',
                    'name'  => __( 'Exclude Tags', 'pixelavo' ),
                    'desc'  => __( 'Selected product tags will not be included in your feed.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'options' => get_download_tags(),
                    'toggle' => array(
                        'key' => 'edd_product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_product_identifier',
                    'name'  => __( 'Product Identifier', 'pixelavo' ),
                    'desc'  => __( 'Set how to identify your product using the Facebook Pixel (content_id) and the feed (g:id). Please make sure to have SKUs set on your products if you choose WooCommerce SKU.', 'pixelavo' ),
                    'type' => 'select',
                    'options' => [
                        [
                            'id' => 'post_id',
                            'text' => 'WordPress Post ID (recommended)'
                        ],
                        [
                            'id' => 'sku',
                            'text' => 'WooCommerce SKU'
                        ]
                    ],
                    'default' => 'post_id',
                    'toggle' => array(
                        'key' => 'edd_product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'edd_description_field',
                    'name'  => __( 'Description Field', 'pixelavo' ),
                    'desc'  => __( 'Set the field to use as your product description for the Facebook product catalog.', 'pixelavo' ),
                    'type' => 'select',
                    'options' => [
                        [
                            'id' => 'description',
                            'text' => 'Product Content'
                        ],
                        [
                            'id' => 'short',
                            'text' => 'Product Excerpt'
                        ]
                    ],
                    'default' => 'description',
                    'toggle' => array(
                        'key' => 'edd_product_feed',
                        'operator' => '==',
                        'value' => 'on'
                    ),
                    "active" => $this->edd_active
                ),
                array(
                    'id'  => 'view_content_delay',
                    'name'  => __( 'View Content Event Delay', 'pixelavo' ),
                    'desc'  => __( 'To exclude bouncing visitors from triggering the ViewContent event on product page, introduce a delay, which will be in second. Default is 0 second', 'pixelavo' ),
                    'type' => 'text',
                    'default' => 0,
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active || $this->edd_active
                ),
                array(
                    'id'  => 'purchase_event_trigger',
                    'name'  => __( 'WooCommerce Purchase Event Trigger.', 'pixelavo' ),
                    'desc'  => __( 'You can select the timing to initiate the <b>"Purchase"</b> event. By default, it occurs when the order status is <b>"On Hold"</b> or <b>"Processing"</b>.', 'pixelavo' ),
                    'type' => 'multiselect',
                    'required' => true,
                    'default' => ['on-hold', 'processing'],
                    'options' => array(
                        array(
                            'id' => "on-hold",
                            'text' => __('On Hold', 'pixelavo' ),
                        ),
                        array(
                            'id' => "processing",
                            'text' => __('Processing', 'pixelavo' ),
                        ),
                        array(
                            'id' => "completed",
                            'text' => __('Completed', 'pixelavo' ),
                        )
                    ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active || $this->edd_active
                ),
                array(
                    'id'  => 'purchase_additional_info',
                    'name'  => __( 'Include additional information with the Purchase event.', 'pixelavo' ),
                    'desc'  => __( 'The Purchase event includes coupon codes (if applied) and shipping/billing information as parameters, allowing you to create more precise and effective custom audiences.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true),
                    "active" => $this->woocommerce_active || $this->edd_active
                ),
                array(
                    'id'  => 'additional_user_info',
                    'name'  => __( 'Additional user information.', 'pixelavo' ),
                    'desc'  => __( 'Include HTTP referrer, user language, post categories, and tags as event parameters, allowing you to build more precise custom audiences.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true)
                ),
                array(
                    'id'  => 'advanced_matching',
                    'name'  => __( 'Advanced Matching.', 'pixelavo' ),
                    'desc'  => __( 'Enable Advanced Matching for all events. According to Facebook, advanced matching has assisted businesses in increasing attributed conversions by 10% and campaign reach by 20% through retargeting.', 'pixelavo' ),
                    'type'  => 'switcher',
                    'default' => 'off',
                    'label_on' => __( 'On', 'pixelavo' ),
                    'label_off' => __( 'Off', 'pixelavo' ),
                    'is_pro' => apply_filters('pixelavo_pro_feature', true)
                )
            )
        );

        return apply_filters( 'pixelavo_admin_fields', $settings );

    }

}