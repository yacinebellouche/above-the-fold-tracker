<?php

namespace AboveTheFoldTracker\Service;

class TrackerEndpoint {

	/**
	 * Create Table.
	 */
	public static function create_table() {
		global $wpdb;
		$table           = $wpdb->prefix . 'above_fold_data';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            screen_width INT,
            screen_height INT,
            links TEXT
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Register Hooks.
	 */
	public static function register_hooks() {
		add_action( 'wp_ajax_nopriv_above_fold_track', array( self::class, 'handle' ) );
		add_action( 'wp_ajax_above_fold_track', array( self::class, 'handle' ) );
	}

	/**
	 * Handle function.
	 */
	public static function handle() {
		check_ajax_referer( 'above_fold_nonce', 'nonce' );

		$screen_width  = isset( $_POST['screen_width'] ) ? intval( $_POST['screen_width'] ) : 0;
		$screen_height = isset( $_POST['screen_height'] ) ? intval( $_POST['screen_height'] ) : 0;

		$raw_links = isset( $_POST['links'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['links'] ) ) ) : array();

		if ( is_string( $raw_links ) ) {
			$raw_links = explode( ',', $raw_links );
		}

		$links      = array_map( 'sanitize_text_field', $raw_links );
		$links_json = wp_json_encode( $links );

		global $wpdb;
		$wpdb->insert(
			$wpdb->prefix . 'above_fold_data',
			array(
				'screen_width'  => $screen_width,
				'screen_height' => $screen_height,
				'links'         => $links_json,
			)
		);

		wp_send_json_success( 'Tracked' );
	}
}
