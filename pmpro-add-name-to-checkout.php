<?php
/*
Plugin Name: Paid Memberships Pro - Add Name to Checkout
Plugin URI: https://www.paidmembershipspro.com/add-ons/add-first-last-name-to-checkout/
Description: Adds first and last name fields to the user account section at checkout for Paid Memberships Pro.
Version: 0.7
Text Domain: pmpro-add-name-to-checkout
Domain Path: /languages
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com/
*/

/*
	Load plugin textdomain.
*/
function pmproan2c_load_textdomain() {
  load_plugin_textdomain( 'pmpro-add-name-to-checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'pmproan2c_load_textdomain' );

/**
 * Add the fields to the form.
 */
function pmproan2c_pmpro_checkout_after_password() {
	global $current_user;

	/**
	 * Allow others to make the first name required or not.
	 *
	 * @since 0.5
	 *
	 * @param bool `true` for required, `false` if not.
	 */
	$first_name_required = apply_filters( 'pmproan2c_first_name_required', true );

	/**
	 * Allow others to make the last name required or not.
	 *
	 * @since 0.5
	 *
	 * @param bool `true` for required, `false` if not.
	 */
	$last_name_required = apply_filters( 'pmproan2c_last_name_required', true );

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
	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-firstname', 'pmpro_form_field-firstname' ) ); ?><?php echo $first_name_required ? esc_attr( ' pmpro_form_field-required' ) : ''; ?>">
		<label for="first_name" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>"><?php esc_html_e( 'First Name', 'pmpro-add-name-to-checkout' ); ?></label>
		<input id="first_name" name="first_name" type="text" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text' ) ); ?><?php echo $first_name_required ? esc_attr( ' pmpro_form_input-required' ) : ''; ?>" value="<?php echo esc_attr( $first_name ); ?>" />
	</div>
	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-lastname', 'pmpro_form_field-lastname' ) ); ?><?php echo $last_name_required ? esc_attr( ' pmpro_form_field-required' ) : ''; ?>">
		<label for="last_name" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>"><?php esc_html_e( 'Last Name', 'pmpro-add-name-to-checkout' ); ?></label>
		<input id="last_name" name="last_name" type="text" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text' ) ); ?><?php echo $last_name_required ? esc_attr( ' pmpro_form_input-required' ) : ''; ?>" value="<?php echo esc_attr( $last_name ); ?>" />
	</div>
	<?php
}
add_action( 'pmpro_checkout_after_password', 'pmproan2c_pmpro_checkout_after_password' );

/**
 * If the user is logged in, we still want to show our version of the account info section.
 *
 * Note: it would be nice in v3.1+ for this to be included in the same logged-in Account Info box above. We will consider a hook so this can display inside the box.
 */
function pmproan2c_account_info_when_logged_in() {
	global $current_user;

	if ( ! is_user_logged_in() ) {
		return;
	}
	?>
	<fieldset id="pmpro_user_fields" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fieldset', 'pmpro_user_fields' ) ); ?>">
		<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card' ) ); ?>">
			<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">
				<legend class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_legend' ) ); ?>">
					<h2 class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_heading pmpro_font-large' ) ); ?>"><?php esc_html_e( 'Your Name', 'pmpro-add-name-to-checkout' ); ?></h2>
				</legend>
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fields' ) ); ?>">
					<?php pmproan2c_pmpro_checkout_after_password(); ?>
				</div> <!-- end pmpro_form_fields -->
			</div> <!-- end pmpro_card_content -->
		</div> <!-- end pmpro_card -->
	</fieldset> <!-- end pmpro_user_fields -->
	<?php
}
add_action( 'pmpro_checkout_after_user_fields', 'pmproan2c_account_info_when_logged_in' );

/**
 * Require the fields.
 *
 * @return bool `true` if registration check passed, `false` if not.
 */
function pmproan2c_pmpro_registration_checks() {
	global $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_error_fields;

	$pmproan2c_error_fields  = array();
	$pmproan2c_error_message = '';
	$pmproan2c_errors        = false;

	/**
	 * Filter whether first/last names are required fields.
	 *
	 * @see pmproan2c_pmpro_checkout_after_password for filter definitions.
	 */
	$first_name_required = apply_filters( 'pmproan2c_first_name_required', true );
	$last_name_required  = apply_filters( 'pmproan2c_last_name_required', true );

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
		if ( $first_name_required && $last_name_required ) {
			$pmproan2c_error_message = __( 'The first and last name fields are required.', 'pmpro-add-name-to-checkout' );
		} elseif ( $first_name_required ) {
			$pmproan2c_error_message = __( 'The first name field is required.', 'pmpro-add-name-to-checkout' );
		} elseif ( $last_name_required ) {
			$pmproan2c_error_message = __( 'The last name field is required.', 'pmpro-add-name-to-checkout' );
		}

		if ( ! $first_name && $first_name_required ) {
			$pmproan2c_error_fields[] = 'first_name';
		}
		if ( ! $last_name && $last_name_required ) {
			$pmproan2c_error_fields[] = 'last_name';
		}
	}
	if ( empty( $pmproan2c_error_fields ) ) {
		return true;
	} else {
		$pmproan2c_errors = true;
	}
	// Populate globals.
	if ( $pmproan2c_errors ) {
		$pmpro_msgt = 'pmpro_error';
		$pmpro_msg  = $pmproan2c_error_message;
	}
	$pmpro_error_fields = array_merge( $pmpro_error_fields, $pmproan2c_error_fields );
	return false;
}
add_filter( 'pmpro_registration_checks', 'pmproan2c_pmpro_registration_checks' );

/**
 * Hook into Paid Memberships Pro default new user array for registration and pull information from $_SESSION if it's available.
 *
 * @since 0.6.1
 * 
 * @param $array $new_user_array The array with user information to create a new WordPress user.
 * @return array $new_user_array The array with user information to create a new WordPress user.
 */
function pmproan2c_pmpro_checkout_new_user_array( $new_user_array ) {
	global $current_user;

	if ( ! $current_user->ID ) {
		//reload the user fields
		if ( ! empty( $_SESSION['first_name'] ) ) {
			$new_user_array['first_name'] = sanitize_text_field( $_SESSION['first_name'] );
		}
		if ( ! empty( $_SESSION['last_name'] ) ) {
			$new_user_array['last_name'] = sanitize_text_field( $_SESSION['last_name'] );
		}
	}

	return $new_user_array;
}
add_filter( 'pmpro_checkout_new_user_array', 'pmproan2c_pmpro_checkout_new_user_array' );

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
	update_user_meta( $user_id, 'display_name', $first_name . ' ' . $last_name );
	update_post_meta( $user_id, 'billing_name', $first_name . ' ' . $last_name );

	// unset the user fields in session
	unset( $_SESSION['first_name'] );
	unset( $_SESSION['last_name'] );
}
add_action( 'pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout' );

/**
 * Update the name on the order.
 */
function pmproan2c_pmpro_checkout_order( $order ) {
	if ( empty( $order->FirstName ) ) {
		$order->FirstName = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	}

	if ( empty( $order->LastName ) ) {
		$order->LastName = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	}

	// Free orders won't have a billing object.
	if ( empty( $order->billing ) ) {
		$order->billing = new stdClass();
		$order->billing->name = '';
	}

	// Trimming because something in testing was adding spaces.
	if ( empty( trim( $order->billing->name ) ) ) {
		$order->billing->name = trim( sanitize_text_field( $_REQUEST['first_name'] ) ) . ' ' . trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	}

	return $order;
}
add_filter( 'pmpro_checkout_order', 'pmproan2c_pmpro_checkout_order' );
add_filter( 'pmpro_checkout_order_free', 'pmproan2c_pmpro_checkout_order' );

/**
 * Save our added fields in session while the user goes off to PayPal/etc
 */
function pmproan2c_pmpro_paypalexpress_session_vars() {
	$_SESSION['first_name'] = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	$_SESSION['last_name']  = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
}
add_action( 'pmpro_paypalexpress_session_vars', 'pmproan2c_pmpro_paypalexpress_session_vars' );
add_action( 'pmpro_before_send_to_twocheckout', 'pmproan2c_pmpro_paypalexpress_session_vars' );
add_action( 'pmpro_before_send_to_payfast', 'pmproan2c_pmpro_paypalexpress_session_vars' );

/**
 * Add links to the plugin row meta
 */
function pmproan2c_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-add-name-to-checkout.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/add-first-last-name-to-checkout/' ) . '" title="' . esc_attr( __( 'View Documentation', 'pmpro-add-name-to-checkout' ) ) . '">' . __( 'Docs', 'pmpro-add-name-to-checkout' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro-add-name-to-checkout' ) ) . '">' . __( 'Support', 'pmpro-add-name-to-checkout' ) . '</a>',
		);
		$links     = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmproan2c_plugin_row_meta', 10, 2 );

/**
 * Adds support for Payfast
 */

function pmproan2c_before_send_to_payfast( $user_id, $morder ){

	if ( empty( $morder->FirstName ) ) {
		$morder->FirstName = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	}

	if ( empty( $morder->LastName ) ) {
		$morder->LastName = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	}

	$morder->saveOrder();

}
add_action( 'pmpro_before_send_to_payfast', 'pmproan2c_before_send_to_payfast', 10, 2 );
