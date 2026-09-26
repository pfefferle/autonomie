/**
 * Adds a "Use as post cover (full-width)" checkbox to the featured image panel.
 *
 * The checkbox switches the post to the "Full-width featured image" template.
 */
( function ( wp ) {
  const { createElement: el, Fragment } = wp.element;
  const { CheckboxControl } = wp.components;
  const { useSelect, useDispatch } = wp.data;
  const { __ } = wp.i18n;

  const TEMPLATE = 'single-full-width-image';

  function CoverCheckbox() {
    const { checked, hasImage } = useSelect( ( select ) => {
      const editor = select( 'core/editor' );

      return {
        checked: editor.getEditedPostAttribute( 'template' ) === TEMPLATE,
        hasImage: !! editor.getEditedPostAttribute( 'featured_media' ),
      };
    }, [] );
    const { editPost } = useDispatch( 'core/editor' );

    if ( ! hasImage ) {
      return null;
    }

    return el( CheckboxControl, {
      __nextHasNoMarginBottom: true,
      label: __( 'Use as post cover (full-width)', 'autonomie' ),
      checked,
      onChange: ( value ) => editPost( { template: value ? TEMPLATE : '' } ),
    } );
  }

  wp.hooks.addFilter(
    'editor.PostFeaturedImage',
    'autonomie/featured-image-cover',
    ( OriginalComponent ) => ( props ) =>
      el( Fragment, {}, el( OriginalComponent, props ), el( CoverCheckbox ) )
  );
} )( window.wp );
