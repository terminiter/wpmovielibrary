<?php
/**
 * WPMovieLibrary Settings Class extension.
 * 
 * Manage WPMovieLibrary settings
 *
 * @package   WPMovieLibrary
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 CaerCam.org
 */

require_once( WPMOLY_PATH . 'includes/wpmoly-config.php' );

if ( ! class_exists( 'WPMOLY_Settings' ) ) :

	/**
	 * WPMOLY Settings class
	 *
	 * @package WPMovieLibrary
	 * @author  Charlie MERLAND <charlie@caercam.org>
	 */
	class WPMOLY_Settings extends WPMOLY_Module {

		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		public function __construct() {

			$this->init();
		}

		/**
		 * Magic!
		 * 
		 * @since    2.0
		 * 
		 * @param    string    $name Called method name
		 * @param    array     $arguments Called method arguments
		 * 
		 * @return   mixed    Callback function return value
		 */
		public static function __callStatic( $name, $arguments ) {

			if ( false !== strpos( $name, 'get_available_movie_' ) ) {
				$name = str_replace( 'get_available_movie_', '', $name );
				return call_user_func( __CLASS__ . '::get_movie_details', $name );
			}

			if ( false !== strpos( $name, 'get_default_movie_' ) ) {
				$name = str_replace( 'get_default_movie_', '', $name );
				return call_user_func( __CLASS__ . '::get_movie_details_default', $name );
			}
		}

		/**
		 * Return the plugin settings.
		 *
		 * @since    1.0
		 *
		 * @return   array    Plugin Settings
		 */
		public static function get_settings() {

			global $wpmoly_settings;

			if ( is_null( $wpmoly_settings ) )
				$wpmoly_settings = get_option( 'wpmoly_settings' );

			return $wpmoly_settings;
		}

		/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 *
		 *                         Hooks setup
		 *
		 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		private static function get_config() {

			global $wpmoly_config;

			$wpmoly_config = apply_filters( 'wpmoly_filter_config', $wpmoly_config );

			return $wpmoly_config;
		}

		private static function get_movie_details( $detail = null ) {

			global $wpmoly_movie_details;

			/**
			 * Filter the Details list to add/remove details.
			 *
			 * This should be used through Plugins to create additionnal
			 * details or edit existing.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_movie_details Existing details
			 */
			$details = apply_filters( 'wpmoly_pre_filter_details', $wpmoly_movie_details );

			if ( ! is_null( $detail ) && isset( $details[ "movie_{$detail}" ] ) )
				$details = apply_filters( "wpmoly_filter_detail_{$detail}", $details[ "movie_{$detail}" ]['options'] );
			else
				foreach ( $details as $detail => $data )
					$details[ $detail ]['options'] = apply_filters( 'wpmoly_filter_detail_' . str_replace( 'movie_', '', $detail ), $details[ $detail ]['options'] );

			/**
			 * Filter the Details list to add/remove details.
			 *
			 * This should be used through Plugins to create additionnal
			 * details or edit existing.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_movie_details Existing details
			 */
			$details = apply_filters( 'wpmoly_filter_details', $details );

			return $details;
		}

		/**
		 * Return the default value for a specitif Movie Detail
		 *
		 * @since    2.0
		 * 
		 * @param    string    $detail
		 *
		 * @return   array    WPMOLY Movie details default value.
		 */
		private static function get_movie_details_default( $detail ) {

			$_detail = self::get_movie_details( "movie_{$detail}" );
			$default = $_detail['default'];

			return $default;
		}

		/**
		 * Return all available shortcodes
		 *
		 * @since    1.1
		 *
		 * @return   array    Available shortcodes
		 */
		public static function get_available_shortcodes() {

			global $wpmoly_shortcodes;

			/**
			 * Filter the Shortcodes list to add/remove shortcodes.
			 *
			 * This should be used through Plugins to create additionnal
			 * Shortcodes.
			 *
			 * @since    1.2
			 *
			 * @param    array    $wpmoly_shortcodes Existing Shortcodes
			 */
			$wpmoly_shortcodes = apply_filters( 'wpmoly_filter_shortcodes', $wpmoly_shortcodes );

			return $wpmoly_shortcodes;
		}

		/**
		 * Return all supported language names for translation
		 *
		 * @since    2.0
		 *
		 * @return   array    Available languages
		 */
		public static function get_available_languages() {

			global $wpmoly_languages;

			/**
			 * Filter the Languages list to add/remove shortcodes.
			 *
			 * This should be used through Plugins to create additionnal
			 * Languages.
			 *
			 * @since    1.2
			 *
			 * @param    array    $wpmoly_languages Existing languages
			 */
			$wpmoly_languages = apply_filters( 'wpmoly_filter_languages', $wpmoly_languages );

			return $wpmoly_languages;
		}

		/**
		 * Return a limited number of language names supported by TMDb API
		 *
		 * @since    2.0
		 *
		 * @return   array    Supported languages
		 */
		public static function get_supported_languages() {

			global $wpmoly_supported_languages;

			/**
			 * Filter the supported languages list to add/remove languages.
			 *
			 * This should be used through Plugins to add additionnal
			 * languages.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_supported_languages Existing languages
			 */
			$wpmoly_supported_languages = apply_filters( 'wpmoly_filter_supported_languages', $wpmoly_supported_languages );

			return $wpmoly_supported_languages;
		}

		/**
		 * Return all available country names for translation
		 *
		 * @since    2.0
		 *
		 * @return   array    Supported 
		 */
		public static function get_supported_countries() {

			global $wpmoly_countries;

			/**
			 * Filter the supported country names list to add/remove countries.
			 *
			 * This should be used through Plugins to add additionnal
			 * countries.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_countries Existing countries
			 */
			$wpmoly_countries = apply_filters( 'wpmoly_filter_countries', $wpmoly_countries );

			return $wpmoly_countries;
		}

		/**
		 * Return Panels data
		 *
		 * @since    2.0
		 *
		 * @return   array    WPMOLY Panels
		 */
		public static function get_metabox_panels() {

			global $wpmoly_metabox_panels;

			/**
			 * Filter the Metabox Panels to add/remove tabs.
			 *
			 * This should be used through Plugins to create additionnal
			 * Metabox panels.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_metabox_panels Existing Panels
			 */
			$wpmoly_metabox_panels = apply_filters( 'wpmoly_filter_metabox_panels', $wpmoly_metabox_panels );

			return $wpmoly_metabox_panels;
		}

		/**
		 * Return Admin Menu config array data
		 *
		 * @since    2.0
		 *
		 * @return   array    WPMOLY Admin Menu array
		 */
		public static function get_admin_menu() {

			global $wpmoly_admin_menu;

			/**
			 * Filter the Admin menu list to edit/add/remove subpages.
			 *
			 * This should be used through Plugins to create additionnal
			 * subpages.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_admin_menu Admin menu
			 */
			$wpmoly_admin_menu = apply_filters( 'wpmoly_filter_admin_menu', $wpmoly_admin_menu );

			return $wpmoly_admin_menu;
		}

		/**
		 * Return Admin Bar Menu config array data
		 *
		 * @since    2.0
		 *
		 * @return   array    WPMOLY Admin Bar Menu array
		 */
		public static function get_admin_bar_menu() {

			global $wpmoly_admin_bar_menu;

			/**
			 * Filter the Admin menu list to edit/add/remove subpages.
			 *
			 * This should be used through Plugins to create additionnal
			 * subpages.
			 *
			 * @since    2.0
			 *
			 * @param    array    $wpmoly_admin_menu Admin menu
			 */
			$wpmoly_admin_bar_menu = apply_filters( 'wpmoly_filter_admin_bar_menu', $wpmoly_admin_bar_menu );

			return $wpmoly_admin_bar_menu;
		}

		/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 *
		 *                         Accessors
		 *
		 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/**
		 * Load default settings.
		 * 
		 * @since    1.0
		 * 
		 * @param    boolean    $minify Should we return only default values?
		 *
		 * @return   array      The Plugin Settings
		 */
		public static function get_default_settings( $minify = false ) {

			$wpmoly_config = self::get_config();

			if ( true !== $minify )
				return $wpmoly_config;

			$defauts = array();
			foreach ( $wpmoly_config as $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $slug => $field ) {
						if ( 'sorter' == $field['type'] )
							$defauts[ $slug ] = $field['used'];
						else
							$defauts[ $slug ] = $field['default'];
					}
				}
			}

			return $defauts;
		}

		/**
		 * General settings accessor
		 *
		 * @since    1.0
		 * 
		 * @param    string        $setting Requested setting slug
		 * 
		 * @return   mixed         Requested setting
		 */
		public static function get( $setting = '' ) {

			$wpmoly_settings = self::get_settings();

			$shorter = str_replace( 'wpmoly-', '', $setting );
			if ( isset( $wpmoly_settings[ $shorter ] ) )
				return $wpmoly_settings[ $shorter ];

			$longer = "wpmoly-$setting";
			if ( isset( $wpmoly_settings[ $longer ] ) )
				return $wpmoly_settings[ $longer ];

			if ( isset( $wpmoly_settings[ $setting ] ) )
				return $wpmoly_settings[ $setting ];

			return false;
		}

		/**
		 * Return the default Movie Media
		 *
		 * @since    1.0
		 *
		 * @return   array    WPMOLY Default Movie Media.
		 */
		/*public static function get_default_movie_media() {

			$wpmoly_movie_details = self::get_details();

			$default = $wpmoly_movie_details['movie_media']['default'];

			return $default;
		}*/

		/**
		 * Return the default Movie Status
		 *
		 * @since    1.0
		 *
		 * @return   array    WPMOLY Default Movie Status.
		 */
		/*public static function get_default_movie_status() {

			$wpmoly_movie_details = self::get_details();

			$default = $wpmoly_movie_details['movie_status']['default'];

			return $default;
		}*/

		/**
		 * Return all supported Movie Details fields
		 *
		 * @since    1.0
		 *
		 * @return   array    WPMOLY Supported Movie Details fields.
		 */
		public static function get_supported_movie_details() {

			$wpmoly_movie_details = self::get_movie_details();

			return $wpmoly_movie_details;
		}

		/**
		 * Return all supported Movie Meta fields
		 *
		 * @since    1.0
		 *
		 * @return   array    WPMOLY Supported Movie Meta fields.
		 */
		public static function get_supported_movie_meta( $type = null ) {

			global $wpmoly_movie_meta;

			if ( is_null( $wpmoly_movie_meta ) )
				require( WPMOLY_PATH . 'includes/wpmoly-config.php' );

			if ( ! is_null( $type ) ) {
				$meta = array();
				foreach ( $wpmoly_movie_meta as $slug => $data )
					if ( $data['group'] == $type )
						$meta[ $slug ] = $data;

				return $meta;
			}

			return $wpmoly_movie_meta;
		}

		/**
		 * Return all supported Shortcodes aliases
		 *
		 * @since    1.1
		 *
		 * @return   array    WPMOLY Supported Shortcodes aliases.
		 */
		public static function get_supported_movie_meta_aliases() {

			global $wpmoly_movie_meta_aliases;

			return $wpmoly_movie_meta_aliases;
		}

		/**
		 * Delete stored settings.
		 * 
		 * This is irreversible, but shouldn't be used anywhere else than
		 * when uninstalling the plugin.
		 * 
		 * @since    1.0
		 */
		public static function clean_settings() {

			delete_option( 'wpmoly_settings' );
		}

		/**
		 * Prepares sites to use the plugin during single or network-wide activation
		 *
		 * @since    1.0
		 *
		 * @param    bool    $network_wide
		 */
		public function activate( $network_wide ) {}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @since    1.0
		 */
		public function deactivate() {}

		/**
		 * Set the uninstallation instructions
		 *
		 * @since    1.0
		 */
		public static function uninstall() {

			self::clean_settings();
		}

		/**
		 * Initializes variables
		 *
		 * @since    1.0
		 */
		public function init() {}

		/**
		 * Register callbacks for actions and filters
		 * 
		 * @since    1.0
		 */
		public function register_hook_callbacks() {}

	}

endif;

/**
 * General settings accessor
 *
 * @since    2.0
 * 
 * @param    string        $setting Requested setting slug
 * 
 * @return   mixed         Requested setting
 */
function wpmoly_o( $search ) {

	return WPMOLY_Settings::get( $search );
}