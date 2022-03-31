"use strict"

// Inspired by https://digitalapps.co/gutenberg-extending-featured-image-component/

const el = wp.element.createElement;
const withState = wp.compose.withState;
const withSelect = wp.data.withSelect;
const withDispatch = wp.data.withDispatch;
const { __ } = wp.i18n;

wp.hooks.addFilter(
  'editor.PostFeaturedImage',
  'autonomie/full_width_featured_image',
  wrapPostFeaturedImage
);

function wrapPostFeaturedImage( OriginalComponent ) {
  return function( props ) {
    return (
      el(
        wp.element.Fragment,
        {},
        '',
        el(
          OriginalComponent,
          props
        ),
        el(
          composedFullWidthFeaturedImageCheckBox
        )
      )
    );
  }
}

class FullWidthFeaturedImageCheckBox extends React.Component {
  render() {
    const {
      meta,
      updateFullWidthFeaturedImage,
    } = this.props;

    return (
      el(
        wp.components.CheckboxControl,
        {
          label: __( 'Use as post cover (full-width)', 'autonomie' ),
          checked: meta.full_width_featured_image,
          onChange:
            ( value ) => {
              this.setState( { isChecked: value } );
              updateFullWidthFeaturedImage( value, meta );
            }
        }
      )
    )
  }
}

const composedFullWidthFeaturedImageCheckBox = wp.compose.compose( [
  withState( ( value ) => { isChecked: value } ),
  withSelect( ( select ) => {
    const currentMeta = select( 'core/editor' ).getCurrentPostAttribute( 'meta' );
    const editedMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
    return {
      meta: { ...currentMeta, ...editedMeta },
    };
  } ),
  withDispatch( ( dispatch ) => ( {
    updateFullWidthFeaturedImage( value, meta ) {
      meta = {
        ...meta,
        full_width_featured_image: value,
      };
      dispatch( 'core/editor' ).editPost( { meta } );
    },
  } ) ),
] )( FullWidthFeaturedImageCheckBox );
