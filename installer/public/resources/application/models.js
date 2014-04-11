define([], function(){
    'use strict';

    return function(UMI){

        var propertyFilters = {
            stringTrim: function(value){
                return value.replace(/^\s+|\s+$/g, '');
            },
            htmlSafe: function(value){
                return Ember.String.htmlSafe(value);
            }
        };

        DS.Model.reopen({
            validErrors: null,
            filterProperty: function(propertyName){
                if(this.get('filters').hasOwnProperty(propertyName)){
                    var filters = this.get('filters')[propertyName];
                    var value = this.get(propertyName);
                    var i;
                    for(i = 0; i < filters.length; i++){
                        Ember.assert('Фильтр "' + filters[i].type + '" не определен.', propertyFilters.hasOwnProperty(filters[i].type));
                        value = propertyFilters[filters[i].type](value);
                    }
                    this.set(propertyName, value);
                }
            },
            validateProperty: function(propertyName){
                if(this.get('validators').hasOwnProperty(propertyName)){
                    var validators = this.get('validators')[propertyName];
                    var i;
                    var value = this.get(propertyName);
                    var errors = [];
                    var activeErrors;
                    for(i = 0; i < validators.length; i++){
                        switch(validators[i].type){
                            case "required":
                                if(!value){
                                    errors.push({'message': validators[i].message});
                                }
                                break;
                            case "regexp":
                                var pattern = eval(validators[i].options.pattern); //TODO: Заменить eval
                                if(!pattern.test(value)){
                                    errors.push({'message': validators[i].message});
                                }
                                break;
                        }

                        if(errors.length){
                            activeErrors = this.get('validErrors');
                            if(activeErrors){
                                this.set('validErrors.' + propertyName, errors);
                            } else{
                                activeErrors = {};
                                activeErrors[propertyName] = errors;
                                this.set('validErrors',activeErrors);
                            }
                            if(this.get('isValid')){
                                this.send('becameInvalid');
                            }
                        } else if(!this.get('isValid')){
                            activeErrors = this.get('validErrors');
                            if(activeErrors && activeErrors.hasOwnProperty(propertyName)){
                                delete activeErrors[propertyName];
                            }
                            i = 0;
                            for(var error in activeErrors){
                                if(activeErrors.hasOwnProperty(error)){
                                    ++i;
                                }
                            }
                            activeErrors = i ? activeErrors : null;
                            this.set('validErrors', activeErrors);
                            this.send('becameValid');
                        }
                    }
                }
            },
            clearValidateForProperty: function(propertyName){
                var i;
                var activeErrors = this.get('validErrors');
                if(activeErrors && activeErrors.hasOwnProperty(propertyName)){
                    delete activeErrors[propertyName];
                }
                // Объект пересобирается без свойств прототипа
                i = 0;
                for(var error in activeErrors){
                    if(activeErrors.hasOwnProperty(error)){
                        ++i;
                    }
                }
                activeErrors = i ? activeErrors : null;
                this.set('validErrors', activeErrors);
            }
        });

        /**
         * Создает экземпляры DS.Model
         * @method modelsFactory
         * @param array Массив обьектов
         */
        UMI.modelsFactory = function(collections){
            var i;
            var j;
            var collection;
            var fieldValue;
            var params = {};

            for(j = 0; j < collections.length; j++){
                var fields = {};
                var filters = {};
                var validators = {};
                collection = collections[j];

                for(i = 0; i < collection.fields.length; i++){
                    switch(collection.fields[i].type){
                        /*case 'string':
                         params = {defaultValue: collection.fields[i]['default']};
                         fieldValue = DS.attr('string', params);
                         break;*/
                        case 'number':
                        case 'counter':
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
                            params = {defaultValue: collection.fields[i]['default']};
                            fieldValue = DS.attr('raw', params);
                            break;
                    }

                    if(collection.fields[i].filters){
                        filters[collection.fields[i].name] = collection.fields[i].filters;
                    }
                    if(collection.fields[i].validators){
                        validators[collection.fields[i].name] = collection.fields[i].validators;
                    }

                    if(collection.fields[i].type !== 'identify'){
                        fields[collection.fields[i].name] = fieldValue;
                    }
                }

                fields.filters = filters;// TODO: Зарезервировать эти свойсва со стороны back-and
                fields.validators = validators;

                UMI[collection.name.capitalize()] = DS.Model.extend(fields);

                UMI.__container__.lookup('store:main').metaForType(collection.name, {'collectionType': collection.type});// TODO: Найти рекоммендации на что заменить __container__

                if(collection.source){
                    UMI[collection.name.capitalize() + 'Adapter'] = DS.UmiRESTAdapter.extend({
                        namespace: collection.source.replace(/^\//g, '')
                    });
                }
            }
        };
    };
});