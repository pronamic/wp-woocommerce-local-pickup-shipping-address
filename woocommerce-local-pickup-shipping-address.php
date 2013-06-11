<?php
/**
* WooCommerce Local Pickup Shipping Address
*
* Display custom shipping address if shipping method is local pickup.
*
* @package WooCommerce Local Pickup Shipping Address
* @author Pronamic <info@pronamic.nl>
* @license GPL-2.0+
* @link http://pronamic.eu/
* @copyright 2013 Pronamic
*
* @wordpress-plugin
* Plugin Name: WooCommerce Local Pickup Shipping Address
* Plugin URI: http://pronamic.eu/
* Description: Hide the shipping address for client if shipping method is local pickup.
* Version: 1.0.0
* Author: Pronamic
* Author URI: http://pronamic.eu/
* Text Domain: wc_lp_sa
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Domain Path: /languages
*/

/**
 * Plugins loaded
 */
function wc_lp_sa_plugins_loaded() {
	load_plugin_textdomain( 'wc_lp_sa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'wc_lp_sa_plugins_loaded' );

/**
 * Admin initialize
 */
function wc_lp_sa_admin_init() {
	add_settings_section(
		'wc_lp_sa_address',
		__( 'WooCommerce Local Pickup Address', 'wc_lp_sa' ),
		'__return_false',
		'general'
	);

	add_settings_field(
		'wc_lp_sa_company',
		__( 'Company', 'wc_lp_sa' ),
		'wc_lp_sa_input_text',
		'general',
		'wc_lp_sa_address',
		array( 'label_for' => 'wc_lp_sa_company' )
	);

	add_settings_field(
		'wc_lp_sa_address_1',
		__( 'Address', 'wc_lp_sa' ),
		'wc_lp_sa_input_text',
		'general',
		'wc_lp_sa_address',
		array( 'label_for' => 'wc_lp_sa_address_1' )
	);

	add_settings_field(
		'wc_lp_sa_city',
		__( 'City', 'wc_lp_sa' ),
		'wc_lp_sa_input_text',
		'general',
		'wc_lp_sa_address',
		array( 'label_for' => 'wc_lp_sa_city' )
	);

	add_settings_field(
		'wc_lp_sa_postcode',
		__( 'Postcode', 'wc_lp_sa' ),
		'wc_lp_sa_input_text',
		'general',
		'wc_lp_sa_address',
		array( 'label_for' => 'wc_lp_sa_postcode' )
	);

	add_settings_field(
		'wc_lp_sa_country',
		__( 'Country', 'wc_lp_sa' ),
		'wc_lp_sa_input_text',
		'general',
		'wc_lp_sa_address',
		array( 'label_for' => 'wc_lp_sa_country' )
	);

	register_setting( 'general', 'wc_lp_sa_company' );
	register_setting( 'general', 'wc_lp_sa_address_1' );
	register_setting( 'general', 'wc_lp_sa_city' );
	register_setting( 'general', 'wc_lp_sa_postcode' );
	register_setting( 'general', 'wc_lp_sa_country' );
}

add_action( 'admin_init', 'wc_lp_sa_admin_init' );

/**
 * Input text
 * 
 * @param array $args
 */
function wc_lp_sa_input_text( $args ) {
	$name = $args['label_for'];

	printf( 
		'<input name="%s" id="%s" type="text" value="%s" />',
		$name,
		$name,
		esc_attr( get_option( $name ) )
	);
}

/**
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.10/templates/emails/email-addresses.php#L24
 */
function wc_lp_sa_order_formatted_shipping_address( $address, $order ) {
	if ( $order->shipping_method == 'local_pickup' ) {
		$address = array(
			'company'   => get_option( 'wc_lp_sa_company' ),
			'address_1' => get_option( 'wc_lp_sa_address_1' ),
			'city'      => get_option( 'wc_lp_sa_city' ),
			'postcode'  => get_option( 'wc_lp_sa_postcode' ),
			'country'   => get_option( 'wc_lp_sa_country' )
		);
	}

	return $address;
}

add_filter( 'woocommerce_order_formatted_shipping_address', 'wc_lp_sa_order_formatted_shipping_address', 10, 2 );
