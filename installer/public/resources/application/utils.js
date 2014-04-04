define([], function(){
    "use strict";

    return function(UMI){
        /**
         * Umi Utilities Class.
         * @class Utils
         * @static
         */
        UMI.Utils = {};

        /**
         * Local Storage
         */
        UMI.Utils.LS = {
            init: (function(){
                if(typeof(localStorage) !== "undefined"){
                    if(!localStorage.getItem("UMI")){
                        localStorage.setItem("UMI", JSON.stringify({}));
                    }
                } else{
                    //TODO: Не обрабатывается сутуация когда Local Storage не поддерживается
                    Ember.assert('Local Storage не поддерживается браузером', typeof(localStorage) !== "undefined");
                }
            }()),
            get: function(key){
                var data = JSON.parse(localStorage['UMI']);
                return Ember.get(data, key);
            },
            set: function(keyPath, value){
                var data = JSON.parse(localStorage['UMI']);
                var keys = keyPath.split('.');
                var i = 0;
                var setNestedProperty = function getNestedProperty(obj, key, value){
                    if(!obj.hasOwnProperty(key)){
                        obj[key] = {};
                    }
                    if(i < keys.length - 1){
                        i++;
                        getNestedProperty(obj[key], keys[i], value);
                    } else{
                        obj[key] = value;
                    }
                };
                setNestedProperty(data, keys[0], value);
                localStorage.setItem('UMI', JSON.stringify(data));
            }
        };


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
    };
});