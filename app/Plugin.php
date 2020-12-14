<?php
/**
 * Token ownership tests for EthPress
 *
 * @since 0.1.0
 *
 * @package Ethpress_Token_Roles
 */

namespace losnappas\Ethpress_Token_Roles;

defined( 'ABSPATH' ) || die;

use losnappas\Ethpress_Token_Roles\Etherscan;
// Note that this file needs to be required by the time we're here.
// Composer cannot find it, since different vendor folders.
// Well, it always is.
use losnappas\Ethpress\Address;

/**
 * Runs the show.
 *
 * Checks how many tokens a user has, then, if they have none, prevents login.
 *
 * @since 0.1.0
 */
class Plugin {

	/**
	 * Attaches plugin's hooks.
	 *
	 * @since 0.1.0
	 */
	 
	 
	 
	 
	public static function attach_hooks() {
		add_action(
			'ethpress_login',
			array( __CLASS__, 'ethpress_login' )
		);

		if ( is_admin() ) {
			self::attach_admin_hooks();
		}
	}

	/**
	 * Attaches admin hooks.
	 *
	 * @since 0.1.0
	 */
	public static function attach_admin_hooks() {
		add_action(
			'admin_menu',
			array( ETHPRESS_TOKEN_ROLES_NS . '\Admin\Options', 'admin_menu' )
		);
		add_action(
			'admin_init',
			array( ETHPRESS_TOKEN_ROLES_NS . '\Admin\Options', 'admin_init' )
		);
		add_action(
			'admin_enqueue_scripts',
			array( ETHPRESS_TOKEN_ROLES_NS . '\Admin\Options', 'admin_enqueue_scripts' )
		);
		$plugin = plugin_basename( ETHPRESS_TOKEN_ROLES_FILE );
		add_filter( "plugin_action_links_$plugin", array( ETHPRESS_TOKEN_ROLES_NS . '\Admin\Options', 'plugin_action_links' ) );
	}

	/**
	 * Attached to ethpress_login hook.
	 *
	 * @since 0.1.0
	 *
	 * @param (WP_User|WP_Error) $user User or error, from the hook.
	 */
	public static function ethpress_login( $user ) {
		if ( is_wp_error( $user ) ) {
			// Failed login. Bail.
			return;
		}

		$address = Address::find_by_user( $user->ID );
		if ( is_wp_error( $address ) ) {
			return;
		}

		$options            = get_option(
			'ethpress_token_roles',
			array()
		);
		$contract_addresses = $options['contract_addresses'];
        
		// No token specified in settings. Bail.
		if ( empty( $contract_addresses ) ) {
			return;
		}
		$rejected_roles = array();
		$approved_roles = array();




		foreach ( $contract_addresses as $contract => $roles) {
			$tokens = self::check_tokens( $address, $contract );
            $tokens = $tokens / 10000;
			if ( is_wp_error( $tokens ) ) {
				continue;
			}
			

            $limits = $options['ranges'];
	    $i = count($limits);
	    $ii = 0;

			while($ii < $i){
				

				
                if($tokens >= $limits[$ii])
			    {

					$user->add_role($roles[$ii]);
		    
			    }
			    else{
                //
		          $user->remove_cap($roles[$ii]);
		          $user->remove_role($roles[$ii]);

			    }
			$ii++;
		}
		}
	 $nftcontracts = $options['nfts']['contracts'];
	 $nftroles = $options['nfts']['roles'];
	 $ic = count($nftcontracts);
	 $icc = 0;
         while($ic > $icc){
		 $holdsnft = self::check_tokens( $address, $nftcontracts[$icc] );
		 if($holdsnft >= 1){
		 $user->add_role($nftroles[$icc]); 
		 }
		 else{			 
		 $user->remove_cap($nftroles[$icc]);
		 $user->remove_role($nftroles[$icc]);		 
		 }
	 $icc++;	 
	 }
	
	
	}
	

	

	/**
	 * Gets tokens for address in contract.
	 *
	 * @since 1.0.0
	 *
	 * @param object $address EthPress Address object.
	 * @param string $contract_address The address to look at.
	 * @return (string|WP_Error) Amount of tokens or WP_Error.
	 */
	public static function check_tokens( $address, $contract_address ) {
		$coinbase      = $address->get_coinbase();
		$token_balance = Etherscan::get_token_ownership( $coinbase, $contract_address );
		if ( ! is_string( $token_balance ) || ! is_numeric( $token_balance ) ) {
			// Most likely "RATE LIMITED" by Etherscan.
			// To fix, use an API key!
			return new \WP_Error( 'ethpress_token_roles', esc_html__( 'Unexpected error, try again soon.', 'ethpress_token_roles' ) );
		}
		return $token_balance;
	}
}
