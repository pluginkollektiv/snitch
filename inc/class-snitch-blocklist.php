<?php
/**
 * Snitch_Blacklist class for updating the settings which types are blocked.
 *
 * @package Snitch
 */

/* Quit */
defined( 'ABSPATH' ) || exit;

/**
 * Snitch_Blacklist
 *
 * @since 0.0.1
 */
class Snitch_Blocklist {
	/**
	 * Blockiert Einträge eines bestimmten Typs
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 *
	 * @param   array  $items  Array mit Einträgen.
	 * @param   string $type  Typ des Eintrags (hosts|files).
	 */
	public static function block( $items, $type ) {
		 /* Type check */
		if ( ! in_array( $type, array( 'hosts', 'files' ) ) ) {
			return;
		}

		/* Add items */
		Snitch::update_options(
			$type,
			array_unique(
				array_merge(
					Snitch::get_options( $type ),
					$items
				)
			)
		);
	}

	/**
	 * Gibt Einträge eines bestimmten Typs frei
	 *
	 * @since   0.0.1
	 * @change  0.0.1
	 *
	 * @param   array  $items  Array mit Einträgen.
	 * @param   string $type  Typ des Eintrags (hosts|files).
	 */
	public static function unblock( $items, $type ) {
		/* Type check */
		if ( ! in_array( $type, array( 'hosts', 'files' ) ) ) {
			return;
		}

		/* Get options */
		$options = Snitch::get_options( $type );

		/* Convert */
		$items = (array) $items;

		/* Loop items */
		foreach ( $items as $item ) {
			$key = array_search( $item, $options );

			if ( false !== $key ) {
				unset( $options[ $key ] );
			}
		}

		/* Update options */
		Snitch::update_options( $type, $options );
	}
}
