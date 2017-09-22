<?php if ( is_archive() ) { ?>
	<h1 id="page-title"<?php zenpress_semantics( 'page-title' ); ?>><?php the_archive_title(); ?></h1>
<?php } elseif ( is_search() ) { ?>
	<h1 id="page-title"<?php zenpress_semantics( 'page-title' ); ?>><?php printf( __( 'Search Results for: %s', 'zenpress' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
<?php
}
