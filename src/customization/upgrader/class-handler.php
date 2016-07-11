<?php
/**
 * Customization folder handler - responsible for moving the customization
 * folder and its contents out of the theme during the upgrade and then
 * back once the upgrade is completed.
 *
 * @package     Theme_API\Customization\Upgrader
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://hellofromtonya.com
 * @license     GNU General Public License 2.0+
 */

namespace Theme_API\Customization\Upgrader;

use Theme_Upgrader;

class Customization_Folder_Handler {

	/**
	 * Runtime configuration array
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Options passed into WordPress from themer
	 *
	 * @param array $options {
	 *     Options used by the upgrader.
	 *
	 * @type string $package Package for update.
	 * @type string $destination Update location.
	 * @type bool $clear_destination Clear the destination resource.
	 * @type bool $clear_working Clear the working resource.
	 * @type bool $abort_if_destination_exists Abort if the Destination directory exists.
	 * @type bool $is_multi Whether the upgrader is running multiple times.
	 * @type array $hook_extra Extra hook arguments.
	 * }
	 */
	protected $options;

	/**
	 * Upgrade folder absolute path
	 *
	 * @var string
	 */
	protected $upgrade_folder;

	/**
	 * Customization folder absolute path
	 *
	 * @var string
	 */
	protected $customization_folder;

	/**********************
	 * Initializers
	 *********************/

	/**
	 * Instantiates the handler
	 *
	 * @since 1.0.0
	 *
	 * @param array $config Runtime configuration parameters
	 * @param array $options Theme update request (from the supplier)
	 */
	public function __construct( array $config, array $options ) {
		$this->init_properties( $config, $options );

		if ( $this->ok_to_bind_to_events() ) {
			$this->upgrade_folder .= $this->options['hook_extra']['customization']['folder_name'];
			$this->init_events();
		}
	}

	/**
	 * Initialize the properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $config
	 * @param array $options
	 *
	 * @return void
	 */
	protected function init_properties( array $config, array $options ) {
		$this->config               = $config;
		$this->options              = $options;
		$this->upgrade_folder       = $this->config['upgrade_folder'];
		$this->customization_folder = $options['hook_extra']['customization']['path'];
	}

	/**
	 * Initialize the WordPress events.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_events() {
		add_filter( 'upgrader_source_selection', array( $this, 'move_from_theme' ), 10, 4 );
		add_filter( 'upgrader_post_install', array( $this, 'move_back_to_theme' ), 10, 3 );
	}

	/**********************
	 * Public Callbacks
	 *********************/

	/**
	 * Theme customization handler.
	 *
	 * @since 1.0.0
	 *
	 * @param $source
	 * @param $remote_source
	 * @param $theme_object
	 * @param array|mixed $hook_extra
	 *
	 * @return mixed
	 */
	public function move_from_theme( $source, $remote_source, $theme_object, $hook_extra ) {

		if ( $this->is_valid_move_from_theme_request( $source, $theme_object, $hook_extra ) ) {
			$this->move_the_folder( true );
		}

		return $source;
	}

	/**
	 * Move the customization folder back into the theme.
	 *
	 * @since 1.0.0
	 *
	 * @param $response
	 * @param $hook_extras
	 * @param array $results
	 *
	 * @return mixed
	 */
	public function move_back_to_theme( $response, $hook_extras, array $results ) {

		if ( ! $response || ! array_key_exists( 'destination', $results ) ) {
			return $response;
		}

		$this->move_the_folder( false );

		return $response;
	}

	/**********************
	 * Helpers
	 *********************/

	/**
	 * Checks if it's ok to bind the handler to the events in WP_Upgrader.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function ok_to_bind_to_events() {
		return $this->has_folder( 'upgrade_folder' ) &&
		       $this->has_folder( 'customization_folder' );
	}

	/**
	 * Checks if the folders exist.
	 *
	 * @since 1.0.0
	 *
	 * @param string $folder_property
	 *
	 * @return bool
	 */
	protected function has_folder( $folder_property ) {
		if ( file_exists( $this->$folder_property ) && is_dir( $this->$folder_property ) ) {
			return true;
		}

		printf( $this->config['patterns']['missing_folder_message'],
			$this->config['messages'][ 'missing_' . $folder_property ],
			$this->$folder_property
		);

		return false;
	}

	/**
	 * Checks if this is a valid move request.
	 *
	 * @since 1.0.0
	 *
	 * @param $source
	 * @param $theme_object
	 * @param array $hook_extra
	 *
	 * @return bool
	 */
	protected function is_valid_move_from_theme_request( $source, $theme_object, array $hook_extra ) {
		if ( is_wp_error( $source ) || ! $this->is_theme_upgrade( $theme_object ) ) {
			return false;
		}

		if ( ! array_key_exists( 'customization', $hook_extra ) ) {
			return false;
		}

		return $hook_extra['customization']['path'] === $this->options['hook_extra']['customization']['path'];
	}

	/**
	 * Double check that yup this is a theme upgrade/update.
	 *
	 * @since 1.0.0
	 *
	 * @param $theme_object
	 *
	 * @return bool
	 */
	protected function is_theme_upgrade( $theme_object ) {
		return is_a( $theme_object, 'Theme_Upgrader' );
	}

	/**
	 * Move the folder to the specified location.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $move_to_upgrades
	 *
	 * @return bool
	 */
	protected function move_the_folder( $move_to_upgrades = false ) {
		global $wp_filesystem;

		$source      = $this->get_path( $move_to_upgrades, true );
		$destination = $this->get_path( $move_to_upgrades, false );

		return $wp_filesystem->move( $source, $destination, true );
	}

	/**
	 * Get the specified path.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $move_out_of_theme
	 * @param bool $get_source
	 *
	 * @return string
	 */
	protected function get_path( $move_out_of_theme = false, $get_source = true ) {

		if ( $get_source ) {
			return $move_out_of_theme ? $this->customization_folder : $this->upgrade_folder;
		}

		return $move_out_of_theme ? $this->upgrade_folder : $this->customization_folder;
	}
}