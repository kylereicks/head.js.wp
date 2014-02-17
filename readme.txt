=== Head.WP ===
Contributors: kylereicks
Donate link: http://shakopee.dollarsforscholars.org/
Tags: script loading, asynchronous, javascript, async, headJS, head.js, enqueue, wp_enqueue_script
Requires at least: 3.2
Tested up to: 3.8.1
Stable tag: 2.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to load all footer scripts and styles with [head.js](https://github.com/headjs/headjs).

== Description ==

Head.js is a script to asynchronously load and manage dependencies of javascript and CSS assets.

This plugin loads the scripts and styles enqueued in footer with the head.js loader. It does not include head.js' feature detection. 

Head.js.wp assumes that scripts are loaded via `wp_enqueue_script()` and that any script enqueued in the `<head>` intends to be loaded before the rest of the page.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where are the plugin options? =

There aren't any, but the plugin counts on scripts being properly enqueued in the footer with correctly listed dependencies. [See wp_enqueue_script in the Codex](http://codex.wordpress.org/Function_Reference/wp_enqueue_script).

= Why are my site's scripts still loading conventionally (i.e. without head.js)? =

There are a few reasons why a script might still be loaded conventionally.
* Head.WP ignores any scripts that are not added via `wp_enqueue_script()`.
* Head.WP only works with scripts enqueued in the footer, it assumes that any script enqueued in the `<head>` intends to block the rest of the rest of the page from loading until it is finished.

= Why are my other scripts not working as expected? =

If a script has no dependencies, head.js will execute that script as soon it is loaded. To ensure that scripts are executed in the correct order, make sure to list all required dependencies in `wp_enqueue_script()`.

= Is this plugin on GitHub? =

Yes it is. [Head.WP](https://github.com/kylereicks/head.js.wp)

== Screenshots ==

== Changelog ==

= 2.0.3 =
* Fix reference error when localizing scripts.

= 2.0.2 =
* Fix warning on log-in page.
* Remove output when no scripts need to be loaded.

= 2.0.1 =
* Hotfix. Fix warning in shortcode class.
* Add head.min and head.map to the svn repo.

= 2.0.0 =
* A complete rewrite. It really is much nicer now.
* Update to head.js version 1.0.3

= 1.0 =
* Release 1.0.

== Upgrade Notice ==

= 2.0.3 =
Bug fix update. Cleans up reference error when localizing scripts.
