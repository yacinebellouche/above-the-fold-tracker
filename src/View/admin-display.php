<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wpdb;

$table    = $wpdb->prefix . 'above_fold_data';
$sql      = "SELECT * FROM $table WHERE timestamp > NOW() - INTERVAL %d DAY";
$prepared = $wpdb->prepare( $sql, 7 );

$cache_key = 'above_fold_tracker_display_results';
$results   = wp_cache_get( $cache_key );

if ( $results === false ) {
	$results = $wpdb->get_results( $prepared );
	wp_cache_set( $cache_key, $results );
}


?>

<div class="wrap">
	<h1>Above-the-Fold Link Views (Past 7 Days)</h1>
	<?php if ( empty( $above_the_fold_tracker__results ) ) : ?>
		<p>No data available for the past 7 days.</p>
	<?php else : ?>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Screen Size</th>
					<th>Links Seen Above the Fold</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $above_the_fold_tracker__results as $above_the_fold_tracker__entry ) : ?>
					<tr>
						<td><?php echo esc_html( $above_the_fold_tracker__entry->timestamp ); ?></td>
						<td><?php echo esc_html( $above_the_fold_tracker__entry->screen_width . ' x ' . $above_the_fold_tracker__entry->screen_height ); ?></td>
						<td>
							<div style="line-height: 1.8;">
								<?php
								$above_the_fold_tracker__decoded_links = json_decode( $above_the_fold_tracker__entry->links );
								if ( ! is_array( $above_the_fold_tracker__decoded_links ) ) {
									$above_the_fold_tracker__decoded_links = explode( ',', $above_the_fold_tracker__entry->links );
								}
								foreach ( $above_the_fold_tracker__decoded_links as $above_the_fold_tracker__link ) :
									?>
									<a href="<?php echo esc_url( $above_the_fold_tracker__link ); ?>" target="_blank" style="margin-right: 10px; display: inline-block;">
										<?php echo esc_html( $above_the_fold_tracker__link ); ?>
									</a><br>
								<?php endforeach; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>