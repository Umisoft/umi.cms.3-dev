define([], function(){
    "use strict";

    return function(UMI){
        /**
         * Umi Utilities Class.
         * @class Utils
         * @static
         */
        UMI.Utils = {};

        UMI.Utils.htmlEncode = function(str){
            str = str + "";
            return str.replace(/[&<>"']/g, function($0) {
                return "&" + {"&":"amp", "<":"lt", ">":"gt", '"':"quot", "'":"#39"}[$0] + ";";
            });
        };

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

        Ember.Handlebars.registerHelper('filterClassName', function(value, options){
            value = Ember.Handlebars.helpers.unbound.apply(this, [value, options]);
            value =value.replace(/\./g, '__');//TODO: replace all deprecated symbols
            return value;
        });

        //Удалить после возвращения Foundation
            $(document).mousedown(function(event){
                    var targetElement = $(event.target).closest('.umi-hide-on-html');
                    if(!targetElement.length){
                        $('body').off('click.umi.tree.contextMenu');
                        $('.umi-hide-on-html').hide();
                    }
                event.stopPropagation();
            });

            $(document).on('click', '.umi-top-bar-user-menu', function(){
                $('.umi-top-bar-user-menu-drop-down').toggle();
            });

            $(document).on('click', '.umi-table-action-list-show', function(){
                $(this).siblings('.umi-table-action-list').toggle();
            });

        //Проверка браузера на мобильность
        window.mobileDetection = {
            Android:    function(){return navigator.userAgent.match(/Android/i);},
            BlackBerry: function(){return navigator.userAgent.match(/BlackBerry/i);},
            iOS:        function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
            Opera:      function(){return navigator.userAgent.match(/Opera Mini/i);},
            Windows:    function(){return navigator.userAgent.match(/IEMobile/i);},
            any:        function(){return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());}
        };

        if(mobileDetection.any()){
            console.log('mobile');
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