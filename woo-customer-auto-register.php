<?php defined( 'ABSPATH' ) or die( 'Direct Access is not allowed !' );
/**
 * @package   WooCommerce: Auto User Registration
 * @author    SunSid Solutions <sunsidsolutions@gmail.com>
 * @license   GPL-2.0+
 * @link      http://sunsidsolutions.com
 * @copyright 2018-2019 SunSid Solutions
 *
 * @woo-customer-auto-register   1.0.0
 *
 * Plugin Name:       WooCommerce: Auto guest user register
 * Plugin URI:        https://sunsidsolutions.com
 * Description:       This plugin is used to create account of guest user automatically once after placing the order by that guest customer in WooCommerce shopping cart, it also include all past orders into newly created account which was placed by that gust customer (by same email).
 * Version:           1.0.0
 * Author:            SunSid Solutions Team
 * Author URI:        https://sunsidsolutions.com
 * Text Domain:       woo-customer-auto-register
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
?>
<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if WooCommerce is active
 **/
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    deactivate_plugins( basename( __FILE__ ) );
	wp_die(
		sprintf( __( 'You need WooCommerce to activate this addon.', 'woo-customer-auto-register' ))
	);
}

//Load file
add_action( 'init', 'sunsid_include_functions' );
function sunsid_include_functions(){
	plugin_dir_path( __FILE__ ).'include/customer-auto-register.php';
}
