<div class="page-banner">
	<div class="page-branding author p-author h-card">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 150 ); ?>
		<h1 id="page-title"<?php autonomie_semantics( 'page-title' ); ?>><?php echo get_the_author_meta( 'display_name' ); ?></h1>
		<div id="page-description"<?php autonomie_semantics( 'page-description' ); ?>><?php echo get_the_author_meta( 'description' ); ?></div>
		<div id="page-meta"><?php echo autonomie_get_archive_author_meta(); ?></div>
	</div>
</div>
