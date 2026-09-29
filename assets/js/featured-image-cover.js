/**
 * Adds a "Use as post cover (full-width)" checkbox to the featured image panel.
 *
 * The checkbox switches the post or page to its "Full-width featured image" template.
 */
( function ( wp ) {
  const { createElement: el, Fragment } = wp.element;
  const { CheckboxControl } = wp.components;
  const { useSelect, useDispatch } = wp.data;
  const { __ } = wp.i18n;

  const TEMPLATES = {
    post: 'single-full-width-image',
    page: 'page-full-width-image',
  };

  function CoverCheckbox() {
    const { template, checked, hasImage } = useSelect( ( select ) => {
      const editor = select( 'core/editor' );
      const slug = TEMPLATES[ editor.getCurrentPostType() ];

      return {
        template: slug,
        checked: !! slug && editor.getEditedPostAttribute( 'template' ) === slug,
        hasImage: !! editor.getEditedPostAttribute( 'featured_media' ),
      };
    }, [] );
    const { editPost } = useDispatch( 'core/editor' );

    if ( ! template || ! hasImage ) {
      return null;
    }

    return el( CheckboxControl, {
      __nextHasNoMarginBottom: true,
      label: __( 'Use as post cover (full-width)', 'autonomie' ),
      checked,
      onChange: ( value ) => editPost( { template: value ? template : '' } ),
    } );
  }

  wp.hooks.addFilter(
    'editor.PostFeaturedImage',
    'autonomie/featured-image-cover',
    ( OriginalComponent ) => ( props ) =>
      el( Fragment, {}, el( OriginalComponent, props ), el( CoverCheckbox ) )
  );
} )( window.wp );
