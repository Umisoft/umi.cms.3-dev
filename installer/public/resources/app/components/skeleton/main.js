define(
    [
        'DS',
        'Modernizr',
        'app/components/skeleton/templates',
        'app/components/skeleton/models',
        'app/components/skeleton/router',
        'app/components/skeleton/controllers',
        'app/components/skeleton/views'
    ],
    function(DS, Modernizr, templates, models, router, controller, views){
        'use strict';

        var moment = require('moment');
        moment.lang('ru');

        //Проверка браузера на мобильность
        window.mobileDetection = {
            Android: function(){
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function(){
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function(){
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function(){
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function(){
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function(){
                return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
            }
        };
        if(mobileDetection.any()){
            console.log('mobile');
        }

        //Проверка размеров экрана
        if(screen.width < 800){
            console.log('Минимальная поддерживаемая ширина экрана - 800px');
        }




        //Проверка браузера на современность - проверка поддержки calc()
        Modernizr.addTest('csscalc', function(){
            var prop = 'width:';
            var value = 'calc(10px);';
            var el = document.createElement('div');
            el.style.cssText = prop + Modernizr._prefixes.join(value + prop);
            return !!el.style.length;
        });


        Modernizr.addTest('cssfilters', function() {
            var el = document.createElement('div');
            el.style.cssText = Modernizr._prefixes.join('filter' + ':blur(2px); ');
            return !!el.style.length && ((document.documentMode === undefined || document.documentMode > 9));
        });


        /**
         * Для отключения "магии" переименования моделей Ember.Data
         * @class Inflector.inflector
         */
        Ember.Inflector.inflector = new Ember.Inflector();

        var UMI = window.UMI = window.UMI || {};

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

        /**
         * Umi Utilities Class.
         * @class Utils
         * @static
         */
        UMI.Utils = {
//            iScroll: function(){
//                var iScrolls = document.querySelectorAll('.umi-iscroll');
//                var iScrollsLength = iScrolls.length;
//                for(var i = 0; i < iScrollsLength; i++){
//                    new IScroll(iScrolls[i], this.defaultIScroll);
//                }
//            },
                iScroll: {
                    defaultSetting: {
                        scrollX: true,
                        probeType: 3,
                        mouseWheel: true,
                        scrollbars: true,
                        bounce: false,
                        click: true,
                        freeScroll: false,
                        keyBindings: true,
                        interactiveScrollbars: true
                    }
                }
//            hideOnOutClick: function(){
//                var element = document.querySelectorAll('.umi-hide-on-out-click');
//                $(document).click(function(event){
//                    if($(event.target).closest(".umi-search-component").length){
//                        return false;
//                    } else{
//                        $('.umi-search-drop-down').hide();
//                        $(".umi-search-input").removeClass('active');
//                        event.stopPropagation();
//                    }
//                });
//            }
        };

        /**
         * Кэширование селекторов
         * @property DOMCache
         * @type Object
         */
        UMI.Utils.DOMCache = {
            body: $('body'),
            document: $(document)
        };

        /**
         * @class UmiRESTAdapter
         * @extends DS.RESTAdapter
         */
        DS.UmiRESTAdapter = DS.RESTAdapter.extend({
            /**
             Метод возвращает URI запроса для CRUD операций данной модели.

             @method buildURL
             @return {String} CRUD ресурс для данной модели
             **/
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

                if (jqXHR && jqXHR.status === 500) {
                    var jsonErrors = jqXHR.responseJSON.result.error.message;
                    return new DS.InvalidError(jsonErrors);
                } else {
                    return error;
                }
            }
        });

        UMI.ApplicationSerializer = DS.RESTSerializer.extend({
            normalizePayload: function(type, payload){
                payload = payload.result;
                if(payload.hasOwnProperty('collection')){
                    payload = payload.collection;
                }
                return payload;
            },
            serializeBelongsTo: function(record, json, relationship) {
                var key = relationship.key;

                var belongsTo = Ember.get(record, key);

                key = this.keyForRelationship ? this.keyForRelationship(key, "belongsTo") : key;

                if (Ember.isNone(belongsTo)) {
                    json[key] = belongsTo;
                } else {
                    json[key] = +Ember.get(belongsTo, 'id');//Все отличие от стандартной реализации метода в приведении ID к числу.
                }

                if (relationship.options.polymorphic) {
                    this.serializePolymorphicType(record, json, relationship);
                }
            }
        });

        UMI.ApplicationAdapter = DS.UmiRESTAdapter;

        /**
         Временное решение для обновления связи hasMany указанной в links
         Вопрос на stackoverflow: http://stackoverflow.com/questions/19983483/how-to-reload-an-async-with-links-hasmany-relationship
         Решение предложенное в коробку но пока не одобренное: https://github.com/emberjs/data/pull/1539
         @class ManyArray
         @namespace DS
         @extends DS.RecordArray
         */
        DS.ManyArray.reopen({
            reloadLinks: function() {
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

        /**
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
         * DS.attr('date')
         * @type {*|void|Object}
         */
        UMI.DateTransform = DS.Transform.extend({
            serialize: function(deserialized){
                deserialized.date = moment(deserialized.date).format('YYYY-MM-DD h:mm:ss');
                return deserialized;
            },
            deserialize: function(serialized){
                serialized.date = moment(serialized.date).format('DD/MM/YYYY');
                return serialized;
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