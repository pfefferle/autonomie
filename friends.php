<?php
/**
 * This is the main /friends/ template.
 *
 * @package Friends
 */

get_header();

$friends = Friends::get_instance(); ?>
<main id="primary" <?php autonomie_main_class(); ?><?php autonomie_semantics( 'main' ); ?>>
	<div class="friends-topbar">
		<?php if ( $friends->frontend->author ) : ?>
			<h1>
			<?php echo esc_html( $friends->frontend->author->display_name ); ?>
			</h1>
			<p>
			<?php
			echo wp_kses(
				// translators: %1$s is a site name, %2$s is a URL.
				sprintf( __( 'Visit %1$s. Back to <a href=%2$s>your friends page</a>.', 'friends' ), '<a href="' . esc_url( $friends->frontend->author->user_url ) . '" class="auth-link" data-token="' . esc_attr( get_user_option( 'friends_out_token', $friends->frontend->author->ID ) ) . '">' . esc_html( $friends->frontend->author->display_name ) . '</a>', '"' . esc_attr( site_url( '/friends/' ) ) . '"' ),
				array(
					'a' => array(
						'href'       => array(),
						'class'      => array(),
						'data-token' => array(),
					),
				)
			);
			?>
			</p>
		<?php else : ?>
			<?php dynamic_sidebar( 'friends-topbar' ); ?>
		<?php endif; ?>
	</div>
	<?php if ( ! have_posts() ) : ?>
		<?php esc_html_e( 'No posts found.' ); ?>
	<?php endif; ?>

	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$token          = get_user_option( 'friends_out_token', get_the_author_meta( 'ID' ) );
		$avatar         = get_post_meta( get_the_ID(), 'gravatar', true );
		$recommendation = get_post_meta( get_the_ID(), 'recommendation', true );
		?>
		<article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
			<header class="entry-header">
				<div class="avatar">
					<?php if ( Friends::CPT === get_post_type() ) : ?>
						<?php if ( $recommendation ) : ?>
							<a href="<?php the_permalink(); ?>" rel="noopener noreferrer">
								<img src="<?php echo esc_url( $avatar ); ?>" width="36" height="36" class="avatar" />
							</a>
						<?php else : ?>
							<a href="<?php echo esc_attr( site_url( '/friends/' . get_the_author_meta( 'login' ) . '/' ) ); ?>"class="author-avatar">
								<img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ) ) ); ?>" width="36" height="36" class="avatar" />
							</a>
						<?php endif; ?>
					<?php else : ?>
						<a href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>" class="author-avatar">
							<img src="<?php echo esc_url( $avatar ? $avatar : get_avatar_url( get_the_author_meta( 'ID' ) ) ); ?>" width="36" height="36" class="avatar" />
						</a>
					<?php endif; ?>
				</div>
				<div class="post-meta">
					<div class="author">
						<?php if ( Friends::CPT === get_post_type() ) : ?>
							<?php if ( $recommendation ) : ?>
								<a href="<?php the_permalink(); ?>" rel="noopener noreferrer" ><strong><?php echo esc_html( get_post_meta( get_the_ID(), 'author', true ) ); ?></strong></a>
							<?php else : ?>
								<a href="<?php echo esc_attr( site_url( '/friends/' . get_the_author_meta( 'login' ) . '/' ) ); ?>">
									<strong><?php the_author(); ?></strong>
								</a>
							<?php endif; ?>
						<?php else : ?>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
								<strong><?php the_author(); ?></strong>
							</a>
						<?php endif; ?>
					</div>
					<span class="post-date" title="<?php echo get_the_time( 'r' ); ?>"><?php /* translators: %s is a time span */ printf( __( '%s ago' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
					<?php edit_post_link(); ?>
				</div>
				<?php if ( false && Friends::CPT === get_post_type() ) : ?>
					<button class="friends-trash-post" title="<?php esc_attr_e( 'Trash this post', 'friends' ); ?>" data-trash-nonce="<?php echo esc_attr( wp_create_nonce( 'trash-post_' . get_the_ID() ) ); ?>" data-untrash-nonce="<?php echo esc_attr( wp_create_nonce( 'untrash-post_' . get_the_ID() ) ); ?>" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
						&#x1F5D1;
					</button>
				<?php endif; ?>
			</header>

			<h4 class="entry-title">
				<?php if ( Friends::CPT === get_post_type() ) : ?>
					<?php if ( $recommendation ) : ?>
						<a href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer" class="auth-link" data-token="<?php echo esc_attr( $token ); ?>">
							<?php
							// translators: %s is a post title.
							echo esc_html( sprintf( __( 'Recommendation: %s', 'friends' ), get_the_title() ) );
							?>
						</a>
					<?php elseif ( $token ) : ?>
						<a href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer" class="auth-link" data-token="<?php echo esc_attr( $token ); ?>"><?php the_title(); ?></a>
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer"><?php the_title(); ?></a>
					<?php endif; ?>
				<?php else : ?>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				<?php endif; ?>
			</h4>

			<div class="entry-content">
				<?php
				if ( Friends::CPT === get_post_type() && $recommendation ) {
					$friend_name = '<a href="' . esc_url( get_the_author_meta( 'url' ) ) . '" class="auth-link" data-token="' . esc_attr( $token ) . '">' . esc_html( get_the_author() ) . '</a>';
					?>
					<p class="friend-recommendation">
					<?php
					if ( is_string( $recommendation ) && '1' !== $recommendation ) {
						echo wp_kses(
							// translators: %1$s is the friend's name, %2$s is the message.
							sprintf( __( 'Your friend %1$s recommended this with the message: %2$s', 'friends' ), $friend_name, '<span>' . esc_html( $recommendation ) . '</span>' ),
							array(
								'a'    => array(
									'class'      => array(),
									'data-token' => array(),
									'href'       => array(),
								),
								'span' => array(),
							)
						);
					} else {
						echo wp_kses(
							// translators: %s is the friend's name.
							sprintf( __( 'Your friend %s recommended this.', 'friends' ), $friend_name ),
							array(
								'a' => array(
									'class'      => array(),
									'data-token' => array(),
									'href'       => array(),
								),
							)
						);
					}
					echo ' ', esc_html__( 'Be aware that this post might have been altered by your friend. Please verify with the original when in doubt.', 'friends' );
					?>
					</p>
					<?php
				}
				?>
				<?php the_content(); ?>
			</div>

			<footer class="entry-meta">
				<?php if ( Friends::CPT === get_post_type() && $token ) : ?>
				<button href="<?php comments_link(); ?>" target="_blank" rel="noopener noreferrer" class="comments auth-link" data-token="<?php echo esc_attr( $token ); ?>">
					<span class="dashicons dashicons-admin-comments"></span>
					<?php comments_number( '', 1, '%' ); ?>
				</button>
				<?php elseif ( Friends::CPT === get_post_type() ) : ?>
				<a href="<?php comments_link(); ?>" target="_blank" rel="noopener noreferrer" class="comments button">
					<span class="dashicons dashicons-admin-comments"></span>
					<?php comments_number( '', 1, '%' ); ?>
				</a>
				<?php else : ?>
				<a href="<?php comments_link(); ?>" class="comments button">
					<span class="dashicons dashicons-admin-comments"></span>
					<?php comments_number( '', 1, '%' ); ?>
				</a>
				<?php endif; ?>
				<?php echo $friends->reactions->post_reactions(); ?>
				<?php if ( Friends::CPT === get_post_type() ) : ?>
					<?php echo $friends->recommendation->post_recommendation(); ?>
				<?php endif; ?>
			</footer>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
