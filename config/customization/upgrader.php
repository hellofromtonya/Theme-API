<?php
/**
 * Runtime configuration for the Upgrader Customization Folder Holder
 *
 * @package     Theme_API\Customization\Upgrader
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://hellofromtonya.com
 * @license     GNU General Public License 2.0+
 */
namespace Theme_API\Customization\Upgrader;

return array(
	'upgrade_folder' => WP_CONTENT_DIR . '/upgrade/',
	'patterns'       => array(
		'missing_folder_message' => '<p>%s : [%s]</p>',
	),
	'messages'       => array(
		'missing_customization_folder' => __( 'This theme does not have a customization folder', 'theme_api' ),
		'missing_upgrade_folder'       => __( 'Whoops, the upgrade folder is missing', 'theme_api' ),
	),
);