<h1 id="page-title"<?php zenpress_semantics( 'page-title' ); ?>><?php
if ( is_archive() ) {
	the_archive_title();
} elseif ( is_search() ) {
?>
	<?php printf( __( 'Search Results for: %s', 'zenpress' ), '<span>' . get_search_query() . '</span>' ); ?>
<?php
}
?></h1>
