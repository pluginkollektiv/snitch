<?php
/**
 * Snitch_CPT class registering a custom post type
 * and defining the actions for the page displaying posts of this post type.
 *
 * @package Snitch
 */

/* Quit */
defined( 'ABSPATH' ) || exit;

/**
 * Snitch_CPT
 *
 * @since 0.0.1
 */
class Snitch_CPT {
	/**
	 * Plugin options
	 *
	 * @var array
	 * @since   0.0.1
	 */
	protected static $options = array();

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
	 * Registrierung der Post Types und Aktionen
	 *
	 * @since   0.0.1
	 * @change  1.1.3
	 */
	public function __construct() {
		 /* Set plugin options */
		self::$options = Snitch::get_options();

		/* Post Type */
		register_post_type(
			'snitch',
			array(
				'label' => 'Snitch',
				'labels' => array(
					'not_found' => esc_html__( 'No items found. Future connections will be shown at this place.', 'snitch' ),
					'not_found_in_trash' => esc_html__( 'No items found in trash.', 'snitch' ),
					'search_items' => esc_html__( 'Search in destination', 'snitch' ),
				),
				'public' => false,
				'show_ui' => true,
				'query_var' => true,
				'hierarchical' => false,
				'capabilities' => array(
					'create_posts' => false,
					'delete_posts' => false,
				),
				'menu_position' => 50,
				'capability_type' => 'snitch',
				'publicly_queryable' => false,
				'exclude_from_search' => true,
			)
		);

		/* Admin only */
		if ( ! is_admin() ) {
			return;
		}

		/* CSS */
		add_action(
			'admin_print_styles-edit.php',
			array(
				__CLASS__,
				'add_css',
			)
		);

		/* Bulk action */
		add_action(
			'load-edit.php',
			array(
				__CLASS__,
				'bulk_action',
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
			'updated_notice',
			array(
				__CLASS__,
				'updated_notice',
			)
		);

		/* Hide menu item */
		add_action(
			'admin_menu',
			array(
				__CLASS__,
				'hide_menu',
			)
		);

		/* Action dropdown */
		add_filter(
			'bulk_actions-edit-snitch',
			'__return_empty_array'
		);

		/* Actions above table */
		add_action(
			'restrict_manage_posts',
			array(
				__CLASS__,
				'actions_above_table',
			)
		);

		/* Vars for column filter */
		add_filter(
			'parse_query',
			array(
				__CLASS__,
				'expand_query_vars',
			)
		);

		/* Vars for search and order by */
		add_filter(
			'request',
			array(
				__CLASS__,
				'orderby_search_columns',
			)
		);

		/* Custom columns */
		add_filter(
			'manage_snitch_posts_columns',
			array(
				__CLASS__,
				'manage_columns',
			)
		);
		add_filter(
			'manage_edit-snitch_sortable_columns',
			array(
				__CLASS__,
				'sortable_columns',
			)
		);
		add_action(
			'manage_snitch_posts_custom_column',
			array(
				__CLASS__,
				'custom_column',
			),
			10,
			2
		);

		/* View links */
		add_filter(
			'views_edit-snitch',
			array(
				__CLASS__,
				'views_edit',
			),
			10,
			1
		);

		/*  row actions */
		add_filter(
			'post_row_actions',
			array(
				__CLASS__,
				'row_actions',
			),
			10,
			2
		);
	}

	/**
	 * Fügt Stylesheets hinzu
	 *
	 * @since   0.0.5
	 * @change  1.0.8
	 */
	public static function add_css() {
		/* Local anesthesia */
		if ( ! self::_current_screen( 'edit-snitch' ) ) {
			return;
		}

		/* Add thickbox */
		add_thickbox();

		/* Register styles */
		wp_enqueue_style(
			'snitch-cpt',
			plugins_url( 'css/cpt.min.css', SNITCH_FILE ),
			array(),
			Snitch::get_version()
		);
	}

	/**
	 * Entfernt den Menüeintrag in der Sidebar
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 */
	public static function hide_menu() {
		unset( $GLOBALS['submenu']['edit.php?post_type=snitch'][10] );
	}

	/**
	 * Definition der Filter-Auswahlbox
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 */
	public static function actions_above_table() {
		/* Local anesthesia */
		if ( ! self::_current_screen( 'edit-snitch' ) ) {
			return;
		}

		/* No items? */
		if ( ! isset( $_GET['snitch_state_filter'] ) && ! _get_list_table( 'WP_Posts_List_Table' )->has_items() ) {
			return;
		}

		/* Filter value */
		$filter = ( ! isset( $_GET['snitch_state_filter'] ) ? '' : (int) $_GET['snitch_state_filter'] );

		/* Filter dropdown */
		echo sprintf(
			'<select name="snitch_state_filter">%s%s%s</select>',
			'<option value="">' . esc_html__( 'All states', 'snitch' ) . '</option>',
			'<option value="' . esc_attr( SNITCH_AUTHORIZED ) . '" ' . selected( $filter, SNITCH_AUTHORIZED, false ) . '>' . esc_html__( 'Authorized', 'snitch' ) . '</option>',
			'<option value="' . esc_attr( SNITCH_BLOCKED ) . '" ' . selected( $filter, SNITCH_BLOCKED, false ) . '>' . esc_html__( 'Blocked', 'snitch' ) . '</option>'
		);

		/* Empty protocol button */
		if ( empty( $filter ) ) {
			submit_button(
				esc_html__( 'Empty Protocol', 'snitch' ),
				'apply',
				'snitch_delete_all',
				false
			);
		}
	}

	/**
	 * Führt den Dropdown Filter aus
	 *
	 * @since   0.0.3
	 * @change  1.0.4
	 *
	 * @param   array $query  Array mit Abfragewerten.
	 */
	public static function expand_query_vars( $query ) {
		if ( ! empty( $_GET['snitch_state_filter'] ) ) {
			$query->query_vars['meta_key'] = '_snitch_state';
			$query->query_vars['meta_value'] = (int) $_GET['snitch_state_filter'];
		}
	}

	/**
	 * Führt die Filterung via Dropdown aus
	 *
	 * @since   0.0.3
	 * @change  1.0.4
	 *
	 * @param   array $vars  Array mit Abfragewerten.
	 * @return  array  $vars  Array mit modifizierten Abfragewerten
	 */
	public static function orderby_search_columns( $vars ) {
		/* Only Snitch */
		if ( ! self::_current_screen( 'edit-snitch' ) ) {
			return $vars;
		}

		/* CPT search */
		if ( ! empty( $vars['s'] ) ) {
			add_filter(
				'get_meta_sql',
				array(
					__CLASS__,
					'modify_and_or',
				)
			);

			/* Search in urls */
			$meta_query = array(
				array(
					'key'     => '_snitch_url',
					'value'   => $vars['s'],
					'compare' => 'LIKE',
				),
			);

			/* Combined with the filter */
			if ( ! empty( $_GET['snitch_state_filter'] ) ) {
				$meta_query[] = array(
					'key'     => '_snitch_state',
					'value'   => (int) $_GET['snitch_state_filter'],
					'compare' => '=',
					'type'    => 'numeric',
				);
			}

			/* Merge attrs */
			$vars = array_merge(
				$vars,
				array(
					'meta_query' => $meta_query,
				)
			);
		}

		/* CPT orderby */
		if ( empty( $vars['orderby'] ) || ! in_array( $vars['orderby'], array( 'url', 'file', 'state', 'code' ) ) ) {
			return $vars;
		}

		/* Set var */
		$orderby = $vars['orderby'];

		return array_merge(
			$vars,
			array(
				'meta_key' => '_snitch_' . $orderby,
				'orderby'  => ( in_array( $orderby, array( 'code', 'state' ) ) ? 'meta_value_num' : 'meta_value' ),
			)
		);
	}

	/**
	 * Ändert AND auf OR bei der MySQL-Abfrage
	 *
	 * @since   1.0.4
	 * @change  1.0.4
	 *
	 * @param   array $join_where  JOIN- und WHERE-Abfragen.
	 * @return  array  $join_where  JOIN- und WHERE-Abfragen
	 */
	public static function modify_and_or( $join_where ) {
		if ( ! empty( $join_where['where'] ) ) {
			$join_where['where'] = str_replace(
				'AND (',
				'OR (',
				$join_where['where']
			);
		}

		return $join_where;
	}

	/**
	 * Verwaltung der benutzerdefinierten Spalten
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 *
	 * @hook    array  snitch_manage_columns
	 *
	 * @return  array  $columns  Array mit Spalten
	 */
	public static function manage_columns() {
		return (array) apply_filters(
			'snitch_manage_columns',
			array(
				'url'      => esc_html__( 'Destination', 'snitch' ),
				'file'     => esc_html__( 'File', 'snitch' ),
				'https'    => esc_html__( 'Scheme', 'snitch' ),
				'state'    => esc_html__( 'State', 'snitch' ),
				'code'     => esc_html__( 'Code', 'snitch' ),
				'duration' => esc_html__( 'Duration', 'snitch' ),
				'created'  => esc_html__( 'Time', 'snitch' ),
				'postdata' => esc_html__( 'Data', 'snitch' ),
			)
		);
	}

	/**
	 * Verwaltung der sortierbaren Spalten
	 *
	 * @since   0.0.2
	 * @change  0.0.3
	 *
	 * @hook    array  snitch_sortable_columns
	 *
	 * @return  array  $columns  Array mit Spalten
	 */
	public static function sortable_columns() {
		return (array) apply_filters(
			'snitch_sortable_columns',
			array(
				'url'     => 'url',
				'file'    => 'file',
				'state'   => 'state',
				'code'    => 'code',
				'created' => 'date',
			)
		);
	}

	/**
	 * Verwaltung der benutzerdefinierten Spalten
	 *
	 * @since   0.0.1
	 * @change  1.1.0
	 *
	 * @hook    array    snitch_custom_column
	 *
	 * @param   string  $column  Aktueller Spaltenname.
	 * @param   integer $post_id  Post-ID.
	 */
	public static function custom_column( $column, $post_id ) {
		 /* Column types */
		$types = (array) apply_filters(
			'snitch_custom_column',
			array(
				'url'      => array( __CLASS__, '_html_url' ),
				'file'     => array( __CLASS__, '_html_file' ),
				'https'    => array( __CLASS__, '_html_https' ),
				'state'    => array( __CLASS__, '_html_state' ),
				'code'     => array( __CLASS__, '_html_code' ),
				'duration' => array( __CLASS__, '_html_duration' ),
				'created'  => array( __CLASS__, '_html_created' ),
				'postdata' => array( __CLASS__, '_html_postdata' ),
			)
		);

		/* If type exists */
		if ( ! empty( $types[ $column ] ) ) {
			/* Callback */
			$callback = $types[ $column ];

			/* Execute */
			if ( is_callable( $callback ) ) {
				call_user_func(
					$callback,
					$post_id
				);
			}
		}
	}

	/**
	 * HTML-Ausgabe der URL
	 *
	 * @since   0.0.1
	 * @change  1.0.11
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_url( $post_id ) {
		 /* Init data */
		$url = self::_get_meta( $post_id, 'url' );
		$host = self::_get_meta( $post_id, 'host' );

		/* Already blacklisted? */
		$blacklisted = in_array( $host, self::$options['hosts'] );

		/* Print output */
		echo sprintf(
			'<div><p class="label blacklisted-%d"></p>%s<div class="row-actions">%s</div></div>',
			esc_attr( $blacklisted ),
			wp_kses(
				str_replace(
					$host,
					'<code>' . $host . '</code>',
					esc_url( $url )
				),
				array( 'code' => array() )
			),
			wp_kses(
				self::_action_link(
					$post_id,
					'host',
					$blacklisted
				),
				array(
					'a' => array(
						'class' => array(),
						'href' => array(),
					),
				)
			)
		);
	}

	/**
	 * HTML-Ausgabe der Herkunftsdatei
	 *
	 * @since   0.0.1
	 * @change  1.0.11
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_file( $post_id ) {
		/* Init data */
		$file = self::_get_meta( $post_id, 'file' );
		$line = self::_get_meta( $post_id, 'line' );
		$meta = self::_get_meta( $post_id, 'meta' );

		if ( ! is_array( $meta ) ) {
			$meta = array();
		}

		if ( ! isset( $meta['type'] ) ) {
			$meta['type'] = 'WordPress';
		}

		if ( ! isset( $meta['name'] ) ) {
			$meta['name'] = 'Core';
		}

		/* Already blacklisted? */
		$blacklisted = in_array( $file, self::$options['files'] );

		/* Print output */
		echo sprintf(
			'<div><p class="label blacklisted-%d"></p>%s: %s<br /><code>/%s:%d</code><div class="row-actions">%s</div></div>',
			esc_attr( $blacklisted ),
			esc_html( $meta['type'] ),
			esc_html( $meta['name'] ),
			esc_html( $file ),
			esc_html( $line ),
			wp_kses(
				self::_action_link(
					$post_id,
					'file',
					esc_attr( $blacklisted )
				),
				array(
					'a' => array(
						'class' => array(),
						'href' => array(),
					),
				)
			)
		);
	}

	/**
	 * HTML-Ausgabe des Zustandes
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_state( $post_id ) {
		/* Item state */
		$state = self::_get_meta( $post_id, 'state' );

		/* State values */
		$states = array(
			SNITCH_BLOCKED    => __( 'Blocked', 'snitch' ),
			SNITCH_AUTHORIZED => __( 'Authorized', 'snitch' ),
		);

		/* Print the state */
		echo sprintf(
			'<span class="%s">%s</span>',
			esc_attr( strtolower( $states[ $state ] ) ),
			esc_html( $states[ $state ] )
		);

		/* Colorize blocked item */
		if ( SNITCH_BLOCKED === $state ) {
			echo sprintf(
				'<style>#post-%1$d {background:rgba(248, 234, 232, 0.8)}#post-%1$d.alternate {background:#f8eae8}</style>',
				esc_attr( $post_id )
			);
		}
	}

	/**
	 * HTML-Ausgabe des Schemes
	 *
	 * @since   1.1.8
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_https( $post_id ) {
		/* Item state */
		$url = self::_get_meta( $post_id, 'url' );
		$parsed_url = wp_parse_url( $url );

		/* Print the state */
		$img_src = SNITCH_URL . 'assets/' . $parsed_url['scheme'] . '.png';
		echo wp_kses(
			'<img src="' . esc_url( $img_src ) . '">',
			array( 'img' => array( 'src' => array() ) )
		);
	}

	/**
	 * HTML-Ausgabe des Status-Codes
	 *
	 * @since   0.0.1
	 * @change  1.0.2
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_code( $post_id ) {
		echo esc_html( self::_get_meta( $post_id, 'code' ) );
	}

	/**
	 * HTML-Ausgabe der Dauer
	 *
	 * @since   1.1.0
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_duration( $post_id ) {
		$duration = self::_get_meta( $post_id, 'duration' );
		if ( $duration ) {
			echo sprintf(
				/* translators: duration in seconds */
				esc_html__( '%s seconds', 'snitch' ),
				esc_html( $duration )
			);
		}
	}

	/**
	 * HTML-Ausgabe des Datums
	 *
	 * @since   0.0.2
	 * @change  0.0.2
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_created( $post_id ) {
		echo sprintf(
			/* translators: duration (e. g. "15 mins") since the post was created */
			esc_html__( '%s ago', 'snitch' ),
			esc_html( human_time_diff( get_post_time( 'G', true, $post_id ) ) )
		);
	}

	/**
	 * HTML-Ausgabe der POST-Daten
	 *
	 * @since   1.0.8
	 * @change  1.1.2
	 *
	 * @param   integer $post_id  Post-ID.
	 */
	private static function _html_postdata( $post_id ) {
		/* Item post data */
		$postdata = self::_get_meta( $post_id, 'postdata' );

		/* Empty data? */
		if ( empty( $postdata ) ) {
			return;
		}

		/* Parse POST data */
		if ( ! is_array( $postdata ) ) {
			wp_parse_str( $postdata, $postdata );
		}

		/* Empty array? */
		if ( empty( $postdata ) ) {
			return;
		}

		/* Thickbox content start */
		echo sprintf(
			'<div id="snitch-thickbox-%d" class="snitch-hidden"><pre>',
			esc_attr( $post_id )
		);

		/* POST data */
		print_r( $postdata );

		/* Thickbox content end */
		echo '</pre></div>';

		/* Thickbox button */
		echo wp_kses(
			sprintf(
				'<a href="#TB_inline?width=400&height=300&inlineId=snitch-thickbox-%d" class="button thickbox">%s</a>',
				$post_id,
				esc_html__( 'Show', 'snitch' )
			),
			array(
				'a' => array(
					'class' => array(),
					'href' => array(),
				),
			)
		);
	}

	/**
	 * Generierung der Action-Links
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 *
	 * @param   integer $post_id      Post-ID.
	 * @param   string  $type         Typ des Links (host|file).
	 * @param   boolean $blacklisted  Bereits in der Blacklist.
	 * @return  string                 Zusammengebauter Action-Link
	 */
	private static function _action_link( $post_id, $type, $blacklisted = false ) {
		if ( $blacklisted ) {
			$action = 'unblock';
			$text = 'host' === $type
				? __( 'Unblock this host', 'snitch' )
				: __( 'Unblock this file', 'snitch' );
		} else {
			$action = 'block';
			$text = 'host' === $type
				? __( 'Block this host', 'snitch' )
				: __( 'Block this file', 'snitch' );
		}

		/* Block link */
		return sprintf(
			'<a href="%s" class="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						array(
							'id'        => $post_id,
							'paged'     => self::_get_pagenum(),
							'type'      => $type,
							'action'    => $action,
							'post_type' => 'snitch',
						),
						admin_url( 'edit.php' )
					),
					'snitch'
				)
			),
			$action,
			esc_html( $text )
		);
	}

	/**
	 * Legt einen Custom Post Type Eintrag an
	 *
	 * @since   0.0.1
	 * @change  1.0.2
	 *
	 * @param   array $meta     Array mit Post-Metadaten.
	 * @return  integer  $post_id  Post-ID
	 */
	public static function insert_post( $meta ) {
		/* Empty? */
		if ( empty( $meta ) ) {
			return;
		}

		/* Create post */
		$post_id = wp_insert_post(
			array(
				'post_status' => 'publish',
				'post_type'   => 'snitch',
			)
		);

		/* Add meta values */
		foreach ( $meta as $key => $value ) {
			add_post_meta(
				$post_id,
				'_snitch_' . $key,
				$value,
				true
			);
		}

		return $post_id;
	}

	/**
	 * Ausführung der Link-Aktionen
	 *
	 * @since   0.0.1
	 * @change  1.0.12
	 */
	public static function bulk_action() {
		/* Local anesthesia */
		if ( ! self::_current_screen( 'edit-snitch' ) ) {
			return;
		}

		/* Delete all items */
		if ( ! empty( $_GET['snitch_delete_all'] ) ) {
			/* Capability check */
			if ( ! current_user_can( 'delete_snitchs' ) ) {
				return;
			}

			/* Check nonce */
			check_admin_referer( 'bulk-posts' );

			/* Delete items */
			self::delete_items();

			/* We're done */
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type' => 'snitch',
					),
					admin_url( 'edit.php' )
				)
			);

			/* Fly */
			exit();
		}

		/* Check for action and type */
		if ( empty( $_GET['action'] ) || empty( $_GET['type'] ) ) {
			return;
		}

		/* Set vars */
		$action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
		$type = sanitize_text_field( wp_unslash( $_GET['type'] ) );

		/* Validate action and type */
		if ( ! in_array( $action, array( 'block', 'unblock' ) ) || ! in_array( $type, array( 'host', 'file' ) ) ) {
			return;
		}

		/* Capability check */
		if ( ! current_user_can( 'edit_snitchs' ) ) {
			return;
		}

		/* Security check */
		check_admin_referer( 'snitch' );

		/* Merge bulk IDs */
		if ( ! empty( $_GET['id'] ) ) {
			$ids = (array) (int) $_GET['id'];
			// } else if ( ! empty($_REQUEST['ids']) ) {
			// $ids = (array)$_REQUEST['ids'];
		} else {
			return;
		}

		/* Init */
		$items = array();

		/* Loop post meta */
		foreach ( $ids as $post_id ) {
			$items[] = self::_get_meta( $post_id, $type );
		}

		/* Handle types */
		call_user_func(
			array(
				'Snitch_Blocklist',
				$action,
			),
			array_unique( $items ),
			$type . 's' /* code is poetry, really */
		);

		/* We're done */
		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => 'snitch',
					'updated'   => count( $ids ) * ( 'unblock' === $action ? -1 : 1 ),
					'paged'     => self::_get_pagenum(),
				),
				admin_url( 'edit.php' )
			)
		);

		/* Fly */
		exit();
	}

	/**
	 * Ausgabe des Administrator-Hinweises
	 *
	 * @since   0.0.1
	 * @change  1.1.2
	 */
	public static function updated_notice() {
		/* Skip requests */
		if ( 'edit.php' !== $GLOBALS['pagenow'] || 'snitch' !== $GLOBALS['typenow'] || empty( $_GET['updated'] ) ) {
			return;
		}

		/* Print */
		$update_message = $_GET['updated'] > 0
			? __( 'New rule added to the Snitch filter. Matches are labeled in red.', 'snitch' )
			: __( 'An existing rule removed from the Snitch filter.', 'snitch' );
		echo sprintf(
			'<div class="updated"><p>%s</p></div>',
			esc_html( $update_message )
		);
	}

	/**
	 * Aktuelle Seitennummer der CPT-Ansicht
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 *
	 * @return  integer  void  Ermittelte Seitennummer
	 */
	private static function _get_pagenum() {
		return ( empty( $GLOBALS['pagenum'] ) ? _get_list_table( 'WP_Posts_List_Table' )->get_pagenum() : $GLOBALS['pagenum'] );
	}

	/**
	 * Rückgabe eines Custom Fields
	 *
	 * @since   1.0.2
	 * @change  1.0.2
	 *
	 * @param   integer $post_id  Post-ID.
	 * @param   string  $key      Key des Fields.
	 * @return  mixed    void      Wert des Fields
	 */
	private static function _get_meta( $post_id, $key ) {
		$value = get_post_meta( $post_id, '_snitch_' . $key, true );
		if ( $value ) {
			return $value;
		}

		return get_post_meta( $post_id, $key, true );
	}

	/**
	 * Erweitert die sekundäre Links-Leiste
	 *
	 * @since   0.0.4
	 * @change  1.1.5
	 *
	 * @param   array $views  Array mit verfügbaren Links.
	 * @return  array  $views  Array mit modifizierten Links
	 */
	public static function views_edit( $views ) {
		$links = array(
			'paypal' => '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TD4AMD2D8EMZW" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Donate', 'snitch' ) . '</a>',
			'wiki' => '<a href="https://github.com/pluginkollektiv/snitch/wiki" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Manual', 'snitch' ) . '</a>',
			'support' => '<a href="https://wordpress.org/support/plugin/snitch" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support', 'snitch' ) . '</a>',
		);

		return $links;
	}

	/**
	 * Bereinigt die Datenbank durch Löschung älterer Einträge
	 *
	 * @since   1.0.5
	 * @change  1.0.6
	 *
	 * @hook    integer  snitch_cleanup_items
	 */
	public static function cleanup_items() {
		self::delete_items(
			(int) apply_filters(
				'snitch_cleanup_items',
				200
			)
		);
	}

	/**
	 * Löscht Einträge in der Datenbank
	 *
	 * @since   1.0.3
	 * @change  1.0.6
	 *
	 * @param   integer $offset  Versatz für DELETE [optional].
	 */
	public static function delete_items( $offset = 0 ) {
		/* Convert */
		$offset = (int) $offset;

		/* WTF? */
		if ( $offset < 0 ) {
			return;
		}

		/* Global */
		global $wpdb;

		/* Select query (with official offset fix) */
		$subquery = sprintf(
			"SELECT * FROM ( SELECT `ID` FROM `$wpdb->posts` WHERE `post_type` = 'snitch' ORDER BY `ID` DESC LIMIT %d, 18446744073709551615 ) as t",
			$offset
		);

		/* Delete postmeta */
		$wpdb->query(
			sprintf(
				"DELETE FROM `$wpdb->postmeta` WHERE `post_id` IN (%s)",
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- allow sub query.
				$subquery
			)
		);

		/* Delete posts */
		$wpdb->query(
			sprintf(
				"DELETE FROM `$wpdb->posts` WHERE `ID` IN (%s)",
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- allow sub query.
				$subquery
			)
		);
	}

	/**
	 * Prüfung auf den aktuellen Screen
	 *
	 * @since   1.0.4
	 * @change  1.0.4
	 *
	 * @param   integer $id   Screen-ID.
	 * @return  boolean  void  TRUE bei Erfolg
	 */
	private static function _current_screen( $id ) {
		$screen = get_current_screen();

		return ( is_object( $screen ) && $screen->id === $id );
	}

	/**
	 * Default row actions unterdrücken
	 *
	 * @since   1.1.7
	 * @param   array  $actions  Array mit den default Aktionen auf ein Post (Edit, Quickedit, löschen).
	 * @param   object $post the post.
	 * @return  array   $actions
	 */
	public static function row_actions( $actions, $post ) {
		if ( 'snitch' === $post->post_type ) {
			return array();
		}
		return $actions;
	}
}
