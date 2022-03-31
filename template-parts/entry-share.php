<indie-action do="post" with="<?php echo get_permalink(); ?>">
	<button type="share" id="entry-share">
		<?php _e( 'share', 'autonomie' ); ?>
	</button>
</indie-action>

<div id="share-options" style="display: none;">
	<p><strong>Sharing is caring ❤️</strong></p>
	<p>
		<label for="entry-shortlink"><?php _e( 'Shortlink', 'autonomie' ); ?></label>
		<input id="entry-shortlink" class="u-url url shortlink" type="text" value="<?php echo wp_get_shortlink(); ?>" />
	</p>
	<p>
		<label for="entry-permalink"><?php _e( 'Permalink', 'autonomie' ); ?></label>
		<input id="entry-permalink" class="u-url url u-uid uid bookmark" type="text" value="<?php echo get_permalink(); ?>" />
	</p>
	<?php
	// some comment ;)
	if ( get_the_title() ) {
		?>
	<p>
		<label for="entry-summary"><?php _e( 'HTML', 'autonomie' ); ?></label>
		<textarea id="entry-summary" class="code" type="text" rows="5" cols="70">&lt;cite class=&quot;h-cite&quot;&gt;&lt;a class=&quot;u-url p-name&quot; href=&quot;<?php echo get_permalink(); ?>&quot;&gt;<?php echo get_the_title(); ?>&lt;/a&gt; (&lt;span class=&quot;p-author h-card&quot; title=&quot;<?php echo get_the_author(); ?>&quot;&gt;<?php echo get_the_author(); ?>&lt;/span&gt; &lt;time class=&quot;dt-published&quot; datetime=&quot;<?php echo get_the_date( 'c' ); ?>&quot;&gt;<?php echo get_the_date(); ?>&lt;/time&gt;)&lt;/cite&gt;</textarea>
	</p>
	<?php } else { ?>
	<p>
		<label for="entry-blockquote"><?php _e( 'HTML', 'autonomie' ); ?></label>
		<textarea id="entry-blockquote" class="code" type="text" rows="5" cols="70">&lt;blockquote&gt;&lt;p&gt;<?php echo get_the_excerpt(); ?>&lt;/p&gt;&lt;small&gt;—&nbsp;by &lt;a href=&quot;<?php echo get_permalink(); ?>&quot; class=&quot;h-card&quot; title=&quot;<?php echo get_the_author(); ?>&quot;&gt;<?php echo get_the_author(); ?>&lt;/a&gt;&lt;/small&gt;&lt;/blockquote&gt;</textarea>
	</p>
	<?php } ?>
</div>
