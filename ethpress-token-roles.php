<?php
/**
 * Plugin Name:     EthPress Token Roles
 * Plugin URI:      https://gitlab.com/losnappas/ethpress-tokens
 * Description:     Token limit control for EthPress.
 * Author:          Lynn (lynn.mvp at tutanota dot com)
 * Author URI:      https://gitlab.com/losnappas/
 * Text Domain:     ethpress_token_roles
 * Domain Path:     /languages
 * Version:         2.0.0
 *
 * @package         Ethpress_Token_Roles
 */

namespace losnappas\Ethpress_Token_Roles;

defined( 'ABSPATH' ) || die;

require_once 'vendor/autoload.php';

define( 'ETHPRESS_TOKEN_ROLES_FILE', __FILE__ );
define( 'ETHPRESS_TOKEN_ROLES_NS', __NAMESPACE__ );
define( 'ETHPRESS_TOKEN_ROLES_PHP_MIN_VER', '5.4.0' );
define( 'ETHPRESS_TOKEN_ROLES_WP_MIN_VER', '4.6.0' );

if ( version_compare( \get_bloginfo( 'version' ), ETHPRESS_TOKEN_ROLES_WP_MIN_VER, '<' ) || version_compare( PHP_VERSION, ETHPRESS_TOKEN_ROLES_PHP_MIN_VER, '<' ) ) {
	/**
	 * Displays notification.
	 */
	function ethpress_token_roles_compatability_warning() {
		echo '<div class="error"><p>' . esc_html(
			sprintf(
				/* translators: version numbers. */
				__( '“%1$s” requires PHP %2$s (or newer) and WordPress %3$s (or newer) to function properly. Your site is using PHP %4$s and WordPress %5$s. Please upgrade. The plugin has been automatically deactivated.', 'ethpress_token_roles' ),
				'EthPress Token Roles',
				ETHPRESS_TOKEN_ROLES_PHP_MIN_VER,
				ETHPRESS_TOKEN_ROLES_WP_MIN_VER,
				PHP_VERSION,
				$GLOBALS['wp_version']
			)
		) . '</p></div>';
		// phpcs:ignore -- no nonces here.
		if ( isset( $_GET['activate'] ) ) {
			// phpcs:ignore -- no nonces here.
			unset( $_GET['activate'] );
		}
	}
	add_action( 'admin_notices', __NAMESPACE__ . '\ethpress_token_roles_compatability_warning' );

	/**
	 * Deactivates.
	 */
	function ethpress_token_roles_deactivate_self() {
		deactivate_plugins( plugin_basename( ETHPRESS_TOKEN_ROLES_FILE ) );
	}
	add_action( 'admin_init', __NAMESPACE__ . '\ethpress_token_roles_deactivate_self' );

	return;
} else {
	Plugin::attach_hooks();
}

