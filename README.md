Head.js.wp Plugin
=================

A plugin to load all footer scripts with [head.js](https://github.com/headjs/headjs).

Details
-------

Head.js is a script to asynchronously load and optionally asynchronously execute javascript assets.

This plugin loads the scripts enqueued in footer with the head.js loader. All of the footer scripts are loaded asynchronously and any scripts that do not share dependencies are executed asynchronously while scripts that share dependencies are executed in order.

Head.WP assumes that scripts are loaded via `wp_enqueue_scipt()` and that any script enqueued in the `<head>` intends to be loaded before the rest of the page.
