# Snitch #
* Contributors:      pluginkollektiv
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TD4AMD2D8EMZW
* Tags:              sniffer, snitch, network, monitoring, firewall, GDPR
* Requires at least: 3.8
* Tested up to:      6.4
* Requires PHP:      5.2.4
* Stable tag:        1.2.0
* License:           GPLv3 or later
* License URI:       https://www.gnu.org/licenses/gpl-3.0

Network monitor for WordPress. Connection overview for monitoring and controlling outgoing data traffic.

## Description ##
Network monitor for WordPress with connection overview for controlling and regulating data traffic from your site.

### Trust, But Verify ###
*Snitch* monitors and logs the outgoing data stream of your WordPress site. It records every outbound connection from WordPress and provides a log table for administrators.

*Snitch* does not only log connection requests, but enables you to block future requests either by target URL (internet address being called in the background), or by script (file being executed to open up a connection). Once blocked, a  connection will be visually highlighted. Blocked entries can be unblocked with a simple click.

*Snitch* is a perfect tool to “listen in” on outbound communication. It is also suitable to early recognize any malware and tracking software installed. You can youse *Snitch* to make sure you comply with GDPR.

### Summary ###
*Snitch* writes a log of both authorized and blocked attempts of connectivity. An overall view provides transparency and lets you control outgoing connections initialized by plugins, themes, or WordPress.

### In A Nutshell ###
* neat interface
* displays target URL and source file
* features grouping, sorting, searching
* visual highlighting of blocked requests
* show POST variables with a simple click
* block/unblock connections by domain/file
* monitors communication in back-end and front-end
* delete all entries by pressing a button
* free of charge, no advertising

### Support ###
* Community support via the [support forums on wordpress.org](https://wordpress.org/support/plugin/snitch)
* We don’t handle support via e-mail, Twitter, GitHub issues etc.

### Contribute ###
* Active development of this plugin is handled [on GitHub](https://github.com/pluginkollektiv/snitch).
* Pull requests for documented bugs are highly appreciated.
* If you think you’ve found a bug (e.g. you’re experiencing unexpected behavior), please post at the [support forums](https://wordpress.org/support/plugin/snitch) first.
* If you want to help us translate this plugin you can do so [on WordPress Translate](https://translate.wordpress.org/projects/wp-plugins/snitch).

### Credits ###
* Author: [Sergej Müller](https://sergejmueller.github.io/)
* Maintainers: [pluginkollektiv](https://pluginkollektiv.org/)

## Installation ##
* If you don’t know how to install a plugin for WordPress, [here’s how](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).


## Frequently Asked Questions ##

### Snitch creates a lot of database entries ###
*Snitch* is designed to log any outgoing connection in WordPress. If the database fills fast, you should look up the cause. Why does your WordPress and plugins communicate so often to the outside that the database table fills? Is this communication really necessary?

As a reminder: *Snitch* is designed to help you improve your WordPress performance by detecting and displaying connections as bottleneck. The task for the blog administrator is to eliminate the source of the cause (plugin, theme, etc.).

*Snitch* automatically ensures that there are not more than 200 entries are kept in the database. If it is nevertheless necessary to remove *Snitch* entries from the database manually, two smart database commands could help:

`sql
DELETE FROM `wp_postmeta` WHERE `post_id` IN ( SELECT `ID` FROM `wp_posts` WHERE `post_type` = 'snitch' )
DELETE FROM `wp_posts` WHERE `post_type` = 'snitch'
`

### Are connections monitored in the front end? ###
*Snitch* catches any connection that leaves the blog via [WordPress HTTP API](http://codex.wordpress.org/HTTP_API) (internal WordPress interface for data communication). This affects both the back-end and the front-end of a WordPress installation.

### Why does Snitch list WordPress cronjobs? ###
WordPress calls internal Cronjobs via [WordPress HTTP API](http://codex.wordpress.org/HTTP_API) - exactly this interface is monitored by _Snitch_ and also records Cronjob accesses accordingly.

If cronjobs are listed too often, something possibly isn't correct. Therefore, it is recommend to check the list of scheduled cronjob jobs.

The following code snippet in the WordPress configuration file `wp-config.php` switches off the logging of the internal WordPress queries:

`php
define('SNITCH_IGNORE_INTERNAL_REQUESTS', true);
`

### Why are Snitch entries indexed by Google? ###
*Snitch* stores its entries as [WordPress Custom Post Types](https://codex.wordpress.org/Post_Types). Important step: By a WordPress attribute Snitch marks all log entries as private, therefore not public. So far, the ideology with private and inaccessible entries would work if there were not WordPress plugins that would carry all - including private - Custom Post Types into the world and communicate with search engines. With fatal consequences for the blogger.

And so it quickly happens that Google suddenly hits *Snitch* entries (as blog pages) which are not intended for public access. For example, because _Snitch_ entries appear in the sitemap XML of the blog, as a sitemap XML plugin is of the opinion that it is also necessary to add private entries and to have them released for indexing. There is also no help to block via `robots.txt` because the `robots.txt` file does not prevent the indexing of the pages.

### Automatic Shares go crazily ###
The fact that every new _Snitch_ entry automatically sends a message to Facebook and/or Twitter, is clearly not due to *Snitch*. Rather, the cause is to be found in the inserted Auto-Tweet-Facebook-Plugin, which faulty triggers an automatic event at every - also non-public - [WordPress Custom Post Type](https://codex.wordpress.org/Post_Types). And that's wrong. The usage of such Plugins should be reconsidered.

A complete documentation is available on the [Snitch website](https://snitch.pluginkollektiv.org/documentation/).


## Changelog ##

### 1.2.0 ###
* Shows schema of request (http/https)
* No "jerking" in the retrieval list during mouse over
* Remove `lang` folder in favor of translations via translate.wordpress.org
* Support for WordPress 6.4

### 1.1.8 ###
* Support for WordPress 5.2
* Bugfix: Deprected Non-static call

### 1.1.7 ###
* Updated README
* Improved user interface
* Support for WordPress 4.9

### 1.1.6 ###
* Updated README
* Updated [plugin authors](https://pluginkollektiv.org/hello-world/)

### 1.1.5 / 06.05.2015 ###
* [GitHub Repository](https://github.com/pluginkollektiv/snitch)

### 1.1.4 ###
* Support for WordPress 4.2
* Nice to have: `admin_url()` for `edit.php` requests

### 1.1.3 ###
* Support for WordPress 4.1

### 1.1.2 ###
* Feature: english translation for the readme file
* Feature: russian translation for plugin files

### 1.1.1 ###
* Feature: status code “-1” for failing connections

### 1.1.0 ###
* Feature: execution time as metric (thanks [Matthias Kilian](https://www.gaertner.de) for the idea)

For the complete changelog, check out our [GitHub repository](https://github.com/pluginkollektiv/snitch).

## Upgrade Notice ##

### 1.1.7 ###
This is mainly a maintenance release which updates the readme and the plugin authors.

## Screenshots ##
1. Snitch connection list
