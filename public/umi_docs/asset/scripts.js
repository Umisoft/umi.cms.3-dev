/* Toggle */
(function() {
	var event = function(handler) {
		jQuery(handler.controlled).toggleClass('active');
		jQuery(handler).parent().toggleClass('active');
	}, handler;
	jQuery('.media-body').each(function() {
		if( jQuery('.media-heading', this).has('.handler').length) {
			handler = jQuery('.handler', this)[0];
			handler.controlled = jQuery('.media-list', this)[0];
			jQuery(handler).on('click', function() {
				event(this);
			});
		}
	});
})();

/* Adaptive */
jQuery('#js-menu-main').on('click', function() {
	jQuery(this).closest('nav').toggleClass('drop');
});

jQuery('.mobile-button').on('click', function() {
	jQuery('.dropdown + ul' ,this.parent).toggleClass('drop');
	return false;
});

/* Adaptive aside */
var page = jQuery('.page > .container'),
	aside = jQuery('aside');
if(jQuery.contains(page[0], aside[0])) {
	$( "body > header" ).after( '<a id="js-menu-aside"><span>Навигация по разделу</span><svg version="1.1" width="20px" height="10px" viewBox="0 0 100 100"><path d="M69.746,53.696l-32.1,32.099c-2.042,2.042-5.352,2.042-7.393,0c-2.041-2.041-2.041-5.352,0-7.393L58.656,50L30.254,21.598c-2.041-2.042-2.041-5.352,0-7.393c2.041-2.042,5.351-2.042,7.393,0l32.1,32.099c1.02,1.021,1.53,2.358,1.53,3.696S70.766,52.676,69.746,53.696z"/></svg></a>' )
}

jQuery('#js-menu-aside').on('click', function() {
	jQuery('.page aside').toggleClass('drop');
	jQuery('#js-menu-aside').toggleClass('active');
	if (jQuery('.page aside').hasClass('drop')) {
		jQuery('#js-menu-aside span').text('Скрыть меню');
	} else {
		jQuery('#js-menu-aside span').text('Навигация по разделу');
	}
})