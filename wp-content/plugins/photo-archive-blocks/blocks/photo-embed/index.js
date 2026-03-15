/**
 * Photo Embed block — editor script.
 *
 * Allows editors to select a Photo CPT post and embed it
 * inline within a Story.
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Placeholder, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

registerBlockType( metadata.name, {
  edit( { attributes, setAttributes } ) {
    const { photoId, showCaption } = attributes;
    const blockProps = useBlockProps();

    const photo = useSelect(
      ( select ) => {
        if ( ! photoId ) return null;
        return select( coreStore ).getEntityRecord( 'postType', 'photo', photoId );
      },
      [ photoId ]
    );

    const thumbnailUrl = photo?.featured_media
      ? useSelect(
          ( select ) =>
            select( coreStore ).getEntityRecord( 'root', 'media', photo.featured_media )
              ?.media_details?.sizes?.medium?.source_url,
          [ photo?.featured_media ]
        )
      : null;

    return (
      <>
        <InspectorControls>
          <PanelBody title={ __( 'Photo', 'photo-archive-blocks' ) }>
            <TextControl
              label={ __( 'Photo ID', 'photo-archive-blocks' ) }
              value={ photoId || '' }
              type="number"
              onChange={ ( val ) => setAttributes( { photoId: parseInt( val ) || undefined } ) }
              help={ __( 'Enter the post ID of the Photo you want to embed.', 'photo-archive-blocks' ) }
            />
            <ToggleControl
              label={ __( 'Show caption', 'photo-archive-blocks' ) }
              checked={ showCaption }
              onChange={ ( val ) => setAttributes( { showCaption: val } ) }
            />
          </PanelBody>
        </InspectorControls>

        <div { ...blockProps }>
          { ! photoId && (
            <Placeholder label={ __( 'Photo Embed', 'photo-archive-blocks' ) }
              instructions={ __( 'Enter a Photo post ID in the block settings.', 'photo-archive-blocks' ) }
            />
          ) }
          { photoId && ! photo && <Spinner /> }
          { photo && (
            <figure className="photo-embed">
              { thumbnailUrl && (
                <img src={ thumbnailUrl } alt={ photo.title?.rendered || '' } />
              ) }
              { showCaption && (
                <figcaption className="photo-embed__caption">
                  <span className="photo-embed__number">
                    { photo.meta?.archive_number
                      ? String( photo.meta.archive_number ).padStart( 3, '0' )
                      : '' }
                  </span>
                  { ' ' }
                  <span className="photo-embed__title">{ photo.title?.rendered }</span>
                </figcaption>
              ) }
            </figure>
          ) }
        </div>
      </>
    );
  },

  save() {
    // Dynamic block — rendered server-side via render_callback
    return null;
  },
} );
