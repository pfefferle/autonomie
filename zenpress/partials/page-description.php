<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php
if ( is_home() ) {
	bloginfo( 'description' );
} elseif ( is_author() ) {
	echo get_the_author_meta( 'description' );
} elseif ( is_archive() ) {
	the_archive_description();
}
?></div>
