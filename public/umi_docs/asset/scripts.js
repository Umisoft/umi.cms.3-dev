/* Toggle */
(function() {
	var event = function(handler) {

		/**((" " + handler.controlled.className + " ").indexOf(" " + 'active' + " ") != -1) ?
			handler.controlled.className = (" " + handler.controlled.className + " ")
				.replace(new RegExp(" " + 'active' + " ", "g"), " ")
				.replace(/^\s+/, "").replace(/\s+$/, "") :
			handler.controlled.className = (handler.controlled.className + ' active')
				.replace(/^\s+/, "");**/
		jQuery(handler.controlled).toggleClass('active');
		jQuery(handler).toggleClass('active');

		if(handler.attributes.switcher){
			var name = handler.attributes.switcher.value;
			handler.attributes.switcher.value = handler.innerHTML;
			handler.innerHTML = name;
		}
	};
	var toggle_case = document.getElementsByClassName('media-body'), handler, i;

	for (i = 0; i < toggle_case.length; i++) {
		handler = toggle_case.item(i).getElementsByClassName('handler').item(0);
		handler.controlled = toggle_case.item(i).getElementsByClassName('media-list').item(0);
		jQuery(handler).on('click', function() {
			event(this);
		});
	}
})();

/* Adaptive */
var mainNav = jQuery('#js-menu-main');

mainNav.on('click', function() {
	jQuery(this).closest('nav').toggleClass('drop');
});

var dropHandler = jQuery('.mobile-button');

dropHandler.on('click', function() {
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