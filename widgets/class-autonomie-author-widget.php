<?php

class Autonomie_Author_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'autonomie-author',  // Base ID
			'Author Details (Autonomie)'   // Name
		);
	}

	public function widget( $args, $instance ) {
		get_template_part( 'template-parts/entry', 'author' );
	}
}
