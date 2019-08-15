<?php
/**
 * Autonomie PWA
 *
 * Adds basic PWA support
 *
 * @link https://github.com/xwp/pwa-wp/
 *
 * @package Autonomie
 * @subpackage pwa
 */

/**
 * Description: With runtime caching of images, scripts, and styles.
 */

add_filter(
	'wp_service_worker_navigation_caching_strategy',
	function () {
		return WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST;
	}
);

add_filter(
	'wp_service_worker_navigation_caching_strategy_args',
	function ( $args ) {
		$args['cacheName'] = 'pages';

		$args['plugins']['expiration']['maxEntries'] = 50;

		return $args;
	}
);

add_action(
	'wp_front_service_worker',
	function( WP_Service_Worker_Scripts $scripts ) {
		// Cache scripts and styles.
		$scripts->caching_routes()->register(
			'/.*\.(?:js|css)(\?.*)?$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'cacheName' => 'assets',
				'plugins'   => array(
					'expiration' => array(
						'maxEntries' => 60,
					),
				),
			)
		);

		// Cache images.
		$scripts->caching_routes()->register(
			'/wp-content/.*\.(?:png|gif|jpg|jpeg|svg|webp)(\?.*)?$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_CACHE_FIRST,
				'cacheName' => 'images',
				'plugins'   => array(
					'expiration' => array(
						'maxEntries' => 60,
					),
				),
			)
		);
	}
);
