=== Plugin Name ===
Contributors: blacki
Donate link: http://32leaves.net
Tags: references, links
Requires at least: 2.1.0 or higher
Tested up to: 2.7.1
Stable tag: 1.0

Generates a list of links used in a post. This can be used to create a list of references like those
in scientific publications.

== Description ==

Generates a list of links used in a post. This can be used to create a list of references like those
in scientific publications. Add [lor] at the end of your post (or wherever you want the list of references)
and add a title attribute to all links which should appear in the list.

E.g. `<a href="http://somelink.org" title="Homepage of somelink">Some link</a>`

== Installation ==

1. Upload `listofreferences.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How can I modify the template of the list of references =
1. Goto the 'Plugins' menu in WordPress
1. Edit the 'Auto list of references' plugin
1. Change the code between the `<<<EOF` and `EOF` statements (around line 12). The `{uid}`, `{id}`, `{title}` and `{href}` tags are replaced.

== Screenshots ==

1. The list of references
1. The modified links in the post
1. Edit the highlighted text to modifiy the list of references template
