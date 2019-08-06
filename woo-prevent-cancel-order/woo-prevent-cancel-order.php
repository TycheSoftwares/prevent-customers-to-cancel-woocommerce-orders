<?php
/**
 * Plugin Name: Prevent Customers To Cancel WooCommerce Orders
 * Plugin URI: https://www.tychesoftwares.com/
 * Description: This plugin prevents customers from cancelling a WooCommerce order. It will hide the Cancel button on My Account page for all user roles, except administrator & shop manager.
 * Author: Tyche Softwares
 * Version: 1.1
 * Author URI: http://www.tychesoftwares.com/about
 * Contributor: Tyche Softwares, http://www.tychesoftwares.com/
 *
 * @package woo-prevent-cancel-order
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Prevent_Cancel_Order' ) ) {

	include_once 'class-wc-prevent-cancel-order.php';

	$prevent_cancel_order = new WC_Prevent_Cancel_Order();
}
