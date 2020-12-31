<?php
/**
 * Snitch_HTTP class checking and logging outgoing requests.
 *
 * @package Snitch
 */

/* Quit */
defined( 'ABSPATH' ) || exit;

/**
 * Class Snitch_HTTP.
 *
 * @since 0.0.1
 */
class Snitch_HTTP {
	/**
	 * Prüft den ausgehenden Request
	 *
	 * @since   0.0.1
	 * @change  1.1.0
	 *
	 * @hook    array    snitch_inspect_request_hosts
	 * @hook    array    snitch_inspect_request_files
	 * @hook    array    snitch_inspect_request_insert_post
	 *
	 * @param   boolean $pre   FALSE.
	 * @param   array   $args  Argumente der Anfrage.
	 * @param   string  $url   URL der Anfrage.
	 * @return  mixed           FALSE im Erfolgsfall
	 */
	public static function inspect_request( $pre, $args, $url ) {
		/* Empty url */
		if ( empty( $url ) ) {
			return $pre;
		}

		/* Invalid host */
		$host = parse_url( $url, PHP_URL_HOST );
		if ( ! $host ) {
			return $pre;
		}

		/* Check for internal requests */
		if ( defined( 'SNITCH_IGNORE_INTERNAL_REQUESTS' ) && SNITCH_IGNORE_INTERNAL_REQUESTS && self::_is_internal( $host ) ) {
			return $pre;
		}

		/* Timer start */
		timer_start();

		/* Snitch options */
		$options = Snitch::get_options();

		/* Blacklisted items */
		$blacklist = array(
			'hosts' => (array) apply_filters(
				'snitch_inspect_request_hosts',
				$options['hosts']
			),
			'files' => (array) apply_filters(
				'snitch_inspect_request_files',
				$options['files']
			),
		);

		/* Backtrace data */
		$backtrace = self::_debug_backtrace();

		/* No reference file found */
		if ( empty( $backtrace['file'] ) ) {
			return $pre;
		}

		/* Show your face, file */
		$meta = self::_face_detect( $backtrace['file'] );

		/* Init data */
		$file = str_replace( ABSPATH, '', $backtrace['file'] );
		$line = (int) $backtrace['line'];

		/* Blocked item? */
		if ( in_array( $host, $blacklist['hosts'] ) || in_array( $file, $blacklist['files'] ) ) {
			return Snitch_CPT::insert_post(
				(array) apply_filters(
					'snitch_inspect_request_insert_post',
					array(
						'url'      => esc_url_raw( $url ),
						'code'     => null,
						'host'     => $host,
						'file'     => $file,
						'line'     => $line,
						'meta'     => $meta,
						'state'    => SNITCH_BLOCKED,
						'postdata' => self::_get_postdata( $args ),
					)
				)
			);
		}

		return $pre;
	}

	/**
	 * Protokolliert den Request
	 *
	 * @since   0.0.1
	 * @change  1.1.0
	 *
	 * @hook   array   snitch_log_response_insert_post
	 *
	 * @param  object $response  Response-Object.
	 * @param  string $type      Typ der API.
	 * @param  string $class     Klasse der API.
	 * @param  array  $args      Argumente der API.
	 * @param  string $url       URL der API.
	 *
	 * @return bool false if the request was not saved.
	 */
	public static function log_response( $response, $type, $class, $args, $url ) {
		/* Only response type */
		if ( 'response' !== $type ) {
			return false;
		}

		/* Empty url */
		if ( empty( $url ) ) {
			return false;
		}

		/* Validate host */
		$host = parse_url( $url, PHP_URL_HOST );
		if ( ! $host ) {
			return false;
		}

		/* Check for internal requests */
		if ( defined( 'SNITCH_IGNORE_INTERNAL_REQUESTS' ) && SNITCH_IGNORE_INTERNAL_REQUESTS && self::_is_internal( $host ) ) {
			return false;
		}

		/* Backtrace data */
		$backtrace = self::_debug_backtrace();

		/* No reference file found */
		if ( empty( $backtrace['file'] ) ) {
			return false;
		}

		/* Show your face, file */
		$meta = self::_face_detect( $backtrace['file'] );

		/* Extract backtrace data */
		$file = str_replace( ABSPATH, '', $backtrace['file'] );
		$line = (int) $backtrace['line'];

		/* Response code */
		$code = ( is_wp_error( $response ) ? -1 : wp_remote_retrieve_response_code( $response ) );

		/* Insert CPT */
		Snitch_CPT::insert_post(
			(array) apply_filters(
				'snitch_log_response_insert_post',
				array(
					'url'      => esc_url_raw( $url ),
					'code'     => $code,
					'duration' => timer_stop( false, 2 ),
					'host'     => $host,
					'file'     => $file,
					'line'     => $line,
					'meta'     => $meta,
					'state'    => SNITCH_AUTHORIZED,
					'postdata' => self::_get_postdata( $args ),
				)
			)
		);
	}

	/**
	 * Ermittelt die Ursprungsdatei des Requests
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 *
	 * @return  array   $item   Information zu Herkunft
	 */
	private static function _debug_backtrace() {
		/* Reverse items */
		$trace = array_reverse( debug_backtrace() );

		/* Loop items */
		foreach ( $trace as $index => $item ) {
			if ( ! empty( $item['function'] ) && strpos( $item['function'], 'wp_remote_' ) !== false ) {
				/* Use prev item */
				if ( empty( $item['file'] ) ) {
					$item = $trace[ -- $index ];
				}

				/* Get file and line */
				if ( ! empty( $item['file'] ) && ! empty( $item['line'] ) ) {
					return $item;
				}
			}
		}
	}

	/**
	 * Versuch die Datei anhand des Pfades zuzuordnen
	 *
	 * @since   0.0.1
	 * @change  0.0.5
	 *
	 * @param   string $path  Pfad der Datei.
	 * @return  array   $meta  Array mit Informationen.
	 */
	private static function _face_detect( $path ) {
		 /* Default */
		$meta = array(
			'type' => 'WordPress',
			'name' => 'Core',
		);

		/* Empty path */
		if ( empty( $path ) ) {
			return $meta;
		}

		/* Search for plugin */
		$data = self::_localize_plugin( $path );
		if ( $data ) {
			return array(
				'type' => 'Plugin',
				'name' => $data['Name'],
			);
		}

		/* Search for theme */
		$data = self::_localize_theme( $path );
		if ( $data ) {
			return array(
				'type' => 'Theme',
				'name' => $data->get( 'Name' ),
			);
		}

		return $meta;
	}

	/**
	 * Suche nach einem Plugin anhand des Pfades
	 *
	 * @since   0.0.1
	 * @change  1.0.11
	 *
	 * @param   string $path  Pfad einer Datei aus dem Plugin-Ordner.
	 * @return  array|bool    Array mit Plugin-Daten.
	 */
	private static function _localize_plugin( $path ) {
		 /* Check path */
		if ( strpos( $path, WP_PLUGIN_DIR ) === false ) {
			return false;
		}

		/* Reduce path */
		$path = ltrim(
			str_replace( WP_PLUGIN_DIR, '', $path ),
			DIRECTORY_SEPARATOR
		);

		/* Get plugin folder */
		$folder = substr(
			$path,
			0,
			strpos( $path, DIRECTORY_SEPARATOR )
		) . DIRECTORY_SEPARATOR;

		/* Frontend */
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		/* All active plugins */
		$plugins = get_plugins();

		/* Loop plugins */
		foreach ( $plugins as $path => $plugin ) {
			if ( strpos( $path, $folder ) === 0 ) {
				return $plugin;
			}
		}
	}

	/**
	 * Suche nach einem Theme anhand des Pfades
	 *
	 * @since   0.0.1
	 * @change  0.0.5
	 *
	 * @param   string $path  Pfad einer Datei aus dem Theme-Ordner.
	 * @return  object|bool   Objekt mit Theme-Daten.
	 */
	private static function _localize_theme( $path ) {
		/* Check path */
		if ( strpos( $path, get_theme_root() ) === false ) {
			return false;
		}

		/* Reduce path */
		$path = ltrim(
			str_replace( get_theme_root(), '', $path ),
			DIRECTORY_SEPARATOR
		);

		/* Get theme folder */
		$folder = substr(
			$path,
			0,
			strpos( $path, DIRECTORY_SEPARATOR )
		);

		/* Get theme */
		$theme = wp_get_theme( $folder );

		/* Check & return theme */
		if ( $theme->exists() ) {
			return $theme;
		}

		return false;
	}

	/**
	 * Liest übermittelte POST-Daten ein
	 *
	 * @since   1.0.8
	 * @change  1.0.8
	 *
	 * @param   array $args  Argumente der Anfrage.
	 * @return  string  void   BODY der Anfrage (POST-Daten)
	 */
	private static function _get_postdata( $args ) {
		/* No POST data? */
		if ( empty( $args['method'] ) || 'POST' !== $args['method'] ) {
			return null;
		}

		/* No body data? */
		if ( empty( $args['body'] ) ) {
			return null;
		}

		return $args['body'];
	}

	/**
	 * Prüft, ob die aufgerufene URL eine Blog-interne ist
	 *
	 * @since   1.0.9
	 * @change  1.0.9
	 *
	 * @param   string $host  Zu prüfender Host.
	 * @return  boolean         TRUE bei interner URL
	 */
	private static function _is_internal( $host ) {
		/* Get the blog host */
		$blog_host = parse_url(
			get_bloginfo( 'url' ),
			PHP_URL_HOST
		);

		return ( $blog_host === $host );
	}
}
