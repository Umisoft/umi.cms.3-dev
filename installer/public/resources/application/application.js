define(
    [
        'DS',
        'Modernizr',
        'iscroll',
        'ckEditor',
        'jQueryUI',
        'elFinder',
        'timepicker',
//        'datepicker',
        'moment',
        'application/config',
        'application/utils',
        'application/templates',
        'application/models',
        'application/router',
        'application/controllers',
        'application/views'
    ],
    function(DS, Modernizr, iscroll, ckEditor, jQueryUI, elFinder, timepicker, /* datepicker,*/ moment, config, utils, templates, models, router, controller, views){
        'use strict';

        var UMI = window.UMI = window.UMI || {};

        /**
         * Для отключения "магии" переименования моделей Ember.Data
         * @class Inflector.inflector
         */
        Ember.Inflector.inflector = new Ember.Inflector();

        /**
         * Umi application.
         * @module UMI
         * @extends Ember.Application
         * @requires Ember
         */
        UMI = Ember.Application.create({
            rootElement: '#body',
            Resolver: Ember.DefaultResolver.extend({
                resolveTemplate: function(parsedName){
                    parsedName.fullNameWithoutType = "UMI/" + parsedName.fullNameWithoutType;
                    return this._super(parsedName);
                }
            })
        });

        UMI.deferReadiness();

        utils(UMI);
        config(UMI);

        /**
         * @class UmiRESTAdapter
         * @extends DS.RESTAdapter
         */
        DS.UmiRESTAdapter = DS.RESTAdapter.extend({
            /**
             Метод возвращает URI запроса для CRUD операций данной модели.

             @method buildURL
             @return {String} CRUD ресурс для данной модели
             */
            buildURL: function(type, id) {
                var url = [],
                    host = Ember.get(this, 'host'),
                    prefix = this.urlPrefix();

                if (id) { url.push(id); }

                if (prefix) { url.unshift(prefix); }

                url = url.join('/');
                if (!host && url) { url = '/' + url; }

                return url;
            },

            namespace: window.UmiSettings.baseApiURL.replace( /^\//g, ''),

            ajaxOptions: function(url, type, hash){
                hash = hash || {};
                hash.url = url;
                hash.type = type;
                hash.dataType = 'json';
                hash.context = this;

                if(hash.data && type !== 'GET'){
                    hash.contentType = 'application/json; charset=utf-8';
                    hash.data = JSON.stringify(hash.data);
                }

                var headers = this.headers;

                if(type === 'PUT' || type === 'DELETE'){
                    headers = headers || {};
                    headers['X-HTTP-METHOD-OVERRIDE'] = type;
                    hash.type = 'POST';
                }

                if(headers !== undefined){
                    hash.beforeSend = function(xhr){
                        Ember.ArrayPolyfills.forEach.call(Ember.keys(headers), function(key){
                            xhr.setRequestHeader(key, headers[key]);
                        });
                    };
                }

                return hash;
            },

            ajaxError: function(jqXHR){
                var error = this._super(jqXHR);
                if(error.status === 403 || error.status === 401){
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    UMI.__container__.lookup('router:main').send('logout');
                    return;
                }
                var message;
                if(error.hasOwnProperty('responseJSON')){
                    if(error.responseJSON.hasOwnProperty('result') && error.responseJSON.result.hasOwnProperty('error')){
                        message = error.responseJSON.result.error.message;
                    }
                } else{
                    message = error.responseText;
                }
                var data = {
                    'close': true,
                    'title': error.status + '. ' + error.statusText,
                    'content': message
                };
                UMI.dialog.open(data).then();
            }
        });


        UMI.ApplicationSerializer = DS.RESTSerializer.extend({
            /**
             Переносим в store metadata для коллекции
             Чтобы получить: store.metadataFor(type)
             */
            extractMeta: function(store, type, payload){
                if(payload && payload.result && payload.result.meta){
                    var meta = store.metadataFor(type) || {};
                    for(var property in payload.result.meta){
                        meta[property] = payload.result.meta[property];
                    }
                    store.metaForType(type, meta);
                    delete payload.result.meta;
                }
            },

            /**
             Удаление объекта-обёртки result из всех приходящих объектов
             Удаление объекта-обёртки collection из всех объектов его содержащих
             */
            normalizePayload: function(type, payload){
                payload = payload.result;
                if(payload.hasOwnProperty('collection')){
                    payload = payload.collection;
                }
                return payload;
            },

            /**
             * При сохранении добавляет в объект тип связи и id связанных объектов (только если они изменялись)
             * @param record
             * @param json
             * @param relationship
             */
            serializeHasMany: function(record, json, relationship){
                var key = relationship.key;

                var relationshipType = DS.RelationshipChange.determineRelationshipType(record.constructor, relationship);

                if (relationshipType === 'manyToNone' || relationshipType === 'manyToMany' || relationshipType === 'manyToOne'){
                    if(record.relationPropertyIsDirty(key)){
                        json[key] = Ember.get(record, 'changedRelationshipsByName.' + key);
                    }
                }
            }
        });

        UMI.ApplicationAdapter = DS.UmiRESTAdapter;

        /**
         Обновление связанных объектов (hasMany) при обновлении текущего
         Проверить наличие реализации в стабильной версии Ember.Data

         Временное решение для обновления связи hasMany указанной в links
         Вопрос на stackoverflow: http://stackoverflow.com/questions/19983483/how-to-reload-an-async-with-links-hasmany-relationship
         Решение предложенное в коробку но пока не одобренное: https://github.com/emberjs/data/pull/1539
         @class ManyArray
         @namespace DS
         @extends DS.RecordArray
         */
        DS.ManyArray.reopen({
            reloadLinks: function(){
                var get = Ember.get;
                var store = get(this, 'store');
                var owner = get(this, 'owner');
                var name = get(this, 'name');
                var resolver = Ember.RSVP.defer();
                var meta = owner.constructor.metaForProperty(name);
                var link = owner._data.links[meta.key];
                store.findHasMany(owner, link, meta, resolver);
            }
        });

        UMI.Store = DS.Store.extend({
            /**
             * Обновление объектов коллекции без очищения загруженных полей
             * @method updateCollection
             * @param type
             * @param id
             * @returns {*}
             */
            updateCollection: function(type, id){
                var promise;
                var self = this;

                function promiseArray(promise, label){
                    var PromiseArray = Ember.ArrayProxy.extend(Ember.PromiseProxyMixin);
                    return PromiseArray.create({
                        promise: Ember.RSVP.Promise.cast(promise, label)
                    });
                }

                function serializerFor(container, type, defaultSerializer) {
                    return container.lookup('serializer:'+type) ||
                        container.lookup('serializer:application') ||
                        container.lookup('serializer:' + defaultSerializer) ||
                        container.lookup('serializer:-default');
                }

                function serializerForAdapter(adapter, type){
                    var serializer = adapter.serializer,
                        defaultSerializer = adapter.defaultSerializer,
                        container = adapter.container;

                    if (container && serializer === undefined) {
                        serializer = serializerFor(container, type.typeKey, defaultSerializer);
                    }

                    if (serializer === null || serializer === undefined) {
                        serializer = {
                            extract: function(store, type, payload) { return payload; }
                        };
                    }

                    return serializer;
                }

                function findQuery(type, query){
                    type = self.modelFor(type);

                    var array = self.recordArrayManager.createAdapterPopulatedRecordArray(type, query);

                    var adapter = self.adapterFor(type);

                    Ember.assert("You tried to load a query but you have no adapter (for " + type + ")", adapter);
                    Ember.assert("You tried to load a query but your adapter does not implement `findQuery`", adapter.findQuery);

                    return promiseArray(_findQuery(adapter, self, type, query, array));
                }

                function _findQuery(adapter, store, type, query, recordArray){
                    var promise = adapter.findQuery(store, type, query, recordArray),
                        serializer = serializerForAdapter(adapter, type),
                        label = "DS: Handle Adapter#findQuery of " + type;

                    return Ember.RSVP.Promise.cast(promise, label).then(function(adapterPayload) {
                        var queryParams = Ember.get(query, 'fields') || '';
                        var payload = serializer.extract(store, type, adapterPayload, null, 'findQuery');

                        Ember.assert("The response from a findQuery must be an Array, not " + Ember.inspect(payload), Ember.typeOf(payload) === 'array');

                        queryParams = queryParams.split(',');
                        queryParams.push('id');
                        queryParams.push('version');
                        for(var i = 0; i < payload.length; i++){
                            for(var key in payload[i]){
                                if(payload[i].hasOwnProperty(key) && !queryParams.contains(key)){
                                    delete payload[i][key];
                                }
                            }
                        }
                        //recordArray.load(payload);
                        return payload;
                    }, null, "DS: Extract payload of findQuery " + type);
                }

                function coerceId(id){
                    return id == null ? null : id+'';
                }

                Ember.assert("You need to pass a type to the store's find method", arguments.length >= 1);
                Ember.assert("You may not pass `" + id + "` as id to the store's find method", arguments.length === 1 || !Ember.isNone(id));

                if (arguments.length === 1){
                    promise = self.findAll(type);
                } else if (Ember.typeOf(id) === 'object'){
                    promise = findQuery(type, id);
                } else{
                    promise = self.findById(type, coerceId(id));
                }

                return promiseArray(promise.then(function(result){
                    var i;
                    var objects = [];
                    for(i = 0; i < result.length; i++){
                        objects.push(self.update(type, result[i]));
                    }

                    return objects;
                }));
            }
        });

        /**
         * Для строковых полей меняет null на ''
         * http://youtrack.umicloud.ru/issue/cms-414
         * DS.StringTransform
         * @type {*|void|Object}
         */
        DS.StringTransform.reopen({
            deserialize: function(serialized) {
                return Ember.isNone(serialized) ? "" : String(serialized);
            }
        });

        /**
         * Приводит приходящий объект date:{} к нужному формату даты
         * TODO Смена формата в зависимости от языка системы
         * TODO Почему не прилылать в простом timeStamp
         * DS.attr('date')
         * @type {*|void|Object}
         */
        UMI.CustomDateTransform = DS.Transform.extend({
            deserialize: function(deserialized){
                if(deserialized && deserialized.date){
                    Ember.set(deserialized, 'date', moment(deserialized.date).format('DD/MM/YYYY'));
                    deserialized = JSON.stringify(deserialized);
                }
                return deserialized;
            },
            serialize: function(serialized){
                if(serialized){
                    serialized = JSON.parse(serialized);
                    if(serialized.date){
                        Ember.set(serialized, 'date', moment(serialized.date, 'DD/MM/YYYY').format('YYYY-MM-DD h:mm:ss'));
                    }
                }
                return serialized;
            }
        });

        /**
         * Позволяет незарегестрированным типам полей объектов отрабатывать в системе без ошибок (просто возвращает это поле)
         * TODO Проверить все приходящие типы и подумать над необходимостью этих методов
         * DS.attr('raw')
         * @type {*|void|Object}
         */
        UMI.RawTransform = DS.Transform.extend({
            serialize: function(deserialized){
                return deserialized;
            },
            deserialize: function(serialized){
                return serialized;
            }
        });

        /**
         * Вывод всех ajax ошибок в tooltip
         */
        $.ajaxSetup({
            headers: {"X-Csrf-Token": window.UmiSettings.token},
            error: function(error){
                var activeTransition = UMI.__container__.lookup('router:main').router.activeTransition;
                if(activeTransition){
                    activeTransition.send('backgroundError', error);
                } else{
                    UMI.__container__.lookup('route:application').send('backgroundError', error);
                }
            }
        });

        templates(UMI);
        models(UMI);
        router(UMI);
        controller(UMI);
        views(UMI);

        return UMI;
    }
);