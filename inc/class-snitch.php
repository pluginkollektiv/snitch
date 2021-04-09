<?php
/**
 * Snitch class initializing the hooks and actions.
 *
 * @package Snitch
 */

/* Quit */
defined( 'ABSPATH' ) || exit;

/**
 * Class Snitch.
 *
 * @since 0.0.1
 */
class Snitch {
	/**
	 * Pseudo-Konstruktor der Klasse
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 */
	public static function instance() {
		 new self();
	}

	/**
	 * Konstruktor der Klasse
	 *
	 * @since   0.0.1
	 * @change  1.0.5
	 */
	public function __construct() {
		 /* Register CPT */
		add_action(
			'init',
			array(
				'Snitch_CPT',
				'instance',
			),
			1
		);

		/* HTTP Request */
		add_filter(
			'pre_http_request',
			array(
				'Snitch_HTTP',
				'inspect_request',
			),
			10,
			3
		);

		/* HTTP API */
		add_action(
			'http_api_debug',
			array(
				'Snitch_HTTP',
				'log_response',
			),
			10,
			5
		);

		/* Cronjob */
		add_action(
			'snitch_cleanup',
			array(
				'Snitch_CPT',
				'cleanup_items',
			)
		);

		/* Admin only */
		if ( ! is_admin() ) {
			return;
		}

		/* Skip secondary hooks */
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			 || ( defined( 'DOING_CRON' ) && DOING_CRON )
			 || ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			 || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return;
		}

		/* CSS */
		add_action(
			'admin_print_styles',
			array(
				__CLASS__,
				'add_css',
			)
		);

		/* Admin notice */
		add_action(
			'admin_notices',
			array(
				__CLASS__,
				'updated_notice',
			)
		);
		add_action(
			'network_admin_notices',
			array(
				__CLASS__,
				'updated_notice',
			)
		);

		/* Meta links */
		add_filter(
			'plugin_row_meta',
			array(
				__CLASS__,
				'meta_links',
			),
			10,
			2
		);
		add_filter(
			'plugin_action_links_' . SNITCH_BASE,
			array(
				__CLASS__,
				'action_links',
			)
		);

		/* Load lang */
		load_plugin_textdomain( 'snitch' );
	}

	/**
	 * Hinzufügen der Meta-Links
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 *
	 * @param   array  $data  Array mit Links.
	 * @param   string $file  Pfad des Plugins.
	 * @return  array          Array mit erweiterten Links
	 */
	public static function meta_links( $data, $file ) {
		 /* Skip the rest */
		if ( SNITCH_BASE !== $file ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TD4AMD2D8EMZW" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Donate', 'snitch' ) . '</a>',
				'<a href="https://wordpress.org/support/plugin/snitch" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support', 'snitch' ) . '</a>',
			)
		);
	}


	/**
	 * Hinzufügen der Action-Links
	 *
	 * @since   0.0.4
	 * @change  1.1.2
	 *
	 * @param   array $data  Bereits existente Links.
	 * @return  array  $data  Erweitertes Array mit Links
	 */
	public static function action_links( $data ) {
		/* Rechte? */
		if ( ! current_user_can( 'manage_options' ) ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'post_type' => 'snitch',
						),
						admin_url( 'edit.php' )
					),
					esc_html__( 'Connections', 'snitch' )
				),
			)
		);
	}

	/**
	 * Ausgabe des Administrator-Hinweises
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 */
	public static function updated_notice() {
		/* Skip requests */
		if ( 'plugins.php' !== $GLOBALS['pagenow']
			 || ! ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ) ) {
			return;
		}

		/* Print */
		echo sprintf(
			'<div class="error"><p>%s</p></div>',
			wp_kses(
				__( 'Outgoing connections are blocked in <code>wp-config.php</code>. Check the constant <code>WP_HTTP_BLOCK_EXTERNAL</code>.', 'snitch' ),
				array( 'code' => array() )
			)
		);
	}

	/**
	 * Fügt Stylesheets hinzu
	 *
	 * @since   0.0.1
	 * @change  1.0.1
	 */
	public static function add_css() {
		wp_enqueue_style(
			'snitch-global',
			plugins_url( 'css/global.min.css', SNITCH_FILE ),
			array(),
			self::get_version()
		);
	}

	/**
	 * Rückgabe der Optionen
	 *
	 * @since   0.0.1
	 * @change  0.0.5
	 *
	 * @param   string $item  Feldname der Option [optional].
	 * @return  mixed          Alle oder eine bestimmte Option
	 */
	public static function get_options( $item = null ) {
		/* Get options */
		$options = get_site_option( 'snitch' );

		return ( empty( $item ) ? $options : @$options[ $item ] );
	}

	/**
	 * Rückgabe der Optionen
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 *
	 * @param   string $key    Feldname der Option.
	 * @param   mixed  $value  Wert der Option.
	 */
	public static function update_options( $key, $value ) {
		update_site_option(
			'snitch',
			array_merge(
				self::get_options(),
				array(
					$key => $value,
				)
			)
		);
	}

	/**
	 * Fügt hinzu oder entfernt Nutzer-Berechtigungen
	 *
	 * @since   0.0.5
	 * @change  0.0.5
	 *
	 * @param   string $role    Benutzerkennung.
	 * @param   string $action  Auszuführende Aktion.
	 */
	private static function _handle_caps( $role, $action ) {
		/* Get role */
		$role = get_role( $role );

		/* Avaliable caps */
		$caps = array(
			'edit_snitchs',
			'edit_snitch',
			'edit_private_snitchs',
			'delete_snitch',
			'delete_snitchs',
			'edit_others_snitchs',
			'read_snitchs',
			'read_private_snitchs',
			'delete_published_snitchs',
			'delete_private_snitchs',
		);

		/* Loop & set caps */
		foreach ( $caps as $caps ) {
			call_user_func(
				array(
					$role,
					$action . '_cap',
				),
				$caps
			);
		}
	}

	/**
	 * Aktionen bei der Aktivierung des Plugins
	 *
	 * @since   0.0.4
	 * @change  1.0.5
	 */
	public static function activation() {
		/* Add default options */
		add_site_option(
			'snitch',
			array(
				'hosts' => array(),
				'files' => array(),
			),
			'',
			'no'
		);

		/* Add caps */
		self::_handle_caps( 'administrator', 'add' );

		/* Init cronjob */
		if ( ! wp_next_scheduled( 'snitch_cleanup' ) ) {
			wp_schedule_event(
				time(),
				'daily',
				'snitch_cleanup'
			);
		}
	}

	/**
	 * Aktionen bei der Deaktivierung des Plugins
	 *
	 * @since   1.0.5
	 * @change  1.0.5
	 */
	public static function deactivation() {
		 wp_clear_scheduled_hook( 'snitch_cleanup' );
	}

	/**
	 * Aktionen bei der Deinstallation des Plugins
	 *
	 * @since   0.0.4
	 * @change  1.0.3
	 */
	public static function uninstall() {
		/* Unregister CPT */
		Snitch_CPT::delete_items();

		/* Kill options */
		delete_site_option( 'snitch' );

		/* Remove caps */
		self::_handle_caps( 'administrator', 'remove' );
	}

	/**
	 * Returns the Snitch version number.
	 *
	 * @return string the Snitch version
	 *
	 * @since 1.2.0
	 */
	public static function get_version() {
		$data = get_plugin_data( SNITCH_FILE );
		return $data['Version'];
	}
}
