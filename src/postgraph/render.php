<?php
/**
 * Postgraph Block - Server-side Render
 *
 * @package Autonomie
 *
 * @var array $attributes Block attributes.
 */

$postgraph_post_types = autonomie_postgraph_resolve_post_types( $attributes['postType'] );
$postgraph_data       = autonomie_postgraph_get_graph_data( $attributes['weeks'], $postgraph_post_types );

$postgraph_label = sprintf(
	/* translators: %d: Number of weeks. */
	_n( 'Posts per day in the last %d week', 'Posts per day in the last %d weeks', $postgraph_data['weeks'], 'autonomie' ),
	$postgraph_data['weeks']
);

$postgraph_wrapper = get_block_wrapper_attributes(
	array(
		'style' => '--postgraph-weeks:' . $postgraph_data['weeks'] . ';',
	)
);
?>
<figure <?php echo $postgraph_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $attributes['showTotal'] ) : ?>
		<figcaption class="postgraph__total">
			<?php
			if ( 53 === $postgraph_data['weeks'] || 52 === $postgraph_data['weeks'] ) {
				/* translators: %s: Number of posts. */
				$postgraph_total = _n( '%s post in the last year', '%s posts in the last year', $postgraph_data['total'], 'autonomie' );
			} else {
				/* translators: %s: Number of posts. */
				$postgraph_total = _n( '%s post in this period', '%s posts in this period', $postgraph_data['total'], 'autonomie' );
			}
			echo esc_html( sprintf( $postgraph_total, number_format_i18n( $postgraph_data['total'] ) ) );
			?>
		</figcaption>
	<?php endif; ?>

	<div class="postgraph__body<?php echo $attributes['showMonths'] ? ' has-months' : ''; ?>">
		<?php if ( $attributes['showWeekdays'] ) : ?>
			<div class="postgraph__weekdays" aria-hidden="true">
				<?php
				global $wp_locale;
				$postgraph_start_of_week = (int) get_option( 'start_of_week', 1 );
				for ( $postgraph_row = 0; $postgraph_row < 7; $postgraph_row++ ) {
					$postgraph_weekday = ( $postgraph_start_of_week + $postgraph_row ) % 7;
					// Like GitHub, only label Monday, Wednesday and Friday.
					echo '<span>' . ( $postgraph_weekday % 2 ? esc_html( $wp_locale->get_weekday_abbrev( $wp_locale->get_weekday( $postgraph_weekday ) ) ) : '' ) . '</span>';
				}
				?>
			</div>
		<?php endif; ?>

		<div class="postgraph__scroll">
			<div class="postgraph__inner">
				<?php if ( $attributes['showMonths'] ) : ?>
					<div class="postgraph__months" aria-hidden="true">
						<?php foreach ( $postgraph_data['months'] as $postgraph_month ) : ?>
							<span style="grid-column:<?php echo (int) $postgraph_month['column']; ?>"><?php echo esc_html( $postgraph_month['label'] ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="postgraph__grid" role="group" aria-label="<?php echo esc_attr( $postgraph_label ); ?>">
					<?php
					foreach ( $postgraph_data['days'] as $postgraph_day ) {
						$postgraph_title = sprintf(
							/* translators: 1: Formatted date, 2: Number of posts. */
							_n( '%1$s: %2$s post', '%1$s: %2$s posts', $postgraph_day['count'], 'autonomie' ),
							wp_date( get_option( 'date_format' ), $postgraph_day['date']->getTimestamp() ),
							number_format_i18n( $postgraph_day['count'] )
						);

						$postgraph_url = $attributes['linkDays'] && $postgraph_day['count'] ? autonomie_postgraph_get_day_url( $postgraph_day['date'], $postgraph_post_types ) : '';

						if ( $postgraph_url ) {
							printf(
								'<a class="postgraph__day" data-level="%1$d" href="%2$s" title="%3$s" aria-label="%3$s"></a>',
								(int) $postgraph_day['level'],
								esc_url( $postgraph_url ),
								esc_attr( $postgraph_title )
							);
						} else {
							printf(
								'<span class="postgraph__day" data-level="%1$d" title="%2$s"></span>',
								(int) $postgraph_day['level'],
								esc_attr( $postgraph_title )
							);
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $attributes['showLegend'] ) : ?>
		<div class="postgraph__legend" aria-hidden="true">
			<span><?php esc_html_e( 'Less', 'autonomie' ); ?></span>
			<span class="postgraph__day" data-level="0"></span>
			<span class="postgraph__day" data-level="1"></span>
			<span class="postgraph__day" data-level="2"></span>
			<span class="postgraph__day" data-level="3"></span>
			<span class="postgraph__day" data-level="4"></span>
			<span><?php esc_html_e( 'More', 'autonomie' ); ?></span>
		</div>
	<?php endif; ?>
</figure>
