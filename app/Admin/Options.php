<?php
/**
 * Displays options on EthPress' options page.
 *
 * @since 0.1.0
 *
 * @package Ethpress_Token_Roles
 */

namespace losnappas\Ethpress_Token_Roles\Admin;

defined( 'ABSPATH' ) || die;

/**
 * Static.
 *
 * @since 0.1.0
 */
class Options {

	/**
	 * Adds options page. Attached to hook.
	 *
	 * @since 0.1.0
	 */
	public static function admin_menu() {
		$page = esc_html__( 'EthPress Tokens', 'ethpress_token_roles' );
		add_options_page(
			$page,
			$page,
			'manage_options',
			'ethpress_token_roles',
			array( __CLASS__, 'create_page' )
		);
	}

	/**
	 * Creates options page.
	 *
	 * @since 0.1.0
	 */
	public static function create_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'EthPress Tokens', 'ethpress_token_roles' ); ?></h1>
			<form action="options.php" method="POST">
			<?php
			settings_fields( 'ethpress_token_roles' );
			do_settings_sections( 'ethpress_token_roles' );
			submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Adds settings for api_url to options page. admin_init hooked.
	 *
	 * @since 0.1.0
	 */
	public static function admin_init() {
		register_setting(
			'ethpress_token_roles',
			'ethpress_token_roles',
			array( __CLASS__, 'options_validate' )
		);

		add_settings_section(
			'ethpress_token_roles_main',
			esc_html__( 'Limit EthPress Logins Based on Token Ownership', 'ethpress_token_roles' ),
			array( __CLASS__, 'settings_section' ),
			'ethpress_token_roles'
		);
		add_settings_field(
			'ethpress_token_roles_options',
			esc_html__( 'Settings', 'ethpress_token_roles' ),
			array( __CLASS__, 'settings_field' ),
			'ethpress_token_roles',
			'ethpress_token_roles_main'
		);
	}

	/**
	 * Outputs section title.
	 *
	 * @since 0.1.0
	 */
	public static function settings_section() {
	}

	/**
	 * Outputs options.
	 *
	 * @since 0.1.0
	 */
	public static function settings_field() {
		$options = get_option(
			'ethpress_token_roles',
			array(
				'etherscan_api_key'  => '',
				'contract_addresses' => array(),
				'ranges' =>array(),
				'nftcontracts' =>array(),
				'nftroles' =>array(),
			)
		);
		?>
		<h4><?php esc_html_e( 'API key from https://etherscan.io', 'ethpress_token_roles' ); ?></h4>
		<input id="ethpress_token_roles_etherscan_api_key" name="ethpress_token_roles[etherscan_api_key]" placeholder="<?php esc_attr_e( 'API key', 'ethpress_token_roles' ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( $options['etherscan_api_key'] ); ?>">
		<p class="description"><?php esc_html_e( 'Without a key, you will get rate limited, which is not good.', 'ethpress_token_roles' ); ?></p>

		<h4><?php esc_html_e( 'Roles to assign', 'ethpress_token_roles' ); ?></h4>
		<div id="ethpress_token_roles_contracts_container" style="display:flex;flex-flow:column;">

		<?php
		if ( 0 < count( $options['contract_addresses'] ) ) {
			$i = 0;
			foreach ( $options['contract_addresses'] as $contract => $roles ) {
				?>
				<input style="margin: 0.2em 0;" name="ethpress_token_roles[contract_address_<?php echo esc_attr( $i ); ?>]" placeholder="0x123...:role,role2,role3" type="text" class="regular-text" value="<?php echo esc_attr( $contract ) . ':' . esc_attr( implode( ',', $roles ) ); ?>">
				<?php
				++$i;
			}
		} else {
			?>
			<input style="margin: 0.2em 0;" name="ethpress_token_roles[contract_address_0]" placeholder="0x123...:role1,role2,role3" type="text" class="regular-text" value="">
			<?php
		}
		?>
		</div>
		<p><button type="button" class="button" id="ethpress_token_roles_add_new"><?php esc_html_e( 'Add New', 'ethpress_token_roles' ); ?></button></p>
		<p class="description"><?php esc_html_e( 'Format: 0xContract_Address:role1,role2,role3... Contract address, colon, roles', 'ethpress_token_roles' ); ?></p>
		<p class="description"><?php esc_html_e( 'You may assign many roles, separated with a comma. Blank lines are removed on save.', 'ethpress_token_roles' ); ?></p>
		
		<h4><?php esc_html_e( 'Roles limits ', 'ethpress_token_roles' ); ?></h4>
		<input id="ethpress_token_roles_ranges" name="ethpress_token_roles[ranges]" placeholder="<?php esc_attr_e( 'ranges', 'ethpress_token_roles' ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( implode(';',($options['ranges'] ))); ?>">
		<p class="description"><?php esc_html_e( 'Put the role limits in order, separated by ";".The number of roles must be equal with the number of limits', 'ethpress_token_roles' ); ?></p>
		<h4><?php esc_html_e( 'Nft roles ', 'ethpress_token_roles' ); ?></h4>
		<input id="ethpress_token_roles_nftcontracts" name="ethpress_token_roles[nftcontracts]" placeholder="<?php esc_attr_e( 'nftcontracts', 'ethpress_token_roles' ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( implode(';',($options['nftcontracts'] ))); ?>">
		<p class="description"><?php esc_html_e( 'Put the nft contracts in order, separated by ";"', 'ethpress_token_roles' ); ?></p>
		<input id="ethpress_token_roles_nftroles" name="ethpress_token_roles[nftroles]" placeholder="<?php esc_attr_e( 'nftroles', 'ethpress_token_roles' ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( implode(';',($options['nftroles'] ))); ?>">
		<p class="description"><?php esc_html_e( 'Put the roles you want to assign for each NFT in order, separated by ";"', 'ethpress_token_roles' ); ?></p>
	
		<?php
	}

	/**
	 * Validates input for api url option.
	 *
	 * @param array $input New options input.
	 * @return array New options.
	 *
	 * @since 0.1.0
	 */
	public static function options_validate( $input ) {
		$opts = get_option( 'ethpress_token_roles', array() );
		$vals = array();
		foreach ( $input as $key => $value ) {
			if ( 'etherscan_api_key' === $key ) {
				$opts['etherscan_api_key'] = trim( sanitize_text_field( $value ) );
			} else {
				// It's a contract address:roles setting.
				$pos = strpos( $value, ':' );
				if ( ! $pos ) {
					continue;
				}
				$contract          = trim( substr( $value, 0, $pos ) );
				$roles             = substr( $value, $pos + 1 );
				$roles             = explode( ',', $roles );
				$vals[ $contract ] = array_unique( array_map( 'trim', array_map( 'sanitize_text_field', empty( $vals[ $contract ] ) ? $roles : array_merge( $vals[ $contract ], $roles ) ) ) );
			}
		}
		$opts['contract_addresses'] = $vals;
			if ( 'ranges' === $key ) {
				$ranges = trim( sanitize_text_field( $value ) );
				$ranges = explode( ';', $ranges );
				$opts['ranges'] = $ranges;
			}
			if ( 'nftcontracts' === $key ) {
				$nftcontracts = trim( sanitize_text_field( $value ) );
				$nftcontracts = explode( ';', $nftcontracts );
				$opts['nftcontracts'] = $nftcontracts;
			}
			if ( 'nftroles' === $key ) {
				$nftroles = trim( sanitize_text_field( $value ) );
				$nftroles = explode( ';', $nftroles );
				$opts['nftroles'] = $nftroles;
			}
		return $opts;
	}


	/**
	 * Adds settings link. Hooked to filter.
	 *
	 * @since 0.1.0
	 *
	 * @param array $links Existing links.
	 */
	public static function plugin_action_links( $links ) {
		$url           = esc_attr(
			esc_url(
				add_query_arg(
					'page',
					'ethpress_token_roles',
					get_admin_url() . 'options-general.php'
				)
			)
		);
		$label         = esc_html__( 'Settings', 'ethpress_token_roles' );
		$settings_link = "<a href='$url'>$label</a>";

		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Admin scripts.
	 *
	 * @since 2.0.0
	 * @param string $page Page.
	 */
	public static function admin_enqueue_scripts( $page ) {
		if ( 'settings_page_ethpress_token_roles' === $page ) {
			wp_enqueue_script(
				'ethpress_token_roles_admin',
				plugin_dir_url( ETHPRESS_TOKEN_ROLES_FILE ) . '/public/admin/main.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}
}
