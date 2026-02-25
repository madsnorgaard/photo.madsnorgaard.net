(function ($) {


"use strict";


function adjustImagesIn3ColGridLayout() {

	$('.posts-grid-3cols .mauer-stills-img-box').each(function() {

		var extraPaddingForSquarelike = 12; // (in %)

		var width = $(this).outerWidth();
		var height = $(this).outerHeight();
		var ratio = width / height;
		var padding = 0;

		if (ratio >= 1) {
			padding = (width - height) / width * 100 * 0.5;
			// add extra padding to square images (understanding square as 5/6 <= ratio <= 6/5 )
			var extraPaddingHere = 0;
			if (ratio >= 5/6 && ratio <= 6/5 ) {
				padding = padding + extraPaddingForSquarelike;
				extraPaddingHere = extraPaddingForSquarelike * ratio;
			}
			$(this).closest('.image-imaginary-square-and-title').css({'padding' : padding.toFixed(0) + '%' + ' ' + extraPaddingHere.toFixed(0) + '%'});
		}
		if (ratio < 1) {
			padding = (height - width) / height * 100 * 0.5;
			// add extra padding to square images (understanding square as 5/6 <= ratio <= 6/5 )
			var extraPaddingHere = 0;
			if (ratio >= 5/6 && ratio <= 6/5 ) {
				padding = padding + extraPaddingForSquarelike;
				extraPaddingHere = extraPaddingForSquarelike * ratio;
			}
			$(this).closest('.image-imaginary-square-and-title').css({'padding' : extraPaddingHere.toFixed(0) + '%' + ' ' + padding.toFixed(0) + '%',});
		}
	});


	$('.posts-grid-3cols .portfolio-project-tile').each(function(i){
		if ( ((i+1) % 3 == 1) && $(this).hasClass('portfolio-project-tile-with-horizontal-img') )  {
			var tiles = $('.posts-grid-3cols .portfolio-project-tile');
			var theClass = 'portfolio-project-tile-with-horizontal-img';
			// if all three in this row have the class (or the last two are absent)
			if ( tiles.eq(i).hasClass(theClass) && (tiles.eq(i+1).hasClass(theClass) || !tiles.eq(i+1).length) && (tiles.eq(i+2).hasClass(theClass) || !tiles.eq(i+2).length) ) {
				// if all three in the preceding row have the class (or are absent)
				if ( ( (i>=3) && (tiles.eq(i-3).hasClass(theClass) && tiles.eq(i-2).hasClass(theClass) && tiles.eq(i-1).hasClass(theClass) ) ) || (i<3) ) {
					tiles.eq(i).addClass('smaller-top-padding-md'); tiles.eq(i+1).addClass('smaller-top-padding-md'); tiles.eq(i+2).addClass('smaller-top-padding-md');
				}
				// if all three in the following row have the class (or are absent)
				if ( (tiles.eq(i+3).hasClass(theClass) || !tiles.eq(i+3).length) && (tiles.eq(i+4).hasClass(theClass) || !tiles.eq(i+4).length) && (tiles.eq(i+5).hasClass(theClass) || !tiles.eq(i+5).length) ) {
					tiles.eq(i).addClass('smaller-bottom-padding-md'); tiles.eq(i+1).addClass('smaller-bottom-padding-md'); tiles.eq(i+2).addClass('smaller-bottom-padding-md');
				}
			}
		}
	});

	$('.posts-grid-3cols .portfolio-project-tile').each(function(i){
		if ( ((i+1) % 2 == 1) && $(this).hasClass('portfolio-project-tile-with-horizontal-img') )  {
			var tiles = $('.posts-grid-3cols .portfolio-project-tile');
			var theClass = 'portfolio-project-tile-with-horizontal-img';
			// if the two in this row have the class (or the last one is absent)
			if ( tiles.eq(i).hasClass(theClass) && (tiles.eq(i+1).hasClass(theClass) || !tiles.eq(i+1).length) ) {
				// if the two in the preceding row have the class (or are absent)
				if ( ( (i>=2) && (tiles.eq(i-2).hasClass(theClass) && tiles.eq(i-1).hasClass(theClass) ) ) || (i<2) ) {
					tiles.eq(i).addClass('smaller-top-padding-sm'); tiles.eq(i+1).addClass('smaller-top-padding-sm');
				}
				// if the two in the following row have the class (or are absent)
				if ( (tiles.eq(i+2).hasClass(theClass) || !tiles.eq(i+2).length) && (tiles.eq(i+3).hasClass(theClass) || !tiles.eq(i+3).length) ) {
					tiles.eq(i).addClass('smaller-bottom-padding-sm'); tiles.eq(i+1).addClass('smaller-bottom-padding-sm');
				}
			}
		}
	});

}




function createSpecialMarksFor3ColGridLayout() {
	$('.posts-grid-3cols').each(function(){
		var theFirstThree = $(this).find('.posts-grid-3cols-item').slice(0,3);
		var theFirstThreeAreHorizontalOrSquare = true;
		theFirstThree.each(function(){
			if ($(this).find('.portfolio-project-tile').hasClass('portfolio-project-tile-with-vertical-img')) {theFirstThreeAreHorizontalOrSquare = false;}
		});

		var theLastThree = $(this).find('.posts-grid-3cols-item').slice($(this).find('.posts-grid-3cols-item').length-3, $(this).find('.posts-grid-3cols-item').length);
		var theLastThreeAreHorizontalOrSquare = true;
		theLastThree.each(function(){
			if ($(this).find('.portfolio-project-tile').hasClass('portfolio-project-tile-with-vertical-img')) {theLastThreeAreHorizontalOrSquare = false;}
		});

		if (theFirstThreeAreHorizontalOrSquare) {$(this).addClass('the-first-three-are-horizontal-or-square');}
		else {$(this).removeClass('the-first-three-are-horizontal-or-square');}

		if (theLastThreeAreHorizontalOrSquare) {$(this).addClass('the-last-three-are-horizontal-or-square');}
		else {$(this).removeClass('the-last-three-are-horizontal-or-square');}

	});
}




// This will check if there's flexbox support
// proposed by Jonathan Neal here: https://github.com/jonathantneal/flexibility/blob/master/SUPPORT.md
function supportsFlexBox() {
	var test = document.createElement('test');
	test.style.display = 'flex';
	return test.style.display === 'flex';
}




// This elegant idea for a 2-col Masonry-looking (and actually Masonry-free) grid
// comes from Mark (https://stackoverflow.com/users/747272/mark)
// http://jsfiddle.net/gK2Vn/, has been tuned to suit this particular setting.
function build2ColFlowLayout() {
	$('.posts-flow-2-cols').each(function() {

		var posts = $(this).find('.posts-flow-2-cols-item');

		var leftColHeight = 0;
		var rightColHeight = 0;
		for (var i = 0; i < posts.length; i++) {
			posts.eq(i).removeClass('posts-flow-2-cols-item-right').removeClass('posts-flow-2-cols-item-left');
			if (leftColHeight > rightColHeight) {
				rightColHeight+= posts.eq(i).addClass('posts-flow-2-cols-item-right').outerHeight(true);
			} else {
				leftColHeight+= posts.eq(i).addClass('posts-flow-2-cols-item-left').outerHeight(true);
			}
		}


	});
}




// If none of the three related projects has a vertical featured image, add a special class that will let the whole block have better proportions.
function adjustNonVerticalRelatedProjects() {

	var allAreHorizontal = true;

	$('.mauer-more-projects-wrapper .posts-grid-3cols img').each(function() {
		var width = $(this).width();
		var height = $(this).height();
		var ratio = width / height;
		var padding = 0;
		// understanding square
		if (ratio <= 5/6) {
			allAreHorizontal = false;
			return false; // this exits the each() loop
		}
	});

	if (allAreHorizontal) { $('.mauer-more-projects-wrapper').addClass('mauer-more-projects-wrapper-all-horizontal'); }

}




function adjustAdminBarPositioning() {
	if ($(window).width() <= 600) {
		$('#wpadminbar').css('position','fixed');
	}
}




function adjustLogoLinkMaxWidth() {
	var mw = $('.top-stripe-holder').width() - $('#mauer-hamburger').width() - 10;
	$('.logo-link').css('max-width', mw + 'px');
}




function bodyBottomMarginByFooterHeight() {
	$('body').css('margin-bottom', $('#footer').outerHeight() + 'px');
}




function tilesRevealer() {
	$(".portfolio-project-tile, .entry-in-feed").not(".shown").each(function(i){
		var element = $(this);
		setTimeout(function(){
			element.addClass("shown");
		}, (i+1)*125);
	});
}




function adjustAdminBar() {
	if($('body').hasClass('admin-bar')) {
		$('html').css('min-height', $(window).height() - $('#wpadminbar').height() + 'px');
		$('.top-stripe').css('top', $('#wpadminbar').height() + 'px');
	}
}




function markWidgetAreaMultilevelLists() {
	$('.widget ul, .widget ol').each(function() {
		if ($(this).children('.menu-item-has-children').length) {
			$(this).addClass('menu-has-grandchildren');
		}
	});
}




function controlWidgetInputsMaxWidth() {
	var widgetWidth = $('.widget').width();
	var widgetInnerElementWidth = $('.widget > *:last-child').width();
	$('.widget input, .widget select').css('max-width', widgetInnerElementWidth + 'px');
	$('.widget select').css('cssText', 'width:' + widgetInnerElementWidth + 'px!important;');
}




function commentFormHighlightNextBorder() {

	$('.comment-respond p.comment-form-author input')
		.mouseenter(function() {
			var urlInput = $(this).closest('p.comment-form-author').next('p.comment-form-email').find('input');
			if (!urlInput.hasClass('mouse-in-the-preceding-input')) {urlInput.addClass(('mouse-in-the-preceding-input'));}
		})
		.mouseleave(function() {
			var urlInput = $(this).closest('p.comment-form-author').next('p.comment-form-email').find('input');
			if (urlInput.hasClass('mouse-in-the-preceding-input')) {urlInput.removeClass(('mouse-in-the-preceding-input'));}
		})
		.focus(function() {
			var urlInput = $(this).closest('p.comment-form-author').next('p.comment-form-email').find('input');
			if (!urlInput.hasClass('focus-on-the-preceding-input')) {urlInput.addClass(('focus-on-the-preceding-input'));}
		})
		.focusout(function() {
			var urlInput = $(this).closest('p.comment-form-author').next('p.comment-form-email').find('input');
			if (urlInput.hasClass('focus-on-the-preceding-input')) {urlInput.removeClass(('focus-on-the-preceding-input'));}
		});

	$('.comment-respond p.comment-form-email input')
		.mouseenter(function() {
			var urlInput = $(this).closest('p.comment-form-email').next('p.comment-form-url').find('input');
			if (!urlInput.hasClass('mouse-in-the-preceding-input')) {urlInput.addClass(('mouse-in-the-preceding-input'));}
		})
		.mouseleave(function() {
			var urlInput = $(this).closest('p.comment-form-email').next('p.comment-form-url').find('input');
			if (urlInput.hasClass('mouse-in-the-preceding-input')) {urlInput.removeClass(('mouse-in-the-preceding-input'));}
		})
		.focus(function() {
			var urlInput = $(this).closest('p.comment-form-email').next('p.comment-form-url').find('input');
			if (!urlInput.hasClass('focus-on-the-preceding-input')) {urlInput.addClass(('focus-on-the-preceding-input'));}
		})
		.focusout(function() {
			var urlInput = $(this).closest('p.comment-form-email').next('p.comment-form-url').find('input');
			if (urlInput.hasClass('focus-on-the-preceding-input')) {urlInput.removeClass(('focus-on-the-preceding-input'));}
		});

}




function adjustPswpAndSharedaddyWidth() {

	if (!$('body').hasClass('page-template-page-alternative')) {

		$(".entry-full .mauer-stills-gallery-pswp-wrapper, .entry-full .mauer-stills-gallery-pswp-wrapper, .single-project .mauer-stills-gallery-pswp-wrapper + .sharedaddy.sd-sharing-enabled, .single-project .wp-block-mauer-stills-gallery + .sharedaddy.sd-sharing-enabled").each(function() {

			// we want to adjust sharedaddy only if the last thing in content is PSWP and if there are no comments on the page

			if ($(this).hasClass("sharedaddy") && $('#comments').length) {return false;} // exit each() as sharedaddy always comes after PSWP and embed-*

			$(this).width($('.mauer-container-fluid-with-max-width').width());
			$(window).trigger("pswpAndSharedaddyWidthsAdjusted");
			$(this).css({
				position: "relative",
				left: $(this).parent().width()/2 - $(this).width()/2,
			});

		});

	}

}




function provideSettingsForScaling() { // used for adaptable images, PSWPs, iframes
	// Settings
	// basically this means we want an element with 4/3 ratio always fit 94% of viewport height.
	var r = { coeff: 0.94, basicRatio: 4/3 };
	return r;
}




function adjustAdaptableImagesAndPswpMaxDimensions() {

	var settingsForScaling = provideSettingsForScaling();
	var coeff = settingsForScaling.coeff;
	var basicRatio = settingsForScaling.basicRatio;

	var maxHeight = $(window).height() * coeff;
	var maxWidth = maxHeight * basicRatio;

	if (maxWidth >= 1068) {maxWidth = 1068;}

	$.fn.addSpecialMaxWidth = function() {
		return this.each(function() {
			var particularMaxWidth = Math.min($(this).parent().width(), maxWidth);
			$(this).css('max-width', particularMaxWidth + 'px');
		});
	};

	$('.mauer-stills-img-adaptable-height-and-width').closest('.mauer-stills-img-box-wrapper').addSpecialMaxWidth();
	$('.mauer-stills-gallery-pswp, .mauer-more-projects-wrapper, .single-project .mauer-stills-gallery-pswp-wrapper + .sharedaddy.sd-sharing-enabled .sd-block, .single-project .wp-block-mauer-stills-gallery + .sharedaddy.sd-sharing-enabled .sd-block').addSpecialMaxWidth();

	// as direct max-height and height control is not possible with the padding-based aspect-ratio-box method,
	// we're constraining height by constraining width based on the aspect ratio.
	$('.mauer-stills-img-adaptable-height, .mauer-stills-img-adaptable-height-and-width').each(function(){
		if ($(this).data('aspectRatio')) {
			var imgAspectRatio = $(this).data('aspectRatio');
			var imgMaxWidth = maxHeight * imgAspectRatio;
			$(this).closest('.mauer-stills-img-box-wrapper').css('max-width', imgMaxWidth + 'px');
		}
	});

	setTimeout(adjustImageCaptionsWidth, 100);

}




function adjustImageCaptionsWidth() {
	$('.mauer-stills-gallery-pswp-tile').each(function() {
		var width = $(this).find('.mauer-stills-img-box').outerWidth();
		$(this).find('figcaption').width(width);
	});
}




function adjustIframesDimensions() {

	// make embedded iframes wrappers in the entry-full area wider.
	var settingsForScaling = provideSettingsForScaling();
	var coeff = settingsForScaling.coeff;
	var basicRatio = settingsForScaling.basicRatio;
	var heightToFitInto = $(window).height() * coeff;
	var widthToFitInto = heightToFitInto * basicRatio;

	if (widthToFitInto >= $('.container-fluid').width()) {
		widthToFitInto = $('.container-fluid').width();
		heightToFitInto = widthToFitInto / basicRatio;
	}

	$('.entry-full .wp-block-embed:not(.alignfull):not(.alignwide) .mauer-wp-embed-wrapper').each(function(){

		if ($(this).closest('.wp-block-embed:not(.alignfull):not(.alignwide)').length) { var targetElement = $(this).closest('.wp-block-embed'); } // Gutenberg
		else { var targetElement = $(this);} // old school embed

		// to fit the iframe into our dimensions, we first see if given the width, it will still fit the height
		if ($.isNumeric(targetElement.find('iframe').attr('width')) && $.isNumeric(targetElement.find('iframe').attr('height'))) {
			var aspectRatio = targetElement.find('iframe').attr('width') / targetElement.find('iframe').attr('height');

			if (aspectRatio >= basicRatio) {
				targetElement.width(widthToFitInto).css('max-width', 'none');
				targetElement.find('iframe').width(widthToFitInto).height(widthToFitInto / aspectRatio).css('max-width', 'none');

				targetElement.css({
				'position': 'relative',
				'left': ($('.entry-full .entry-content').width() - widthToFitInto)/2
				});
			}
			else {
				targetElement.find('iframe').height(heightToFitInto).width(heightToFitInto * aspectRatio).css('max-width', 'none');
			}
		}

	});


	// preserve aspect ratio of all iframes that have width and height attributes set.
	$('iframe').each(function(i){
		if ($.isNumeric($(this).attr('width')) && $.isNumeric($(this).attr('height'))) {
			var aspectRatio = $(this).attr('width') / $(this).attr('height');
			$(this).height($(this).width() / aspectRatio);
		}
	});

}




function isTouchDevice() {
	return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
	// http://stackoverflow.com/questions/3974827/detecting-touch-screen-devices-with-javascript#answer-33604438
}




function masonryInitializer() {
	// init Masonry
	$('.masonry-grid .posts-grid-content').imagesLoaded().progress(function(){
		var $grid = $('.masonry-grid .posts-grid-content').masonry({
			itemSelector: '.masonry-grid-item',
			columnWidth: '.masonry-grid-item',
			percentPosition: true,
			isAnimated: false,
		});
	});

}




function loadMoreInitializer() {
	// ajax call for loading more posts

	$("#mauer-ajax-load-more").on("click", function(e) {
		e.preventDefault();
		$(this).html($(this).data('loadingText')).addClass('pulse');
		$(this).prop('disabled', true);
		$.ajax({
			url:$(this).data("nextPostsLink"),
			success: function(response) {
				// masonry
				if ($('#mauer-ajax-load-more').hasClass('more-masonry')) {
					var data = $(response).find(".masonry-grid .posts-grid-content").html();
					var $data = $(data);
					$("#mauer-ajax-buffer").html($data).imagesLoaded(function(){
						$("#mauer-ajax-buffer").html("");

						$(".masonry-grid .posts-grid-content").append($data);
						$(".masonry-grid .posts-grid-content").masonry('appended', $data);
						tilesRevealer();

						var nextLink = $(response).find('#mauer-ajax-load-more').data("nextPostsLink");
						var button = $('#mauer-ajax-load-more');
						if (nextLink) {button.data('nextPostsLink', nextLink).html(button.data('loadedText')).prop('disabled', false).removeClass('pulse');}
						else {button.html("").addClass('state-2'); $('.mauer-ajax-load-more-col').addClass('state-2');}
					});

				}
				// grid
				if ($('#mauer-ajax-load-more').hasClass('more-grid')) {
					var data = $(response).find(".posts-grid-content").html();
					var $data = $(data);
					$("#mauer-ajax-buffer").html($data).imagesLoaded(function(){
						$(".posts-grid-content").append($data);
						$("#mauer-ajax-buffer").html("");
						tilesRevealer();
						adjustImagesIn3ColGridLayout();
						createSpecialMarksFor3ColGridLayout();
						bodyBottomMarginByFooterHeight();
						build2ColFlowLayout();

						var nextLink = $(response).find('#mauer-ajax-load-more').data('nextPostsLink');
						var button = $('#mauer-ajax-load-more');
						if (nextLink) {button.data('nextPostsLink', nextLink).html(button.data('loadedText')).prop('disabled', false).removeClass('pulse');}
						else {button.html("").addClass('state-2'); $('.mauer-ajax-load-more-col').addClass('state-2');}
					});
				}

				$(window).trigger('moreContentLoaded');

				// trigger Jetpack lazy loading for newly added content
				document.body.dispatchEvent(new Event('is.post-load')); // Jetpack >= 8.4
				$(document.body).trigger('post-load'); // Jetpack < 8.4

			}
		});
	});
}




function searchPopupController() {
	var elementsToBlur = $('.section-header, .section-main-content, #footer');
	$(".search-popup-opener").on("click", function(e) {
		e.preventDefault();
		$(".search-popup").addClass('shown');
		elementsToBlur.addClass('mauer-blur-filter');
		setTimeout(function(){
			$(".search-popup .search-input").focus();
		}, 200); // needs to be greater than the animation duration
	});

	$(".search-popup-closer").on("click", function(e) {
		e.preventDefault();
		$(".search-popup").removeClass('shown');
		elementsToBlur.removeClass('mauer-blur-filter');
	});

	$(document).keydown(function(e) {
		if (e.keyCode == 27) {
			$(".search-popup").removeClass('shown');
			elementsToBlur.removeClass('mauer-blur-filter');
		}
	});
}




function adjustSearchPopupOffset() {
	if($('body').hasClass('admin-bar')) {
		$('.search-popup').css('top', $('#wpadminbar').height() + 'px');
	}
}




// This is inspired by Justin Hileman's snippet, http://justinhileman.info/article/a-jquery-widont-snippet/
function adjustOrphans() {
	$('.entry-title, .portfolio-welcome-phrase').each(function() {
		var targetElement = $(this);
		if ($(this).children().length) {
			targetElement = $(this).children(":first");
		}
		targetElement.html(targetElement.html().replace(/\s([^\s<]{0,5})\s*$/,'<i class="mauer-nbsp">&nbsp;</i><i class="mauer-sp"> </i>$1'));
	});
}




function deleteCookie(name) {document.cookie = name + '=; max-age=0; path=/';}

function fontAndColorSchemesPreviewSwitcher() {

	$("li.fonts-and-colors-switcher a").on("click", function(e) {

		e.preventDefault();
		var parent = $(this).parent('li.fonts-and-colors-switcher');
		var maxAge = 60 * 30; // in seconds

		if (parent.hasClass('colors-example-1')) {deleteCookie('preview_color_scheme');}
		if (parent.hasClass('colors-example-2')) {document.cookie = "preview_color_scheme=2; max-age=" + maxAge + "; path=/";}
		if (parent.hasClass('colors-example-3')) {document.cookie = "preview_color_scheme=3; max-age=" + maxAge + "; path=/";}

		if (parent.hasClass('fonts-example-1')) {deleteCookie('preview_font_scheme');}
		if (parent.hasClass('fonts-example-2')) {document.cookie = "preview_font_scheme=2; max-age=" + maxAge + "; path=/";}
		if (parent.hasClass('fonts-example-3')) {document.cookie = "preview_font_scheme=3; max-age=" + maxAge + "; path=/";}
		if (parent.hasClass('fonts-example-4')) {document.cookie = "preview_font_scheme=4; max-age=" + maxAge + "; path=/";}

		window.location.reload(true);

	});
}




// this marks images only if there's no active Jetpack Lazy Images module running, or if we are in preview mode;
// the latter is done because of Jetpack not appending its class to loaded images when in preview mode
function markLoadedImages() {
	if (!$('html').hasClass('jetpack-lazy-images-js-enabled') || $('body').hasClass('viewing-post-in-preview-mode')) {
		$('.mauer-stills-img-box').each(function() {
			var elem = $(this);
			$(this).imagesLoaded(function(){
				elem.css('background-color','transparent').find('img').addClass('mauer-stills-img-loaded');
			});
		});
	}
}




// this marks images only if Jetpack Lazy Images module is running
function markLoadedJetpackLazyImages() {
	$(document.body).on('jetpack-lazy-loaded-image', function (e) {
		var elem = $(e.target);
		elem.imagesLoaded(function(){
			elem.closest('.mauer-stills-img-box').css('background-color','transparent').find('img').addClass('mauer-stills-jetpack-lazy-img-loaded');
		});
	});
}




// WP has started displaying block image elements as tables, which has started
// to prevent the img-boxes added for lazy images from fitting the width of the parent,
// as table elements have issues obeying the max-width rule; so we are taking care of this via js
function adjustBlockImagesMaxWidth() {
	$('.wp-block-image .aligncenter .mauer-stills-img-box, .wp-block-image .alignleft .mauer-stills-img-box, .wp-block-image .alignright .mauer-stills-img-box, .wp-block-image.is-resized .mauer-stills-img-box').each(function(){
		var elDefaultWidth = $(this).data('mauerStillsDefaultWidth');
		var entryContentWidth = $(this).closest('.entry-content').width();
		if (elDefaultWidth >= entryContentWidth) {
			$(this).width(entryContentWidth);
		} else {
			$(this).width($(this).data('mauerStillsDefaultWidth'));
		}
	});
}




// This provides legacy support for classic mode. Used for assigning margins.
function markNonAlignedImagesAddedInClassicMode() {
	$('img:not(.aligncenter):not(.alignleft):not(.alignright)').each(function() {
		if (!$(this).parents('.wp-block-image').length) {
			$(this).closest('.mauer-stills-img-box-wrapper').addClass('non-aligned-image-added-in-classic-mode');
		}
	});
}




// Unwrapping the images in standard WP galleries.
function unwrapTheImagesInStandardWpGalleries() {
	$('img').each(function() {
		if ($(this).parents('.wp-block-gallery').length) {
			$(this).closest('.mauer-stills-img-box').unwrap('.mauer-stills-img-box-wrapper');
			$(this).unwrap('.mauer-stills-img-box');
		}
	});
}




// Unwrapping the masked images.
function unwrapTheCircleMaskedImages() {
	$('img').each(function() {
		if ($(this).parents('.wp-block-image.is-style-circle-mask').length) {
			$(this).closest('.mauer-stills-img-box').unwrap('.mauer-stills-img-box-wrapper');
			$(this).unwrap('.mauer-stills-img-box');
		}
	});
}






$(document).ready(function() {
	adjustImagesIn3ColGridLayout();
	createSpecialMarksFor3ColGridLayout();
	build2ColFlowLayout();
	masonryInitializer();
	adjustPswpAndSharedaddyWidth();
	adjustAdaptableImagesAndPswpMaxDimensions();
	adjustIframesDimensions();
	bodyBottomMarginByFooterHeight();
	adjustOrphans();
	$(".mauer-preloader").addClass("mauer-preloader-hidden");
	if (isTouchDevice()) {$('body').addClass('mauerTouchDevice');}
	adjustBlockImagesMaxWidth();
	markLoadedJetpackLazyImages();
	markLoadedImages();
	$(window).on("moreContentLoaded", function() {markLoadedImages();});
	adjustLogoLinkMaxWidth();
	adjustAdminBar();
	commentFormHighlightNextBorder();
	adjustAdminBarPositioning();
	markWidgetAreaMultilevelLists();
	searchPopupController();
	adjustSearchPopupOffset();
	autosize($('textarea'));
	fontAndColorSchemesPreviewSwitcher();
	markNonAlignedImagesAddedInClassicMode();
	unwrapTheImagesInStandardWpGalleries();
	unwrapTheCircleMaskedImages();
});


$(window).load(function(){
	masonryInitializer();
	loadMoreInitializer();
	adjustBlockImagesMaxWidth();
	controlWidgetInputsMaxWidth();
	adjustNonVerticalRelatedProjects();
	bodyBottomMarginByFooterHeight();
	adjustSearchPopupOffset();
	if (!supportsFlexBox()){flexibility(document.body);}
	$(".mauer-preloader").addClass("mauer-preloader-hidden");
	setTimeout(tilesRevealer, 10);
});


var lastRecordedWidth = $(window).width();

$(window).resize(function(){
	if ($(window).width()!=lastRecordedWidth) {
		adjustImagesIn3ColGridLayout();
		createSpecialMarksFor3ColGridLayout();
		lastRecordedWidth = $(window).width();
	}
	adjustAdminBar();
	build2ColFlowLayout();
	setTimeout(adjustAdaptableImagesAndPswpMaxDimensions, 50);
	adjustIframesDimensions();
	adjustLogoLinkMaxWidth();
	adjustPswpAndSharedaddyWidth();
	adjustBlockImagesMaxWidth();
	adjustAdminBarPositioning();
	controlWidgetInputsMaxWidth();
	bodyBottomMarginByFooterHeight();
	searchPopupController();
});

})(jQuery);