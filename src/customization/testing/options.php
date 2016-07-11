<?php
/**
 * This file is just for testing purposes for functionality such as
 * adding the customization hook into the upgrade request (as it
 * doesn't exist ... yet).
 *
 * @package     Theme_API\Customization
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://hellofromtonya.com
 * @license     GNU General Public License 2.0+
 */
namespace Theme_API\Customization;

/**
 * Add customization parameter to the 'hook_extra' upgrade request.
 *
 * @since 1.0.0
 *
 * @param array $options
 *
 * @return void
 */
function add_customization_to_hook_extra( array &$options ) {

	$destination = trailingslashit( $options['destination'] ) . $options['hook_extra']['theme'];

	$options['hook_extra']['customization'] = array(
		'preserve'    => true,
		'folder_name' => 'customization',
		'path'        => $destination . '/customization/',
	);
}