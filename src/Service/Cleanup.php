<?php

namespace AboveTheFoldTracker\Service;

class Cleanup {

	/**
	 * Init.
	 */
	public static function init() {
		if ( ! wp_next_scheduled( 'above_fold_cleanup' ) ) {
			wp_schedule_event( time(), 'daily', 'above_fold_cleanup' );
		}

		add_action( 'above_fold_cleanup', array( self::class, 'run' ) );
	}

	/**
	 * Deletes above-the-fold data older than 7 days.
	 */
	public static function run() {
		global $wpdb;

		$table = $wpdb->prefix . 'above_fold_data';

		$sql            = "DELETE FROM {$table} WHERE timestamp < NOW() - INTERVAL %d DAY";
		$prepared_query = $wpdb->prepare( $sql, 7 );

		$wpdb->query( $prepared_query );
	}

	/**
	 * Deactivate.
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'above_fold_cleanup' );
	}
}
