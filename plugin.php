<?php

/**
 * Plugin Name: Above The Fold Tracker
 * Description: Tracks which homepage hyperlinks were seen above the fold by anonymous visitors in the last 7 days.
 * Version: 1.0.0
 * Author: Yacine Bellouche
 */

use AboveTheFoldTracker\Service\TrackerEndpoint;
use AboveTheFoldTracker\Service\AdminPage;
use AboveTheFoldTracker\Service\Cleanup;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

// Register activation and deactivation hooks.
register_activation_hook(__FILE__, array(TrackerEndpoint::class, 'create_table'));
register_deactivation_hook(__FILE__, array(Cleanup::class, 'deactivate'));

// Init
add_action(
	'init',
	function () {
		Cleanup::init();
		TrackerEndpoint::register_hooks();
	}
);

// Admin menu.
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'Above The Fold Data',
			'Above The Fold',
			'manage_options',
			'above-the-fold',
			array(AdminPage::class, 'render')
		);
	}
);

// Enqueue JS on homepage for visitors only.
add_action(
	'wp_enqueue_scripts',
	function () {
		if (is_front_page() && ! is_user_logged_in()) {
			wp_enqueue_script(
				'above-fold-js',
				plugin_dir_url(__FILE__) . 'src/assets/js/track-links.js',
				array(),
				null,
				true
			);
			wp_localize_script(
				'above-fold-js',
				'AboveFoldTracker',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce'    => wp_create_nonce('above_fold_nonce'),
				)
			);
		}
	}
);
