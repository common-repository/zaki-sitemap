=== Zaki Sitemap ===
Contributors: r.conte
Donate link: http://www.zaki.it
Tags: posts, pages, post-type, list, sitemap, page tree, post tree, tree
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

That plugin allow you to create a sitemap of your site. Use [zakisitemap] shortcode

== Description ==

That plugin allow you to create a sitemap of your site. You can choose to exclude pages, categories and post-types. You can embed it with a shortcode [zakisitemap] in posts and pages or directly in theme with do_shortcode() function. To customize the widget you can refer to this CSS containers: `ul.zaki-sitemap-list`.

== Installation ==

1. Unzip and upload the plugin in your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag & Drop the widget in your sidebar and customize your options

== Frequently asked questions ==

= Will be provided for new features? =
Of course! Currently i'm working for better compatibility with WPML and more features like a button into editor for fast embed and a customizable and more solid structure.

== Changelog ==

= 1.2 =
* (Add) Added setting to display post-type posts in sitemap

= 1.1.2 =
* (Bug fix) Set "show_option_none" to null in wp_list_categories function to prevent output with no category content

= 1.1.1 =
* (Add) Added class_exists control of Zaki Plugins new features

= 1.1 =
* (Change) Added WP build-in function for generate the tree view instead of a custom recursive function
* (Bug fix) Fixed some label string

= 1.0.1 =
* (Bug fix) Fixed WPML check function that return always "true"
* (Bug fix) Added ZAKI_SITEMAP_FILE define for correct hook init

= 1.0 =
* First release of the widget

