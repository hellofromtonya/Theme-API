<?php
/**
 * Customization Upgrader Factory - handles instantiating the upgrader
 *
 * @package     Theme_API\Customization\Upgrader
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://hellofromtonya.com
 * @license     GNU General Public License 2.0+
 */

namespace Theme_API\Customization\Upgrader;

class Factory {

	public static function create( array $options ) {
		if ( ! class_exists( 'Theme_API\Customization\Upgrader\Customization_Folder_Handler' ) ) {
			require_once( 'class-handler.php' ); 
		}
		
		$config = include( THEME_API_CONFIG_DIR . 'customization/upgrader.php' );
		
		return new Customization_Folder_Handler( $config, $options );
	}
}