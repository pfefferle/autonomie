<?php if ( zenpress_show_page_banner() ) : ?>
<div class="page-banner">
	<?php if ( ! is_singular() ) : ?>
	<div class="page-branding">
		<?php if ( zenpress_get_the_archive_title() ) { ?>
		<h1 id="page-title"<?php zenpress_semantics( 'page-title' ); ?>><?php echo zenpress_get_the_archive_title(); ?></h1>
		<?php } ?>
		<?php if ( zenpress_get_the_archive_description() ) { ?>
		<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php echo zenpress_get_the_archive_description(); ?></div>
		<?php } ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>
