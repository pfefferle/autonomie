<?php
if ( ! function_exists( 'autonomie_content_nav' ) ) :
	/**
	 * Display navigation to next/previous pages when applicable.
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_content_nav( $nav_id ) {
		global $wp_query;
		?>
		<?php if ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages ?>
		<nav id="archive-nav">
			<div class="assistive-text"><?php _e( 'Post navigation', 'autonomie' ); ?></div>
			<?php echo paginate_links(); ?>
		</nav><!-- #<?php echo $nav_id; ?> -->
		<?php endif; ?>
		<?php
	}
endif; // autonomie_content_nav

if ( ! function_exists( 'autonomie_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 * Create your own autonomie_posted_by to override in a child theme.
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_posted_by() {
		printf(
			'<address class="byline">
				<span class="author p-author vcard hcard h-card" itemprop="author" itemscope itemtype="https://schema.org/Person">
					%1$s
					<a class="url uid u-url u-uid fn p-name" href="%2$s" title="%3$s" rel="author">
						<span itemprop="name">%4$s</span>
					</a>
					<link itemprop="url" href="%2$s" />
				</span>
			</address>',
			get_avatar( get_the_author_meta( 'ID' ), 40),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			// translators:
			esc_attr( sprintf( __( 'View all posts by %s', 'autonomie' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
endif;

if ( ! function_exists( 'autonomie_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 * Create your own autonomie_posted_on to override in a child theme.
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_posted_on( $type = 'published' ) {
		global $query;

		if ( ! in_array( $type, array( 'published', 'updated' ) ) ) {
			$type = 'published';
		}

		if ( get_query_var( 'is_now', false ) ) {
			$type = 'updated';
		}

		if ( 'updated' === $type ) {
			// updated
			$time      = get_the_modified_time();
			$date_c    = get_the_modified_date( 'c' );
			$date      = get_the_modified_date();
			$item_prop = 'dateModified';
		} else {
			// published
			$time      = get_the_time();
			$date_c    = get_the_date( 'c' );
			$date      = get_the_date();
			$item_prop = 'datePublished';
		}

		// translators: the author byline
		printf(
			// translators:
			'<a href="%1$s" title="%2$s" rel="bookmark" class="url u-url" itemprop="mainEntityOfPage"><time class="entry-date %5$s dt-%5$s" datetime="%3$s" itemprop="%6$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( $time ),
			esc_attr( $date_c ),
			esc_html( $date ),
			esc_html( $type ),
			esc_html( $item_prop ),
		);
	}
endif;

/**
 * Display the id for the post div.
 *
 * @param string $id
 */
function autonomie_post_id( $post_id = null ) {
	if ( $post_id ) {
		echo 'id="' . $post_id . '"';
	} else {
		echo 'id="' . autonomie_get_post_id() . '"';
	}
}

/**
 * Retrieve the id for the post div.
 *
 * @return string The post-id.
 */
function autonomie_get_post_id() {
	$post_id = 'post-' . get_the_ID();

	return apply_filters( 'autonomie_post_id', $post_id, get_the_ID() );
}

function autonomie_main_class( $class = '' ) {
	// Separates class names with a single space, collates class names for body element
	echo 'class="' . join( ' ', autonomie_get_main_class( $class ) ) . '"';
}

function autonomie_get_main_class( $class = '' ) {
	$classes = array();

	if ( is_singular() ) {
		$classes = autonomie_get_post_classes( $classes );
	}

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS main class names for the current post or page.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $classes An array of main class names.
	 * @param string[] $class   An array of additional class names added to the main.
	 */
	$classes = apply_filters( 'autonomie_main_class', $classes, $class );

	return array_unique( $classes );
}

function autonomie_get_the_archive_title() {
	if ( is_archive() ) {
		return get_the_archive_title();
	} elseif ( is_search() ) {
		// translators: The title of the search results page
		return sprintf( __( 'Search Results for: %s', 'autonomie' ), '<span>' . get_search_query() . '</span>' );
	}
}

/**
 * Check if page banner is enabled.
 *
 * @return boolean
 */
function autonomie_show_page_banner() {
	if ( is_home() && ! display_header_text() ) {
		return false;
	}

	if ( is_home() || is_archive() || is_search() ) {
		return true;
	}

	return false;
}

/**
 * Adds support for standard post-format
 *
 * @return void
 */
function autonomie_get_post_format() {
	return get_post_format() ? : 'standard';
}

/**
 * Add support for Attachment and Article.
 *
 * @return void
 */
function autonomie_get_post_format_string() {
	if ( get_post_format() ) {
		return get_post_format();
	} elseif ( 'attachment' === get_post_type() ) {
		return __( 'Attachment', 'autonomie' );
	} else {
		return __( 'Text', 'autonomie' );
	}
}

/**
 * Adds support for "standard" post-format archive links.
 *
 * @param string $post_format
 *
 * @return void
 */
function autonomie_get_post_format_link( $post_format ) {
	if ( 'standard' !== $post_format ) {
		return get_post_format_link( $post_format );
	}

	global $wp_rewrite;

	$termlink = $wp_rewrite->get_extra_permastruct( 'post_format' );

	if ( empty( $termlink ) ) {
		$termlink = '?post_format=standard';
		$termlink = home_url( $termlink );
	} else {
		$termlink = str_replace( '%post_format%', 'standard', $termlink );
		$termlink = home_url( user_trailingslashit( $termlink, 'category' ) );
	}

	return $termlink;
}

/**
 * Check archive type.
 *
 * @return string
 */
function autonomie_get_archive_type() {
	$type = null;

	if ( is_author() ) {
		$type = 'author';
	}

	return apply_filters( 'autonomie_archive_type', $type );
}

/**
 * Returns Meta-Data like "number of posts" and "subscribe buttons" for the Author.
 *
 * @return string
 */
function autonomie_get_archive_author_meta() {
	$meta = array();

	// translators: list of followers
	$meta[] = sprintf( __( '%s Followers', 'autonomie' ), apply_filters( 'autonomie_archive_author_followers', 0, get_the_author_meta( 'ID' ) ) );
	// translators: a post counter
	$meta[] = sprintf( __( '%s Posts', 'autonomie' ), count_user_posts( get_the_author_meta( 'ID' ) ) );
	$meta[] = sprintf( '<indie-action do="follow" with="%1$s"><a rel="alternate" class="feed u-feed openwebicons-feed" href="%1$s">%2$s</a></indie-action>', get_author_feed_link( get_the_author_meta( 'ID' ) ), __( 'Subscribe', 'autonomie' ) );

	$meta = apply_filters( 'autonomie_archive_author_meta', $meta, get_the_author_meta( 'ID' ) );

	return implode( ' | ', $meta );
}

/**
 * Returns the page description
 *
 * @return string The page description
 */
function autonomie_get_the_archive_description() {
	if ( is_home() ) {
		return get_bloginfo( 'description' );
	} elseif ( is_author() ) {
		return get_the_author_meta( 'description' );
	} elseif ( is_archive() ) {
		return get_the_archive_description();
	} elseif ( is_search() ) {
		// @see https://github.com/raamdev/independent-publisher/blob/513e7ff71312f585f13eb1460b4d9bc74d0b59bd/inc/template-tags.php#L674
		global $wp_query;
		$total = $wp_query->found_posts;
		// translators: Description for search results
		$stats_text = sprintf( _n( 'Found %1$s search result for <strong>%2$s</strong>.', 'Found %1$s search results for <strong>%2$s</strong>.', $total, 'autonomie' ), number_format_i18n( $total ), get_search_query() );

		return wpautop( $stats_text );
	}
}

/**
 * Estimated reading time
 */
function autonomie_reading_time() {
	$content = get_post_field( 'post_content' );
	$word_count = str_word_count( strip_tags( $content ) );
	$readingtime = ceil( $word_count / 200 );

	printf(
		_n(
			'<span class="entry-duration"><time datetime="PT%1$sM" class="dt-duration" itemprop="timeRequired">%1$s minute</time> to read</span>',
			'<span class="entry-duration"><time datetime="PT%1$sM" class="dt-duration" itemprop="timeRequired">%1$s minutes</time> to read</span>',
			$readingtime,
			'autonomie'
		),
		number_format_i18n( $readingtime )
	);
}

/**
 * Add possibility to use `the_excerpt` instead of `the_content` for longer posts
 *
 * @return void
 */
function autonomie_the_content() {
	if ( is_search() ) {
		the_excerpt();
		return;
	}

	if ( is_singular() ) {
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'autonomie' ) );
		return;
	}

	$count = str_word_count( strip_tags( get_the_content() ) );

	if ( AUTONOMIE_EXCERPT && ( false === get_post_format() || $count > AUTONOMIE_EXCERPT_COUNT ) ) {
		the_excerpt();
	} else {
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'autonomie' ) );
	}
}
