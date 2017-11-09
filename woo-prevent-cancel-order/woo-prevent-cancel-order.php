<?php
/*
Plugin Name: Prevent Customers To Cancel WooCommerce Orders
Plugin URI: https://www.tychesoftwares.com/
Description: This plugin prevents customers from cancelling a WooCommerce order. It will hide the Cancel button on My Account page for all user roles, except administrator & shop manager.
Author: Tyche Softwares
Version: 1.1
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/

if ( !class_exists( 'WC_Prevent_Cancel_Order' ) ) {

	class WC_Prevent_Cancel_Order {
		
		public function __construct() {
			add_filter( 'woocommerce_my_account_my_orders_actions', array( __CLASS__, 'hide_cancel_on_my_account' ), 15, 2 );
			add_filter( 'parse_request'                           , array( __CLASS__, 'sniff_cancel_url' ) );
		}
		
		public static function hide_cancel_on_my_account( $actions, $order ) {

			foreach( $actions as $key => $value ) {
				if ( 'cancel' === $key && false === WC_Prevent_Cancel_Order::check_if_current_user_is_admin() ) {
					unset( $actions[ $key ] );
				}
			}
			return $actions;
		}

		public static function sniff_cancel_url() {
			global $wp;

			if ( isset( $wp->query_vars[ 'pagename' ] ) && 'my-account' === $wp->query_vars[ 'pagename' ] && 
				 isset( $_GET[ 'cancel_order' ] ) && 'yes' === $_GET[ 'cancel_order' ] && 
				 isset( $_GET[ 'order_id' ] ) && 0 < $_GET[ 'order_id' ] && 
				 false === WC_Prevent_Cancel_Order::check_if_current_user_is_admin() ) {
				wp_redirect( 'my-account' );
				exit;
			}
		}

		public static function check_if_current_user_is_admin() {
			$current_user_is_admin = true;
			$user = wp_get_current_user();
			if ( !in_array( 'administrator', (array) $user->roles ) && 
				 !in_array( 'shop_manager', (array) $user->roles ) ) {
				$current_user_is_admin = false;
			}
			return $current_user_is_admin;
		}
	}
}

$prevent_cancel_order = new WC_Prevent_Cancel_Order();

