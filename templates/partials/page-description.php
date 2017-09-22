<?php if ( is_home() ) { ?>
	<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php bloginfo( 'description' ); ?></div>
<?php } elseif ( is_author() ) { ?>
	<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php echo get_the_author_meta( 'description' ); ?></div>
<?php } elseif ( is_archive() ) { ?>
	<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php the_archive_description(); ?></div>
<?php }
