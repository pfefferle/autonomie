<?php
/**
 * Poststats Block - Server-side Render
 *
 * @package Autonomie
 *
 * @var array $attributes Block attributes.
 */

$poststats_first = autonomie_poststats_get_first_post();

if ( ! $poststats_first ) {
	return;
}

$poststats_today  = new DateTimeImmutable( 'today', wp_timezone() );
$poststats_counts = autonomie_postgraph_get_post_counts( array( 'post' ), get_the_date( 'Y-m-d', $poststats_first ), $poststats_today->format( 'Y-m-d' ) );
$poststats_year   = $poststats_today->modify( '-1 year' )->format( 'Y-m-d' );
$poststats_recent = 0;

foreach ( $poststats_counts as $poststats_date => $poststats_count ) {
	if ( $poststats_date > $poststats_year ) {
		$poststats_recent += $poststats_count;
	}
}

$poststats_total     = (int) wp_count_posts( 'post' )->publish;
$poststats_years     = (int) $poststats_today->diff( new DateTimeImmutable( get_the_date( 'Y-m-d', $poststats_first ), wp_timezone() ) )->y;
$poststats_reactions = autonomie_poststats_get_comment_type_counts();
$poststats_streaks   = autonomie_poststats_get_streaks( $poststats_counts );
$poststats_tags      = autonomie_poststats_get_trending_tags();

$poststats_range = function ( $streak ) {
	return sprintf(
		/* translators: 1: Start date, 2: End date. */
		__( '%1$s – %2$s', 'autonomie' ),
		wp_date( get_option( 'date_format' ), $streak['start']->getTimestamp() ),
		wp_date( get_option( 'date_format' ), $streak['end']->getTimestamp() )
	);
};
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<ul class="poststats__numbers">
		<li>
			<strong><?php echo esc_html( number_format_i18n( $poststats_recent ) ); ?></strong>
			<span>
				<?php
				printf(
					/* translators: %s: Posts per day. */
					esc_html( _n( 'post in the last year, %s per day', 'posts in the last year, %s per day', $poststats_recent, 'autonomie' ) ),
					esc_html( number_format_i18n( $poststats_recent / 365, 2 ) )
				);
				?>
			</span>
		</li>
		<li>
			<strong><?php echo esc_html( number_format_i18n( $poststats_total ) ); ?></strong>
			<span>
				<?php
				$poststats_since = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $poststats_first ) ), esc_html( get_the_date( '', $poststats_first ) ) );

				if ( $poststats_years ) {
					printf(
						/* translators: 1: Linked date of the first post, 2: Number of years. */
						esc_html( _n( 'posts since %1$s, %2$s year', 'posts since %1$s, %2$s years', $poststats_years, 'autonomie' ) ),
						$poststats_since, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
						esc_html( number_format_i18n( $poststats_years ) )
					);
				} else {
					/* translators: %s: Linked date of the first post. */
					printf( esc_html__( 'posts since %s', 'autonomie' ), $poststats_since ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
				}
				?>
			</span>
		</li>
		<li>
			<strong><?php echo esc_html( number_format_i18n( array_sum( $poststats_reactions ) ) ); ?></strong>
			<span><?php echo esc_html( _n( 'reaction', 'reactions', array_sum( $poststats_reactions ), 'autonomie' ) ); ?></span>
		</li>
		<li>
			<strong><?php echo esc_html( number_format_i18n( $poststats_streaks['current'] ? $poststats_streaks['current']['days'] : 0 ) ); ?></strong>
			<span><?php echo esc_html( _n( 'day posting streak', 'days posting streak', $poststats_streaks['current'] ? $poststats_streaks['current']['days'] : 0, 'autonomie' ) ); ?></span>
		</li>
	</ul>

	<?php if ( $poststats_reactions ) : ?>
		<h2 class="wp-block-heading"><?php esc_html_e( 'Reactions', 'autonomie' ); ?></h2>
		<ul class="poststats__list">
			<?php foreach ( $poststats_reactions as $poststats_type => $poststats_count ) : ?>
				<li><span><?php echo esc_html( autonomie_poststats_get_comment_type_label( $poststats_type ) ); ?></span> <span><?php echo esc_html( number_format_i18n( $poststats_count ) ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $poststats_streaks['longest'] ) : ?>
		<h2 class="wp-block-heading"><?php esc_html_e( 'Longest streaks', 'autonomie' ); ?></h2>
		<ol class="poststats__list">
			<?php foreach ( $poststats_streaks['longest'] as $poststats_streak ) : ?>
				<li>
					<span>
						<?php
						/* translators: %s: Number of days. */
						echo esc_html( sprintf( _n( '%s day', '%s days', $poststats_streak['days'], 'autonomie' ), number_format_i18n( $poststats_streak['days'] ) ) );
						?>
					</span>
					<span><?php echo esc_html( $poststats_range( $poststats_streak ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>

	<?php if ( $poststats_tags ) : ?>
		<h2 class="wp-block-heading"><?php esc_html_e( 'Trending tags of the last 30 days', 'autonomie' ); ?></h2>
		<ul class="poststats__list">
			<?php foreach ( $poststats_tags as $poststats_tag ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $poststats_tag['term'] ) ); ?>"><?php echo esc_html( $poststats_tag['term']->name ); ?></a> <span><?php echo esc_html( number_format_i18n( $poststats_tag['count'] ) ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
