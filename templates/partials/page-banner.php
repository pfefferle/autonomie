<?php if ( autonom_show_page_banner() ) : ?>
<div class="page-banner">
	<?php if ( ! is_singular() ) : ?>
	<div class="page-branding">
		<?php if ( autonom_get_the_archive_title() ) { ?>
		<h1 id="page-title"<?php autonom_semantics( 'page-title' ); ?>><?php echo autonom_get_the_archive_title(); ?></h1>
		<?php } ?>
		<?php if ( autonom_get_the_archive_description() ) { ?>
		<div id="page-description"<?php autonom_semantics( 'page-description' ); ?>><?php echo autonom_get_the_archive_description(); ?></div>
		<?php } ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>
