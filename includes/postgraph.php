<?php
/**
 * Postgraph block helpers, a GitHub-style contribution graph of the posts per day.
 *
 * @package Autonomie
 */

/**
 * Get the public post types a graph can show.
 *
 * Attachments are left out, they are never published on their own.
 *
 * @return string[] Post type names.
 */
function autonomie_postgraph_get_public_post_types() {
	$post_types = get_post_types( array( 'public' => true ) );
	unset( $post_types['attachment'] );

	return array_values( $post_types );
}

/**
 * Resolve the `postType` block attribute to a list of post types.
 *
 * @param string $post_type A public post type, or `any` for all public post types.
 *
 * @return string[] Post type names. Falls back to `post` for unknown or non-public types.
 */
function autonomie_postgraph_resolve_post_types( $post_type ) {
	$public = autonomie_postgraph_get_public_post_types();

	if ( 'any' === $post_type ) {
		return $public;
	}

	return in_array( $post_type, $public, true ) ? array( $post_type ) : array( 'post' );
}

/**
 * Get the date archive URL of a day for the given post types.
 *
 * Only post types that can be queried publicly show up in date archives, so no
 * URL is returned if one of them cannot, otherwise the archive would list fewer
 * posts than the graph counts.
 *
 * @param DateTimeInterface $date       The day.
 * @param string[]           $post_types Post type names.
 *
 * @return string The URL, or an empty string if the day can not be linked.
 */
function autonomie_postgraph_get_day_url( $date, $post_types ) {
	foreach ( $post_types as $post_type ) {
		if ( ! get_post_type_object( $post_type )->publicly_queryable ) {
			return '';
		}
	}

	$url = get_day_link( $date->format( 'Y' ), $date->format( 'm' ), $date->format( 'd' ) );

	if ( array( 'post' ) !== $post_types ) {
		$url = add_query_arg( 'post_type', count( $post_types ) > 1 ? $post_types : $post_types[0], $url );
	}

	return $url;
}

/**
 * Get the number of published posts per day.
 *
 * Dates are compared against `post_date`, so days follow the site's timezone.
 *
 * @param string|string[] $post_types One or more post types.
 * @param string          $start      First day (Y-m-d), inclusive.
 * @param string          $end        Last day (Y-m-d), inclusive.
 *
 * @return int[] Post counts keyed by date (Y-m-d).
 */
function autonomie_postgraph_get_post_counts( $post_types, $start, $end ) {
	global $wpdb;

	$post_types = array_values( array_unique( (array) $post_types ) );
	sort( $post_types );

	if ( ! $post_types ) {
		return array();
	}

	$cache_key = 'autonomie_postgraph:' . md5( implode( ',', $post_types ) . $start . $end ) . ':' . wp_cache_get_last_changed( 'posts' );
	$counts    = wp_cache_get( $cache_key, 'autonomie_postgraph' );

	if ( false !== $counts ) {
		return $counts;
	}

	$placeholders = implode( ', ', array_fill( 0, count( $post_types ), '%s' ) );

	// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT DATE(post_date) AS day, COUNT(ID) AS total
			FROM {$wpdb->posts}
			WHERE post_type IN ( {$placeholders} )
			AND post_status = 'publish'
			AND post_date >= %s
			AND post_date < %s
			GROUP BY DATE(post_date)",
			array_merge(
				$post_types,
				array(
					$start . ' 00:00:00',
					gmdate( 'Y-m-d', strtotime( $end . ' +1 day' ) ) . ' 00:00:00',
				)
			)
		)
	);
	// phpcs:enable

	$counts = array();
	foreach ( $rows as $row ) {
		$counts[ $row->day ] = (int) $row->total;
	}

	wp_cache_set( $cache_key, $counts, 'autonomie_postgraph', DAY_IN_SECONDS );

	return $counts;
}

/**
 * Map a post count to one of five intensity levels (0-4).
 *
 * @param int $count The post count of a day.
 * @param int $max   The highest post count in the range.
 *
 * @return int The level.
 */
function autonomie_postgraph_get_level( $count, $max ) {
	if ( $count < 1 || $max < 1 ) {
		return 0;
	}

	return (int) min( 4, ceil( $count / $max * 4 ) );
}

/**
 * Build the graph data.
 *
 * The grid always starts on the site's first day of the week and ends today,
 * so the first column may start before the requested range, like on GitHub.
 *
 * @param int             $weeks      Number of weeks (columns).
 * @param string|string[] $post_types One or more post types.
 *
 * @return array {
 *     @type array[] $days   List of days with `date`, `count` and `level`.
 *     @type array[] $months List of month labels with `column` and `label`.
 *     @type int     $total  Total number of posts in the range.
 *     @type int     $weeks  Number of weeks (columns).
 * }
 */
function autonomie_postgraph_get_graph_data( $weeks, $post_types ) {
	$weeks = max( 1, min( 104, (int) $weeks ) );
	$tz    = wp_timezone();
	$today = new DateTimeImmutable( 'today', $tz );

	// Walk back to the first day of the current week, then back the remaining weeks.
	$offset = ( (int) $today->format( 'w' ) - (int) get_option( 'start_of_week', 1 ) + 7 ) % 7;
	$start  = $today->modify( '-' . ( $offset + ( $weeks - 1 ) * 7 ) . ' days' );

	$counts = autonomie_postgraph_get_post_counts( $post_types, $start->format( 'Y-m-d' ), $today->format( 'Y-m-d' ) );
	$max    = $counts ? max( $counts ) : 0;

	$days   = array();
	$months = array();
	$total  = 0;
	$day    = $start;
	$index  = 0;

	while ( $day <= $today ) {
		$date   = $day->format( 'Y-m-d' );
		$count  = $counts[ $date ] ?? 0;
		$total += $count;

		$days[] = array(
			'date'  => $day,
			'count' => $count,
			'level' => autonomie_postgraph_get_level( $count, $max ),
		);

		// Label a month in the first column that contains its first day.
		if ( '01' === $day->format( 'd' ) ) {
			$months[] = array(
				'column' => intdiv( $index, 7 ) + 1,
				'label'  => wp_date( 'M', $day->getTimestamp(), $tz ),
			);
		}

		$day = $day->modify( '+1 day' );
		++$index;
	}

	return array(
		'days'   => $days,
		'months' => $months,
		'total'  => $total,
		'weeks'  => $weeks,
	);
}
