=== Snitch ===
Contributors: sergej.mueller
Tags: sniffer, snitch, network, monitoring, firewall
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAQUT9RLPW8QN
Requires at least: 3.8
Tested up to: 4.2.1
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html



Network monitor for WordPress. Connection overview for monitoring and controlling outgoing data traffic.



== Description ==

Network monitor for WordPress with connection overview for controlling and regulating data traffic from your site.

> For German users: [Beschreibung](https://github.com/sergejmueller/snitch) und [Dokumentation](https://github.com/sergejmueller/snitch/wiki) in Deutsch


= Trust, But Verify =

*Snitch* monitors and logs the outgoing data stream of your WordPress site. It records every outbound connection from WordPress and provides a log table for administrators.

*Snitch* does not only log connection requests, but enables you to block future requests either by target URL (internet address being called in the background), or by script (file being executed to open up a connection). Once blocked, a  connection will be visually highlighted. Blocked entries can be unblocked with a simple click.

*Snitch* is a perfect tool to “listen in” on outbound communication. It is also suitable to early recognize any malware and tracking software installed.


= Summary =
*Snitch* writes a log of both authorized and blocked attempts of connectivity. An overall view provides transparency and lets you control outgoing connections initialized by plugins, themes, or WordPress.

= In A Nutshell =
* neat interface
* displays target URL and source file
* features grouping, sorting, searching
* visual highlighting of blocked requests
* show POST variables with a simple click
* block/unblock connections by domain/file
* monitors communication in back-end and front-end
* delete all entries by pressing a button
* free of charge, no advertising


= Requirements =
* PHP 5.2.4
* WordPress 3.8


= Memory Usage =
* Backend: ~ 0,32 MB
* Frontend: ~ 0,27 MB


= Languages =
* German
* English
* Русский


= Contributors =
* [Caspar Hübinger](http://glueckpress.com)
* [Bego Mario Garde](https://garde-medienberatung.de)


= Author =
* [Twitter](https://twitter.com/wpSEO)
* [Google+](https://plus.google.com/110569673423509816572)
* [Plugins](http://wpcoder.de "WordPress Plugins")


== Changelog ==

= 1.1.5 / 06.05.2015 =
* [GitHub Repository](https://github.com/sergejmueller/snitch)

= 1.1.4 =
* Support for WordPress 4.2
* Nice to have: `admin_url()` for `edit.php` requests

= 1.1.3 =
* Support for WordPress 4.1

= 1.1.2 =
* feature: english translation for the readme file
* feature: russian translation for plugin files
* *time investment for this release (development & QA): 2.0 hours*

= 1.1.1 =
* feature: status code “-1” for failing connections
* *time investment for this release (development & QA): 2.5 hours*

= 1.1.0 =
* feature: execution time as metric (thanks [Matthias Kilian](https://www.gaertner.de) for the idea)

= 1.0.12 =
* extensive consideration of user roles
* copy adjustments

= 1.0.11 =
* support for WordPress 3.9
* source code face lifting

= 1.0.10 =
* change: $pre as return value of function `inspect_request`

= 1.0.9 =
* optimization for WordPress 3.8
* introduction of constant `SNITCH_IGNORE_INTERNAL_REQUESTS`
* [details for this update on Google+](https://plus.google.com/+SergejMüller/posts/KaSGc9uNpk4)

= 1.0.8 =
* output POST data on click
* support for WordPress 3.6
* [details for this update on Google+](https://plus.google.com/110569673423509816572/posts/f8VaQaHfQjx)

= 1.0.7 =
* removal of obsolete "New" link from the toolbar
* prevention of direct file calls

= 1.0.6 =
* set function `delete_items` to public

= 1.0.5 =
* storage of a maximum of 200 Snitch entries

= 1.0.4 =
* new: searching of target URLs

= 1.0.3 =
* new: button *Empty Protocol*
* removed: avoidance of trash

= 1.0.2 =
* renaming of custom field keys to avoid conflict

= 1.0.1 =
* fix for *Call to undefined function get_current_screen*

= 1.0.0 =
* Snitch goes online




== Screenshots ==

1. Snitch connection list with target URL and actions

2. Snitch connection list with further information