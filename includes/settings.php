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

add_filter(
	'pno_registered_settings_tabs_sections',
	function( $sections ) {

		$sections['general']['recaptcha'] = esc_html__( 'reCAPTCHA' );

		return $sections;

	}
);

add_filter(
	'pno_options_panel_settings',
	function( $settings ) {

		return $settings;

	}
);
