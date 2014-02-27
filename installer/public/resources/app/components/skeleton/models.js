define([], function(){
    'use strict';

    return function(UMI){
        /**
         * Создает экземпляры DS.Model
         *
         * @param {array} Массив обьектов
         * */
        UMI.modelsFactory = function(collections){
            var i;
            var j;
            var collection;
            var fields = {};
            var fieldValue;
            var paramsRelation = {};
            var relationName;
            var lowerFirstLetter = function(string){
                return string.charAt(0).toLowerCase() + string.slice(1);
            };

            for(j = 0; j < collections.length; j++){
                collection = collections[j];

                for(i = 0; i < collection.fields.length; i++){
                    switch(collection.fields[i].type){
                        case 'string':
                            fieldValue = DS.attr('string');
                            break;
                        case 'number': case 'counter':
                            fieldValue = DS.attr('number');
                            break;
                        case 'bool':
                            fieldValue = DS.attr('boolean');
                            break;
                        case 'dateTime':
                            fieldValue = DS.attr('date');
                            break;
                        case 'belongsToRelation':
                            paramsRelation = {async: true};
                            relationName = lowerFirstLetter(collection.fields[i].targetCollection);
                            fieldValue = DS.belongsTo(relationName, paramsRelation);
                            break;
                        case 'hasManyRelation':
                            paramsRelation = {async: true, inverse: collection.fields[i].targetField};
                            relationName = lowerFirstLetter(collection.fields[i].targetCollection);
                            fieldValue = DS.hasMany(relationName, paramsRelation);
                            break;
                        case 'manyToManyRelation':
                            paramsRelation = {async: true, inverse: collection.fields[i].targetFieldName};
                            relationName = lowerFirstLetter(collection.fields[i].bridgeCollection);
                            fieldValue = DS.hasMany(relationName, paramsRelation);
                            break;
                        default:
                            fieldValue = DS.attr();
                            break;
                    }
                    if(collection.fields[i].type !== 'identify'){
                        fields[collection.fields[i].name] = fieldValue;
                    }
                }

                UMI[collection.name] = DS.Model.extend(fields);

            }
            setModelsNameSpace(collection.name);
        };

       var setModelsNameSpace = function(namespace){
            UMI[namespace + 'Adapter'] = DS.UmiRESTAdapter.extend({
                namespace: window.UmiSettings.baseUrl + '/api/resource'
            });
       };

        UMI.ComponentMode = DS.Model.extend({
            modes: DS.attr()
        });

        UMI.ComponentModeAdapter = DS.RESTAdapter.extend({
            namespace: 'resource/admin/modules/news/categories',
            buildURL: function(record, suffix){
                var s = this._super(record, suffix);
                return s + ".json";
            }
        });
    };
});