<?php

namespace AboveTheFoldTracker\Service;

class AdminPage {

	/**
	 * Render the admin table page.
	 */
	public static function render() {
		include plugin_dir_path( dirname( __DIR__ ) ) . 'View/admin-display.php';
	}
}
