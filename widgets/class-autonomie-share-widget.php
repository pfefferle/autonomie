<?php

class Autonomie_Share_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'autonomie-share',  // Base ID
			'Share Button (Autonomie)'   // Name
		);
	}

	public function widget( $args, $instance ) {
		get_template_part( 'template-parts/entry', 'share' );
	}
}
