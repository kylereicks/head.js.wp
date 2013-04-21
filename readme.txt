=== Head.WP ===
Contributors: kylereicks
Donate link: http://shakopee.dollarsforscholars.org/
Tags: script loading, asynchronous, javascript, async, headJS, head.js, enqueue, wp_enqueue_script
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to load all footer scripts with [head.js](https://github.com/headjs/headjs).

== Description ==

Head.js is a script to asynchronously load and optionally asynchronously execute javascript assets.

This plugin loads the scripts enqueued in footer with the head.js loader. All of the footer scripts are loaded asynchronously and any scripts that do not share dependencies are executed asynchronously while scripts that share dependencies are executed in order.

Head.WP assumes that scripts are loaded via `wp_enqueue_scipt()` and that any script enqueued in the `<head>` intends to be loaded before the rest of the page.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where are the plugin options? =

There aren't any, but the plugin counts on scripts being properly enqueued in the footer with correctly listed dependencies. [See wp_enqueue_script in the Codex](http://codex.wordpress.org/Function_Reference/wp_enqueue_script).

= Why are my site's scripts still loading conventionally (without head.js)? =

There are a few reasons why a script might still be loaded conventionally.
* Head.WP ignores any scripts that are not added via `wp_enqueue_script()`.
* Head.WP only works with scripts enqueued in the footer, it assumes that any script enqueued in the `<head>` intends to block the rest of the rest of the page from loading until it is finished.

= Why are my other scripts not working as expected? =

If a script has no dependencies, head.js will execute that script as soon it is loaded. To ensure that scripts are executed in the correct order, make sure to list all required dependencies in `wp_enqueue_script()`.

= Is this plugin on GitHub? =

Yes it is. [Head.WP](https://github.com/kylereicks/head.js.wp)

== Screenshots ==

== Changelog ==

= 1.0 =
* Release 1.0.

== Upgrade Notice ==

= 1.0 =
This is the initial public release.
