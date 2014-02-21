define(['App','Modernizr', 'Foundation','iscroll', 'ckEditor', 'jQuery'], function(UMI){
	'use strict';

	return function(){
		$('.qunit-container-button').click(function(){
			$('.qunit-container').toggle();
		});
		require(
			[
				'topBar',
				'dock',
				'tableControl',
				'tree',
				'form',
				'search'
			],

			function(){
				UMI.advanceReadiness();
			}
		);
		return UMI;
	};
});