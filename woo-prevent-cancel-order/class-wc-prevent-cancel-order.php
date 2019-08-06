<?php
/**
 * Prevent Cancel Orders for WooCommerce main class file.
 *
 * @package woo-prevent-cancel-order
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Prevent_Cancel_Order' ) ) {

	/**
	 * Main Class for Prevent Cancel Orders.
	 */
	class WC_Prevent_Cancel_Order {

		/**
		 * Constructor: Add all the hooks used.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_my_account_my_orders_actions', array( __CLASS__, 'hide_cancel_on_my_account' ), 15, 2 );
			add_filter( 'parse_request', array( __CLASS__, 'sniff_cancel_url' ) );
		}

		/**
		 * Hide cancel button by unsetting the button from array.
		 *
		 * @param array    $actions Actions array.
		 * @param WC_Order $order Woo Order Object.
		 * @return Actions array
		 * @since 1.0
		 */
		public static function hide_cancel_on_my_account( $actions, $order ) {

			foreach ( $actions as $key => $value ) {
				if ( 'cancel' === $key && false === self::check_if_current_user_is_admin() ) {
					unset( $actions[ $key ] );
				}
			}
			return $actions;
		}

		/**
		 * Sniff the URL containing cancel request and redirect to My Account page.
		 *
		 * @since 1.0
		 */
		public static function sniff_cancel_url() {
			global $wp;

			if ( isset( $wp->query_vars['pagename'] ) && 'my-account' === $wp->query_vars['pagename'] &&
				isset( $_GET['cancel_order'] ) && 'yes' === $_GET['cancel_order'] && // phpcs:ignore WordPress.Security.NonceVerification
				isset( $_GET['order_id'] ) && 0 < $_GET['order_id'] && // phpcs:ignore WordPress.Security.NonceVerification
				false === self::check_if_current_user_is_admin() ) {
				wp_safe_redirect( 'my-account' );
				exit;
			}
		}

		/**
		 * Checks if the current user is an admin.
		 *
		 * @return true if user admin else false.
		 * @since 1.0
		 */
		public static function check_if_current_user_is_admin() {
			$current_user_is_admin = true;
			$user                  = wp_get_current_user();
			if ( ! in_array( 'administrator', (array) $user->roles, true ) &&
				! in_array( 'shop_manager', (array) $user->roles, true ) ) {
				$current_user_is_admin = false;
			}
			return $current_user_is_admin;
		}
	}
}
