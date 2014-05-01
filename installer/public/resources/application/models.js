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
            needReloadHasMany: Ember.K,
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
            },
            loadedRelationshipsByName: {},
            changedRelationshipsByName: {},
            changeRelationshipsValue: function(property, value){
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');
                Ember.set(changedRelationships, property, value);
                var isDirty = false;
                var object = this;

                for(var key in changedRelationships){
                    if(!(key in loadedRelationships)){
                        isDirty = true;
                    } else if(Object.prototype.toString.call(loadedRelationships[key]).slice(8, -1) === 'Array' && Object.prototype.toString.call(changedRelationships[key]).slice(8, -1) === 'Array'){
                        if(loadedRelationships[key].length !== changedRelationships[key].length){
                            isDirty = true;
                        } else{
                            isDirty = changedRelationships[key].every(function(id){
                                if(loadedRelationships[key].contains(id)) { return true; }
                            });
                            isDirty = !isDirty;
                        }
                    } else if(loadedRelationships[key] !== changedRelationships[key]){
                        isDirty = true;
                    }
                }

                if(isDirty){
                    object.send('becomeDirty');
                } else{
                    var changedAttributes = object.changedAttributes();
                    if(JSON.stringify(changedAttributes) === JSON.stringify({})){
                        object.send('rolledBack');
                    }
                }
            },
            relationPropertyIsDirty: function(property){
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');
                var isDirty = false;

                if(changedRelationships.hasOwnProperty(property)){
                    Ember.assert('Не добавлена загруженная связь. После загрузки связей hasMany и ManyToMany необходимо добавлять их результат к loadedRelationshipsByName', loadedRelationships.hasOwnProperty(property));
                    if(Object.prototype.toString.call(loadedRelationships[property]).slice(8, -1) === 'Array' && Object.prototype.toString.call(changedRelationships[property]).slice(8, -1) === 'Array'){
                        if(loadedRelationships[property].length !== changedRelationships[property].length){
                            isDirty = true;
                        } else{
                            isDirty = changedRelationships[property].every(function(id){
                                if(loadedRelationships[property].contains(id)) { return true; }
                            });
                            isDirty = !isDirty;
                        }
                    } else if(loadedRelationships[property] !== changedRelationships[property]){
                        isDirty = true;
                    }
                }
                return isDirty;
            },
            updateRelationhipsMap: function(){
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');
                for(var property in changedRelationships){
                    if(changedRelationships.hasOwnProperty(property)){
                        loadedRelationships[property] = changedRelationships[property];
                    }
                }
                this.set('changedRelationshipsByName', {});
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

            for(j = 0; j < collections.length; j++){
                var fields = {};
                var filters = {};
                var validators = {};
                collection = collections[j];

                for(i = 0; i < collection.fields.length; i++){
                    var params = {};
                    if(collection.fields[i].displayName){
                        params.displayName = collection.fields[i].displayName;
                    }
                    if(collection.fields[i]['default']){
                        params.defaultValue = collection.fields[i]['default'];
                    }

                    switch(collection.fields[i].type){
                        case 'string':
                            fieldValue = DS.attr('string', params);
                            break;
                        case 'number':
                        case 'counter':
                            fieldValue = DS.attr('number', params);
                            break;
                        case 'bool':
                            fieldValue = DS.attr('boolean', params);
                            break;
                        case 'dateTime':
                            fieldValue = DS.attr('CustomDate', params);
                            break;
                        case 'belongsToRelation':
                            params.async = true;
                            //TODO: инверсия избыточна, но DS почему то без неё не может
                            if(collection.fields[i].targetCollection === collection.name){
                                params.inverse = 'children';
                            }
                            fieldValue = DS.belongsTo(collection.fields[i].targetCollection, params);
                            break;
                        case 'hasManyRelation':
                            params.async = true;
                            params.inverse = collection.fields[i].targetField;
                            fieldValue = DS.hasMany(collection.fields[i].targetCollection, params);
                            break;
                        case 'manyToManyRelation':
                            params.async = true;
                            params.inverse = collection.fields[i].mirrorField;
                            fieldValue = DS.hasMany(collection.fields[i].targetCollection, params);
                            break;
                        default:
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