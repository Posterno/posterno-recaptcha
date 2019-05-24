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

function pno_recaptcha_verify_submission() {

	throw new Exception( 'hehehe', 'recaptcha-validation-error' );

}
add_action( 'pno_before_user_login', 'pno_recaptcha_verify_submission' );
