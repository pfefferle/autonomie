/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import {
	quote,
	image,
	gallery,
	video,
	audio,
	comment,
	link as linkIcon,
	postContent,
	Icon,
} from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './style.scss';
import './editor.scss';

// WordPress icons for each format
const icons = {
	aside: postContent,
	gallery: gallery,
	link: linkIcon,
	image: image,
	quote: quote,
	status: postContent,
	video: video,
	audio: audio,
	chat: comment,
	standard: postContent,
};

// Format display names
const formatNames = {
	aside: 'Aside',
	gallery: 'Gallery',
	link: 'Link',
	image: 'Image',
	quote: 'Quote',
	status: 'Status',
	video: 'Video',
	audio: 'Audio',
	chat: 'Chat',
	standard: 'Standard',
};

/**
 * Post Format Block Edit Component
 */
const Edit = ( { context } ) => {
	const { postId, postType = 'post' } = context;

	// Get the post format from the taxonomy
	const { format, formatLink } = useSelect(
		( select ) => {
			if ( ! postId ) {
				return { format: 'standard', formatLink: '' };
			}

			const { getEntityRecord, getTaxonomy } = select( 'core' );
			const post = getEntityRecord( 'postType', postType, postId );

			if ( ! post ) {
				return { format: 'standard', formatLink: '' };
			}

			// Get format from taxonomy
			const formatTermIds = post.format || [];
			let postFormat = 'standard';

			if ( formatTermIds.length > 0 ) {
				// Get the term slug
				const formatTerm = getEntityRecord(
					'taxonomy',
					'post_format',
					formatTermIds[ 0 ]
				);
				if ( formatTerm ) {
					postFormat = formatTerm.slug.replace( 'post-format-', '' );
				}
			}

			// Get format archive link
			let link = '';
			if ( formatTermIds.length > 0 ) {
				const formatTerm = getEntityRecord(
					'taxonomy',
					'post_format',
					formatTermIds[ 0 ]
				);
				if ( formatTerm && formatTerm.link ) {
					link = formatTerm.link;
				}
			}

			return { format: postFormat, formatLink: link };
		},
		[ postId, postType ]
	);

	const blockProps = useBlockProps( {
		className: `format-${ format }`,
	} );

	const icon = icons[ format ] || icons.standard;
	const formatName = formatNames[ format ] || formatNames.standard;

	return (
		<div { ...blockProps }>
			<span className="format-display">
				<Icon className="format-icon" icon={ icon } />
				<span className="format-text">{ formatName }</span>
			</span>
		</div>
	);
};

/**
 * Register the block
 */
registerBlockType( 'autonomie/post-format', {
	edit: Edit,
} );
