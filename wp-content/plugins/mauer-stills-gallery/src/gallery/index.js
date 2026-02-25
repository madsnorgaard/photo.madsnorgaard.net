/**
 * External dependencies
 */
import filter from 'lodash/filter';
import every from 'lodash/every';
import map from 'lodash/map';
import some from 'lodash/some';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { createBlock } = wp.blocks;
const { RichText, mediaUpload } = wp.editor;
const { createBlobURL } = wp.blob;
const { G, Path, SVG } = wp.components;

/**
 * Internal dependencies
 */
import { default as edit, pickRelevantMediaFiles, pswpFigureClass } from './edit';

const blockAttributes = {
	images: {
		type: 'array',
		default: [],
		source: 'query',
		selector: '.wp-block-mauer-stills-gallery figure',
		query: {
			id: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'data-id',
			},
			alt: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'alt',
				default: '',
			},
			caption: {
				type: 'string',
				source: 'html',
				selector: 'figcaption',
			},
			imgWideUrl: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'data-img-wide-url',
			},
			imgHalfWideUrl: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'data-img-half-wide-url',
			},
			imgBigUrl: {
				source: 'attribute',
				selector: 'a.mauer-stills-gallery-pswp-big-img-link',
				attribute: 'href',
			},
			url: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'data-img-full-url',
			},
			aspectRatio: {
				source: 'attribute',
				selector: 'img.mauer-stills-gallery-pswp-img',
				attribute: 'data-aspect-ratio',
			},
			imgBigDimensions: {
				source: 'attribute',
				selector: 'a.mauer-stills-gallery-pswp-big-img-link',
				attribute: 'data-size',
			},
		},
	},
	ids: {
		type: 'array',
		default: [],
	},
};

export const name = 'mauer-stills/gallery';

const parseShortcodeIds = ( ids ) => {
	if ( ! ids ) {
		return [];
	}

	return ids.split( ',' ).map( ( id ) => (
		parseInt( id, 10 )
	) );
};

export const settings = {
	title: __( 'Stills Gallery' ),
	description: __( 'The gallery designed for Mauer Stills theme. Plays most nicely if you occasionally use the #half tag. See theme demo\'s Features section for more details.', 'mauer-stills-gallery' ),
	icon: <SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><Path fill="none" d="M0 0h24v24H0V0z" /><G><Path d="M20 4v12H8V4h12m0-2H8L6 4v12l2 2h12l2-2V4l-2-2z" /><Path d="M12 12l1 2 3-3 3 4H9z" /><Path d="M2 6v14l2 2h14v-2H4V6H2z" /></G></SVG>,
	category: 'common',
	keywords: [ __( 'images', 'mauer-stills-gallery' ), __( 'photos', 'mauer-stills-gallery' ) ],
	attributes: blockAttributes,
	supports: {
		align: true,
	},

	edit,

	save( { attributes } ) {
		const { images } = attributes;
		return (

			<div>
				<div class="mauer-stills-gallery-pswp-wrapper">
					<div class="mauer-stills-gallery-pswp" data-style="display: flex;" itemscope="" itemtype="http://schema.org/ImageGallery" data-pswp-uid="1">

						{ images.map( ( image, index ) => {
							var imgBigUrl = image.imgBigUrl;

							if (image.caption.indexOf('#half') !== -1) {var imgRegUrl = image.imgHalfWideUrl;}
							else {var imgRegUrl = image.imgWideUrl;}

							const figureClass = pswpFigureClass( image );

							const img =
								<a class="mauer-stills-gallery-pswp-big-img-link" href={`${ imgBigUrl }`} itemprop="contentUrl" data-size={`${ image.imgBigDimensions }`}>
									<img
										src={ imgRegUrl }
										alt={ image.alt }
										className="mauer-stills-img-adaptable-height mauer-stills-gallery-pswp-img"
										data-id={ image.id }
										data-img-full-url = { image.url }
										data-img-wide-url = {image.imgWideUrl}
										data-img-half-wide-url = {image.imgHalfWideUrl}
										data-aspect-ratio = { image.aspectRatio }
									/>
								</a>;

							return (
								<figure class={figureClass} itemprop="associatedMedia" itemscope="" itemtype="http://schema.org/ImageObject">
									{ img }
									{ image.caption ? <RichText.Content tagName="figcaption" value={ image.caption } /> : null }
								</figure>
							);

						} ) }

					</div>
					<div class="clearfix"></div>
				</div>
			</div>

		);
	}

};