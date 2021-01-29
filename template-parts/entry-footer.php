<?php if ( is_singular() || is_attachment() ) : ?>
	<footer class="entry-footer entry-meta">
		<?php dynamic_sidebar( 'entry-meta' ); ?>
		<?php do_action( 'autonomie_entry_footer' ); ?>

		<?php // get_template_part( 'template-parts/entry', 'nav' ); ?>
	</footer><!-- #entry-meta -->
<?php else : ?>
	<footer class="entry-footer entry-meta">
		<?php if ( comments_open() || ( '0' !== get_comments_number() && ! comments_open() ) ) : ?>
		<indie-action do="reply" width="<?php the_permalink(); ?>"><div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'autonomie' ), __( '1 Comment', 'autonomie' ), __( '% Comments', 'autonomie' ) ); ?></div></indie-action>
		<?php endif; ?>
	</footer><!-- #entry-meta -->
<?php endif; ?>
