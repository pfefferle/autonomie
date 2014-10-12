<?php
/**
 * @package ZenPress
 * @since ZenPress 1.0.0
 */
?>
<article <?php zenpress_post_id(); ?> <?php post_class(); ?><?php zenpress_semantics("post"); ?>>
  <header class="entry-header">
    <h1 class="entry-title p-name" itemprop="name headline"><a href="<?php the_permalink(); ?>" class="u-url url" title="<?php printf( esc_attr__( 'Permalink to %s', 'zenpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>

    <div class="entry-meta">
      <?php zenpress_posted_on(); ?>
    </div><!-- .entry-meta -->
  </header><!-- .entry-header -->

  <?php if ( is_search() ) : // Only display Excerpts for Search ?>
  <div class="entry-summary p-summary" itemprop="description">
    <?php the_excerpt(); ?>
  </div><!-- .entry-summary -->
  <?php else : ?>
  <?php zenpress_the_post_thumbnail('<div class="entry-media">', '</div>'); ?>
  <div class="entry-content e-content" itemprop="description articleBody">
    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'zenpress' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'zenpress' ), 'after' => '</div>' ) ); ?>
  </div><!-- .entry-content -->
  <?php endif; ?>

  <?php get_template_part( 'entry', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->
