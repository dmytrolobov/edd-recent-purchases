<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* Public Class
		*
		* @package     WOW_EDD_RECENT_PURCHASES_SHORTCODE
		* @subpackage  
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	class WOW_EDD_RECENT_PURCHASES_SHORTCODE {
		
		private $arg;
		
		public function __construct( $arg ) {
			$this->plugin_name      = $arg['plugin_name'];
			$this->plugin_menu      = $arg['plugin_menu'];
			$this->version          = $arg['version'];
			$this->pref             = $arg['pref'];			
			$this->slug             = $arg['slug'];
			$this->plugin_dir       = $arg['plugin_dir'];
			$this->plugin_url       = $arg['plugin_url'];
			$this->plugin_home_url  = $arg['plugin_home_url'];
			$this->shortcode        = $arg['shortcode'];			
			// admin pages
			add_shortcode($this->shortcode, array($this, 'shortcode') );			
		}
		
		// Show on Front end
		function shortcode($atts) {	
			$param = get_option($this->pref);			
			$quantity = isset( $param['quntity'] ) ? $param['quntity'] : '5';
			$notification = isset( $param['content'] ) ? $param['content'] : " {fname} {lname} ". __( "purchased", "edd-recent-purchases" ) . " {download} " . __( "for ", "edd-recent-purchases" ) . " {price}$ {time} ". __( "ago ", "edd-recent-purchases" );
			
			$img = isset( $param['shortcode_img'] ) ? $param['shortcode_img'] : 'none'; 
			$img_size = isset( $param['shortcode_img_size'] ) ? $param['shortcode_img_size'] : '32';
			
			$args = array(
			'numberposts'      => $quantity,
			'post_status'      => 'publish',			
			'post_type'        => 'edd_payment',
			'suppress_filters' => true, 
			);						
			$payments = get_posts( $args );			
			$out = null;
			if ( $payments ) { 
				$out .= '<ul class="wow_edd_recent_purchases">';			
				foreach ( $payments as $payment ) { 
					setup_postdata($payment);
					$meta = get_post_meta($payment->ID, '_edd_payment_meta' );					
					$fname = $meta[0]['user_info']['first_name'];
					$lname = $meta[0]['user_info']['last_name'];							
					$date = $meta[0]['date'];
					$time = human_time_diff( strtotime($date), current_time('timestamp') );
					$cart = $meta[0]['cart_details'];
					$url = get_permalink($cart[0]['id']);
					$download = '<a href="'.$url.'">'.$cart[0]['name'].'</a>';							
					$price = $cart[0]['subtotal'];											
					$message = $notification;
					$message = str_replace( '{fname}', $fname, $message );
					$message = str_replace( '{lname}', $lname, $message );
					$message = str_replace( '{download}', $download, $message );
					$message = str_replace( '{price}', $price, $message );
					$message = str_replace( '{time}', $time, $message );										
					if($img == 'download'){
						$image = get_the_post_thumbnail( $cart[0]['id'], array($img_size,$img_size), array('class' => 'alignleft') );
						$image = '<a href="'.$url.'">'.$image.'</a>';
					}
					elseif($img == 'avatar'){
						$url = get_avatar_url( $payment->user_id, array('size' => $img_size,'default'=>'monsterid',) );
						$image = '<img alt="" src="'. $url .'">';														
					}
					else {
						$image = null;
					}
					
					$out .=  '<li>'.$image.' '.$message.'</li>';
				}
				wp_reset_postdata();
				$out .= '</ul>';
			}			
			return $out;							
		}
		 
	}			