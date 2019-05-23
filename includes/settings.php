<?php
/**
 * Register new settings for the options panel.
 *
 * @package     posterno
 * @copyright   Copyright (c) 2018, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

use Carbon_Fields\Field;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register a new tab within the options panel.
 */
add_filter(
	'pno_registered_settings_tabs_sections',
	function( $sections ) {

		$sections['general']['recaptcha'] = esc_html__( 'reCAPTCHA', 'posterno-recaptcha' );

		return $sections;

	}
);

/**
 * Get options for the display location of the recaptcha field.
 *
 * @return array
 */
function pno_get_recaptcha_locations() {

	$locations = [
		'login'              => esc_html__( 'Login form', 'posterno-recaptcha' ),
		'registration'       => esc_html__( 'Registration form', 'posterno-recaptcha' ),
		'password_recovery'  => esc_html__( 'Password recovery form', 'posterno-recaptcha' ),
		'listing_submission' => esc_html__( 'Listing submission form', 'posterno-recaptcha' ),
	];

	return $locations;

}

/**
 * Register settings for the addon.
 */
add_filter(
	'pno_options_panel_settings',
	function( $settings ) {

		$settings['recaptcha'][] = Field::make( 'text', 'recaptcha_site_key', esc_html__( 'Google reCAPTCHA site key', 'posterno-recaptcha' ) )
			->set_help_text( __( 'Enter your site key.', 'posterno-recaptcha' ) . ' ' . sprintf( __( 'Get your reCAPTCHA keys from Google', 'posterno-recaptcha' ), 'https://www.google.com/recaptcha/' ) );

		$settings['recaptcha'][] = Field::make( 'text', 'recaptcha_secret_key', esc_html__( 'Google reCAPTCHA secret key', 'posterno-recaptcha' ) )
			->set_help_text( __( 'Enter your site secret key.', 'posterno-recaptcha' ) . ' ' . sprintf( __( 'Get your reCAPTCHA keys from Google', 'posterno-recaptcha' ), 'https://www.google.com/recaptcha/' ) );

		$settings['recaptcha'][] = Field::make( 'multiselect', 'recaptcha_location', esc_html__( 'Google reCAPTCHA display location', 'posterno-recaptcha' ) )
			->set_help_text( esc_html__( 'Select which forms you wish to protect.', 'posterno-recaptcha' ) )
			->add_options( 'pno_get_recaptcha_locations' );

		return $settings;

	}
);
