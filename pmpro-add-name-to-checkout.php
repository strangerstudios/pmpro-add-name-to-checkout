<?php
/*
Plugin Name: PMPro Add Name to Checkout
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-add-name-to-checkout/
Description: Adds first and last name fields to the user account section at checkout for Paid Memberships Pro.
Version: .1
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

//add the fields to the form
function pmproan2c_pmpro_checkout_after_password()
{
	$first_name = $_REQUEST['first_name'];
	$last_name = $_REQUEST['last_name']; 
	?>
	<div>
	<label for="first_name">First Name</label>
	<input id="first_name" name="first_name" type="text" class="input" size="30" value="<?=$first_name?>" />
	</div>
	<div>
	<label for="last_name">Last Name</label>
	<input id="last_name" name="last_name" type="text" class="input" size="30" value="<?=$last_name?>" />
	</div> 
	<?php
}
add_action('pmpro_checkout_after_password', 'pmproan2c_pmpro_checkout_after_password');

//require the fields
function pmproan2c_pmpro_registration_checks()
{
	global $pmpro_msg, $pmpro_msgt, $current_user;
	if(!empty($_REQUEST['first_name']))
		$first_name = $_REQUEST['first_name'];
	elseif(!empty($_SESSION['first_name']))
		$first_name = $_SESSION['first_name'];
	else
		$first_name = "";
	
	if(!empty($_REQUEST['last_name']))
		$last_name = $_REQUEST['last_name'];
	elseif(!empty($_SESSION['last_name']))
		$last_name = $_SESSION['last_name'];
	else
		$last_name = "";

	if($first_name && $last_name || $current_user->ID)
	{
		//all good
		return true;
	}
	else
	{
		$pmpro_msg = "The first and last name fields are required.";
		$pmpro_msgt = "pmpro_error";
		return false;
	}
}
add_filter("pmpro_registration_checks", "pmproan2c_pmpro_registration_checks");

//update the user after checkout
function pmproan2c_update_first_and_last_name_after_checkout($user_id)
{
	if(!empty($_REQUEST['first_name']))
		$first_name = $_REQUEST['first_name'];
	elseif(!empty($_SESSION['first_name']))
		$first_name = $_SESSION['first_name'];
	else
		$first_name = "";
	
	if(!empty($_REQUEST['last_name']))
		$last_name = $_REQUEST['last_name'];
	elseif(!empty($_SESSION['last_name']))
		$last_name = $_SESSION['last_name'];
	else
		$last_name = "";

	update_user_meta($user_id, "first_name", $first_name);
	update_user_meta($user_id, "last_name", $last_name);
}
add_action('pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout');

function pmproan2c_pmpro_paypalexpress_session_vars()
{
	//save our added fields in session while the user goes off to PayPal
	$_SESSION['first_name'] = $_REQUEST['first_name'];
	$_SESSION['last_name'] = $_REQUEST['last_name'];
}
add_action("pmpro_paypalexpress_session_vars", "pmproan2c_pmpro_paypalexpress_session_vars");
