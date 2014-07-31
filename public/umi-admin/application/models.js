define([], function(){
    'use strict';


    return function(UMI){

        /**
         * Фильтрация значения полей
         * @type {{stringTrim: stringTrim, htmlSafe: htmlSafe}}
         */
        var propertyFilters = {
            stringTrim: function(value){
                if(value){
                    value = value.replace(/^\s+|\s+$/g, '');
                }
                return value;
            },

            htmlSafe: function(value){
                return Ember.String.htmlSafe(value);
            },

            stripTags: function(value){//TODO: add filter
                return value;
            },

            slug: function(value){//TODO: add filter
                return value;
            }
        };

        DS.Model.reopen({
            needReloadHasMany: Ember.K,
            validErrors: null,

            filterProperty: function(propertyName){
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                var filters = Ember.get(meta, 'filters.' + propertyName);
                if(filters){
                    var value = this.get(propertyName);
                    for(var i = 0; i < filters.length; i++){
                        Ember.assert('Фильтр "' + filters[i].type + '" не определен.', propertyFilters.hasOwnProperty(filters[i].type));
                        value = propertyFilters[filters[i].type](value);
                    }
                    this.set(propertyName, value);
                }
            },

            validatorsForProperty: function(propertyName){
                Ember.assert('propertyName is required for method validatorsForProperty.', propertyName);
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                return Ember.get(meta, 'validators.' + propertyName);
            },

            validateProperty: function(propertyName){
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                var validators = Ember.get(meta, 'validators.' + propertyName);
                if(validators){
                    var value = this.get(propertyName);
                    var errors = [];
                    var activeErrors;
                    for(var i = 0; i < validators.length; i++){
                        switch(validators[i].type){
                            case "required":
                                if(!value){
                                    errors.push(validators[i].message);
                                }
                                break;
                            case "regexp":
                                var pattern = eval(validators[i].options.pattern); //TODO: Заменить eval
                                if(!pattern.test(value)){
                                    errors.push(validators[i].message);
                                }
                                break;
                        }

                        if(errors.length){
                            errors = errors.join(' ');
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
            /**
             * Валидация объекта
             * @method validateObject
             * @param {Array|undefined} fields Список полей для валидации.
             */
            validateObject: function(fields){
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                var filters = Ember.get(meta, 'filters');
                var validators = Ember.get(meta, 'validators');
                var key;
                var fieldsLength;

                if(Ember.typeOf(fields) !== 'array'){
                    fields = [];
                }
                fieldsLength = fields.length;

                if(Ember.typeOf(filters) === 'object'){
                    for(key in filters){
                        if(filters.hasOwnProperty(key)){
                            this.filterProperty(key);
                        }
                    }
                }
                if(Ember.typeOf(validators) === 'object'){
                    for(key in validators){
                        if(validators.hasOwnProperty(key)){
                            if((fieldsLength && fields.contains(key)) || !fieldsLength){
                                this.validateProperty(key);
                                if(!this.get('isValid')){
                                    break;
                                }
                            }
                        }
                    }
                }
            },

            setInvalidProperties: function(invalidProperties){
                var i;
                var self = this;
                var errors;
                var propertyName;
                var activeErrors;
                if(Ember.typeOf(invalidProperties) === 'array'){
                    for(i = 0; i < invalidProperties.length; i++){
                        errors = invalidProperties[i].errors;
                        if(Ember.typeOf(errors) === 'array'){
                            errors = errors.join(' ');
                        }
                        propertyName = invalidProperties[i].propertyName || '';
                        propertyName = propertyName.replace(/#.*/g, '');
                        activeErrors = self.get('validErrors');
                        if(activeErrors){
                            self.set('validErrors.' + propertyName, errors);
                        } else{
                            activeErrors = {};
                            activeErrors[propertyName] = errors;
                            self.set('validErrors', activeErrors);
                        }
                    }
                }
            },

            clearValidateForProperty: function(propertyName){
                var activeErrors = this.get('validErrors');
                if(Ember.get(activeErrors, propertyName)){
                    delete activeErrors[propertyName];
                }
                // Объект пересобирается без свойств прототипа
                var i = 0;
                for(var error in activeErrors){
                    if(activeErrors.hasOwnProperty(error)){
                        ++i;
                    }
                }
                activeErrors = i ? activeErrors : null;
                this.set('validErrors', activeErrors);
                if(!i){
                    if(!this.get('isValid')){
                        this.send('becameValid');
                    }
                }
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

        var extendedTypes = {
            mpath: function(params){
                return DS.attr('raw', params);
            },

            date: function(params){
                return DS.attr('CustomDate', params);
            },

            dateTime: function(params){
                return DS.attr('CustomDateTime', params);
            },

            serialized: function(params){
                return DS.attr('serialized', params);
            },

            cmsPageRelation: function(params){
                return DS.attr('objectRelation', params);
            },

            objectRelation: function(params){
                return DS.attr('objectRelation', params);
            },

            belongsToRelation: function(params, field, collection){
                params.async = true;
                //TODO: инверсия избыточна, но DS почему то без нее не может
                if(field.targetCollection === collection.name){
                    params.inverse = 'children';
                }
                return DS.belongsTo(field.targetCollection, params);
            },

            hasManyRelation: function(params, field){
                params.async = true;
                params.inverse = field.targetField;
                return DS.hasMany(field.targetCollection, params);
            },

            manyToManyRelation: function(params, field){
                params.async = true;
                return DS.hasMany(field.targetCollection, params);
            }
        };

        var getBaseTypes = function(typeName){
            var baseType = typeName;

            switch(typeName){
                case 'integer':
                    baseType = 'number';
                    break;
            }
            return baseType;
        };

        var getExtendedTypes = function(typeName){
            return Ember.get(extendedTypes, typeName);
        };

        /**
         * Создает экземпляры DS.Model
         * @method modelsFactory
         * @param array Массив обьектов
         */
        UMI.modelsFactory = function(collections){
            var collection;
            var collectionName;
            var fields;
            var field;
            var fieldValue;
            var filters;
            var validators;
            var params;
            var type;

            for(var j = 0; j < collections.length; j++){
                collection = collections[j];
                collectionName = collection.name;
                fields = {};
                filters = {};
                validators = {};

                for(var i = 0; i < collection.fields.length; i++){
                    field = collection.fields[i];
                    params = {};
                    type = null;

                    if(field.displayName){
                        params.displayName = field.displayName;
                    }

                    if(Ember.typeOf(field['default']) !== 'undefined'){
                        params.defaultValue = field['default'];
                    }

                    type = getExtendedTypes(field.type);

                    if(Ember.typeOf(type) === 'function'){
                        fieldValue = type(params, field, collection);
                    } else{
                        fieldValue = DS.attr(getBaseTypes(field.dataType), params);
                    }

                    if(field.filters){
                        filters[field.name] = field.filters;
                    }

                    if(field.validators){
                        validators[field.name] = field.validators;
                    }

                    if(field.type !== 'identify'){
                        fields[field.name] = fieldValue;
                    }
                }

                fields.meta = DS.attr('raw');

                UMI[collectionName.capitalize()] = DS.Model.extend(fields);

                UMI.__container__.lookup('store:main').metaForType(collectionName, {
                    "collectionType": collection.type,
                    "filters": filters,
                    "validators": validators
                });// TODO: Найти рекоммендации на что заменить __container__

                if(collection.source){
                    UMI[collectionName.capitalize() + 'Adapter'] = DS.UmiRESTAdapter.extend({
                        namespace: collection.source.replace(/^\//g, '')
                    });
                }
            }
        };
    };
});