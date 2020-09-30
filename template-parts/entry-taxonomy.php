<?php
/* translators: used between list items, there is a space after the comma */
$categories_list = get_the_category_list();
if ( $categories_list ) :
?>
<div class="cat-links">
	<?php echo __( 'Categories', 'autonomie' ); ?>
	<?php printf( __( '%1$s', 'autonomie' ), $categories_list ); ?>
</div>
<?php endif; // End if categories ?>

<?php
/* translators: used between list items, there is a space after the comma */
$tags_list = get_the_tag_list( '<ul><li>', '</li><li>', '</li></ul>' );
if ( $tags_list ) :
?>
<div class="tag-links" itemprop="keywords">
	<?php echo __( 'Tags', 'autonomie' ); ?>
	<?php printf( __( '%1$s', 'autonomie' ), $tags_list ); ?>
</div>
<?php endif; // End if $tags_list ?>
