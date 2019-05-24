<?php
/**
 * Trigger recaptcha verification within forms.
 *
 * @package     posterno-recaptcha
 * @copyright   Copyright (c) 2018, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use PNO\Exception;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Validate recaptcha response.
 *
 * @throws Exception When validation fails.
 * @return void
 */
function pno_recaptcha_verify_submission() {

	$secret_key = pno_get_option( 'recaptcha_secret_key', false );

	if ( $secret_key && isset( $_POST['g-recaptcha-response'] ) ) {
		$recaptcha              = new \ReCaptcha\ReCaptcha( $secret_key );
		$recaptcha_response_key = esc_html( $_POST['g-recaptcha-response'] );
		$resp                   = $recaptcha->verify( $recaptcha_response_key, $_SERVER['REMOTE_ADDR'] );
		if ( ! $resp->isSuccess() ) {
			throw new Exception( esc_html__( 'Recaptcha validation failed.', 'posterno-recaptcha' ), 'recaptcha-validation-error' );
		}
	}

}

// Hook into the allowed forms.
$locations = pno_get_option( 'recaptcha_location', [] );

if ( is_array( $locations ) && ! empty( $locations ) ) {
	if ( in_array( 'login', $locations, true ) ) {
		add_action( 'pno_before_user_login', 'pno_recaptcha_verify_submission' );
	}
	if ( in_array( 'registration', $locations, true ) ) {
		add_action( 'pno_before_registration', 'pno_recaptcha_verify_submission' );
	}
}


