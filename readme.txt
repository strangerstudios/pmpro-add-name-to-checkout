=== Paid Memberships Pro - Add Name to Checkout Add On ===
Contributors: strangerstudios
Tags: paid memberships pro, pmpro, first name, fname, last name, lname, names
Requires at least: 3.5
Tested up to: 5.5
Stable tag: 0.5

Adds first and last name fields to the Paid Memberships Pro checkout page.

== Description ==

Adds first and last name fields to the Paid Memberships Pro checkout page.

== Installation ==

= Download, Install and Activate! =
1. Upload the `pmpro-add-name-to-checkout` directory to the `/wp-content/plugins/` directory of your site.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= I found a bug in the plugin. =

Please post it in the issues section of GitHub and we'll fix it as soon as we can. Thanks for helping. https://github.com/strangerstudios/pmpro-add-name-to-checkout/issues

= I need help installing, configuring, or customizing the plugin. =

Please visit our premium support site at http://www.paidmembershipspro.com for more documentation and our support forums.

== Changelog ==
= 0.5 - 2020-08-19 =
* ENHANCEMENT: Enable translation/internationalization.
* BUG FIX: Fixed issue that data wasn't saved when using the PayFast payment gateway.
* BUG FIX: Fixed issue that first name and last name data wasn't being updated in Stripe.

= .4 =
* SECURITY: Properly sanitizing and escaping user input.
* BUG FIX/ENHANCEMENT: Using pmpro_getClassForField() to set class attributes on field tags. (Thanks, Ted Barnett)
* BUG FIX/ENHANCEMENT: Added pmpro_checkout-field/etc classes to wrapping divs for field to match the core PMPro checkout fields and allow for easier styling. (Thanks, Mark Bloomfield)
* BUG FIX/ENHANCEMENT: Using trim() when checking name fields to disallow blank names.
* ENHANCEMENT: Make add-on translation ready. (Thanks, Thomas Sjolshagen)
* ENHANCEMENT: WordPress coding standards.
* FEATURE: Now showing the User Information section with name fields if a user is already logged in at checkout.

= .3.1 =
* BUG: Fixed error when PHP short tags is disabled.
* BUG: Fixed some warnings.

= .3 =
* BUG: Fixed issue where first and last name were being "blanked out" when logged in users checked out again.

= .2 =
* Fixed some warnings. Changed priority on pmpro_after_checkout hook to 5 so name meta fields are setup before most other plugins will try to hook.
* Now also checking if an existing user is logged in with an existing first/last name before potentially overwriting the first/last name on a second checkout.

= .1 =
* Initial commit.
