# Snitch #
* Contributors:      pluginkollektiv
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8CH5FPR88QYML
* Tags:              sniffer, snitch, network, monitoring, firewall
* Requires at least: 3.8
* Tested up to:      4.6
* Stable tag:        trunk
* License:           GPLv3 or later
* License URI:       https://www.gnu.org/licenses/gpl-3.0.html


Network monitor for WordPress. Connection overview for monitoring and controlling outgoing data traffic.

## Description ##
Network monitor for WordPress with connection overview for controlling and regulating data traffic from your site.

> #### Auf Deutsch? ####
> Für eine ausführliche Dokumentation besuche bitte das [Wiki](https://github.com/pluginkollektiv/snitch/wiki).
>
> **Community-Support auf Deutsch** erhältst du in einem der [deutschsprachigen Foren](https://de.forums.wordpress.org/forum/plugins); im [Plugin-Forum für Snitch](https://wordpress.org/support/plugin/snitch) wird, wie in allen Plugin-Foren auf wordpress.org, ausschließlich **Englisch** gesprochen.


### Trust, But Verify ###
*Snitch* monitors and logs the outgoing data stream of your WordPress site. It records every outbound connection from WordPress and provides a log table for administrators.

*Snitch* does not only log connection requests, but enables you to block future requests either by target URL (internet address being called in the background), or by script (file being executed to open up a connection). Once blocked, a  connection will be visually highlighted. Blocked entries can be unblocked with a simple click.

*Snitch* is a perfect tool to “listen in” on outbound communication. It is also suitable to early recognize any malware and tracking software installed.


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


### Memory Usage ###
* Backend: ~ 0.32 MB
* Frontend: ~ 0.27 MB

### Languages ###
* German
* English
* Русский

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
* Maintainers: [pluginkollektiv](http://pluginkollektiv.org/)
* Contributor: [Bego Mario Garde](https://garde-medienberatung.de)


## Installation ##
* If you don’t know how to install a plugin for WordPress, [here’s how](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).


### Requirements ###
* PHP 5.2.4 or greater
* WordPress 3.8 or greater


## Changelog ##
### 1.1.6 ###
* updated README
* updated [plugin authors](https://gist.github.com/glueckpress/f058c0ab973d45a72720)

### 1.1.5 / 06.05.2015 ###
* [GitHub Repository](https://github.com/sergejmueller/snitch)

### 1.1.4 ###
* Support for WordPress 4.2
* Nice to have: `admin_url()` for `edit.php` requests

### 1.1.3 ###
* Support for WordPress 4.1

### 1.1.2 ###
* feature: english translation for the readme file
* feature: russian translation for plugin files

### 1.1.1 ###
* feature: status code “-1” for failing connections

### 1.1.0 ###
* feature: execution time as metric (thanks [Matthias Kilian](https://www.gaertner.de) for the idea)

## Upgrade Notice ##

### 1.1.6 ###
This is mainly a maintenance release which updates the readme and the plugin authors.

## Screenshots ##
1. Snitch connection list with target URL and actions

2. Snitch connection list with further information
