/**
 * Extract WordPress Icon Paths to PHP
 *
 * This script reads icon definitions from @wordpress/icons
 * and generates a static PHP file with the SVG paths.
 */

const fs = require( 'fs' );
const path = require( 'path' );

// Icons we need for post formats
const iconMap = {
	aside: 'post-content',
	gallery: 'gallery',
	link: 'link',
	image: 'image',
	quote: 'quote',
	status: 'post-content',
	video: 'video',
	audio: 'audio',
	chat: 'comment',
	standard: 'post-content',
};

/**
 * Extract SVG path from WordPress icon JS file
 */
function extractIconPath( iconName ) {
	const iconPath = path.join(
		__dirname,
		'../node_modules/@wordpress/icons/build-module/library',
		`${ iconName }.js`
	);

	if ( ! fs.existsSync( iconPath ) ) {
		console.error( `Icon not found: ${ iconName }` );
		return null;
	}

	const content = fs.readFileSync( iconPath, 'utf8' );

	// Extract the d attribute from Path component
	const match = content.match( /d:\s*"([^"]+)"/ );

	if ( match ) {
		return match[ 1 ];
	}

	console.error( `Could not extract path from: ${ iconName }` );
	return null;
}

/**
 * Generate PHP file with icon paths
 */
function generateIconsFile() {
	const icons = {};

	// Extract all icon paths
	for ( const [ format, iconName ] of Object.entries( iconMap ) ) {
		const iconPath = extractIconPath( iconName );
		if ( iconPath ) {
			icons[ format ] = iconPath;
		}
	}

	// Generate PHP code
	const phpCode = `<?php
/**
 * WordPress Icons for Post Formats
 *
 * This file is auto-generated during build.
 * Do not edit manually - changes will be overwritten.
 *
 * Generated from @wordpress/icons library.
 *
 * @package Autonomie
 * @since 2.0.0
 */

/**
 * Get post format icon SVG paths
 *
 * @return array Icon paths keyed by format name.
 */
function autonomie_get_post_format_icon_paths() {
	return array(
${ Object.entries( icons )
	.map(
		( [ format, iconPath ] ) =>
			`\t\t'${ format }' => '${ iconPath }',`
	)
	.join( '\n' ) }
	);
}
`;

	// Write to src directory (will be copied to build by webpack)
	const outputPath = path.join(
		__dirname,
		'../src/post-format/icon-paths.php'
	);

	fs.writeFileSync( outputPath, phpCode );

	console.log( '✓ Generated icon-paths.php with paths for:' );
	Object.keys( icons ).forEach( ( format ) =>
		console.log( `  - ${ format }` )
	);
}

// Run the script
generateIconsFile();
