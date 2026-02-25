(function ($) {

"use strict";

window.adjustImagesInGalleries = function() {
	$('.mauer-stills-gallery-pswp').each(function(){

		$(this).find('.mauer-stills-gallery-pswp-img-1-and-only').each(function(){
			$(this).closest('figure').width('100%');
			$(this).height('auto');
		});

		$(this).find('.mauer-stills-gallery-pswp-img-1-of-2').each(function(){
			var firstImageInPair = $(this);
			var secondImageInPair = $(this).closest('figure').next().find('img');
			var combinedAspectRatio = parseFloat(firstImageInPair.attr('data-aspect-ratio')) + parseFloat(secondImageInPair.attr('data-aspect-ratio'));
			var firstImageRelWidth = firstImageInPair.attr('data-aspect-ratio') / combinedAspectRatio;
			var secondImageRelWidth = secondImageInPair.attr('data-aspect-ratio') / combinedAspectRatio;

			firstImageInPair.height('auto').closest('figure').width( firstImageRelWidth * 100 + '%' );
			secondImageInPair.height('auto').closest('figure').width( secondImageRelWidth * 100 + '%' );

			var galleryWidth = $(this).closest('.mauer-stills-gallery-pswp').width();
			var firstImageCalculatedHeight = (galleryWidth * firstImageRelWidth ) / parseFloat(firstImageInPair.attr('data-aspect-ratio'));
			var secondImageCalculatedHeight = (galleryWidth * secondImageRelWidth ) / parseFloat(secondImageInPair.attr('data-aspect-ratio'));
			var lowestHeight = Math.min(firstImageCalculatedHeight, secondImageCalculatedHeight);
			firstImageInPair.add(secondImageInPair).outerHeight(lowestHeight + 2); // + 2 comes from the borders
		});

		$(this).find('.mauer-stills-gallery-pswp-img-1-of-3').each(function(){
			var firstImageInTriad = $(this);
			var secondImageInTriad = $(this).closest('figure').next().find('img');
			var thirdImageInTriad = $(this).closest('figure').next().next().find('img');
			var combinedAspectRatio = parseFloat(firstImageInTriad.attr('data-aspect-ratio')) + parseFloat(secondImageInTriad.attr('data-aspect-ratio')) + parseFloat(thirdImageInTriad.attr('data-aspect-ratio'));

			var firstImageRelWidth = firstImageInTriad.attr('data-aspect-ratio') / combinedAspectRatio;
			var secondImageRelWidth = secondImageInTriad.attr('data-aspect-ratio') / combinedAspectRatio;
			var thirdImageRelWidth = thirdImageInTriad.attr('data-aspect-ratio') / combinedAspectRatio;

			firstImageInTriad.height('auto').closest('figure').width( firstImageRelWidth * 100 + '%' );
			secondImageInTriad.height('auto').closest('figure').width( secondImageRelWidth * 100 + '%' );
			thirdImageInTriad.height('auto').closest('figure').width( thirdImageRelWidth * 100 + '%' );

			var galleryWidth = $(this).closest('.mauer-stills-gallery-pswp').width();
			var firstImageCalculatedHeight = (galleryWidth * firstImageRelWidth ) / parseFloat(firstImageInTriad.attr('data-aspect-ratio'));
			var secondImageCalculatedHeight = (galleryWidth * secondImageRelWidth ) / parseFloat(secondImageInTriad.attr('data-aspect-ratio'));
			var thirdImageCalculatedHeight = (galleryWidth * thirdImageRelWidth ) / parseFloat(thirdImageInTriad.attr('data-aspect-ratio'));
			var lowestHeight = Math.min(firstImageCalculatedHeight, secondImageCalculatedHeight, thirdImageCalculatedHeight);
			firstImageInTriad.add(secondImageInTriad).add(thirdImageInTriad).outerHeight(lowestHeight + 2); // + 2 comes from the borders
		});

	});
}




// as CSS does not have a 'followed by' selector, we're going the JS way to target a next-to-last
// half image followed by another half image
// and also checking that they are in the same row
window.markTheNextToLastImageInTheLastPswpRow = function() {
	$('.mauer-stills-gallery-pswp').each(function(){

		if ($(this).children('.mauer-stills-gallery-pswp-tile').length >= 2) {
			var lastTileIndex = $('figure.mauer-stills-gallery-pswp-tile:last-of-type', this).index();
			var lastImgEl = $(this).children().eq($('figure.mauer-stills-gallery-pswp-tile:last-of-type', this).index());
			var nextToLastImgEl = $(this).children().eq($('figure.mauer-stills-gallery-pswp-tile:last-of-type', this).index() - 1);

			if (lastImgEl.hasClass('mauer-stills-gallery-pswp-tile-half') && nextToLastImgEl.hasClass('mauer-stills-gallery-pswp-tile-half')) {
				// only mark the next to last element if it is on the left in the last row, together with the last element.
				if (lastImgEl.offset().left > nextToLastImgEl.offset().left) {
					nextToLastImgEl.addClass('mauer-stills-first-image-in-a-closing-pair-of-halfs');
				}
			} else {
				nextToLastImgEl.removeClass('mauer-stills-first-image-in-a-closing-pair-of-halfs');
			}
		}

	});
}




// Using % paddings on flex elements is buggy and not recommended by Flexbox documentation, so we're going the JS way.
function addResponsivePaddingToFlexItems() {
	if (!$('body').hasClass('wp-admin')) {
		$('.mauer-stills-gallery-pswp-tile-full, .mauer-stills-gallery-pswp-tile-half').each(function(i,el) {
			var padding = $(el).closest('.mauer-stills-gallery-pswp-wrapper').outerWidth() * 0.05 + 32; /* should be consistent with the value in for .entry-thumb .entry-thumb-inner in style.css of the theme */
			$(el).css('padding-bottom', padding + 'px');
		});
	}
}






$(document).ready(function(){
	adjustImagesInGalleries();
	markTheNextToLastImageInTheLastPswpRow();
	$(window).on("pswpAndSharedaddyWidthsAdjusted", function() {addResponsivePaddingToFlexItems();});
});

$(window).load(function(){
	adjustImagesInGalleries();
	markTheNextToLastImageInTheLastPswpRow();
});

$(window).resize(function(){
	adjustImagesInGalleries();
	markTheNextToLastImageInTheLastPswpRow();
	addResponsivePaddingToFlexItems();
});


})(jQuery);