=== Paid Memberships Pro - Add Name to Checkout Add On ===
Contributors: strangerstudios
Tags: paid memberships pro, pmpro, first name, fname, last name, lname, names
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: .3

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
= .3 =
* BUG: Fixed issue where first and last name were being "blanked out" when logged in users checked out again.

= .2 =
* Fixed some warnings. Changed priority on pmpro_after_checkout hook to 5 so name meta fields are setup before most other plugins will try to hook.
* Now also checking if an existing user is logged in with an existing first/last name before potentially overwriting the first/last name on a second checkout.

= .1 =
* Initial commit.