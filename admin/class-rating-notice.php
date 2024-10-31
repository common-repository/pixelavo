<?php 

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pixelavo_Rating_Notice' ) ) {
    class Pixelavo_Rating_Notice {
        private $previous_date;
        private $plugin_slug = 'pixelavo';
        private $plugin_name = 'Pixelavo Facebook Pixel Conversion Tracking Plugin';
        private $logo_url = PIXELAVO_PL_URL . "/assets/images/logo.png";
        private $after_click_maybe_later_days = '-20 days';
        private $after_installed_days = '-14 days';
        private $installed_date_option_key = 'pixelavo_installed';

        /**
         * Instance.
         */
        public static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function __construct() {
            $this->previous_date = false == get_option('pixelavo_maybe_later_time') ? strtotime( $this->after_installed_days ) : strtotime( $this->after_click_maybe_later_days );
            if ( current_user_can('administrator') ) {
                if ( empty( get_option('pixelavo_rating_already_rated', false ) ) ) {
                    add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
                }
            }

            if ( is_admin() ) {
                add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
            }

            add_action( 'wp_ajax_pixelavo_rating_maybe_later', [ $this, 'pixelavo_rating_maybe_later' ] );
            add_action( 'wp_ajax_pixelavo_rating_already_rated', [ $this, 'pixelavo_rating_already_rated' ] );
        }

        public function check_plugin_install_time() {
            $installed_date = get_option( $this->installed_date_option_key );

            if ( false == get_option( 'pixelavo_maybe_later_time' ) && false !== $installed_date && $this->previous_date >= $installed_date ) {
                add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

            } else if ( false != get_option( 'pixelavo_maybe_later_time' ) && $this->previous_date >= get_option( 'pixelavo_maybe_later_time' ) ) {
                add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

            }
        }

        public function pixelavo_rating_maybe_later() {

            if ( ! wp_verify_nonce( $_POST['nonce'], 'pixelavo-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
            exit;
            }

            update_option( 'pixelavo_maybe_later_time', strtotime('now') );
        }

        function pixelavo_rating_already_rated() {

            if ( ! wp_verify_nonce( $_POST['nonce'], 'pixelavo-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
            exit; 
            }

            update_option( 'pixelavo_rating_already_rated' , true );
        }
        
        public function rating_notice_content() {
            if ( is_admin() ) {
                echo '<div class="notice pixelavo-rating-notice is-dismissible" style="border-left-color: #2271b1!important; display: flex; align-items: center;">
                            <div class="pixelavo-rating-notice-logo">
                                <img src="' . esc_url($this->logo_url) . '">
                            </div>
                            <div>
                                <h3>Thank you for choosing '. esc_html($this->plugin_name) .' to track your audience!</h3>
                                <p style="">Would you mind doing us a huge favor by providing your feedback on WordPress? Your support helps us spread the word and greatly boosts our motivation.</p>
                                <p>
                                    <a href="https://wordpress.org/support/plugin/'. esc_attr($this->plugin_slug) .'/reviews/?filter=5#new-post" target="_blank" class="pixelavo-you-deserve-it button button-primary">OK, you deserve it!</a>
                                    <a class="pixelavo-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                    <a class="pixelavo-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                                </p>
                            </div>
                    </div>';
            }
        }

        public static function enqueue_scripts() {
            echo "<style>
                .pixelavo-rating-notice {
                padding: 10px 20px;
                border-top: 0;
                border-bottom: 0;
                }
                .pixelavo-rating-notice-logo {
                    margin-right: 20px;
                    width: 100px;
                    height: 100px;
                }
                .pixelavo-rating-notice-logo img {
                    max-width: 100%;
                }
                .pixelavo-rating-notice h3 {
                margin-bottom: 0;
                }
                .pixelavo-rating-notice p {
                margin-top: 3px;
                margin-bottom: 15px;
                display:flex;
                }
                .pixelavo-maybe-later,
                .pixelavo-already-rated {
                    text-decoration: none;
                    margin-left: 12px;
                    font-size: 14px;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }
                .pixelavo-already-rated .dashicons,
                .pixelavo-maybe-later .dashicons {
                vertical-align: middle;
                }
                .pixelavo-rating-notice .notice-dismiss {
                    display: none;
                }
            </style>";
            $ajax_url = admin_url('admin-ajax.php');
            $notice_admin_nonce = wp_create_nonce('pixelavo-plugin-notice-nonce');
            ?>

            <script type="text/javascript">
                (function ($) {
                    $(document).on( 'click', '.pixelavo-maybe-later', function() {
                        $('.pixelavo-rating-notice').slideUp();
                        jQuery.post({
                            url: <?php echo wp_json_encode( $ajax_url ); ?>,
                            data: {
                                nonce: <?php echo wp_json_encode( $notice_admin_nonce ); ?>,
                                action: 'pixelavo_rating_maybe_later'
                            }
                        });
                    });

                    $(document).on( 'click', '.pixelavo-already-rated', function() {
                        $('.pixelavo-rating-notice').slideUp();
                        jQuery.post({
                            url: <?php echo wp_json_encode( $ajax_url ); ?>,
                            data: {
                                nonce: <?php echo wp_json_encode( $notice_admin_nonce ); ?>,
                                action: 'pixelavo_rating_already_rated'
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
        }

    }

    Pixelavo_Rating_Notice::get_instance();
}