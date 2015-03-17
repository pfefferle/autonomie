  <header class="entry-header">
    <div class="entry-meta">
      <span class="post-format">
        <a class="entry-format entry-format-<?php echo get_post_format(); ?>" href="<?php echo esc_url( get_post_format_link(get_post_format()) ); ?>"><?php echo get_post_format_string(get_post_format()); ?></a>
      </span>
    </div>

    <?php if (!in_array(get_post_format(), array("aside", "quote", "link", "status"))) : ?>
    <h1 class="entry-title p-name" itemprop="name headline"><a href="<?php the_permalink(); ?>" class="u-url url" title="<?php printf( esc_attr__( 'Permalink to %s', 'zenpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>

    <div class="entry-meta">
      <?php zenpress_posted_on(); ?>
    </div><!-- .entry-meta -->
    <?php endif; ?>
  </header><!-- .entry-header -->
