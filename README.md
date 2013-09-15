Head.js.wp Plugin
=================

A plugin to load all footer scripts with [head.js](https://github.com/headjs/headjs).

Details
-------

Head.js is a script to asynchronously load and optionally asynchronously execute javascript assets.

This plugin loads the scripts enqueued in footer with the head.js loader. All of the footer scripts are loaded asynchronously and any scripts that do not share dependencies are executed asynchronously while scripts that share dependencies are executed in order.

Head.WP assumes that scripts are loaded via `wp_enqueue_script()` and that any script enqueued in the `<head>` intends to be loaded before the rest of the page.

This plugin also uses javascript to load any CSS files that are enqueued in the `wp_footer`.

###Note

This plugin replaces the `_wp_footer_scripts` function for non-admin pages. Replacing underscore functions can be risky. If you notice a problem with your site after a WordPress core update, check this plugin and others that override underscore functions first.
