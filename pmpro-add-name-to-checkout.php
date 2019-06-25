<?php
/*
Plugin Name: Paid Memberships Pro - Add Name to Checkout Add On
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-add-name-to-checkout/
Description: Adds first and last name fields to the user account section at checkout for Paid Memberships Pro.
Version: .4
Text Domain: pmpro-add-name-to-checkout
Domain Path: /languages
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
Text Domain: pmpro-add-name-to-checkout
Domain Path: /languages
*/

/**
 * Load text domain
 * pmproan2c_load_plugin_text_domain
 */
function pmproan2c_load_plugin_text_domain() {
	load_plugin_textdomain( 'pmpro-add-name-to-checkout', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'pmproan2c_load_plugin_text_domain' ); 

/**
 * Add the fields to the form.
 */
function pmproan2c_pmpro_checkout_after_password() {
	global $current_user;
	
	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = sanitize_text_field( $_REQUEST['first_name'] );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = sanitize_text_field( $_SESSION['first_name'] );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( ! empty( $_REQUEST['last_name'] ) ) {
		$last_name = sanitize_text_field( $_REQUEST['last_name'] );
	} elseif ( ! empty( $_SESSION['last_name'] ) ) {
		$last_name = sanitize_text_field( $_SESSION['last_name'] );
	} elseif ( is_user_logged_in() ) {
		$last_name = $current_user->last_name;
	} else {
		$last_name = '';
	}
	?>
	<div class="pmpro_checkout-field pmpro_checkout-field-firstname">
		<label for="first_name"><?php _e( 'First Name', 'paid-memberships-pro' ); ?></label>
		<input id="first_name" name="first_name" type="text" class="input pmpro_required <?php echo pmpro_getClassForField( 'first_name' ); ?>" size="30" value="<?php echo esc_attr( $first_name ); ?>" />
	</div>
	<div class="pmpro_checkout-field pmpro_checkout-field-lastname">
		<label for="last_name"><?php _e( 'Last Name', 'paid-memberships-pro' ); ?></label>
		<input id="last_name" name="last_name" type="text" class="input pmpro_required <?php echo pmpro_getClassForField( 'last_name' ); ?>" size="30" value="<?php echo esc_attr( $last_name ); ?>" />
	</div> 
	<?php
}
add_action( 'pmpro_checkout_after_password', 'pmproan2c_pmpro_checkout_after_password' );

/**
 * If the user is logged in, we still want to show our version of the account info section.
 */
function pmproan2c_account_info_when_logged_in() {
	global $current_user;
	
	if ( ! is_user_logged_in() ) {
		return;
	}	
	?>
	<hr />
	<div id="pmpro_user_fields" class="pmpro_checkout">
		<h3>
			<span class="pmpro_checkout-h3-name"><?php _e('Account Information', 'pmpro-add-name-to-checkout' );?></span>			
		</h3>
		<div class="pmpro_checkout-fields">
			<?php pmproan2c_pmpro_checkout_after_password(); ?>
		</div>
	</div>
	<?php
}
add_action( 'pmpro_checkout_after_pricing_fields', 'pmproan2c_account_info_when_logged_in' );

/**
 * Require the fields.
 */
function pmproan2c_pmpro_registration_checks() {
	global $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_error_fields;
	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_SESSION['first_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( ! empty( $_REQUEST['last_name'] ) ) {
		$last_name = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	} elseif ( ! empty( $_SESSION['last_name'] ) ) {
		$last_name = trim( sanitize_text_field( $_SESSION['last_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$last_name = $current_user->last_name;
	} else {
		$last_name = '';
	}

	if ( $first_name && $last_name || $current_user->ID ) {
		//all good
		return true;
	} else {
		$pmpro_msg  = __( 'The first and last name fields are required.', 'pmpro-add-name-to-checkout' );
		$pmpro_msgt = 'pmpro_error';
		if ( ! $first_name ) {
			$pmpro_error_fields[] = 'first_name';
		}
		if ( ! $last_name ) {
			$pmpro_error_fields[] = 'last_name';
		}
		return false;
	}
}
add_filter( 'pmpro_registration_checks', 'pmproan2c_pmpro_registration_checks' );

/**
 * Update the user after checkout.
 */
function pmproan2c_update_first_and_last_name_after_checkout( $user_id ) {
	global $current_user;

	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_SESSION['first_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( ! empty( $_REQUEST['last_name'] ) ) {
		$last_name = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	} elseif ( ! empty( $_SESSION['last_name'] ) ) {
		$last_name = trim( sanitize_text_field( $_SESSION['last_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$last_name = $current_user->last_name;
	} else {
		$last_name = '';
	}

	update_user_meta( $user_id, 'first_name', $first_name );
	update_user_meta( $user_id, 'last_name', $last_name );
}
add_action( 'pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout' );

/**
 * Save our added fields in session while the user goes off to PayPal/etc
 */
function pmproan2c_pmpro_paypalexpress_session_vars() {
	$_SESSION['first_name'] = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	$_SESSION['last_name']  = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
}
add_action( 'pmpro_paypalexpress_session_vars', 'pmproan2c_pmpro_paypalexpress_session_vars' );
add_action( 'pmpro_before_send_to_twocheckout', 'pmproan2c_pmpro_paypalexpress_session_vars' );

/**
 * Add links to the plugin row meta
 */
function pmproan2c_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-add-name-to-checkout.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'paid-memberships-pro' ) ) . '">' . __( 'Support', 'paid-memberships-pro' ) . '</a>',
		);
		$links     = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmproan2c_plugin_row_meta', 10, 2 );
