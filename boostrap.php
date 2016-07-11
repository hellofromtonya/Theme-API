<?php
/**
 * Theme API - Adds needed functionality for themes
 *
 * @package         Theme_API
 * @author          hellofromTonya
 * @license         GPL-2.0+
 * @link            https://hellofromtonya.com
 *
 * @wordpress-plugin
 * Plugin Name:     Theme API
 * Plugin URI:      https://hellofromtonya.com
 * Description:     Adds customization and switching theme features missing from Core
 * Version:         1.0.2
 * Author:          hellofromTonya
 * Author URI:      https://hellofromtonya.com
 * Text Domain:     theme_api
 * Requires WP:     4.4
 * Requires PHP:    5.6
 */

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

namespace Theme_API;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Oh silly, you cannot call me like this.' );
}

init_constants();

add_action( 'plugins_loaded', __NAMESPACE__ . '\launch' );
/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	load_dependencies();
}

/**
 * To speed everything up, we are loading files directly here.
 *
 * @since 1.0.0
 *
 * @return void
 */
function load_dependencies() {

	if ( is_admin() ) {
		include_once( __DIR__ . '/src/customization/bootstrap.php' );
	}
}

/**
 * Initialize the plugin constants.
 *
 * @since 1.0.0
 *
 * @return void
 */
function init_constants() {
	define( 'THEME_API_VERSION', '1.0.0' );
	define( 'THEME_API_TESTING', true );
	define( 'THEME_API_DIR', plugin_dir_path( __FILE__ ) );

	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}
	define( 'THEME_API_URL', $plugin_url );
	define( 'THEME_API_CONFIG_DIR', THEME_API_DIR . 'config/' );
}