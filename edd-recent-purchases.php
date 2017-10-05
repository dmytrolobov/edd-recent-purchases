<?php
	/**
		* Plugin Name:       Easy Digital Downloads - Recent Purchases
		* Plugin URI:        https://wordpress.org/plugins/edd-recent-purchases
		* Description:       Create and show easy digital downloads recent purchases
		* Version:           1.0
		* Author:            Wow-Company
		* Author URI:        https://wow-estore.com/
		* License:           GPL-2.0+
		* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
		* Text Domain:       edd-recent-purchases
	*/
	if ( ! defined( 'WPINC' ) ) {die;}
	// Declaration Wow-Company class
	
	if( !class_exists( 'Wow_Company' )) {
		require_once plugin_dir_path( __FILE__ ) . 'asset/class-wow-company.php';				
	}	
		
	// Uninstall plugin
	register_uninstall_hook( __FILE__, array( 'EDD_RECENT_PURCHASES', 'uninstall' ) );
	
	final class EDD_RECENT_PURCHASES {
		
		private static $instance;	
		
		const PREF = 'edd_recent_purchases';	
		
		
		public static function uninstall() {
			delete_option( self::PREF );			
		}
		
		/**
			* Main Instance.
			*
			* Insures that only one instance of EDD_RECENT_PURCHASES exists in memory at any one
			* time. Also prevents needing to define globals all over the place.
			*
			* @since 1.0
			* @static
			* @staticvar array $instance	 
			* @uses EDD_RECENT_PURCHASES::includes() Include the required files.		
			* @return object|EDD_RECENT_PURCHASES The one true EDD_RECENT_PURCHASES
		*/
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EDD_RECENT_PURCHASES ) ) {
				$arg = array(
				'plugin_name' => 'EDD Recent Purchases',
				'plugin_menu' => 'EDD Recent Purchases',
				'plugin_home_url'  => 'edd-recent-purchases',
				'version'     => '1.0',
				'base_file'   => basename(__FILE__),
				'slug'        => dirname(plugin_basename(__FILE__)),
				'plugin_dir'  => plugin_dir_path( __FILE__ ),
				'plugin_url'  => plugin_dir_url( __FILE__ ),
				'pref'        => self::PREF,
				'shortcode'   => 'EDD-Recent-Purchases',
				
				);
				self::$instance = new EDD_RECENT_PURCHASES;
				add_action( 'wp_enqueue_scripts', array(self::$instance, 'style' ) );
				
				
				self::$instance->includes();
				self::$instance->adminlinks = new WOW_EDD_RECENT_PURCHASES_ADMIN_LINKS($arg);				
				self::$instance->admin      = new WOW_EDD_RECENT_PURCHASES_ADMIN($arg);								
				self::$instance->shortcode  = new WOW_EDD_RECENT_PURCHASES_SHORTCODE($arg);
				self::$instance->widget     = new WOW_EDD_RECENT_PURCHASES_WIDGET();				
			}
			return self::$instance;
		}		
		
		function style(){
			wp_enqueue_style( 'wow-edd-style', plugins_url('public/css/style.css', __FILE__) );			
		}
		
		/**
			* Throw error on object clone.
			*
			* The whole idea of the singleton design pattern is that there is a single
			* object therefore, we don't want the object to be cloned.
			*
			* @since 1.0
			* @access protected
			* @return void
		*/
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'edd-recent-purchases' ), '1.0' );
		}
		
		/**
			* Disable unserializing of the class.
			*
			* @since 1.0
			* @access protected
			* @return void
		*/
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'edd-recent-purchases' ), '1.0' );
		}
		
		/**
			* Include required files.
			*
			* @access private
			* @since 1.0
			* @return void
		*/
		
		private function includes() {						
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-links.php';			
			require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin.php';
			require_once plugin_dir_path( __FILE__ ) . 'public/class-shortcode.php';
			require_once plugin_dir_path( __FILE__ ) . 'widget/widget.php';			
		}		
	}
	
	function edd_recent_purchases() {
		return EDD_RECENT_PURCHASES::instance();
	}
	
	edd_recent_purchases();
