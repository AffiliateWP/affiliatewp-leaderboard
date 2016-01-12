=== AffiliateWP - Leaderboard ===
Contributors: sumobi, mordauk
Tags: AffiliateWP, affiliate, Pippin Williamson, leaderboard, Andrew Munro, mordauk, pippinsplugins, sumobi, ecommerce, e-commerce, e commerce, selling, membership, referrals, marketing
Requires at least: 3.3
Tested up to: 4.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display an affiliate leaderboard on your website

== Description ==

> This plugin requires [AffiliateWP](http://affiliatewp.com/ "AffiliateWP") in order to function.

This plugin allows you to show a leaderboard of your top affiliates. Show any number of affiliates and include their referrals, earnings and visits. You can also order them by referrals, earnings, or visits. Showing an affiliate leaderboard on your website is a great way to encourage affiliates to make you more sales.

**Features:**

1. An [affiliate_leaderboard] shortcode for showing an affiliate leaderboard on any WordPress page
2. An Affiliate Leaderboard widget for showing an affiliate leaderboard in your theme's sidebar
3. A PHP function for developers for showing an affiliate leaderboard anywhere on your site.

**Shortcode Usage**

The defaut shortcode with no additional parameters will show 10 affiliates, ordered by referrals.

    [affiliate_leaderboard]

The `number` parameter can be used to control how many affiliates are shown in the leaderboard:

    [affiliate_leaderboard number="5"]

The `referrals` parameter can be used to show an affiliate's referrals:

    [affiliate_leaderboard referrals="yes"]

The `earnings` parameter can be used to show an affiliate's earnings:

    [affiliate_leaderboard earnings="yes"]

The `visits` parameter can be used to show an affiliate's visits:

    [affiliate_leaderboard visits="yes"]

You can also list all 3 (referrals, earnings, visits) like this:

    [affiliate_leaderboard referrals="yes" earnings="yes" visits="yes"]

The `orderby` parameter can be used to order the leaderboard by either `referrals`, `earnings` or `visits`:

    [affiliate_leaderboard orderby="referrals"]

    [affiliate_leaderboard orderby="earnings"]

    [affiliate_leaderboard orderby="visits"]

**What is AffiliateWP?**

[AffiliateWP](http://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

== Screenshots ==

1. The affiliate leaderboard can be a simple list
1. The affiliate leaderboard can show referrals, earnings and visits
1. The affiliate leaderboard widget

== Upgrade Notice ==

== Changelog ==

= 1.0.2 =
* Fix: Set up missing text strings for translation

= 1.0.1 =
* Fix: changed default orderby from "earnings" to "referrals" when no shortcode parameters are used.

= 1.0 =
* Initial release
