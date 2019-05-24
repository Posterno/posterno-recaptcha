<?php
/**
 * Handles scripts registration.
 *
 * @package     posterno-recaptcha
 * @copyright   Copyright (c) 2018, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register and load scripts required for recaptcha.
 */
add_action(
	'wp_enqueue_scripts',
	function() {

		$site_key  = pno_get_option( 'recaptcha_site_key', false );
		$locations = pno_get_option( 'recaptcha_location', [] );

		if ( $site_key && ! empty( $locations ) ) {

			wp_register_script( 'posterno-recaptcha', 'https://www.google.com/recaptcha/api.js', [], PNO_REC_VERSION, true );

			foreach ( $locations as $location ) {

				if (
					( $location === 'login' && is_page( pno_get_login_page_id() ) ) ||
					( $location === 'registration' && is_page( pno_get_registration_page_id() ) )
				) {
					wp_enqueue_script( 'posterno-recaptcha' );
				}
			}
		}
	}
);

/**
 * Modify the script tag attributes for the recaptcha handle.
 */
add_filter(
	'script_loader_tag',
	function( $tag, $handle ) {

		if ( $handle === 'posterno-recaptcha' ) {
			return str_replace( ' src', ' async defer src', $tag );
		}

		return $tag;

	},
	10,
	2
);
