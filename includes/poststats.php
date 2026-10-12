<?php
/**
 * Poststats block helpers: key numbers, reactions, streaks and trending tags.
 *
 * @package Autonomie
 */

/**
 * Get the first published post.
 *
 * @return WP_Post|null The post, or null if there is none.
 */
function autonomie_poststats_get_first_post() {
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'posts_per_page' => 1,
		)
	);

	return $posts ? $posts[0] : null;
}

/**
 * Get the label of a comment type.
 *
 * Uses the labels the ActivityPub and Webmention plugins register for their types.
 *
 * @param string $type The comment type.
 *
 * @return string The (plural) label.
 */
function autonomie_poststats_get_comment_type_label( $type ) {
	global $activitypub_comment_types, $webmention_comment_types;

	foreach ( array( $activitypub_comment_types, $webmention_comment_types ) as $types ) {
		if ( isset( $types[ $type ]['label'] ) ) {
			return $types[ $type ]['label'];
		}
	}

	$labels = array(
		'comment'    => __( 'Comments', 'autonomie' ),
		'pingback'   => __( 'Pingbacks', 'autonomie' ),
		'trackback'  => __( 'Trackbacks', 'autonomie' ),
		'webmention' => __( 'Webmentions', 'autonomie' ),
		'like'       => __( 'Likes', 'autonomie' ),
		'repost'     => __( 'Reposts', 'autonomie' ),
		'quote'      => __( 'Quotes', 'autonomie' ),
		'favorite'   => __( 'Favorites', 'autonomie' ),
	);

	return $labels[ $type ] ?? ucfirst( $type );
}

/**
 * Get the number of approved comments per comment type.
 *
 * @return int[] Counts keyed by comment type, highest first.
 */
function autonomie_poststats_get_comment_type_counts() {
	global $wpdb;

	$cache_key = 'comment_types:' . wp_cache_get_last_changed( 'comment' );
	$counts    = wp_cache_get( $cache_key, 'autonomie_poststats' );

	if ( false !== $counts ) {
		return $counts;
	}

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$rows = $wpdb->get_results( "SELECT comment_type, COUNT(comment_ID) AS total FROM {$wpdb->comments} WHERE comment_approved = '1' GROUP BY comment_type" );

	$counts = array();
	foreach ( $rows as $row ) {
		// Old comments have an empty type.
		$type            = $row->comment_type ? $row->comment_type : 'comment';
		$counts[ $type ] = ( $counts[ $type ] ?? 0 ) + (int) $row->total;
	}

	arsort( $counts );

	wp_cache_set( $cache_key, $counts, 'autonomie_poststats', DAY_IN_SECONDS );

	return $counts;
}

/**
 * Get the daily posting streaks.
 *
 * A streak is a run of consecutive days with at least one published post.
 *
 * @param int[] $counts Post counts keyed by date (Y-m-d).
 *
 * @return array {
 *     @type array|null $current The streak that ends today or yesterday, with `days`, `start` and `end`.
 *     @type array[]    $longest The three longest streaks of at least two days, with `days`, `start` and `end`.
 * }
 */
function autonomie_poststats_get_streaks( $counts ) {
	$streaks = array();
	$streak  = null;

	ksort( $counts );

	foreach ( array_keys( $counts ) as $date ) {
		$day = new DateTimeImmutable( $date, wp_timezone() );

		if ( $streak && $streak['end']->modify( '+1 day' ) == $day ) { // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual -- Compares the dates, not the objects.
			$streak['end'] = $day;
			++$streak['days'];
			continue;
		}

		if ( $streak ) {
			$streaks[] = $streak;
		}

		$streak = array(
			'days'  => 1,
			'start' => $day,
			'end'   => $day,
		);
	}

	if ( $streak ) {
		$streaks[] = $streak;
	}

	$today   = new DateTimeImmutable( 'today', wp_timezone() );
	$current = null;

	// Today's post may still be coming, so a streak that ended yesterday still counts.
	if ( $streaks && end( $streaks )['end'] >= $today->modify( '-1 day' ) ) {
		$current = end( $streaks );
	}

	usort(
		$streaks,
		function ( $a, $b ) {
			return $b['days'] <=> $a['days'];
		}
	);

	$streaks = array_filter(
		$streaks,
		function ( $streak ) {
			return $streak['days'] > 1;
		}
	);

	return array(
		'current' => $current,
		'longest' => array_slice( $streaks, 0, 3 ),
	);
}

/**
 * Get the most used tags of the last days.
 *
 * @param int $days  Number of days.
 * @param int $limit Number of tags.
 *
 * @return array[] List of tags with `term` (WP_Term) and `count`.
 */
function autonomie_poststats_get_trending_tags( $days = 30, $limit = 10 ) {
	global $wpdb;

	$since     = wp_date( 'Y-m-d 00:00:00', strtotime( '-' . (int) $days . ' days' ) );
	$cache_key = 'trending:' . md5( $since . $limit ) . ':' . wp_cache_get_last_changed( 'posts' ) . ':' . wp_cache_get_last_changed( 'terms' );
	$rows      = wp_cache_get( $cache_key, 'autonomie_poststats' );

	if ( false === $rows ) {
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT tt.term_id, COUNT(p.ID) AS total
				FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE tt.taxonomy = 'post_tag'
				AND p.post_type = 'post'
				AND p.post_status = 'publish'
				AND p.post_date >= %s
				GROUP BY tt.term_id
				ORDER BY total DESC
				LIMIT %d",
				$since,
				$limit
			)
		);
		// phpcs:enable

		wp_cache_set( $cache_key, $rows, 'autonomie_poststats', DAY_IN_SECONDS );
	}

	$tags = array();
	foreach ( $rows as $row ) {
		$term = get_term( (int) $row->term_id, 'post_tag' );

		if ( $term instanceof WP_Term ) {
			$tags[] = array(
				'term'  => $term,
				'count' => (int) $row->total,
			);
		}
	}

	return $tags;
}
