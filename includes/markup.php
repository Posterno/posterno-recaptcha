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

	$site_key   = pno_get_option( 'recaptcha_site_key', false );
	$locations  = pno_get_option( 'recaptcha_location', [] );
	$is_allowed = true;

	foreach ( $locations as $location ) {
		switch ( $location ) {
			case 'login':
				$is_allowed = doing_filter( 'pno_login_form_fields' );
				break;
			case 'registration':
				$is_allowed = doing_filter( 'pno_registration_form_fields' );
				break;
		}
	}

	if ( $site_key && ! empty( $locations ) && is_array( $locations ) && $is_allowed ) {

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
add_filter( 'pno_login_form_fields', 'pno_recaptcha_add_submit_btn_class' );
add_filter( 'pno_registration_form_fields', 'pno_recaptcha_add_submit_btn_class' );

/**
 * Add markup to the forms pages for the recaptcha field.
 */
add_action(
	'wp_footer',
	function() {

		$site_key   = pno_get_option( 'recaptcha_site_key', false );
		$locations  = pno_get_option( 'recaptcha_location', [] );
		$is_allowed = false;
		$form_id    = false;

		foreach ( $locations as $location ) {
			switch ( $location ) {
				case 'login':
					$is_allowed = is_page( pno_get_login_page_id() );
					$form_id    = 'pno-form-login';
					break;
				case 'registration':
					$is_allowed = is_page( pno_get_registration_page_id() );
					$form_id    = 'pno-form-registration';
					break;
			}
		}

		if ( $site_key && ! empty( $locations ) && is_array( $locations ) && $is_allowed && $form_id ) {

			?>
			<script>
				function pnoRecaptchaOnSubmit(token) {
					document.getElementById( "<?php echo esc_js( $form_id ); ?>" ).submit();
				}
			</script>
			<?php

		}

	}
);
