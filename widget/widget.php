<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* Widget
		*
		* @package     
		* @subpackage  
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	class WOW_EDD_RECENT_PURCHASES_WIDGET extends WP_Widget {	
		
		
		/**
			* Register widget with WordPress.
		*/
		function __construct() {			
			parent::__construct(
			'wow_edd_recent_purchases', // Base ID
			'EDD Purchases Notifications', // Name
			array( 'description' => 'Display Easy Digital Downloads recent purchase', ) // Args
			);
			
			
		}
		
		/**
			* Front-end display of widget.
			*
			* @see WP_Widget::widget()
			*
			* @param array $args     Widget arguments.
			* @param array $instance Saved values from database.
		*/
		public function widget( $args, $instance ) {	
			$args['id']        = ( isset( $args['id'] ) ) ? $args['id'] : 'wow_edd_purchases_notifications';
			$instance['title'] = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );
			
			$param = get_option('edd_recent_purchases');
			
			$quantity = isset( $param['widget_quntity'] ) ? $param['widget_quntity'] : '5';
			
			$notification = isset( $param['widget'] ) ? $param['widget'] : " {fname} {lname} ". __( "purchased", "edd-notifications" ) . " {download} " . __( "for ", "edd-notifications" ) . " {price} {time} ". __( "ago ", "edd-notifications" );
			
			$img = isset( $param['widget_img'] ) ? $param['widget_img'] : 'none'; 
			$img_size = isset( $param['widget_img_size'] ) ? $param['widget_img_size'] : '32';
			
			
			$args_payments = array(
				'numberposts'      => $quantity,
				'post_status'      => 'publish',			
				'post_type'        => 'edd_payment',
				'suppress_filters' => true, 
			);						
			$payments = get_posts( $args_payments );
			
			$out = null;
			if ( $payments ) { 
				$out .= '<ul class="wow_edd_recent_purchases_widget">';
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
						$image = '<img src="'. $url .'" class="alignleft" width="'.$img_size.'">';														
					}
					else {
						$image = null;
					}
					
					$out .=  '<li>'.$image.' '.$message.'</li>';
				}
				wp_reset_postdata();
				$out .= '</ul>';
			}			
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo $out;
			echo $args['after_widget'];
			
		}
		
		/**
			* Back-end widget form.
			*
			* @see WP_Widget::form()
			*
			* @param array $instance Previously saved values from database.
		*/
		public function form( $instance ) {		
		?>		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'edd-notifications' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php if(!empty($instance['title']))echo $instance['title']; ?>"/>
			
		</p>
		<?php 
		}
		
		/**
			* Sanitize widget form values as they are saved.
			*
			* @see WP_Widget::update()
			*
			* @param array $new_instance Values just sent to be saved.
			* @param array $old_instance Previously saved values from database.
			*
			* @return array Updated safe values to be saved.
		*/
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
			
			return $instance;
		}
		
	} // class Foo_Widget					
	
	function edd_recent_purchases_pro_widget() {
		register_widget( 'WOW_EDD_RECENT_PURCHASES_WIDGET' );	
	}
add_action( 'widgets_init', 'edd_recent_purchases_pro_widget' );