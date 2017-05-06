<?php
/**
 * Plugin Name: Meta Box Site Post
 * Plugin URI:
 * Description: Add-on for meta box plugin, allows you to add field type 'site_post' that allow you to choose post from other sites in the multisite network.
 * Version: 1.0.0
 * Author: Guillermo García
 * Author URI: http://guillermogarcia.info
 * License: GPL2+
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Site_Post' ) ) {
	/**
	 * Extension main class.
	 */
	class RWMB_Site_Post {
		/**
		 * Indicate that the meta box is saved or not.
		 * This variable is used inside group field to show child fields.
		 *
		 * @var bool
		 */
		static $saved = false;

		/**
		 * Add hooks to meta box.
		 */
		public function __construct() {
			/**
			 * Hook to 'init' with priority 5 to make sure all actions are registered before Meta Box 4.9.0 runs
			 */
			add_action( 'init', array( $this, 'load_files' ), 5 );

			add_action( 'rwmb_before', array( $this, 'set_saved' ) );
			add_action( 'rwmb_after', array( $this, 'unset_saved' ) );
		}

		/**
		 * Load field group class.
		 */
		public function load_files() {
			if ( class_exists('RWMB_Post_Field') && ! class_exists('RWMB_Site_Post_Field') && is_multisite() ) {
				require plugin_dir_path( __FILE__ ) . 'class-site-post-field.php';
			}
		}

		/**
		 * Check if current meta box is saved.
		 * This variable is used inside group field to show child fields.
		 *
		 * @param object $obj Meta Box object
		 */
		public function set_saved( $obj ) {
			self::$saved = $obj->is_saved();
		}

		/**
		 * Unset 'saved' variable, to be ready for next meta box.
		 */
		public function unset_saved() {
			self::$saved = false;
		}
	}

	new RWMB_Site_Post;
}
