/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled,
	PanelBody,
	RangeControl,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './style.scss';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps();

		const postTypes = useSelect(
			( select ) =>
				(
					select( 'core' ).getPostTypes( { per_page: -1 } ) || []
				).filter(
					( type ) => type.viewable && 'attachment' !== type.slug
				),
			[]
		);

		const toggle = ( key, label ) => (
			<ToggleControl
				__nextHasNoMarginBottom
				label={ label }
				checked={ attributes[ key ] }
				onChange={ ( value ) => setAttributes( { [ key ]: value } ) }
			/>
		);

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'autonomie' ) }>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Weeks', 'autonomie' ) }
							value={ attributes.weeks }
							min={ 4 }
							max={ 104 }
							onChange={ ( weeks ) => setAttributes( { weeks } ) }
						/>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Post type', 'autonomie' ) }
							value={ attributes.postType }
							options={ [
								{
									label: __(
										'All public post types',
										'autonomie'
									),
									value: 'any',
								},
								...postTypes.map( ( type ) => ( {
									label: type.name,
									value: type.slug,
								} ) ),
							] }
							help={ __(
								'Days only link to their archive if all post types appear in date archives.',
								'autonomie'
							) }
							onChange={ ( postType ) =>
								setAttributes( { postType } )
							}
						/>
						{ toggle( 'showTotal', __( 'Show total', 'autonomie' ) ) }
						{ toggle(
							'showMonths',
							__( 'Show months', 'autonomie' )
						) }
						{ toggle(
							'showWeekdays',
							__( 'Show weekdays', 'autonomie' )
						) }
						{ toggle(
							'showLegend',
							__( 'Show legend', 'autonomie' )
						) }
						{ toggle(
							'linkDays',
							__( 'Link days to archives', 'autonomie' )
						) }
					</PanelBody>
				</InspectorControls>
				<div { ...blockProps }>
					<Disabled>
						<ServerSideRender
							block={ metadata.name }
							attributes={ attributes }
						/>
					</Disabled>
				</div>
			</>
		);
	},
} );
