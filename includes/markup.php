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
	$is_allowed = false;

	foreach ( $locations as $location ) {
		switch ( $location ) {
			case 'login':
				$is_allowed = is_page( pno_get_login_page_id() ) && doing_filter( 'pno_login_form_fields' );
				break;
		}
	}

	if ( $site_key && ! empty( $locations ) && is_array( $locations ) && $is_allowed ) {

		if ( isset( $fields['submitv'] ) ) {

			$existing_classes = $fields['submitv']['attributes']['class'];
			$new_classes      = $existing_classes . ' g-recaptcha';

			$fields['submitv']['attributes']['class']         = $new_classes;
			$fields['submitv']['attributes']['data-sitekey']  = esc_attr( $site_key );
			$fields['submitv']['attributes']['data-callback'] = 'pnoRecaptchaOnSubmit';
			$fields['submitv']['attributes']['data-badge']    = 'inline';

		}
	}

	return $fields;

}
add_filter( 'pno_login_form_fields', 'pno_recaptcha_add_submit_btn_class' );

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
			}
		}

		if ( $site_key && ! empty( $locations ) && is_array( $locations ) && $is_allowed && $form_id ) {

			?>
			<script>
				function pnoRecaptchaOnSubmit(token) {
					console.log( document.getElementById( "<?php echo esc_js( $form_id ); ?>" ) )
					document.getElementById( "<?php echo esc_js( $form_id ); ?>" ).submit();
				}
			</script>
			<?php

		}

	}
);
