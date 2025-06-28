
# Explanation.md

## Problem Statement

The client wants to track Above the Fold (ATF) content on their WordPress site. Specifically, they need to:
- Collect data on which links and assets are loaded in the above-the-fold area.
- Store this data in a way that is accessible and manageable.
- Display this information in the WordPress admin dashboard.
- Allow easy removal of old data to avoid performance issues.

## Technical Specification and How It Works

This plugin, `above-the-fold-tracker`, solves the problem using the following components:

### 1. **Tracker Endpoint (`TrackerEndpoint.php`)**
- Registers a REST API endpoint at `/wp-json/above-fold-tracker/v1/track`.
- When called, it receives data (e.g., timestamp, URL, user agent, list of above-the-fold links).
- It stores this data in a custom database table (`wp_above_fold_data`).

### 2. **Admin Page Display (`AdminPage.php` and `admin-display.php`)**
- Adds a menu item to the WordPress admin sidebar.
- Loads a custom page that queries the last 50 entries from the `above_fold_data` table.
- Displays those entries in a readable HTML format using decoded link data.

### 3. **Automatic Cleanup (`Cleanup.php`)**
- Schedules a daily cron job (`above_fold_cleanup`) using `wp_schedule_event`.
- Deletes entries older than 7 days from the database using a `DELETE FROM ... WHERE timestamp < ...` query.
- Cleans up scheduled events on plugin deactivation.

## Technical Decisions and Why

### Custom Table
- Using a custom table instead of post meta or options ensures structured, fast access to the data and avoids bloating core tables.

### REST API
- Chose a REST API over admin-ajax for better decoupling and modern best practices.
- This allows external JS snippets or browser plugins to send data easily.

### Cron Cleanup
- WordPress doesn’t clean up plugin data by default, so a scheduled event was added to clean outdated tracking entries.
- This prevents unnecessary database growth over time.

### View Template
- Used a basic view (`admin-display.php`) to separate logic from presentation, which helps keep things readable and maintainable.

## How This Solves the User Story

**User Story**:  
_As a site admin, I want to monitor the above-the-fold links loaded on my site so I can optimize page speed and understand what users see first._

**Solution**:
- The plugin tracks above-the-fold content using a dedicated API endpoint.
- Stores the data in a structured table.
- Allows the admin to view the last entries from the admin panel.
- Automatically removes old data, avoiding clutter and performance issues.

The plugin is easy to use, lightweight, and integrates directly with WordPress' admin interface. No technical knowledge is needed to access the tracking data.

## Developer Notes and Code Style

- The plugin follows WordPress coding standards and is PSR-4 autoloadable.
- Used `namespace AboveTheFoldTracker\Service;` and proper class structures.
- Registered activation and deactivation hooks.

## PHPCS Errors and Warnings

The plugin has been checked using `phpcs` with the `phpcs.xml.dist` standard. Despite efforts to fix most issues, a few warnings and errors remain:

### Remaining Warnings and Errors:
- **Use of direct database queries**: WordPress discourages this, but `prepare()` is used to sanitize inputs where appropriate.
- **No caching on direct queries**: For real-time admin display, we opted for uncached queries to always show the latest data.
- **Yoda conditions**: One Yoda condition error remains for simplicity/readability.
- **Deprecation notices in `phpcs.xml.dist`**: Legacy comma-separated properties for `text_domain` and `prefixes`. These are minor config-level issues and don't affect functionality.

These remaining issues were deemed acceptable given the scope and context of the plugin.

## Final Thoughts

This plugin is designed to be minimal, understandable, and functional. It avoids overengineering while still following most WordPress development best practices. The choice of a REST API and automatic cleanup ensures it scales reasonably without needing much maintenance.
