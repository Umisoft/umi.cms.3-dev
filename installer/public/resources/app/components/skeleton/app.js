define(['DS', 'Modernizr', 'datepicker'], function(DS, Modernizr){
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

    Modernizr.addTest('cssfilters', function() {
        var el = document.createElement('div');
        el.style.cssText = Modernizr._prefixes.join('filter' + ':blur(2px); ');
        return !!el.style.length && ((document.documentMode === undefined || document.documentMode > 9));
    });

    Modernizr.addTest('svgfilters', function(){
        var result = false;
        try {
            result = typeof SVGFEColorMatrixElement !== undefined &&
                SVGFEColorMatrixElement.SVG_FECOLORMATRIX_TYPE_SATURATE == 2;
        }
        catch(e) {}
        return result;
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


    return UMI;
});