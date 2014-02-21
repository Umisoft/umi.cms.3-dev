require(['./config'], function(){
	'use strict';

	require(['app/main'], function(application){
		application();
	});
});