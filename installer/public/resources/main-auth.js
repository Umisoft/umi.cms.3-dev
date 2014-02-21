require(['./config'], function(){
	'use strict';
	require(['auth/main'], function(Auth){
		Auth();
	});
});