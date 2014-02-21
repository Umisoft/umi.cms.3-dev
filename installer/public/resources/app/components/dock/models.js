define(['App'], function(UMI){
	'use strict';

	return function(){
		// определяем модель для списка модулей
		UMI.ModuleList = DS.Model.extend({
			slug: DS.attr('string'),
			title: DS.attr('string'),
			componentList: DS.hasMany('componentList'),
			img: function(){
				return '/resource/admin/modules/' + this.get('slug') + '/icon.png';
			}.property()
		});

		// определяем модель для списка компонентов
		UMI.ComponentList = DS.Model.extend({
			slug: DS.attr('string'),
			title: DS.attr('string'),
			resource: DS.attr('string')
		});
	};
});