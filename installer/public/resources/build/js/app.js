define('application/config',[], function(){
    

    return function(UMI){
        UMI.config = {
            iScroll: {
                scrollX: true,
                probeType: 3,
                mouseWheel: true,
                scrollbars: true,
                bounce: false,
                click: true,
                freeScroll: false,
                keyBindings: true,
                interactiveScrollbars: true
            },

            elFinder: {
                url : '/admin/api/files/manager/action/connector',
                lang: 'ru',

                //                getFileCallback : function(file) {
                //                    window.opener.CKEDITOR.tools.callFunction(funcNum, file);
                //                    window.close();
                //                },

                closeOnGetFileCallback : true,
//                editorCallback : function(url) {
//                    console.log('elFinder', url);
//                    document.querySelector('.umi-input-wrapper-file .umi-file').value = url;
//                },
                getFileCallback : function(fileInfo){
                    console.log('getFileCallback', fileInfo);
//                    window.opener.CKEDITOR.tools.callFunction(funcNum, url);
                    document.querySelector('.umi-input-wrapper .umi-file').value = fileInfo.path;
                    document.querySelector('.umi-input-wrapper img').src = fileInfo.tmb;
//                    window.close();
                },

                uiOptions: {
                    toolbar : [
                        ['back', 'forward'], ['reload'], ['getfile'],
                        // ['home', 'up'],
                        ['mkdir', 'mkfile', 'upload'], ['download'],
//                      ['info'], ['quicklook'],
                        ['copy', 'cut', 'paste'], ['rm'], ['duplicate', 'rename', 'edit'],
//                      ['extract', 'archive'], ['search'],
                        ['view'], ['help']
                    ]
                }
            }
        };

        CKEDITOR.editorConfig = function( config ) {
            // Define changes to default configuration here.
            // For the complete reference:
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            config.filebrowserBrowseUrl = '/admin/api/files/manager/action/connector';
            // The toolbar groups arrangement, optimized for two toolbar rows.
            config.toolbarGroups = [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }
            ];

            // Remove some buttons, provided by the standard plugins, which we don't
            // need to have in the Standard(s) toolbar.
            config.removeButtons = 'Underline,Subscript,Superscript';

            // Se the most common block elements.
            config.format_tags = 'p;h1;h2;h3;pre';

            // Make dialogs simpler.
            config.removeDialogTabs = 'image:advanced;link:advanced';
        };
    };
});
define('application/utils',['Modernizr'], function(Modernizr){
    

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

        UMI.Utils.replacePlaceholder = function(object, pattern){
            var deserialize;
            deserialize = pattern.replace(/{\w+}/g, function(key) {
                if(key){
                    key = key.slice(1, -1);
                }
                return Ember.get(object, key) || key;//TODO: error handling
            });
            return deserialize;
        };

        UMI.Utils.objectsMerge = function(objectBase, objectProperty){
            Ember.assert('Некорректный тип аргументов. Метод objectsMerge ожидает аргументы с типом "object"', Ember.typeOf(objectBase) === 'object' && Ember.typeOf(objectProperty) === 'object');
            for(var key in objectProperty){
                if(objectProperty.hasOwnProperty(key)){
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        /**
         * Local Storage
         */
        UMI.Utils.LS = {
            store: localStorage,
            init: function(){
                if(Modernizr.localstorage){
                    if(!localStorage.getItem("UMI")){
                        localStorage.setItem("UMI", JSON.stringify({}));
                    }
                } else{
                    //TODO: Не обрабатывается сутуация когда Local Storage не поддерживается
                    this.store = {'UMI': JSON.stringify({})};
                }
            },

            get: function(key){
                var data = JSON.parse(this.store.UMI);
                return Ember.get(data, key);
            },

            set: function(keyPath, value){
                var data = JSON.parse(this.store.UMI);
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
                if(Modernizr.localstorage){
                    this.store.setItem('UMI', JSON.stringify(data));
                } else{
                    this.store.UMI = JSON.stringify(data);
                }
            }
        };

        Ember.Handlebars.registerHelper('filterClassName', function(value, options){
            value = Ember.Handlebars.helpers.unbound.apply(this, [value, options]);
            value =value.replace(/\./g, '__');//TODO: replace all deprecated symbols
            return value;
        });

        UMI.Utils.LS.init();

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
define('application/i18n',[], function(){
    
    return function(UMI){
        Ember.Handlebars.registerHelper('i18n',
            function(label, namespace){
                if(Ember.typeOf(namespace) !== 'string'){
                    namespace = undefined;
                }
                var translateLabel = UMI.i18n.getTranslate(label, namespace);
                return translateLabel ? translateLabel : label;
            }
        );

        UMI.i18n = Ember.Object.extend({
            dictionary: {},
            setDictionary: function(translate, namespace){
                var dictionary = this.get('dictionary');
                var namespaceDictionary;
                if(namespace && namespace){
                    namespaceDictionary = Ember.typeOf(dictionary[namespace]) === 'object' ? dictionary[namespace] : {};
                    Ember.set(dictionary, namespace, namespaceDictionary);
                }
                for(var key in translate){
                    if(translate.hasOwnProperty(key)){
                        if(namespace){
                            Ember.set(Ember.get(dictionary, namespace), key, translate[key]);
                        } else{
                            Ember.set(dictionary, key, translate[key]);
                        }

                    }
                }
            },
            getTranslate: function(label, componentPath){
                var path = 'dictionary.' + (componentPath ? componentPath + '.' : '') + label ;
                var translate = this.get(path);
                return translate ? translate : label;
            }
        }).create({});

        UMI.i18nInterface = Ember.Mixin.create({
            /**
             * namespace, например имя контола реализующего итерфейс i18n
             * @property context
             * @abstract
             */
            dictionaryNamespace: null,
            /**
             * Словарь для контрола
             * @property localDictionary
             * @abstract
             */
            localDictionary: null,
            setDictionary: function(){
                UMI.i18n.setDictionary(this.get('localDictionary'), this.get('dictionaryNamespace'));
            },
            init: function(){
                this._super();
                this.setDictionary();
            }
        });
    };
});
define('application/templates.compile',["Ember"], function(Ember){

Ember.TEMPLATES["UMI/application"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing;


  data.buffer.push("<div class=\"s-full-height-before umi-header\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "topBar", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "dock", options) : helperMissing.call(depth0, "render", "dock", options))));
  data.buffer.push(" </div> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.outlet || (depth0 && depth0.outlet),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "popup", options) : helperMissing.call(depth0, "outlet", "popup", options))));
  return buffer;
  
});

Ember.TEMPLATES["UMI/component"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "sideBarControl", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-divider-right sideBarControl::wide")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <div class=\"umi-component columns small-12 s-padding-clear s-full-height\"> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <div class=\"umi-divider-left\"> <div class=\"umi-divider-left-content\"> ");
  data.buffer.push(escapeExpression((helper = helpers.outlet || (depth0 && depth0.outlet),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "sideBar", options) : helperMissing.call(depth0, "outlet", "sideBar", options))));
  data.buffer.push(" </div> <div class=\"umi-divider\"></div> </div> <div class=\"umi-left-bottom-panel s-unselectable\"> <a href=\"javascript:void(0)\" class=\"button white square umi-divider-left-toggle\"> <i class=\"icon icon-left\"></i> </a> </div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"s-full-height\"> ");
  stack1 = helpers.view.call(depth0, "divider", {hash:{
    'modelBinding': ("model")
  },hashTypes:{'modelBinding': "STRING"},hashContexts:{'modelBinding': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/counter"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<style> .umi-counter{ height: 100%; } .umi-counter-header, .umi-counter-period, .umi-counter-content{ float: left; padding: 20px 30px 0; width: calc(100% - 200px); box-sizing: border-box; } .umi-counter-date{ float: left; margin-right: 30px; } .umi-counter-info{ float: left; width: calc(100% - 200px); height: 100%; } </style> <div class=\"umi-counter\" style=\"background: #F5F6F7;\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "accordion", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" <div class=\"umi-counter-info\"> <div class=\"umi-counter-period\"> <div class=\"umi-counter-date large-4 small-12\"> <div> <span class=\"umi-form-label\">Начало отчётного периода</span> </div> <div class=\"umi-input-wrapper-date\"> <input type=\"text\" class=\"umi-date umi-date-from\" /> <i class=\"icon icon-calendar\"></i> </div> </div> <div class=\"umi-counter-date large-4 small-12\"> <div> <span class=\"umi-form-label\">Конец отчётного периода</span> </div> <div class=\"umi-input-wrapper-date\"> <input type=\"text\" class=\"umi-date umi-date-to\" /> <i class=\"icon icon-calendar\"></i> </div> </div> </div> <div class=\"umi-counter-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "chartControl", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"umi-counter-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "table", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/counters"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "table", {hash:{
    'contentBinding': ("this")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/editForm"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "formControl", "model", options) : helperMissing.call(depth0, "render", "formControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/empty"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1;


  data.buffer.push("<div class=\"s-full-height panel\"> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "model.control.params.content", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h3> </div> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/files"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fileManager", {hash:{
    'content': ("model")
  },hashTypes:{'content': "ID"},hashContexts:{'content': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/filter"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "tableControl", "model", options) : helperMissing.call(depth0, "render", "tableControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/megaIndex"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "seoMegaIndex", {hash:{
    'contentBinding': ("this")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/simpleForm"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "formBase", "model", options) : helperMissing.call(depth0, "render", "formBase", "model", options))));
  
});

Ember.TEMPLATES["UMI/yandexWebmaster"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "seoYandexWebmaster", {hash:{
    'contentBinding': ("this")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/errors"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "status", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(". ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <p>");
  stack1 = helpers._triageMustache.call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</p> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"text-left\"> <code>");
  stack1 = helpers._triageMustache.call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</code> </div> ");
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"alert-box error\"> <ul> ");
  stack1 = helpers.each.call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li>");
  stack1 = helpers._triageMustache.call(depth0, "error", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</li> ");
  return buffer;
  }

  data.buffer.push("<div class=\"umi-component s-full-height\"> <div class=\"row\"> <div class=\"small-10 columns small-centered text-center\"> <p></p> <div> <h2> ");
  stack1 = helpers['if'].call(depth0, "status", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </h2> ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/menu"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "sideMenu", "model", options) : helperMissing.call(depth0, "render", "sideMenu", "model", options))));
  
});

Ember.TEMPLATES["UMI/tree"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "treeControl", "model", options) : helperMissing.call(depth0, "render", "treeControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/partials/chartControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  


  data.buffer.push("<div class=\"umi-canvas-wrapper\"> <canvas id=\"umi-metrika-canvas\"></canvas> </div>");
  
});

Ember.TEMPLATES["UMI/partials/dialog-layout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-overlay\"></div> <div class=\"umi-dialog\"> ");
  stack1 = helpers['if'].call(depth0, "model.close", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <a href=\"\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "model", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" class=\"close\">&times;</a> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "model", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/dialog-template"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <h5 class=\"subheader umi-dialog-header\">");
  stack1 = helpers._triageMustache.call(depth0, "model.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "model.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  stack1 = helpers._triageMustache.call(depth0, "checkbox-element", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Remember my choice", options) : helperMissing.call(depth0, "i18n", "Remember my choice", options))));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button small secondary left\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "confirm", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</button> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button small secondary right\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.reject", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</button> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "model.title", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.proposeRemember", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.reject", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dock"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "dockModuleButton", {hash:{
    'module': ("module")
  },hashTypes:{'module': "ID"},hashContexts:{'module': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("span"),
    'class': ("dock-module {{unbound module.name}}")
  },hashTypes:{'tagName': "STRING",'class': "STRING"},hashContexts:{'tagName': depth0,'class': depth0},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "module", "module.name", options) : helperMissing.call(depth0, "link-to", "module", "module.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"f-dropdown center\"> ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <img ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'src': ("view.icon")
  },hashTypes:{'src': "ID"},hashContexts:{'src': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" /> <span>");
  stack1 = helpers._triageMustache.call(depth0, "module.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <li class=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "component.name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","ID"],data:data},helper ? helper.call(depth0, "component", "module.name", "component.name", options) : helperMissing.call(depth0, "link-to", "component", "module.name", "component.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "component.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['with'].call(depth0, "activeModule", "as", "module", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("component.name")
  },hashTypes:{'class': "ID"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","ID"],data:data},helper ? helper.call(depth0, "component", "module.name", "component.name", options) : helperMissing.call(depth0, "link-to", "component", "module.name", "component.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"dock-wrapper\"> <div class=\"dock-wrapper-bg\"> <ul class=\"dock navigation\"> ");
  stack1 = helpers.each.call(depth0, "module", "in", "modules", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div> <div class=\"dock-components\"> <nav class=\"components-nav\"> ");
  stack1 = helpers['if'].call(depth0, "activeModule", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </nav> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/checkboxGroup/CollectionElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.checkboxElementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/checkboxGroup"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {hash:{
    'meta': ("element")
  },hashTypes:{'meta': "ID"},hashContexts:{'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "element", "in", "meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/dateElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dateTimeElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression((helper = helpers.input || (depth0 && depth0.input),options={hash:{
    'type': ("text"),
    'class': ("umi-date"),
    'value': ("view.value")
  },hashTypes:{'type': "STRING",'class': "STRING",'value': "ID"},hashContexts:{'type': depth0,'class': depth0,'value': depth0},contexts:[],types:[],data:data},helper ? helper.call(depth0, options) : helperMissing.call(depth0, "input", options))));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/fileElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-2 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></i> <i class=\"icon icon-open-folder\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "fileManager", "view.object", "view.meta", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0,depth0,depth0],types:["STRING","STRING","ID","ID"],data:data})));
  data.buffer.push("></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/imageElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-2 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></i> <i class=\"icon icon-image\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "fileManager", "view.object", "view.meta", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0,depth0,depth0],types:["STRING","STRING","ID","ID"],data:data})));
  data.buffer.push("></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/multi-select-lazy-choices"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "id", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': ("hover")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Nothing found", options) : helperMissing.call(depth0, "i18n", "Nothing found", options))));
  data.buffer.push(" </li> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "id", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/multi-select"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "value", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': ("hover")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Nothing found", options) : helperMissing.call(depth0, "i18n", "Nothing found", options))));
  data.buffer.push(" </li> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "value", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/permissions"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <dl class=\"accordion\"> <dd class=\"accordion-navigation\"> <a class=\"accordion-navigation-button\" href=\"javascript:void(0)\"><i class=\"icon icon-right\"></i> ");
  stack1 = helpers._triageMustache.call(depth0, "component.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</a> <div class=\"content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {hash:{
    'component': ("component")
  },hashTypes:{'component': "ID"},hashContexts:{'component': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> </dd> </dl> ");
  return buffer;
  }

  data.buffer.push("<div class=\"umi-permissions\"> ");
  stack1 = helpers.each.call(depth0, "component", "in", "view.meta.resources", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/permissions/partial"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li class=\"umi-permissions-role-list-item\"> <div class=\"umi-permissions-role\"> ");
  stack1 = helpers['if'].call(depth0, "role.component", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"umi-permissions-role-label\" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'data-permissions-component-path': ("view.component.path")
  },hashTypes:{'data-permissions-component-path': "STRING"},hashContexts:{'data-permissions-component-path': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {hash:{
    'meta': ("role"),
    'name': ("role.value"),
    'attributeValue': ("role.value"),
    'value': (""),
    'className': ("umi-permissions-role-checkbox")
  },hashTypes:{'meta': "ID",'name': "ID",'attributeValue': "ID",'value': "STRING",'className': "STRING"},hashContexts:{'meta': depth0,'name': depth0,'attributeValue': depth0,'value': depth0,'className': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </span> </div> ");
  stack1 = helpers['if'].call(depth0, "role.component", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }
function program2(depth0,data) {
  
  
  data.buffer.push(" <span class=\"button tiny square white left s-margin-clear umi-permissions-role-button-expand\"> <i class=\"icon icon-right\"></i> </span> ");
  }

function program4(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"umi-permissions-component\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {hash:{
    'component': ("role.component")
  },hashTypes:{'component': "ID"},hashContexts:{'component': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "role", "in", "view.component.roles", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/radioElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.radioElementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "view.meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/textareaElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.textareaView", {hash:{
    'meta': ("meta"),
    'object': ("object")
  },hashTypes:{'meta': "ID",'object': "ID"},hashContexts:{'meta': depth0,'object': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <div class=\"umi-element-textarea-resizer\"></div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/timeElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\"></i> </span> </div> <style> .umi-time-picker{ position: absolute; float: left; width: 200px; height: 200px; background: #FFFFFF; } </style>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/form"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("control.toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(15, program15, data),fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <fieldset id=\"fieldset-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "formElement.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> <legend ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expand", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"s-unselectable\"> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </legend> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(13, program13, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program13(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("element")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <br /> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("formElement")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.submitToolbarView", {hash:{
    'elements': ("model.control.submitToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "control.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['with'].call(depth0, "model.control.meta.elements", "as", "formElements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/formControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("control.toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(15, program15, data),fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <fieldset id=\"fieldset-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "formElement.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> <legend ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expand", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"s-unselectable\"> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </legend> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(13, program13, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program13(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "submitToolbar", {hash:{
    'elements': ("model.control.submitToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "control.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['with'].call(depth0, "model.control.meta.elements", "as", "formElements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/form/submitToolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "view.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/alert-box"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <h5 class=\"subheader\">");
  stack1 = helpers._triageMustache.call(depth0, "view.content.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "view.content", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" class=\"close\">&times;</span> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.content.title", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.content.close", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/popup"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"umi-popup-header\"> <span class=\"umi-popup-title\">");
  stack1 = helpers._triageMustache.call(depth0, "view.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> <a href=\"#\" class=\"umi-popup-close-button\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "closePopup", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </a> </div> <div class=\"umi-popup-content\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-popup-resizer\"></div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/sideMenu"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("li")
  },hashTypes:{'tagName': "STRING"},hashContexts:{'tagName': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "context", "object.id", options) : helperMissing.call(depth0, "link-to", "context", "object.id", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push("<a href=\"javascript:void(0)\">");
  stack1 = helpers._triageMustache.call(depth0, "object.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</a>");
  return buffer;
  }

  data.buffer.push("<ul class=\"side-nav\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/table"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-ajax-header-column\"> <div class=\"umi-table-ajax-title-div\">");
  stack1 = helpers._triageMustache.call(depth0, "", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> </td> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.row", {hash:{
    'object': ("element")
  },hashTypes:{'object': "ID"},hashContexts:{'object': depth0},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.cell", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-ajax-empty-column\"></td> ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-ajax-cell-td\"> <div class=\"umi-table-ajax-cell-div\">");
  stack1 = helpers._triageMustache.call(depth0, "", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> </td> ");
  return buffer;
  }

function program7(depth0,data) {
  
  
  data.buffer.push(" <tr class=\"umi-metrika-empty-row\"> <!-- TODO colspan заменить на переменную с количеством колонок --> <td class=\"umi-table-ajax-empty-result\" colspan=\"4\"> Нет записей </td> </tr> ");
  }

  data.buffer.push("<style> .umi-table-ajax{ background: #D6E0E9;; } .umi-table-ajax tbody td{ border-left: 1px solid #E3E4E5; } .umi-table-ajax-tr:hover{ background: #BFE0F8; cursor: pointer; } </style> <div class=\"umi-table-ajax-control-content\"> <div class=\"umi-table-ajax-control-content-center\"> <table cellpadding=\"0\" class=\"umi-table-ajax-content\"> <thead> <tr class=\"umi-table-ajax-titles\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-ajax-empty-header-column\"></td> </tr> </thead> <tbody> ");
  stack1 = helpers.each.call(depth0, "element", "in", "view.data", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(7, program7, data),fn:self.program(3, program3, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/tableControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.paginationView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Rows on page", "tableControl", options) : helperMissing.call(depth0, "i18n", "Rows on page", "tableControl", options))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }
function program3(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  }

function program5(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  }

function program7(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <td class=\"umi-table-control-header-column column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\" style=\"width: 200px\"> <div class=\"umi-table-control-cell-firefox-fix\"> <div class=\"umi-table-control-header-cell\"> ");
  stack1 = helpers._triageMustache.call(depth0, "column.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-table-control-column-resizer\"></div> </div> </td> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <td class=\"umi-table-control-header-column\"> <div class=\"umi-table-control-header-cell filter column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.filterRowView", {hash:{
    'column': ("column")
  },hashTypes:{'column': "ID"},hashContexts:{'column': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.sortHandlerView", {hash:{
    'propertyName': ("column.attributes.name")
  },hashTypes:{'propertyName': "ID"},hashContexts:{'propertyName': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </td> ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :black view.sortAscending:icon-bottom-thin:icon-top-thin")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <td class=\"umi-table-control-content-cell column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\" style=\"width: 200px\"></td> ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(19, program19, data),fn:self.program(15, program15, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.rowView", {hash:{
    'object': ("object")
  },hashTypes:{'object': "ID"},hashContexts:{'object': depth0},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program16(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> ");
  return buffer;
  }
function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-content-cell\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCellContent", {hash:{
    'objectBinding': ("object"),
    'column': ("column")
  },hashTypes:{'objectBinding': "STRING",'column': "ID"},hashContexts:{'objectBinding': depth0,'column': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </td> ");
  return buffer;
  }

function program19(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <tr> <td> <div class=\"umi-table-control-content-div-empty\"> <span>");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "No data", "tableControl", options) : helperMissing.call(depth0, "i18n", "No data", "tableControl", options))));
  data.buffer.push("</span> </div> </td> </tr> ");
  return buffer;
  }

function program21(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-table-control-column-fixed-cell object.active::umi-inactive")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-objectId=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "object.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers['if'].call(depth0, "controller.parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(22, program22, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program22(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "tableControlContextToolbar", {hash:{
    'elements': ("controller.parentController.contextToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(23, program23, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program23(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(24, program24, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program24(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-table-control-header s-unselectable\"> <div class=\"umi-table-control-header-center\"> <table class=\"umi-table-control-header\"> <tbody> <tr class=\"umi-table-control-row\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> <tr class=\"umi-table-control-row filters\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-control-header-fixed-right\"> <div class=\"umi-table-control-header-title\"> <div class=\"umi-table-control-header-fixed-right-first\"> </div> <div class=\"umi-table-control-header-fixed-right-second\"> </div> </div> <div class=\"umi-table-control-header-filter\"> </div> </div> </div> <div class=\"umi-table-control-content-wrapper\"> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-control-content\"> <tbody> <tr class=\"umi-table-control-content-row-size\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> ");
  stack1 = helpers['if'].call(depth0, "objects.content.isFulfilled", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> <!-- Колонка справа от контента --> <div class=\"umi-table-control-content-fixed-right\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{
    'itemController': ("tableControlContextToolbarItem")
  },hashTypes:{'itemController': "STRING"},hashContexts:{'itemController': depth0},inverse:self.noop,fn:self.program(21, program21, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/button"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i class=\"icon icon-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.behaviour.name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></i> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton/backupList"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span> ");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </span> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"row collapse\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "applyBackup", "", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <div class=\"columns small-6 place-button\"> ");
  stack1 = helpers['if'].call(depth0, "isActive", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(6, program6, data),fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "created.date", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"columns small-6\"> ");
  stack1 = helpers['if'].call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(10, program10, data),fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-accept\"></i> </button> ");
  }

function program6(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-renew\"></i> </button> ");
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "User name", "toolbar:dropdownButton", options) : helperMissing.call(depth0, "i18n", "User name", "toolbar:dropdownButton", options))));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<a href=\"javascript:void(0)\" class=\"button white dropdown\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-backupList\"></i> ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </a> <div class=\"f-dropdown umi-dropdown dropdown-rows right\"> <div class=\"row collapse\"> <div class=\"columns small-12\"> <strong>");
  stack1 = helpers._triageMustache.call(depth0, "view.button.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</strong> </div> </div> <div class=\"s-scroll-wrap\"> <div> ");
  stack1 = helpers.each.call(depth0, "view.backupList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i class=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.icon.class", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></i> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.icon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"f-dropdown\"> ");
  stack1 = helpers.each.call(depth0, "view.meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/splitButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.defaultBehaviourIcon")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :icon-accept view.isDefaultBehaviour::white")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleDefaultBehaviour", "view._parentView.contentIndex", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp")
  },hashTypes:{'target': "STRING",'on': "STRING"},hashContexts:{'target': depth0,'on': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("></i> <a ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp")
  },hashTypes:{'target': "STRING",'on': "STRING"},hashContexts:{'target': depth0,'on': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </a> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"dropdown-toggler\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></span> <ul class=\"f-dropdown composite\"> ");
  stack1 = helpers.each.call(depth0, "view.meta.behaviour.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/toolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<ul class=\"button-group left\"> ");
  stack1 = helpers.each.call(depth0, "view.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> <div class=\"right\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/topBar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', helper, options, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing;


  data.buffer.push("<nav class=\"umi-top-bar\"> <ul class=\"umi-top-bar-list left\"> <li> <a href=\"javascript:void(0)\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "viewOnSite", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"button tiny flat umi-top-bar-button\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Open site in new tab", options) : helperMissing.call(depth0, "i18n", "Open site in new tab", options))));
  data.buffer.push(" <i class=\"icon icon-viewOnSite\"></i> </a> </li> </ul> <ul class=\"umi-top-bar-list right\"> <li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "dropdownButton", {hash:{
    'tagName': ("span"),
    'class': ("button tiny flat dropdown umi-top-bar-button")
  },hashTypes:{'tagName': "STRING",'class': "STRING"},hashContexts:{'tagName': depth0,'class': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "notificationList", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </ul> </nav>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeItem", {hash:{
    'treeControlView': ("view"),
    'item': ("item")
  },hashTypes:{'treeControlView': "ID",'item': "ID"},hashContexts:{'treeControlView': depth0,'item': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"columns small-12\" style=\"overflow: hidden;\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "root", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeControl/treeItem"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {hash:{
    'on': ("mouseDown"),
    'target': ("view")
  },hashTypes:{'on': "STRING",'target': "STRING"},hashContexts:{'on': depth0,'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-expand")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(3, program3, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document view.item.root::move")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.sortedChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(5, program5, data),fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program5(depth0,data) {
  
  
  data.buffer.push(" <i class=\"animate animate-loader-20\"></i> ");
  }

function program7(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document view.item.root::move")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","STRING"],data:data},helper ? helper.call(depth0, "action", "view.item.id", "editForm", options) : helperMissing.call(depth0, "link-to", "action", "view.item.id", "editForm", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.savedDisplayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "context", "view.item.id", options) : helperMissing.call(depth0, "link-to", "context", "view.item.id", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(15, program15, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "treeControlContextToolbar", "view.item", options) : helperMissing.call(depth0, "render", "treeControlContextToolbar", "view.item", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program16(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program18(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(19, program19, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program19(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-tree-list\" data-parent-id=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "item.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "view.sortedChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(20, program20, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program20(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeItem", {hash:{
    'treeControlView': ("view.treeControlView"),
    'item': ("item")
  },hashTypes:{'treeControlView': "ID",'item': "ID"},hashContexts:{'treeControlView': depth0,'item': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-item view.item.type view.active view.inActive")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "item.childCount", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(7, program7, data),fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.editLink", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(12, program12, data),fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "controller.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "item.childCount", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(18, program18, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeSimple/item"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "resource", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(7, program7, data),fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-expand")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> </span> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "settings.component", "view.nestedSlug", options) : helperMissing.call(depth0, "link-to", "settings.component", "view.nestedSlug", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-tree-list\"> ");
  stack1 = helpers.each.call(depth0, "components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("div"),
    'class': ("umi-item"),
    'disabled': (true),
    'bubbles': (false)
  },hashTypes:{'tagName': "STRING",'class': "STRING",'disabled': "BOOLEAN",'bubbles': "BOOLEAN"},hashContexts:{'tagName': depth0,'class': depth0,'disabled': depth0,'bubbles': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "settings.component", "view.nestedSlug", options) : helperMissing.call(depth0, "link-to", "settings.component", "view.nestedSlug", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeSimple/list"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"columns small-12\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  stack1 = helpers.each.call(depth0, "view.collection", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div>");
  return buffer;
  
});

});
define('application/templates.extends',[], function(){
        

        return function(){
            Ember.TEMPLATES['UMI/module/errors'] = Ember.TEMPLATES['UMI/component/errors'] = Ember.TEMPLATES['UMI/errors'];
            Ember.TEMPLATES['UMI/createForm'] = Ember.TEMPLATES['UMI/editForm'];
        };
    }
);
define('application/models',[], function(){
    


    return function(UMI){

        /**
         * Фильтрация значения полей
         * @type {{stringTrim: stringTrim, htmlSafe: htmlSafe}}
         */
        var propertyFilters = {
            stringTrim: function(value){
                return value.replace(/^\s+|\s+$/g, '');
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
                var activeErrors = this.get('validErrors');
                if(activeErrors && activeErrors.hasOwnProperty(propertyName)){
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

            var collection;
            var fieldValue;

            for(var j = 0; j < collections.length; j++){
                var fields = {};
                var filters = {};
                var validators = {};
                collection = collections[j];

                for(var i = 0; i < collection.fields.length; i++){
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
                        case 'integer':
                        case 'counter':
                            fieldValue = DS.attr('number', params);
                            break;
                        case 'bool':
                            fieldValue = DS.attr('boolean', params);
                            break;
                        case 'date':
                            fieldValue = DS.attr('CustomDate', params);
                            break;
                        case 'dateTime':
                            fieldValue = DS.attr('CustomDateTime', params);
                            break;
                        case 'time':
                            fieldValue = DS.attr('string', params);
                            break;
                        case 'serialized':
                            fieldValue = DS.attr('serialized', params);
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

                fields.meta = DS.attr('raw');

                UMI[collection.name.capitalize()] = DS.Model.extend(fields);

                UMI.__container__.lookup('store:main').metaForType(collection.name, {
                    "collectionType": collection.type,
                    "filters": filters,
                    "validators": validators
                });// TODO: Найти рекоммендации на что заменить __container__

                if(collection.source){
                    UMI[collection.name.capitalize() + 'Adapter'] = DS.UmiRESTAdapter.extend({
                        namespace: collection.source.replace(/^\//g, '')
                    });
                }
            }
        };
    };
});
define('text',{load: function(id){throw new Error("Dynamic load not allowed: " + id);}});

define('text!auth/templates/auth.hbs',[],function () { return '<div class="auth-layout">\r\n    <div class="bubbles"></div>\r\n    <div class="bubbles-front"></div>\r\n    <div class="row vertical-center">\r\n        <div class="small-centered columns auth-layout-content">\r\n            <p class="text-center">\r\n                <img src="/resources/build/img/auth-logo.png"/>\r\n            </p>\r\n\r\n            <div>\r\n                {{{outlet}}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n<div class="auth-mask"></div>';});


define('text!auth/templates/index.hbs',[],function () { return '<div class="panel pretty radius shake-it">\r\n    <form name="auth" novalidate="novalidate" class="custom large umi-validator" method="post" action="{{form.attributes.action}}">\r\n        <div class="errors-list">\r\n            {{#if accessError}}\r\n                <div class="alert-box alert visible">\r\n                    <a href="#" class="close">×</a>\r\n                    {{accessError}}\r\n                </div>\r\n            {{/if}}\r\n        </div>\r\n        {{#each form.elements}}\r\n            <div class="row">\r\n                <div class="small-12 columns">\r\n                    {{#ifCond type \'select\'}}\r\n                        <select name="{{attributes.name}}" placeholder="{{label}}" class="select-language">\r\n                            {{#each choices}}\r\n                                <option value="{{attributes.value}}">{{label}}</option>\r\n                            {{/each}}\r\n                        </select>\r\n                    {{else}}\r\n                        <i class="icon icon-{{attributes.name}} input-icon"></i>\r\n                        <input name="{{attributes.name}}" placeholder="{{label}}" type="{{attributes.type}}" required="required" autocomplete="off" value="">\r\n                        {{#if validators}}\r\n                            <span class="error">\r\n                                {{#each validators}}\r\n                                    {{message}}\r\n                                {{/each}}\r\n                            </span>\r\n                        {{/if}}\r\n                    {{/ifCond}}\r\n                </div>\r\n            </div>\r\n        {{/each}}\r\n        <div class="row">\r\n            <div class="small-6 small-offset-6 columns">\r\n                <input name="submit" class="button radius expand s-margin-clear" type="submit" value="Войти" />\r\n            </div>\r\n        </div>\r\n    </form>\r\n</div>';});


define('text!auth/templates/errors.hbs',[],function () { return '<div class="alert-box alert">\r\n    <a href="#" class="close">×</a>\r\n    {{#if error}}\r\n    {{error}}\r\n    {{else}}\r\n    Форма заполнена некорректно.\r\n    {{/if}}\r\n</div>';});

define('auth/templates',[
    'text!./templates/auth.hbs', 'text!./templates/index.hbs', 'text!./templates/errors.hbs', 'Handlebars'
], function(tpl, index, errors){
    
    return function(Auth){
        Auth.TEMPLATES.app = Handlebars.compile(tpl);
        Auth.TEMPLATES.index = Handlebars.compile(index);
        Auth.TEMPLATES.lostPassword = Handlebars.compile(index);
        Auth.TEMPLATES.forgetLink = Handlebars.compile('{{#link-to "lostPassword" class="button"}}Забыли пароль?{{/link-to}}');
        Auth.TEMPLATES.indexLink = Handlebars.compile('{{#link-to "index" class="button"}}Войти в CMS{{/link-to}}');
        Auth.TEMPLATES.errors = Handlebars.compile(errors);
    };
});
define('auth/main',['auth/templates', 'Handlebars', 'jquery'], function(templates){
    

    /**
     * @param {Object} [authParams]
     * @param {Object} [authParams.accessError] Объект ошибки доступа к ресурсу window.UmiSettings.authUrl
     * @param {Boolean} [authParams.appIsFreeze] Приложение уже загружено
     * @param {HTMLElement} appLayout корневой DOM элемент приложения
     */
    return function(authParams){
        /**
         * Сбрасываем настройки ajax установленые админ приложением (появляются после выхода из системы)
         * @method ajaxSetup
         */
        $.ajaxSetup({
            headers: {'X-Csrf-Token': null},
            error: function(){}
        });

        /**
         * Компонент авторизации
         * @module Auth
         */
        var Auth = {
            /**
             * Метод возвращает ошибки доступа к ресурсу window.UmiSettings.authUrl
             * @method accessError
             * @returns {*|error.message|string}
             */
            accessError: function(){
                if(authParams && authParams.accessError && authParams.accessError.status !== 401){
                    return authParams.accessError.responseJSON && authParams.accessError.responseJSON.result.error.message;
                }
            },
            /**
             * Шаблоны авторизации
             * @property TEMPLATES
             */
            TEMPLATES: {},
            /**
             * Шаблоны форм
             * @property forms
             */
            forms: {},
            /**
             * Получает форму для action
             * @param {null|String} action
             * @returns Object $.Deferred
             */
            getForm: function(action){
                var deferred = $.Deferred();
                action =  action || 'form';
                var self = this;
                $.get(window.UmiSettings.baseApiURL + '/action/' + action).then(function(results){
                    self.forms[action] = results.result.form;
                    deferred.resolve();
                });
                return deferred;
            },
            /**
             *
             * @property validator
             */
            validator: {
                /**
                 * При некорректной авторизации метод "трясёт" форму словно говоря НЕТ (не используется)
                 * @method shake
                 */
                shake: function(){
                    function shake(id, a, d){
                        id.style.left = a.shift() + 'px';
                        if(a.length > 0){
                            setTimeout(function(){
                                shake(id, a, d);
                            }, d);
                        }
                    }

                    var p = [10, 20, 10, 0, -10, -20, -10, 0];
                    p = p.concat(p.concat(p));
                    var i = document.getElementsByClassName('shake-it').item(0);
                    i.style.position = 'relative';
                    shake(i, p, 20);
                },
                /**
                 * Метод валидирует форму
                 * @method check
                 * @param form
                 * @returns {boolean}
                 */
                check: function(form){
                    var i;
                    var element;
                    var pattern;
                    var valid = true;
                    var removeError = function(el){
                        el.onfocus = function(){
                            $(this).closest('.columns').removeClass('error');
                        };
                    };

                    var toggleError = function(element, valid){
                        if(valid){
                            $(element).closest('.columns').removeClass('error');
                            element.onfocus = null;
                        } else{
                            $(element).closest('.columns').addClass('error');
                            removeError(element);
                            return true;
                        }
                    };

                    for(i = 0; i < form.elements.length; i++){

                        element = form.elements[i];
                        if((element.hasAttribute('required') || element.value) && element.hasAttribute('pattern')){
                            pattern = new RegExp(element.getAttribute('pattern'));
                            if(!pattern.test(element.value)){
                                valid = false;
                            }
                            if(toggleError(element, valid)){
                                break;
                            }
                        } else if(element.hasAttribute('required')){
                            if(!element.value){
                                valid = false;
                            }
                            if(toggleError(element, valid)){
                                break;
                            }
                        }
                    }
                    return valid;
                }
            },
            /**
             * Метод загрузает админ приложение после успешной авторизации,
             * и вызывает метод destroy() для приложение Auth
             * @method transition
             */
            transition: function(){
                window.applicationLoading = $.Deferred();
                // Событие при успешном переходе
                document.onmousemove = null;
                var authLayout = document.querySelector('.auth-layout');
                var maskLayout = document.querySelector('.auth-mask');
                $(authLayout).addClass('off is-transition');

                var removeAuth = function(){
                    // Анимация осуществляется с помощью css transition.
                    // Время анимации .7s
                    $(authLayout).addClass('fade-out');
                    setTimeout(function(){
                        authLayout.parentNode.removeChild(authLayout);
                        maskLayout.parentNode.removeChild(maskLayout);
                        Auth.destroy();
                        //Auth = null; TODO: Нужно удалять приложение Auth после авторизации
                    }, 800);
                };

                if(authParams.appIsFreeze){
                    window.applicationLoading.resolve();
                    $(authParams.appLayout).removeClass('off fade-out');
                    removeAuth();
                } else{
                    require(['application/main'], function(application){
                        application();
                        window.applicationLoading.then(function(){
                            removeAuth();
                        });
                    });
                }
            },
            /**
             * Старт приложения авторизации
             * @method init
             */
            init: function(){
                var self = this;

                /**
                 * Регистрация хелпера ifCond, позволяющего сравнивать значения в шаблоне
                 * method registerHelper
                 */
                Handlebars.registerHelper('ifCond', function(v1, v2, options) {
                    if(v1 === v2) {
                        return options.fn(this);
                    }
                    return options.inverse(this);
                });

                /**
                 * Загружает шаблоны определёные в templates.js
                 * method templates
                 */
                templates(self);

                this.getForm().then(function(){
                    // Проверяем есть ли шаблон и если нет то собираем его
                    if(!document.querySelector('.auth-layout')){
                        var helper = document.createElement('div');
                        helper.innerHTML = self.TEMPLATES.app({outlet: self.TEMPLATES.index({accessError: self.accessError, form: self.forms.form})});
                        helper = document.body.appendChild(helper);
                        $(helper.firstElementChild).unwrap();
                    }
                    $('body').removeClass('loading');

                    var bubbles = document.querySelector('.bubbles');
                    var bubblesFront = document.querySelector('.bubbles-front');
                    var parallax = function(event){
                        var bodyWidth = document.body.offsetWidth;
                        var bodyHeight = document.body.offsetHeight;
                        var deltaX = 0.04;
                        var deltaY = 0.04;
                        var left = (bodyWidth / 2 - event.clientX) * deltaX;
                        var top = (bodyHeight / 2 - event.clientY) * deltaY;
                        bubbles.style.marginLeft = left + 'px';
                        bubbles.style.marginTop = top + 'px';
                        bubblesFront.style.marginLeft = left * 0.2 + 'px';
                        bubblesFront.style.marginTop = top * 0.2 + 'px';
                    };

                    var bubblesHidden = true;
                    document.onmousemove = function(event){
                        if(bubblesHidden){
                            bubbles.className = 'bubbles visible';
                            bubblesFront.className = 'bubbles-front visible';
                            bubblesHidden = false;
                        }
                        parallax(event);
                    };

                    if(history.state && history.state.hasOwnProperty('language')){
                        $('.select-language').val(history.state.language);
                    }

                    $(document).on('change.umi.auth', '.select-language', function(){
                        history.replaceState({language: this.value}, "?language=" + this.value);
                        window.location.href = window.location.href;
                    });

                    $(document).on('click.umi.auth', '.close', function(){
                        this.parentNode.parentNode.removeChild(this.parentNode);
                        return false;
                    });

                    var errorsBlock = document.querySelector('.errors-list');

                    $(document).on('submit.umi.auth', 'form', function(){
                        if(!self.validator.check(this)){
                            return false;
                        }
                        var submitButton = this.querySelector('input[type="submit"]');
                        $(submitButton).addClass('loading');
                        var submit = this.elements.submit;
                        submit.setAttribute('disabled', 'disabled');
                        var data = $(this).serialize();
                        var action = this.getAttribute('action');
                        var deffer = $.post(action, data);

                        var authFail = function(error){
                            $(submitButton).removeClass('loading');
                            submit.removeAttribute('disabled');
                            var errorList = {error: error.responseJSON.result.error.message};
                            errorsBlock.innerHTML = self.TEMPLATES.errors(errorList);
                            $(errorsBlock).children('.alert-box').addClass('visible');
                        };

                        deffer.done(function(data){
                            var objectMerge = function(objectBase, objectProperty){
                                for(var key in objectProperty){
                                    if(objectProperty.hasOwnProperty(key)){
                                        if(key === 'token'){
                                            $.ajaxSetup({
                                                headers: {'X-Csrf-Token': objectProperty[key]}
                                            });
                                        }
                                        objectBase[key] = objectProperty[key];
                                    }
                                }
                            };

                            if(data.result){
                                objectMerge(window.UmiSettings, data.result);
                            }

                            self.transition();
                        });
                        deffer.fail(function(error){
                            authFail(error);
                        });
                        return false;
                    });
                });
            },
            destroy: function(){
                $(document).off('click.umi.auth');
                $(document).off('submit.umi.auth');
                $(document).off('change.umi.auth');
            }
        };

        Auth.init();
    };
});
define('application/router',[], function(){
    
    return function(UMI){
        /**
         @module UMI
         @submodule Router
         **/
        UMI.Router.reopen({
            location: 'auto',
            rootURL: window.UmiSettings.baseURL
        });

        /**
         @class map
         @constructor
         */
        UMI.Router.map(function(){
            this.resource('module', {path: '/:module'}, function(){
                this.route('errors', {path: '/:status'});
                this.resource('component', {path: '/:component'}, function(){
                    this.route('errors', {path: '/:status'});
                    this.resource('context', {path: '/:context'}, function(){
                        this.resource('action', {path: '/:action'});
                    });
                });
            });
        });

        /**
         * @class ApplicationRoute
         * @extends Ember.Route
         * @uses modelsFactory
         */
        UMI.ApplicationRoute = Ember.Route.extend({
            /**
             Инициализирует модели данных, модули и компоненты для Dock.

             @method model
             @return
             **/
            model: function(params, transition){
                var self = this;
                var promise;

                try{
                    if(!UmiSettings.baseApiURL){
                        throw new Error('Для UmiSettings не задан baseApiURL');
                    }
                    promise = $.get(UmiSettings.baseApiURL).then(function(results){
                        if(results && results.result){
                            var result = results.result;
                            self.controllerFor('application').set('settings', result);
                            if(result.collections){
                                UMI.modelsFactory(result.collections);
                            }
                            if(result.modules){
                                self.controllerFor('application').set('modules', result.modules);
                            }
                            if(result.i18n){
                                UMI.i18n.setDictionary(result.i18n);
                            }
                        } else{
                            try{
                                throw new Error('Запрашиваемый ресурс ' + UmiSettings.baseApiURL + ' некорректен.');
                            } catch(error){
                                transition.abort();
                                transition.send('dialogError', error);
                            }
                        }
                    }, function(error){
                        var becameError = new Error(error);
                        error.stack = becameError.stack;
                        transition.send('dialogError', error);
                    });
                } catch(error){
                    transition.abort();
                    transition.send('dialogError', error);
                } finally{
                    return promise;
                }
            },
            /**
             * Сохраняет обьект
             * @method saveObject
             * @param {Object} params Объект аргументов
             * params.object - сохраняемый объект (полностью объект системы)
             * params.handler - элемент (кнопка) вызвавший событие сохранение - JS DOM Element
             * @returns {promise} возвращает promise результатом которого является true в случае успешного сохранения
             */
            saveObject: function(params){
                var isNewObject;
                var self = this;
                var deferred;

                if(!params.object.get('isValid')){
                    if(params.handler){
                        $(params.handler).removeClass('loading');
                    }
                    deferred = Ember.RSVP.defer();
                    return deferred.resolve(false);
                }

                if(params.object.get('currentState.stateName') === 'root.loaded.created.uncommitted'){
                    isNewObject = true;
                }

                return params.object.save().then(
                    function(){
                        params.object.updateRelationhipsMap();

                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }

                        return params.object;
                    },

                    function(results){
                        results = results || {};
                        var self = this;
                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }

                        var data = {
                            'close': false,
                            'title': results.errors,
                            'content': results.message,
                            'confirm': 'Загрузить объект с сервера'
                        };

                        return UMI.dialog.open(data).then(
                            function(){
                                //https://github.com/emberjs/data/issues/1632
                                //params.object.transitionTo('updated.uncommitted');
                                //                                    console.log(params.object.get('currentState.stateName'), results, self);
                                /* params.object.rollback();
                                 params.object.reload();*/
                                return false;
                            }
                        );
                    }
                );
            },

            beforeAdd: function(params){
                var self = this;
                return self.saveObject(params).then(function(addObject){
                    if(addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic'){
                        var parent = addObject.get('parent');
                        if(parent && 'isFulfilled' in parent){
                            return parent.then(function(parent){
                                parent.reload().then(function(parent){
                                    parent.trigger('needReloadHasMany');
                                });
                                return addObject;
                            });
                        } else{
                            // Обновление связей рутовой ноды в дереве.
                            // TODO: подумать как можно избежать обращения к контроллеру дерева.
                            self.get('container').lookup('controller:treeControl').get('root')[0].updateChildren(addObject.get('id'), 'root');
                            return addObject;
                        }
                    } else{
                        return addObject;
                    }
                });
            },

            actions: {
                willTransition: function(){
                    UMI.notification.removeAll();
                },

                logout: function(){
                    var applicationLayout = document.querySelector('.umi-main-view');
                    var maskLayout = document.createElement('div');
                    maskLayout.className = 'auth-mask';
                    maskLayout = document.body.appendChild(maskLayout);
                    $(applicationLayout).addClass('off is-transition');
                    $.post(UmiSettings.baseApiURL + '/action/logout').then(function(){
                        require(['auth/main'], function(auth){
                            auth({appIsFreeze: true, appLayout: applicationLayout});
                            $(applicationLayout).addClass('fade-out');
                            Ember.run.later('', function(){
                                $(applicationLayout).removeClass('is-transition');
                                maskLayout.parentNode.removeChild(maskLayout);
                            }, 800);
                        });
                    });
                },

                dialogError: function(error){
                    var settings = this.parseError(error);
                    if(settings !== 'silence'){
                        settings.close = true;
                        settings.title = error.status + '. ' + error.statusText;
                        UMI.dialog.open(settings).then();
                    }
                },

                /**
                 Метод генерирует фоновую ошибку (красный tooltip)
                 @method backgroundError
                 @property error Object {status: status, title: title, content: content, stack: stack}
                 */
                backgroundError: function(error){
                    var settings = this.parseError(error);
                    if(settings !== 'silence'){
                        settings.type = 'error';
                        settings.duration = false;
                        UMI.notification.create(settings);
                    }
                },

                /**
                 Метод генерирует ошибку (выводится вместо шаблона)
                 @method templateLogs
                 @property error Object {status: status, title: title, content: content, stack: stack}
                 */
                templateLogs: function(error, parentRoute){
                    parentRoute = parentRoute || 'module';
                    var dataError = this.parseError(error);
                    if(dataError !== 'silence'){
                        var model = Ember.Object.create(dataError);
                        this.intermediateTransitionTo(parentRoute + '.errors', model);
                    }
                },

                showPopup: function(popupType, object, meta){
                    UMI.PopupView.create({
                        container: this.container,
                        popupType: popupType,
                        object: object,
                        meta: meta
                    }).append();
                },

                /// global actions
                /**
                 * Сохраняет обьект вызывая метод saveObject
                 * @method save
                 */
                save: function(params){
                    this.saveObject(params);
                },

                saveAndGoBack: function(params){
                    var self = this;
                    self.saveObject(params).then(function(isSaved){
                        if(isSaved){
                            self.send('backToFilter');
                        }
                    });
                },

                add: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(addObject){
                        self.send('edit', addObject);
                    });
                },

                addAndGoBack: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(){
                        self.send('backToFilter');
                    });
                },

                addAndCreate: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(addObject){
                        var behaviour = {type: addObject.get('type')};
                        if(addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic'){
                            return addObject.get('parent').then(function(parent){
                                self.send('create', parent, behaviour);
                            });
                        } else{
                            self.send('create', addObject, behaviour);
                        }
                    });
                },

                switchActivity: function(object){
                    try{
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchActivitySource = this.controllerFor('component').get('settings').actions[(object.get('active') ? 'de' : '') + 'activate'].source;
                        switchActivitySource = UMI.Utils.replacePlaceholder(object, switchActivitySource);
                        $.ajax({
                            url: switchActivitySource,
                            type: "POST",
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function(){
                            object.reload();
                        });
                    } catch(error){
                        this.send('backgroundError', error);
                    }
                },

                create: function(params){
                    var type = params.behaviour.type;
                    var parentObject = params.object;
                    var contextId = 'root';
                    if(parentObject.constructor.typeKey){
                        var meta = this.store.metadataFor(parentObject.constructor.typeKey) || {};
                        if(meta.hasOwnProperty('collectionType') && meta.collectionType === 'hierarchic'){
                            contextId = parentObject.get('id');
                        }
                    }
                    this.transitionTo('action', contextId, 'createForm', {queryParams: {'type': type}});
                },

                edit: function(object){
                    this.transitionTo('action', object.get('id'), 'editForm');
                },

                viewOnSite: function(object){
                    var link;
                    if(object){
                        link = object._data.meta.pageUrl;
                    } else{
                        link = window.UmiSettings.baseSiteURL;
                    }

                    link = window.location.host + link;
                    var tab = window.open('//' + link.replace('\/\/', '\/'), '_blank');
                    tab.focus();
                },

                /**
                 * Восстанавливает объект из корзины
                 * @method untrash
                 * @param object
                 * @returns {*|Promise}
                 */
                untrash: function(object){
                    var self = this;
                    var promise;
                    var serializeObject;
                    var untrashAction;
                    var collectionName;
                    var store = self.get('store');
                    var objectId;
                    try{
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        collectionName = object.constructor.typeKey;
                        untrashAction = self.controllerFor('component').get('settings').actions.untrash;
                        if(!untrashAction){
                            throw new Error('Action untrash not supported for component.');
                        }
                        promise = $.ajax({
                            url: untrashAction.source + '?id=' + objectId + '&collection=' + collectionName,
                            type: "POST",
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function(){
                            var invokedObjects = [];
                            invokedObjects.push(object);
                            var collection = store.all(collectionName);
                            if(store.metadataFor(collectionName).collectionType === 'hierarchic'){
                                var mpath = object.get('mpath');
                                var parent;
                                if(Ember.typeOf(mpath) === 'array' && mpath.length){
                                    for(var i = 0; i < mpath.length; i++){
                                        parent = collection.findBy('id', mpath[i]  + "");
                                        if(parent){
                                            invokedObjects.push(parent);
                                        }
                                    }
                                }
                            }

                            invokedObjects.invoke('unloadRecord');
                            var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" restore.'};
                            UMI.notification.create(settings);
                        }, function(){
                            var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" not restored.'};
                            UMI.notification.create(settings);
                        });
                    } catch(error){
                        self.send('backgroundError', error);
                    } finally{
                        return promise;
                    }
                },

                /**
                 * Удаляет объект (перемещает в корзину)
                 * @method trash
                 * @param object
                 * @returns {*|Promise}
                 */
                trash: function(object){
                    var self = this;
                    var store = self.get('store');
                    var promise;
                    var serializeObject;
                    var isActiveContext;
                    var trashAction;
                    var objectId;
                    try{
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        isActiveContext = this.modelFor('context') === object;
                        trashAction = this.controllerFor('component').get('settings').actions.trash;
                        if(!trashAction){
                            throw new Error('Action trash not supported for component.');
                        }
                        promise = $.ajax({
                            url: trashAction.source + '?id=' + objectId,
                            type: "POST",
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function(){
                            var collectionName = object.constructor.typeKey;
                            var invokedObjects = [];
                            invokedObjects.push(object);
                            if(store.metadataFor(collectionName).collectionType === 'hierarchic'){
                                var collection = store.all(collectionName);
                                collection.find(function(item){
                                    var mpath = item.get('mpath') || [];
                                    if(mpath.contains(parseFloat(objectId)) && mpath.length > 1){
                                        invokedObjects.push(item);
                                    }
                                });
                            }

                            invokedObjects.invoke('unloadRecord');
                            var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" удалено в корзину.'};
                            UMI.notification.create(settings);
                            if(isActiveContext){
                                self.send('backToFilter');
                            }
                        }, function(){
                            var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" не удалось поместить в корзину.'};
                            UMI.notification.create(settings);
                        });
                    } catch(error){
                        this.send('backgroundError', error);
                    } finally{
                        return promise;
                    }
                },

                /**
                 * Спрашивает пользователя и в случае положительного ответа безвозвратно удаляет объект
                 * @method delete
                 * @param object
                 * @returns {*|Promise}
                 */
                "delete": function(object){
                    var self = this;
                    var isActiveContext = this.modelFor('context') === object;
                    var data = {
                        'close': false,
                        'title': 'Удаление "' + object.get('displayName') + '".',
                        'content': '<div>Объект будет удалён без возможности востановления, всё равно продолжить?</div>',
                        'confirm': 'Удалить',
                        'reject': 'Отмена'
                    };
                    return UMI.dialog.open(data).then(
                        function(){
                            var collectionName = object.constructor.typeKey;
                            var store = object.get('store');
                            var objectId = object.get('id');
                            return object.destroyRecord().then(function(){
                                var invokedObjects = [];
                                if(store.metadataFor(collectionName).collectionType === 'hierarchic'){
                                    var collection = store.all(collectionName);
                                    collection.find(function(item){
                                        var mpath = item.get('mpath') || [];
                                        if(mpath.contains(parseFloat(objectId)) && mpath.length > 1){
                                            invokedObjects.push(item);
                                        }
                                    });
                                }
                                invokedObjects.invoke('unloadRecord');
                                var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" успешно удалено.'};
                                UMI.notification.create(settings);
                                if(isActiveContext){
                                    self.send('backToFilter');
                                }
                            }, function(){
                                var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" не удалось удалить.'};
                                UMI.notification.create(settings);
                            });
                        },
                        function(){}
                    );
                },
                /**
                 * Возвращает к списку
                 */
                backToFilter: function(){
                    this.transitionTo('context', 'root');
                }
            },

            /**
             Метод парсит ошибку и возвпращает её в виде объекта (ошибки с Back-end)
             @method parseError
             @return Object|null|String {status: status, title: title, content: content, stack: stack}
             */
            parseError: function(error){
                var parsedError = {
                    status: error.status,
                    title: error.statusText,
                    stack: error.stack
                };

                if(error.status === 403 || error.status === 401){
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    this.send('logout');
                    return 'silence';
                }

                var content;
                if(error.hasOwnProperty('responseJSON')){
                    if(error.responseJSON.hasOwnProperty('result') && error.responseJSON.result.hasOwnProperty('error')){
                        content = error.responseJSON.result.error.message;
                    }
                } else{
                    content = error.responseText || error.message;
                }
                parsedError.content = content;
                return parsedError;
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.IndexRoute = Ember.Route.extend({
            /**
             Выполняет редирект на роут `Module`.
             @method redirect
             @return
             **/
            redirect: function(model, transition){
                var firstChild;
                if(transition.targetName === this.routeName){
                    try{
                        firstChild = this.controllerFor('application').get('modules')[0];
                        if(!firstChild){
                            throw new Error('Ни одного модуля системы не найдено');
                        }
                    } catch(error){
                        transition.send('backgroundError', error);//TODO: Проверить вывод ошибок
                    } finally{
                        return this.transitionTo('module', Ember.get(firstChild, 'name'));//TODO: Нужно дать пользователю выбрать компонент
                    }
                }
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.ModuleRoute = Ember.Route.extend({
            model: function(params, transition){
                var deferred;
                var modules;
                var module;
                try{
                    deferred = Ember.RSVP.defer();
                    modules = this.controllerFor('application').get('modules');
                    module = modules.findBy('name', params.module);
                    if(module){
                        deferred.resolve(module);
                    } else{
                        throw new Error('The module "' + params.module + '" was not found.');
                    }
                } catch(error){
                    deferred.reject(error);
                } finally{
                    return deferred.promise;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var self = this;
                    var deferred;
                    var firstChild;
                    try{
                        deferred = Ember.RSVP.defer();
                        firstChild = Ember.get(model, 'components')[0];
                        if(firstChild){
                            deferred.resolve(self.transitionTo('component', Ember.get(firstChild, 'name')));
                        } else{
                            throw new Error('For module "' + Ember.get(model, 'name') + '" components not found.');
                        }
                    } catch(error){
                        deferred.reject(Ember.run.next(self, function(){this.send('templateLogs', error);}));
                    } finally{
                        return deferred.promise;
                    }
                }
            },

            serialize: function(model){
                return {module: Ember.get(model, 'slug')};
            }
        });

        UMI.ComponentRoute = Ember.Route.extend({
            /**
             * @method model
             * @param params
             * @param transition
             * @returns {*}
             */
            model: function(params, transition){
                var self = this;
                var deferred;
                var components;
                var model;
                var componentName = transition.params.component.component;
                try{
                    deferred = Ember.RSVP.defer();
                    components = Ember.get(this.modelFor('module'), 'components');
                    // filterBy
                    for(var i = 0; i < components.length; i++){
                        if(components[i].name === componentName){
                            model = components[i];
                            break;
                        }
                    }
                    if(model){
                        /**
                         * Ресурс компонента
                         */
                        Ember.$.get(Ember.get(model, 'resource')).then(function(results){
                            var componentController = self.controllerFor('component');
                            if(Ember.typeOf(results) === 'object' && Ember.get(results, 'result.layout')){
                                var settings = results.result.layout;
                                componentController.set('settings', settings);
                                componentController.set('selectedContext', Ember.get(transition,'params.context') ? Ember.get(transition, 'params.context.context') : 'root');
                                deferred.resolve(model);
                            } else{
                                var error = new Error('Ресурс "' + Ember.get(model, 'resource') + '" некорректен.');
                                transition.send('backgroundError', error);
                                deferred.reject();
                            }
                        }, function(error){
                            deferred.reject(Ember.run.next(this, function(){transition.send('templateLogs', error);}));
                        });
                    } else{
                        throw new URIError('The component "' + componentName + '" was not found.');
                    }
                } catch(error){
                    deferred.reject(Ember.run.next(this, function(){transition.send('templateLogs', error);}));
                } finally{
                    return deferred.promise;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var context;
                    try{
                        var emptyControl = this.controllerFor('component').get('settings.contents.emptyContext.redirect');
                        if(emptyControl){
                            context = Ember.get(emptyControl, 'params.slug');
                        } else{
                            context ='root';
                        }
                    } catch(error){
                        transition.send('backgroundError', error);
                    } finally{
                        return this.transitionTo('context', context);
                    }
                }
            },

            serialize: function(model){
                return {component: Ember.get(model, 'name')};
            },

            /**
             * Отрисовываем компонент
             * Если есть sideBar - его тоже отрисовываем
             * @param controller
             */
            renderTemplate: function(controller){
                this.render();

                if(controller.get('sideBarControl')){
                    try{
                        this.render(controller.get('sideBarControl.name'), {
                            into: 'component',
                            outlet: 'sideBar'
                        });
                    } catch(error){
                        this.send('templateLogs', error, 'component');
                    }
                }
            }
        });

        /**
         * Отрисовка выбранного объекта
         * @type {*|void|Object}
         */
        UMI.ContextRoute = Ember.Route.extend({
            model: function(params, transition){
                var componentController;
                var collection;
                var RootModel;
                var model;

                try{
                    componentController = this.controllerFor('component');
                    collection = componentController.get('dataSource');
                    componentController.set('selectedContext', params.context);// TODO: зачем это вообще нужно?

                    if(params.context === 'root'){
                        RootModel = Ember.Object.extend({});
                        model = new Ember.RSVP.Promise(function(resolve){
                            resolve(RootModel.create({'id': 'root', type: 'base'}));
                        });
                    } else{
                        switch(Ember.get(collection, 'type')){
                            case 'static':
                                model = new Ember.RSVP.Promise(function(resolve, reject){
                                    var objects = Ember.get(collection, 'objects');
                                    var object;
                                    // filterBy
                                    for(var i = 0; i < objects.length; i++){
                                        if(objects[i].id === params.context){
                                            object = objects[i];
                                            break;
                                        }
                                    }
                                    if(object){
                                        resolve(object);
                                    } else{
                                        reject('Не найден объект с ID ' + params.context);
                                    }
                                });
                                break;
                            case 'collection':
                                if(this.store.hasRecordForId(Ember.get(collection, 'name'), params.context)){
                                    model = this.store.getById(Ember.get(collection, 'name'), params.context);
                                    model = model.reload();
                                } else{
                                    model = this.store.find(Ember.get(collection, 'name'), params.context);
                                }
                                break;
                            default:
                                throw new Error('Неизвестный тип dataSource компонента.');
                        }
                    }
                } catch(error){
                    Ember.run.next(this, function(){transition.send('templateLogs', error);});
                } finally{
                    return model;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var control;
                    var controlName;
                    try{
                        control = this.controllerFor('component').get('contentControls')[0];
                        controlName = Ember.get(control, 'id');
                        if(!controlName){
                            throw new Error('Действия для данного контекста не доступны.');
                        }
                    } catch(error){
                        transition.send('backgroundError', error);
                    } finally{
                        return this.transitionTo('action', controlName);
                    }
                }
            },

            serialize: function(model){
                if(model){
                    return {context: Ember.get(model, 'id')};
                }
            }
        });

        UMI.ActionRoute = Ember.Route.extend({
            queryParams: {
                type: {
                    refreshModel: true,
                    replace: true
                }
            },

            model: function(params, transition){
                var self = this;
                var actionName;
                var contextModel;
                var componentController;
                var contentControls;
                var contentControl;
                var routeData;
                var createdParams;
                var deferred;
                var actionResource;
                var actionResourceName;
                var controlObject;

                try{
                    deferred = Ember.RSVP.defer();
                    actionName = params.action;
                    contextModel = this.modelFor('context');
                    componentController = this.controllerFor('component');
                    contentControls = componentController.get('contentControls');
                    contentControl = contentControls.findBy('id', actionName);
                    routeData = {
                        'object': contextModel,
                        'control': contentControl
                    };

                    if(Ember.get(contentControl, 'params.isStatic')){
                        // Понадобится когда не будет необходимости менять метаданные контрола в зависимости от контекста
                        deferred.resolve(routeData);
                    } else{
                        actionResourceName = Ember.get(contentControl, 'params.action');
                        actionResource = Ember.get(componentController, 'settings.actions.' + actionResourceName + '.source');

                        if(actionResource){
                            controlObject = routeData.object;
                            if(actionName === 'createForm'){
                                createdParams = {};
                                if(componentController.get('dataSource.type') === 'collection'){
                                    var meta = this.store.metadataFor(componentController.get('dataSource.name')) || {};
                                    if(Ember.get(meta, 'collectionType') === 'hierarchic' && routeData.object.get('id') !== 'root'){
                                        createdParams.parent = contextModel;
                                    }
                                }
                                if(transition.queryParams.type){
                                    createdParams.type = transition.queryParams.type;
                                }
                                routeData.createObject = self.store.createRecord(componentController.get('dataSource.name'), createdParams);
                                controlObject = routeData.createObject;
                            }
                            actionResource = UMI.Utils.replacePlaceholder(controlObject, actionResource);

                            Ember.$.get(actionResource).then(function(results){
                                var dynamicControl;
                                var dynamicControlName;
                                if(actionName === 'dynamic'){
                                    dynamicControl = Ember.get(results, 'result') || {};
                                    for(var key in dynamicControl){
                                        if(dynamicControl.hasOwnProperty(key)){
                                            dynamicControlName = key;
                                        }
                                    }
                                    dynamicControl = dynamicControl[dynamicControlName] || {};
                                    dynamicControl.name = dynamicControlName;

                                    UMI.Utils.objectsMerge(routeData.control, dynamicControl);
                                } else{
                                    Ember.set(routeData.control, 'meta', Ember.get(results, 'result.' + actionResourceName));
                                }
                                deferred.resolve(routeData);
                            }/*, function(error){
                             Сообщение ошибки в таких случаях возникает на уровне ajaxSetup, получается две одинаковых. Нужно научить ajax наследованию
                             deferred.reject(transition.send('backgroundError', error));
                             }*/);
                        } else{
                            throw new Error('Действие ' + Ember.get(contentControl, 'name') + ' для данного контекста недоступно.');
                        }

                    }
                } catch(error){
                    deferred.reject(transition.send('backgroundError', error));
                } finally{
                    return deferred.promise;
                }
            },

            serialize: function(routeData){
                if(Ember.get(routeData, 'control')){
                    return {action: Ember.get(routeData, 'control.id')};
                }
            },

            renderTemplate: function(controller, routeData){
                try{
                    var templateType = Ember.get(routeData, 'control.name');
                    this.render(templateType, {
                        controller: controller
                    });
                } catch(error){
                    this.send('templateLogs', error, 'component');
                }
            },

            setupController: function(controller, model){
                this._super(controller, model);
                if(model.createObject){
                    Ember.set(model, 'object', model.createObject);
                    Ember.set(model, 'createObject', null);
                }
                controller.set('model', model);
            },

            actions: {
                /**
                 Метод вызывается при уходе с роута.
                 @event willTransition
                 @param {Object} transition
                 */
                willTransition: function(transition){
                    if(transition.params.action && transition.params.action.action !== 'createForm'){
                        this.get('controller').set('typeName', null);
                    }
                    var model = Ember.get(this.modelFor('action'), 'object');
                    if(Ember.get(model, 'isNew')){
                        model.deleteRecord();
                    }
                    if(Ember.get(model, 'isDirty')){
                        transition.abort();
                        var data = {
                            'close': false,
                            'title': 'Изменения не были сохранены.',
                            'content': 'Переход на другую страницу вызовет потерю несохраненых изменений. Остаться на странице чтобы сохранить изменения?',
                            'confirm': 'Остаться на странице',
                            'reject': 'Продолжить без сохранения'
                        };
                        return UMI.dialog.open(data).then(
                            function(){/*При положительном ответе делать ничего не нужно*/ },
                            function(){
                                if(!model.get('isValid')){
                                    model.set('validErrors', null);
                                    model.send('becameValid');
                                }
                                model.rollback();
                                transition.retry();
                            }
                        );
                    }
                }
            }
        });
    };
});
define('application/controllers',[], function(){
    
    return function(UMI){
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null,
            modules: null
        });

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            /**
             * Уникальное имя компонента
             * @property name
             */
            name: function(){
                return this.get('container').lookup('route:module').get('context.name') + Ember.String.capitalize(this.get('model.name'));
            }.property('model.name'),

            settings: null,

            dataSourceBinding: 'settings.dataSource',

            /**
             Выбранный контекcт, соответствующий модели роута 'Context'
             @property selectedContext
             @type String
             @default null
             */
            selectedContext: null,
            /**
             Вычисляемое свойсво возвращающее массив контролов для текущего контекста
             @method contentControls
             @return Array Возвращает массив Ember объектов содержащий возможные действия текущего контрола
             */
            contentControls: function(){
                var self = this;
                var contentControls = [];
                var settings = this.get('settings');
                try{

                    var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                    var controls = settings.contents[selectedContext];
                    var key;
                    var control;
                    for(key in controls){ //for empty - createForm & filter
                        if(controls.hasOwnProperty(key)){
                            control = controls[key];
                            control.id = key;// used by router
                            control.name = key;// used by templates
                            contentControls.push(control);
                        }
                    }
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function(){
                        self.send('templateLogs', errorObject, 'component');
                    });
                }

                return contentControls;
            }.property('settings', 'selectedContext'),

            /**
             Контрол компонента в области сайд бара
             @property sideBarControl
             @type Boolean
             @default false
             */
            sideBarControl: function(){
                var sideBarControl;
                var self = this;
                try{
                    var settings = this.get('settings');
                    if(settings && settings.hasOwnProperty('sideBar')){
                        var control;
                        var controlParams;
                        for(control in settings.sideBar){
                            if(settings.sideBar.hasOwnProperty(control)){
                                controlParams = settings.sideBar[control];
                                if(Ember.typeOf(controlParams) !== 'object'){
                                    controlParams = {};
                                }
                                sideBarControl = controlParams;
                                sideBarControl.name = control;
                            }
                        }
                    }

                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function(){
                        self.send('templateLogs', errorObject, 'component');
                    });
                }

                return sideBarControl;
            }.property('settings')
        });

        UMI.ContextController = Ember.ObjectController.extend({});

        UMI.ActionController = Ember.ObjectController.extend({
            queryParams: ['type'],
            type: null
        });
    };
});
define('application/views',[], function(){
    

    return function(UMI){

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            didInsertElement: function(){
                $('body').removeClass('loading');
                if(window.applicationLoading){
                    window.applicationLoading.resolve();
                }
            }
        });

        UMI.ComponentView = Ember.View.extend({
            classNames: ['umi-content', 's-full-height'],

            filtersView: Ember.View.extend({
                classNames: ['umi-tree-control-filters']
            })
        });
    };
});
define(
    'App',[
        'DS',
        'Modernizr',
        'iscroll',
        'ckEditor',
        'jqueryUI',
        'elFinder',
        'timepicker',
        'moment',
        'application/config',
        'application/utils',
        'application/i18n',
        'application/templates.compile',
        'application/templates.extends',
        'application/models',
        'application/router',
        'application/controllers',
        'application/views'
    ],
    function(DS, Modernizr, iscroll, ckEditor, jqueryUI, elFinder, timepicker, moment, config, utils, i18n, templates, templatesExtends, models, router, controller, views){
        

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
        i18n(UMI);

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
            normalizePayload: function(payload){
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

        UMI.ApplicationStore = DS.Store.extend({
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
                        queryParams.push('meta');
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

                var deffered = Ember.RSVP.defer();
                promise.then(function(result){
                    var i;
                    var objects = [];
                    Ember.run.later(function(){//TODO: Очередь запросов
                        var updateMany = function(self, objects, type, params){
                            objects.push(self.update(type, params));
                        };
                        for(i = 0; i < result.length; i++){
                            updateMany(self, objects, type, result[i]);
                        }
                        deffered.resolve(objects);
                    }, 700);
                });
                return promiseArray(deffered.promise);
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
                deserialized = Ember.isNone(deserialized) ? "" : String(deserialized);
                if(deserialized){
                    deserialized = moment(deserialized).format('DD.MM.YYYY');
                }
                return deserialized;
            },
            serialize: function(serialized){
                if(serialized){
                    serialized = moment(serialized, 'DD.MM.YYYY').format('YYYY-MM-DD');
                }
                return serialized;
            }
        });

        /**
         * Приводит приходящий объект date:{} к нужному формату даты
         * TODO Смена формата в зависимости от языка системы
         * TODO Почему не прилылать в простом timeStamp
         * DS.attr('date')
         * @type {*|void|Object}
         */
        UMI.CustomDateTimeTransform = DS.Transform.extend({
            deserialize: function(deserialized){
                if(Ember.typeOf(deserialized) === 'object' && deserialized.date){
                    Ember.set(deserialized, 'date', moment(deserialized.date).format('DD.MM.YYYY h:mm:ss'));
                    deserialized = JSON.stringify(deserialized);
                } else{
                    deserialized = "";
                }
                return deserialized;
            },
            serialize: function(serialized){
                if(serialized){
                    try{
                        serialized = JSON.parse(serialized);
                        if(serialized.date){
                            Ember.set(serialized, 'date', moment(serialized.date, 'DD.MM.YYYY h:mm:ss').format('YYYY-MM-DD h:mm:ss'));
                        }
                    } catch(error){
                        error.message = 'Некорректное значение поля. Ожидается массив или null. ' + error.message;
                        this.get('container').lookup('route:application').send('backgroundError', error);
                    }
                } else{
                    serialized = null;
                }
                return serialized;
            }
        });

        /**
         * Значение поля "сериализованный массив"
         * DS.attr('serialized')
         */
        UMI.SerializedTransform = DS.Transform.extend({
            deserialize: function(deserialized){
                if(deserialized){
                    if(Ember.typeOf(deserialized) === 'array'){
                        deserialized.sort();
                    }
                    deserialized = JSON.stringify(deserialized);
                } else{
                    deserialized = '';
                }
                return deserialized;
            },
            serialize: function(serialized){
                if(serialized){
                    try{
                        serialized = JSON.parse(serialized);
                    } catch(error){
                        error.message = 'Некорректное значение поля. Ожидается массив или null. ' + error.message;
                        this.get('container').lookup('route:application').send('backgroundError', error);
                    }
                } else{
                    serialized = [];
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

        templatesExtends();
        models(UMI);
        router(UMI);
        controller(UMI);
        views(UMI);

        return UMI;
    }
);
define('toolbar/view',['App'], function(UMI){
    

    return function(){

        UMI.ToolbarElementView = Ember.View.extend({
            tagName: 'li',

            template: function(){
                var self = this;
                var type = this.get('context.type');
                if(this.get(type + 'View')){
                    return Ember.Handlebars.compile('{{view view.' + type + 'View meta=this}}');
                } else{
                    try{
                        throw new Error('View c типом ' + type + ' не зарегестрирован для toolbar контроллера');
                    } catch(error){
                        Ember.run.next(function(){
                            self.get('controller').send('backgroundError', error);
                        });
                    }
                }
            }.property(),

            buttonView: function(){
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.buttonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                var instance = UMI.ButtonView.extend(behaviour);
                return instance;
            }.property(),

            dropdownButtonView: function(){
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.dropdownButtonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                var instance = UMI.DropdownButtonView.extend(behaviour);
                return instance;
            }.property(),

            splitButtonView: function(){
                var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent);
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.splitButtonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                instance = instance.extend(behaviour);
                return instance;
            }.property()
        });


        UMI.ToolbarView = Ember.View.extend({
            /**
             * @property layoutName
             */
            layoutName: 'partials/toolbar',
            /**
             * @property classNames
             */
            classNames: ['s-unselectable', 'umi-toolbar'],

            elementView: UMI.ToolbarElementView
        });
    };
});
define(
    'partials/toolbar/buttons/globalBehaviour',['App'],
    function(UMI){
        

        return function(){
            /**
             * Абстрактный класс поведения
             * @class
             * @abstract
             */
            UMI.GlobalBehaviour = Ember.Object.extend({
                save: {
                    label: function(){
                        if(this.get('controller.object.isDirty')){
                            return this.get('defaultBehaviour.attributes.label');
                        } else{
                            return this.get('meta.attributes.states.notModified.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.isDirty', 'defaultBehaviour'),
                    classNameBindings: ['controller.object.isDirty::disabled', 'controller.object.isValid::disabled'],
                    beforeSave: function(){
                        var model = this.get('controller.object');
                        if(!model.get('isDirty') || !model.get('isValid')){
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },
                    actions: {
                        save: function(){
                            var params = this.beforeSave();
                            if(params){
                                this.get('controller').send('save', params);
                            }
                        },

                        saveAndGoBack: function(){
                            var params = this.beforeSave();
                            if(params){
                                this.get('controller').send('saveAndGoBack', params);
                            }
                        }
                    }
                },

                create: {
                    actions: {
                        create: function(params){
                            var behaviour = params.behaviour;
                            var object = params.object || this.get('controller.object');
                            this.get('controller').send('create', {behaviour: behaviour, object: object});
                        }
                    }
                },

                switchActivity: {
                    label: function(){
                        if(this.get('controller.object.active')){
                            return this.get('meta.attributes.states.deactivate.label');
                        } else{
                            return this.get('meta.attributes.states.activate.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.active'),
                    classNameBindings: ['controller.object.active::umi-disabled'],
                    actions: {
                        switchActivity: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('switchActivity', model);
                        }
                    }
                },

                backToFilter: {
                    actions: {
                        backToFilter: function(){
                            this.get('controller').send('backToFilter');
                        }
                    }
                },

                trash: {
                    actions: {
                        trash: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('trash', model);
                        }
                    }
                },

                untrash: {
                    actions: {
                        untrash: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('untrash', model);
                        }
                    }
                },

                "delete": {
                    actions: {
                        "delete": function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('delete', model);
                        }
                    }
                },

                viewOnSite: {
                    actions: {
                        viewOnSite: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('viewOnSite', model);
                        }
                    }
                },

                edit: {
                    actions: {
                        edit: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('edit', model);
                        }
                    }
                },

                add: {
                    classNameBindings: ['controller.object.isValid::disabled'],
                    beforeAdd: function(params){
                        params = params || {};
                        var model = params.object || this.get('controller.object');
                        if(!model.get('isValid')){
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },
                    actions: {
                        add: function(params){
                            params = params || {};
                            var addParams = this.beforeAdd(params.object);
                            if(addParams){
                                this.get('controller').send('add', addParams);
                            }
                        },

                        addAndGoBack: function(){
                            var params = this.beforeAdd();
                            if(params){
                                this.get('controller').send('addAndGoBack', params);
                            }
                        },

                        addAndCreate: function(){
                            var params = this.beforeAdd();
                            if(params){
                                this.get('controller').send('addAndCreate', params);
                            }
                        }
                    }
                }
            });
        };
    }
);
define('partials/toolbar/buttons/button/main',['App'],
    function(UMI){
        

        return function(){
            UMI.ButtonView = Ember.View.extend({
                label: function(){
                    return this.get('meta.attributes.label');
                }.property('meta.attributes.label'),
                templateName: 'partials/button',
                tagName: 'a',
                classNameBindings: 'meta.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
                click: function(){
                    var behaviour = this.get('meta').behaviour;
                    var params = {
                        behaviour: behaviour
                    };
                    this.send(behaviour.name, params);
                }
            });

            UMI.buttonBehaviour = UMI.GlobalBehaviour.extend({}).create({});
        };
    }
);
define('partials/toolbar/buttons/dropdownButton/main',['App', 'moment'],
    function(UMI, moment){
        

        return function(){
            UMI.DropdownButtonView = Ember.View.extend({
                templateName: 'partials/dropdownButton',
                tagName: 'a',
                classNameBindings: 'meta.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
                didInsertElement: function(){
                    var $el = this.$();
                    $el.on('click.umi.dropdown', function(event){
                        if(!$(event.target).closest('.f-dropdown').length){
                            event.stopPropagation();
                            var $button = $(this);
                            $button.toggleClass('open');
                            setTimeout(function(){
                                if($button.hasClass('open')){
                                    $('body').on('click.umi.dropdown.close', function(bodyEvent){
                                        bodyEvent.stopPropagation();
                                        var $buttonDropdown = $(bodyEvent.target).closest('.dropdown');
                                        if(!$buttonDropdown.length || $buttonDropdown[0].getAttribute('id') !== $button[0].getAttribute('id')){
                                            $('body').off('click.umi.dropdown.close');
                                            $button.toggleClass('open');
                                        }
                                    });
                                }
                            }, 0);
                        }
                    });
                },
                willDestroyElement: function(){
                    var $el = this.$();
                    $el.off('click.umi.dropdown');
                },
                actions: {
                    sendActionForBehaviour: function(behaviour){
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                }
            });

            UMI.dropdownButtonBehaviour = UMI.GlobalBehaviour.extend({
                backupList: {
                    classNames: ['coupled'],
                    classNameBindings: ['isOpen:open'],
                    isOpen: false,
                    iScroll: null,
                    tagName: 'div',
                    templateName: 'partials/dropdownButton/backupList',

                    getBackupList: function(){
                        var backupList;
                        var object = this.get('controller.object');
                        var settings = this.get('controller.settings');
                        var getBackupListAction = UMI.Utils.replacePlaceholder(object, settings.actions.getBackupList.source);

                        var currentVersion = {
                            objectId: object.get('id'),
                            date: object.get('updated'),
                            user: null,
                            id: 'current',
                            current: true,
                            isActive: true
                        };

                        var results = [currentVersion];

                        var promiseArray = DS.PromiseArray.create({
                            promise: $.get(getBackupListAction).then(function(data){
                                return results.concat(data.result.getBackupList.serviceBackup);
                            })
                        });

                        backupList = Ember.ArrayProxy.create({
                            content: promiseArray
                        });
                        return backupList;
                    },

                    backupList: null,

                    actions: {
                        open: function(){
                            var self = this;
                            var el = this.$();
                            this.toggleProperty('isOpen');
                            if(this.get('isOpen')){
                                setTimeout(function(){
                                    $('body').on('click.umi.controlDropUp', function(event){
                                        var targetElement = $(event.target).closest('.umi-dropup');
                                        if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                            $('body').off('click.umi.controlDropUp');
                                            self.set('isOpen', false);
                                        }
                                    });
                                    if(self.get('iScroll')){
                                        self.get('iScroll').refresh();
                                    }
                                }, 0);
                            }
                        },
                        applyBackup: function(backup){
                            if(backup.isActive){
                                return;
                            }
                            var self = this;
                            var object = this.get('controller.object');
                            var list = self.get('backupList');
                            var current = list.findBy('id', backup.id);
                            var setCurrent = function(){
                                list.setEach('isActive', false);
                                Ember.set(current, 'isActive', true);
                            };
                            var backupObjectAction;
                            if(backup.current){
                                object.rollback();
                                setCurrent();
                            } else{
                                backupObjectAction = UMI.Utils.replacePlaceholder(current, Ember.get(self.get('controller.settings'), 'actions.getBackup.source'));
                                $.get(backupObjectAction).then(function(data){
                                    object.rollback();
                                    delete data.result.getBackup.version;
                                    delete data.result.getBackup.id;
                                    // При обновлении свойств не вызываются методы desialize для атрибутов модели
                                    self.get('controller.store').modelFor(object.constructor.typeKey).eachTransformedAttribute(function(name, type){
                                        if(type === 'CustomDateTime' && data.result.getBackup.hasOwnProperty(name) && Ember.typeOf(data.result.getBackup[name]) === 'object'){
                                            Ember.set(data.result.getBackup[name], 'date', moment(data.result.getBackup[name].date).format('DD.MM.YYYY h:mm:ss'));
                                            data.result.getBackup[name] = JSON.stringify(data.result.getBackup[name]);
                                        }
                                    });
                                    object.setProperties(data.result.getBackup);
                                    setCurrent();
                                });
                            }
                        }
                    },
                    didInsertElement: function(){
                        var el = this.$();
                        var scroll;
                        var scrollElement = el.find('.s-scroll-wrap');
                        if(scrollElement.length){
                            scroll = new IScroll(scrollElement[0], UMI.config.iScroll);
                        }
                        this.set('iScroll', scroll);
                        var self = this;
                        self.set('backupList', self.getBackupList());
                        self.get('controller.object').off('didUpdate');
                        self.get('controller.object').on('didUpdate', function(){
                            self.set('backupList', self.getBackupList());
                        });

                        self.get('controller').addObserver('object', function() {
                            self.set('backupList', self.getBackupList());
                        });
                    },
                    willDestroyElement: function(){
                        this.get('controller').removeObserver('content.object');
                    }
                }
            }).create({});
        };
    }
);
define('partials/toolbar/buttons/splitButton/main',['App'],
    function(UMI){
        

        return function(){
            /**
             * Mixin реализующий интерфейс действия по умолчанию для split button.
             */
            UMI.SplitButtonDefaultBehaviour = Ember.Mixin.create({
                pathInLocalStorage: function(){
                    var meta = this.get('meta') || {behaviour : {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name;
                }.property(),

                defaultBehaviourIndex: 0,

                defaultBehaviour: function(){
                    var index = this.get('defaultBehaviourIndex');
                    var meta = this.get('meta') || {behaviour : {choices: []}};
                    if(meta.behaviour.choices[index]){
                        return meta.behaviour.choices[index];
                    } else{
                        this.set('defaultBehaviourIndex', 0);
                    }
                }.property('defaultBehaviourIndex'),

                defaultBehaviourIcon: function(){
                    if(this.get('defaultBehaviour')){
                        return 'icon-' + this.get('defaultBehaviour.behaviour.name');
                    }
                }.property('defaultBehaviour'),

                actions: {
                    toggleDefaultBehaviour: function(index){
                        if(this.get('defaultBehaviourIndex') !== index){
                            this.set('defaultBehaviourIndex', index);
                            UMI.Utils.LS.set(this.get('pathInLocalStorage'), index);
                        }
                    }
                },

                init: function(){
                    this._super();
                    this.set('defaultBehaviourIndex', UMI.Utils.LS.get(this.get('pathInLocalStorage')) || 0);
                }
            });

            UMI.SplitButtonDefaultBehaviourForComponent = Ember.Mixin.create({
                pathInLocalStorage: function(){
                    var componentName = this.get('controller.componentName');
                    var meta = this.get('meta') || {behaviour : {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name + '.' + componentName;
                }.property('controller.componentName')
            });

            var containerSettings = Ember.Object.create({
                defaultBehaviourIndex: 0
            });

            UMI.SplitButtonSharedSettingsBehaviour = Ember.Mixin.create({
                containerSettings: containerSettings,
                defaultBehaviourIndex: Ember.computed.alias('containerSettings.defaultBehaviourIndex')
            });

            UMI.SplitButtonView = Ember.View.extend(UMI.SplitButtonDefaultBehaviour, {
                templateName: 'partials/splitButton',
                tagName: 'span',
                isOpen: false,
                classNameBindings: ['meta.attributes.class', 'isOpen:open'],
                attributeBindings: ['title'],
                label: function(){
                    return this.get('defaultBehaviour.attributes.label');
                }.property('defaultBehaviour.attributes.label'),
                title: Ember.computed.alias('meta.attributes.title'),
                click: function(event){
                    var el = this.$();
                    if(event.target.getAttribute('id') === el[0].getAttribute('id') || ( ($(event.target).hasClass('icon') || $(event.target).hasClass('button-label')) && event.target.parentElement.getAttribute('id') === el[0].getAttribute('id'))){
                        this.send('sendActionForBehaviour', this.get('defaultBehaviour').behaviour);
                    }
                },
                actions: {
                    open: function(event){
                        var self = this;
                        var $el = self.$();
                        setTimeout(function(){
                            self.toggleProperty('isOpen');

                            if(self.get('isOpen')){
                                // закрывает список в случае клика мимо списка
                                $('html').on('click.splitButton', function(event){
                                    if($el.children('.dropdown-toggler')[0] === event.target){
                                        return;
                                    }
                                    var targetElement = $(event.target).closest('.f-dropdown');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== $el[0].getAttribute('id')){
                                        $('html').off('click.splitButton');
                                        self.set('isOpen', false);
                                    }
                                });
                            }
                        }, 0);
                    },

                    /**
                     * @method sendActionForBehaviour
                     * @param behaviour
                     */
                    sendActionForBehaviour: function(behaviour){
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                },

                itemView: Ember.View.extend({
                    tagName: 'li',
                    label: function(){
                        return this.get('context.attributes.label');
                    }.property('context.attributes.label'),
                    isDefaultBehaviour: function(){
                        var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                        return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                    }.property('parentView.defaultBehaviourIndex')
                })
            });

            UMI.splitButtonBehaviour = UMI.GlobalBehaviour.extend({
                dropUp: {
                    classNames: ['split-dropup']
                },

                contextMenu: {
                    itemView: function(){
                        var baseItem = Ember.View.extend({
                            tagName: 'li',
                            label: function(){
                                return this.get('context.attributes.label');
                            }.property('context.attributes.label'),
                            isDefaultBehaviour: function(){
                                var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                                return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                            }.property('parentView.defaultBehaviourIndex'),
                            init: function(){
                                this._super();
                                var context = this.get('context');
                                if(Ember.get(context, 'behaviour.name') === 'switchActivity'){
                                    this.reopen({
                                        label: function(){
                                            if(this.get('controller.object.active')){
                                                return this.get('context.attributes.states.deactivate.label');
                                            } else{
                                                return this.get('context.attributes.states.activate.label');
                                            }
                                        }.property('context.attributes.label', 'controller.object.active')
                                    });
                                }
                            }
                        });
                        return baseItem;
                    }.property()
                }
            }).create({});
        };
    }
);
define(
    'toolbar/buttons/main',[
        'partials/toolbar/buttons/globalBehaviour',
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropdownButton/main',
        'partials/toolbar/buttons/splitButton/main'
    ],
    function(globalBehaviour, button, dropdownButton, splitButton){
        

        return function(){
            globalBehaviour();
            button();
            dropdownButton();
            splitButton();
        };
    }
);
define('toolbar/main',['App', './view', './buttons/main'], function(UMI, view, buttons){
    

    buttons();
    view();
});
define('toolbar', ['toolbar/main'], function (main) { return main; });

define('topBar/main',[
    'App', 'toolbar'
], function(UMI){
    

    UMI.TopBarView = Ember.View.extend({
        templateName: 'partials/topBar',
        dropdownView: UMI.DropdownButtonView.extend({
            template: function(){
                return Ember.Handlebars.compile('mail@yandex.ru<ul class="f-dropdown right"><li><a href="javascript:void(0)" {{action "logout"}}>{{i18n "Logout"}}</a></li></ul>');
            }.property()
        })
    });
});
define('topBar', ['topBar/main'], function (main) { return main; });

define('divider/view',['App'], function(UMI){
    
    return function(){

        UMI.DividerView = Ember.View.extend({
            classNames: ['off-canvas-wrap', 'umi-divider-wrapper', 's-full-height'],

            didInsertElement: function(){
                this.fakeDidInsertElement();
            },

            willDestroyElement:  function(){
                this.removeObserver('model');
            },

            modelChange: function(){
                this.fakeDidInsertElement();
            }.observes('model'),

            fakeDidInsertElement: function(){
                var $el = this.$();
                $el.off('mousedown.umi.divider.toggle');
                $('body').off('mousedown.umi.divider.proccess');

                var $sidebar = $el.find('.umi-divider-left');
                var $content = $el.find('.umi-divider-right');

                if($sidebar.length){
                    $el.on('mousedown.umi.divider.toggle', '.umi-divider-left-toggle', function(){
                        $sidebar.toggleClass('hide');
                        $(this).children('.icon').toggleClass('icon-left').toggleClass('icon-right');
                    });

                    $('body').on('mousedown.umi.divider.proccess', '.umi-divider', function(event){
                        if(event.button === 0){
                            $sidebar.removeClass('divider-virgin');
                            $('html').addClass('s-unselectable');

                            $('html').on('mousemove.umi.divider.proccess', function(event){
                                var sidebarWidth = event.pageX;
                                sidebarWidth = sidebarWidth < 150 ? 150 : sidebarWidth;
                                sidebarWidth = sidebarWidth > 500 ? 500 : sidebarWidth;

                                if(sidebarWidth > $(window).width() - 720){
                                    $('.magellan-content').find('.umi-columns').removeClass('large-4').addClass('large-12');
                                }else{
                                    $('.magellan-content').find('.umi-columns').removeClass('large-12').addClass('large-4');
                                }

                                $content.css({marginLeft: sidebarWidth + 1});
                                $sidebar.css({width: sidebarWidth});

                                $('html').on('mouseup.umi.divider.proccess', function(){
                                    $('html').off('mousemove.umi.divider.proccess');
                                    $('html').removeClass('s-unselectable');
                                });
                            });
                        }
                    });
                } else{
                    $content.css({'marginLeft': ''});
                }
            }
        });
    };
});

define('divider/main',[
    'App',
    './view'
], function(UMI, view){
    

    view();
});
define('divider', ['divider/main'], function (main) { return main; });

define('dock/controllers',['App'], function(UMI){
    

    return function(){
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],
            modulesBinding: 'controllers.application.modules',
            activeModuleBinding: 'controllers.module.model'
        });
    };
});
define('dock/view',['App'], function(UMI){
    

    return function(){

        var expanded = false;
        var move = {};
        var def = {old: 0, cur: 0, def: 0, coeff: 1 };
        var intervalLeaveItem;

        UMI.DockView = Ember.View.extend({
            templateName: 'partials/dock',
            classNames: ['umi-dock', 's-unselectable'],
            didInsertElement: function(){
                var self = this;
                var dock = self.$().find('.dock')[0];
                dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                $(dock).addClass('active');
                if(!dock.style.marginLeft){
                    dock.style.marginLeft = 0;
                }
                var futureOffset;

                var moving = function(el, event){
                    move.proccess = true;
                    var isDropdown = $(event.target).closest('.dropdown-menu').size();
                    var elOffsetLeft = el.offsetLeft;
                    var elWidth = el.offsetWidth;
                    var dockParentWidth = el.parentNode.offsetWidth;
                    def.cur = event.clientX;
                    if(def.old){
                        def.def = def.old - def.cur;
                    }
                    if(Math.abs(elOffsetLeft) + elWidth > dockParentWidth && !isDropdown){
                        if(def.def > 0){
                            // move left
                            def.coeff = Math.abs(elOffsetLeft) / (event.clientX);
                            futureOffset = Math.round(parseInt(el.style.marginLeft, 10) + def.def * def.coeff);
                            if(def.coeff > 0 && futureOffset + parseInt(el.style.left, 10) < -20){
                                el.style.marginLeft = futureOffset + 'px';
                            }
                        } else if(def.def < 0){
                            // move right
                            def.coeff = Math.abs((elWidth - dockParentWidth + elOffsetLeft) / (dockParentWidth - event.clientX));
                            futureOffset = Math.round(parseInt(el.style.marginLeft, 10) + def.def * def.coeff);

                            if(def.coeff > 0 && dockParentWidth < elWidth - 20 + ( futureOffset + (parseInt(el.style.left, 10) ))){
                                el.style.marginLeft = futureOffset + 'px';
                            }
                        }
                    }
                    def.old = event.clientX;
                };
                $(dock).mousemove(function(event){
                    if(!move.oldtime){
                        move.oldtime = new Date();
                    }
                    move.curtime = new Date();
                    if(move.curtime - move.oldtime > 700 || move.proccess){
                        moving(this, event);
                    }
                });

                $(window).on('resize.umi.dock', function(){
                    setTimeout(function(){
                        dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                    }, 0);
                });

                $(dock).find('.f-dropdown').on('click.umi.dock.component', 'a', function(){
                    self.set('closeDropdown', true);
                    $(dock).find('.dropdown').removeClass('open');
                    self.leaveDock();
                    setTimeout(function(){
                        self.set('closeDropdown', false);
                    }, 300);
                });
            },
            mouseLeave: function(event){
                var self = this;
                var dock = self.$().find('.dock')[0];
                def.old = false;

                if(!event.relatedTarget){
                    $(document.body).bind('mouseover', function(e){
                        if($(dock).hasClass('full') && !($(e.target).closest('.dock')).size()){
                            self.leaveDock();
                        }
                        $(this).unbind('mouseover');
                    });
                    return;
                }
                this.leaveDock();
            },

            leaveDock: function(){
                var self = this;
                var dock = self.$().find('.dock')[0];

                expanded = false;
                move.oldtime = false;
                move.proccess = false;
                $(dock).find('img').stop().animate({margin: '9px 11px 9px', height: 30}, {
                    duration: 130,
                    easing: 'linear'
                });
                $(dock).animate({marginLeft: '0px'}, {duration: 130, easing: 'linear', complete: function(){
                    $(dock).removeClass('full');
                }});
            },

            willDestroyElement: function(){
                $(window).off('resize.umi.dock');
            }
        });

        var dropDownTimeout;
        UMI.DockModuleButtonView = Ember.View.extend({
            tagName: 'li',
            classNames: ['umi-dock-button', 'dropdown'],
            classNameBindings: ['open'],
            open: false,
            icon: function(){
                return '/resources/modules/' + this.get('module.name') + '/icon.svg';
            }.property('module.name'),
            mouseEnter: function(){
                var self = this;
                var dock = this.$().closest('.dock');
                var $el = this.$();

                var onHover = function(){
                    self.set('open', true);
                    if(!expanded){
                        expanded = true;
                        move.proccess = false;
                        var posBegin = $el.position().left + $el[0].offsetWidth / 2 + (parseInt(dock[0].style.marginLeft, 10) || 0);

                        $($el[0].parentNode).find('img').stop().animate({height: 48, margin: '8px 36px 28px'}, {
                            duration: 280,
                            step: function(n, o){
                                if(this.parentNode.parentNode === $el[0]){
                                    dock[0].style.marginLeft = posBegin - (o.elem.parentNode.parentNode.offsetLeft + o.elem.parentNode.offsetWidth / 2) + 'px';
                                }
                            },
                            complete: function(){
                                dock.addClass('full');
                                move.proccess = true;
                            }
                        });
                    }
                };

                !intervalLeaveItem||clearTimeout(intervalLeaveItem);
                intervalLeaveItem = setTimeout(function() {
                    onHover();
                }, 120);
            },
            mouseLeave: function(){
                if(intervalLeaveItem){
                    clearTimeout(intervalLeaveItem);
                }
                if(dropDownTimeout){
                    clearInterval(dropDownTimeout);
                }
                this.set('open', false);
            }
        });
    };
});
define('dock/main',['./controllers', './view', 'App'], function(controller, view){
    
    controller();
    view();
});
define('dock', ['dock/main'], function (main) { return main; });

define('tableControl/controllers',['App'], function(UMI){
    

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend(UMI.i18nInterface,{
            componentNameBinding: 'controllers.component.name',
            collectionName: function(){
                var dataSource = this.get('controllers.component.dataSource.name');
                if(!dataSource){
                    dataSource = this.get('controllers.component.selectedContext');
                }
                return dataSource;
            }.property('controllers.component.dataSource.name', 'controllers.component.selectedContext'),
            dictionaryNamespace: 'tableControl',
            localDictionary: function(){
                var filter = this.get('control') || {};
                return filter.i18n;
            }.property(),
            /**
             * Данные
             * @property objects
             */
            objects: null,
            fieldsList: null,
            objectChange: function(){
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted'),

            updateObjectDeleted: function(){//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item){
                    if(item && item.get('isDeleted')){
                        objects.get('content.content.content').removeObject(item);
                    }
                });
            },

            /**
             * метод получает данные учитывая query параметры
             * @method getObjects
             */
            getObjects: function(){
                var self = this;
                var query = this.get('query') || {};
                var collectionName = self.get('collectionName');
                var objects = self.store.find(collectionName, query);
                var orderByProperty = this.get('orderByProperty');
                var sortProperties = orderByProperty && orderByProperty.property ? orderByProperty.property : 'id';
                var sortAscending = orderByProperty && 'direction' in orderByProperty ? orderByProperty.direction : true;
                var data = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: objects,
                    sortProperties: [sortProperties],
                    sortAscending: sortAscending
                });
                this.set('objects', data);
            },
            /**
             * Количество объектов на странице
             * @property limit
             */
            limit: 25,
            /**
             * Индекс первого объекта на странице
             * @property offset
             */
            offset: 0,
            /**
             * Количество объектов во всей коллекции
             * @property total
             */
            total: 0,
            /**
             * Свойство по которому необходимо выполнить фильтрацию
             * @property orderByProperty
             * @example {'property' : propertyName, 'direction': sortAscending}
             */
            orderByProperty: null,

            /**
             * Вычисляемое свойство возвращающее параметры сортировки
             * @property order
             */
            order: function(){
                var orderByProperty = this.get('orderByProperty');
                if(orderByProperty){
                    var order = {};
                    order[orderByProperty.property] = orderByProperty.direction ? 'ASC' : 'DESC';
                    return order;
                }
            }.property('orderByProperty'),
            /**
             * Список отображаемых полей принадлежащих объекту
             */
            nativeFieldsList: null,
            /**
             * Вычисляемое свойство списка полей принадлежащих объекту
             * @property fields
             */
            nativeFields: function(){
                var nativeFieldsList = this.get('nativeFieldsList');
                if(Ember.typeOf(nativeFieldsList) === 'array' && nativeFieldsList.length){
                    nativeFieldsList = nativeFieldsList.join(',');
                    return nativeFieldsList;
                }
            }.property('nativeFieldsList'),
            /**
             * Список полей имеющих связь belongsTo
             */
            relatedFieldsList: null,
            /**
             * Вычисляемое свойство возвращающее поля belongsTo
             * @property fields
             */
            relatedFields: function(){
                var relatedFields = this.get('relatedFieldsList');
                if(Ember.typeOf(relatedFields) === 'object' && JSON.stringify(relatedFields) !== '{}'){
                    return relatedFields;
                }
            }.property('relatedFieldsList'),

            /**
             * Свойства фильтрации коллекции
             * @collectionFilterParams
             */
            collectionFilterParams: null,
            /**
             * Свойства фильтрации
             * @property filters
             */
            filterParams: null,
            /**
             * Вычисляемое свойство фильтрации
             * @property filters
             */
            filters: function(){
                var filters = {};
                var filter;
                var filterParams = this.get('filterParams') || {};
                var collectionFilterParams = this.get('collectionFilterParams') || {};
                for(filter in collectionFilterParams){
                    if(collectionFilterParams.hasOwnProperty(filter)){
                        if(Ember.typeOf(collectionFilterParams[filter]) === 'string' && !collectionFilterParams[filter].length){
                            delete filters[filter];
                        } else{
                            filters[filter] = collectionFilterParams[filter];
                        }
                    }
                }
                for(filter in filterParams){
                    if(filterParams.hasOwnProperty(filter)){
                        if(Ember.typeOf(filterParams[filter]) === 'string' && !filterParams[filter].length){
                            delete filters[filter];
                        } else{
                            filters[filter] = filterParams[filter];
                        }
                    }
                }
                return filters;
            }.property('filterParams.@each', 'collectionFilterParams.@each'),

            setFilters: function(property, filter){
                this.propertyWillChange('filterParams');
                this.set('filterParams', null);
                var filterParams = {};
                filterParams[property] = filter;
                this.set('filterParams', filterParams);
                this.propertyDidChange('filterParams');
            },

            /**
             * Вычисляемое свойство параметров запроса коллекции
             * @property query
             */
            query: function(){
                var query = {};
                var nativeFields = this.get('nativeFields');
                var relatedFields = this.get('relatedFields');
                var limit = this.get('limit');
                var filters = this.get('filters');
                var offset = this.get('offset');
                var order = this.get('order');
                if(nativeFields){
                    query.fields = nativeFields;
                }
                if(relatedFields){
                    query['with'] = relatedFields;
                }
                if(limit){
                    query.limit = limit;
                }
                if(filters){
                    query.filters = filters;
                }
                if(offset){
                    query.offset = offset * limit;
                }
                if(order){
                    query.orderBy = order;
                }
                return query;
            }.property('limit', 'filters', 'offset', 'order', 'nativeFields', 'relatedFields'),

            /**
             * Метод вызывается при смене контекста (компонента).
             * Сбрасывает значения фильтров,вызывает метод getObjects, вычисляет total
             * @method contextChanged
             */
            contextChanged: function(){
                var store = this.get('store');
                // Вычисляем фильтр в зависимости от типа коллекции
                var collectionName = this.get('collectionName');
                var metaForCollection = store.metadataFor(collectionName);

                //TODO: check user configurations
                var modelForCollection = store.modelFor(collectionName);
                var fieldsList = this.get('control.meta.form.elements') || [];
                var defaultFields = this.get('control.meta.defaultFields') || [];

                var i;
                for(i = 0; i < fieldsList.length; i++){
                    if(!defaultFields.contains(fieldsList[i].dataSource)){
                        fieldsList.splice(i, 1);
                        --i;
                    }
                }

                var nativeFieldsList = [];
                var relatedFieldsList = {};

                var filterParams = this.get('control.params.filter') || {};

                modelForCollection.eachAttribute(function(name){
                    var selfProperty = fieldsList.findBy('dataSource', name);
                    if(selfProperty){
                        nativeFieldsList.push(selfProperty.dataSource);
                    } else if(name === 'active'){
                        nativeFieldsList.push('active');
                    } else if(name === 'trashed' && !Ember.get(filterParams, 'trashed')){
                        filterParams.trashed = 'equals(0)';
                    }
                });

                modelForCollection.eachRelationship(function(name, relatedModel){
                    var i;
                    var relatedModelDataSource;
                    if(relatedModel.kind === 'belongsTo'){
                        for(i = 0; i < fieldsList.length; i++){
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if(relatedModelDataSource === name){
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                            } else if(relatedModelDataSource.indexOf(name + '.', 0) === 0){
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                                relatedFieldsList[name].push(relatedModelDataSource.slice(name.length + 1));
                            }
                        }
                    } else if(relatedModel.kind === 'hasMany' || relatedModel.kind === 'manyToMany'){
                        for(i = 0; i < fieldsList.length; i++){
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if(relatedModelDataSource === name || relatedModelDataSource.indexOf(name + '.', 0) === 0){
                                fieldsList.splice(i, 1);
                                --i;
                            }
                        }
                        //Ember.assert('Поля с типом hasMany и manyToMany недопустимы в фильтре.'); TODO: uncomment
                    }

                    if(relatedFieldsList[name]){
                        relatedFieldsList[name] = relatedFieldsList[name].join(',');
                    }
                });

                // Сбрасываем параметры запроса, не вызывая обсервер query
                this.set('withoutChangeQuery', true);
                this.setProperties({nativeFieldsList: nativeFieldsList, relatedFieldsList: relatedFieldsList, offset: 0, orderByProperty: null, total: 0, collectionFilterParams: filterParams});
                this.set('withoutChangeQuery', false);

                this.getObjects();
                Ember.run.next(this, function(){
                    var self = this;
                    this.get('objects.content').then(function(){
                        self.set('total', metaForCollection.total);
                        self.set('fieldsList', fieldsList);
                    });
                });
            }.observes('content.object.id').on('init'),

            /**
             * Метод вызывается при изменении параметров запроса.
             * @method queryChanged
             */
            queryChanged: function(){
                if(this.get('withoutChangeQuery')){
                    return;
                }
                Ember.run.once(this, 'getObjects');
            }.observes('query'),

            /**
             * Возвращает список кнопок контекстного меню
             * @property contextToolbar
             * return Array
             */
            contextToolbar: function(){
                var filter = this.get('control') || {};
                return filter.contextToolbar;
            }.property('model'),

            /**
             * Возвращает toolbar
             * @property toolbar
             * return Array
             */
            toolbar: function(){
                var filter = this.get('control') || {};
                return filter.toolbar || [];
            }.property('model'),

            actions: {
                orderByProperty: function(propertyName, sortAscending){
                    this.set('orderByProperty', {'property' : propertyName, 'direction': sortAscending});
                }
            },

            needs: ['component'],

            itemController: 'tableControlContextToolbarItem'
        });

        UMI.TableControlContextToolbarItemController = Ember.ObjectController.extend({
            objectBinding: 'content',
            componentNameBinding: 'parentController.componentName'
        });

    };
});
define('tableControl/view',['App', 'toolbar'], function(UMI){
    
    return function(){

        UMI.TableControlView = Ember.View.extend({
            componentNameBinding: 'controller.controllers.component.name',
            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/tableControl',
            /**
             * Классы для view
             * @classNames
             */
            classNames: ['umi-table-control'],

            objectsEditable: function(){
                var objectsEditable = this.get('controller.control.params.objectsEditable');
                objectsEditable = objectsEditable === false ? false : true;
                return objectsEditable;
            }.property('controller.control.params.objectsEditable'),
            /**
             * @property iScroll
             */
            iScroll: null,
            /**
             * При изменении данных вызывает ресайз скрола.
             * @method scrollUpdate
             * @observer
             */
            scrollUpdate: function(){
                var self = this;
                var tableControl = this.$();
                var objects = this.get('controller.objects.content');
                var iScroll = this.get('iScroll');

                var scrollUpdate = function(){
                    Ember.run.scheduleOnce('afterRender', self, function(){
                        // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                        //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                        var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                        var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];
                        //umiTableLeft.style.marginTop = 0;
                        umiTableRight.style.marginTop = 0;
                        umiTableHeader.style.marginLeft = 0;
                        setTimeout(function(){
                            iScroll.refresh();
                            iScroll.scrollTo(0, 0);
                        }, 0);
                    });
                };

                if(objects && iScroll){
                    objects.then(function(){
                        scrollUpdate();
                    });
                }
            }.observes('controller.objects').on('didInsertElement'),
            /**
             * Событие вызываемое после вставки шаблона в DOM
             * @method didInsertElement
             */
            didInsertElement: function(){
                var tableControl = this.$();

                var self = this;
                var objects = this.get('controller.objects.content');

                // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];

                var umiTableContentRowSize = tableControl.find('.umi-table-control-content-row-size')[0];

                if(objects){
                    var tableContent = tableControl.find('.s-scroll-wrap');

                    objects.then(function(objects){
                        if(!objects.content.length){
                            return;
                        }

                        Ember.run.scheduleOnce('afterRender', self, function(){
                            var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll);
                            self.set('iScroll', scrollContent);

                            scrollContent.on('scroll', function(){
                                //umiTableLeft.style.marginTop = this.y + 'px';
                                umiTableRight.style.marginTop = this.y + 'px';
                                umiTableHeader.style.marginLeft = this.x + 'px';
                            });

                            // После ресайза страницы необходимо изменить отступы у элементов  umiTableLeft, umiTableRight, umiTableHeader
                            $(window).on('resize.umi.tableControl', function(){
                                setTimeout(function(){
                                    //umiTableLeft.style.marginTop = scrollContent.y + 'px';
                                    umiTableRight.style.marginTop = scrollContent.y + 'px';
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                }, 100);// TODO: заменить на событие окончания ресайза iScroll
                            });

                            // Событие изменения ширины колонки
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-control-column-resizer', function(){
                                $('html').addClass('s-unselectable');
                                var handler = this;
                                $(handler).addClass('on-resize');
                                var columnEl = handler.parentNode.parentNode;
                                var columnName = columnEl.className;
                                columnName = columnName.substr(columnName.indexOf('column-id-'));
                                var columnOffset = $(columnEl).offset().left;
                                var columnWidth;
                                var contentCell = umiTableContentRowSize.querySelector('.' + columnName);

                                $('body').on('mousemove.umi.tableControl', function(event){
                                    event.stopPropagation();
                                    columnWidth = event.pageX - columnOffset;
                                    if(columnWidth >= 60 && columnEl.offsetWidth > 59){
                                        columnEl.style.width = contentCell.style.width = columnWidth + 'px';
                                    }
                                });

                                $('body').on('mouseup.umi.tableControl', function(){
                                    $('html').removeClass('s-unselectable');
                                    $(handler).removeClass('on-resize');
                                    $('body').off('mousemove');
                                    $('body').off('mouseup.umi.tableControl');
                                    scrollContent.refresh();
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                });
                            });

                            // Hover event
                            var getHoverElements = function(el){
                                var isContentRow = $(el).hasClass('umi-table-control-content-row');
                                var rows = el.parentNode.querySelectorAll(isContentRow ? '.umi-table-control-content-row' : '.umi-table-control-column-fixed-cell');

                                for(var i = 0; i < rows.length; i++){
                                    if(rows[i] === el){
                                        break;
                                    }
                                }
                                //var leftElements = umiTableLeft.querySelectorAll('.umi-table-control-column-fixed-cell');
                                var rightElements = umiTableRight.querySelectorAll('.umi-table-control-column-fixed-cell');
                                if(!isContentRow){
                                    el = tableContent[0].querySelectorAll('.umi-table-control-content-row')[i];
                                }
                                return [el, rightElements[i]];//[el, leftElements[i], rightElements[i]];
                            };

                            tableControl.on('mouseenter.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).addClass('hover');
                            });

                            tableControl.on('mouseleave.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).removeClass('hover');
                            });
                            // Drag and Drop
                        });
                    });
                }
            },
            /**
             * Событие вызываемое после удаления шаблона из DOM
             * @method willDestroyElement
             */
            willDestroyElement: function(){
                $(window).off('.umi.tableControl');
                // Удаляем Observes для контоллера
                this.get('controller').removeObserver('content.object.id');
                this.get('controller').removeObserver('query');
                this.get('controller').removeObserver('objects.@each.isDeleted');
            },

            paginationView: Ember.View.extend({
                classNames: ['right', 'umi-table-control-pagination'],

                counter: function(){
                    var label = 'из';
                    var limit = this.get('controller.limit');
                    var offset = this.get('controller.offset') + 1;
                    var total = this.get('controller.total');
                    var maxCount = offset*limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('controller.limit', 'controller.offset', 'controller.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        return this.get('controller.offset');
                    }.property('controller.offset'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('controller').decrementProperty('offset');
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        var limit = this.get('controller.limit');
                        var offset = this.get('controller.offset') + 1;
                        var total = this.get('controller.total');
                        return total > limit * offset;
                    }.property('controller.limit', 'controller.offset', 'controller.total'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('controller').incrementProperty('offset');
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function(){
                        return this.get('controller.limit');
                    }.property('controller.limit'),

                    type: 'text',

                    keyDown: function(event){
                        if(event.keyCode === 13){
                            // При изменении количества строк на странице сбрасывается offset
                            this.get('controller').setProperties({'offset': 0, 'limit': this.$()[0].value});
                        }
                    }
                })
            }),

            sortHandlerView: Ember.View.extend({
                classNames: ['button', 'flat', 'tiny', 'square', 'sort-handler'],
                classNameBindings: ['isActive:active'],
                sortAscending: true,

                isActive: function(){
                    var orderByProperty = this.get('controller.orderByProperty');
                    if(orderByProperty){
                        return this.get('propertyName') === orderByProperty.property;
                    }
                }.property('controller.orderByProperty'),

                click: function(){
                    var propertyName = this.get('propertyName');

                    $('.umi-table-control-header-cell .icon-top-thin:not(.active)').removeClass('icon-top-thin').addClass('icon-bottom-thin');

                    if(this.get('isActive')){
                        this.toggleProperty('sortAscending');
                    }

                    var sortAscending = this.get('sortAscending');
                    this.get('controller').send('orderByProperty', propertyName, sortAscending);
                }
            }),

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-control-content-row'],
                classNameBindings: ['object.type', 'isActive::umi-inactive', 'objectsEditable:s-pointer'],
                isActive: function(){
                    var object = this.get('object');
                    var hasActiveProperty  = false;
                    object.eachAttribute(function(name){
                        if(name === 'active'){
                            hasActiveProperty = true;
                        }
                    });
                    if(hasActiveProperty){
                        return object.get('active');
                    } else{
                        return true;
                    }
                }.property('object.active'),

                attributeBindings: ['objectId'],

                objectIdBinding: 'object.id',

                objectsEditable: function(){
                    return this.get('parentView.objectsEditable');
                }.property(),

                click: function(){
                    var objectsEditable = this.get('objectsEditable');
                    if(this.get('object.meta.editLink') && objectsEditable){
                        this.get('controller').transitionToRoute(this.get('object.meta.editLink').replace('/admin', ''));//TODO: fix replace
                    }
                }
            }),

            filterRowView: Ember.View.extend({
                filterType: null,
                template: function(){
                    var column = this.get('column');
                    var template = '';
                    switch(Ember.get(column, 'attributes.type')){
                        case 'text':
                            this.set('filterType', 'text');
                            template = '<input type="text" class="table-control-filter-input"/>';
                            break;
                    }
                    return Ember.Handlebars.compile(template);
                }.property('column'),
                didInsertElement: function(){
                    var self = this;
                    var $el = this.$();
                    var $input = $el.children('input');
                    var filterType = this.get('filterType');
                    $input.on('focus', function(){
                        $(this).closest('.umi-table-control-row').find('.table-control-filter-input').val('');
                    });
                    switch(filterType){
                        case 'text':
                            $input.on('keypress.umi.tableControl.filters', function(event){
                               if(event.keyCode === 13){
                                   self.setFilter('like(%' + this.value + '%)');
                               }
                           });
                            break;
                    }
                },
                setFilter: function(filter){
                    this.get('controller').setFilters(this.get('column.dataSource'), filter);
                }
            })
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-content-cell-div'],
            classNameBindings: ['columnId'],
            promise: null,
            template: function(){
                var column;
                var object;
                var template = '';
                var value;
                var self = this;
                var properties;
                function propertyHtmlEncode(value){
                    if(Ember.typeOf(value) === 'null'){
                        value = '';
                    } else{
                        value = UMI.Utils.htmlEncode(value);
                    }
                    return value;
                }
                try{
                    object = this.get('object');
                    column = this.get('column');
                    switch(column.type){
                        case 'checkbox':
                            template = '<span {{bind-attr class="view.object.' + column.dataSource + ':umi-checkbox-state-checked:umi-checkbox-state-unchecked"}}></span>&nbsp;';
                            break;
                        case 'checkboxGroup':
                        case 'multiSelect':
                            value = object.get(column.dataSource);
                            if(Ember.typeOf(value) === 'array'){
                                template = value.join(', ');
                            }
                            break;
                        case 'datetime':
                            value = object.get(column.dataSource);
                            if(value){
                                try{
                                    value = JSON.parse(value);
                                    template = Ember.get(value, 'date');
                                } catch(error){
                                    this.get('controller').send('backgroundError', error);
                                }
                            }
                            break;
                        default:
                            properties = column.dataSource.split('.');
                            if(this.checkRelation(properties[0])){
                                if(properties.length > 1){
                                    value = object.get(properties[0]);
                                    if(Ember.typeOf(value) === 'instance'){
                                        value.then(function(object){
                                            value = object.get(properties[1]);
                                            value = propertyHtmlEncode(value);
                                            self.set('promiseProperty', value);
                                        });
                                        template = '{{view.promiseProperty}}';
                                    }
                                } else{
                                    template = '{{view.object.' + column.dataSource + '.displayName}}';
                                }
                            } else{
                                value = object.get(column.dataSource);
                                value = propertyHtmlEncode(value);
                                template = value;
                            }
                            break;
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);
                } finally{
                    return Ember.Handlebars.compile(template);
                }
            }.property('column'),

            checkRelation: function(property){
                var object = this.get('object');
                var isRelation = false;
                object.eachRelationship(function(name, relatedModel){
                    if(property === name){
                        isRelation = true;
                    }
                });
                return isRelation;
            }
        });


        UMI.TableControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'table-context-toolbar'],
            elementView: UMI.ToolbarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent, UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour;
                    var i;
                    var action;
                    if(behaviourName){
                        behaviour = UMI.splitButtonBehaviour.get(behaviourName) || {};
                    } else{
                        behaviour = {};
                    }
                    var choices = this.get('context.behaviour.choices');
                    if(behaviourName === 'contextMenu' && Ember.typeOf(choices) === 'array'){
                        for(i = 0; i < choices.length; i++){
                            var prefix = '';
                            action = '';
                            var behaviourAction = UMI.splitButtonBehaviour.get(choices[i].behaviour.name);
                            if(behaviourAction){
                                if(behaviourAction.hasOwnProperty('_actions')){
                                    prefix = '_';
                                }
                                action = behaviourAction[prefix + 'actions'][choices[i].behaviour.name];
                            }
                            if(action){
                                if(Ember.typeOf(behaviour.actions) !== 'object'){
                                    behaviour.actions = {};
                                }
                                behaviour.actions[choices[i].behaviour.name] = action;
                            }
                        }
                    }
                    behaviour.classNames = ['white square'];
                    behaviour.label = null;
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});
define('tableControl/main',[
    './controllers',
    './view',
    'App'
], function(controllers, view){
    

    controllers();
    view();
});
define('tableControl', ['tableControl/main'], function (main) { return main; });

define('fileManager/main',['App'], function(UMI){
    

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager s-full-height'],

        layout: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        didInsertElement: function(){
            var self = this;
            $('#elfinder').elfinder({
                url : '/admin/rest/files/manager/action/connector',//self.get('controller.connector.source'),
                lang: 'ru',
                getFileCallback : function(fileInfo){
                    var contentParams = {};
                    contentParams.fileInfo = fileInfo;
                    self.set('parentView.contentParams', contentParams);
                    $('.umi-popup').remove(); //TODO отправлять событие на закрытие Popup
                },

                uiOptions: {
                    toolbar : [
                        ['back', 'forward'], ['reload'], ['getfile'], ['mkdir', 'mkfile', 'upload'], ['download'], ['copy', 'cut', 'paste'], ['rm'], ['duplicate', 'rename', 'edit'], ['view'], ['help']
                    ]
                }
            }).elfinder('instance');

            $('.elfinder-navbar').on('mousedown.umi.fileManager', '.elfinder-navbar-div', function(){
                $('.elfinder-navbar').children().removeClass('ui-state-active');
                $(this).addClass('ui-state-active');
            });
        },

        willDestroyElement: function(){
            $(window).off('.umi.fileManager');
        }
    });
});
define('fileManager', ['fileManager/main'], function (main) { return main; });

define('treeSimple/main',[
    'App'
], function(UMI){
    

    UMI.TreeSimpleView = Ember.View.extend({
        classNames: ['row', 's-full-height'],
        templateName: 'partials/treeSimple/list'
    });

    UMI.TreeSimpleItemView = Ember.View.extend({
        tagName: 'li',
        templateName: 'partials/treeSimple/item',
        isExpanded: true,
        checkExpanded: function(){
            var params = this.get('controller.target.router.state.params');
            if(params && 'settings.component' in params && params['settings.component'].component === this.get('context.name')){
                this.set('isExpanded', true);
            }
        },
        nestedSlug: function(){
            var computedSlug = '';
            if(this.get('parentView').constructor.toString() === '.TreeSimpleItemView'){
                computedSlug = this.get('parentView').get('context.name') + '.';
            }
            computedSlug += this.get('context.name');
            return computedSlug;
        }.property(),
        actions: {
            expanded: function(){
                this.toggleProperty('isExpanded');
            }
        }
    });
});
define('treeSimple', ['treeSimple/main'], function (main) { return main; });

define('tree/controllers',['App'], function(UMI){
    
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
            needs: ['component', 'context'],

            filterTrashed: null,

            objectProperties: function(){
                var objectProperties = ['displayName', 'order', 'active', 'childCount', 'children', 'parent'] ;
                var collectionName = this.get('collectionName');
                var model = this.get('store').modelFor(collectionName);
                var modelFields = Ember.get(model, 'fields');
                modelFields = modelFields.keys.list;
                for(var i = 0; i < objectProperties.length; i++){
                    if(!modelFields.contains(objectProperties[i])){
                        objectProperties.splice(i, 1);
                        --i;
                    }
                }
                if(modelFields.contains('trashed')){
                    this.set('filterTrashed', true);
                } else{
                    this.set('filterTrashed', false);
                }
                return objectProperties;
            }.property('model'),

            expandedBranches: [],

            collectionNameBinding: 'controllers.component.dataSource.name',

            clearExpanded: function(){
                this.set('expandedBranches', []);
            }.observes('collectionName'),

            setExpandedBranches: function(){
                var expandedBranches = this.get('expandedBranches');
                var activeContext = this.get('activeContext');
                if(activeContext && this.get('controllers.component.sideBarControl.name') === 'tree'){
                    var mpath = [];
                    if(activeContext.get('id') !== 'root' && activeContext.get('mpath')){
                        mpath = activeContext.get('mpath').without(parseFloat(activeContext.get('id'))) || [];
                    }
                    mpath.push('root');
                    this.set('expandedBranches', expandedBranches.concat(mpath).uniq());
                }
            },

            activeContextChange: function(){
                Ember.run.next(this, 'setExpandedBranches');
            }.observes('activeContext').on('init'),

            /**
             Возвращает корневой элемент
             @property root
             @type Object
             @return
             */
            root: function(){
                var collectionName = this.get('collectionName');
                var sideBarControl = this.get('controllers.component.sideBarControl');
                if(!sideBarControl){
                    return;
                }
                var self = this;
                var Root = Ember.Object.extend({
                    displayName: Ember.typeOf(sideBarControl.params) === 'object' ? sideBarControl.params.rootNodeName : '',
                    root: true,
                    hasChildren: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    typeKey: collectionName,
                    childCount: function(){
                        return this.get('children.length');
                    }.property('children.length'),
                    children: function(){
                        var children;
                        var objectProperties;
                        try{
                            if(!collectionName){
                                throw new Error('Collection name is not defined.');
                            }
                            objectProperties = self.get('objectProperties').join(',');
                            var requestParams = {'filters[parent]': 'null()', 'fields': objectProperties};
                            if(self.get('filterTrashed')){
                                requestParams['filters[trashed]'] = 'equals(0)';
                            }
                            var nodes = self.store.updateCollection(collectionName, requestParams);
                            children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                                content: nodes,
                                sortProperties: ['order', 'id'],
                                sortAscending: true
                            });
                        } catch(error){
                            var errorObject = {
                                'statusText': error.name,
                                'message': error.message,
                                'stack': error.stack
                            };
                            Ember.run.next(self, function(){
                                this.send('templateLogs', errorObject, 'component');
                            });
                        }
                        return children;
                    }.property(),
                    updateChildren: function(id, parentId){
                        var objectContext = this;
                        var collectionName = self.get('collectionName');
                        var object = self.store.find(collectionName, id);
                        object.then(function(object){
                            objectContext.get('children.content').then(function(children){
                                if(parentId === 'root'){
                                    children.pushObject(object);
                                } else{
                                    children.removeObject(object);
                                }
                            });
                        });
                    }
                });
                var root = Root.create({});
                return [root];// Намеренно возвращается значение в виде массива, так как шаблон ожидает именно такой формат
            }.property('root.childCount', 'model'),

            rootChildren: null,
            /**
             Активный контекст
             */
            activeContext: function(){
                return this.get('controllers.context.model');
            }.property('controllers.context.model'),

            contextToolbar: function(){
                var sideBarControl = this.get('controllers.component.sideBarControl') || {};
                return Ember.get(sideBarControl, 'contextToolbar');
            }.property('controllers.component.sideBarControl.contextToolbar'),

            actions: {
                /**
                 Сохранение результата drag and drop
                 @method updateSortOrder
                 @param String id ID перемещаемого объекта
                 @param String id ID нового родителя перемещаемого объекта
                 @param String id ID элемента после которого вставлен перемещаемый объект
                 @param Array Массив nextSibling следующие обьекты за перемещаемым объектом
                 */
                updateSortOrder: function(id, parentId, prevSiblingId, nextSibling){
                    var self = this;
                    var type = this.get('collectionName');
                    var ids = nextSibling || [];
                    var moveParams = {};
                    var resource;
                    var sibling;
                    var node;
                    var parent;
                    var oldParentId;
                    var models = this.store.all(type);

                    node = models.findBy('id', id);
                    moveParams.object = {
                        'id': node.get('id'),
                        'version': node.get('version')
                    };
                    oldParentId = node.get('parent.id') || 'root';


                    if(parentId && parentId !== 'root'){
                        parent = models.findBy('id', parentId);
                        moveParams.branch = {
                            'id': parent.get('id'),
                            'version': parent.get('version')
                        };
                    }
                    if(prevSiblingId){
                        sibling = models.findBy('id', prevSiblingId);
                        moveParams.sibling = {
                            'id': sibling.get('id'),
                            'version': sibling.get('version')
                        };
                    }

                    resource = this.get('controllers.component.settings.actions.move.source');
                    $.ajax({'type': 'POST', 'url': resource, 'data': JSON.stringify(moveParams), 'dataType': 'json', 'contentType': 'application/json'}).then(
                        function(){
                            ids.push(id);
                            var parentsUpdateRelation = [];
                            if(parentId !== oldParentId){
                                if(parentId && parentId !== 'root'){
                                    ids.push(parentId);
                                    parentsUpdateRelation.push(parentId);
                                }
                                if(oldParentId && oldParentId !== 'root'){
                                    ids.push(oldParentId);
                                    parentsUpdateRelation.push(oldParentId);
                                }
                            }
                            self.store.findByIds(type, ids).then(function(nodes){
                                nodes.invoke('reload');
                                var parent;
                                var promises = [];
                                for(var i = 0; i < parentsUpdateRelation.length; i++){
                                    parent = models.findBy('id', parentsUpdateRelation[i]);
                                    parent.get('children').then(function(children){
                                        promises.push(children.reloadLinks());
                                    });
                                }

                                if(parentId !== oldParentId && (parentId === 'root' || oldParentId === 'root')){
                                    self.get('root')[0].updateChildren(id, parentId);
                                }
                            });
                        },
                        function(error){
                            self.send('backgroundError', error);
                        }
                    );
                }
            }
        });
    };
});
define('tree/views',['App', 'toolbar'], function(UMI){
    
    return function(){
        UMI.TreeControlView = Ember.View.extend({
            templateName: 'partials/treeControl',
            classNames: ['row', 's-full-height'],

            expandedBranchesChange: function(){
                var expandedBranches = this.get('controller.expandedBranches');
                for(var i = 0; i < expandedBranches.length; i++){
                    this.send('expandItem', expandedBranches[i]);
                }
            }.observes('controller.expandedBranches'),

            actions: {
                expandItem: function(id){
                    if(this.$()){
                        var itemView = this.$().find('[data-id='+ id +']');
                        if(itemView.length){
                            itemView = Ember.View.views[itemView[0].id];
                            if(itemView && !itemView.get('isExpanded')){
                                itemView.set('isExpanded', true);
                            }
                        }
                    }
                }
            },

            didInsertElement: function(){
                var scrollContainer = this.$().find('.umi-tree-wrapper')[0];
                new IScroll(scrollContainer, UMI.config.iScroll);
                var self = this;

                var dragAndDrop = function(event, el){
                    var draggableNode = el.parentNode.parentNode;
                    var placeholder = document.createElement('li');
                    var ghost = document.createElement('span');
                    // Смещение призрака относительно курсора
                    var ghostPositionOffset = 2;

                    $('html').addClass('s-unselectable');
                    // Для компонента tree класс выставлюющий потомкам cursor=default
                    self.$().addClass('drag-inside');
                    // Добавим плейсхолдер на место перемещаемой ноды
                    placeholder.className = 'umi-tree-placeholder';
                    placeholder.setAttribute('data-id', el.parentNode.parentNode.getAttribute('data-id'));
                    $(draggableNode).addClass('hide');
                    placeholder = draggableNode.parentNode.insertBefore(placeholder, draggableNode);

                    // Добавим призрак
                    ghost.className = 'umi-tree-ghost';
                    ghost.innerHTML = '<i class="' + el.className + '"></i>' + $(el.parentNode).children('a').text();
                    ghost = document.body.appendChild(ghost);

                    /**
                     * Устанавливает позицию призрака
                     * */
                    var ghostPosition = function(event){
                        ghost.style.top = event.pageY + ghostPositionOffset + 'px';
                        ghost.style.left = event.pageX + ghostPositionOffset + 'px';
                    };
                    ghostPosition(event);

                    /**
                     * Возвращает соседний элемент определеного типа
                     *
                     * @param {Object} Элемент для которого нужно найти следующих соседей
                     * @param {string} Тип элемента который требуется найти
                     * @returns {Object|Null} Возвращаем найденный элемент
                     * */
                    function findNextSibling(element, type){
                        type = type.toUpperCase();
                        var nextElement = element.nextElementSibling;
                        while(nextElement && nextElement.tagName !== type){
                            nextElement = nextElement.nextElementSibling;
                        }
                        return nextElement;
                    }

                    var delayBeforeExpand;
                    $(document).on('mousemove', 'body, .umi-tree-ghost', function(event){
                        if(delayBeforeExpand){
                            clearTimeout(delayBeforeExpand);
                        }
                        ghostPosition(event);
                        var nextElement;
                        var hoverElement;
                        var elemHeight;
                        var elemPositionTop;
                        // Вычисляем элемент под курсором мыши
                        var elem = document.elementFromPoint(event.clientX, event.clientY);

                        // Раскрытие ноды имеющую потомков
                        var setExpanded = function(node){
                            var itemView = Ember.View.views[node.id];
                            if(itemView.get('controller.model.childCount')){
                                itemView.set('isExpanded', true);
                            }
                        };
                        // Проверим находимся мы над деревом или нет
                        if($(elem).closest('.umi-tree').length){
                            hoverElement = $(elem).closest('li')[0];
                            // Устанавливаем плэйсхолдер рядом с элементом
                            if(hoverElement && hoverElement !== placeholder && hoverElement.getAttribute('data-id') !== 'root'){
                                elemHeight = hoverElement.offsetHeight;
                                elemPositionTop = hoverElement.getBoundingClientRect().top;
                                // Помещаем плэйсхолдер:
                                // 1) после ноды - Если позиция курсора на ноде ниже ~70% её высоты
                                // 2) перед нодой - Если позиция курсора на ноде выше ~30% её высоты
                                // 3) "внутрь" ноды - если навели на центр. При задержке пользователя на центре раскрываем ноду.
                                if(event.clientY > elemPositionTop + parseInt(elemHeight * 0.7, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    nextElement = findNextSibling(hoverElement, 'li');
                                    if(nextElement){
                                        placeholder = hoverElement.parentNode.insertBefore(placeholder, nextElement);
                                    } else{
                                        placeholder = hoverElement.parentNode.appendChild(placeholder);
                                    }
                                } else if(event.clientY < elemPositionTop + parseInt(elemHeight * 0.3, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    placeholder = hoverElement.parentNode.insertBefore(placeholder, hoverElement);
                                } else{
                                    var emptyChildList = document.createElement('ul');
                                    emptyChildList.className = 'umi-tree-list';
                                    emptyChildList.setAttribute('data-parent-id', hoverElement.getAttribute('data-id'));
                                    placeholder = placeholder.parentNode.removeChild(placeholder);

                                    placeholder = emptyChildList.appendChild(placeholder);
                                    emptyChildList = hoverElement.appendChild(emptyChildList);
                                    delayBeforeExpand = setTimeout(function(){
                                        setExpanded(hoverElement);
                                    }, 500);
                                }
                            }
                        }
                    });

                    $(document).on('mouseup', function(event){
                        var elem = document.elementFromPoint(event.clientX, event.clientY);
                        var prevSiblingId = null;
                        var list = $(elem).closest('.umi-tree-list')[0];

                        // Удаляем обработчик события
                        $(document).off('mousemove', 'body, .umi-tree-ghost');
                        $(document).off('mouseup');
                        //Удаление призрака
                        ghost.parentNode.removeChild(ghost);

                        // Если курсор над плейсхолдером считаем что перемещение удачное
                        if(list && !$(list).hasClass('umi-tree')){
                            /**
                             * Находим предыдущего соседа
                             */
                            (function findPrevSibling(el){
                                var sibling = el.previousElementSibling;
                                if(sibling && ($(sibling).hasClass('hide') || sibling.tagName !== 'LI')){
                                    findPrevSibling(sibling);
                                } else{
                                    prevSiblingId = sibling ? sibling.getAttribute('data-id') : null;
                                }
                            }(placeholder));

                            var nextSibling = [];
                            /**
                             * Фильтр элементов списка
                             */
                            (function findNextSibling(element){
                                var sibling = element.nextElementSibling;
                                if(sibling){
                                    if($(sibling).hasClass('hide') || sibling.tagName !== 'LI'){
                                        findNextSibling(sibling);
                                    } else{
                                        nextSibling.push(sibling.getAttribute('data-id'));
                                    }
                                }
                            }(placeholder));
                            var parentId = list.getAttribute('data-parent-id');
                            self.get('controller').send('updateSortOrder', placeholder.getAttribute('data-id'), parentId, prevSiblingId, nextSibling);
                            self.send('expandItem', parentId);
                        }
                        // Удаление плэйсхолдера
                        if(placeholder.parentNode){
                            placeholder.parentNode.removeChild(placeholder);
                        }
                        $(draggableNode).removeClass('hide');
                        $('html').removeClass('s-unselectable');
                    });
                };

                var timeoutForDrag;
                this.$().on('mousedown', '.icon.move', function(event){
                    if(event.originalEvent.which !== 1){
                        return;
                    }
                    var el = this;
                    timeoutForDrag = setTimeout(function(){
                        dragAndDrop(event, el);
                    }, 200);
                });

                this.$().on('mouseup', '.icon.move', function(){
                    if(timeoutForDrag){
                        clearTimeout(timeoutForDrag);
                    }
                });
            },

            willDestroyElement: function(){
                this.get('controller').removeObserver('collectionName');
                this.get('controller').removeObserver('activeContext');
                this.get('controller').removeObserver('objects.@each.isDeleted');
                this.removeObserver('controller.expandedBranches');
            }
        });

        UMI.TreeItemView = Ember.View.extend({
            treeControlView: null,
            templateName: 'partials/treeControl/treeItem',
            tagName: 'li',
            classNameBindings: ['item.isDragged:hide', 'item.isDeleted:hide'],
            attributeBindings: ['dataId:data-id'],

            editLink: function(){
                var link = this.get('item.meta.editLink');
                return link;
            }.property('item'),

            dataId: function(){
                return this.get('item.id');
            }.property('item.id'),

            inActive: function(){
                return this.get('item.active') === false ? true : false;
            }.property('item.active'),

            active: function(){// TODO: можно сделать через lookup http://jsbin.com/iFEvATE/2/edit
                return this.get('controller.activeContext.id') === this.get('item.id');
            }.property('controller.activeContext.id'),

            savedDisplayName: function(){
                if(this.get('item.id') === 'root'){
                    return this.get('item.displayName');
                } else{
                    return this.get('item._data.displayName');
                }
            }.property('item.currentState.loaded.saved'),//TODO: Отказаться от использования _data

            expandActiveContext: function(){
                Ember.run.once(this, function(){
                    var expandedBranches = this.get('controller.expandedBranches') || [];
                    if(expandedBranches){
                        if(this.get('item.id') === 'root'){
                            return this.set('isExpanded', true);
                        }
                        var contains = expandedBranches.contains(parseFloat(this.get('item.id')));
                        if(contains){
                            return this.set('isExpanded', true);
                        }
                    }
                });
            },

            actions: {
                expanded: function(){
                    var id = this.get('item.id');
                    id = id === 'root' ? id : parseFloat(id);
                    var treeControl = this.get('controller');
                    var expandedBranches = treeControl.get('expandedBranches');
                    this.set('isExpanded', !this.get('isExpanded'));
                    if(this.get('isExpanded')){
                        expandedBranches.push(id);
                    } else{
                        expandedBranches = expandedBranches.without(id);
                    }
                    treeControl.set('expandedBranches', expandedBranches);
                }
            },


            getChildren: function(){
                var model = this.get('item');
                var collectionName = model.get('typeKey') || model.constructor.typeKey;
                var promise;
                var self = this;
                if(model.get('id') === 'root'){
                    promise = model.get('children');
                } else{
                    var objectProperties = self.get('controller').get('objectProperties').join(',');
                    var requestParams = {'filters[parent]': model.get('id'), 'fields': objectProperties};
                    if(self.get('controller').get('filterTrashed')){
                        requestParams['filters[trashed]'] = 'equals(0)';
                    }
                    promise = this.get('controller').store.updateCollection(collectionName, requestParams);
                }
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: promise,
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
            },

            sortedChildren: function(){
                return this.getChildren();
            }.property('item.didUpdate'),

            init: function(){
                this._super();
                var model = this.get('item');
                if('needReloadHasMany' in this.get('item')){
                    this.get('item').on('needReloadHasMany', function(){
                        model.get('children').then(function(children){
                            children.reloadLinks();
                        });
                    });
                }
            },

            didInsertElement: function(){
                Ember.run.once(this, 'expandActiveContext');
            }
        });

        UMI.TreeControlContextToolbarController = Ember.ObjectController.extend({});

        UMI.TreeControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'umi-tree-context-toolbar', 'right'],
            elementView: UMI.ToolbarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent, UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour;
                    var i;
                    var action;
                    if(behaviourName){
                        behaviour = UMI.splitButtonBehaviour.get(behaviourName) || {};
                    } else{
                        behaviour = {};
                    }
                    var choices = this.get('context.behaviour.choices');
                    if(behaviourName === 'contextMenu' && Ember.typeOf(choices) === 'array'){
                        for(i = 0; i < choices.length; i++){
                            var prefix = '';
                            var behaviourAction = UMI.splitButtonBehaviour.get(choices[i].behaviour.name);
                            if(behaviourAction){
                                if(behaviourAction.hasOwnProperty('_actions')){
                                    prefix = '_';
                                }
                                action = behaviourAction[prefix + 'actions'][choices[i].behaviour.name];
                                if(action){
                                    if(Ember.typeOf(behaviour.actions) !== 'object'){
                                        behaviour.actions = {};
                                    }
                                    behaviour.actions[choices[i].behaviour.name] = action;
                                }
                            }
                        }
                    }
                    behaviour.actions.sendActionForBehaviour = function(behaviour){
                        var object = this.get('controller.model');
                        this.send(behaviour.name, {behaviour: behaviour, object: object});
                    };
                    behaviour.classNames = ['tiny white square'];
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});
define(
    'tree/main',[
        'App',
        './controllers',
        './views'
    ],
    function(UMI, controllers, views){
        

        controllers();
        views();
    }
);
define('tree', ['tree/main'], function (main) { return main; });

define('partials/forms/elements/mixins',['App'], function(UMI){
    

    return function(){
        UMI.InputValidate = Ember.Mixin.create({
            validator: null,
            validateElement: null,

            focusOut: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('object');
                    object.filterProperty(this.get('meta.dataSource'));
                    object.validateProperty(this.get('meta.dataSource'));
                }
            },

            focusIn: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('object');
                    object.clearValidateForProperty(this.get('meta.dataSource'));
                }
            },

            validateErrorsTemplate: function(){
                var propertyName = this.get('meta.dataSource');
                var template = '{{#if view.object.validErrors.' + propertyName + '}}' +
                    '<small class="error">{{#each object.validErrors.' + propertyName + '}}' +
                    '{{message}} ' +
                    '{{/each}}</small>' +
                    '{{/if}}';
                return template;
            }
        });
    };
});
define('partials/forms/elements/wysiwyg/main',['App'], function(UMI){
    

    return function(){
        UMI.HtmlEditorView = Ember.View.extend({
            classNames: ['ckeditor-row'],

            template: function(){
                var textarea = '{{textarea value=view.meta.attributes.value placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                return Ember.Handlebars.compile(textarea);
            }.property(),

            didInsertElement: function(){
                var self = this;
                var el = this.$().children('textarea');
                var edit = CKEDITOR.replace(el[0].id);
            }
        });

        UMI.HtmlEditorCollectionView = Ember.View.extend(UMI.InputValidate, {
            classNames: ['ckeditor-row'],

            textareaId: function(){
                return 'textarea-' + this.get('elementId');
            }.property(),

            template: function(){
                var textarea = '<textarea id="{{unbound view.textareaId}}" placeholder="{{unbound view.meta.placeholder}}" name="{{unbound view.meta.attributes.name}}">{{unbound view.object.' + this.get('meta.dataSource') + '}}</textarea>';
                var validate = this.validateErrorsTemplate();
                return Ember.Handlebars.compile(textarea + validate);
            }.property(),

            setTextareaValue: function(edit){
                if(this.$() && this.$().children('textarea').length){
                    var value = this.get('object.' + this.get('meta.dataSource'));
                    if(edit && edit.getData() !== value){
                        edit.setData(value);
                    }
                }
            },

            updateContent: function(event, edit){
                var self = this;
                if(event.editor.checkDirty()){
                    self.get('object').set(self.get('meta.dataSource'), edit.getData());
                }
            },

            didInsertElement: function(){
                var self = this;
                var el = this.$().children('textarea');
                var edit = CKEDITOR.replace(el[0].id);

                edit.on('blur', function(event){
                    Ember.run.once(self, 'updateContent', event, edit);
                });

                edit.on('key', function(event){// TODO: Это событие было добавлено только из-за того, что событие save срабатывает быстрее blur. Кажется можно сделать лучше.
                    Ember.run.once(self, 'updateContent', event, edit);
                });

                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setTextareaValue', edit);
                });
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            }
        });
    };
});
define('partials/forms/elements/select/main',['App'], function(UMI){
    

    return function(){
        UMI.SelectView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            optionLabelPath: function(){
                return 'content.label';
            }.property(),
            optionValuePath: function(){
                return 'content.value';
            }.property(),
            prompt: function(){
                var meta = this.get('meta.choices');
                var choicesHasPrompt;
                if(meta && Ember.typeOf(meta) === 'array'){
                    choicesHasPrompt = meta.findBy('value', '');
                }
                if(choicesHasPrompt){
                    return choicesHasPrompt.label;
                } else{
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),
            content: null,
            init: function(){
                this._super();
                this.set('selection', this.get('object.choices').findBy('value', this.get('object.value')));
                this.set('content', this.get('object.choices'));
            },
            didInsertElement: function(){
                var prompt = this.$().find('option')[0];
                var validators = this.get('meta.validators') || [];
                validators = validators.findBy('type', 'required');
                if(!prompt.value && validators){
                    prompt.disabled = true;
                }
            }
        });

        UMI.SelectCollectionView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            isLazy: false,
            optionLabelPath: function(){
                return this.get('isLazy') ? 'content.displayName' : 'content.label';
            }.property(),
            optionValuePath: function(){
                return this.get('isLazy') ? 'content.id' : 'content.value';
            }.property(),
            prompt: function(){
                var meta = this.get('meta.choices');
                var choicesHasPrompt;
                if(meta && Ember.typeOf(meta) === 'array'){
                    choicesHasPrompt = meta.findBy('value', '');
                }
                if(choicesHasPrompt){
                    return choicesHasPrompt.label;
                } else{
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),
            content: null,
            changeValue: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedObject = this.get('selection');
                var value;
                if(this.get('isLazy')){
                    value = selectedObject ? selectedObject : undefined;
                    object.set(property, value);
                    object.changeRelationshipsValue(property, selectedObject ? selectedObject.get('id') : undefined);
                } else{
                    value = selectedObject ? selectedObject.value : '';
                    object.set(property, value);
                }
            }.observes('value'),
            init: function(){
                this._super();
                var self = this;
                var promises = [];
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                this.set('isLazy', this.get('meta.lazy'));
                if(this.get('isLazy')){
                    var store = self.get('controller.store');
                    promises.push(object.get(property));

                    var getCollection = function(relation){
                        promises.push(store.findAll(relation.type));
                    };
                    object.eachRelationship(function(name, relation){
                        if(name === property){
                            getCollection(relation);
                        }
                    });

                    return Ember.RSVP.all(promises).then(function(results){
                        Ember.set(object.get('loadedRelationshipsByName'), property, results[0] ? results[0].get('id') : undefined);
                        self.set('selection', results[0]);
                        self.set('content', results[1]);
                    });
                } else{
                    self.set('selection', this.get('meta.choices').findBy('value', object.get(property)));
                    self.set('content', this.get('meta.choices'));

                    this.addObserver('object.' + property, function(){
                        Ember.run.once(function(){
                            self.set('selection', self.get('meta.choices').findBy('value', object.get(property)));
                        });
                    });
                }
            },
            didInsertElement: function(){
                var property = this.get('meta.dataSource');
                var collectionName = this.get('object').constructor.typeKey;
                var metadata = this.get('controller.store').metadataFor(collectionName);
                var validators = Ember.get(metadata, 'validators.' + property);
                if(validators && Ember.typeOf(validators) === 'array'){
                    validators = validators.findBy('type', 'required');
                    if(validators){
                        var prompt = this.$().find('option')[0];
                        if(!prompt.value && validators){
                            prompt.disabled = true;
                        }
                    }
                }
            },
            willDestroyElement: function(){
                this.removeObserver('value');
                this.removeObserver('object.' + this.get('meta.dataSource'));
            }
        });
    };
});
define('partials/forms/elements/multi-select/main',['App'], function(UMI){
    

    return function(){
        var lazyChoicesBehaviour = {
            /**
             * Шаблон View
             * @property template
             */
            templateName: 'partials/multi-select-lazy-choices',
            /**
             * Изменяет связь hasMany для объекта
             * @param type
             * @param id
             * @returns {Promise}
             */
            changeRelations: function(type, id){
                var object = this.get('object');
                var selectedObject = this.get('collection').findBy('id', id);
                var property = this.get('meta.dataSource');
                var relation = object.get(property);
                return relation.then(function(relation){
                    if(type === 'select'){
                        relation.pushObject(selectedObject);
                    } else{
                        relation.removeObject(selectedObject);
                    }
                    var Ids = relation.get('content').mapBy('id');
                    object.changeRelationshipsValue(property, Ids);
                });
            }
        };

        UMI.MultiSelectView = Ember.View.extend({
            /**
             * Класс для view
             * property classNames
             */
            classNames: ['row', 'collapse', 'umi-multi-select'],
            /**
             * Вычесляемые классы
             * @classNameBindings
             */
            classNameBindings: ['isOpen:opened'],
            /**
             * Шаблон View
             * @property template
             */
            templateName: 'partials/multi-select',
            /**
             * Тригер списка значений
             * @property isOpen
             */
            isOpen: false,
            /**
             * Значение placeholder
             * @property placeholder
             */
            placeholder: '',
            /**
             * определяет использование lazy списка
             * @property isLazy
             */
            isLazy: false,
            /**
             * Коллекция объектов (choices)
             * @property collection
             */
            collection: [],
            /**
             * Выбранные ID объектов
             * @property selectedIds
             */
            selectedIds: [],
            /**
             * @property filterIds
             */
            filterIds: [],
            /**
             * @property filterOn
             */
            filterOn: false,
            /**
             * @property inputInFocus
             */
            inputInFocus: false,
            /**
             * Связанные объекты
             * @property selectedObjects
             */
            selectedObjects: function(){
                var key = this.get('isLazy') ? 'id' : 'value';
                var collection = this.get('collection') || [];
                var selectedObjects = [];
                var selectedIds = this.get('selectedIds') || [];
                collection.forEach(function(item){
                    var id = Ember.get(item, key);
                    if(selectedIds.contains(id)){
                        selectedObjects.push(item);
                    }
                });
                return selectedObjects;
            }.property('selectedIds.@each'),
            /**
             * Несвязанные объекты. Появляются в выпадающем списке
             * @property notSelectedObjects
             */
            notSelectedObjects: function(){
                var key = this.get('isLazy') ? 'id' : 'value';
                var collection = this.get('collection');
                var notSelectedObjects = [];
                var ids;
                if(this.get('filterOn')){
                    ids = this.get('filterIds') || [];
                    collection.forEach(function(item){
                        var id = Ember.get(item, key);
                        if(ids.contains(id)){
                            notSelectedObjects.push(item);
                        }
                    });
                } else{
                    ids = this.get('selectedIds') || [];
                    collection.forEach(function(item){
                        var id = Ember.get(item, key);
                        if(!ids.contains(id)){
                            notSelectedObjects.push(item);
                        }
                    });
                }
                return notSelectedObjects;
            }.property('selectedIds.@each', 'filterIds'),
            /**
             * Изменяет состояние выпадающего списка (отрывет/закрывает)
             * @method opened
             */
            opened: function(){
                var isOpen = this.get('isOpen');
                var self = this;
                if(isOpen){
                    this.set('inputInFocus', true);
                    $('body').on('click.umi.multiSelect', function(event){
                        if(!$(event.target).closest('.umi-multi-select-list').length || !$(event.target).hasClass('umi-multi-select-input')){
                            self.set('isOpen', false);
                        }
                    });
                } else{
                    $('body').off('.umi.multiSelect');
                    this.set('inputInFocus', false);
                    this.get('notSelectedObjects').setEach('hover', false);
                }
            }.observes('isOpen'),

            changeRelations: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedIds = this.get('selectedIds');
                if(this.get('isLazy')){
                    object.set(property, selectedIds);
                } else{
                    selectedIds = Ember.typeOf(selectedIds) === 'array' ? JSON.stringify(selectedIds.sort()) : '';
                    selectedIds = selectedIds === '[]' ? '' : selectedIds;
                    object.set(property, selectedIds);
                }
            },

            actions: {
                toggleList: function(){
                    this.set('filterIds', []);
                    this.set('filterOn', null);
                    var isOpen = !this.get('isOpen');
                    this.set('isOpen', isOpen);
                },
                select: function(id){
                    this.get('selectedIds').pushObject(id);
                    this.changeRelations('select', id);
                },
                unSelect: function(id){
                    this.get('selectedIds').removeObject(id);
                    this.changeRelations('unSelect', id);
                },
                markHover: function(key){
                    var collection = this.get('notSelectedObjects');
                    var hoverObject = collection.findBy('hover', true);
                    var index = 0;
                    if(hoverObject){
                        hoverObject.set('hover', false);
                        index = collection.indexOf(hoverObject);
                        if(key === 'Down' && index < collection.length - 1){
                            ++index;
                        } else if(key === 'Up' && index){
                            --index;
                        }
                    }
                    collection.objectAt(index).set('hover', true);
                },
                selectHover: function(){
                    var key = this.get('isLazy') ? 'id' : 'value';
                    var collection = this.get('notSelectedObjects');
                    var hoverObject = collection.findBy('hover', true);
                    this.send('select', hoverObject.get(key));
                    hoverObject.set('hover', false);
                    this.send('toggleList');
                }
            },

            inputView: Ember.View.extend({
                tagName: 'input',
                classNames: ['umi-multi-select-input'],
                attributeBindings: ['parentView.placeholder:placeholder', 'value', 'autocomplete'],
                toggleFocus: function(){
                    if(this.get('parentView.inputInFocus')){
                        this.$().focus();
                    } else{
                        this.$().blur();
                    }
                }.observes('parentView.inputInFocus'),
                autocomplete: 'off',
                value: function(){
                    var selectedObject = this.get('parentView.selectedObjects');
                    var value;
                    if(selectedObject.length){
                        value = '';
                    } else{
                        value = '';
                    }
                    return value;
                }.property('parentView.selectedObjects'),
                click: function(){
                    this.get('parentView').set('isOpen', true);
                },
                keyUp: function(){
                    var key = 'value';
                    var label = 'label';
                    var parentView = this.get('parentView');
                    if(parentView.get('isLazy')){
                        key = 'id';
                        label = 'displayName';
                    }
                    var val = this.$().val();
                    if(!val){
                        return;
                    }
                    parentView.set('filterOn', true);
                    var pattern = new RegExp("^" + val, "i");
                    var collection = parentView.get('collection');
                    var filterIds = [];
                    var selectedIds = parentView.get('selectedIds');
                    collection.forEach(function(item){
                        if(pattern.test(Ember.get(item, label)) && !selectedIds.contains(Ember.get(item, key))){
                            filterIds.push(Ember.get(item, key));
                        }
                    });
                    parentView.set('filterIds', filterIds);
                    parentView.set('isOpen', true);
                },
                keyDown: function(event){
                    event.stopPropagation();
                    var key;
                    var parentView = this.get('parentView');
                    //TODO: вынести маппинг кнопок в метод UMI.Utils
                    switch(event.keyCode){
                        case 38:
                            key = 'Up';
                            break;
                        case 40:
                            key = 'Down';
                            break;
                        case 13:
                            key = 'Enter';
                            break;
                        case 27:
                            key = 'Escape';
                            break;
                    }
                    switch(key){
                        case 'Down': case 'Up':
                        parentView.send('markHover', key);
                        break;
                        case 'Enter':
                            parentView.send('selectHover');
                            event.preventDefault();// Предотвращаем submit form
                            break;
                        case 'Escape':
                            parentView.set('isOpen', false);
                            break;
                    }
                },
                blur: function(){
                    this.$()[0].value = '';
                },
                willDestroyElement: function(){
                    this.removeObserver('parentView.inputInFocus');
                }
            }),

            init: function(){
                this._super();
                var self = this;
                var property = this.get('meta.dataSource');
                var object = this.get('object');
                var store = self.get('controller.store');
                var promises = [];
                var selectedObjects;
                this.set('isLazy', this.get('meta.lazy'));
                if(this.get('isLazy')){
                    this.reopen(lazyChoicesBehaviour);
                    selectedObjects = object.get(property);
                    promises.push(selectedObjects);

                    var getCollection = function(relation){
                        promises.push(store.findAll(relation.type));
                    };
                    object.eachRelationship(function(name, relation){
                        if(name === property){
                            getCollection(relation);
                        }
                    });

                    return Ember.RSVP.all(promises).then(function(results){
                        var relatedObjectsId = results[0].mapBy('id') || [];
                        var loadedRelationshipsByName = results[0].mapBy('id') || [];
                        self.set('collection', results[1]);
                        self.set('selectedIds', relatedObjectsId);
                        Ember.set(object.get('loadedRelationshipsByName'), property, loadedRelationshipsByName);
                    });
                } else{
                    var propertyArray = object.get(property) || '[]';
                    try{
                        propertyArray = JSON.parse(propertyArray);
                    } catch(error){
                        error.message = 'Некорректное значение поля ' + property + '. Ожидается массив или null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    self.set('collection', this.get('meta.choices'));
                    self.set('selectedIds', propertyArray);
                }
            },

            willDestroyElement: function(){
                this.removeObserver('isOpen');
            }
        });
    };
});
define('partials/forms/elements/checkbox/main',['App'], function(UMI){
    

    return function(){
        UMI.CheckboxElementView = Ember.View.extend({
            template: function(){
                var self = this;
                var name = self.get('name');
                var attributeValue = self.get('attributeValue');
                var className = self.get('className');
                var isChecked = self.get('value');

                var hiddenInput = '<input type="hidden" name="' + name + '" value="0" />';
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + attributeValue + '" class="' + className + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(hiddenInput + checkbox + label);
            }.property(),

            name: function(){
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.name');
            }.property('meta.attributes.name'),

            value: function(){
                var meta = this.get('meta');
                return Ember.get(meta, 'value');
            }.property('meta.value'),

            attributeValue: function(){
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.value');
            }.property('meta.attributes.value'),

            classNames: ['umi-element-checkbox'],

            actions: {
                change: function(){
                    var $el = this.$();
                    var checkbox = $el.find('input[type="checkbox"]')[0];
                    checkbox.checked = !checkbox.checked;
                    $(checkbox).trigger("change");
                }
            }
        });

        UMI.CheckboxCollectionElementView = Ember.View.extend({
            template: function(){
                var self = this;
                var isChecked;
                var object = self.get('object');
                var meta = self.get('meta');
                var name = Ember.get(meta, 'attributes.name');
                var value = Ember.get(meta, 'attributes.value');

                isChecked = Ember.get(object, Ember.get(meta, 'dataSource'));

                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(checkbox + label);
            }.property(),

            classNames: ['umi-element-checkbox'],

            setCheckboxValue: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    $el.find('input[type="checkbox"]')[0].checked = self.get('object.' + self.get('meta.dataSource'));
                }
            },

            addObserverProperty: function(){
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setCheckboxValue');
                });
            },

            init: function(){
                this._super();
                this.addObserverProperty();
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            actions: {
                change: function(){
                    var self = this;
                    var $el = this.$();
                    var checkbox;
                    self.get('object').toggleProperty(self.get('meta.dataSource'));
                }
            }
        });
    };
});
define('partials/forms/elements/radio/main',['App'], function(UMI){
    

    return function(){
        UMI.RadioElementView = Ember.View.extend({
            templateName: 'partials/radioElement',
            classNames: ['umi-element-radio-group'],

            addObserverProperty: function(){
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setSelectedRadioElement');
                });
            },

            setSelectedRadioElement: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "";
                    var radio = $el[0].querySelector('input[type="radio"][value="' + objectValue + '"]');
                    if(radio){
                        radio.checked = true;
                    }
                }
            },

            init: function(){
                this._super();
                Ember.warn('Поле с типом radio не поддерживает lazy choices.', !this.get('meta.lazy'));

                if(Ember.typeOf(this.get('object')) === 'instance'){
                    this.addObserverProperty();
                }
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            radioElementView: Ember.View.extend({
                classNames: ['umi-element-radio'],

                template: function(){
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue;

                    if(Ember.typeOf(object) === 'instance'){
                        objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "";
                    } else{
                        objectValue = Ember.get(meta, 'value');
                    }

                    if(objectValue === value){
                        isChecked = true;
                    }
                    var radio = '<input type="radio" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(radio + label);
                }.property(),

                label: function(){
                    Ember.warn('Не задан label в choices поля с типом radio.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),

                actions: {
                    change: function(){
                        var self = this;
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');

                        if(Ember.typeOf(object) === 'instance'){
                            Ember.set(object, propertyName, value);
                        } else{
                            var radio = this.$().find('input[type="radio"]');
                            if(radio.length){
                                radio[0].checked = true;
                            }
                        }
                    }
                }
            })
        });
    };
});
define('partials/forms/elements/text/main',['App'], function(UMI){
    

    return function(){
        UMI.TextElementView = Ember.View.extend(UMI.InputValidate, {
            type: "text",
            classNames: ['umi-element-text'],
            template: function(){
                var template;
                if(Ember.typeOf(this.get('object')) === 'instance'){
                    this.set('validator', 'collection');
                    var dataSource = this.get('meta.dataSource');
                    var input = '{{input type=view.type value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                    var validate = this.validateErrorsTemplate();
                    template = input + validate;
                } else{
                    template = '{{input type=view.type value=view.meta.value name=view.meta.attributes.name}}';
                }
                return Ember.Handlebars.compile(template);
            }.property()
        });
    };
});
define('partials/forms/elements/number/main',['App'], function(UMI){
    

    return function(){
        UMI.NumberElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-number'],
            type: 'number'
        });
    };
});
define('partials/forms/elements/email/main',['App'], function(UMI){
    

    return function(){
        UMI.EmailElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-email'],
            type: "email"
        });
    };
});
define('partials/forms/elements/password/main',['App'], function(UMI){
    

    return function(){
        UMI.PasswordElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],
            type: 'text'
        });
    };
});
define('partials/forms/elements/time/main',['App'], function(UMI){
    


    return function(){
        UMI.TimeElementComponent = Ember.Component.extend(UMI.InputValidate, {
            templateName: 'partials/timeElement',
            classNames: ['row', 'collapse'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });

                this.$().find('input').timepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    currentText: 'Выставить текущее время'
                });
            },

            inputView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});
define('partials/forms/elements/date/main',['App'], function(UMI){
    

    return function(){
        UMI.DateElementView = Ember.View.extend({
            templateName: 'partials/dateElement',

            classNames: ['row', 'collapse'],

            didInsertElement: function(){
                this.$().find('input').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd.mm.yy'
                });
            },

            actions: {
                clearValue: function(){
                    var self = this;
                    var el = self.$();
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else{
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
define('partials/forms/elements/datetime/main',['App'], function(UMI){
    

    return function(){

        UMI.DateTimeElementView = Ember.View.extend({
            templateName: 'partials/dateTimeElement',

            classNames: ['row', 'collapse'],

            value: null,

            changeValue: function(){
                Ember.run.once(this, 'setValueForObject');
            }.observes('value'),

            setValueForObject: function(){
                var self = this;

                if(Ember.typeOf(self.get('object')) === 'instance'){
                    var value = self.get('value');
                    value = Ember.isNone(value) ? '' : value;
                    var valueInObject = self.get('object.' + self.get("meta.dataSource")) || '';
                    if(value){
                        if(valueInObject){
                            valueInObject = JSON.parse(valueInObject);
                        } else{
                            valueInObject = {date: null};
                        }
                        valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                        if(valueInObject.date !== value){
                            var result = '';
                            if(value){
                                result = {
                                    date: value,
                                    timezone_type: 3,
                                    timezone: "Europe/Moscow"
                                };
                                result = JSON.stringify(result);
                            }
                            self.get('object').set(self.get("meta.dataSource"), result);
                        }
                    } else{
                        if(valueInObject !== value){
                            self.get('object').set(self.get("meta.dataSource"), '');
                        }
                    }
                }
            },

            setInputValue: function(){
                var self = this;
                var valueInObject = self.get('object.' + self.get("meta.dataSource"));
                var value = self.get('value');
                value = Ember.isNone(value) ? '' : value;
                if(valueInObject){
                    valueInObject = JSON.parse(valueInObject);
                    valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                    if(valueInObject.date !== value){
                        self.set('value', valueInObject.date);
                    }
                } else{
                    if(valueInObject !== value){
                        self.set('value', '');
                    }
                }
            },

            init: function(){
                this._super();
                var value;
                var self = this;
                try{
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        value = self.get('object.' + self.get("meta.dataSource"))  || '{}';
                        value = JSON.parse(value);
                        self.set("value", value.date || "");

                        self.addObserver('object.' + self.get('meta.dataSource'), function(){
                            Ember.run.once(self, 'setInputValue');
                        });
                    } else{
                        self.set("value", self.get('meta.value'));
                    }
                } catch(error){
                    self.get('controller').send('backgroundError', error);
                }
            },

            didInsertElement: function(){
                this.$().find('input').datetimepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    secondText: 'Секунды',
                    currentText: 'Выставить текущее время',
                    timeFormat: 'hh:mm:ss',
                    dateFormat: 'dd.mm.yy'
                });
            },

            willDestroyElement: function(){
                var self = this;
                if(Ember.typeOf(self.get('object')) === 'instance'){
                    self.removeObserver('object.' + self.get('meta.dataSource'));
                }
            },

            actions: {
                clearValue: function(){
                    var self = this;
                    self.set('value', '');
                }
            }
        });
    };
});
define('partials/forms/elements/file/main',['App'], function(UMI){
    

    return function(){
        UMI.FileElementView = Ember.View.extend({
            templateName: 'partials/fileElement',

            classNames: ['row', 'collapse'],

            actions: {
                clearValue: function(){
                    var self = this;
                    var el = self.$();
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else{
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
define('partials/forms/elements/image/main',['App'], function(UMI){
    

    return function(){
        UMI.ImageElementView = Ember.View.extend({
            template: 'partials/imageElement',

            classNames: ['row', 'collapse'],

            tumb: null,//TODO: Нужно превью? Если да, то предстоит генерить его на фронте

            actions: {
                clearValue: function(){
                    var self = this;
                    var el = self.$();
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else{
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
define('partials/forms/elements/textarea/main',['App'], function(UMI){
    

    return function(){

        UMI.TextareaElementView = Ember.View.extend({
            templateName: 'partials/textareaElement',

            classNames: ['umi-element-textarea'],

            textareaView: function(){
                var viewParams = {
                    didInsertElement: function(){
                        this.allowResize();
                    },

                    willDestroyElement: function(){
                        this.get('parentView').$().off('mousedown.umi.textarea');
                    },

                    allowResize: function(){
                        var $textarea = this.$().find('textarea');
                        var minHeight = 60;
                        if($textarea.length){
                            $textarea.css({height: minHeight});
                        }
                        this.get('parentView').$().on('mousedown.umi.textarea', '.umi-element-textarea-resizer', function(event){
                            if(event.button === 0){
                                var $el = $(this);
                                $('html').addClass('s-unselectable');
                                $el.addClass('s-unselectable');
                                var posY = $textarea.offset().top;
                                $('body').on('mousemove.umi.textarea', function(event){
                                    //TODO: Cделать метод глобальным
                                    //Подумать над тем, чтобы выделение сделаное до mouseMove не слетало
                                    //http://hashcode.ru/questions/86466/javascript-%D0%BA%D0%B0%D0%BA-%D0%B7%D0%B0%D0%BF%D1%80%D0%B5%D1%82%D0%B8%D1%82%D1%8C-%D0%B2%D1%8B%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%81%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B8%D0%BC%D0%BE%D0%B3%D0%BE-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%B1%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B5
                                    var removeSelection = function(){
                                        if(window.getSelection){
                                            window.getSelection().removeAllRanges();
                                        } else if(document.selection && document.selection.clear){
                                            document.selection.clear();
                                        }
                                    };
                                    removeSelection();

                                    var height = event.pageY - posY;
                                    if(height < minHeight){height = minHeight}
                                    $textarea.css({height: height});
                                });
                                $('body').on('mouseup.umi.textarea', function(){
                                    $('body').off('mousemove.umi.textarea');
                                    $('body').off('mouseup.umi.textarea');
                                    $('html').removeClass('s-unselectable');
                                });
                            }
                        });
                    }
                };

                if(Ember.typeOf(this.get('object')) === 'instance'){
                    viewParams.template = function(){
                        var propertyName = this.get('meta.dataSource');
                        var textarea = '{{textarea placeholder=view.attributes.placeholder name=view.meta.attributes.name value=view.object.' + propertyName + '}}';
                        var validate = this.validateErrorsTemplate();
                        return Ember.Handlebars.compile(textarea + validate);
                    }.property();
                    return Ember.View.extend(UMI.InputValidate, viewParams);
                } else{
                    viewParams.template = function(){
                        var textarea = '{{textarea placeholder=view.attributes.placeholder name=view.meta.attributes.name value=view.attributes.value}}';
                        return Ember.Handlebars.compile(textarea);
                    }.property();
                    return Ember.View.extend(viewParams);
                }
            }.property()
        });
    };
});
define('partials/forms/elements/checkbox-group/main',['App'], function(UMI){
    

    return function(){
        UMI.CheckboxGroupElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup',
            classNames: ['umi-element-checkbox-group']
        });

        UMI.CheckboxGroupCollectionElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup/collectionElement',
            classNames: ['umi-element-checkbox-group'],

            addObserverProperty: function(){
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setCheckboxesValue');
                });
            },

            setCheckboxesValue: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    var checkboxes = $el.find('input[type="checkbox"]');
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "[]";
                    try{
                        objectValue = JSON.parse(objectValue);
                    } catch(error){
                        error.message = 'Некорректное значение поля ' + propertyName + '. Ожидается массив или null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    for(var i = 0; i < checkboxes.length; i++){
                        checkboxes[i].checked = objectValue.contains(checkboxes[i].value);
                    }
                }
            },

            init: function(){
                this._super();
                Ember.warn('Поле с типом checkboxGroup не поддерживает lazy choices.', !this.get('meta.lazy'));
                this.addObserverProperty();
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            checkboxElementView: Ember.View.extend({
                classNames: ['umi-element-checkbox'],
                template: function(){
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "[]";
                    try{
                        objectValue = JSON.parse(objectValue);
                    } catch(error){
                        error.message = 'Некорректное значение поля ' + propertyName + '. Ожидается массив или null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    if(objectValue.contains(value)){
                        isChecked = true;
                    }
                    var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(checkbox + label);
                }.property(),
                label: function(){
                    Ember.warn('Не задан label в choices поля с типом checkboxGroup.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),
                actions: {
                    change: function(){
                        var self = this;
                        var $el = this.$();
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');
                        var objectValue = Ember.get(object, propertyName) || "[]";
                        try{
                            objectValue = JSON.parse(objectValue);
                        } catch(error){
                            error.message = 'Некорректное значение поля ' + propertyName + '. Ожидается массив или null. ' + error.message;
                            this.get('controller').send('backgroundError', error);
                        }

                        if(objectValue.contains(value)){
                            objectValue = objectValue.without(value);
                        } else{
                            objectValue.push(value);
                        }
                        objectValue.sort();
                        Ember.set(object, propertyName, JSON.stringify(objectValue));
                    }
                }
            })
        });
    };
});
define('partials/forms/elements/color/main',['App'], function(UMI){
    

    return function(){
        UMI.ColorElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-color'],
            type: "color"
        });
    };
});
define('partials/forms/elements/permissions/main',['App'], function(UMI){
    

    return function(){
        UMI.PermissionsView = Ember.View.extend({
            templateName: 'partials/permissions',
            objectProperty: function(){
                var object = this.get('object');
                var dataSource = this.get('meta.dataSource');
                var property = object.get(dataSource) || "{}";
                try{
                    property = JSON.parse(property);
                    if(Ember.typeOf(property) !== 'object'){
                        property = {};
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);
                } finally{
                    return property;
                }
            }.property('object'),

            setObjectProperty: function(checkbox, path, isChecked){
                var object = this.get('object');
                var objectProperty = this.get('objectProperty');
                var componentRoles = objectProperty[path];
                var childrenCheckboxes;
                var i;
                var childComponentName;
                var currentRole = checkbox.value;

                if(Ember.typeOf(componentRoles) !== 'array'){
                    componentRoles = objectProperty[path] = [];
                }

                function checkedParentCheckboxes(checkbox){
                    if(checkbox){
                        var checkedSiblingsCheckboxes = 0;
                        var checkboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        for(var i = 0; i < checkboxes.length; i++){
                            if(checkboxes[i].checked){
                                checkedSiblingsCheckboxes++;
                            }
                        }
                        if(checkedSiblingsCheckboxes === checkboxes.length){
                            checkbox.indeterminate = false;
                        } else{
                            checkbox.indeterminate = true;
                        }

                        var parentCheckbox = $(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                        if(parentCheckbox.length){
                            parentCheckbox[0].checked = true;

                            var parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var parentComponentRoles = objectProperty[parentComponentName];
                            if(Ember.typeOf(parentComponentRoles) !== 'array'){
                                parentComponentRoles = objectProperty[parentComponentName] = [];
                            }
                            if(!parentComponentRoles.contains(parentCheckbox[0].name)){
                                parentComponentRoles.push(parentCheckbox[0].name);
                                parentComponentRoles.sort();
                            }
                            checkedParentCheckboxes(parentCheckbox[0]);
                        }
                    }
                }

                function checkedChildrenCheckboxes(checkbox){
                    checkbox.indeterminate = false;
                    var childrenCheckboxes;
                    var childComponentName;
                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if(childrenCheckboxes.length){
                        for(i = 0; i < childrenCheckboxes.length; i++){
                            childrenCheckboxes[i].checked = true;
                            childrenCheckboxes[i].indeterminate = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            if(Ember.typeOf(objectProperty[childComponentName]) !== 'array'){
                                objectProperty[childComponentName] = [];
                            }
                            objectProperty[childComponentName].push(childrenCheckboxes[i].name);
                        }
                    }
                }

                function setParentCheckboxesIndeterminate(checkbox){
                    var childrenCheckboxes;
                    var childrenCheckboxesChecked = 0;
                    var parentComponentName;
                    var parentComponentRoles;
                    var parentCheckbox =$(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                    if(parentCheckbox.length){
                        if(parentCheckbox[0].checked){
                            childrenCheckboxes = $(parentCheckbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                            if(childrenCheckboxes.length){
                                for(var i = 0; i < childrenCheckboxes.length; i++){
                                    if(childrenCheckboxes[i].checked){
                                        ++childrenCheckboxesChecked;
                                    }
                                }
                            }
                            if(!childrenCheckboxesChecked){
                                parentCheckbox[0].checked = false;
                                parentCheckbox[0].indeterminate = false;
                                parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                                parentComponentRoles = objectProperty[parentComponentName];
                                if(Ember.typeOf(parentComponentRoles) !== 'array'){
                                    parentComponentRoles = objectProperty[parentComponentName] = [];
                                }
                                parentComponentRoles = objectProperty[parentComponentName] = parentComponentRoles.without(parentCheckbox[0].name);
                                if(!parentComponentRoles.length){
                                    delete objectProperty[parentComponentName];
                                }
                            } else{
                                parentCheckbox[0].indeterminate = true;
                            }
                        }
                        setParentCheckboxesIndeterminate(parentCheckbox[0]);
                    }
                }

                if(isChecked){
                    if(!componentRoles.contains(currentRole)){
                        componentRoles.push(currentRole);
                        componentRoles.sort();
                    }
                    checkedChildrenCheckboxes(checkbox);
                    checkedParentCheckboxes(checkbox);
                } else{
                    if(componentRoles.contains(currentRole)){
                        objectProperty[path] = componentRoles.without(currentRole);
                        if(!objectProperty[path].length){
                            delete objectProperty[path];
                        }
                    }

                    checkbox.indeterminate = false;

                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if(childrenCheckboxes.length){
                        for(i = 0; i < childrenCheckboxes.length; i++){
                            childrenCheckboxes[i].checked = false;
                            childrenCheckboxes[i].indeterminate = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var childComponentRoles = objectProperty[childComponentName];
                            if(Ember.typeOf(childComponentRoles) !== 'array'){
                                childComponentRoles = objectProperty[childComponentName] = [];
                            }
                            childComponentRoles = objectProperty[childComponentName] = childComponentRoles.without(childrenCheckboxes[i].name);
                            if(!childComponentRoles.length){
                                delete objectProperty[childComponentName];
                            }
                        }
                    }

                    setParentCheckboxesIndeterminate(checkbox);
                }
                if(JSON.stringify(objectProperty) === '{}'){
                    objectProperty = [];
                }
                object.set(this.get('meta.dataSource'), JSON.stringify(objectProperty));
            },

            didInsertElement: function(){
                var self = this;
                var $el = this.$();
                var property = this.get('objectProperty');

                var checkedInput = function(objectProperty, componentName){
                    var i;
                    var checkbox;
                    if(Ember.typeOf(objectProperty[componentName]) === 'array'){
                        for(i = 0; i < objectProperty[componentName].length; i++){
                            checkbox = $el.find('[data-permissions-component-path="' + componentName + '"]').find('.umi-permissions-role-checkbox').filter('[name="' + objectProperty[componentName][i] + '"]');
                            if(checkbox.length){
                                checkbox[0].checked = true;
                            }
                        }
                    }
                };

                function setCheckboxIndeterminate(checkbox){
                    if(checkbox.checked){
                        var childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        var checkedChildrenCheckboxes = 0;
                        for(var i = 0; i < childrenCheckboxes.length; i++){
                            if(childrenCheckboxes[i].checked){
                                checkedChildrenCheckboxes++;
                            }
                        }
                        if(checkedChildrenCheckboxes === childrenCheckboxes.length){
                            checkbox.indeterminate = false;
                        } else{
                            checkbox.indeterminate = true;
                        }
                    }
                }

                for(var key in property){
                    if(property.hasOwnProperty(key)){
                        checkedInput(property, key);
                    }
                }

                var $checkboxes = $el.find('.umi-permissions-role-checkbox');
                for(var i = 0; i < $checkboxes.length; i++){
                    setCheckboxIndeterminate($checkboxes[i]);
                }

                var accordion = $el.find('.accordion');
                accordion.each(function(index){
                    var triggerButton = $(accordion[index]).find('.accordion-navigation-button');
                    var triggerBlock = $(accordion[index]).find('.content');
                    triggerButton.on('click.umi.permissions.triggerButton', function(){
                        triggerBlock.toggleClass('active');
                        triggerButton.find('.icon').toggleClass('icon-right icon-bottom');
                    });
                });

                $el.on('click.umi.permissions', '.umi-permissions-role-button-expand', function(){
                    var component = $(this).closest('li').children('.umi-permissions-component');
                    component.toggleClass('expand');
                    $(this).find('.icon').toggleClass('icon-right icon-bottom');
                    component.find('.umi-permissions-component').removeClass('expand');
                    component.find('.umi-permissions-role-button-expand').find('.icon').addClass('icon-right').removeClass('icon-bottom');
                });

                $el.on('change.umi.permissions', '.umi-permissions-role-checkbox', function(){
                    var isChecked = this.checked;
                    var componentName = $(this).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                    self.setObjectProperty(this, componentName, isChecked);
                });
            },
            willDestroyElement: function(){
                var $el = this.$();
                $el.off('click.umi.permissions');
                $el.off('change.umi.permissions');
            }
        });

        UMI.PermissionsPartialView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['no-bullet', 'umi-permissions-role-list'],
            templateName: 'partials/permissions/partial'
        });
    };
});
define(
    'forms/elements/main',[
        'App',

        'partials/forms/elements/mixins',

        'partials/forms/elements/wysiwyg/main',
        'partials/forms/elements/select/main',
        'partials/forms/elements/multi-select/main',
        'partials/forms/elements/checkbox/main',
        'partials/forms/elements/radio/main',
        'partials/forms/elements/text/main',
        'partials/forms/elements/number/main',
        'partials/forms/elements/email/main',
        'partials/forms/elements/password/main',
        'partials/forms/elements/time/main',
        'partials/forms/elements/date/main',
        'partials/forms/elements/datetime/main',
        'partials/forms/elements/file/main',
        'partials/forms/elements/image/main',
        'partials/forms/elements/textarea/main',
        'partials/forms/elements/checkbox-group/main',
        'partials/forms/elements/color/main',
        'partials/forms/elements/permissions/main'
    ],
    function(
        UMI,

        mixins,

        wysiwygElement,
        selectElement,
        multiSelectElement,
        checkboxElement,
        radioElement,
        textElement,
        numberElement,
        emailElement,
        passwordElement,
        timeElement,
        dateElement,
        datetimeElement,
        fileElement,
        imageElement,
        textareaElement,
        checkboxGroupElement,
        colorElement,
        permissions
    ){
        

        return function(){
            mixins();

            wysiwygElement();
            selectElement();
            multiSelectElement();
            checkboxElement();
            radioElement();
            textElement();
            numberElement();
            emailElement();
            passwordElement();
            timeElement();
            dateElement();
            datetimeElement();
            fileElement();
            imageElement();
            textareaElement();
            checkboxGroupElement();
            colorElement();
            permissions();
        };
    }
);
define('partials/forms/partials/magellan/main',['App'], function(UMI){
    

    return function(){
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before', 's-unselectable'],
            focusId: null,
            elementView: Ember.View.extend({
                isFieldset: function(){
                    return this.get('content.type') === 'fieldset';
                }.property()
            }),
            buttonView: Ember.View.extend({
                tagName: 'a',
                classNameBindings: ['isFocus:focus'],
                isFocus: function(){
                    return this.get('model.id') === this.get('parentView.parentView.focusId');
                }.property('parentView.parentView.focusId'),
                click: function(){
                    var self = this;
                    var fieldset = document.getElementById('fieldset-' + this.get('model.id'));
                    $(fieldset).closest('.magellan-content').animate({'scrollTop': fieldset.parentNode.offsetTop - parseFloat(getComputedStyle(fieldset).marginTop)}, 0);
                    setTimeout(function(){
                        if(self.get('parentView.parentView.focusId') !== self.get('model.id')){
                            self.get('parentView.parentView').set('focusId', self.get('model.id'));
                        }
                    }, 10);
                }
            }),
            init: function(){
                var elements = this.get('elements');
                elements = elements.filter(function(item){
                    return item.type === 'fieldset';
                });
                this.set('focusId', elements.get('firstObject.id'));
            },
            didInsertElement: function(){
                var self = this;
                var scrollArea = $('.magellan-menu').parent().find('.magellan-content');//TODO: По хорошему нужно выбирать элемент через this.$()
                if(!scrollArea.length){
                    return;
                }
                scrollArea.on('scroll.umi.magellan', function(){
                    var scrollOffset = $(this).scrollTop();
                    var focusField;
                    var fieldset = $(this).find('fieldset');
                    var scrollElement;
                    for(var i = 0; i < fieldset.length; i++){
                        scrollElement = fieldset[i].parentNode.offsetTop;
                        if(scrollElement - parseFloat(getComputedStyle(fieldset[i]).marginTop) <= scrollOffset && scrollOffset <= scrollElement + fieldset[i].offsetHeight){
                            focusField = fieldset[i];
                        }
                    }
                    if(focusField){
                        self.set('focusId', focusField.id.replace(/^fieldset-/g, ''));
                    }
                });
            }
        });
    };
});
define('partials/forms/partials/submitToolbar/main',['App', 'toolbar'], function(UMI){
    

    return function(){
        UMI.SubmitToolbarView = Ember.View.extend({
            layoutName: 'partials/form/submitToolbar',
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: UMI.ToolbarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.splitButtonBehaviour.dropUp);
                    var behaviour = this.get('context.behaviour.name');
                    if(behaviour){
                        behaviour = UMI.splitButtonBehaviour.get(behaviour) || {};
                    } else{
                        behaviour = {};
                    }
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});

define(
    'forms/formBase/main',[
        'App',
        'partials/forms/partials/magellan/main',
        'partials/forms/partials/submitToolbar/main'
    ],
    function(UMI, magellan, submitToolbar){
        

        /**
         * Базовый тип формы.
         * @example
         * Объявление формы:
         *  {{render 'formBase' model}}
         */
        return function(){

            magellan();
            submitToolbar();

            UMI.FormControllerMixin = Ember.Mixin.create(UMI.i18nInterface, {
                dictionaryNamespace: 'form',
                localDictionary: function(){
                    var form = this.get('control') || {};
                    return form.i18n;
                }.property()
            });

            UMI.FormViewMixin = Ember.Mixin.create({
                /**
                 * Тип DOM элемента
                 * @property tagName
                 * @type String
                 * @default "form"
                 */
                tagName: 'form',
                submit: function(){
                    return false;
                },
                /**
                 * Проверяет наличие fieldset
                 * @method hasFieldset
                 * @return bool
                 */
                hasFieldset: function(){
                    return this.get('context.control.meta.elements').isAny('type', 'fieldset');
                }.property('context.control.meta'),

                elementView: Ember.View.extend({
                    classNameBindings: ['isField'],
                    isFieldset: function(){
                        return this.get('content.type') === 'fieldset';
                    }.property(),
                    isExpanded: true,
                    isField: function(){
                        if(!this.get('isFieldset')){
                            return this.gridType();
                        }
                    }.property(),
                    /**
                     * @method gridType
                     */
                    gridType: function(){
                        var wideElements = ['wysiwyg', 'permissions'];
                        var widthClass = 'large-4 small-12';
                        if(wideElements.contains(this.get('content.type'))){
                            widthClass = 'small-12';
                        }
                        return 'umi-columns ' + widthClass;
                    },

                    actions: {
                        expand: function(){
                            this.toggleProperty('isExpanded');
                        }
                    }
                })
            });

            UMI.FieldMixin = Ember.Mixin.create({
                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}</span></div>{{yield}}'),
                template: function(){
                    var meta;
                    var template;
                    try{
                        meta = this.get('meta');
                        template = this.get(Ember.String.camelize(meta.type) + 'Template') || '';
                        if(!template){
                            throw new Error('Для поля с типом ' + meta.type + ' не реализован шаблонный метод.');
                        }
                    } catch(error){
                        this.get('controller').send('backgroundError', error);// TODO: при первой загрузке сообщения не всплывают.
                    } finally{
                        template = this.extendTemplate(template);
                        return Ember.Handlebars.compile(template);
                    }
                }.property(),
                /**
                 * Метод используется декораторами для расширения базового шаблона.
                 * @method extendTemplate
                 * @param template
                 * @returns String
                 */
                extendTemplate: function(template){
                    return template;
                },

                textTemplate: function(){
                    return '{{view "textElement" object=view.object meta=view.meta}}';
                }.property(),

                emailTemplate: function(){
                    return '{{view "emailElement" object=view.object meta=view.meta}}';
                }.property(),

                passwordTemplate: function(){
                    return '{{view "passwordElement" object=view.object meta=view.meta}}';
                }.property(),

                numberTemplate: function(){
                    return '{{view "numberElement" object=view.object meta=view.meta}}';
                }.property(),

                colorTemplate: function(){
                    return '{{view "colorElement" object=view.object meta=view.meta}}';
                }.property(),

                timeTemplate: function(){
                    return '{{time-element object=view.object meta=view.meta}}';
                }.property(),

                dateTemplate: function(){
                    return '{{view "dateElement" object=view.object meta=view.meta}}';
                }.property(),

                datetimeTemplate: function(){
                    return '{{view "dateTimeElement" object=view.object meta=view.meta}}';
                }.property(),

                fileTemplate: function(){
                    return '{{view "fileElement" object=view.object meta=view.meta}}';
                }.property(),

                imageTemplate: function(){
                    return '{{view "imageElement" object=view.object meta=view.meta}}';
                }.property(),

                textareaTemplate: function(){
                    return '{{view "textareaElement" object=view.object meta=view.meta}}';
                }.property(),

                wysiwygTemplate: function(){
                    return '{{view "htmlEditor" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "select" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                multiSelectTemplate: function(){
                    return '{{view "multiSelect" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                checkboxTemplate: function(){
                    return '{{view "checkboxElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function(){
                    return '{{view "checkboxGroupElement" object=view.object meta=view.meta}}';
                }.property(),

                radioTemplate: function(){
                    return '{{view "radioElement" object=view.object meta=view.meta}}';
                }.property()
            });

            UMI.FormBaseController = Ember.ObjectController.extend(UMI.FormControllerMixin, {});

            UMI.FormBaseView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/form',

                /**
                 * Классы view
                 * @property classNames
                 * @type Array
                 */
                classNames: ['s-margin-clear', 's-full-height', 'umi-form-control'],

                attributeBindings: ['action'],

                action: function(){
                    return this.get('context.control.meta.attributes.action');
                }.property('context.control.meta.attributes.action'),

                actions: {
                    submit: function(handler){
                        var self = this;
                        if(handler){
                            handler.addClass('loading');
                        }
                        var data = this.$().serialize();
                        $.post(self.get('action'), data).then(function(results){
                            var meta = Ember.get(results, 'result.save');
                            var context = self.get('context');
                            Ember.set(context, 'control.meta', meta);
                            handler.removeClass('loading');
                            var params = {type: 'success', 'content': 'Сохранено.'};
                            UMI.notification.create(params);
                        });
                    }
                },

                submitToolbarView: UMI.SubmitToolbarView.extend({
                    elementView: UMI.ToolbarElementView.extend({
                        buttonView: function(){
                            var button = UMI.ButtonView.extend();
                            if(this.get('context.behaviour.name') === 'save'){
                                button.reopen({
                                    actions: {
                                        save: function(){
                                            this.get('parentView.parentView.parentView').send('submit', this.$());
                                        }
                                    }
                                });
                            }
                            return button;
                        }.property()
                    })
                })
            });

            UMI.FieldBaseView = Ember.View.extend(UMI.FieldMixin, {});
        };
    }
);
define('forms/formControl/main',['App'],

    function(UMI){
        

        return function(){

            UMI.FormControlController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                needs: ['component'],

                settings: function(){
                    var settings = {};
                    settings = this.get('controllers.component.settings');
                    return settings;
                }.property()
            });

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control']
            });

            UMI.FieldFormControlView = Ember.View.extend(UMI.FieldMixin, {
                classNameBindings: ['isError:error'],

                isError: function(){
                    var dataSource = this.get('meta.dataSource');
                    return !!this.get('object.validErrors.' + dataSource);
                }.property('object.validErrors'),

                wysiwygTemplate: function(){
                    return '{{view "htmlEditorCollection" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "selectCollection" object=view.object meta=view.meta}}';
                }.property(),

                checkboxTemplate: function(){
                    return '{{view "checkboxCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function(){
                    return '{{view "checkboxGroupCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                permissionsTemplate: function(){
                    return '{{view "permissions" object=object meta=view.meta}}';
                }.property()
            });
        };
    }
);
define(
    'forms/main',['App', './elements/main', './formBase/main', './formControl/main'],
    function(UMI, elements, formBase, formControl){
        

        elements();
        formBase();
        formControl();
    }
);
define('forms', ['forms/main'], function (main) { return main; });

define('notification/main',['App'], function(UMI){
    

    UMI.Notification = Ember.Object.extend({
        settings: {
            'type': 'secondary',
            'title': 'UMI.CMS',
            'content': '',
            'close': true,
            'duration': 3000
        },
        create: function(params){
            var settings = this.get('settings');
            if(params){
                for(var param in params){
                    if(params.hasOwnProperty(param)){
                        settings[param] = params[param];
                    }
                }
            }
            settings.id = UMI.notificationList.incrementProperty('notificationId');
            var data = UMI.notificationList.get('content');
            Ember.run.next(this, function(){data.pushObject(Ember.Object.create(settings));});
        },
        removeAll: function(){
            UMI.notificationList.set('content', []);
        }
    });

    UMI.notification = UMI.Notification.create({});

    UMI.NotificationList = Ember.ArrayController.extend({
        content: [],
        sortProperties: ['id'],
        sortAscending: true,
        notificationId: 0,
        closeAll: false,
        itemCount: function(){
            if(this.get('content.length') > 1 && !this.get('closeAll')){
                this.set('closeAll', true);
                this.get('content').pushObject(
                    Ember.Object.create({
                        id: 'closeAll',
                        type: 'secondary',
                        content: 'Закрыть все'
                    })
                );
            }
            if(this.get('content.length') <= 2 && this.get('closeAll')){
                var object = this.get('content').findBy('id', 'closeAll');
                this.get('content').removeObject(object);
                this.set('closeAll', false);
            }
        }.observes('content.length')
    });

    UMI.notificationList = UMI.NotificationList.create({});

    UMI.AlertBox = Ember.View.extend({
        classNames: ['alert-box'],
        classNameBindings: ['content.type'],
        layoutName: 'partials/alert-box',
        didInsertElement: function(){
            var duration = this.get('content.duration');
            if(duration){
                Ember.run.later(this, function(){
                    //this.$().slideDown();
                    var id = this.get('content.id');
                    var content = this.get('controller.content') || [];
                    var object = content.findBy('id', id);
                    content.removeObject(object);
                }, duration);
            }
        },
        actions: {
            close: function(){
                var content = this.get('controller.content');
                content.removeObject(this.get('content'));
            }
        }
    });

    UMI.NotificationListView = Ember.CollectionView.extend({
        tagName: 'div',
        classNames: ['umi-alert-wrapper'],
        itemViewClass: UMI.AlertBox,
        contentBinding: 'controller.content',
        controller: UMI.notificationList
    });
});
define('notification', ['notification/main'], function (main) { return main; });

define('dialog/main',['App'], function(UMI){
    

    UMI.DialogController = Ember.ObjectController.extend({
        deferred: null,
        open: function(params){
            this.set('deferred', Ember.RSVP.defer());
            var deferred = this.get('deferred');
            if(Ember.get(params, 'proposeRemember')){
                //проверить присутсвие запомненного действия
            }
            this.set('model', Ember.Object.create(params));
            return deferred.promise;
        },
        actions: {
            confirm: function(){
                this.set('model', null);
                var deferred = this.get('deferred');
                deferred.resolve('loaded');
            },
            close: function(){
                this.set('model', null);
                var deferred = this.get('deferred');
                deferred.reject('reject');
            }
        }
    });

    UMI.dialog = UMI.DialogController.create();

    UMI.DialogView = Ember.View.extend({
        layoutName: 'partials/dialog-layout',
        templateName: 'partials/dialog-template',
        modelBinding: 'controller.model',
        controller: UMI.dialog,
        showDialog: function(){
            if(this.get('model')){
                this.append();
            } else if(this.isVisible){
                this.remove();
            }
        }.observes('model'),
        didInsertElement: function(){
            var element = this.$();
            var dialog = element.children('.umi-dialog');
            var screenSize = $(document).height();
            var dialogMarginTop = screenSize > dialog[0].offsetHeight ? - dialog[0].offsetHeight/2 : - dialog[0].offsetHeight/2 + dialog[0].offsetHeight - screenSize;
            dialog.css({'marginTop': dialogMarginTop});
            dialog.addClass('visible');
        },
        actions: {
            confirm: function(){
                var element = this.$();
                var dialog = element.children('.umi-dialog');
                dialog.removeClass('visible');
                return this.get('controller').send('confirm');
            },
            close: function(){
                var element = this.$();
                var dialog = element.children('.umi-dialog');
                dialog.removeClass('visible');
                return this.get('controller').send('close');
            }
        }
    });

    var dialogView = UMI.DialogView.create();
});
define('dialog', ['dialog/main'], function (main) { return main; });

define('popup/view',['App'], function(UMI){
    

    return function(){

        UMI.TableControlColumnSelectorPopupView = Ember.View.extend({
            templateName: 'tableControlColumnSelectorPopup',
            classNames: ['umi-table-control-column-selector-popup'],

            init: function(){
                this.get('parentView').setProperties({
                    'title': 'Выбор колонок в таблице',
                    'width': 400,
                    'height': 300,
                    'contentOverflow': ['overflow', 'scroll']
                });
            },

            didInsertElement: function(){
                this.$().find('li').mousedown(function(){
                    $(this).find('input').click();
                });

                if(window.pageYOffset || document.documentElement.scrollTop){

                }
            }
        });

        UMI.PopupView = Ember.View.extend({
            //Параметры приходящие из childView
                contentParams: {},

            classNames: ['umi-popup'],
            title: '',
            width: 600,
            height: 400,
            contentOverflow: ['overflow', 'hidden'],
            blur: false,
            fade: true,
            drag: true,
            resize: true,
            layoutName: 'partials/popup',

            checkContentParams: function(){
                this.get('object').set(this.get('meta.dataSource'), this.contentParams.fileInfo.path);
            }.observes('contentParams'),

            template: function(){
                var template;
                var templateName = this.get('popupType');

                //TODO Разнести по файлам аналогично elements?
                switch(templateName){
                    case 'fileManager':                         template = '{{view "fileManager" object=view.object meta=view.meta}}'; break;
                    case 'tableControlColumnSelectorPopup':     template = '{{view "tableControlColumnSelectorPopup"}}'; break;
                    default:                                    template = 'Шаблон не обнаружен в системе';
                }
                return Ember.Handlebars.compile(template);
            }.property('popupType'),

            didInsertElement: function(){
                if(this.blur){this.addBlur()}
                if(this.fade){this.fadeIn()}
                if(this.drag){this.allowDrag()}
                if(this.resize){this.allowResize()}
                if(this.contentOverflow !== 'hidden'){
                    $('.umi-popup-content').css(this.contentOverflow[0], this.contentOverflow[1]);
                }
                this.setSize();
            },

            actions: {
                closePopup: function(){
                    this.removeBlur();
                    this.remove();
                }
            },

            setSize: function(){
                this.$().width(this.width);
                this.$().height(this.height);
            },

            fadeIn: function(){
                var self = this;
                $('body').append('<div class="umi-popup-visible-overlay"></div>');
                $('body').on('click.umi.popup', '.umi-popup-visible-overlay', function(){
                    self.send('closePopup');
                    $('body').off('click.umi.popup');
                });
            },

            addBlur: function(){
                $('.umi-header').addClass('s-blur');
                $('.umi-content').addClass('s-blur');
                $('body').append('<div class="umi-popup-invisible-overlay"></div>');
            },

            removeBlur: function(){
                $('.umi-header').removeClass('s-blur');
                $('.umi-content').removeClass('s-blur');
                $('.umi-popup-invisible-overlay').remove();
                $('.umi-popup-visible-overlay').remove();
            },

            allowResize: function(){
                var that = this;
                $('.umi-popup-resizer').show();
                $('body').on('mousedown', '.umi-popup-resizer', function(event){
                    if(event.button === 0){
                        $('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var posX = $('.umi-popup').offset().left;
                        var posY = $('.umi-popup').offset().top;

                        $('html').addClass('s-unselectable');
                        $('html').mousemove(function(event){
                            var w = event.pageX - posX;
                            var h = event.pageY - posY;

                            if(w < that.get('width')){w = that.get('width')}
                            if(h < that.get('height')){h = that.get('height')}

                            $('.umi-popup').css({width: w, height: h});

                            $('html').on('mouseup', function(){
                                $('html').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                $('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            allowDrag: function(){
                var that = this;
                $('.umi-popup-header').css({'cursor':'move'});
                $('body').on('mousedown', '.umi-popup-header', function(event){
                    $('.umi-popup').css('z-index', '10000');
                    $(this).parent().css('z-index', '10001');

                    var $that = $(this);
                    if(event.button === 0){
                        $('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var clickX = event.pageX - $(this).offset().left;
                        var clickY = event.pageY - $(this).offset().top;

                        var windowHeight = $(window).height() - 34;
                        var windowWidth = $(window).width() - 34;


                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event){
                            var x = event.pageX - clickX;
                            var y = event.pageY - clickY;

                            //Запрет на вывод Popup за пределы экрана
                                if(y <= 0){return}
                                if(y >= windowHeight){return}
                                if(x <= 68 - that.width){return} // 68 - чтобы не только крестик оставался, но и было за что без опаски схватить
                                if(x >= windowWidth){return}

                            $that.parent().offset({left: x, top: y});

                            $('body').on('mouseup', function(){
                                $('body').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                $('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            }
        });
    };
});
define('popup/main',[
    './view',
    'App'
], function(
    view
){
    
    view();
});
define('popup', ['popup/main'], function (main) { return main; });

define('table/view',['App'], function(UMI){
    

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'partials/table',
            classNames: ['umi-table-ajax'],
            headers: [],
            objectId: [],
            data: [],

            didInsertElement: function(){
                var that = this;

                //Получаем список счётчиков
                (function getCounters(){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/counters",
                        data: {},
                        cache: false,

                        beforeSend: function(){
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; overflow: hidden; z-index: 1; padding: 30px; width: 100%; height: 100%; background: #FFFFFF;"><i class="animate animate-loader-40"></i><h3>Идёт загрузка данных...</h3></div>';
                            });
                        },

                        success: function(response){
                            $('.umi-loader').remove();
//                            if(!response){
//                                $('.umi-component').css({'position':'relative'}).append(function(){
//                                    return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Данные отсутствуют</h3></div>';
//                                });
//                            }

                            var headers = [];
                            headers.push(response.result.counters.labels.name, response.result.counters.labels.site, response.result.counters.labels['code_status']);

                            var rows = response.result.counters.counters;
                            var rowsLength = rows.length;
                            var result = [];

                            for(var i = 0; i < rowsLength; i++){
                                result.push([rows[i].id, rows[i].site, rows[i].name, rows[i]['code_status']]);
                            }
                            renderCounters(headers, result);
                        },

                        error: function(code){
                            $('.umi-loader').remove();
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Не удалось загрузить данные</h3></div>';
                            });
                        }
                    });
                })();


                //Выводим таблицу со списком счётчиков
                //headers - массив
                //rows - двумерный массив
                function renderCounters(headers, rows){

                    //Заносим заголовки в переменную для шаблонизатора
                    that.set('headers', headers);

                    //Заносим содержимое таблицы с удалением первой колонки (id) в переменную шаблонизатора
                    var rowsLength = rows.length;
                    var objectId = [];
                    that.set('data', rows);
                    that.set('objectId', objectId);
//                    console.log(that.get('objectId'));
                }

                $('.umi-table-ajax').on('click.umi.table','.umi-table-ajax-tr',function(){
                    var counterId = $(this).data('object-id');
                    that.get('controller').transitionToRoute('context', counterId);
                });

            },

            willDestroyElement: function(){
                $(window).off('.umi.table');
            },

            row: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-ajax-tr'],
                attributeBindings: ['objectId:data-object-id'],
                objectId: function(){
                    return this.get('object')[0];
                }.property('object'),
                cell: function(){
                    var object = this.get('object');
                    object.shift(0);
                    return object;
                }.property('object')
            })

        });
    };
});
define('table/main',[
    './view',
    'App'
], function(view){
    

    view();
});
define('table', ['table/main'], function (main) { return main; });

define('sideMenu/main',['App'],
    function(UMI){
        

        UMI.SideMenuController = Ember.ObjectController.extend({
            needs: ['component'],
            objects: function(){
                return this.get('controllers.component.dataSource.objects');
            }.property('model')
        });

        UMI.SideMenuView = Ember.View.extend({
            layoutName: 'partials/sideMenu'
        });
    }
);
define('sideMenu', ['sideMenu/main'], function (main) { return main; });

define(
    'application/main',[
        'App',
        'topBar',
        'divider',
        'dock',
        'toolbar',
        'tableControl',
        'fileManager',
        'treeSimple',
        'tree',
        'forms',
        'notification',
        'dialog',
        'popup',
        'DS',
        'table',
        'sideMenu'
    ],
    function(UMI){
        
        return function(){
             UMI.advanceReadiness();
        };
    }
);
require.config({
    baseUrl: '/resources',

    paths: {
        text:       'libs/requirejs-text/text',

        App:        'application/application',
        jquery:     'libs/jquery/dist/jquery',
        jqueryUI:   'libs/jquery-ui/jquery-ui.min',
        Modernizr:  'libs/modernizr/modernizr',
        Handlebars: 'libs/handlebars/handlebars',
        Ember:      'libs/ember/ember',
        DS:         'libs/ember-data/ember-data',
        iscroll:    'libsStatic/iscroll-probe-5.1.1',
        ckEditor:   'libs/ckeditor/ckeditor',
        timepicker: 'libs/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon',
        moment:     'libs/momentjs/min/moment-with-langs.min',
        elFinder:   'libsStatic/elFinder'
    },

    shim: {
        Modernizr:  {exports: 'Modernizr'},
        jquery:     {exports: 'jQuery'},
        jqueryUI:   {exports: 'jQuery', deps: ['jquery']},
        elFinder:   {exports: 'elFinder',   deps: ['jquery', 'jqueryUI']},
        Ember:      {exports: 'Ember',      deps: ['Handlebars', 'jquery']},
        DS:         {exports: 'DS',         deps: ['Ember']},
        ckEditor:   {exports: 'ckEditor'},
        timepicker: {exports: 'timepicker', deps: ['jquery', 'jqueryUI']}
    },

    packages: [
        {name: 'accordion',         location: "partials/accordion"},
        {name: 'chartControl',      location: "partials/chartControl"},
        {name: 'dialog',            location: "partials/dialog"},
        {name: 'divider',            location: "partials/divider"},
        {name: 'dock',              location: "partials/dock"},
        {name: 'fileManager',       location: "partials/fileManager"},
        {name: 'forms',              location: "partials/forms"},
        {name: 'notification',      location: "partials/notification"},
        {name: 'popup',             location: "partials/popup"},
        {name: 'search',            location: "partials/search"},
        {name: 'megaIndex',         location: "partials/seo/megaIndex"},
        {name: 'sideMenu',          location: "partials/sideMenu"},
        {name: 'yandexWebmaster',   location: "partials/seo/yandexWebmaster"},
        {name: 'table',             location: "partials/table"},
        {name: 'tableControl',      location: "partials/tableControl"},
        {name: 'toolbar',           location: "partials/toolbar"},
        {name: 'topBar',            location: "partials/topBar"},
        {name: 'tree',              location: "partials/tree"},
        {name: 'treeSimple',        location: "partials/treeSimple"}
    ]
});

require(['jquery'], function(){
    

    var deffer = $.get(window.UmiSettings.authUrl);

    deffer.done(function(data){
        var objectMerge = function(objectBase, objectProperty){
            for(var key in objectProperty){
                if(objectProperty.hasOwnProperty(key)){
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        if(data.result){
            objectMerge(window.UmiSettings, data.result.auth);
        }
        require(['application/main'], function(application){
            application();
        });
    });

    deffer.fail(function(error){
        require(['auth/main'], function(auth){
            auth({accessError: error});
        });
    });
});

define("main", function(){});

