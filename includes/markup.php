<?php
/**
 * Handles markup modification of the forms in order to accommodate the recaptcha requirements.
 *
 * @package     posterno-recaptcha
 * @copyright   Copyright (c) 2018, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Add required attributes to the forms submit button.
 *
 * @param array $fields list of fields for the forms.
 * @return array
 */
function pno_recaptcha_add_submit_btn_class( $fields ) {

	$site_key = pno_get_option( 'recaptcha_site_key', false );

	if ( $site_key ) {

		if ( isset( $fields['submit-form'] ) ) {

			$existing_classes = $fields['submit-form']['attributes']['class'];
			$new_classes      = $existing_classes . ' g-recaptcha';

			$fields['submit-form']['attributes']['class']         = $new_classes;
			$fields['submit-form']['attributes']['data-sitekey']  = esc_attr( $site_key );
			$fields['submit-form']['attributes']['data-callback'] = 'pnoRecaptchaOnSubmit';
			$fields['submit-form']['attributes']['data-badge']    = 'inline';

		}
	}

	return $fields;

}

$locations = pno_get_option( 'recaptcha_location', [] );

if ( is_array( $locations ) && ! empty( $locations ) ) {
	if ( in_array( 'login', $locations, true ) ) {
		add_filter( 'pno_login_form_fields', 'pno_recaptcha_add_submit_btn_class' );
	}
	if ( in_array( 'registration', $locations, true ) ) {
		add_filter( 'pno_registration_form_fields', 'pno_recaptcha_add_submit_btn_class' );
	}
	if ( in_array( 'password_recovery', $locations, true ) ) {
		add_filter( 'pno_forgot_password_form_fields', 'pno_recaptcha_add_submit_btn_class' );
	}
}

/**
 * Add markup to the forms pages for the recaptcha field.
 */
add_action(
	'wp_footer',
	function() {

		$site_key  = pno_get_option( 'recaptcha_site_key', false );
		$locations = pno_get_option( 'recaptcha_location', [] );
		$form_id   = false;

		ob_start();

		foreach ( $locations as $location ) {
			if ( $location === 'login' && is_page( pno_get_login_page_id() ) ) {
				$form_id = 'pno-form-login';
			} elseif ( $location === 'registration' && is_page( pno_get_registration_page_id() ) ) {
				$form_id = 'pno-form-registration';
			} elseif ( $location === 'password_recovery' && is_page( pno_get_password_recovery_page_id() ) ) {
				$form_id = 'pno-form-forgotPassword';
			}
		}

		?>
		<script>
			function pnoRecaptchaOnSubmit(token) {
				document.getElementById( "<?php echo esc_js( $form_id ); ?>" ).submit();
			}
		</script>
		<?php

		$markup = ob_get_clean();

		if ( $site_key && ! empty( $locations ) && is_array( $locations ) ) {

			foreach ( $locations as $location ) {
				if (
					( $location === 'login' && is_page( pno_get_login_page_id() ) ) ||
					( $location === 'registration' && is_page( pno_get_registration_page_id() ) ) ||
					( $location === 'password_recovery' && is_page( pno_get_password_recovery_page_id() ) )
				) {
					echo $markup; //phpcs:ignore
				}
			}
		}
	}
);
