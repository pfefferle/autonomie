/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './style.scss';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit() {
		// The dialog needs a post, so the editor only shows the button.
		return (
			<div { ...useBlockProps() }>
				<span className="wp-block-autonomie-share__button">
					{ __( 'Share', 'autonomie' ) }
				</span>
			</div>
		);
	},
} );
