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
*/

/**
 * Add the fields to the form.
 */
global $pmproan2c_add_middle_name;
$pmproan2c_add_middle_name = false;
function pmproan2c_pmpro_checkout_after_password() {
	global $current_user, $pmproan2c_add_middle_name;

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

	/**
	 * Allow others to make the first middle name required or not.
	 *
	 * @since 0.5
	 *
	 * @param bool `true` for required, `false` if not.
	 */
	$middle_name_required = apply_filters( 'pmproan2c_middle_name_required', false );

	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = sanitize_text_field( $_REQUEST['first_name'] );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = sanitize_text_field( $_SESSION['first_name'] );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( ! empty( $_REQUEST['middle_name'] ) ) {
		$middle_name = sanitize_text_field( $_REQUEST['middle_name'] );
	} elseif ( ! empty( $_SESSION['middle_name'] ) ) {
		$middle_name = sanitize_text_field( $_SESSION['middle_name'] );
	} elseif ( is_user_logged_in() ) {
		$middle_name = get_user_meta( $current_user->ID, 'middle_name', true );
	} else {
		$middle_name = '';
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
		<label for="first_name"><?php _e( 'First Name', 'pmpro' ); ?></label>
		<input id="first_name" name="first_name" type="text" class="input <?php echo $first_name_required ? esc_attr( 'pmpro_required' ) : ''; ?> <?php echo pmpro_getClassForField( 'first_name' ); ?>" size="30" value="<?php echo esc_attr( $first_name ); ?>" />
	</div>
	<?php
	if ( $pmproan2c_add_middle_name ) {
		?>
		<div class="pmpro_checkout-field pmpro_checkout-field-lastname">
			<label for="middle_name"><?php esc_html_e( 'Middle Name', 'pmpro' ); ?></label>
			<input id="middle_name" name="middle_name" type="text" class="input <?php echo $middle_name_required ? esc_attr( 'pmpro_required' ) : ''; ?> <?php echo esc_attr( pmpro_getClassForField( 'middle_name' ) ); ?>" size="30" value="<?php echo esc_attr( $middle_name ); ?>" />
		</div>
		<?php
	}
	?>
	<div class="pmpro_checkout-field pmpro_checkout-field-lastname">
		<label for="last_name"><?php _e( 'Last Name', 'pmpro' ); ?></label>
		<input id="last_name" name="last_name" type="text" class="input <?php echo $last_name_required ? esc_attr( 'pmpro_required' ) : ''; ?> <?php echo pmpro_getClassForField( 'last_name' ); ?>" size="30" value="<?php echo esc_attr( $last_name ); ?>" />
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
 *
 * @return bool `true` if registration check passed, `false` if not.
 */
function pmproan2c_pmpro_registration_checks() {
	global $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_error_fields, $pmproan2c_add_middle_name;

	$pmproan2c_error_fields  = array();
	$pmproan2c_error_message = '';
	$pmproan2c_errors        = false;

	/**
	 * Filter whether first/last names are required fields.
	 *
	 * @see pmproan2c_pmpro_checkout_after_password for filter definitions.
	 */
	$first_name_required  = apply_filters( 'pmproan2c_first_name_required', true );
	$middle_name_required = apply_filters( 'pmproan2c_middle_name_required', false );
	$last_name_required   = apply_filters( 'pmproan2c_last_name_required', true );

	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_SESSION['first_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( isset( $_REQUEST[ 'middle_name'] ) ) {
		$middle_name = trim( sanitize_text_field( $_REQUEST['middle_name'] ) );
	} elseif ( isset( $_SESSION['middle_name'] ) ) {
		$middle_name = trim( sanitize_text_field( $_SESSION['middle_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$middle_name = get_user_meta( $current_user->ID, 'middle_name', true );
	} else {
		$middle_name = '';
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

	if ( ( $first_name && $last_name && ! ( $first_name && $last_name && $pmproan2c_add_middle_name && $middle_name_required && ! $middle_name ) ) || $current_user->ID ) {
		//all good
		return true;
	} else {
		if ( $first_name_required && $last_name_required && $middle_name_required ) {
			$pmproan2c_error_message = __( 'The first, middle, and last name fields are required.', 'pmpro-add-name-to-checkout' );
		} elseif ( $first_name_required && $last_name_required ) {
			$pmproan2c_error_message = __( 'The first and last name fields are required.', 'pmpro-add-name-to-checkout' );	
		} elseif ( $first_name_required ) {
			$pmproan2c_error_message = __( 'The first name field is required.', 'pmpro-add-name-to-checkout' );
		} elseif ( $last_name_required ) {
			$pmproan2c_error_message = __( 'The last name field is required.', 'pmpro-add-name-to-checkout' );
		} elseif ( $middle_name_required ) {
			$pmproan2c_error_message = __( 'The middle name field is required.', 'pmpro-add-name-to-checkout' );
		}

		if ( ! $first_name && $first_name_required ) {
			$pmproan2c_error_fields[] = 'first_name';
		}
		if ( ! $last_name && $last_name_required ) {
			$pmproan2c_error_fields[] = 'last_name';
		}
		if ( ! $middle_name && $middle_name_required ) {
			$pmproan2c_error_fields[] = 'middle_name';
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
 * Update the user after checkout.
 */
function pmproan2c_update_first_and_last_name_after_checkout( $user_id ) {
	global $current_user, $pmproan2c_add_middle_name;

	if ( ! empty( $_REQUEST['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	} elseif ( ! empty( $_SESSION['first_name'] ) ) {
		$first_name = trim( sanitize_text_field( $_SESSION['first_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$first_name = $current_user->first_name;
	} else {
		$first_name = '';
	}

	if ( ! empty( $_REQUEST['middle_name'] ) ) {
		$middle_name = trim( sanitize_text_field( $_REQUEST['middle_name'] ) );
	} elseif ( ! empty( $_SESSION['middle_name'] ) ) {
		$middle_name = trim( sanitize_text_field( $_SESSION['middle_name'] ) );
	} elseif ( is_user_logged_in() ) {
		$middle_name = get_user_meta( $current_user->ID, 'middle_name', true );
	} else {
		$middle_name = '';
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

	if ( $pmproan2c_add_middle_name ) {
		update_user_meta( $user_id, 'middle_name', $middle_name );
	}
	update_user_meta( $user_id, 'first_name', $first_name );
	update_user_meta( $user_id, 'last_name', $last_name );
}
add_action( 'pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout' );

/**
 * Save our added fields in session while the user goes off to PayPal/etc
 */
function pmproan2c_pmpro_paypalexpress_session_vars() {
	global $pmproan2c_add_middle_name;
	$_SESSION['first_name'] = trim( sanitize_text_field( $_REQUEST['first_name'] ) );
	$_SESSION['last_name']  = trim( sanitize_text_field( $_REQUEST['last_name'] ) );
	if ( $pmproan2c_add_middle_name ) {
		$_SESSION['middle_name']  = trim( sanitize_text_field( $_REQUEST['middle_name'] ) );
	}
}
add_action( 'pmpro_paypalexpress_session_vars', 'pmproan2c_pmpro_paypalexpress_session_vars' );
add_action( 'pmpro_before_send_to_twocheckout', 'pmproan2c_pmpro_paypalexpress_session_vars' );

/**
 * Add links to the plugin row meta
 */
function pmproan2c_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-add-name-to-checkout.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro-add-name-to-checkout' ) ) . '">' . __( 'Support', 'pmpro-add-name-to-checkout' ) . '</a>',
		);
		$links     = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmproan2c_plugin_row_meta', 10, 2 );

/**
 * Enqueue scripts on the user profile page.
 */
function pmproan2c_enqueue_scripts() {
		// Get user ID.
		$user_id = isset( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : 0;
		if ( 0 === $user_id && IS_PROFILE_PAGE ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}
		$middle_name = get_user_meta( $user_id, 'middle_name', true );
		wp_enqueue_script(
			'pmproan2c_user_profile',
			plugins_url( '/js/add-middle-name-profile.js', __FILE__ ),
			array( 'jquery' ),
			'0.4.0',
			true
		);
		wp_localize_script(
			'pmproan2c_user_profile',
			'pmproan2c',
			array(
				'middle_name'        => sanitize_text_field( $middle_name ),
				'middle_name_string' => __( 'Middle Name', 'pmpro-add-name-to-checkout' ),
			)
		);
}
add_action( 'admin_print_scripts-user-edit.php', 'pmproan2c_enqueue_scripts' );
add_action( 'admin_print_scripts-profile.php', 'pmproan2c_enqueue_scripts' );

/**
 * Save Middle Name to User Meta.
 *
 * @param int $user_id The user id to update.
 */
function pmproan2c_user_profile( $user_id ) {
	check_admin_referer( 'update-user_' . $user_id );
	$middle_name = sanitize_text_field( trim( filter_input( INPUT_POST, 'middle_name' ) ) );
	update_user_meta( $user_id, 'middle_name', $middle_name );
}
add_action( 'edit_user_profile_update', 'pmproan2c_user_profile' );
add_action( 'personal_options_update', 'pmproan2c_user_profile' );

/**
 * Add the middle name to the display name.
 */
function pmproan2c_filter_user_display_name() {
	global $pmproan2c_add_middle_name, $current_user;

	if ( ! is_user_logged_in() || ! $pmproan2c_add_middle_name ) {
		return;
	}
	$display_name = $current_user->display_name;
	$first_name   = $current_user->first_name;
	$middle_name  = get_user_meta( $current_user->ID, 'middle_name', true );
	$last_name    = $current_user->last_name;
	if ( $middle_name && $first_name && $last_name ) {
		$display_name               = sprintf(
			'%s %s %s',
			$first_name,
			$middle_name,
			$last_name
		);
		// PMPro Compatibility.
		if ( isset( $current_user->user_firstname ) && isset( $current_user->user_lastname ) ) {
			$current_user->user_lastname  = '';
			$current_user->user_firstname = sanitize_text_field( $display_name );
		}
		if ( $current_user->display_name === $display_name ) {
			return;
		}
		// Update the user's display name once or on change of middle name.
		wp_update_user(
			array(
				'ID'           => absint( $current_user->ID ),
				'display_name' => sanitize_text_field( $display_name ),
			)
		);
	}
}
add_action( 'init', 'pmproan2c_filter_user_display_name', 10, 2 );
