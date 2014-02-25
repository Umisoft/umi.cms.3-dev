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

        $('.qunit-container-button').click(function(){
            $('.qunit-container').toggle();
        });

        /**
         * Application namespace.
         * @namespace UMI
         */
        var UMI = window.UMI = window.UMI || {};

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

        //Кэширование селекторов
        UMI.DOMCache = {
            body: $('body'),
            document: $(document)
        };

        UMI.RESTAdapter = DS.RESTAdapter.extend({
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
                    headers['x-real-request-method'] = type;
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

            }
        });

        UMI.Store = DS.Store.extend({
            adapter: 'UMI.RESTAdapter'
        });

        templates(UMI);
        models(UMI);
        router(UMI);
        controller(UMI);
        views(UMI);

        return UMI;
    }
);