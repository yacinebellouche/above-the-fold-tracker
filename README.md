# Above The Fold Tracker

This WordPress plugin tracks which hyperlinks are visible above the fold on the homepage when a visitor lands. It captures screen size and link visibility and stores that data for 7 days. A dashboard page in the WordPress admin displays this data for analysis.

## Features

-  Detects visible links above the fold on homepage visits
-  Captures screen width and height
-  Only tracks anonymous (non-logged-in) users
-  Stores data securely in the database
-  Provides an admin dashboard to view tracked data
-  Automatically purges data older than 7 days
-  Built using a modern PSR-compliant plugin architecture

## Requirements

- PHP 7.3 or higher
- WordPress 6.0 or higher

## Installation

1. Clone or download this repository into `wp-content/plugins/above-the-fold-tracker`
2. Run `composer install` inside the plugin directory
3. Activate the plugin from your WordPress admin

## Development

- All PHP code is in `/src`
- JS scripts are in `/src/assets/js`
- Templates are in `/src/View`

## Author

Your Name – [yourwebsite.com](https://yourwebsite.com)
