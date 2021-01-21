<?php

class Autonomie_Taxonomy_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'autonomie-taxonomy',  // Base ID
			'Entry Taxonomy (Autonomie)'   // Name
		);
	}

	public function widget( $args, $instance ) {
		get_template_part( 'template-parts/entry', 'taxonomy' );
	}
}
