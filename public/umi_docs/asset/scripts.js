/* Toggle */
(function() {
	var event = function(handler) {

		((" " + handler.controlled.className + " ").indexOf(" " + 'active' + " ") != -1) ?
			handler.controlled.className = (" " + handler.controlled.className + " ")
				.replace(new RegExp(" " + 'active' + " ", "g"), " ")
				.replace(/^\s+/, "").replace(/\s+$/, "") :
			handler.controlled.className = (handler.controlled.className + ' active')
				.replace(/^\s+/, "");

		if(handler.attributes.switcher){
			var name = handler.attributes.switcher.value;
			handler.attributes.switcher.value = handler.innerHTML;
			handler.innerHTML = name;
		}
	};
	var toggle_case = document.getElementsByClassName('media-body'), handler, i;

	for (i = 0; i < toggle_case.length; i++) {
		handler = toggle_case.item(i).getElementsByClassName('media-heading').item(0);
		handler.controlled = toggle_case.item(i).getElementsByClassName('media-list').item(0);
		jQuery(handler).bind('click', function() {
			event(this);
		});
	}
})();