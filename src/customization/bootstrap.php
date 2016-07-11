<?php
/**
 * Customization Module
 *
 * @package     Theme_API\Customization
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://hellofromtonya.com
 * @license     GNU General Public License 2.0+
 */

namespace Theme_API\Customization;

use Theme_API\Customization\Upgrader\Customization_Folder_Handler;
use Theme_API\Customization\Upgrader\Factory;

add_filter( 'upgrader_package_options', __NAMESPACE__ . '\theme_upgrade_handler' );
/**
 * Theme Upgrade Handler.
 *
 * @since 1.0.0
 *
 * @param array $options
 *
 * @return void
 */
function theme_upgrade_handler( array $options ) {

	if ( ! is_theme_upgrade( $options['hook_extra'] ) ) {
		return $options;
	}

	load_testing_module( $options );

	load_customization_handler( $options );

	return $options;
}

/**
 * Checks if this is a theme upgrade/update.
 *
 * @since 1.0.0
 *
 * @param array $hook_extra
 *
 * @return bool
 */
function is_theme_upgrade( array $hook_extra ) {
	if ( ! array_key_exists( 'theme', $hook_extra ) ) {
		return false;
	}

	if ( ! array_key_exists( 'action', $hook_extra ) ) {
		return false;
	}

$args = array(
	'source'                      => '',
	'destination'                 => '',
	'clear_destination'           => false,
	'clear_working'               => false,
	'abort_if_destination_exists' => true,
	'hook_extra'                  => array(
		'theme'  => 'your theme name',
		'type'   => 'theme',
		'action' => 'update',
		'customization' => array(
			'preserve'    => true,
			'folder_name' => 'customization', // name of the customization folder
			'path'        => '', // path to the customization folder
		)
	),
);

	return 'update' == $hook_extra['action'] && 'theme' == $hook_extra['type'];
}

/**
 * Load the customization folder handler.
 *
 * @since 1.0.0
 *
 * @param array $options
 *
 * @return Customization_Folder_Handler
 */
function load_customization_handler( array $options ) {
	require_once( 'upgrader/class-factory.php' );

	return Factory::create( $options );
}

/**
 * The testing module is just for proving stuff works when the themer's
 * update request does not yet include the Theme API parameters in the
 * `hook_extra`.
 *
 * @since 1.0.0
 *
 * @param array $options
 *
 * @return void
 */
function load_testing_module( array &$options ) {
	if ( ! defined( 'THEME_API_TESTING' ) || ! THEME_API_TESTING ) {
		return;
	}

	include_once( 'testing/options.php' );
	add_customization_to_hook_extra( $options );
}