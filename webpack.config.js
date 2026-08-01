const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );

// Filter out default CopyWebpackPlugin
const filteredPlugins = defaultConfig.plugins.filter(
	( plugin ) => plugin.constructor.name !== 'CopyWebpackPlugin'
);

// Add custom copy plugin for PHP files
filteredPlugins.push(
	new CopyWebpackPlugin( {
		patterns: [
			{
				from: 'src/post-format/*.php',
				to: 'post-format/[name][ext]',
			},
			{
				from: 'src/post-format/block.json',
				to: 'post-format/block.json',
			},
		],
	} )
);

module.exports = {
	...defaultConfig,
	entry: {
		'post-format/index': path.resolve(
			process.cwd(),
			'src/post-format',
			'index.js'
		),
	},
	output: {
		path: path.resolve( process.cwd(), 'build' ),
		filename: '[name].js',
	},
	plugins: filteredPlugins,
};
