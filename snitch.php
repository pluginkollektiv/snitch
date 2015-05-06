<?php
/*
Plugin Name: Snitch
Description: Network monitor for WordPress. Connection overview for monitoring and controlling outgoing data traffic.
Text Domain: snitch
Domain Path: /lang
Author: Sergej M&uuml;ller
Author URI: https://sergejmueller.github.io
Plugin URI: https://wordpress.org/extend/plugins/snitch/
License: GPLv3 or later
Version: 1.1.5
*/

/*
Copyright (C)  2013-2015 Sergej Müller

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/


/* Quit */
defined('ABSPATH') OR exit;


/* Konstanten */
define('SNITCH_FILE', __FILE__);
define('SNITCH_DIR', dirname(__FILE__));
define('SNITCH_BASE', plugin_basename(__FILE__));
define('SNITCH_BLOCKED', 1);
define('SNITCH_AUTHORIZED', -1);


/* Hooks */
add_action(
	'plugins_loaded',
	array(
		'Snitch',
		'instance'
	)
);


/* Hooks */
register_activation_hook(
	__FILE__,
	array(
		'Snitch',
		'activation'
	)
);
register_deactivation_hook(
	__FILE__,
	array(
		'Snitch',
		'deactivation'
	)
);
register_uninstall_hook(
	__FILE__,
	array(
		'Snitch',
		'uninstall'
	)
);


/* Autoload Init */
spl_autoload_register('snitch_autoload');

/* Autoload Funktion */
function snitch_autoload($class) {
	if ( in_array($class, array('Snitch', 'Snitch_HTTP', 'Snitch_CPT', 'Snitch_Blacklist')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				SNITCH_DIR,
				strtolower($class)
			)
		);
	}
}