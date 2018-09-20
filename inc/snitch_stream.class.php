<?php

/* Quit */
defined('ABSPATH') OR exit;


/**
 * Snitch_Stream
 *
 * @since 1.1.8
 *
 * @todo settingsfield in stream to activate/deactivate the logging of Snitchevents in stream
 * 
 */

class Snitch_Stream  extends \WP_Stream\Connector {

	/**
	 * Actions registered for this connector
	 *
	 * These are actions that My Plugin has created, we are defining them here to
	 * tell Stream to run a callback each time this action is fired so we can
	 * log information about what happened.
	 *
	 * @var array
	 */
	public $actions = array(
		'snitch_connection_accepted',
	);

	/**
	 * The minimum version required for My Plugin
	 *
	 * @const string
	 */
	const PLUGIN_MIN_VERSION = '1.1.8';

	/**
	 * Display an admin notice if plugin dependencies are not satisfied
	 *
	 * If My Plugin does not have the minimum required version number specified
	 * in the constant above, then Stream will display an admin notice for us.
	 *
	 * @return bool
	 */
	public function is_dependency_satisfied() {
		return true;
		$version_compare = version_compare( My_Plugin_Class::$version, self::PLUGIN_MIN_VERSION, '>=' );
		if ( class_exists( 'Snitch' ) && $version_compare ) {
			return true;
		}

		return false;
	}

	/**
	 * Return translated connector label
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Snitch', 'snitch' );
	}

	/**
	 * Return translated context labels
	 *
	 * @return array
	 */
	public function get_context_labels() {
		return array(
			'snitch_connection_accepted' => __( 'Accepted', 'snitch' ),
		);
	}

	/**
	 * Return translated action labels
	 *
	 * @return array
	 */
	public function get_action_labels() {
		return array(
			'Accepted' => __( 'Accepted', 'snitch' ),
		);
	}


	/**
	 * Track create and update actions on Foos
	 *
	 * @param array $post_id
	 *
	 * @return void
	 */
	public function callback_snitch_connection_accepted( $post_id ) {
		$this->log(
		// Summary message
			sprintf(
				__( '"%1$s"  %2$s', 'snitch' ),
				get_post_meta( $post_id, '_snitch_url', true )  ,
				__( 'Accepted', 'snitch' )
			),
			// This array is compacted and saved as Stream meta
			array(
				'action' => __( 'Accepted', 'snitch' ),
				'id'     => $post_id,
				'title'  => get_post_meta( $post_id, '_snitch_url', true ),
			),
			$post_id, // Object ID
			'', // Context
			__( 'Accepted', 'snitch' )
		);
	}

}