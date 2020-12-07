<?php
/**
 * Etherscan API connector.
 *
 * @since 0.1.0
 *
 * @package Ethpress_Token_Roles
 */

namespace losnappas\Ethpress_Token_Roles;

defined( 'ABSPATH' ) || die;

/**
 * Queries Etherscan.
 *
 * @since 0.1.0
 */
class Etherscan {

	/**
	 * Gets amount of tokens.
	 *
	 * @since 0.1.0
	 *
	 * @param (string) $address Wallet address.
	 * @param (string) $contract_address Contract address.
	 *
	 * @return (string|boolean) Amount of tokens as string, or false on failure.
	 */
	public static function get_token_ownership( $address, $contract_address ) {
		$address          = trim( $address );
		$contract_address = trim( $contract_address );
		$options          = get_option(
			'ethpress_token_roles',
			array(
				'etherscan_api_key' => '',
			)
		);
		$api_key          = $options['etherscan_api_key'];
		$url              = sprintf(
			'https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=%1$s&address=%2$s&tag=latest&apikey=%3$s',
			$contract_address,
			$address,
			$api_key
		);
		$response         = \wp_safe_remote_get( $url );
		$body             = \wp_remote_retrieve_body( $response );
		if ( ! empty( $body ) ) {
			$body   = json_decode( $body );
			$result = $body->result;
			return $result;
		} else {
			return false;
		}
	}
}

