<?php
/**
 * EduForge WooCommerce & Payment Integration
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_WooCommerce {

	public static function init() {
		// Hook into order completion to automatically enroll student
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'process_course_purchase' ) );
		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'add_course_product_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_course_product_fields' ) );
	}

	/**
	 * Automatically enroll student upon successful WooCommerce payment
	 */
	public static function process_course_purchase( $order_id ) {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$student_id = $order->get_customer_id();
		if ( ! $student_id ) {
			// If guest checkout, lookup user by email or create account
			$user = get_user_by( 'email', $order->get_billing_email() );
			if ( $user ) {
				$student_id = $user->ID;
			}
		}

		if ( ! $student_id ) {
			EduForge_Logger::warning( "Order {$order_id} completed but no student account resolved." );
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_product_id();
			$course_id = get_post_meta( $product_id, '_eduforge_linked_course_id', true );

			if ( $course_id ) {
				$result = EduForge_Course_Manager::enroll_student( $student_id, $course_id );
				if ( ! is_wp_error( $result ) ) {
					EduForge_Logger::info( "Auto-enrolled student {$student_id} into course {$course_id} via Order #{$order_id}." );
					$order->add_order_note( sprintf( __( 'EduForge: Student auto-enrolled into course #%d.', 'eduforge360' ), $course_id ) );
				}
			}
		}
	}

	/**
	 * Add linked course dropdown inside WooCommerce Product edit screen
	 */
	public static function add_course_product_fields() {
		if ( ! function_exists( 'woocommerce_wp_select' ) ) {
			return;
		}

		$courses = get_posts( array(
			'post_type'      => 'courses',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		) );

		$options = array( '' => __( '-- Select Linked Course --', 'eduforge360' ) );
		foreach ( $courses as $c ) {
			$options[ $c->ID ] = $c->post_title;
		}

		echo '<div class="options_group">';
		woocommerce_wp_select( array(
			'id'          => '_eduforge_linked_course_id',
			'label'       => __( 'EduForge Linked Course', 'eduforge360' ),
			'description' => __( 'Select the EduForge360 course automatically unlocked upon purchase.', 'eduforge360' ),
			'desc_tip'    => true,
			'options'     => $options,
		) );
		echo '</div>';
	}

	/**
	 * Save WooCommerce product course association
	 */
	public static function save_course_product_fields( $post_id ) {
		if ( isset( $_POST['_eduforge_linked_course_id'] ) ) {
			$course_id = intval( $_POST['_eduforge_linked_course_id'] );
			update_post_meta( $post_id, '_eduforge_linked_course_id', $course_id );
		}
	}

	/**
	 * Check if WooCommerce is installed & activated
	 */
	public static function is_wc_active() {
		return class_exists( 'WooCommerce' );
	}
}
