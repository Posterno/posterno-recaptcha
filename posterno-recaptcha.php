<?php
/**
 * Plugin Name:     Posterno reCAPTCHA
 * Plugin URI:      https://posterno.com/extensions/recaptcha
 * Description:     Addon for Posterno, stop spam registrations on your website for free.
 * Author:          Posterno
 * Author URI:      https://posterno.com
 * Text Domain:     posterno-recaptcha
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * Posterno Recaptcha is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Posterno Recaptcha is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PosternoRecaptcha. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package posterno-recaptcha
 * @author #
 */

namespace PosternoRecaptcha;

defined( 'ABSPATH' ) || exit;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

add_action(
	'plugins_loaded',
	function() {

		$requirements_check = new \PosternoRequirements\Check(
			array(
				'title' => 'Posterno reCAPTCHA',
				'file'  => __FILE__,
				'pno'   => '0.9.0',
			)
		);

		if ( $requirements_check->passes() ) {
			$addon = Plugin::instance( __FILE__ );
			add_action( 'plugins_loaded', [ $addon, 'textdomain' ], 11 );
		}
		unset( $requirements_check );

	}
);


