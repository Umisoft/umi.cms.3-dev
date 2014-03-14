define([], function(){
    'use strict';

    return function(UMI){
        /**
         * Создает экземпляры DS.Model
         * @method modelsFactory
         * @param array Массив обьектов
         */
        UMI.Utils.modelsFactory = function(collections){
            var i;
            var j;
            var collection;
            var fieldValue;
            var params = {};

            for(j = 0; j < collections.length; j++){
                var fields = {};
                collection = collections[j];

                for(i = 0; i < collection.fields.length; i++){
                    switch(collection.fields[i].type){
                        /*case 'string':
                            params = {defaultValue: collection.fields[i]['default']};
                            fieldValue = DS.attr('string', params);
                            break;*/
                        case 'number': case 'counter':
                            params = {defaultValue: collection.fields[i]['default']};
                            fieldValue = DS.attr('number', params);
                            break;
                       case 'bool':
                           params = {defaultValue: collection.fields[i]['default']};
                           fieldValue = DS.attr('boolean', params);
                           break;
                       case 'dateTime':
                           params = {defaultValue: collection.fields[i]['default']};
                           fieldValue = DS.attr('date', params);
                           break;
                        case 'belongsToRelation':
                           params = {async: true};
                           //TODO: инверсия избыточна, но DS почему то без неё не может
                           if(collection.fields[i].targetCollection === collection.name){
                               params.inverse = 'children';
                           }
                           fieldValue = DS.belongsTo(collection.fields[i].targetCollection, params);
                           break;
                       case 'hasManyRelation':
                           params = {async: true, inverse: collection.fields[i].targetField};
                           fieldValue = DS.hasMany(collection.fields[i].targetCollection, params);
                           break;
                       case 'manyToManyRelation':
                           params = {async: true, inverse: collection.fields[i].mirrorField};
                           fieldValue = DS.hasMany(collection.fields[i].targetCollection, params);
                            break;
                        default:
                           fieldValue = DS.attr();// TODO: заменить на raw
                           break;
                    }
                    if(collection.fields[i].type !== 'identify'){
                        fields[collection.fields[i].name] = fieldValue;
                    }
                }

                UMI[collection.name.capitalize()] = DS.Model.extend(fields);
            }
        };

        UMI.Utils.setModelsResources = function(resources){
            //TODO: Костыль убирающий первый слэш. Написать регулярку.
            var baseURL = window.UmiSettings.baseApiURL.slice(1);
            var setNamespace = function(namespace, module, component){
                UMI[namespace.capitalize() + 'Adapter'] = DS.UmiRESTAdapter.extend({
                    namespace: baseURL + '/' + module + '/' + component + '/collection'
                });
            };
            for(var i = 0; i < resources.length; i++){
                setNamespace(resources[i].collection, resources[i].module, resources[i].component);
            }
       };
    };
});