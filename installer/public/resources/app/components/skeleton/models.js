define(['./app'],	function(UMI){
	'use strict';

	return function(){
		/**
		 * Создает экземпляры DS.Model
		 *
		 * @param {array} Массив обьектов
		 * */
		UMI.FactoryForModels = function(models){
			var i;
			var j;
			var model;
			var properties = {};
			var propertyValue;
			var relationshipParams;

			for(j = 0; j < models.length; j++){
				model = models[j];

				for(i = 0; i < model.properties.length; i++){
					switch(model.properties[i].type){
						case 'string':
							propertyValue = DS.attr('string');
							break;
						case 'number':
							propertyValue = DS.attr('number');
							break;
						case 'boolean':
							propertyValue = DS.attr('boolean');
							break;
						case 'date':
							propertyValue = DS.attr('date');
							break;
						case 'relationship':
							if(model.properties[i].relationship.params){
								propertyValue = DS[model.properties[i].relationship.type](model.properties[i].relationship.name, model.properties[i].relationship.params);
							} else{
								propertyValue = DS[model.properties[i].relationship.type](model.properties[i].relationship.name);
							}
							break;
						default:
							propertyValue = DS.attr();
							break;
					}
					properties[model.properties[i].name] = propertyValue;
				}

				UMI[model.name] = DS.Model.extend(properties);

				if(model.resource){
					UMI[model.name + 'Adapter'] = UMI.RESTAdapter.extend({
						namespace: model.resource,
						buildURL: function(record, suffix) {
							var s = this._super(record, suffix);
							return s + ".php";
						}
					});
				}
			}
		};


		UMI.ComponentMode = DS.Model.extend({
			modes: DS.attr()
		});

		UMI.ComponentModeAdapter = DS.RESTAdapter.extend({
			namespace: 'resource/admin/modules/news/categories',
			buildURL: function(record, suffix) {
				var s = this._super(record, suffix);
				return s + ".json";
			}
		});
	};
});