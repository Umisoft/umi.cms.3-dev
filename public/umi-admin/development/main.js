define('application/config',[], function() {
    

    return function(UMI) {
        UMI.config = {
            iScroll: {
                scrollX: true,
                probeType: 3,
                mouseWheel: true,
                scrollbars: true,
                bounce: false,
                click: false,
                freeScroll: false,
                keyBindings: true,
                interactiveScrollbars: true,
                fadeScrollbars: true,
                disableMouse: false
            },

            elFinder: {
                url: '/admin/api/files/manager/action/connector',
                lang: 'ru',

                closeOnGetFileCallback: true,

                uiOptions: {
                    toolbar: [
                        ['back', 'forward'],
                        ['reload'],
                        ['getfile'],
                        // ['home', 'up'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['download'],
                        //                      ['info'], ['quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit'],
                        //                      ['extract', 'archive'], ['search'],
                        ['view'],
                        ['help']
                    ]
                }
            }
        };

        UMI.config.CkEditor = function() {
            var config = {};
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            config.toolbarGroups = [
                { name: 'clipboard', groups: ['clipboard', 'undo'] },
                { name: 'editing', groups: ['find', 'selection'] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document', groups: ['mode', 'document', 'doctools'] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] },
                { name: 'styles' },
                { name: 'colors' }
            ];

            config.removeButtons = 'Underline,Subscript,Superscript';

            config.format_tags = 'p;h1;h2;h3;pre';

            config.removeDialogTabs = 'image:advanced;link:advanced';

            var locale = Ember.get(window, 'UmiSettings.locale') || '';
            locale = locale.split('-')[0];
            var allowedLocale = ['en', 'ru', 'de', 'es', 'it', 'uk'];
            if (allowedLocale.contains(locale)) {
                config.language = locale;
            } else {
                config.language = 'en';
            }

            config.height = '450px';

            config.baseFloatZIndex = 200;

            config.image_previewText = ' ';

            config.baseHref = Ember.get(window, 'UmiSettings.projectAssetsUrl');

            return config;
        };
    };
});
define('application/utils',['Modernizr'], function(Modernizr) {
    

    return function(UMI) {
        /**
         * Umi Utilities Class.
         * @class Utils
         * @static
         */
        UMI.Utils = {};

        UMI.Utils.htmlEncode = function(str) {
            str = str + "";
            return str.replace(/[&<>"']/g, function($0) {
                return "&" + {"&": "amp", "<": "lt", ">": "gt", '"': "quot", "'": "#39"}[$0] + ";";
            });
        };

        UMI.Utils.replacePlaceholder = function(object, pattern) {
            var deserialize;
            deserialize = pattern.replace(/{\w+}/g, function(key) {
                if (key) {
                    key = key.slice(1, -1);
                }
                return Ember.get(object, key) || key;//TODO: error handling
            });
            return deserialize;
        };

        UMI.Utils.objectsMerge = function(objectBase, objectProperty) {
            Ember.warn('Incorrect type of arguments. ObjectsMerge method expects arguments of type "object"', Ember.typeOf(objectBase) === 'object' && Ember.typeOf(objectProperty) === 'object');
            for (var key in objectProperty) {
                if (objectProperty.hasOwnProperty(key)) {
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        UMI.Utils.getStringValue = function(prop) {
            var property;
            var properties;
            var value;
            switch (Ember.typeOf(prop)) {
                case 'string':
                    value = prop;
                    break;
                case 'array':
                    value = prop.join(',');
                    break;
                case 'object':
                    properties = [];
                    for (property in prop) {
                        if (prop.hasOwnProperty(property)) {
                            properties.push(UMI.Utils.getStringValue(prop[property]));
                        }
                    }
                    value = properties;
                    break;
            }
            return value;
        };

        /**
         * Local Storage
         */
        UMI.Utils.LS = {
            store: localStorage,
            init: function() {
                if (Modernizr.localstorage) {
                    if (!localStorage.getItem("UMI")) {
                        localStorage.setItem("UMI", JSON.stringify({}));
                    }
                } else {
                    //TODO: Не обрабатывается сутуация когда Local Storage не поддерживается
                    this.store = {'UMI': JSON.stringify({})};
                }
            },

            get: function(key) {
                var data = JSON.parse(this.store.UMI);
                return Ember.get(data, key);
            },

            set: function(keyPath, value) {
                var data = JSON.parse(this.store.UMI);
                var keys = keyPath.split('.');
                var i = 0;
                var setNestedProperty = function getNestedProperty(obj, key, value) {
                    if (!obj.hasOwnProperty(key)) {
                        obj[key] = {};
                    }
                    if (i < keys.length - 1) {
                        i++;
                        getNestedProperty(obj[key], keys[i], value);
                    } else {
                        obj[key] = value;
                    }
                };
                setNestedProperty(data, keys[0], value);
                if (Modernizr.localstorage) {
                    this.store.setItem('UMI', JSON.stringify(data));
                } else {
                    this.store.UMI = JSON.stringify(data);
                }
            }
        };

        Ember.Handlebars.registerHelper('filterClassName', function(value, options) {
            value = Ember.Handlebars.helpers.unbound.apply(this, [value, options]);
            value = value.replace(/\./g, '__');//TODO: replace all deprecated symbols
            return value;
        });

        UMI.Utils.LS.init();

        //Проверка браузера на мобильность
        window.mobileDetection = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
            }
        };

        if (mobileDetection.any()) {
            console.log('mobile');
        }

        //Проверка браузера на современность - проверка поддержки calc()
        Modernizr.addTest('csscalc', function() {
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
define('application/i18n',[], function() {
    
    return function(UMI) {
        Ember.Handlebars.registerHelper('i18n', function(label, namespace) {
                if (Ember.typeOf(namespace) !== 'string') {
                    namespace = undefined;
                }
                var translateLabel = UMI.i18n.getTranslate(label, namespace);
                return translateLabel ? translateLabel : label;
            });

        UMI.i18n = Ember.Object.extend({
            dictionary: {},
            setDictionary: function(translate, namespace) {
                var dictionary = this.get('dictionary');
                var namespaceDictionary;
                if (namespace && namespace) {
                    namespaceDictionary = Ember.typeOf(dictionary[namespace]) === 'object' ? dictionary[namespace] : {};
                    Ember.set(dictionary, namespace, namespaceDictionary);
                }
                for (var key in translate) {
                    if (translate.hasOwnProperty(key)) {
                        if (namespace) {
                            Ember.set(Ember.get(dictionary, namespace), key, translate[key]);
                        } else {
                            Ember.set(dictionary, key, translate[key]);
                        }

                    }
                }
            },
            getTranslate: function(label, componentPath) {
                var path = 'dictionary.' + (componentPath ? componentPath + '.' : '') + label;
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
            setDictionary: function() {
                UMI.i18n.setDictionary(this.get('localDictionary'), this.get('dictionaryNamespace'));
            },
            init: function() {
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

Ember.TEMPLATES["UMI/getBacklinks"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "backlinksTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/host"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaHostTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/indexed"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaIndexesTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/simpleForm"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "formBase", "model", options) : helperMissing.call(depth0, "render", "formBase", "model", options))));
  
});

Ember.TEMPLATES["UMI/simpleTable"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCounters", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/siteAnalyze"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "siteAnalyzeTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/tops"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaTopsTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/update"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "updateLayout", {hash:{
    'data': ("model")
  },hashTypes:{'data': "ID"},hashContexts:{'data': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/errors"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <p>");
  stack1 = helpers._triageMustache.call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</p> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"text-left\"> <code>");
  stack1 = helpers._triageMustache.call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</code> </div> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"alert-box error\"> <ul> ");
  stack1 = helpers.each.call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li>");
  stack1 = helpers._triageMustache.call(depth0, "error", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</li> ");
  return buffer;
  }

  data.buffer.push("<div class=\"umi-component s-full-height\"> <div class=\"row\"> <div class=\"small-10 columns small-centered text-center\"> <p></p> <div>  <h2> ");
  stack1 = helpers._triageMustache.call(depth0, "title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(". </h2>  ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
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

Ember.TEMPLATES["UMI/partials/dialog-layout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-overlay\"></div> <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-dialog model.type")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
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
  data.buffer.push(" class=\"close\"><i class=\"icon white icon-close\"></i></a> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "model", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/dialog-template"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-dialog-header\">");
  stack1 = helpers._triageMustache.call(depth0, "model.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "model.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-dialog-buttons\"> ");
  stack1 = helpers['if'].call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.reject", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button primary left\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "confirm", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</button> ");
  return buffer;
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button secondary right\" ");
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
  data.buffer.push(" <div class=\"umi-dialog-section\"> ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasButtons", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
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
    'class': ("dock-module dropdown"),
    'data-dropdown': (""),
    'data-options': ("selectorById: false; isHover: true; buttonSelector: .dropdown;")
  },hashTypes:{'class': "STRING",'data-dropdown': "STRING",'data-options': "STRING"},hashContexts:{'class': depth0,'data-dropdown': depth0,'data-options': depth0},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "module", "module.name", options) : helperMissing.call(depth0, "link-to", "module", "module.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"f-dropdown\" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-dock-module-icon umi-dock-module-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.module.name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></div> <span>");
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
  stack1 = helpers.each.call(depth0, "module", "in", "sortedModules", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
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
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div>");
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

Ember.TEMPLATES["UMI/partials/objectRelationElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/objectRelationLayout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "sideBarControl", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-divider-right sideBarControl::wide")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <div class=\"columns small-12 s-padding-clear s-full-height\"> ");
  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "tableControlShared", "tableControlSettings", options) : helperMissing.call(depth0, "render", "tableControlShared", "tableControlSettings", options))));
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"umi-divider-left\"> <div class=\"umi-divider-left-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.parentView.sideMenu", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> <div class=\"umi-divider\"></div> </div> <div class=\"umi-left-bottom-panel s-unselectable\"> <a href=\"javascript:void(0)\" class=\"button white square umi-divider-left-toggle\"> <i class=\"icon icon-left\"></i> </a> </div> ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "divider", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/objectRelationLayout/sideMenu"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {hash:{
    'item': ("item")
  },hashTypes:{'item': "ID"},hashContexts:{'item': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <a href=\"javascript:void(0)\">");
  stack1 = helpers._triageMustache.call(depth0, "item.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</a> ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "item", "in", "collections", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
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

Ember.TEMPLATES["UMI/partials/singleCollectionObjectRelationElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/textareaElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.textareaView", {hash:{
    'meta': ("view.meta"),
    'object': ("view.object")
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
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(14, program14, data),fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
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
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("element")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <br/> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("formElement")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program16(depth0,data) {
  
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
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
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
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(14, program14, data),fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
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
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program16(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "submitToolbar", {hash:{
    'elements': ("control.submitToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "control.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> ");
  stack1 = helpers['if'].call(depth0, "control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
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

Ember.TEMPLATES["UMI/partials/alert-box/close-all"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  
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
  data.buffer.push(" class=\"close\"><i class=\"icon icon-close white\"></i></span> ");
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

Ember.TEMPLATES["UMI/partials/popup/fileManager"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fileManager", {hash:{
    'templateParams': ("templateParams")
  },hashTypes:{'templateParams': "ID"},hashContexts:{'templateParams': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
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
  data.buffer.push("> <i class=\"icon white icon-close\"></i> </a> </div> <div class=\"umi-popup-content\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-popup-resizer\"></div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/popup/objectRelation"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "objectRelationLayout", "templateParams", options) : helperMissing.call(depth0, "render", "objectRelationLayout", "templateParams", options))));
  
});

Ember.TEMPLATES["UMI/partials/popup/singleCollectionObjectRelation"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "singleCollectionObjectRelationLayout", "templateParams", options) : helperMissing.call(depth0, "render", "singleCollectionObjectRelationLayout", "templateParams", options))));
  
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
  var stack1, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <p></p> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "view.error", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h3> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.paginationView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <div class=\"umi-table-header-wrap\"> <table class=\"umi-table-header\"> <tbody> <tr class=\"umi-table-tr\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-header-shadow\"></div> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-content\"> <tbody> <tr class=\"umi-table-content-sizer\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tr> ");
  stack1 = helpers.each.call(depth0, "row", "in", "view.visibleRows", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(11, program11, data),fn:self.program(8, program8, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"> <div class=\"umi-table-td-relative-wrap\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> <div class=\"umi-table-header-column-resizer\"></div> </div> </td> ");
  return buffer;
  }

function program6(depth0,data) {
  
  
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"></td> ");
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> ");
  stack1 = helpers.each.call(depth0, "property", "in", "row", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> ");
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-td\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "property", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> </td> ");
  return buffer;
  }

function program11(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> <td> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "No data", "table", options) : helperMissing.call(depth0, "i18n", "No data", "table", options))));
  data.buffer.push(" </td> </tr> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.error", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/table/toolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  }

function program3(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  }

  data.buffer.push("<div class=\"right umi-table-control-pagination\"> <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Rows on page", "table", options) : helperMissing.call(depth0, "i18n", "Rows on page", "table", options))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> </div> ");
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
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(15, program15, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
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
  
  var buffer = '', stack1;
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-table-control-column-fixed-cell object.active::umi-inactive isSelected:selected")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-objectId=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "object.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers['if'].call(depth0, "controller.parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(20, program20, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program20(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "tableControlContextToolbar", {hash:{
    'elements': ("controller.parentController.contextToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(21, program21, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program21(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(22, program22, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program22(depth0,data) {
  
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
  },hashTypes:{'itemController': "STRING"},hashContexts:{'itemController': depth0},inverse:self.noop,fn:self.program(19, program19, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
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
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.iconClass")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton/backupList"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.iconClass")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"f-dropdown-content-header\">");
  stack1 = helpers._triageMustache.call(depth0, "view.button.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.backupList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(18, program18, data),fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <tr ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': ("isActive::selectable")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "applyBackup", "", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <td> ");
  stack1 = helpers['if'].call(depth0, "isActive", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(12, program12, data),fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "created.date", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </td> <td> ");
  stack1 = helpers['if'].call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(16, program16, data),fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </td> </tr> ");
  return buffer;
  }
function program10(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-accept\"></i> </button> ");
  }

function program12(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-renew\"></i> </button> ");
  }

function program14(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program16(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "User name", "toolbar:dropdownButton", options) : helperMissing.call(depth0, "i18n", "User name", "toolbar:dropdownButton", options))));
  data.buffer.push(" ");
  return buffer;
  }

function program18(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <tr> <td colspan=\"2\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.noBackupsLabel", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </td> </tr> ");
  return buffer;
  }

function program20(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <tr> <td colspan=\"2\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Loading", options) : helperMissing.call(depth0, "i18n", "Loading", options))));
  data.buffer.push("..</td> </tr> ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "view.buttonView", {hash:{
    'meta': ("view.meta")
  },hashTypes:{'meta': "ID"},hashContexts:{'meta': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'id': ("view.dropdownId"),
    'class': (":f-dropdown view.dropdownClassName")
  },hashTypes:{'id': "STRING",'class': "STRING"},hashContexts:{'id': depth0,'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-dropdown-content> <li> ");
  stack1 = helpers['if'].call(depth0, "view.button.displayName", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-scroll-wrap\"> <table> <tbody> ");
  stack1 = helpers['if'].call(depth0, "view.backupList.isLoaded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(20, program20, data),fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> </li> </ul>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton/form"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.iconClass")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.form", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.formView", {hash:{
    'form': ("view.form")
  },hashTypes:{'form': "ID"},hashContexts:{'form': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <div class=\"form-loading\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Loading", options) : helperMissing.call(depth0, "i18n", "Loading", options))));
  data.buffer.push("..</div> ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "view.buttonView", {hash:{
    'meta': ("view.meta")
  },hashTypes:{'meta': "ID"},hashContexts:{'meta': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'id': ("view.dropdownId"),
    'class': (":f-dropdown view.dropdownClassName")
  },hashTypes:{'id': "STRING",'class': "STRING"},hashContexts:{'id': depth0,'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-dropdown-content> <li> ");
  stack1 = helpers['if'].call(depth0, "view.form.isLoaded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(9, program9, data),fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> </ul>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton/formLayout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"row collapse\"> <div class=\"large-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.fieldView", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("view.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> </div> ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "formElement", "in", "view.form.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.iconClass")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li> <a href=\"javascript:void(0);\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </a> </li> ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "view.buttonView", {hash:{
    'meta': ("view.meta")
  },hashTypes:{'meta': "ID"},hashContexts:{'meta': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'id': ("view.dropdownId"),
    'class': (":f-dropdown view.dropdownClassName")
  },hashTypes:{'id': "STRING",'class': "STRING"},hashContexts:{'id': depth0,'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/splitButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"dropdown-toggler\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'data-options': ("view.dataOptions"),
    'data-dropdown': ("view.parentView.dropdownId")
  },hashTypes:{'data-options': "STRING",'data-dropdown': "STRING"},hashContexts:{'data-options': depth0,'data-dropdown': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></span> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.parentView.defaultBehaviourIcon")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <a ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp")
  },hashTypes:{'target': "STRING",'on': "STRING"},hashContexts:{'target': depth0,'on': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.icon")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> <div>");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :icon-accept :split-default-button view.isDefaultBehaviour::white")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleDefaultBehaviour", "view._parentView.contentIndex", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp"),
    'bubbles': (false)
  },hashTypes:{'target': "STRING",'on': "STRING",'bubbles': "BOOLEAN"},hashContexts:{'target': depth0,'on': depth0,'bubbles': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("></i> </a> ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "view.buttonView", {hash:{
    'meta': ("view.meta"),
    'defaultBehaviour': ("view.defaultBehaviour")
  },hashTypes:{'meta': "ID",'defaultBehaviour': "ID"},hashContexts:{'meta': depth0,'defaultBehaviour': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'id': ("view.dropdownId")
  },hashTypes:{'id': "STRING"},hashContexts:{'id': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" class=\"f-dropdown f-dropdown-composite\" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.meta.behaviour.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
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
  var buffer = '', stack1, helper, options, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <li> ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("umi-top-bar-dropdown-modules-item")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "module", "name", options) : helperMissing.call(depth0, "link-to", "module", "name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <i class=\"umi-top-bar-module-icon umi-dock-module-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></i> <span>");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <span class=\"umi-top-bar-label umi-top-bar-loader\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Loading", options) : helperMissing.call(depth0, "i18n", "Loading", options))));
  data.buffer.push("... </span> <div class=\"umi-overlay\"></div> ");
  return buffer;
  }

  data.buffer.push("<nav class=\"umi-top-bar\"> <ul class=\"umi-top-bar-list left\"> <li> <span class=\"button tiny flat dropdown without-arrow umi-top-bar-button\" data-dropdown data-options=\"selectorById: false;\"> <i class=\"icon icon-butterfly\"></i> </span> <ul class=\"f-dropdown f-dropdown-double\" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.modules", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </li> <li> <span class=\"umi-top-bar-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.activeProject", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> </li> <li> <a href=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.siteUrl", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\" class=\"button tiny flat umi-top-bar-button\"> <i class=\"icon white icon-viewOnSite\"></i> </a> </li> </ul> ");
  stack1 = helpers['if'].call(depth0, "routeIsTransition", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"umi-top-bar-list right\"> <li> <span class=\"button tiny flat dropdown umi-top-bar-button\" data-dropdown data-options=\"selectorById: false;\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.userName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> <ul class=\"f-dropdown\" data-dropdown-content> <li> <a href=\"javascript:void(0)\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "logout", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-exit\"></i> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Logout", options) : helperMissing.call(depth0, "i18n", "Logout", options))));
  data.buffer.push(" </a> </li> </ul> </li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "notificationList", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </ul> </nav>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  
  data.buffer.push(" <div class=\"umi-tree-loader\"><i class=\"animate animate-loader-60\"></i></div> ");
  }

  data.buffer.push("<div class=\"columns small-12\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeControlItem", {hash:{
    'item': ("root"),
    'treeControlView': ("view")
  },hashTypes:{'item': "ID",'treeControlView': "ID"},hashContexts:{'item': depth0,'treeControlView': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </ul> ");
  stack1 = helpers['if'].call(depth0, "isLoading", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div>");
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
  data.buffer.push(" </span> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.childrenList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(5, program5, data),fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
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
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","STRING"],data:data},helper ? helper.call(depth0, "action", "view.item.id", "editForm", options) : helperMissing.call(depth0, "link-to", "action", "view.item.id", "editForm", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.savedDisplayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "context", "view.item.id", options) : helperMissing.call(depth0, "link-to", "context", "view.item.id", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(13, program13, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "treeControlContextToolbar", "view.item", options) : helperMissing.call(depth0, "render", "treeControlContextToolbar", "view.item", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program13(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program14(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program16(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program17(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-tree-list\" data-parent-id=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.item.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "view.childrenList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(18, program18, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program18(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeControlItem", {hash:{
    'item': ("item"),
    'treeControlView': ("view.treeControlView")
  },hashTypes:{'item': "ID",'treeControlView': "ID"},hashContexts:{'item': depth0,'treeControlView': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-item view.item.type view.isActiveContext:active view.inActive")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "view.hasChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon view.iconTypeClass view.allowMove:move")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  stack1 = helpers['if'].call(depth0, "editLink", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(10, program10, data),fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "controller.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.hasChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
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

Ember.TEMPLATES["UMI/partials/updateLayout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <h5>");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Latest version", "updateLayout", options) : helperMissing.call(depth0, "i18n", "Latest version", "updateLayout", options))));
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.data.control.params.latestVersion.version", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> <br/> <span class=\"button large\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "update", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "view.buttonLabel", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <br/> <span class=\"button large disabled\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Nothing update", "updateLayout", options) : helperMissing.call(depth0, "i18n", "Nothing update", "updateLayout", options))));
  data.buffer.push("</span> ");
  return buffer;
  }

  data.buffer.push("<div class=\"columns small-12\"> <div class=\"umi-update-layout\"> <span class=\"icon icon-butterfly umi-update-layout-logo\"></span> <span class=\"umi-update-layout-content\"> <h2>UMI.CMS Lite</h2> <h5>");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Current version", "updateLayout", options) : helperMissing.call(depth0, "i18n", "Current version", "updateLayout", options))));
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.data.control.params.currentVersion.version", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  stack1 = helpers['if'].call(depth0, "view.data.control.params.latestVersion", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </div> </div>");
  return buffer;
  
});

});
define('application/templates.extends',[], function() {
        

        return function() {
            Ember.TEMPLATES['UMI/module/errors'] = Ember.TEMPLATES['UMI/component/errors'] = Ember.TEMPLATES['UMI/errors'];
            Ember.TEMPLATES['UMI/createForm'] = Ember.TEMPLATES['UMI/editForm'];
        };
    });
define('application/validators',[], function(){
    


    return function(UMI){

        var Validator = Ember.Object.extend({
            /**
             * Валидатор элементов формы
             * @method validateForm
             */
            validateForm: function(){},

            filterProperty: function(value, filters){
                var self = this;
                var i;

                for(i = 0; i < filters.length; i++){
                    value = self.itemFilterProperty(value, Ember.get(filters[i], 'type'));
                }

                return value;
            },

            itemFilterProperty: function(value, filterName){
                if(Ember.typeOf(this[filterName]) === 'function'){
                    return this[filterName](value);
                } else{
                    Ember.warn('Filter "' + filterName + '" was not defined.');
                }
            },

            /**
             *
             * @param value
             * @param validators
             * @returns {Array}
             */
            validateProperty: function(value, validators){
                var i;
                var errorList = [];

                for(i = 0; i < validators.length; i++){
                    switch(validators[i].type){
                        case "required":
                            if(!value){
                                errorList.push(validators[i].message);
                            }
                            break;
                        case "regexp":
                            var pattern = eval(validators[i].options.pattern); //TODO: Заменить eval
                            if(!pattern.test(value)){
                                errorList.push(validators[i].message);
                            }
                            break;
                    }
                }

                if(errorList.length){
                    return errorList;
                }
            },

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
        });

        UMI.validator = Validator.create({});
    };
});
define('application/models',[], function() {
    

    return function(UMI) {

        var defaultValueForType = function(type, defaultValue) {
            switch (type) {
                case 'number':
                    defaultValue = Ember.isEmpty(defaultValue) ? undefined : defaultValue;
                    break;
                default:
                    defaultValue = Ember.isEmpty(defaultValue) ? '' : defaultValue;
                    break;
            }

            return defaultValue;
        };

        DS.Model.reopen({
            needReloadHasMany: Ember.K,

            validErrors: null,

            filterProperty: function(propertyName) {
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                var filters = Ember.get(meta, 'filters.' + propertyName);

                if (filters) {
                    var value = this.get(propertyName);
                    value = UMI.validator.filterProperty(value, filters);
                    this.set(propertyName, value);
                }
            },

            validatorsForProperty: function(propertyName) {
                Ember.assert('PropertyName is required for method validatorsForProperty.', propertyName);
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                return Ember.get(meta, 'validators.' + propertyName);
            },

            validateProperty: function(propertyName) {
                var validators = this.validatorsForProperty(propertyName);
                var value;
                var errorList;
                var activeErrors;
                var otherErrors;

                if (Ember.typeOf(validators) === 'array' && validators.length) {
                    value = this.get(propertyName);
                    errorList = UMI.validator.validateProperty(value, validators);

                    if (Ember.typeOf(errorList) === 'array' && errorList.length) {
                        errorList = errorList.join('. ');
                        activeErrors = this.get('validErrors');

                        if (activeErrors) {
                            this.set('validErrors.' + propertyName, errorList);
                        } else {
                            activeErrors = {};
                            activeErrors[propertyName] = errorList;
                            this.set('validErrors', activeErrors);
                        }

                        if (this.get('isValid') && this.get('isDirty')) {
                            this.send('becameInvalid');
                        }

                    } else if (!this.get('isValid')) {
                        activeErrors = this.get('validErrors');

                        if (activeErrors && activeErrors.hasOwnProperty(propertyName)) {
                            delete activeErrors[propertyName];
                        }

                        otherErrors = 0;

                        for (var error in activeErrors) {
                            if (activeErrors.hasOwnProperty(error)) {
                                ++otherErrors;
                            }
                        }

                        if (otherErrors) {
                            this.set('validErrors', activeErrors);
                        } else {
                            this.set('validErrors', null);
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
            validateObject: function(fields) {
                var meta = this.get('store').metadataFor(this.constructor.typeKey) || '';
                var filters = Ember.get(meta, 'filters');
                var validators = Ember.get(meta, 'validators');
                var key;
                var fieldsLength;

                if (Ember.typeOf(fields) !== 'array') {
                    fields = [];
                    Ember.warn('Unexpected arguments. "fields" must be array');
                }

                fieldsLength = fields.length;

                if (Ember.typeOf(filters) === 'object') {
                    for (key in filters) {
                        if (filters.hasOwnProperty(key)) {
                            this.filterProperty(key);
                        }
                    }
                }

                if (Ember.typeOf(validators) === 'object') {
                    for (key in validators) {
                        if (validators.hasOwnProperty(key)) {
                            if ((fieldsLength && fields.contains(key)) || !fieldsLength) {
                                this.validateProperty(key);
                                if (!this.get('isValid')) {
                                    break;
                                }
                            }
                        }
                    }
                }
            },

            setInvalidProperties: function(invalidProperties) {
                var i;
                var self = this;
                var errors;
                var propertyName;
                var activeErrors;
                if (Ember.typeOf(invalidProperties) === 'array') {
                    for (i = 0; i < invalidProperties.length; i++) {
                        errors = invalidProperties[i].errors;
                        if (Ember.typeOf(errors) === 'array') {
                            errors = errors.join(' ');
                        }
                        propertyName = invalidProperties[i].propertyName || '';
                        propertyName = propertyName.replace(/#.*/g, '');
                        activeErrors = self.get('validErrors');
                        if (activeErrors) {
                            self.set('validErrors.' + propertyName, errors);
                        } else {
                            activeErrors = {};
                            activeErrors[propertyName] = errors;
                            self.set('validErrors', activeErrors);
                        }
                    }
                }
            },

            clearValidateForProperty: function(propertyName) {
                var activeErrors = this.get('validErrors');
                if (Ember.get(activeErrors, propertyName)) {
                    delete activeErrors[propertyName];
                }
                // Объект пересобирается без свойств прототипа
                var i = 0;
                for (var error in activeErrors) {
                    if (activeErrors.hasOwnProperty(error)) {
                        ++i;
                    }
                }
                activeErrors = i ? activeErrors : null;
                this.set('validErrors', activeErrors);
                if (!i) {
                    if (!this.get('isValid')) {
                        this.send('becameValid');
                    }
                }
            },

            loadedRelationshipsByName: {},

            changedRelationshipsByName: {},

            changeRelationshipsValue: function(property, value) {
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');
                Ember.set(changedRelationships, property, value);
                var isDirty = false;
                var object = this;

                for (var key in changedRelationships) {
                    if (changedRelationships.hasOwnProperty(key)) {
                        if (!(key in loadedRelationships)) {
                            isDirty = true;
                        } else if (Object.prototype.toString.call(loadedRelationships[key]).slice(8, -1) === 'Array' &&
                            Object.prototype.toString.call(changedRelationships[key]).slice(8, -1) === 'Array') {
                            if (loadedRelationships[key].length !== changedRelationships[key].length) {
                                isDirty = true;
                            } else {
                                isDirty = changedRelationships[key].every(function(id) {
                                    if (loadedRelationships[key].contains(id)) {
                                        return true;
                                    }
                                });
                                isDirty = !isDirty;
                            }
                        } else if (loadedRelationships[key] !== changedRelationships[key]) {
                            isDirty = true;
                        }
                    }
                }

                if (isDirty) {
                    object.send('becomeDirty');
                } else {
                    var changedAttributes = object.changedAttributes();
                    if (JSON.stringify(changedAttributes) === JSON.stringify({})) {
                        object.send('rolledBack');
                    }
                }
            },

            /**
             * Проверяет не сохранённое состояние для связанного поля (relationships). Используется сериализатором перед
             * сохранением объекта.
             * @param {string} property
             * @return {boolean}
             */
            relationPropertyIsDirty: function(property) {
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');
                var isDirty = false;

                if (changedRelationships.hasOwnProperty(property)) {
                    Ember.warn('Loaded relationship has not been added. After loading hasMany and ManyToMany ' +
                        'relations need to add them to the result loadedRelationshipsByName',
                        loadedRelationships.hasOwnProperty(property));
                    if (Object.prototype.toString.call(loadedRelationships[property]).slice(8, -1) === 'Array' &&
                        Object.prototype.toString.call(changedRelationships[property]).slice(8, -1) === 'Array') {

                        if (loadedRelationships[property].length !== changedRelationships[property].length) {
                            isDirty = true;
                        } else {
                            isDirty = changedRelationships[property].every(function(id) {
                                if (loadedRelationships[property].contains(id)) { return true; }
                            });
                            isDirty = !isDirty;
                        }
                    } else if (loadedRelationships[property] !== changedRelationships[property]) {
                        isDirty = true;
                    }
                }
                return isDirty;
            },

            /**
             * Метод вызывается после сохранения объекта. Изменённые связи переносятся в сохраннёные, и список
             * изменённных сзвязей очищается
             * @method updateRelationshipsMap
             */
            updateRelationshipsMap: function() {
                var loadedRelationships = this.get('loadedRelationshipsByName');
                var changedRelationships = this.get('changedRelationshipsByName');

                for (var property in changedRelationships) {
                    if (changedRelationships.hasOwnProperty(property)) {
                        loadedRelationships[property] = changedRelationships[property];
                    }
                }

                this.set('changedRelationshipsByName', {});
            },

            getDefaultValueForProperty: function(propertyName) {
                var defaultValue;
                //TODO: how get meta for given property?
                this.eachAttribute(function(name, meta) {
                    if (name === propertyName) {
                        defaultValue = defaultValueForType(Ember.get(meta, 'type'),
                            Ember.get(meta, 'options.defaultValue'));
                    }
                });

                return defaultValue;
            }
        });

        var extendedTypes = {
            mpath: function(params) {
                return DS.attr('raw', params);
            },

            date: function(params) {
                return DS.attr('CustomDate', params);
            },

            dateTime: function(params) {
                return DS.attr('CustomDateTime', params);
            },

            serialized: function(params) {
                return DS.attr('serialized', params);
            },

            cmsPageRelation: function(params) {
                return DS.attr('objectRelation', params);
            },

            objectRelation: function(params) {
                return DS.attr('objectRelation', params);
            },

            belongsToRelation: function(params, field, collection) {
                params.async = true;

                if (field.targetCollection === collection.name) {
                    params.inverse = null;
                }

                params.readOnly = false;
                return DS.belongsTo(field.targetCollection, params);
            },

            hasManyRelation: function(params, field) {
                params.async = true;
                params.inverse = field.targetField;
                params.readOnly = false;
                return DS.hasMany(field.targetCollection, params);
            },

            manyToManyRelation: function(params, field) {
                params.async = true;
                params.inverse = null;
                return DS.hasMany(field.targetCollection, params);
            }
        };

        var getBaseTypes = function(typeName) {
            var baseType = typeName;

            switch (typeName) {
                case 'integer':
                    baseType = 'number';
                    break;
            }
            return baseType;
        };

        var getExtendedTypes = function(typeName) {
            return Ember.get(extendedTypes, typeName);
        };

        /**
         * Создает экземпляры DS.Model
         * @method modelsFactory
         * @param {array} collections Массив обьектов
         */
        UMI.modelsFactory = function(collections) {
            var collection;
            var collectionName;
            var fields;
            var field;
            var fieldValue;
            var filters;
            var validators;
            var params;
            var type;

            for (var j = 0; j < collections.length; j++) {
                collection = collections[j];
                collectionName = collection.name;
                fields = {};
                filters = {};
                validators = {};

                for (var i = 0; i < collection.fields.length; i++) {
                    field = collection.fields[i];
                    params = {};
                    type = null;

                    if (field.displayName) {
                        params.displayName = field.displayName;
                    }

                    if (field['default'] !== 'null') {
                        params.defaultValue = field['default'];
                    }

                    type = getExtendedTypes(field.type);

                    if (Ember.typeOf(type) === 'function') {
                        fieldValue = type(params, field, collection);
                    } else {
                        fieldValue = DS.attr(getBaseTypes(field.dataType), params);
                    }

                    if (field.filters) {
                        filters[field.name] = field.filters;
                    }

                    if (field.validators) {
                        validators[field.name] = field.validators;
                    }

                    if (field.type !== 'identify') {
                        fields[field.name] = fieldValue;
                    }
                }

                fields.meta = DS.attr('raw');

                UMI[collectionName.capitalize()] = DS.Model.extend(fields);

                UMI.__container__.lookup('store:main').metaForType(collectionName, {
                    'collectionType': collection.type,
                    'filters': filters,
                    'validators': validators
                });// TODO: Найти рекоммендации на что заменить __container__

                if (collection.source) {
                    UMI[collectionName.capitalize() + 'Adapter'] = DS.UmiRESTAdapter.extend({
                        namespace: collection.source.replace(/^\//g, '')
                    });
                }
            }
        };
    };
});
define('text',{load: function(id){throw new Error("Dynamic load not allowed: " + id);}});

define('text!auth/templates/auth.hbs',[],function () { return '<div class="auth-layout">\r\n    <div class="bubbles"></div>\r\n    <div class="bubbles-front"></div>\r\n    <div class="row vertical-center">\r\n        <div class="small-centered columns auth-layout-content">\r\n            <p class="text-center">\r\n                <img src="{{assetsUrl}}images/svg/elements/auth-logo.svg" class="auth-layout-logo"/>\r\n            </p>\r\n\r\n            <div>\r\n                {{{outlet}}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n<div class="auth-mask"></div>';});


define('text!auth/templates/index.hbs',[],function () { return '<div class="panel pretty radius shake-it">\r\n    <form name="auth" novalidate="novalidate" class="custom large umi-validator" method="post"\r\n          action="{{form.attributes.action}}">\r\n        <div class="errors-list">\r\n            {{#if accessError}}\r\n            <div class="alert-box alert visible">\r\n                <a href="#" class="close">×</a>\r\n                {{accessError}}\r\n            </div>\r\n            {{/if}}\r\n        </div>\r\n        {{#each form.elements}}\r\n        {{#ifCond type \'select\'}}\r\n                <div class="auth-layout-lang">\r\n                    <span class="button dropdown without-arrow" data-dropdown\r\n                          data-options="selectorById: false; align: right;">\r\n                        {{../../currentLocale}}\r\n                    </span>\r\n                    <ul class="f-dropdown radius" data-dropdown-content>\r\n                        {{#each choices}}\r\n                        <li>\r\n                            {{#if active}}\r\n                                <a href="javascript: void(0);">\r\n                                    <i class="icon icon-accept"></i>\r\n                                    {{label}}\r\n                                </a>\r\n                            {{else}}\r\n                                <a href="javascript: void(0);" class="locale-select" data-locale="{{attributes.value}}">\r\n                                    <i class="icon white icon-accept"></i>\r\n                                    {{label}}\r\n                                </a>\r\n                            {{/if}}\r\n                        </li>\r\n                        {{/each}}\r\n                    </ul>\r\n                    <input name="{{attributes.name}}" value="{{../../currentLocale}}" type="hidden" class="auth-layout-lang-input"/>\r\n                </div>\r\n            {{else}}\r\n                {{#ifCond type \'submit\'}}\r\n                    <div class="row">\r\n                        <div class="small-6 small-offset-6 columns">\r\n                            <input name="submit" class="button expand s-margin-clear" type="submit" value="{{label}}"/>\r\n                        </div>\r\n                    </div>\r\n                {{else}}\r\n                    <div class="row">\r\n                        <div class="small-12 columns">\r\n                            <i class="icon icon-{{attributes.name}} input-icon"></i>\r\n                            <input name="{{attributes.name}}" placeholder="{{label}}" type="{{attributes.type}}"\r\n                                   required="required" autocomplete="off" value="">\r\n                            {{#if validators}}\r\n                                <span class="error">\r\n                                    {{#each validators}}\r\n                                        {{message}}\r\n                                    {{/each}}\r\n                                </span>\r\n                            {{/if}}\r\n                        </div>\r\n                    </div>\r\n                {{/ifCond}}\r\n            {{/ifCond}}\r\n        {{/each}}\r\n\r\n    </form>\r\n</div>';});


define('text!auth/templates/errors.hbs',[],function () { return '<div class="alert-box alert">\r\n    <a href="#" class="close">×</a>\r\n    {{#if error}}\r\n    {{error}}\r\n    {{else}}\r\n    Form is invalid.\r\n    {{/if}}\r\n</div>';});

define('auth/templates',[
    'text!./templates/auth.hbs', 'text!./templates/index.hbs', 'text!./templates/errors.hbs', 'Handlebars'
], function(tpl, index, errors) {
    
    return function(Auth) {
        Auth.TEMPLATES.app = Handlebars.compile(tpl);
        Auth.TEMPLATES.index = Handlebars.compile(index);
        Auth.TEMPLATES.errors = Handlebars.compile(errors);
    };
});
define('auth/main',['auth/templates', 'Handlebars', 'jquery', 'Foundation'], function(templates) {
    

    /**
     * @param {Object} [authParams]
     * @param {Object} [authParams.accessError] Объект ошибки доступа к ресурсу window.UmiSettings.authUrl
     * @param {Boolean} [authParams.appIsFreeze] Приложение уже загружено
     * @param {HTMLElement} appLayout корневой DOM элемент приложения
     */
    return function(authParams) {
        /**
         * Сбрасываем настройки ajax установленые админ приложением (появляются после выхода из системы)
         */
        $.ajaxSetup({
            headers: {'X-Csrf-Token': null},
            error: function() {
            }
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
            accessError: function() {
                if (authParams && authParams.accessError && authParams.accessError.status !== 401 && authParams.accessError.responseJSON) {
                    return authParams.accessError.responseJSON.result.error.message;
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
            getForm: function(action) {
                var deferred = $.Deferred();
                action = action || 'form';
                var self = this;
                $.get(window.UmiSettings.baseApiURL + '/action/' + action).then(function(results) {
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
                 * При некорректной авторизации метод "трясет" форму словно говоря НЕТ (не используется)
                 * @method shake
                 */
                shake: function() {
                    function shake(id, a, d) {
                        id.style.left = a.shift() + 'px';
                        if (a.length > 0) {
                            setTimeout(function() {
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
                check: function(form) {
                    var i;
                    var element;
                    var pattern;
                    var valid = true;
                    var removeError = function(el) {
                        el.onfocus = function() {
                            $(this).closest('.columns').removeClass('error');
                        };
                    };

                    var toggleError = function(element, valid) {
                        if (valid) {
                            $(element).closest('.columns').removeClass('error');
                            element.onfocus = null;
                        } else {
                            $(element).closest('.columns').addClass('error');
                            removeError(element);
                            return true;
                        }
                    };

                    for (i = 0; i < form.elements.length; i++) {

                        element = form.elements[i];
                        if ((element.hasAttribute('required') || element.value) && element.hasAttribute('pattern')) {
                            pattern = new RegExp(element.getAttribute('pattern'));
                            if (!pattern.test(element.value)) {
                                valid = false;
                            }
                            if (toggleError(element, valid)) {
                                break;
                            }
                        } else if (element.hasAttribute('required')) {
                            if (!element.value) {
                                valid = false;
                            }
                            if (toggleError(element, valid)) {
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
            transition: function() {
                window.applicationLoading = $.Deferred();
                // Событие при успешном переходе
                document.onmousemove = null;
                var authLayout = document.querySelector('.auth-layout');
                var maskLayout = document.querySelector('.auth-mask');
                $(authLayout).addClass('off is-transition');

                var removeAuth = function() {
                    // Анимация осуществляется с помощью css transition.
                    // Время анимации .7s
                    $(authLayout).addClass('fade-out');
                    setTimeout(function() {
                        authLayout.parentNode.removeChild(authLayout);
                        maskLayout.parentNode.removeChild(maskLayout);
                        Auth.destroy();
                        //Auth = null; TODO: Нужно удалять приложение Auth после авторизации
                    }, 800);
                };

                if (authParams.appIsFreeze) {
                    window.applicationLoading.resolve();
                    $(authParams.appLayout).removeClass('off fade-out');
                    removeAuth();
                } else {
                    require(['application/main'], function(application) {
                        application();
                        window.applicationLoading.then(function() {
                            removeAuth();
                        });
                    });
                }
            },
            /**
             * Старт приложения авторизации
             * @method init
             */
            init: function() {
                var self = this;
                var assetsUrl = window.UmiSettings && window.UmiSettings.assetsUrl;

                /**
                 * Регистрация хелпера ifCond, позволяющего сравнивать значения в шаблоне
                 * method registerHelper
                 */
                Handlebars.registerHelper('ifCond', function(v1, v2, options) {
                    if (v1 === v2) {
                        return options.fn(this);
                    }
                    return options.inverse(this);
                });

                /**
                 * Загружает шаблоны определеные в templates.js
                 * method templates
                 */
                templates(self);

                this.getForm('form').then(function() {
                    var currentLocale = self.cookie.get('auth-locale');
                    var options;
                    var currentLocaleLabel;

                    if (!currentLocale && window.UmiSettings && window.UmiSettings.hasOwnProperty('locale')) {
                        currentLocale = window.UmiSettings.locale;
                    }

                    try {
                        options = self.forms.form.elements[2].choices;
                        currentLocale = currentLocale || options[0].value;

                        for (var i = 0; i < options.length; i++) {
                            if (options[i].value === currentLocale) {
                                options[i].active = 'true';
                                currentLocaleLabel = options[i].label || options[i].value;
                            }
                        }
                    } catch (error) {
                        console.warn(error);
                    }

                    // Проверяем есть ли шаблон и если нет то собираем его
                    if (!document.querySelector('.auth-layout')) {
                        var helper = document.createElement('div');
                        helper.innerHTML = self.TEMPLATES.app({
                            assetsUrl: assetsUrl,
                            outlet: self.TEMPLATES.index(
                                {
                                    accessError: self.accessError,
                                    form: self.forms.form,
                                    currentLocale: currentLocaleLabel
                                }
                            )
                        });
                        helper = document.body.appendChild(helper);
                        $(helper.firstElementChild).unwrap();
                    }
                    $('body').removeClass('loading');

                    $(document.querySelector('.auth-layout')).foundation();

                    var bubbles = document.querySelector('.bubbles');
                    var bubblesFront = document.querySelector('.bubbles-front');
                    var parallax = function(event) {
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
                    document.onmousemove = function(event) {
                        if (bubblesHidden) {
                            bubbles.className = 'bubbles visible';
                            bubblesFront.className = 'bubbles-front visible';
                            bubblesHidden = false;
                        }
                        parallax(event);
                    };

                    $(document).on('click.umi.auth', '.locale-select', function(event) {
                        event.preventDefault();

                        var locale = $(this).data('locale');

                        if (locale && self.cookie.get('auth-locale') !== locale) {
                            self.cookie.set('auth-locale', locale, {path: '/'});
                            window.location.href = window.location.href;
                        }
                    });

                    $(document).on('click.umi.auth', '.close', function() {
                        this.parentNode.parentNode.removeChild(this.parentNode);
                        return false;
                    });

                    var errorsBlock = document.querySelector('.errors-list');

                    $(document).on('submit.umi.auth', 'form', function() {
                        if (!self.validator.check(this)) {
                            return false;
                        }
                        var submitButton = this.querySelector('input[type="submit"]');
                        $(submitButton).addClass('loading');
                        var submit = this.elements.submit;
                        submit.setAttribute('disabled', 'disabled');
                        var data = $(this).serialize();
                        var action = this.getAttribute('action');
                        var deffer = $.post(action, data);

                        var authFail = function(error) {
                            $(submitButton).removeClass('loading');
                            submit.removeAttribute('disabled');
                            var errorList = {error: error.responseJSON.result.error.message};
                            errorsBlock.innerHTML = self.TEMPLATES.errors(errorList);
                            $(errorsBlock).children('.alert-box').addClass('visible');
                        };

                        deffer.done(function(data) {
                            var objectMerge = function(objectBase, objectProperty) {
                                for (var key in objectProperty) {
                                    if (objectProperty.hasOwnProperty(key)) {
                                        if (key === 'token') {
                                            $.ajaxSetup({
                                                headers: {'X-Csrf-Token': objectProperty[key]}
                                            });
                                        }
                                        objectBase[key] = objectProperty[key];
                                    }
                                }
                            };

                            if (data.result) {
                                objectMerge(window.UmiSettings, data.result);
                            }

                            self.transition();
                        });
                        deffer.fail(function(error) {
                            authFail(error);
                        });
                        return false;
                    });
                });
            },

            destroy: function() {
                $(document).off('click.umi.auth');
                $(document).off('submit.umi.auth');
                $(document).off('change.umi.auth');
            },

            cookie: {
                get: function(name) {
                    var matches = document.cookie.match(new RegExp(
                        '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
                    ));
                    return matches ? decodeURIComponent(matches[1]) : undefined;
                },

                set: function(name, value, options) {
                    options = options || {};

                    var expires = options.expires;

                    if (typeof expires === 'number' && expires) {
                        var d = new Date();
                        d.setTime(d.getTime() + expires * 1000);
                        expires = options.expires = d;
                    }
                    if (expires && expires.toUTCString) {
                        options.expires = expires.toUTCString();
                    }

                    value = encodeURIComponent(value);

                    var updatedCookie = name + '=' + value;

                    for (var propName in options) {
                        if (options.hasOwnProperty(propName)) {
                            updatedCookie += '; ' + propName;
                            var propValue = options[propName];
                            if (propValue !== true) {
                                updatedCookie += '=' + propValue;
                            }
                        }
                    }

                    document.cookie = updatedCookie;
                },

                delete: function(name) {
                    Auth.cookie.set(name, '', { expires: -1 });
                }
            }
        };

        Auth.init();
    };
});
define('application/router',[], function() {
    

    return function(UMI) {

        /**
         @module UMI
         @submodule Router
         **/
        UMI.Router.reopen({
            location: 'history',
            rootURL: window.UmiSettings.baseURL
        });

        /**
         @class map
         @constructor
         */
        UMI.Router.map(function() {
            this.resource('module', {path: '/:module'}, function() {
                this.route('errors', {path: '/:status'});
                this.resource('component', {path: '/:component'}, function() {
                    this.route('errors', {path: '/:status'});
                    this.resource('context', {path: '/:context'}, function() {
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
            model: function(params, transition) {
                var self = this;
                var promise;

                try {
                    if (!UmiSettings.baseApiURL) {
                        throw new Error('BaseApiURL was not defined in UmiSettings.');
                    }

                    promise = $.get(UmiSettings.baseApiURL).then(function(results) {
                        if (results && results.result) {
                            var result = results.result;
                            self.controllerFor('application').set('settings', result);

                            if (result.collections) {
                                UMI.modelsFactory(result.collections);
                            }

                            if (result.modules) {
                                self.controllerFor('application').set('modules', result.modules);
                            }

                            if (result.i18n) {
                                UMI.i18n.setDictionary(result.i18n);
                            }
                        } else {
                            try {
                                throw new Error('Resource "' + UmiSettings.baseApiURL + '" not supported.');
                            } catch (error) {
                                transition.abort();
                                transition.send('dialogError', error);
                            }
                        }
                    }, function(error) {
                        var becameError = new Error(error);
                        error.stack = becameError.stack;
                        transition.send('dialogError', error);
                    });
                } catch (error) {
                    transition.abort();
                    transition.send('dialogError', error);
                } finally {
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
            saveObject: function(params) {
                var self = this;
                var deferred = Ember.RSVP.defer();

                try {
                    params.object.validateObject(Ember.get(params, 'fields'));

                    if (!params.object.get('isValid')) {
                        if (params.handler) {
                            $(params.handler).removeClass('loading');
                        }

                        deferred.reject();
                    } else {
                        params.object.save().then(
                            function() {
                                params.object.updateRelationshipsMap();

                                if (params.handler) {
                                    $(params.handler).removeClass('loading');
                                }

                                deferred.resolve(params.object);
                            },

                            function(results) {
                                try {
                                    results = results || {};

                                    if (params.handler) {
                                        $(params.handler).removeClass('loading');
                                    }

                                    var store = self.get('store');
                                    var collection;
                                    var object;
                                    var invalidObjects = Ember.get(results, 'responseJSON.result.error.invalidObjects');
                                    var invalidObject;
                                    var invalidProperties;
                                    var i;

                                    if (Ember.typeOf(invalidObjects) === 'array') {
                                        if (params.object.get('isValid')) {
                                            params.object.send('becameInvalid');
                                        }

                                        for (i = 0; i < invalidObjects.length; i++) {
                                            invalidObject = invalidObjects[i];
                                            invalidProperties = Ember.get(invalidObject, 'invalidProperties');
                                            collection = store.all(invalidObject.collection);
                                            object = collection.findBy('guid', invalidObject.guid);

                                            if (object) {
                                                object.setInvalidProperties(invalidProperties);
                                            }
                                        }
                                    }
                                    deferred.reject();
                                } catch (error) {
                                    self.send('backgroundError', error);
                                }
                            }
                        );
                    }
                } catch (error) {
                    self.send('backgroundError', error);
                } finally {
                    return deferred.promise;
                }
            },

            beforeAdd: function(params) {
                var self = this;
                return self.saveObject(params).then(function(addObject) {
                    if (addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic') {
                        var parent = addObject.get('parent');

                        if (parent && 'isFulfilled' in parent) {
                            return parent.then(function(parent) {
                                parent.reload().then(function(parent) {
                                    parent.trigger('needReloadHasMany', 'add', addObject);
                                });

                                return addObject;
                            });
                        } else {
                            self.controllerFor('component').trigger('needReloadRootElements', 'add', addObject);

                            return addObject;
                        }
                    } else {
                        return addObject;
                    }
                });
            },

            actions: {
                willTransition: function() {
                    UMI.notification.removeAll();
                    this.send('showLoader');
                },

                didTransition: function() {
                    this.send('hideLoader');
                },

                error: function(error, transition) {
                    this.send('hideLoader');
                    return true;
                },

                showLoader: function() {
                    this.controllerFor('application').set('routeIsTransition', true);
                },

                hideLoader: function() {
                    this.controllerFor('application').set('routeIsTransition', false);
                },

                logout: function() {
                    var applicationLayout = document.querySelector('.umi-main-view');
                    var maskLayout = document.createElement('div');
                    maskLayout.className = 'auth-mask';
                    maskLayout = document.body.appendChild(maskLayout);
                    $(applicationLayout).addClass('off is-transition');

                    $.post(UmiSettings.baseApiURL + '/action/logout').then(function() {
                        require(['auth/main'], function(auth) {
                            auth({appIsFreeze: true, appLayout: applicationLayout});
                            $(applicationLayout).addClass('fade-out');

                            Ember.run.later('', function() {
                                $(applicationLayout).removeClass('is-transition');
                                maskLayout.parentNode.removeChild(maskLayout);
                            }, 800);
                        });
                    });
                },

                dialogError: function(error) {
                    var settings = this.parseError(error);

                    if (settings !== 'silence') {
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
                backgroundError: function(error) {
                    var settings = this.parseError(error);

                    if (settings !== 'silence') {
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
                templateLogs: function(error, parentRoute) {
                    parentRoute = parentRoute || 'module';
                    var dataError = this.parseError(error);

                    if (dataError !== 'silence') {
                        var model = Ember.Object.create(dataError);
                        this.intermediateTransitionTo(parentRoute + '.errors', model);
                    }
                },

                showPopup: function(params) {
                    Ember.warn('Param "popupType" is required for create popup.',
                        Ember.get(params, 'viewParams.popupType'));
                    var controller = this.controllerFor('popup');

                    if (Ember.typeOf(params) === 'object') {
                        controller.setProperties(params);
                    }

                    return this.render('popup', {
                        into: 'application',
                        outlet: 'popup',
                        controller: controller
                    });
                },

                closePopup: function() {
                    this.get('container').lookup('view:popup').send('closePopup');
                },

                removePopupLayout: function() {
                    return this.disconnectOutlet({
                        outlet: 'popup',
                        parentView: 'application'
                    });
                },

                /// global actions
                /**
                 * Сохраняет обьект вызывая метод saveObject
                 * @method save
                 */
                save: function(params) {
                    this.saveObject(params);
                },

                saveAndGoBack: function(params) {
                    var self = this;

                    self.saveObject(params).then(function(isSaved) {
                        if (isSaved) {
                            self.send('backToFilter');
                        }
                    });
                },

                add: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function(addObject) {
                        self.send('edit', addObject);
                    });
                },

                addAndGoBack: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function() {
                        self.send('backToFilter');
                    });
                },

                addAndCreate: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function(addObject) {
                        var behaviour = {type: addObject.get('type')};

                        if (addObject.store.metadataFor(addObject.constructor.typeKey)
                            .collectionType === 'hierarchic') {
                            return addObject.get('parent').then(function(parent) {
                                self.send('create', parent, behaviour);
                            });
                        } else {
                            self.send('create', addObject, behaviour);
                        }
                    });
                },

                switchActivity: function(object) {
                    try {
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchActivitySource = this.controllerFor('component').get('settings').actions[(
                            object.get('active') ? 'de' : '') + 'activate'].source;
                        switchActivitySource = UMI.Utils.replacePlaceholder(object, switchActivitySource);

                        $.ajax({
                            url: switchActivitySource,
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function() {
                            object.reload();
                        });
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                },

                create: function(params) {
                    var type = params.behaviour.type;
                    var parentObject = params.object;
                    var contextId = 'root';

                    if (parentObject.constructor.typeKey) {
                        var meta = this.store.metadataFor(parentObject.constructor.typeKey) || {};

                        if (meta.collectionType === 'hierarchic') {
                            contextId = parentObject.get('id');
                        }
                    }

                    this.transitionTo('action', contextId, 'createForm', {queryParams: {'type': type}});
                },

                edit: function(object) {
                    this.transitionTo('action', object.get('id'), 'editForm');
                },

                viewOnSite: function(object) {
                    var link;

                    if (object) {
                        link = object._data.meta.pageUrl;
                    } else {
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
                untrash: function(object) {
                    var self = this;
                    var promise;
                    var serializeObject;
                    var untrashAction;
                    var collectionName;
                    var store = self.get('store');
                    var objectId;

                    try {
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        collectionName = object.constructor.typeKey;
                        untrashAction = self.controllerFor('component').get('settings').actions.untrash;

                        if (!untrashAction) {
                            throw new Error('Action untrash not supported for component.');
                        }

                        promise = $.ajax(
                            {
                                url: untrashAction.source + '?id=' + objectId + '&collection=' + collectionName,
                                type: 'POST',
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }
                        ).then(
                            function() {
                                var invokedObjects = [];
                                invokedObjects.push(object);
                                var collection = store.all(collectionName);

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var mpath = object.get('mpath');
                                    var parent;
                                    if (Ember.typeOf(mpath) === 'array' && mpath.length) {
                                        for (var i = 0; i < mpath.length; i++) {
                                            parent = collection.findBy('id', mpath[i] + '');
                                            if (parent) {
                                                invokedObjects.push(parent);
                                            }
                                        }
                                    }
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" ' +
                                    UMI.i18n.getTranslate('Restored').toLowerCase() + '.'};
                                UMI.notification.create(settings);
                            },
                            function() {
                                var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" ' +
                                    UMI.i18n.getTranslate('Not restored').toLowerCase() + '.'};
                                UMI.notification.create(settings);
                            }
                        );
                    } catch (error) {
                        self.send('backgroundError', error);
                    } finally {
                        return promise;
                    }
                },

                /**
                 * Удаляет объект (перемещает в корзину)
                 * @method trash
                 * @param object
                 * @returns {*|Promise}
                 */
                trash: function(object) {
                    var self = this;
                    var store = self.get('store');
                    var promise;
                    var serializeObject;
                    var isActiveContext;
                    var trashAction;
                    var objectId;

                    try {
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        isActiveContext = this.modelFor('context') === object;
                        trashAction = this.controllerFor('component').get('settings').actions.trash;

                        if (!trashAction) {
                            throw new Error('Action trash not supported for component.');
                        }

                        promise = $.ajax(
                            {
                                url: trashAction.source + '?id=' + objectId,
                                type: 'POST',
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }
                        ).then(
                            function() {
                                var collectionName = object.constructor.typeKey;
                                var invokedObjects = [];
                                invokedObjects.push(object);

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var collection = store.all(collectionName);

                                    collection.find(function(item) {
                                        var mpath = item.get('mpath') || [];

                                        if (mpath.contains(parseFloat(objectId)) && mpath.length > 1) {
                                            invokedObjects.push(item);
                                        }
                                    });
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {
                                    type: 'success',
                                    'content': UMI.i18n.getTranslate('Moved to trash') + ': "' +
                                        object.get('displayName') + '".'
                                };
                                UMI.notification.create(settings);

                                if (isActiveContext) {
                                    self.send('backToFilter');
                                }
                            },
                            function() {
                                var settings = {
                                    type: 'error',
                                    'content': UMI.i18n.getTranslate('Failed to move in the trash') + ': "' +
                                        object.get('displayName') + '".'
                                };

                                UMI.notification.create(settings);
                            }
                        );
                    } catch (error) {
                        this.send('backgroundError', error);
                    } finally {
                        return promise;
                    }
                },

                /**
                 * Спрашивает пользователя и в случае положительного ответа безвозвратно удаляет объект
                 * @method delete
                 * @param object
                 * @returns {*|Promise}
                 */
                'delete': function(object) {
                    var self = this;
                    var isActiveContext = this.modelFor('context') === object;
                    var data = {
                        'close': false,
                        'title': UMI.i18n.getTranslate('Delete') + ' "' + object.get('displayName') + '".',
                        'content': '<div>' +
                            UMI.i18n.getTranslate('The object will be deleted permanently, continue anyway') +
                            '?</div>',
                        'confirm': UMI.i18n.getTranslate('Delete'),
                        'reject': UMI.i18n.getTranslate('Cancel')
                    };

                    return UMI.dialog.open(data).then(
                        function() {
                            var collectionName = object.constructor.typeKey;
                            var store = object.get('store');
                            var objectId = object.get('id');

                            return object.destroyRecord().then(function() {
                                var invokedObjects = [];

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var collection = store.all(collectionName);

                                    collection.find(function(item) {
                                        var mpath = item.get('mpath') || [];

                                        if (mpath.contains(parseFloat(objectId)) && mpath.length > 1) {
                                            invokedObjects.push(item);
                                        }
                                    });
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {
                                    type: 'success',
                                    'content': '"' + object.get('displayName') + '" ' +
                                        UMI.i18n.getTranslate('Successfully removed').toLowerCase() + '.'
                                };
                                UMI.notification.create(settings);

                                if (isActiveContext) {
                                    self.send('backToFilter');
                                }
                            }, function() {
                                var settings = {
                                    type: 'error',
                                    'content': '"' + object.get('displayName') + '" ' +
                                        UMI.i18n.getTranslate('Failed to delete').toLowerCase() + '.'
                                };
                                UMI.notification.create(settings);
                            });
                        },
                        function() {}
                    );
                },
                /**
                 * Возвращает к списку
                 */
                backToFilter: function() {
                    this.transitionTo('context', 'root');
                },

                /**
                 * Импорт Rss ленты
                 */
                importFromRss: function(object) {
                    try {
                        var data = {
                            'content': '<div class="text-center"><i class="animate animate-loader-40"></i> ' +
                                UMI.i18n.getTranslate('Waiting') + '..</div>',
                            'close': false,
                            'type': 'check-process'
                        };

                        UMI.dialog.open(data).then(
                            function() {},
                            function() {}
                        );

                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));

                        var importFromRssSource = this.controllerFor('component').get('settings').actions.importFromRss
                            .source;
                        $.ajax({
                            url: importFromRssSource,
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(
                            function(results) {
                                var model = UMI.dialog.get('model');
                                model.setProperties(
                                    {
                                        'content': Ember.get(results, 'result.importFromRss.message'),
                                        'close': true,
                                        'reject': UMI.i18n.getTranslate('Close'), 'type': null
                                    }
                                );
                            }
                        );
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                },

                /**
                 *
                 */
                switchRobots: function(object, currentState, defer) {
                    try {
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchRobotsSource = Ember.get(this.controllerFor('component').get('settings'), 'actions.' +
                            (currentState ? 'dis' : '') + 'allowRobots.source');

                        $.ajax({
                            url: switchRobotsSource + '?id=' + object.get('id'),
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function() {
                            defer.resolve();
                        });
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                }
            },

            /**
             Метод парсит ошибку и возвпращает ее в виде объекта (ошибки с Back-end)
             @method parseError
             @return Object|null|String {status: status, title: title, content: content, stack: stack}
             */
            parseError: function(error) {
                error = error || {};
                var status = Ember.get(error, 'status');
                var stack = Ember.get(error, 'stack');

                var parsedError = {
                    status: status,
                    title: UMI.i18n.getTranslate(error.statusText),
                    stack: stack
                };

                if (status === 403 || status === 401) {
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    this.send('logout');
                    return 'silence';
                }

                var content;
                var responseError;
                if (error.hasOwnProperty('responseJSON')) {
                    responseError = Ember.get(error, 'responseJSON.result.error');
                    if (responseError) {
                        content = Ember.get(responseError, 'message');
                    }
                } else {
                    content = Ember.get(error, 'responseText') || Ember.get(error, 'message');
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
            redirect: function(model, transition) {
                var firstChild;
                if (transition.targetName === this.routeName) {
                    try {
                        firstChild = this.controllerFor('application').get('modules')[0];

                        if (!firstChild) {
                            throw new Error(UMI.i18n.getTranslate('Modules are not available.'));
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);//TODO: Проверить вывод ошибок
                    } finally {//TODO: Нужно дать пользователю выбрать компонент
                        return this.transitionTo('module', Ember.get(firstChild, 'name'));
                    }
                }
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.ModuleRoute = Ember.Route.extend({
            model: function(params) {
                var deferred;
                var modules;
                var module;

                try {
                    deferred = Ember.RSVP.defer();
                    modules = this.controllerFor('application').get('modules');
                    module = modules.findBy('name', params.module);

                    if (module) {
                        deferred.resolve(module);
                    } else {
                        throw new Error(UMI.i18n.getTranslate('Module') + ' "' + params.module + '" ' +
                            UMI.i18n.getTranslate('not found') + '.');
                    }
                } catch (error) {
                    deferred.reject(error);
                } finally {
                    return deferred.promise;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var self = this;
                    var deferred;
                    var firstChild;

                    try {
                        deferred = Ember.RSVP.defer();
                        firstChild = Ember.get(model, 'components')[0];

                        if (firstChild) {
                            deferred.resolve(self.transitionTo('component', Ember.get(firstChild, 'name')));
                        } else {
                            throw new Error(UMI.i18n.getTranslate('For') + ' ' +
                                UMI.i18n.getTranslate('Module').toLowerCase() + '"' + Ember.get(model, 'name') + '" ' +
                                UMI.i18n.getTranslate('Components').toLowerCase() + ' ' +
                                UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                        }
                    } catch (error) {
                        deferred.reject(Ember.run.next(self, function() {
                            this.send('templateLogs', error);
                        }));
                    } finally {
                        return deferred.promise;
                    }
                }
            },

            serialize: function(model) {
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
            model: function(params, transition) {
                var self = this;
                var deferred;
                var components;
                var model;
                var componentName = transition.params.component.component;

                try {
                    deferred = Ember.RSVP.defer();
                    components = Ember.get(this.modelFor('module'), 'components');

                    // filterBy
                    for (var i = 0; i < components.length; i++) {
                        if (components[i].name === componentName) {
                            model = components[i];
                            break;
                        }
                    }

                    if (model) {
                        /**
                         * Ресурс компонента
                         */
                        $.ajax({
                            type: 'GET',
                            url: Ember.get(model, 'resource'),
                            global: false,
                            success: function(results) {
                                var componentController = self.controllerFor('component');

                                if (Ember.typeOf(results) === 'object' && Ember.get(results, 'result.layout')) {
                                    var settings = results.result.layout;
                                    var dataSource = Ember.get(settings, 'dataSource') || '';
                                    componentController.set('settings', settings);
                                    componentController.set('selectedContext', Ember.get(transition, 'params.context') ?
                                        Ember.get(transition, 'params.context.context') : 'root');

                                    if (Ember.get(dataSource, 'type') === 'lazy') {
                                        $.get(Ember.get(settings, 'actions.' + Ember.get(dataSource, 'action') +
                                                '.source')).then(
                                            function(results) {
                                                var data = Ember.get(results, 'result.' +
                                                    Ember.get(dataSource, 'action') + '.objects');

                                                Ember.set(dataSource, 'objects', data);
                                                deferred.resolve(model);
                                            },
                                            function(error) {
                                                deferred.reject(transition.send('backgroundError', error));
                                            }
                                        );
                                    } else {
                                        deferred.resolve(model);
                                    }
                                } else {
                                    var error = new Error(UMI.i18n.getTranslate('Resource') + ' "' +
                                        Ember.get(model, 'resource') + '" ' +
                                        UMI.i18n.getTranslate('Incorrect').toLowerCase() + '.');
                                    transition.send('backgroundError', error);
                                    deferred.reject();
                                }
                            },

                            error: function(error) {
                                deferred.reject(Ember.run.next(this, function() {
                                    transition.send('templateLogs', error);
                                }));
                            }
                        });
                    } else {
                        throw new URIError(UMI.i18n.getTranslate('Component') + ' "' + componentName + '" ' +
                            UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                    }
                } catch (error) {
                    deferred.reject(Ember.run.next(this, function() {
                        transition.send('templateLogs', error);
                    }));
                } finally {
                    return deferred.promise;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var context;

                    try {
                        var emptyControl = this.controllerFor('component')
                            .get('settings.contents.emptyContext.redirect');

                        if (emptyControl) {
                            context = Ember.get(emptyControl, 'params.slug');
                        } else {
                            context = 'root';
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);
                    } finally {
                        return this.transitionTo('context', context);
                    }
                }
            },

            serialize: function(model) {
                return {
                    component: Ember.get(model, 'name')
                };
            },

            /**
             * Отрисовываем компонент
             * Если есть sideBar - его тоже отрисовываем
             * @param controller
             */
            renderTemplate: function(controller) {
                this.render();

                if (controller.get('sideBarControl')) {
                    try {
                        this.render(controller.get('sideBarControl.name'), {
                            into: 'component',
                            outlet: 'sideBar'
                        });
                    } catch (error) {
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
            model: function(params, transition) {
                var componentController;
                var collection;
                var RootModel;
                var model;

                try {
                    componentController = this.controllerFor('component');
                    collection = componentController.get('dataSource');
                    componentController.set('selectedContext', params.context);// TODO: зачем это вообще нужно?

                    if (params.context === 'root') {
                        RootModel = Ember.Object.extend({});
                        model = new Ember.RSVP.Promise(function(resolve) {
                            resolve(RootModel.create({'id': 'root', type: 'base'}));
                        });
                    } else {
                        switch (Ember.get(collection, 'type')) {
                            case 'static':
                            case 'lazy':
                                model = new Ember.RSVP.Promise(function(resolve, reject) {
                                    var objects = Ember.get(collection, 'objects');
                                    var object;
                                    // filterBy
                                    for (var i = 0; i < objects.length; i++) {
                                        if (objects[i].id === params.context) {
                                            object = objects[i];
                                            break;
                                        }
                                    }

                                    if (object) {
                                        resolve(object);
                                        resolve(object);
                                    } else {
                                        reject(UMI.i18n.getTranslate('Object') + ' ' +
                                            UMI.i18n.getTranslate('With').toLowerCase() + ' ID ' + params.context +
                                            ' ' + UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                                    }
                                });
                                break;
                            case 'collection':
                                if (this.store.hasRecordForId(Ember.get(collection, 'name'), params.context)) {
                                    model = this.store.getById(Ember.get(collection, 'name'), params.context);
                                    //model = model.reload();
                                } else {
                                    model = this.store.updateCollection(
                                        Ember.get(collection, 'name'),
                                        {'filters[id]': params.context, fields: 'displayName'}
                                    ).then(function(results) {
                                        return results.get('firstObject');
                                    });
                                }
                                break;
                            default:
                                throw new Error(UMI.i18n.getTranslate('Incorrect') + ' dataSource.');
                        }
                    }
                } catch (error) {
                    Ember.run.next(this, function() {
                        transition.send('templateLogs', error);
                    });
                } finally {
                    return model;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var control;
                    var controlName;

                    try {
                        control = this.controllerFor('component').get('contentControls')[0];
                        controlName = Ember.get(control, 'id');
                        if (!controlName) {
                            throw new Error(UMI.i18n.getTranslate('The actions for this context is not available') +
                                '.');
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);
                    } finally {
                        return this.transitionTo('action', controlName);
                    }
                }
            },

            serialize: function(model) {
                if (model) {
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

            model: function(params, transition) {
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

                try {
                    deferred = Ember.RSVP.defer();
                    actionName = params.action;
                    contextModel = this.modelFor('context');
                    componentController = this.controllerFor('component');
                    contentControls = componentController.get('contentControls');
                    contentControl = contentControls.findBy('id', actionName);

                    if (!contentControl) {
                        throw new Error(UMI.i18n.getTranslate('Action') + ' "' + actionName + '" ' +
                            UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                    }

                    contentControl = $.extend({}, contentControl);

                    routeData = {
                        'object': contextModel,
                        'control': contentControl
                    };
                    actionResourceName = Ember.get(contentControl, 'params.action');

                    if (!actionResourceName) {
                        deferred.resolve(routeData);
                    } else {
                        actionResource = Ember.get(componentController, 'settings.actions.' + actionResourceName +
                            '.source');

                        if (actionResource) {
                            controlObject = routeData.object;

                            if (actionName === 'createForm') {
                                createdParams = {};

                                if (componentController.get('dataSource.type') === 'collection') {
                                    var meta = this.store.metadataFor(componentController.get('dataSource.name')) || {};
                                    if (Ember.get(meta, 'collectionType') === 'hierarchic' &&
                                        routeData.object.get('id') !== 'root') {
                                        createdParams.parent = contextModel;
                                    }
                                }

                                if (transition.queryParams.type) {
                                    createdParams.type = transition.queryParams.type;
                                }

                                routeData.createObject = self.store.createRecord(
                                    componentController.get('dataSource.name'), createdParams
                                );

                                controlObject = routeData.createObject;
                            }

                            actionResource = UMI.Utils.replacePlaceholder(controlObject, actionResource);

                            $.ajax({
                                type: 'GET',
                                url: actionResource,
                                global: false,
                                success: function(results) {
                                    var dynamicControl;
                                    var dynamicControlName;

                                    if (actionName === 'dynamic') {
                                        dynamicControl = Ember.get(results, 'result') || {};

                                        for (var key in dynamicControl) {
                                            if (dynamicControl.hasOwnProperty(key)) {
                                                dynamicControlName = key;
                                            }
                                        }

                                        dynamicControl = dynamicControl[dynamicControlName] || {};
                                        dynamicControl.name = dynamicControlName;

                                        UMI.Utils.objectsMerge(routeData.control, dynamicControl);
                                    } else {
                                        if (actionName === 'createForm') {
                                            routeData.createObject.set('guid',
                                                Ember.get(results, 'result.' + actionResourceName + '.guid'));
                                            Ember.set(routeData.control, 'meta',
                                                Ember.get(results, 'result.' + actionResourceName + '.form'));
                                        } else {
                                            Ember.set(routeData.control, 'meta',
                                                Ember.get(results, 'result.' + actionResourceName));
                                        }
                                    }
                                    deferred.resolve(routeData);
                                },

                                error: function(error) {
                                    deferred.reject(transition.send('templateLogs', error, 'component'));
                                }
                            });
                        } else {
                            throw new Error(UMI.i18n.getTranslate('Action') + ' ' + Ember.get(contentControl, 'name') +
                                ' ' + UMI.i18n.getTranslate('Not available for the selected context').toLowerCase() +
                                '.');
                        }
                    }
                } catch (error) {
                    deferred.reject(transition.send('templateLogs', error, 'component'));
                } finally {
                    return deferred.promise;
                }
            },

            afterModel: function(model, transition) {
                try {
                    var defer = Ember.RSVP.defer();
                    var control = this.controllerFor('action');
                    var controlName = Ember.get(model, 'control.name');
                    var controlPromiseService = control[controlName + 'PromiseService'];

                    if (controlPromiseService) {
                        if (Ember.canInvoke(controlPromiseService, 'execute')) {
                            controlPromiseService.execute(model).then(
                                function(result) {
                                    defer.resolve(result);
                                },

                                function(error) {
                                    defer.reject(error);
                                }
                            );
                        } else {
                            defer.resolve();
                        }
                    } else {
                        defer.resolve();
                    }
                    return defer.promise;
                } catch (error) {
                    transition.send('backgroundError', error);
                }
            },

            serialize: function(routeData) {
                if (Ember.get(routeData, 'control')) {
                    return {action: Ember.get(routeData, 'control.id')};
                }
            },

            renderTemplate: function(controller, routeData) {
                try {
                    var templateType = Ember.get(routeData, 'control.name');

                    this.render(templateType, {
                        controller: controller
                    });
                } catch (error) {
                    this.send('templateLogs', error, 'component');
                }
            },

            setupController: function(controller, model) {
                this._super(controller, model);

                if (model.createObject) {
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
                willTransition: function(transition) {
                    if (transition.params.action && transition.params.action.action !== 'createForm') {
                        this.get('controller').set('typeName', null);
                    }

                    var model = Ember.get(this.modelFor('action'), 'object');

                    if (Ember.get(model, 'isNew')) {
                        model.deleteRecord();
                    }

                    if (Ember.get(model, 'isDirty')) {
                        transition.abort();
                        var data = {
                            'close': false,
                            'title': UMI.i18n.getTranslate('The changes were not saved') + '.',
                            'content': UMI.i18n.getTranslate('Transition:unsaved changes') + '?',
                            'confirm': UMI.i18n.getTranslate('Stay on the page'),
                            'reject': UMI.i18n.getTranslate('Continue without saving')
                        };

                        return UMI.dialog.open(data).then(
                            function() {},
                            function() {
                                if (!model.get('isValid')) {
                                    model.set('validErrors', null);
                                    model.send('becameValid');
                                }

                                model.rollback();
                                transition.retry();
                            }
                        );
                    }
                    return true;
                }
            }
        });
    };
});
define('application/controllers',[], function() {
    
    return function(UMI) {
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null,
            modules: null,
            routeIsTransition: null
        });
        UMI.ModuleController = Ember.ObjectController.extend({});

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend(Ember.Evented, {
            /**
             * Уникальное имя компонента
             * @property name
             */
            name: function() {
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
             * Метод-тригер позволяющий обновить объекты не имеющих родителя
             * @method needReloadRootElements
             */
            needReloadRootElements: Ember.K,
            /**
             Вычисляемое свойсво возвращающее массив контролов для текущего контекста
             @method contentControls
             @return Array Возвращает массив Ember объектов содержащий возможные действия текущего контрола
             */
            contentControls: function() {
                var self = this;
                var contentControls = [];
                var settings = this.get('settings');
                try {

                    var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                    var controls = settings.contents[selectedContext];
                    var key;
                    var control;
                    for (key in controls) { //for empty - createForm & filter
                        if (controls.hasOwnProperty(key)) {
                            control = controls[key];
                            control.id = key;// used by router
                            control.name = key;// used by templates
                            contentControls.push(control);
                        }
                    }
                } catch (error) {
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function() {
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
            sideBarControl: function() {
                var sideBarControl;
                var self = this;
                try {
                    var settings = this.get('settings');
                    if (settings && settings.hasOwnProperty('sideBar')) {
                        var control;
                        var controlParams;
                        for (control in settings.sideBar) {
                            if (settings.sideBar.hasOwnProperty(control)) {
                                controlParams = settings.sideBar[control];
                                if (Ember.typeOf(controlParams) !== 'object') {
                                    controlParams = {};
                                }
                                sideBarControl = controlParams;
                                sideBarControl.name = control;
                            }
                        }
                    }

                } catch (error) {
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function() {
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
define('application/views',[], function() {
    

    return function(UMI) {

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            didInsertElement: function() {
                $('body').removeClass('loading');
                if (window.applicationLoading) {
                    window.applicationLoading.resolve();
                }

                this.$().foundation();
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
        'DS', 'Modernizr', 'iscroll', 'ckEditor', 'jqueryUI', 'elFinder', 'timepicker', 'moment', 'application/config',
        'application/utils', 'application/i18n', 'application/templates.compile', 'application/templates.extends',
        'application/validators', 'application/models', 'application/router', 'application/controllers',
        'application/views'
    ],
    function(DS, Modernizr, iscroll, ckEditor, jqueryUI, elFinder, timepicker, moment, config, utils, i18n, templates,
    templatesExtends, validators, models, router, controller, views) {
        

        var UMI = window.UMI = window.UMI || {};

        /**
         * Для отключения "магии" переименования моделей Ember.Data
         * @class Inflector.inflector
         */
        Ember.Inflector.inflector = new Ember.Inflector();

        /**
         * Umi application.
         * @module UMI
         * @extends {Ember.Application}
         * @requires Ember
         */
        UMI = Ember.Application.create({
            rootElement: '#body',
            Resolver: Ember.DefaultResolver.extend({
                resolveTemplate: function(parsedName) {
                    parsedName.fullNameWithoutType = 'UMI/' + parsedName.fullNameWithoutType;
                    return this._super(parsedName);
                }
            })
        });

        UMI.deferReadiness();

        Ember.View.reopen({
            init: function() {
                this._super();
                var self = this;

                // bind attributes beginning with 'data-'
                Ember.keys(this).forEach(function(key) {
                    if (key.substr(0, 5) === 'data-') {
                        self.get('attributeBindings').pushObject(key);
                    }
                });
            }
        });

        utils(UMI);
        config(UMI);
        i18n(UMI);

        /**
         * @class OrmSettings
         * @type {{defaultProperties: string[]}}
         */
        UMI.OrmSettings = {
            defaultProperties: ['id', 'guid', 'type', 'version', 'mpath', 'slug', 'uri', 'h1', 'meta', 'links']
        };

        /**
         * @class OrmHelper
         */
        UMI.OrmHelper = {
            /**
             * @method buildRequest
             */
            buildRequest: function(object, fields) {
                var store = object.get('store');
                var collectionName = object.constructor.typeKey;
                var model = store.modelFor(collectionName);

                var nativeProperties = this.getNativeProperties(model, fields);
                var belongsToProperties = this.getBelongsToProperties(model, fields);

                var query = {};

                query.fields = nativeProperties;
                query['with'] = belongsToProperties;

                return query;
            },

            getNativeProperties: function(model, fields) {
                var nativeProperties = [];

                model.eachAttribute(function(name) {
                    if (fields.contains(name)) {
                        nativeProperties.push(name);
                    }
                });

                nativeProperties = nativeProperties.join(',');
                return nativeProperties;
            },

            getBelongsToProperties: function(model, fields) {
                var fieldsList = {};

                model.eachRelationship(function(name, relatedModel) {
                    var i;
                    var dataSource;

                    if (relatedModel.kind === 'belongsTo') {
                        for (i = 0; i < fields.length; i++) {
                            dataSource = fields[i];

                            if (dataSource === name) {
                                fieldsList[name] = fieldsList[name] || [];
                            } else if (dataSource.indexOf(name + '.', 0) === 0) {
                                fieldsList[name] = fieldsList[name] || [];
                                fieldsList[name].push(dataSource.slice(name.length + 1));
                            }
                        }

                        if (fieldsList[name]) {//TODO: parametrize properties list
                            fieldsList[name] = fieldsList[name].join(',') || 'displayName';
                        }
                    }
                });

                return fieldsList;
            },

            getHasManyProperties: function(model, fields) {
                var fieldsList = {};

                model.eachRelationship(function(name, relatedModel) {
                    var i;
                    var dataSource;

                    if (relatedModel.kind === 'hasMany' || relatedModel.kind === 'manyToMany') {
                        for (i = 0; i < fields.length; i++) {
                            dataSource = fields[i];

                            if (dataSource === name) {
                                fieldsList[name] = fieldsList[name] || [];
                            } else if (dataSource.indexOf(name + '.', 0) === 0) {
                                fieldsList[name] = fieldsList[name] || [];
                                fieldsList[name].push(dataSource.slice(name.length + 1));
                            }
                        }

                        if (fieldsList[name]) {//TODO: parametrize properties list
                            fieldsList[name] = fieldsList[name].join(',') || 'displayName';
                        }
                    }
                });

                return fieldsList;
            },

            getRelationProperties: function(model, fields) {
                var fieldsList = {};

                model.eachRelationship(function(name, relatedModel) {
                    var i;
                    var dataSource;
                    var collectionName = Ember.get(relatedModel, 'type.typeKey');

                    if (relatedModel.kind === 'belongsTo' || relatedModel.kind === 'hasMany' ||
                        relatedModel.kind === 'manyToMany') {
                        for (i = 0; i < fields.length; i++) {
                            dataSource = fields[i];

                            if (dataSource === name) {
                                fieldsList[collectionName] = fieldsList[collectionName] || [];
                            } else if (dataSource.indexOf(name + '.', 0) === 0) {
                                fieldsList[collectionName] = fieldsList[collectionName] || [];
                                fieldsList[collectionName].push(dataSource.slice(name.length + 1));
                            }
                        }

                        if (fieldsList[collectionName]) {//TODO: parametrize properties list
                            fieldsList[collectionName] = fieldsList[collectionName].join(',') || 'displayName';
                        }
                    }
                });

                return fieldsList;
            }
        };

        /**
         * @class UmiRESTAdapter
         * @extends {DS.RESTAdapter}
         */
        DS.UmiRESTAdapter = DS.RESTAdapter.extend({
            /**
             Метод возвращает URI запроса для CRUD операций данной модели.

             @method buildURL
             @return {String} CRUD ресурс для данной модели
             */
            buildURL: function(type, id) {
                var url = [], host = Ember.get(this, 'host'), prefix = this.urlPrefix();

                if (id) {
                    url.push(id);
                }

                if (prefix) {
                    url.unshift(prefix);
                }

                url = url.join('/');
                if (!host && url) {
                    url = '/' + url;
                }

                return url;
            },

            namespace: window.UmiSettings.baseApiURL.replace(/^\//g, ''),

            ajaxOptions: function(url, type, hash) {
                hash = hash || {};
                hash.url = url;
                hash.type = type;
                hash.dataType = 'json';
                hash.context = this;

                if (hash.data && type !== 'GET') {
                    hash.contentType = 'application/json; charset=utf-8';
                    hash.data = JSON.stringify(hash.data);
                }

                var headers = this.headers;

                if (type === 'PUT' || type === 'DELETE') {
                    headers = headers || {};
                    headers['X-HTTP-METHOD-OVERRIDE'] = type;
                    hash.type = 'POST';
                }

                if (headers !== undefined) {
                    hash.beforeSend = function(xhr) {
                        Ember.ArrayPolyfills.forEach.call(Ember.keys(headers), function(key) {
                            xhr.setRequestHeader(key, headers[key]);
                        });
                    };
                }

                return hash;
            },

            ajaxError: function(jqXHR) {
                var error = this._super(jqXHR);
                if (error.status === 403 || error.status === 401) {
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    UMI.__container__.lookup('router:main').send('logout');
                    return;
                }
                if (error.status === 422) {
                    return error;
                } else {
                    var message;
                    if (error.hasOwnProperty('responseJSON')) {
                        message = Ember.get(error, 'responseJSON.result.error.message');
                    } else {
                        message = error.responseText;
                    }
                    var data = {
                        'close': true,
                        'title': error.status + '. ' + error.statusText,
                        'content': message,
                        'duration': false,
                        'type': 'error'
                    };
                    UMI.notification.create(data);
                }
            }
        });


        UMI.ApplicationSerializer = DS.RESTSerializer.extend({

            /**
             Переносим в store metadata для коллекции
             Чтобы получить: store.metadataFor(type)
             */
            extractMeta: function(store, type, payload) {
                var payloadMeta = Ember.get(payload, 'result.meta');

                if (payloadMeta) {
                    store.metaForType(type, payloadMeta);
                    delete payload.result.meta;
                }
            },

            /**
             Удаление объекта-обертки result из всех приходящих объектов
             Удаление объекта-обертки collection из всех объектов его содержащих
             */
            normalizePayload: function(payload) {
                payload = payload.result;
                if (payload.hasOwnProperty('collection')) {
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
            serializeHasMany: function(record, json, relationship) {
                var key = relationship.key;

                var relationshipType = DS.RelationshipChange.determineRelationshipType(record.constructor, relationship);

                if (relationshipType === 'manyToNone' || relationshipType === 'manyToMany' || relationshipType === 'manyToOne') {
                    if (record.relationPropertyIsDirty(key)) {
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

        UMI.ApplicationStore = DS.Store.extend({
            /**
             * Обновление объектов коллекции без очищения загруженных полей
             * @method updateCollection
             * @param type
             * @param id
             * @returns {*}
             */
            updateCollection: function(type, id) {
                var promise;
                var self = this;

                function promiseArray(promise, label) {
                    var PromiseArray = Ember.ArrayProxy.extend(Ember.PromiseProxyMixin);
                    return PromiseArray.create({
                        promise: Ember.RSVP.Promise.cast(promise, label)
                    });
                }

                function serializerFor(container, type, defaultSerializer) {
                    return container.lookup('serializer:' + type) ||
                        container.lookup('serializer:application') ||
                        container.lookup('serializer:' + defaultSerializer) ||
                        container.lookup('serializer:-default');
                }

                function serializerForAdapter(adapter, type) {
                    var serializer = adapter.serializer,
                        defaultSerializer = adapter.defaultSerializer,
                        container = adapter.container;

                    if (container && serializer === undefined) {
                        serializer = serializerFor(container, type.typeKey, defaultSerializer);
                    }

                    if (serializer === null || serializer === undefined) {
                        serializer = {
                            extract: function(store, type, payload) {
                                return payload;
                            }
                        };
                    }

                    return serializer;
                }

                function findQuery(type, query) {
                    type = self.modelFor(type);

                    var array = self.recordArrayManager.createAdapterPopulatedRecordArray(type, query);

                    var adapter = self.adapterFor(type);

                    Ember.warn('You tried to load a query but you have no adapter (for ' + type + ')', adapter);
                    Ember.warn('You tried to load a query but your adapter does not implement `findQuery`', adapter.findQuery);

                    return promiseArray(_findQuery(adapter, self, type, query, array));
                }

                function _findQuery(adapter, store, type, query, recordArray) {
                    var promise = adapter.findQuery(store, type, query, recordArray);
                    var serializer = serializerForAdapter(adapter, type);
                    var label = 'DS: Handle Adapter#findQuery of ' + type;

                    return Ember.RSVP.Promise.cast(promise, label).then(function(adapterPayload) {
                        var key;
                        var queryParams = (Ember.get(query, 'fields') || '').split(',');
                        var baseParams = UMI.OrmSettings.defaultProperties;
                        queryParams = queryParams.concat(baseParams);

                        var belongsToFields = Ember.get(query, 'with');

                        if (Ember.typeOf(belongsToFields) === 'object') {
                            for (key in belongsToFields) {
                                if (belongsToFields.hasOwnProperty(key)) {
                                    queryParams.push(key);
                                }
                            }
                        }

                        var payload = serializer.extract(store, type, adapterPayload, null, 'findQuery');

                        Ember.assert('The response from a findQuery must be an Array, not ' + Ember.inspect(payload), Ember.typeOf(payload) === 'array');

                        for (var i = 0; i < payload.length; i++) {
                            for (key in payload[i]) {
                                if (payload[i].hasOwnProperty(key) && !queryParams.contains(key)) {
                                    delete payload[i][key];
                                }
                            }
                        }
                        //recordArray.load(payload);
                        return payload;
                    }, null, 'DS: Extract payload of findQuery ' + type);
                }

                function coerceId(id) {
                    return Boolean(id) ? null : id + '';
                }

                Ember.warn('You need to pass a type to the store\'s find method', arguments.length >= 1);
                Ember.warn('You may not pass `' + id + '` as id to the store\'s find method', arguments.length === 1 || !Ember.isNone(id));

                if (arguments.length === 1) {
                    promise = self.findAll(type);
                } else if (Ember.typeOf(id) === 'object') {
                    promise = findQuery(type, id);
                } else {
                    promise = self.findById(type, coerceId(id));
                }

                var deferred = Ember.RSVP.defer();

                promise.then(function(result) {
                    var i;
                    var objects = [];

                    Ember.run.later(function() {

                        var updateMany = function(self, objects, type, params) {
                            objects.push(self.update(type, params));
                        };

                        for (i = 0; i < result.length; i++) {
                            updateMany(self, objects, type, result[i]);
                        }

                        deferred.resolve(objects);
                    }, 0);

                });
                return promiseArray(deferred.promise);
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
                return Ember.isNone(serialized) ? '' : String(serialized);
            }
        });

        /**
         * Приводит приходящий объект date:{} к нужному формату даты
         * TODO Смена формата в зависимости от языка системы
         * DS.attr('date')
         * @type {*|void|Object}
         */
        UMI.CustomDateTransform = DS.Transform.extend({
            deserialize: function(deserialized) {
                deserialized = Ember.isNone(deserialized) ? '' : String(deserialized);
                if (deserialized) {
                    deserialized = moment(deserialized).format('DD.MM.YYYY');
                }
                return deserialized;
            },
            serialize: function(serialized) {
                if (serialized) {
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
            deserialize: function(deserialized) {
                var date = Ember.get(deserialized || '', 'date');
                if (date) {
                    Ember.set(deserialized, 'date', moment(date).format('DD.MM.YYYY HH:mm:ss'));
                    deserialized = JSON.stringify(deserialized);
                } else {
                    deserialized = '';
                }
                return deserialized;
            },
            serialize: function(serialized) {
                var date;
                if (serialized) {
                    try {
                        serialized = JSON.parse(serialized);
                        date = Ember.get(serialized, 'date');
                        if (date) {
                            Ember.set(serialized, 'date', moment(date, 'DD.MM.YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss'));
                        }
                    } catch (error) {
                        error.message = 'Incorrect field value. Expected array or null. ' + error.message;
                        this.get('container').lookup('route:application').send('backgroundError', error);
                    }
                } else {
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
            deserialize: function(deserialized) {
                if (deserialized) {
                    if (Ember.typeOf(deserialized) === 'array') {
                        deserialized.sort();
                    }
                    deserialized = JSON.stringify(deserialized);
                } else {
                    deserialized = '';
                }
                return deserialized;
            },
            serialize: function(serialized) {
                if (serialized) {
                    try {
                        serialized = JSON.parse(serialized);
                    } catch (error) {
                        error.message = 'Incorrect field value. Expected array or null. ' + error.message;
                        this.get('container').lookup('route:application').send('backgroundError', error);
                    }
                } else {
                    serialized = [];
                }
                return serialized;
            }
        });

        UMI.ObjectRelationTransform = UMI.SerializedTransform.extend({
            serialize: function(serialized) {
                if (serialized) {
                    try {
                        serialized = JSON.parse(serialized);
                        serialized = {
                            collection: serialized.collection ? serialized.collection :
                                Ember.get(serialized, 'meta.collectionName'),
                            guid: serialized.guid
                        };
                    } catch (error) {
                        error.message = 'Incorrect field value. Expected array or null. ' + error.message;
                        this.get('container').lookup('route:application').send('backgroundError', error);
                    }
                } else {
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
            serialize: function(deserialized) {
                return deserialized;
            },
            deserialize: function(serialized) {
                return serialized;
            }
        });

        /**
         * Вывод всех ajax ошибок в tooltip
         */
        $.ajaxSetup({
            headers: {'X-Csrf-Token': Ember.get(window, 'UmiSettings.token')},
            error: function(error) {
                var activeTransition = UMI.__container__.lookup('router:main').router.activeTransition;
                if (activeTransition) {
                    activeTransition.send('backgroundError', error);
                } else {
                    UMI.__container__.lookup('route:application').send('backgroundError', error);
                }
            }
        });

        templatesExtends();
        validators(UMI);
        models(UMI);
        router(UMI);
        controller(UMI);
        views(UMI);

        return UMI;
    }
);

define('topBar/main',[
    'App'
], function(UMI) {
    

    UMI.TopBarView = Ember.View.extend({
        templateName: 'partials/topBar',

        activeProject: function() {
            return window.location.host;
        }.property(),

        siteUrl: function() {
            return Ember.get(window, 'UmiSettings.baseSiteURL');
        }.property(),

        userName: function() {
            return Ember.get(window, 'UmiSettings.user.displayName');
        }.property(),//TODO: reload after logout

        modules: function() {
            return this.get('controller.modules');
        }.property()
    });
});
define('topBar', ['topBar/main'], function (main) { return main; });

define('divider/view',['App'], function(UMI) {
    
    return function() {

        UMI.DividerView = Ember.View.extend({
            classNames: ['off-canvas-wrap', 'umi-divider-wrapper', 's-full-height'],

            didInsertElement: function() {
                this.fakeDidInsertElement();
            },

            willDestroyElement: function() {
                this.removeObserver('model');
            },

            modelChange: function() {
                this.fakeDidInsertElement();
            }.observes('model'),

            fakeDidInsertElement: function() {
                var $el = this.$();
                var $divider = $el.find('.umi-divider');
                var $sidebar = $el.find('.umi-divider-left');
                var $content = $el.find('.umi-divider-right');
                var $gridElements = $el.find('.magellan-content').find('.umi-columns.large-4');

                $el.off('mousedown.umi.divider.toggle');
                $divider.off('mousedown.umi.divider.proccess');

                if ($sidebar.length) {
                    $el.on('mousedown.umi.divider.toggle', '.umi-divider-left-toggle', function() {
                        $sidebar.toggleClass('hide');
                        $(this).children('.icon').toggleClass('icon-left').toggleClass('icon-right');
                        $('html').trigger('toggled.umi.divider');
                    });


                    $divider.on('mousedown.umi.divider.proccess', function(event) {
                        if (event.button === 0) {
                            $sidebar.removeClass('divider-virgin');
                            $('html').addClass('s-unselectable');

                            $('html').on('mousemove.umi.divider.proccess', function(event) {
                                var sidebarWidth = event.pageX - $el.offset().left;
                                sidebarWidth = sidebarWidth < 150 ? 150 : sidebarWidth;
                                sidebarWidth = sidebarWidth > 500 ? 500 : sidebarWidth;

                                if (sidebarWidth > $el.width() - 720) {
                                    $gridElements.removeClass('large-4').addClass('large-12');
                                } else {
                                    $gridElements.removeClass('large-12').addClass('large-4');
                                }

                                $content.css({marginLeft: sidebarWidth + 1});
                                $sidebar.css({width: sidebarWidth});

                                $('html').on('mouseup.umi.divider.proccess', function() {
                                    $('html').off('mousemove.umi.divider.proccess');
                                    $('html').removeClass('s-unselectable');
                                });
                            });
                        }
                    });
                } else {
                    $content.css({'marginLeft': ''});
                }
            }
        });
    };
});

define('divider/main',[
    'App', './view'
], function(UMI, view) {
    

    view();
});
define('divider', ['divider/main'], function (main) { return main; });

define('dock/controllers',['App'], function(UMI) {
    

    return function() {
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],
            modulesBinding: 'controllers.application.modules',
            sortedModules: function() {
                var userSettings = UMI.Utils.LS.get('dock');
                var modules = this.get('modules');
                if (Ember.typeOf(userSettings) === 'array') {
                    var sortedModules = [];
                    modules = modules.slice();
                    for (var i = 0, l = userSettings.length; i < l; i++) {
                        for (var j = 0, l2 = modules.length; j < l2; j++) {
                            if (modules[j] && modules[j].name === userSettings[i]) {
                                sortedModules.push(modules[j]);
                                modules.splice(j, 1);
                            }
                        }
                    }
                    return sortedModules.concat(modules);
                } else {
                    return modules;
                }
            }.property('modules'),
            activeModuleBinding: 'controllers.module.model'
        });
    };
});
define('dock/view',['App'], function(UMI) {
    

    return function() {

        var expanded = false;
        var move = {};
        var def = {old: 0, cur: 0, def: 0, coeff: 1 };
        var intervalLeaveItem;

        UMI.DockView = Ember.View.extend({
            templateName: 'partials/dock',

            classNames: ['umi-dock', 's-unselectable'],

            didInsertElement: function() {
                var self = this;
                var dock = self.$().find('.dock')[0];
                dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                $(dock).addClass('active');

                if (!dock.style.marginLeft) {
                    dock.style.marginLeft = 0;
                }

                var futureOffset;

                var moving = function(el, event) {
                    move.proccess = true;
                    var isDropdown = $(event.target).closest('.dropdown-menu').size();
                    var elOffsetLeft = el.offsetLeft;
                    var elWidth = el.offsetWidth;
                    var dockParentWidth = el.parentNode.offsetWidth;
                    def.cur = event.clientX;

                    if (def.old) {
                        def.def = def.old - def.cur;
                    }

                    if (Math.abs(elOffsetLeft) + elWidth > dockParentWidth && !isDropdown) {
                        if (def.def > 0) {
                            // move left
                            def.coeff = Math.abs(elOffsetLeft) / (event.clientX);
                            futureOffset = Math.round(parseInt(el.style.marginLeft, 10) + def.def * def.coeff);

                            if (def.coeff > 0 && futureOffset + parseInt(el.style.left, 10) < -20) {
                                el.style.marginLeft = futureOffset + 'px';
                            }
                        } else if (def.def < 0) {
                            // move right
                            def.coeff = Math.abs((elWidth - dockParentWidth + elOffsetLeft) /
                                (dockParentWidth - event.clientX));
                            futureOffset = Math.round(parseInt(el.style.marginLeft, 10) + def.def * def.coeff);

                            if (def.coeff > 0 && dockParentWidth < elWidth - 20 + (futureOffset +
                                (parseInt(el.style.left, 10)))) {
                                el.style.marginLeft = futureOffset + 'px';
                            }
                        }
                    }
                    def.old = event.clientX;
                };

                $(dock).mousemove(function(event) {
                    if (!move.oldtime) {
                        move.oldtime = new Date();
                    }

                    move.curtime = new Date();

                    if (move.curtime - move.oldtime > 700 || move.proccess) {
                        moving(this, event);
                    }
                });

                $(window).on('resize.umi.dock', function() {
                    setTimeout(function() {
                        dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                    }, 0);
                });
            },

            mouseLeave: function(event) {
                var self = this;
                var dock = self.$().find('.dock')[0];
                def.old = false;

                if (!event.relatedTarget) {
                    $(document.body).bind('mouseover', function(e) {
                        if ($(dock).hasClass('full') && !($(e.target).closest('.dock')).size()) {
                            self.leaveDock();
                        }
                        $(this).unbind('mouseover');
                    });
                    return;
                }
                this.needDockMinimize = true;
                this.leaveDock();
            },

            leaveDock: function() {
                if (this.isBlocked || !this.needDockMinimize) {
                    return;
                }
                var self = this;
                var dock = self.$().find('.dock')[0];
                expanded = false;
                move.oldtime = false;
                move.proccess = false;
                self.needDockMinimize = false;

                $(dock).find('.umi-dock-module-icon').stop().animate({margin: '9px 11px 9px', height: 30, width: 30}, {
                    duration: 130,
                    easing: 'linear'
                });

                $(dock).animate({marginLeft: '0px'}, {duration: 130, easing: 'linear', complete: function() {
                    $(dock).removeClass('full');
                }});
            },

            willDestroyElement: function() {
                $(window).off('resize.umi.dock');
            },

            isBlocked: false,

            needDockMinimize: false
        });

        UMI.DockModuleButtonView = Ember.View.extend({
            tagName: 'li',

            classNames: ['umi-dock-button'],

            attributeBindings: ['name:data-name'],

            name: function() {
                return this.get('module.name');
            }.property('module.name'),

            mouseEnter: function() {
                var self = this;
                var dock = self.$().closest('.dock');
                var $el = self.$();

                var onHover = function() {
                    self.set('parentView.needDockMinimize', false);
                    if (!expanded) {
                        expanded = true;
                        move.proccess = false;
                        var posBegin = $el.position().left + $el[0].offsetWidth / 2 +
                            (parseInt(dock[0].style.marginLeft, 10) || 0);

                        $($el[0].parentNode).find('.umi-dock-module-icon').stop()
                            .animate({height: 48, width: 48, margin: '8px 36px 28px'}, {
                                duration: 280,
                                step: function(n, o) {
                                    if (this.parentNode.parentNode === $el[0]) {
                                        dock[0].style.marginLeft = posBegin - (o.elem.parentNode.parentNode.offsetLeft +
                                            o.elem.parentNode.offsetWidth / 2) + 'px';
                                    }
                                },
                                complete: function() {
                                    dock.addClass('full');
                                    move.proccess = true;
                                }
                            });
                    }
                };

                !intervalLeaveItem || clearTimeout(intervalLeaveItem);
                intervalLeaveItem = setTimeout(function() {
                    onHover();
                }, 120);
            },

            mouseLeave: function() {
                if (intervalLeaveItem) {
                    clearTimeout(intervalLeaveItem);
                }
            },

            mouseDown: function(e) {
                e.preventDefault();
                var self = this;
                var $el = self.$();
                var $dock = self.$().closest('.dock');
                var $body = $(document.body);
                var curPos = e.pageX;
                var elPos = $el.position().left;
                var elStartPos = elPos;
                var elIndex = 0;
                var $empty = $('<li class="umi-dock-button-empty">');
                var moved = false;

                $body.on('mousemove.sort.umi.dock', function(e) {
                    elPos = elPos + e.pageX - curPos;

                    if (!moved) {
                        self.set('parentView.isBlocked', true);
                        $el.addClass('umi-dock-button-dragging').after($empty);
                        moved = true;
                    }

                    var tmpIndex = Math.round((elPos - elStartPos) / 120); // 120 - width of the element in the dock
                    var $newEl;
                    if (tmpIndex > elIndex) {
                        $newEl = $empty.nextAll('li:not(.umi-dock-button-dragging):first');
                        if ($newEl.length) {
                            $newEl.after($empty);
                            elIndex++;
                        }
                    }
                    if (tmpIndex < elIndex) {
                        $newEl = $empty.prevAll('li:not(.umi-dock-button-dragging):first');
                        if ($newEl.length) {
                            $newEl.before($empty);
                            elIndex--;
                        }
                    }
                    $el.css({left: elPos});
                    curPos = e.pageX;
                }).on('mouseup.sort.umi.dock', function(e) {
                    $body.off('.sort.umi.dock');

                    if (!moved) {
                        return;
                    }

                    $empty.after($el.removeClass('umi-dock-button-dragging').css({left: ''})).remove();

                    self.set('parentView.isBlocked', false);
                    self.get('parentView').leaveDock();

                    var mass = [];
                    $dock.children('li').each(function() {
                        mass.push($(this).data('name'));
                    });
                    UMI.Utils.LS.set('dock', mass);
                });
            }
        });
    };
});

define('dock/main',['./controllers', './view', 'App'], function(controller, view) {
    
    controller();
    view();
});
define('dock', ['dock/main'], function (main) { return main; });

define('toolbar/view',['App'], function(UMI) {
    

    return function() {

        UMI.ToolbarElement = Ember.Mixin.create({
            tagName: 'li',

            template: function() {
                var self = this;
                var type = this.get('context.type');
                if (this.get(type + 'View')) {
                    return Ember.Handlebars.compile('{{view view.' + type + 'View meta=this}}');
                } else {
                    try {
                        throw new Error('View c типом ' + type + ' не зарегестрирован для toolbar');
                    } catch (error) {
                        Ember.run.next(function() {
                            self.get('controller').send('backgroundError', error);
                        });
                    }
                }
            }.property(),

            buttonView: function() {
                var behaviourName = this.get('context.behaviour.name');
                var dirtyBehaviour = Ember.get(UMI.buttonBehaviour, behaviourName) || {};
                var behaviour = {};
                for (var key in dirtyBehaviour) {
                    if (dirtyBehaviour.hasOwnProperty(key)) {
                        behaviour[key] = dirtyBehaviour[key];
                    }
                }
                var instance = UMI.ButtonView.extend(behaviour);
                return instance;
            }.property(),

            dropdownButtonView: function() {
                var behaviourName = this.get('context.behaviour.name');
                var dirtyBehaviour = Ember.get(UMI.dropdownButtonBehaviour, behaviourName) || {};
                var behaviour = {};
                for (var key in dirtyBehaviour) {
                    if (dirtyBehaviour.hasOwnProperty(key)) {
                        behaviour[key] = dirtyBehaviour[key];
                    }
                }
                var instance = UMI.DropdownButtonView.extend(behaviour);
                return instance;
            }.property(),

            splitButtonView: function() {
                var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent);
                var behaviourName = this.get('context.behaviour.name');
                var dirtyBehaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                var behaviour = {};
                for (var key in dirtyBehaviour) {
                    if (dirtyBehaviour.hasOwnProperty(key)) {
                        behaviour[key] = dirtyBehaviour[key];
                    }
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

            elementView: Ember.View.extend(UMI.ToolbarElement),
            didInsertElement: function() {
                var $el = this.$();
                var $buttonGroup = $el.find('.button-group');
                var buttonGroupWidth = $buttonGroup.width();
                var nextElementsWidth = 0;
                var $nextElements = $buttonGroup.next();
                if ($nextElements.length) {
                    nextElementsWidth = $nextElements.width();
                    buttonGroupWidth += nextElementsWidth + 60;
                }
                var toggleLabel = function(needShow) {
                    var $button = $buttonGroup.find('.button');
                    if (needShow) {
                        $buttonGroup.removeClass('umi-hide-button-label');
                    } else {
                        $buttonGroup.addClass('umi-hide-button-label');
                    }
                    if ($button.length) {
                        $button.each(function(index) {
                            var label = '';
                            if (!needShow) {
                                label = $($button[index]).find('.umi-button-label').text();
                            }
                            $($button[index]).attr('title', label);
                        });
                    }
                };

                if ($buttonGroup.length) {
                    if (buttonGroupWidth >= $el.width()) {
                        toggleLabel();
                    }
                }
                $(window).on('resize.umi.toolbar', function() {
                    if (buttonGroupWidth >= $el.width()) {
                        toggleLabel();
                    } else {
                        toggleLabel(true);
                    }
                });
            },
            willDestroyElement: function() {
                $(window).off('resize.umi.toolbar');
            }
        });
    };
});
define('partials/toolbar/buttons/globalBehaviour',['App'], function(UMI) {
        

        return function() {
            /**
             * Абстрактный класс поведения
             * @class
             * @abstract
             */
            function GlobalBehaviour() {
            }

            GlobalBehaviour.prototype = {
                save: {
                    extendButton: {
                        label: function() {
                            if (this.get('controller.object.isDirty')) {
                                return this.get('defaultBehaviour.attributes.label');
                            } else {
                                return this.get('meta.attributes.states.notModified.label');
                            }
                        }.property('meta.attributes.label', 'controller.object.isDirty', 'defaultBehaviour'),

                        classNameBindings: ['controller.object.isDirty::disabled',
                            'controller.object.isValid::disabled']
                    },

                    beforeSave: function() {
                        var model = this.get('controller.object');
                        if (!model.get('isDirty') || !model.get('isValid')) {
                            return false;
                        }
                        var button = this.$().children('.button');
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },

                    actions: {
                        save: function() {
                            var params = this.beforeSave();
                            if (params) {
                                this.get('controller').send('save', params);
                            }
                        },

                        saveAndGoBack: function() {
                            var params = this.beforeSave();
                            if (params) {
                                this.get('controller').send('saveAndGoBack', params);
                            }
                        }
                    }
                },

                'create': {
                    actions: {
                        'create': function(params) {
                            var behaviour = params.behaviour;
                            var object = params.object || this.get('controller.object');
                            this.get('controller').send('create', {behaviour: behaviour, object: object});
                        }
                    }
                },

                switchActivity: {
                    label: function() {
                        if (this.get('controller.object.active')) {
                            return this.get('meta.attributes.states.deactivate.label');
                        } else {
                            return this.get('meta.attributes.states.activate.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.active'),

                    classNameBindings: ['controller.object.active::umi-disabled'],

                    iconClass: function() {
                        var iconClass = 'inactive';
                        if (this.get('controller.object.active')) {
                            iconClass = 'active';
                        }
                        return 'icon-' + iconClass;
                    }.property('meta.behaviour.name', 'controller.object.active'),

                    actions: {
                        switchActivity: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('switchActivity', model);
                        }
                    }
                },

                backToFilter: {
                    classNames: ['wide-medium', 'umi-toolbar-button-border'],

                    actions: {
                        backToFilter: function() {
                            this.get('controller').send('backToFilter');
                        }
                    }
                },

                trash: {
                    actions: {
                        trash: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('trash', model);
                        }
                    }
                },

                untrash: {
                    actions: {
                        untrash: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('untrash', model);
                        }
                    }
                },

                'delete': {
                    actions: {
                        'delete': function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('delete', model);
                        }
                    }
                },

                viewOnSite: {
                    actions: {
                        viewOnSite: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('viewOnSite', model);
                        }
                    }
                },

                edit: {
                    actions: {
                        edit: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('edit', model);
                        }
                    }
                },

                add: {
                    classNameBindings: ['controller.object.isValid::disabled'],

                    beforeAdd: function(params) {
                        params = params || {};
                        var model = params.object || this.get('controller.object');
                        if (!model.get('isValid')) {
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },

                    actions: {
                        add: function(params) {
                            params = params || {};
                            var addParams = this.beforeAdd(params.object);
                            if (addParams) {
                                this.get('controller').send('add', addParams);
                            }
                        },

                        addAndGoBack: function() {
                            var params = this.beforeAdd();
                            if (params) {
                                this.get('controller').send('addAndGoBack', params);
                            }
                        },

                        addAndCreate: function() {
                            var params = this.beforeAdd();
                            if (params) {
                                this.get('controller').send('addAndCreate', params);
                            }
                        }
                    }
                },

                importFromRss: {
                    actions: {
                        importFromRss: function() {
                            var model = this.get('controller.object');
                            this.get('controller').send('importFromRss', model);
                        }
                    }
                },

                switchRobots: {
                    isAllowedRobots: null,

                    label: function() {
                        var title;
                        if (this.get('isAllowedRobots')) {
                            title = this.get('meta.attributes.states.disallow.label');
                        } else {
                            title = this.get('meta.attributes.states.allow.label');
                        }
                        return title;
                    }.property('meta.attributes.label', 'isAllowedRobots'),

                    iconClass: function() {
                        if (this.get('isAllowedRobots')) {
                            return 'icon-allowRobots';
                        } else {
                            return 'icon-disallowRobots';
                        }
                    }.property('isAllowedRobots'),

                    actions: {
                        switchRobots: function() {
                            var self = this;
                            var defer = Ember.RSVP.defer();
                            var promise = defer.promise;
                            var currentState = this.get('isAllowedRobots');
                            var model = this.get('controller.object');
                            this.get('controller').send('switchRobots', model, currentState, defer);
                            promise.then(function() {
                                self.set('isAllowedRobots', !currentState);
                            });
                        }
                    },

                    checkIsAllowedRobots: function() {
                        var self = this;
                        var object = this.get('controller.object');
                        var componentController = this.get('container').lookup('controller:component');
                        var isAllowedRobotsSource;

                        if (componentController) {
                            isAllowedRobotsSource = componentController.get('settings.actions.isAllowedRobots.source');
                            return $.get(isAllowedRobotsSource + '?id=' + object.get('id')).then(function(results) {
                                results = results || {};
                                self.set('isAllowedRobots', Ember.get(results, 'result.isAllowedRobots'));
                            });
                        }
                    },

                    willDestroyElement: function() {
                        this.removeObserver('label');
                    },

                    labelChange: function() {
                        var $el = this.$();
                        if ($el && $el.attr('title')) {
                            $el.attr('title', this.get('label'));
                        }
                    }.observes('label').on('didInsertElement'),

                    init: function() {
                        this._super();
                        Ember.run.once(this, 'checkIsAllowedRobots');
                    }
                }
            };

            UMI.globalBehaviour = new GlobalBehaviour();
        };
    });
define('partials/toolbar/buttons/button/main',['App'], function(UMI) {
        

        return function() {
            UMI.ButtonView = Ember.View.extend({
                tagName: 'a',

                templateName: 'partials/button',

                classNameBindings: 'meta.attributes.class',

                attributeBindings: ['title'],

                title: Ember.computed.alias('meta.attributes.title'),

                label: function() {
                    return this.get('meta.attributes.label');
                }.property('meta.attributes.label'),

                iconClass: function() {
                    return 'icon-' + this.get('meta.behaviour.name');
                }.property('meta.behaviour.name'),

                click: function() {
                    var behaviour = this.get('meta').behaviour;
                    var params = {
                        behaviour: behaviour
                    };
                    this.send(behaviour.name, params);
                }
            });

            function ButtonBehaviour() {
            }

            ButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);
            UMI.buttonBehaviour = new ButtonBehaviour();
        };
    });
define('partials/toolbar/buttons/dropdownButton/main',['App', 'moment'], function(UMI, moment) {
        

        return function() {
            UMI.DropdownButtonView = Ember.View.extend({
                templateName: 'partials/dropdownButton',

                dropdownId: function() {
                    return Foundation.utils.random_str();
                }.property(),

                dropdownClassName: null,

                _button: {
                    classNameBindings: 'meta.attributes.class',

                    attributeBindings: ['dataDropdown:data-dropdown', 'title', 'dataOptions:data-options'],

                    dataDropdown: function() {
                        return this.get('parentView.dropdownId');
                    }.property(),

                    title: Ember.computed.alias('meta.attributes.title'),

                    iconClass: function() {
                        return 'icon-' + this.get('meta.behaviour.name');
                    }.property('meta.behaviour.name')
                },

                extendButton: {},

                buttonView: function() {
                    var buttonView = Ember.View.extend(this.get('_button'));
                    buttonView.reopen(this.get('extendButton'));
                    return buttonView;
                }.property(),

                actions: {
                    sendActionForBehaviour: function(behaviour) {
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                }
            });

            function DropdownButtonBehaviour() {}

            DropdownButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);

            DropdownButtonBehaviour.prototype.backupList = {
                templateName: 'partials/dropdownButton/backupList',

                iScroll: null,

                dropdownClassName: 'content',

                noBackupsLabel: null,

                extendButton: {
                    dataOptions: function() {
                        return 'fastSelectHoverSelector: tr; fastSelectTarget: tr;';
                    }.property()
                },

                isLazyDropdown: true,

                getBackupList: function() {
                    var backupList;
                    var self = this;
                    var object = self.get('controller.object');
                    var settings = self.get('controller.settings');
                    var getBackupListAction = UMI.Utils.replacePlaceholder(object,
                        settings.actions.getBackupList.source);
                    var date = object.get('updated');

                    try {
                        date = JSON.parse(date);
                    } catch (error) {}

                    var currentVersion = {
                        objectId: object.get('id'),
                        id: 'current',
                        current: true,
                        isActive: true
                    };

                    var promiseArray = DS.PromiseArray.create({
                        promise: $.get(getBackupListAction).then(function(data) {
                            var results = [];
                            var serviceBackupList = Ember.get(data, 'result.getBackupList.collection.serviceBackup');
                            var users = Ember.get(data, 'result.getBackupList.collection.user');
                            var user;
                            var currentEditor;

                            UMI.i18n.setDictionary(Ember.get(data, 'result.getBackupList.i18n'), 'form.backupList');
                            self.set('noBackupsLabel', UMI.i18n.getTranslate('No backups', 'form.backupList'));
                            if (!serviceBackupList || !serviceBackupList.length) {
                                return [];
                            }

                            var setCurrentEditor = function(currentEditor) {
                                currentEditor.then(function(currentEditor) {
                                    Ember.set(currentVersion, 'user', Ember.get(currentEditor, 'displayName'));
                                });
                            };

                            currentEditor = object.get('editor');
                            if (Ember.typeOf(currentEditor) === 'instance') {
                                setCurrentEditor(currentEditor);
                            } else {
                                currentEditor = object.get('owner');
                                if (Ember.typeOf(currentEditor) === 'instance') {
                                    setCurrentEditor(currentEditor);
                                }
                            }

                            Ember.set(currentVersion, 'created', {date: UMI.i18n.getTranslate('Current version',
                                'form.backupList')});
                            results.push(currentVersion);

                            if (Ember.typeOf(serviceBackupList) === 'array') {
                                for (var i = 0; i < serviceBackupList.length; i++) {
                                    user = users.findBy('id', serviceBackupList[i].owner);
                                    serviceBackupList[i].user = user.displayName;
                                    Ember.set(serviceBackupList[i], 'created.date',
                                        moment(Ember.get(serviceBackupList[i], 'created.date'))
                                            .format('DD.MM.YYYY h:mm:ss'));
                                }

                                results = results.concat(serviceBackupList);
                            }

                            return results;
                        })
                    });

                    promiseArray.then(function() {
                        var iScroll = self.get('iScroll');
                        if (iScroll) {
                            setTimeout(function() {
                                iScroll.refresh();
                            }, 150);
                        }
                    });

                    var ArrayProxy = Ember.ArrayProxy.extend({
                        isLoaded: function() {
                            return this.get('content.isFulfilled');
                        }.property('content.isFulfilled')
                    });

                    backupList = ArrayProxy.create({
                        content: promiseArray
                    });

                    return backupList;
                },

                backupList: null,

                actions: {
                    applyBackup: function(backup) {
                        if (backup.isActive) {
                            return;
                        }

                        var self = this;
                        var object = this.get('controller.object');
                        var list = self.get('backupList');
                        var backupObjectAction;

                        var current = list.findBy('id', backup.id);
                        var setCurrent = function() {
                            list.setEach('isActive', false);
                            Ember.set(current, 'isActive', true);
                        };

                        if (backup.current) {
                            object.rollback();
                            setCurrent();
                        } else {
                            backupObjectAction = UMI.Utils.replacePlaceholder(current,
                                Ember.get(self.get('controller.settings'), 'actions.getBackup.source'));

                            $.get(backupObjectAction).then(function(data) {
                                object.rollback();

                                delete data.result.getBackup.version;
                                delete data.result.getBackup.id;

                                // При обновлении свойств не вызываются методы desialize для атрибутов модели
                                self.get('controller.store').modelFor(object.constructor.typeKey)
                                    .eachTransformedAttribute(function(name, type) {
                                    var property = Ember.get(data, 'result.getBackup.' + name);

                                    if (type === 'CustomDateTime' && Ember.typeOf(property) === 'object') {
                                        Ember.set(property, 'date',
                                            moment(property.date).format('DD.MM.YYYY h:mm:ss'));

                                        Ember.set(data, 'result.getBackup.' + name, JSON.stringify(property));
                                    }
                                });
                                object.setProperties(Ember.get(data, 'result.getBackup'));
                                setCurrent();
                            });
                        }
                    }
                },

                didInsertElement: function() {
                    var self = this;
                    var $el = this.$();
                    var scroll;

                    var scrollElement = $el.find('.s-scroll-wrap');
                    if (scrollElement.length) {
                        scroll = new IScroll(scrollElement[0], UMI.config.iScroll);
                    }
                    this.set('iScroll', scroll);

                    if (this.get('isLazyDropdown')) {
                        var isFirstLoad = true;
                        $el.children('[data-dropdown-content]').on('opened.fndtn.dropdown', function() {
                            if (isFirstLoad) {
                                isFirstLoad = false;
                                self.set('backupList', self.getBackupList());
                            }
                        });
                    }

                    self.get('controller.object').off('didUpdate');
                    self.get('controller.object').on('didUpdate', function() {
                        Ember.run.once(self, function() {
                            this.set('backupList', this.getBackupList());
                        });
                    });

                    self.get('controller').addObserver('object', function() {
                        isFirstLoad = true;
                    });
                },

                willDestroyElement: function() {
                    this.get('controller').removeObserver('object');
                    this.get('controller.object').off('didUpdate');
                }
            };


            DropdownButtonBehaviour.prototype.form = {
                templateName: 'partials/dropdownButton/form',

                dropdownClassName: 'content',

                extendButton: {
                    dataOptions: function() {
                        return 'fastSelect: false;';
                    }.property()
                },

                isLazyDropdown: true,

                formView: Ember.View.extend({
                    tagName: 'form',

                    templateName: 'partials/dropdownButton/formLayout',

                    attributeBindings: ['action'],

                    action: function() {
                        return this.get('form.attributes.action');
                    }.property('form.attributes.action'),

                    submit: function() {
                        return false;
                    },

                    object: function() {
                        var contextObject = this.get('controller.object');
                        var object = contextObject.toJSON({includeId: true});
                        return object;
                    }.property('controller.object'),

                    fieldView: function() {
                        return UMI.FieldBaseView.extend({
                            actions: {
                                submit: function() {
                                    this.get('parentView').send('submit', this.$());
                                }
                            }
                        });
                    }.property('object'),

                    actions: {
                        submit: function(handler) {
                            var self = this;
                            var object = self.get('controller.object');
                            var store = self.get('controller.store');
                            var collectionName = Ember.get(object, 'constructor.typeKey');
                            var collection = store.all(collectionName);
                            object = collection.findBy('id', object.get('id'));
                            object = object.toJSON({includeId: true});
                            if (handler) {
                                handler.addClass('loading');
                            }

                            var data = this.$().serializeArray();
                            var name;
                            for (var i = 0; i < data.length; i++) {
                                name = data[i].name;
                                if (name) {
                                    object[name] = data[i].value;
                                }
                            }
                            var serializeObject = JSON.stringify(object);
                            $.ajax({
                                url: self.get('action'),
                                type: 'POST',
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }).then(function(results) {
                                var result = Ember.get(results, 'result') || '';
                                if (!result) {
                                    Ember.run.later(self, function() {
                                        var error = new Error(UMI.i18n.getTranslate('Unknown error') + '.');
                                        self.get('controller').send('backgroundError', error);
                                    }, 100);
                                } else {
                                    var actionName = '';
                                    for (var key in result) {
                                        if (result.hasOwnProperty(key)) {
                                            actionName = key;
                                            break;
                                        }
                                    }
                                    var data = Ember.get(result, actionName);
                                    delete data.updated;
                                    delete data.created;
                                    store.update(collectionName, data);
                                    var params = {type: 'success', content: UMI.i18n.getTranslate('Saved') + '.'};
                                    UMI.notification.create(params);
                                }
                            });
                        }
                    },

                    didInsertElement: function() {
                        var self = this;
                        var $el = self.$();
                        self.$().on('submit', function() {
                            self.send('submit', $el.find('.button'));
                            return false;
                        });
                    }
                }),

                form: null,

                getForm: function() {
                    var self = this;
                    var meta = self.get('meta');

                    var action = Ember.get(self.get('controller.settings'), 'actions.' +
                        Ember.get(meta, 'behaviour.action') + '.source');
                    var promise = $.get(action);

                    var proxy = Ember.ObjectProxy.create({
                        content: null,
                        isLoaded: false
                    });

                    promise.then(function(results) {
                        var form = Ember.get(results, 'result.' + Ember.get(meta, 'behaviour.action'));
                        form = UMI.FormHelper.fillMeta(form, self.get('controller.object'));
                        proxy.set('content', form);
                        proxy.set('isLoaded', true);
                    });

                    return proxy;
                },

                didInsertElement: function() {
                    var self = this;
                    var $el = this.$();

                    if (this.get('isLazyDropdown')) {
                        var isFirstLoad = true;
                        $el.children('[data-dropdown-content]').on('opened.fndtn.dropdown', function() {
                            if (isFirstLoad) {
                                isFirstLoad = false;
                                self.set('form', self.getForm());
                            }
                        });
                    }

                    self.addObserver('controller.object', function() {
                        isFirstLoad = true;
                    });
                },

                willDestroyElement: function() {
                    this.removeObserver('controller.object');
                }
            };

            UMI.dropdownButtonBehaviour = new DropdownButtonBehaviour();
        };
    });
define('partials/toolbar/buttons/splitButton/main',['App'], function(UMI) {
        

        return function() {
            /**
             * Mixin реализующий интерфейс действия по умолчанию для split button.
             */
            UMI.SplitButtonDefaultBehaviour = Ember.Mixin.create({
                pathInLocalStorage: function() {
                    var meta = this.get('meta') || {behaviour: {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name;
                }.property(),

                defaultBehaviourIndex: null,

                defaultBehaviour: function() {
                    var index = this.get('defaultBehaviourIndex');
                    var choices = this.get('meta.behaviour.choices') || [];
                    if (choices[index]) {
                        return choices[index];
                    } else if (index > 0) {
                        this.set('defaultBehaviourIndex', 0);
                    }
                    return choices[0];
                }.property('defaultBehaviourIndex'),

                defaultBehaviourIcon: function() {
                    if (this.get('defaultBehaviour')) {
                        return 'icon-' + this.get('defaultBehaviour.behaviour.name');
                    }
                }.property('defaultBehaviour'),

                actions: {
                    toggleDefaultBehaviour: function(index) {
                        if (this.get('defaultBehaviourIndex') !== index) {
                            this.set('defaultBehaviourIndex', index);
                            UMI.Utils.LS.set(this.get('pathInLocalStorage'), index);
                        }
                    }
                },

                init: function() {
                    this._super();
                    this.set('defaultBehaviourIndex', UMI.Utils.LS.get(this.get('pathInLocalStorage')) || 0);
                }
            });

            UMI.SplitButtonDefaultBehaviourForComponent = Ember.Mixin.create({
                pathInLocalStorage: function() {
                    var componentName = this.get('controller.componentName');
                    var meta = this.get('meta') || {behaviour: {}};
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

            /**
             * элемент списка
             * @type {Class}
             */
            var ListItemView = Ember.View.extend({
                tagName: 'li',

                classNames: ['has-default-button'],

                label: function() {
                    return this.get('context.attributes.label');
                }.property('context.attributes.label'),

                icon: function() {
                    return 'icon-' + this.get('context.behaviour.name');
                }.property('context.behaviour.name'),

                isDefaultBehaviour: function() {
                    var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                    return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                }.property('parentView.defaultBehaviourIndex')
            });

            UMI.SplitButtonView = Ember.View.extend(UMI.SplitButtonDefaultBehaviour, {
                templateName: 'partials/splitButton',

                dropdownId: function() {
                    return Foundation.utils.random_str();
                }.property(),

                actions: {
                    /**
                     * @method sendActionForBehaviour
                     * @param behaviour
                     */
                    sendActionForBehaviour: function(behaviour) {
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                },

                _button: {
                    classNameBindings: ['meta.attributes.class'],

                    attributeBindings: ['meta.attributes.title'],

                    dataOptions: function() {
                        return 'replaceTarget: .button;';
                    }.property(),

                    label: function() {
                        return this.get('defaultBehaviour.attributes.label');
                    }.property('defaultBehaviour.attributes.label'),

                    title: Ember.computed.alias('meta.attributes.title'),

                    click: function(event) {
                        if ($(event.target).data('dropdown') === undefined) {
                            this.get('parentView').send('sendActionForBehaviour',
                                this.get('defaultBehaviour').behaviour);
                        }
                    }
                },

                extendButton: {},

                buttonView: function() {
                    var buttonView = Ember.View.extend(this.get('_button'));
                    buttonView.reopen(this.get('extendButton'));
                    return buttonView;
                }.property(),

                itemView: ListItemView
            });

            function SplitButtonBehaviour() {}

            SplitButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);

            SplitButtonBehaviour.prototype.contextMenu = {
                itemView: function() {
                    var baseItem = ListItemView.extend({
                        init: function() {
                            this._super();
                            var context = this.get('context');

                            if (Ember.get(context, 'behaviour.name') === 'switchActivity') {
                                this.reopen({
                                    label: function() {
                                        if (this.get('controller.object.active')) {
                                            return this.get('context.attributes.states.deactivate.label');
                                        } else {
                                            return this.get('context.attributes.states.activate.label');
                                        }
                                    }.property('context.attributes.label', 'controller.object.active')
                                });
                            }
                        }
                    });

                    return baseItem;
                }.property(),

                init: function() {
                    this._super();
                    var behaviour = {};
                    var i;
                    var action;
                    var choices = this.get('context.behaviour.choices');

                    if (Ember.typeOf(choices) === 'array') {
                        for (i = 0; i < choices.length; i++) {
                            action = '';
                            var behaviourAction = Ember.get(UMI.splitButtonBehaviour, choices[i].behaviour.name);
                            if (behaviourAction) {
                                action = behaviourAction.actions[choices[i].behaviour.name];
                                if (action) {
                                    if (Ember.typeOf(behaviour.actions) !== 'object') {
                                        behaviour.actions = {};
                                    }
                                    behaviour.actions[choices[i].behaviour.name] = action;
                                }
                            }
                        }
                    }

                    this.reopen(behaviour);
                }
            };

            UMI.splitButtonBehaviour = new SplitButtonBehaviour();
        };
    });
define('toolbar/buttons/main',[
    'partials/toolbar/buttons/globalBehaviour', 'partials/toolbar/buttons/button/main',
    'partials/toolbar/buttons/dropdownButton/main', 'partials/toolbar/buttons/splitButton/main'
], function(globalBehaviour, button, dropdownButton, splitButton) {
        

        return function() {
            globalBehaviour();
            button();
            dropdownButton();
            splitButton();
        };
    });
define('toolbar/main',['App', './view', './buttons/main'], function(UMI, view, buttons) {
    

    buttons();
    view();
});
define('toolbar', ['toolbar/main'], function (main) { return main; });

define('tableControl/controllers',['App'], function(UMI) {
    

    return function() {
        UMI.TableControlMixin = Ember.Mixin.create(UMI.i18nInterface, {
            /**
             * @abstract
             * @property collectionName
             */
            collectionName: null,

            dictionaryNamespace: 'tableControl',

            hasContextMenu: false,

            localDictionary: function() {
                var filter = this.get('control') || {};
                return filter.i18n;
            }.property(),

            /**
             * Данные
             * @property objects
             */
            objects: null,

            fieldsList: null,

            objectChange: function() {
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted'),

            updateObjectDeleted: function() {//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item) {
                    if (item && item.get('isDeleted')) {
                        objects.removeObject(item);
                    }
                });
            },

            /**
             * метод получает данные учитывая query параметры
             * @method getObjects
             */
            getObjects: function() {
                var self = this;
                self.send('showLoader');
                var query = this.get('query') || {};
                var collectionName = self.get('collectionName');
                var objects = self.store.updateCollection(collectionName, query);
                var orderByProperty = self.get('orderByProperty');
                var sortProperties = orderByProperty && orderByProperty.property ? orderByProperty.property : 'id';
                var sortAscending = orderByProperty && 'direction' in orderByProperty ? orderByProperty.direction :
                    true;
                objects.then(function() {
                    self.send('hideLoader');
                });
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
            order: function() {
                var orderByProperty = this.get('orderByProperty');
                if (orderByProperty) {
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
            nativeFields: function() {
                var nativeFieldsList = this.get('nativeFieldsList');
                if (Ember.typeOf(nativeFieldsList) === 'array' && nativeFieldsList.length) {
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
            relatedFields: function() {
                var relatedFields = this.get('relatedFieldsList');
                if (Ember.typeOf(relatedFields) === 'object' && JSON.stringify(relatedFields) !== '{}') {
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
            filters: function() {
                var filters = {};
                var filter;
                var filterParams = this.get('filterParams') || {};
                var collectionFilterParams = this.get('collectionFilterParams') || {};
                for (filter in collectionFilterParams) {
                    if (collectionFilterParams.hasOwnProperty(filter)) {
                        if (Ember.typeOf(collectionFilterParams[filter]) === 'string' && !collectionFilterParams[filter].length) {
                            delete filters[filter];
                        } else {
                            filters[filter] = collectionFilterParams[filter];
                        }
                    }
                }
                for (filter in filterParams) {
                    if (filterParams.hasOwnProperty(filter)) {
                        if (Ember.typeOf(filterParams[filter]) === 'string' && !filterParams[filter].length) {
                            delete filters[filter];
                        } else {
                            filters[filter] = filterParams[filter];
                        }
                    }
                }
                return filters;
            }.property('filterParams.@each', 'collectionFilterParams.@each'),

            setFilters: function(property, filter) {
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
            query: function() {
                var query = {};
                var nativeFields = this.get('nativeFields');
                var relatedFields = this.get('relatedFields');
                var limit = this.get('limit');
                var filters = this.get('filters');
                var offset = this.get('offset');
                var order = this.get('order');
                if (nativeFields) {
                    query.fields = nativeFields;
                }
                if (relatedFields) {
                    query['with'] = relatedFields;
                }
                if (limit) {
                    query.limit = limit;
                }
                if (filters) {
                    query.filters = filters;
                }
                if (offset) {
                    query.offset = offset * limit;
                }
                if (order) {
                    query.orderBy = order;
                }
                return query;
            }.property('limit', 'filters', 'offset', 'order', 'nativeFields', 'relatedFields'),

            /**
             * Метод вызывается при смене контекста (компонента).
             * Сбрасывает значения фильтров,вызывает метод getObjects, вычисляет total
             * @method updateContent
             */
            updateContent: function() {
                var store = this.get('store');
                // Вычисляем фильтр в зависимости от типа коллекции
                var collectionName = this.get('collectionName');
                var metaForCollection = store.metadataFor(collectionName);

                //TODO: check user configurations
                var modelForCollection = store.modelFor(collectionName);
                var fieldsList = this.get('control.meta.form.elements') || [];
                var defaultFields = this.get('control.meta.defaultFields') || [];

                var i;
                for (i = 0; i < fieldsList.length; i++) {
                    if (!defaultFields.contains(fieldsList[i].dataSource)) {
                        fieldsList.splice(i, 1);
                        --i;
                    }
                }

                var nativeFieldsList = [];
                var relatedFieldsList = {};

                var filterParams = this.get('control.params.filter') || {};

                modelForCollection.eachAttribute(function(name) {
                    var selfProperty = fieldsList.findBy('dataSource', name);
                    if (selfProperty) {
                        nativeFieldsList.push(selfProperty.dataSource);
                    } else if (name === 'active') {
                        nativeFieldsList.push('active');
                    } else if (name === 'trashed' && !Ember.get(filterParams, 'trashed')) {
                        filterParams.trashed = 'equals(0)';
                    }
                });

                modelForCollection.eachRelationship(function(name, relatedModel) {
                    var i;
                    var relatedModelDataSource;
                    if (relatedModel.kind === 'belongsTo') {
                        for (i = 0; i < fieldsList.length; i++) {
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if (relatedModelDataSource === name) {
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                            } else if (relatedModelDataSource.indexOf(name + '.', 0) === 0) {
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                                relatedFieldsList[name].push(relatedModelDataSource.slice(name.length + 1));
                            }
                        }
                    } else if (relatedModel.kind === 'hasMany' || relatedModel.kind === 'manyToMany') {
                        for (i = 0; i < fieldsList.length; i++) {
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if (relatedModelDataSource === name || relatedModelDataSource.indexOf(name + '.', 0) === 0) {
                                fieldsList.splice(i, 1);
                                --i;
                            }
                        }
                        //Ember.assert('Поля с типом hasMany и manyToMany недопустимы в фильтре.'); TODO: uncomment
                    }

                    if (relatedFieldsList[name]) {
                        relatedFieldsList[name] = relatedFieldsList[name].join(',');
                    }
                });

                // Сбрасываем параметры запроса, не вызывая обсервер query
                this.set('withoutChangeQuery', true);
                this.setProperties({nativeFieldsList: nativeFieldsList, relatedFieldsList: relatedFieldsList, offset: 0, orderByProperty: null, total: 0, collectionFilterParams: filterParams});
                this.set('withoutChangeQuery', false);

                this.getObjects();
                Ember.run.next(this, function() {
                    var self = this;
                    this.get('objects.content').then(function() {
                        self.set('total', metaForCollection.total);
                        self.set('fieldsList', fieldsList);
                    });
                });
            },

            /**
             * @abstract
             */
            contextChange: null,

            /**
             * Метод вызывается при изменении параметров запроса.
             * @method queryChanged
             */
            queryChanged: function() {
                if (this.get('withoutChangeQuery')) {
                    return;
                }
                Ember.run.once(this, 'getObjects');
            }.observes('query'),

            /**
             * Возвращает список кнопок контекстного меню
             * @property contextToolbar
             * return Array
             */
            contextToolbar: function() {
                var filter = this.get('control') || {};
                return filter.contextToolbar;
            }.property('model'),

            /**
             * Возвращает toolbar
             * @property toolbar
             * return Array
             */
            toolbar: function() {
                var filter = this.get('control') || {};
                return filter.toolbar || [];
            }.property('model'),

            actions: {
                orderByProperty: function(propertyName, sortAscending) {
                    this.set('orderByProperty', {'property': propertyName, 'direction': sortAscending});
                }
            }
        });

        UMI.TableControlController = Ember.ObjectController.extend(UMI.TableControlMixin, {
            needs: ['component'],

            componentNameBinding: 'controllers.component.name',

            hasContextMenu: true,

            collectionName: function() {
                var dataSource = this.get('controllers.component.dataSource.name');
                if (!dataSource) {
                    dataSource = this.get('controllers.component.selectedContext');
                }
                return dataSource;
            }.property('controllers.component.dataSource.name', 'controllers.component.selectedContext'),

            itemController: 'tableControlContextToolbarItem',

            updateObjectDeleted: function() {//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item) {
                    if (item && item.get('isDeleted')) {
                        objects.get('content.content').removeObject(item);
                    }
                });
            },

            contextChange: function() {
                this.updateContent();
            }.observes('content.object.id').on('init'),

            objectChange: function() {
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted')
        });

        UMI.TableControlContextToolbarItemController = Ember.ObjectController.extend({
            objectBinding: 'content',
            componentNameBinding: 'parentController.componentName',

            isSelected: function() {
                var objectGuid = this.get('object.guid');
                return objectGuid === this.get('parentController.control.meta.activeObjectGuid');
            }.property('parentController.control.meta.activeObjectGuid')
        });

        UMI.TableControlSharedController = Ember.ObjectController.extend(UMI.TableControlMixin, {
            collectionName: function() {
                return this.get('control.collectionName');
            }.property('control.collectionName'),

            contextChange: function() {
                this.updateContent();
            }.observes('collectionName').on('init'),

            actions: {
                executeBehaviour: function(behaviourName, object) {
                    var behaviour = this.get('control.behaviour.' + behaviourName);

                    if (Ember.typeOf(behaviour) === 'function') {
                        behaviour(this, object);
                    } else {
                        Ember.warn('Behaviour for row click did not set.');
                    }
                }
            }
        });
    };
});
define('tableControl/view',['App', 'toolbar'], function(UMI) {
    
    return function() {

        UMI.TableControlViewMixin = Ember.Mixin.create({
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
            /**
             * @property iScroll
             */
            iScroll: null,
            /**
             * При изменении данных вызывает ресайз скрола.
             * @method scrollUpdate
             * @observer
             */
            scrollUpdate: function() {
                var self = this;
                var tableControl = this.$();
                var objects = this.get('controller.objects.content');
                var iScroll = this.get('iScroll');

                var scrollUpdate = function() {
                    Ember.run.scheduleOnce('afterRender', self, function() {
                        // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                        //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                        var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                        var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];
                        //umiTableLeft.style.marginTop = 0;
                        umiTableRight.style.marginTop = 0;
                        umiTableHeader.style.marginLeft = 0;
                        setTimeout(function() {
                            iScroll.refresh();
                            iScroll.scrollTo(0, 0);
                        }, 0);
                    });
                };

                if (objects && iScroll) {
                    objects.then(function() {
                        scrollUpdate();
                    });
                }
            }.observes('controller.objects').on('didInsertElement'),
            /**
             * Событие вызываемое после вставки шаблона в DOM
             * @method didInsertElement
             */
            didInsertElement: function() {
                var tableControl = this.$();

                var self = this;
                var objects = this.get('controller.objects.content');

                // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];

                var umiTableContentRowSize = tableControl.find('.umi-table-control-content-row-size')[0];

                if (objects) {
                    var tableContent = tableControl.find('.s-scroll-wrap');

                    objects.then(function(objects) {
                        if (!objects.length) {
                            return;
                        }

                        Ember.run.scheduleOnce('afterRender', self, function() {
                            var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll);
                            self.set('iScroll', scrollContent);

                            scrollContent.on('scroll', function() {
                                //umiTableLeft.style.marginTop = this.y + 'px';
                                umiTableRight.style.marginTop = this.y + 'px';
                                umiTableHeader.style.marginLeft = this.x + 'px';
                            });

                            // После ресайза страницы необходимо изменить отступы у элементов  umiTableLeft, umiTableRight, umiTableHeader
                            $(window).on('resize.umi.tableControl', function() {
                                setTimeout(function() {
                                    //umiTableLeft.style.marginTop = scrollContent.y + 'px';
                                    umiTableRight.style.marginTop = scrollContent.y + 'px';
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                }, 100);// TODO: заменить на событие окончания ресайза iScroll
                            });

                            // Событие изменения ширины колонки
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-control-column-resizer', function() {
                                $('html').addClass('s-unselectable');
                                var handler = this;
                                $(handler).addClass('on-resize');
                                var columnEl = handler.parentNode.parentNode;
                                var columnName = columnEl.className;
                                columnName = columnName.substr(columnName.indexOf('column-id-'));
                                var columnOffset = $(columnEl).offset().left;
                                var columnWidth;
                                var contentCell = umiTableContentRowSize.querySelector('.' + columnName);

                                $('body').on('mousemove.umi.tableControl', function(event) {
                                    event.stopPropagation();
                                    columnWidth = event.pageX - columnOffset;
                                    if (columnWidth >= 60 && columnEl.offsetWidth > 59) {
                                        columnEl.style.width = contentCell.style.width = columnWidth + 'px';
                                    }
                                });

                                $('body').on('mouseup.umi.tableControl', function() {
                                    $('html').removeClass('s-unselectable');
                                    $(handler).removeClass('on-resize');
                                    $('body').off('mousemove');
                                    $('body').off('mouseup.umi.tableControl');
                                    scrollContent.refresh();
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                });
                            });

                            // Hover event
                            var getHoverElements = function(el) {
                                var isContentRow = $(el).hasClass('umi-table-control-content-row');
                                var rows = el.parentNode.querySelectorAll(isContentRow ?
                                    '.umi-table-control-content-row' : '.umi-table-control-column-fixed-cell');

                                for (var i = 0; i < rows.length; i++) {
                                    if (rows[i] === el) {
                                        break;
                                    }
                                }
                                //var leftElements = umiTableLeft.querySelectorAll('.umi-table-control-column-fixed-cell');
                                var rightElements = umiTableRight.querySelectorAll('.umi-table-control-column-fixed-cell');
                                if (!isContentRow) {
                                    el = tableContent[0].querySelectorAll('.umi-table-control-content-row')[i];
                                }
                                return [el, rightElements[i]];//[el, leftElements[i], rightElements[i]];
                            };

                            tableControl.on('mouseenter.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function() {
                                var elements = getHoverElements(this);
                                $(elements).addClass('hover');
                            });

                            tableControl.on('mouseleave.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function() {
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
            willDestroyElement: function() {
                $(window).off('.umi.tableControl');
                // Удаляем Observes для контоллера
                this.get('controller').removeObserver('query');
            },

            paginationView: Ember.View.extend({
                classNames: ['right', 'umi-table-control-pagination'],

                counter: function() {
                    var label = (UMI.i18n.getTranslate('Of') || '').toLowerCase();
                    var limit = this.get('controller.limit');
                    var offset = this.get('controller.offset') + 1;
                    var total = this.get('controller.total');
                    var maxCount = offset * limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('controller.limit', 'controller.offset', 'controller.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        return this.get('controller.offset');
                    }.property('controller.offset'),

                    click: function() {
                        if (this.get('isActive')) {
                            this.get('controller').decrementProperty('offset');
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        var limit = this.get('controller.limit');
                        var offset = this.get('controller.offset') + 1;
                        var total = this.get('controller.total');
                        return total > limit * offset;
                    }.property('controller.limit', 'controller.offset', 'controller.total'),

                    click: function() {
                        if (this.get('isActive')) {
                            this.get('controller').incrementProperty('offset');
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function() {
                        return this.get('controller.limit');
                    }.property('controller.limit'),

                    type: 'text',

                    keyDown: function(event) {
                        if (event.keyCode === 13) {
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

                isActive: function() {
                    var orderByProperty = this.get('controller.orderByProperty');
                    if (orderByProperty) {
                        return this.get('propertyName') === orderByProperty.property;
                    }
                }.property('controller.orderByProperty'),

                click: function() {
                    var propertyName = this.get('propertyName');

                    $('.umi-table-control-header-cell .icon-top-thin:not(.active)').removeClass('icon-top-thin').addClass('icon-bottom-thin');

                    if (this.get('isActive')) {
                        this.toggleProperty('sortAscending');
                    }

                    var sortAscending = this.get('sortAscending');
                    this.get('controller').send('orderByProperty', propertyName, sortAscending);
                }
            }),

            hasRowEvent: false,
            /**
             * @hook
             * @method rowEvent
             */
            rowEvent: function() {
            },

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-control-content-row'],
                classNameBindings: [
                    'object.type', 'isActive::umi-inactive', 'parentView.hasRowEvent:s-pointer', 'isSelected:selected'
                ],
                isActive: function() {//TODO: оптимизировать. Нет необходимости для каждого объекта проверять метаданные.
                    var object = this.get('object');
                    var hasActiveProperty = false;
                    object.eachAttribute(function(name) {
                        if (name === 'active') {
                            hasActiveProperty = true;
                        }
                    });
                    if (hasActiveProperty) {
                        return object.get('active');
                    } else {
                        return true;
                    }
                }.property('object.active'),

                isSelected: function() {
                    var objectGuid = this.get('object.guid');
                    return objectGuid === this.get('controller.control.meta.activeObjectGuid');
                }.property('controller.control.activeObjectGuid'),

                attributeBindings: ['objectId'],

                objectIdBinding: 'object.id',

                click: function() {
                    if (this.get('parentView.hasRowEvent') && !this.get('isSelected')) {
                        this.get('parentView').rowEvent(this.get('object'));
                    }
                }
            }),

            filterRowView: Ember.View.extend({
                filterType: null,
                template: function() {
                    var column = this.get('column');
                    var template = '';
                    switch (Ember.get(column, 'attributes.type')) {
                        case 'text':
                            this.set('filterType', 'text');
                            template = '<input type="text" class="table-control-filter-input"/>';
                            break;
                    }
                    return Ember.Handlebars.compile(template);
                }.property('column'),
                didInsertElement: function() {
                    var self = this;
                    var $el = this.$();
                    var $input = $el.children('input');
                    var filterType = this.get('filterType');
                    $input.on('focus', function() {
                        $(this).closest('.umi-table-control-row').find('.table-control-filter-input').val('');
                    });
                    switch (filterType) {
                        case 'text':
                            $input.on('keypress.umi.tableControl.filters', function(event) {
                                if (event.keyCode === 13) {
                                    self.setFilter('like(%' + this.value + '%)');
                                }
                            });
                            break;
                    }
                },
                setFilter: function(filter) {
                    this.get('controller').setFilters(this.get('column.dataSource'), filter);
                }
            })
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-content-cell-div'],

            classNameBindings: ['columnId'],

            promise: null,

            template: function() {
                var column;
                var object;
                var template = '';
                var value;
                var self = this;
                var properties;

                function propertyHtmlEncode(value) {
                    if (Ember.typeOf(value) === 'null') {
                        value = '';
                    } else {
                        value = UMI.Utils.htmlEncode(value);
                    }
                    return value;
                }

                try {
                    object = this.get('object');
                    column = this.get('column');
                    switch (column.type) {
                        case 'checkbox':
                            template = '<span {{bind-attr class="view.object.' + column.dataSource + ':umi-checkbox-state-checked:umi-checkbox-state-unchecked"}}></span>&nbsp;';
                            break;
                        case 'checkboxGroup':
                        case 'multiSelect':
                            value = object.get(column.dataSource);
                            if (Ember.typeOf(value) === 'array') {
                                template = value.join(', ');
                            }
                            break;
                        case 'datetime':
                            value = object.get(column.dataSource);
                            if (value) {
                                try {
                                    value = JSON.parse(value);
                                    template = Ember.get(value, 'date');
                                } catch (error) {
                                    this.get('controller').send('backgroundError', error);
                                }
                            }
                            break;
                        default:
                            properties = column.dataSource.split('.');

                            if (this.checkRelation(properties[0])) {
                                if (properties.length > 1) {
                                    value = object.get(properties[0]);
                                    if (Ember.typeOf(value) === 'instance') {
                                        value.then(function(object) {
                                            value = object.get(properties[1]);
                                            value = propertyHtmlEncode(value);
                                            self.set('promiseProperty', value);
                                        });
                                        template = '{{view.promiseProperty}}';
                                    }
                                } else {
                                    template = '{{view.object.' + column.dataSource + '.displayName}}';
                                }
                            } else {
                                value = object.get(column.dataSource);
                                value = propertyHtmlEncode(value);
                                template = value;
                            }
                            break;
                    }
                } catch (error) {
                    this.get('controller').send('backgroundError', error);
                } finally {
                    return Ember.Handlebars.compile(template);
                }
            }.property('column'),

            checkRelation: function(property) {
                var object = this.get('object');
                var isRelation = false;
                object.eachRelationship(function(name, relatedModel) {
                    if (property === name) {
                        isRelation = true;
                    }
                });
                return isRelation;
            }
        });

        UMI.TableControlView = Ember.View.extend(UMI.TableControlViewMixin, {
            componentNameBinding: 'controller.controllers.component.name',

            hasRowEvent: function() {
                var objectsEditable = this.get('controller.control.params.objectsEditable');
                objectsEditable = objectsEditable === false ? false : true;
                return objectsEditable;
            }.property('controller.control.params.objectsEditable'),

            rowEvent: function(object) {
                if (object.get('meta.editLink')) {
                    this.get('controller').transitionToRoute(object.get('meta.editLink').replace(Ember.get(window, 'UmiSettings.baseURL'), ''));
                }
            },

            willDestroyElement: function() {
                this.get('controller').removeObserver('objects.@each.isDeleted');
                this.get('controller').removeObserver('content.object.id');
            }
        });

        UMI.TableControlSharedView = Ember.View.extend(UMI.TableControlViewMixin, {
            componentNameBinding: null,

            hasRowEvent: true,

            rowEvent: function(object) {
                this.get('controller').send('executeBehaviour', 'rowEvent', object);
            },

            willDestroyElement: function() {
                this.get('controller').removeObserver('control.collectionName');
            }
        });

        UMI.TableControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'table-context-toolbar'],
            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function() {
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent,
                        UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = {};
                    var splitButtonBehaviour;

                    if (behaviourName) {
                        splitButtonBehaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                        for (var key in splitButtonBehaviour) {
                            if (splitButtonBehaviour.hasOwnProperty(key)) {
                                behaviour[key] = splitButtonBehaviour[key];
                            }
                        }
                    }

                    behaviour.extendButton = behaviour.extendButton = {};
                    behaviour.extendButton.classNames = ['white square'];
                    behaviour.extendButton.label = null;

                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});
define('tableControl/main',[
    './controllers', './view', 'App'
], function(controllers, view) {
    

    controllers();
    view();
});
define('tableControl', ['tableControl/main'], function (main) { return main; });

define('fileManager/main',['App'], function(UMI) {
    

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager s-full-height'],

        layout: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        /**
         * @hook
         * @param fileInfo
         */
        fileSelect: function(fileInfo) {
            return fileInfo;
        },

        init: function() {
            this._super();

            var templateParams = this.get('templateParams');
            if (Ember.typeOf(templateParams) === 'object') {
                this.setProperties(templateParams);
            }
        },

        didInsertElement: function() {
            var self = this;
            $('#elfinder').elfinder({
                url: window.UmiSettings.baseApiURL + '/files/manager/action/connector',//self.get('controller.connector.source'),
                lang: 'ru',
                getFileCallback: function(fileInfo) {
                    self.fileSelect(fileInfo);
                },

                uiOptions: {
                    toolbar: [
                        ['back', 'forward'],
                        ['reload'],
                        ['getfile'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['download'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit'],
                        ['view'],
                        ['help']
                    ]
                }
            }).elfinder('instance');

            $('.elfinder-navbar').on('mousedown.umi.fileManager', '.elfinder-navbar-div', function() {
                $('.elfinder-navbar').children().removeClass('ui-state-active');
                $(this).addClass('ui-state-active');
            });
        },

        willDestroyElement: function() {
            $(window).off('.umi.fileManager');
        }
    });
});
define('fileManager', ['fileManager/main'], function (main) { return main; });

define('treeSimple/main',[
    'App'
], function(UMI) {
    

    UMI.TreeSimpleView = Ember.View.extend({
        classNames: ['row', 's-full-height'],
        templateName: 'partials/treeSimple/list'
    });

    UMI.TreeSimpleItemView = Ember.View.extend({
        tagName: 'li',
        templateName: 'partials/treeSimple/item',
        isExpanded: true,
        checkExpanded: function() {
            var params = this.get('controller.target.router.state.params');
            if (params && 'settings.component' in params && params['settings.component'].component === this.get('context.name')) {
                this.set('isExpanded', true);
            }
        },
        nestedSlug: function() {
            var computedSlug = '';
            if (this.get('parentView').constructor.toString() === '.TreeSimpleItemView') {
                computedSlug = this.get('parentView').get('context.name') + '.';
            }
            computedSlug += this.get('context.name');
            return computedSlug;
        }.property(),
        actions: {
            expanded: function() {
                this.toggleProperty('isExpanded');
            }
        }
    });
});
define('treeSimple', ['treeSimple/main'], function (main) { return main; });

define('tree/controllers',['App'], function(UMI) {
    
    return function() {

        UMI.TreeControlController = Ember.ObjectController.extend({
            /**
             * Импортируемые контроллеры
             * @property needs
             */
            needs: ['component', 'context'],

            /**
             * Имя коллекции
             * @property collectionName
             */
            collectionNameBinding: 'controllers.component.dataSource.name',

            /**
             * Определяет 'trasheble' коллекцию
             * @property isTrashableCollection
             */
            isTrashableCollection: null,

            /**
             * Запрашиваемые свойства объекта
             * @property properties
             */
            properties: function() {
                var properties = ['displayName', 'order', 'active', 'childCount', 'type', 'locked'];
                var collectionName = this.get('collectionName');
                var model = this.get('store').modelFor(collectionName);
                var modelFields = Ember.get(model, 'fields');
                modelFields = modelFields.keys.list;
                for (var i = 0; i < properties.length; i++) {
                    if (!modelFields.contains(properties[i])) {
                        properties.splice(i, 1);
                        --i;
                    }
                }
                this.set('isTrashableCollection', modelFields.contains('trashed'));
                return properties;
            }.property('model'),

            /**
             * Контекстное меню
             * @property contentToolbar
             */
            contextToolbar: function() {
                return Ember.get(this.get('controllers.component'), 'sideBarControl.contextToolbar');
            }.property('controllers.component.sideBarControl.contextToolbar'),

            /**
             * Возвращает корневой элемент
             * @property root
             */
            root: function() {
                var collectionName = this.get('collectionName');
                var sideBarControl = this.get('controllers.component.sideBarControl');
                if (!sideBarControl) {
                    return;
                }
                var self = this;
                var Root = Ember.Object.extend({
                    displayName: Ember.get(sideBarControl, 'params.rootNodeName') || '',
                    root: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    typeKey: collectionName,
                    childCount: 1
                });
                var root = Root.create({});
                return root;
            }.property('model'),

            /**
             * Активный контекст
             * @property activeContext
             */
            activeContext: function() {
                return this.get('controllers.context.model');
            }.property('controllers.context.model'),

            /**
             * Индикатор процесса загрузки при частичной перезагрузке элементов дерева
             * @property isLoading
             */
            isLoading: false,

            actions: {
                /**
                 Сохранение результата drag and drop
                 @method updateSortOrder
                 @param String id ID перемещаемого объекта
                 @param String id ID нового родителя перемещаемого объекта
                 @param String id ID элемента после которого вставлен перемещаемый объект
                 @param Array Массив nextSibling следующие обьекты за перемещаемым объектом
                 */
                updateSortOrder: function(id, parentId, prevSiblingId, nextSibling) {
                    var self = this;
                    var collectionName = this.get('collectionName');
                    var ids = nextSibling || [];
                    var moveParams = {};
                    var resource;
                    var sibling;
                    var node;
                    var parent;
                    var oldParentId;
                    var store = self.get('store');
                    var models = store.all(collectionName);
                    var properties = self.get('properties');

                    self.send('showLoader');

                    node = models.findBy('id', id);
                    moveParams.object = {
                        'id': node.get('id'),
                        'version': node.get('version')
                    };
                    oldParentId = node.get('parent.id') || 'root';

                    if (parentId && parentId !== 'root') {
                        parent = models.findBy('id', parentId);
                        moveParams.branch = {
                            'id': parent.get('id'),
                            'version': parent.get('version')
                        };
                    }

                    if (prevSiblingId) {
                        sibling = models.findBy('id', prevSiblingId);
                        moveParams.sibling = {
                            'id': sibling.get('id'),
                            'version': sibling.get('version')
                        };
                    }

                    resource = self.get('controllers.component.settings.actions.move.source');
                    return $.ajax({
                        'type': 'POST',
                        'url': resource,
                        'data': JSON.stringify(moveParams),
                        'dataType': 'json',
                        'contentType': 'application/json',
                        global: false,
                        success: function() {
                            ids.push(id);
                            var parentsUpdateRelation = {};

                            if (parentId !== oldParentId) {
                                if (parentId && parentId !== 'root') {
                                    ids.push(parentId);
                                    parentsUpdateRelation.currentParent = parentId;
                                }
                                if (oldParentId && oldParentId !== 'root') {
                                    ids.push(oldParentId);
                                    parentsUpdateRelation.oldParent = oldParentId;
                                }
                            }

                            var promise;
                            var requestParams = {};
                            requestParams.fields = properties.join(',');
                            requestParams['filters[id]'] = 'equals(' + ids.join(',') + ')';

                            promise = store.updateCollection(collectionName, requestParams);


                            return promise.then(function(updatedObjects) {
                                    var parent;

                                    if (parentId !== 'root') {
                                        node = updatedObjects.findBy('id', id);
                                        store.find(collectionName, parentId).then(function(parent) {
                                            node.set('parent', parent);
                                        });
                                    }

                                    if (parentId !== oldParentId) {
                                        for (var key in parentsUpdateRelation) {
                                            if (parentsUpdateRelation.hasOwnProperty(key)) {
                                                parent = models.findBy('id', parentsUpdateRelation[key]);
                                                parent.trigger('needReloadHasMany', (
                                                    key === 'currentParent' ? 'add' : 'remove'), node);
                                            }
                                        }
                                        if (parentId !== oldParentId && (parentId === 'root' || oldParentId === 'root')) {
                                            self.get('controllers.component').trigger('needReloadRootElements', (
                                                parentId === 'root' ? 'add' : 'remove'), node);
                                        }
                                    }

                                    self.send('hideLoader');
                                });

                        },
                        error: function(error) {
                            self.send('backgroundError', error);
                            self.send('hideLoader');
                        }
                    });
                },

                showLoader: function() {
                    this.set('isLoading', true);
                },

                hideLoader: function() {
                    this.set('isLoading', false);
                }
            }
        });

        UMI.TreeControlContextToolbarController = Ember.ObjectController.extend({
            needs: ['component'],

            componentNameBinding: 'controllers.component.name'
        });
    };
});
define('tree/views',['App', 'toolbar'], function(UMI) {
    
    return function() {
        UMI.TreeControlView = Ember.View.extend({

            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/treeControl',

            /**
             * Имена классов
             * @property classNames
             */
            classNames: ['row', 's-full-height', 's-unselectable'],

            /**
             * @property iScroll
             */
            iScroll: null,

            /**
             * Observer для активного контекста
             * @observer activeContext
             */
            activeContextChange: function() {
                Ember.run.next(this, 'expandBranch');
            }.observes('controller.activeContext').on('didInsertElement'),

            /**
             * Раскрывает загруженную ветвь дерева и выставляет значение expandBranch
             * @method expandBranch
             */
            expandBranch: function() {
                if (this.get('isDestroying') || this.get('isDestroyed')) {//TODO: fixed
                    return;
                }
                var $el = this.$();
                var activeContext = this.get('controller.activeContext');
                var checkExpandItem = function(id) {
                    if ($el.length) {
                        var itemView = $el.find('[data-id=' + id + ']');
                        return itemView.length ? true : false;
                    }
                };
                if (activeContext) {
                    var mpath = [];
                    var contextMpath = [];
                    var needsExpandedItems = [];
                    mpath.push('root');
                    if (activeContext.get('id') !== 'root' && activeContext.get('mpath')) {
                        contextMpath = activeContext.get('mpath').without(parseFloat(activeContext.get('id'))) || [];
                    }
                    contextMpath = mpath.concat(contextMpath);
                    for (var i = 0, size = contextMpath.length; i < size; i++) {
                        if (checkExpandItem(contextMpath[i])) {
                            this.send('expandItem', contextMpath[i]);
                        } else {
                            needsExpandedItems.push(contextMpath[i]);
                        }
                    }
                    this.set('needsExpandedItems', needsExpandedItems);

                }
            },

            /**
             * Ветви дерева, которые требуется открыть при сменене контекста
             * @property needsExpandedItems
             */
            needsExpandedItems: '',

            actions: {
                expandItem: function(id) {
                    if (this.$()) {
                        var itemView = this.$().find('[data-id=' + id + ']');
                        if (itemView.length) {
                            itemView = Ember.View.views[itemView[0].id];
                            if (itemView && !itemView.get('isExpanded')) {
                                itemView.set('isExpanded', true);
                            }
                        }
                    }
                }
            },

            /**
             * Метод устанавливающий события после рендинга шаблона.
             * @method didInsertElement
             */
            didInsertElement: function() {
                var scrollContainer = this.$().find('.umi-tree-wrapper')[0];
                var iScrollConfiguration = $.extend({disableMouse: true}, UMI.config.iScroll);
                var contentScroll = new IScroll(scrollContainer, iScrollConfiguration);

                this.set('iScroll', contentScroll);
                var self = this;

                //
                $('html').on('toggled.umi.divider', function() {
                    setTimeout(function() {
                        contentScroll.refresh();
                    }, 100);
                });
                // Раскрытие ноды имеющую потомков
                var setExpanded = function(node) {
                    var itemView = Ember.View.views[node.id];

                    if (itemView.get('hasChildren')) {
                        itemView.set('isExpanded', true);
                    }
                };

                /**
                 * Устанавливает позицию призрака
                 * */
                var ghostPosition = function(event, ghost, ghostPositionOffset) {
                    ghost.style.top = event.pageY + ghostPositionOffset + 'px';
                    ghost.style.left = event.pageX + ghostPositionOffset + 'px';
                };

                /**
                 * Возвращает соседний элемент определеного типа
                 *
                 * @param {Object} Элемент для которого нужно найти следующих соседей
                 * @param {string} Тип элемента который требуется найти
                 * @returns {Object|Null} Возвращаем найденный элемент
                 * */
                function findNextSibling(element, type) {
                    type = type.toUpperCase();
                    var nextElement = element.nextElementSibling;
                    while (nextElement && nextElement.tagName !== type) {
                        nextElement = nextElement.nextElementSibling;
                    }
                    return nextElement;
                }

                var dragAndDrop = function(event, el) {
                    var draggableNode = el.parentNode.parentNode;
                    var placeholder = document.createElement('li');
                    var ghost = document.createElement('span');
                    var delayBeforeExpand;

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

                    ghostPosition(event, ghost, ghostPositionOffset);

                    $(document).on('mousemove', 'body, .umi-tree-ghost', function(event) {
                        if (delayBeforeExpand) {
                            clearTimeout(delayBeforeExpand);
                        }
                        ghostPosition(event, ghost, ghostPositionOffset);

                        var nextElement;
                        var hoverElement;
                        var elemHeight;
                        var elemPositionTop;
                        // Вычисляем элемент под курсором мыши
                        var elem = document.elementFromPoint(event.clientX, event.clientY);

                        // Проверим находимся мы над деревом или нет
                        if ($(elem).closest('.umi-tree').length) {
                            hoverElement = $(elem).closest('li')[0];
                            // Устанавливаем плэйсхолдер рядом с элементом
                            if (hoverElement && hoverElement !== placeholder && hoverElement.getAttribute('data-id') !== 'root') {
                                elemHeight = hoverElement.offsetHeight;
                                elemPositionTop = hoverElement.getBoundingClientRect().top;
                                // Помещаем плэйсхолдер:
                                // 1) после ноды - Если позиция курсора на ноде ниже ~70% ее высоты
                                // 2) перед нодой - Если позиция курсора на ноде выше ~30% ее высоты
                                // 3) "внутрь" ноды - если навели на центр. При задержке пользователя на центре раскрываем ноду.
                                if (event.clientY > elemPositionTop + parseInt(elemHeight * 0.7, 10)) {
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    nextElement = findNextSibling(hoverElement, 'li');
                                    if (nextElement) {
                                        placeholder = hoverElement.parentNode.insertBefore(placeholder, nextElement);
                                    } else {
                                        placeholder = hoverElement.parentNode.appendChild(placeholder);
                                    }
                                } else if (event.clientY < elemPositionTop + parseInt(elemHeight * 0.3, 10)) {
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    placeholder = hoverElement.parentNode.insertBefore(placeholder, hoverElement);
                                } else {
                                    var emptyChildList = document.createElement('ul');
                                    emptyChildList.className = 'umi-tree-list';
                                    emptyChildList.setAttribute('data-parent-id', hoverElement.getAttribute('data-id'));
                                    placeholder = placeholder.parentNode.removeChild(placeholder);

                                    placeholder = emptyChildList.appendChild(placeholder);
                                    emptyChildList = hoverElement.appendChild(emptyChildList);
                                    delayBeforeExpand = setTimeout(function() {
                                        setExpanded(hoverElement);
                                    }, 500);
                                }
                            }
                        }
                    });

                    $(document).on('mouseup', function(event) {
                        var elem = document.elementFromPoint(event.clientX, event.clientY);
                        var prevSiblingId = null;
                        var list = $(elem).closest('.umi-tree-list')[0];

                        // Удаляем обработчик события
                        $(document).off('mousemove', 'body, .umi-tree-ghost');
                        $(document).off('mouseup');
                        //Удаление призрака
                        ghost.parentNode.removeChild(ghost);

                        // Если курсор над плейсхолдером считаем что перемещение удачное
                        if (list && !$(list).hasClass('umi-tree')) {
                            /**
                             * Находим предыдущего соседа
                             */
                            (function findPrevSibling(el) {
                                var sibling = el.previousElementSibling;
                                if (sibling && ($(sibling).hasClass('hide') || sibling.tagName !== 'LI')) {
                                    findPrevSibling(sibling);
                                } else {
                                    prevSiblingId = sibling ? sibling.getAttribute('data-id') : null;
                                }
                            }(placeholder));

                            var nextSibling = [];
                            /**
                             * Фильтр элементов списка
                             */
                            (function findNextSibling(element) {
                                var sibling = element.nextElementSibling;
                                if (sibling) {
                                    if ($(sibling).hasClass('hide') || sibling.tagName !== 'LI') {
                                        findNextSibling(sibling);
                                    } else {
                                        nextSibling.push(sibling.getAttribute('data-id'));
                                    }
                                }
                            }(placeholder));
                            var parentId = list.getAttribute('data-parent-id');
                            self.get('controller').send('updateSortOrder', placeholder.getAttribute('data-id'), parentId, prevSiblingId, nextSibling);
                            self.send('expandItem', parentId);
                        }
                        // Удаление плэйсхолдера
                        if (placeholder.parentNode) {
                            placeholder.parentNode.removeChild(placeholder);
                        }
                        $(draggableNode).removeClass('hide');
                        $('html').removeClass('s-unselectable');
                    });
                };

                var timeoutForDrag;
                this.$().on('mousedown', '.icon.move', function(event) {
                    if (event.originalEvent.which !== 1) {
                        return;
                    }
                    var el = this;
                    timeoutForDrag = setTimeout(function() {
                        dragAndDrop(event, el);
                    }, 200);
                });

                this.$().on('mouseup', '.icon.move', function() {
                    if (timeoutForDrag) {
                        clearTimeout(timeoutForDrag);
                    }
                });
            },

            willDestroyElement: function() {
                this.removeObserver('controller.activeContext');
            }
        });

        UMI.TreeControlItemView = Ember.View.extend({
            item: null,

            treeControlView: null,

            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/treeControl/treeItem',

            /**
             * Имя тега элемента
             * @property tagName
             */
            tagName: 'li',

            /**
             * @property classNames
             */
            classNames: ['umi-tree-list-li'],

            /**
             * @property classNameBindings
             */
            classNameBindings: ['item.isDragged:hide', 'item.isDeleted:hide'],

            /**
             * @property attributeBindings
             */
            attributeBindings: ['dataId:data-id'],

            /**
             * @property dataId
             */
            dataId: function() {
                return this.get('item.id');
            }.property('item.id'),

            iconTypeClass: function() {
                var iconTypeClass;
                var item = this.get('item');

                if (item.get('id') === 'root') {
                    iconTypeClass = 'icon-open-folder';
                } else {
                    switch (item.get('type')) {
                        case 'system':
                            iconTypeClass = 'icon-settings';
                            break;
                        default:
                            iconTypeClass = 'icon-document';
                            break;
                    }
                }

                return iconTypeClass;
            }.property('item.type'),

            /**
             * Ссылка на редактирование елемента
             * @property editLInk
             */
            editLink: function() {
                var link = this.get('item.meta.editLink');
                return link;
            }.property('item'),

            allowMove: function() {
                var item = this.get('item');
                if (item.get('id') !== 'root' && !item.get('locked')) {
                    return true;
                }
            }.property('item.id'),

            /**
             * Сохранённое имя отображения объекта
             * @property savedDisplayName
             */
            savedDisplayName: function() {
                if (this.get('item.id') === 'root') {
                    return this.get('item.displayName');
                } else {
                    return this.get('item._data.displayName');
                }
            }.property('item.currentState.loaded.saved'),//TODO: Отказаться от использования _data

            /**
             * Для активного объекта добавляется класс active
             * @property active
             */
            isActiveContext: function() {
                return this.get('controller.activeContext.id') === this.get('item.id');
            }.property('controller.activeContext.id'),

            childrenList: function() {
                if (!this.get('item')) {//TODO: fixed
                    return;
                }
                return this.getChildren();
            }.property('item'),

            hasChildren: Ember.computed.bool('item.childCount'),

            /**
             *
             * @returns {*}
             */
            getChildren: function() {
                var model = this.get('item');
                var collectionName = model.get('typeKey') || model.constructor.typeKey;
                var promise;
                var self = this;

                var properties = self.get('controller.properties').join(',');
                var parentId;
                if (model.get('id') === 'root') {
                    parentId = 'null()';
                } else {
                    parentId = model.get('id');
                }
                var requestParams = {'filters[parent]': parentId, 'fields': properties};
                if (self.get('controller.isTrashableCollection')) {
                    requestParams['filters[trashed]'] = 'equals(0)';
                }
                promise = this.get('controller.store').updateCollection(collectionName, requestParams);

                if (model.get('id') !== 'root') {
                    promise.then(function(children) {
                        for (var i = 0; i < children.length; i++) {
                            children[i].set('parent', model);
                        }
                    });
                }

                var promiseArray = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: promise,
                    sortProperties: ['order'],
                    sortAscending: true
                });
                return promiseArray;
            },

            scrollUpdate: function() {
                var self = this;
                setTimeout(function() {
                    var iScroll = self.get('treeControlView.iScroll');
                    if (iScroll) {
                        setTimeout(function() {
                            iScroll.refresh();
                        }, 100);
                    }
                }, 0);
            },

            scrollNeedUpdate: function() {
                this.scrollUpdate();
            }.observes('childrenList.length'),

            /**
             * Для неактивных элементов добавляется класс inActive
             * @property inActive
             */
            inActive: function() {
                return this.get('item.active') === false ? true : false;
            }.property('item.active'),

            isExpanded: false,

            expandActiveContext: function() {
                if (!this.get('isExpanded')) {
                    var id = this.get('item.id');
                    var treeControlView = this.get('treeControlView');
                    var needsExpandedItems = treeControlView.get('needsExpandedItems');
                    if (id === 'root') {
                        this.set('isExpanded', true);
                    } else if (needsExpandedItems && needsExpandedItems.contains(parseFloat(id))) {
                        treeControlView.set('needsExpandedItems', needsExpandedItems.without(parseFloat(id)));
                        this.set('isExpanded', true);
                    }
                }
            },

            actions: {
                expanded: function() {
                    var isExpanded = this.toggleProperty('isExpanded');
                    this.scrollUpdate();
                }
            },

            didInsertElement: function() {
                Ember.run.once(this, 'expandActiveContext');
            },

            init: function() {
                this._super();
                var self = this;
                var model = this.get('item');

                if (model.get('id') === 'root') {
                    self.get('controller.controllers.component').on('needReloadRootElements', function(event, object) {
                        if (event === 'add') {
                            self.get('childrenList').pushObject(object);
                        } else if (event === 'remove') {
                            self.get('childrenList').removeObject(object);
                        }
                    });
                } else {
                    this.get('item').on('needReloadHasMany', function(event, object) {
                        if (event === 'add') {
                            self.get('childrenList').pushObject(object);
                        } else {
                            self.get('childrenList').removeObject(object);
                        }
                    });
                }
            },

            willDestroyElement: function() {
                this.removeObserver('childrenList.length');
            }
        });

        UMI.TreeControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',

            classNames: ['button-group', 'umi-tree-context-toolbar', 'right'],

            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function() {
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent,
                        UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = {};
                    var splitButtonBehaviour;

                    if (behaviourName) {
                        splitButtonBehaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                        for (var key in splitButtonBehaviour) {
                            if (splitButtonBehaviour.hasOwnProperty(key)) {
                                behaviour[key] = splitButtonBehaviour[key];
                            }
                        }
                    }

                    behaviour.extendButton = behaviour.extendButton = {};
                    behaviour.extendButton.classNames = ['tiny white square'];
                    behaviour.extendButton.label = null;
                    behaviour.extendButton.dataOptions = function() {
                        return 'align: right; checkPositionRegardingElement: .umi-tree-wrapper;' +
                            ' maxWidthLikeElement: .umi-tree-wrapper;';
                    }.property();
                    behaviour.actions = behaviour.actions || {};
                    behaviour.actions.sendActionForBehaviour = function(contextBehaviour) {
                        var object = this.get('controller.model');
                        this.send(contextBehaviour.name, {behaviour: contextBehaviour, object: object});
                    };

                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});
define('tree/main',[
    'App', './controllers', './views'
], function(UMI, controllers, views) {
        

        controllers();
        views();
    });
define('tree', ['tree/main'], function (main) { return main; });

define('partials/forms/elements/mixins',['App'], function(UMI) {
    

    return function() {
        UMI.InputValidate = Ember.Mixin.create({
            /**
             * Определяет тип валидатора
             * @property validatorType
             * @optional
             */
            validatorType: null,

            /**
             * Метод вызывается при необходимости валидации поля
             * @method sendValidate
             * @optional
             */
            checkValidate: function() {},

            focusOut: function() {//TODO: Вынести тригеры из миксина. Удобнее будет управлять валидацией непосредственно из элемента
                this.validate();
            },

            /**
             * Метод вызывается для отмены валидатора
             */
            clearValidate: function() {},

            focusIn: function() {
                this.clearValidateError();
            },

            validate: function() {
                var self = this;
                var validatorType = this.get('validatorType');
                var property;
                var meta = self.get('meta');
                var validationError;

                if (validatorType === 'collection') {
                    property = Ember.get(meta, 'dataSource');
                    var object = self.get('object');
                    object.filterProperty(property);
                    object.validateProperty(property);
                } else {
                    UMI.validator.filterProperty(Ember.get(meta, 'value'), Ember.get(meta, 'filters'));
                    validationError = UMI.validator.validateProperty(Ember.get(meta, 'value'), Ember.get(meta, 'validators'));
                    validationError = validationError || [];
                    Ember.set(meta, 'errors', validationError);
                }
            },

            clearValidateError: function() {
                var self = this;
                var meta = self.get('meta');
                var dataSource = Ember.get(meta, 'dataSource');

                if (self.get('validatorType') === 'collection') {
                    var object = self.get('object');
                    object.clearValidateForProperty(dataSource);
                } else {
                    Ember.set(meta, 'errors', []);
                }
            },

            validateErrorsTemplate: function() {
                var validatorType = this.get('validatorType');
                var template;
                var propertyName;

                if (validatorType === 'collection') {
                    propertyName = this.get('meta.dataSource');
                    template = '{{#if view.object.validErrors.' + propertyName + '}}' +
                        '<small class="error">{{view.object.validErrors.' + propertyName + '}}</small>' +
                        '{{/if}}';
                } else {
                    template = '{{#if view.parentView.isError}}<small class="error">{{view.parentView.isError}}</small>{{/if}}';
                }

                return template;
            }
        });

        UMI.SerializedValue = Ember.Mixin.create({
            /**
             * Путь к изменяемому свойству
             * @abstract
             */
            path: null,
            /**
             * @property value
             */
            value: null,

            inputIsObservable: false,

            objectIsObservable: false,

            computeValue: function(computedValue, path, value) {
                Ember.set(computedValue, path, value);
                return computedValue;
            },

            setValueForObject: function() {
                var self = this;
                var value;
                var path;
                var computedValue;
                var selectedValue;
                var result = '';
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        value = self.get('value');
                        path = self.get('path');
                        value = Ember.isNone(value) ? '' : value;
                        computedValue = self.get('object.' + self.get('meta.dataSource')) || '';

                        if (value) {
                            if (computedValue) {
                                computedValue = JSON.parse(computedValue);
                            }
                            selectedValue = Ember.get(computedValue, path);
                            selectedValue = Ember.isNone(selectedValue) ? '' : selectedValue;
                            if (selectedValue !== value) {
                                if (value) {
                                    result = self.computeValue(computedValue, path, value);
                                    result = JSON.stringify(result);
                                }
                                self.get('object').set(self.get('meta.dataSource'), result);
                            }
                        } else {
                            if (computedValue !== value) {
                                self.get('object').set(self.get('meta.dataSource'), result);
                            }
                        }
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            setInputValue: function() {
                var self = this;
                var path = self.get('path');
                var computedValue = self.get('object.' + self.get('meta.dataSource'));
                var value = self.get('value');
                value = Ember.isNone(value) ? '' : value;
                var selectedValue;
                try {
                    if (computedValue) {
                        computedValue = JSON.parse(computedValue);
                        selectedValue = Ember.get(computedValue, path);
                        selectedValue = Ember.isNone(selectedValue) ? '' : selectedValue;
                        Ember.set(computedValue, path, selectedValue);

                        if (selectedValue !== value) {
                            self.set('value', selectedValue);
                        }
                    } else {
                        if (computedValue !== value) {
                            self.set('value', '');
                        }
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            init: function() {
                this._super();
                var computedValue;
                var self = this;
                var path = this.get('path');
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        computedValue = self.get('object.' + self.get('meta.dataSource')) || '{}';
                        computedValue = JSON.parse(computedValue);

                        self.set('value', Ember.get(computedValue, path) || '');

                        if (self.get('inputIsObservable')) {
                            self.addObserver('value', function() {
                                Ember.run.once(this, 'setValueForObject');
                            });
                        }

                        if (self.get('objectIsObservable')) {
                            self.addObserver('object.' + self.get('meta.dataSource'), function() {
                                Ember.run.once(self, 'setInputValue');
                            });
                        }

                    } else {
                        self.set('value', self.get('meta.value'));
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            willDestroyElement: function() {
                var self = this;
                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.removeObserver('object.' + self.get('meta.dataSource'));
                    self.removeObserver('value');
                }
            }
        });
    };
});
define('partials/forms/elements/wysiwyg/main',['App'], function(UMI) {
    

    return function() {
        CKEDITOR.on('dialogDefinition', function(event) {
            var editor = event.editor;
            var dialogDefinition = event.data.definition;
            var tabCount = dialogDefinition.contents.length;
            var dialogName = event.data.name;

            var popupParams = {
                viewParams: {
                    popupType: 'fileManager',
                    title: UMI.i18n.getTranslate('Select file')
                },
                templateParams: {
                    fileSelect: function(fileInfo) {
                        var self = this;
                        var image = Ember.get(fileInfo, 'url') || '';
                        var baseUrl = Ember.get(window, 'UmiSettings.projectAssetsUrl');
                        var pattern = new RegExp('^' + baseUrl, 'g');

                        window.CKEDITOR.tools.callFunction(editor._.filebrowserFn, image.replace(pattern, ''));
                        self.get('controller').send('closePopup');
                    }
                }
            };
            var browseButton;

            var showFileManager = function() {
                editor._.filebrowserSe = this;
                var $dialog = $('.cke_dialog');
                $dialog.addClass('umi-blur');
                var $dialogCover = $('.cke_dialog_background_cover');
                $dialogCover.addClass('hide');

                var showDialogCK = function() {
                    $dialog.removeClass('umi-blur');
                    $dialogCover.removeClass('hide');
                };
                popupParams.viewParams.beforeClose = showDialogCK;
                UMI.__container__.lookup('route:application').send('showPopup', popupParams);
            };

            for (var i = 0; i < tabCount; i++) {
                browseButton = dialogDefinition.contents[i];
                if (browseButton) {
                    browseButton = browseButton.get('browse');

                    if (browseButton !== null) {
                        browseButton.label = UMI.i18n.getTranslate('File manager');

                        if (i === 0) {
                            browseButton.style = 'display: inline-block; margin-top: 15px; margin-left: auto; margin-right: auto;';
                        }
                        browseButton.hidden = false;
                        browseButton.onClick = showFileManager;
                    }
                }
            }
        });

        UMI.HtmlEditorView = Ember.View.extend({
            classNames: ['ckeditor-row'],

            ckeditor: null,

            template: function() {
                var textarea = '{{textarea value=view.meta.attributes.value placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                return Ember.Handlebars.compile(textarea);
            }.property(),

            didInsertElement: function() {
                var config = UMI.config.CkEditor();
                var self = this;
                var el = this.$().children('textarea');
                el.css({'height': config.height});
                var editor = CKEDITOR.replace(el[0].id, config);
                self.set('ckeditor', editor);
            },

            willDestroyElement: function() {
                this.get('ckeditor').destroy();
            }
        });

        UMI.HtmlEditorCollectionView = Ember.View.extend(UMI.InputValidate, {
            classNames: ['ckeditor-row'],

            ckeditor: null,

            textareaId: function() {
                return 'textarea-' + this.get('elementId');
            }.property(),

            template: function() {
                var textarea = '<textarea id="{{unbound view.textareaId}}" placeholder="{{unbound view.meta.placeholder}}" name="{{unbound view.meta.attributes.name}}">{{unbound view.object.' + this.get('meta.dataSource') + '}}</textarea>';
                var validate = this.validateErrorsTemplate();
                return Ember.Handlebars.compile(textarea + validate);
            }.property(),

            setTextareaValue: function(edit) {
                if (this.get('isDestroying') || this.get('isDestroyed')) {
                    return;
                }
                if (this.$() && this.$().children('textarea').length) {
                    var value = this.get('object.' + this.get('meta.dataSource'));
                    if (edit && edit.getData() !== value) {
                        edit.setData(value);
                    }
                }
            },

            updateContent: function(event, edit) {
                var self = this;
                if (event.editor.checkDirty()) {
                    self.get('object').set(self.get('meta.dataSource'), edit.getData());
                }
            },

            didInsertElement: function() {
                Ember.run.next(this, function() {
                    var self = this;
                    var config = UMI.config.CkEditor();
                    var el = this.$().children('textarea');
                    el.css({'height': config.height});
                    var editor = CKEDITOR.replace(el[0].id, config);
                    self.set('ckeditor', editor);

                    editor.on('blur', function(event) {
                        Ember.run.once(self, 'updateContent', event, editor);
                    });

                    editor.on('key', function(event) {// TODO: Это событие было добавлено только из-за того, что событие save срабатывает быстрее blur. Кажется можно сделать лучше.
                        Ember.run.once(self, 'updateContent', event, editor);
                    });

                    self.addObserver('object.' + self.get('meta.dataSource'), function() {
                        Ember.run.next(self, 'setTextareaValue', editor);
                    });
                });
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
                self.get('ckeditor').destroy();
            }
        });
    };
});
define('partials/forms/elements/select/main',['App'], function(UMI) {
    

    return function() {
        UMI.SelectView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],

            optionLabelPath: function() {
                return 'content.label';
            }.property(),

            optionValuePath: function() {
                return 'content.value';
            }.property(),

            prompt: function() {
                var meta = this.get('meta.choices');
                var choicesHasPrompt;

                if (meta && Ember.typeOf(meta) === 'array') {
                    choicesHasPrompt = meta.findBy('value', '');
                }

                if (choicesHasPrompt) {
                    return choicesHasPrompt.label;
                } else {
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),

            content: null,

            init: function() {
                this._super();
                this.set('selection', this.get('object.choices').findBy('value', this.get('object.value')));
                this.set('content', this.get('object.choices'));
            },

            didInsertElement: function() {
                var prompt = this.$().find('option')[0];
                var validators = this.get('meta.validators') || [];
                validators = validators.findBy('type', 'required');

                if (!prompt.value && validators) {
                    prompt.disabled = true;
                }
            }
        });

        UMI.SelectCollectionView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],

            isLazy: false,

            optionLabelPath: function() {
                return this.get('isLazy') ? 'content.displayName' : 'content.label';
            }.property(),

            optionValuePath: function() {
                return this.get('isLazy') ? 'content.id' : 'content.value';
            }.property(),

            prompt: function() {
                var meta = this.get('meta.choices');
                var choicesHasPrompt;

                if (meta && Ember.typeOf(meta) === 'array') {
                    choicesHasPrompt = meta.findBy('value', '');
                }

                if (choicesHasPrompt) {
                    return choicesHasPrompt.label;
                } else {
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),

            content: null,

            changeValue: function() {
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedObject = this.get('selection');
                var value;

                if (this.get('isLazy')) {
                    value = selectedObject ? selectedObject : undefined;
                    object.set(property, value);
                    object.changeRelationshipsValue(property, selectedObject ? selectedObject.get('id') : undefined);
                } else {
                    value = selectedObject ? selectedObject.value : object.getDefaultValueForProperty(property);
                    object.set(property, value);
                }
            }.observes('value'),

            init: function() {
                this._super();
                var self = this;
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                this.set('isLazy', this.get('meta.lazy'));

                if (this.get('isLazy')) {
                    var store = self.get('controller.store');
                    var relatedCollectionName;

                    object.eachRelationship(function(name, meta) {
                        if (name === property) {
                            relatedCollectionName = Ember.get(meta, 'type.typeKey');
                        }
                    });

                    Ember.warn('Name of related collection is undefined.', relatedCollectionName);

                    var relationDidFetch = function(relatedObject) {
                        Ember.set(object.get('loadedRelationshipsByName'), property, relatedObject ?
                            relatedObject.get('id') : undefined);
                        self.set('selection', relatedObject);
                    };

                    var relatedCollection = store.all(relatedCollectionName);

                    self.set('content', relatedCollection);
                    var relatedObject = object.get(property);

                    if (Ember.typeOf(relatedObject) === 'instance') {
                        return relatedObject.then(function(relatedObject) {
                            relationDidFetch(relatedObject);
                        });
                    } else {
                        relationDidFetch();
                    }
                } else {
                    self.set('selection', this.get('meta.choices').findBy('value', object.get(property)));
                    self.set('content', this.get('meta.choices'));
                    Ember.run.next(self, function() {
                        var self = this;
                        self.addObserver('object.' + property, function() {
                            Ember.run.once(function() {
                                self.set('selection', self.get('meta.choices').findBy('value', object.get(property)));
                            });
                        });
                    });
                }
            },

            didInsertElement: function() {
                var property = this.get('meta.dataSource');
                var collectionName = this.get('object').constructor.typeKey;
                var metadata = this.get('controller.store').metadataFor(collectionName);
                var validators = Ember.get(metadata, 'validators.' + property);

                if (validators && Ember.typeOf(validators) === 'array') {
                    validators = validators.findBy('type', 'required');

                    if (validators) {
                        var prompt = this.$().find('option')[0];

                        if (!prompt.value && validators) {
                            prompt.disabled = true;
                        }
                    }
                }
            },

            willDestroyElement: function() {
                this.removeObserver('value');
                this.removeObserver('object.' + this.get('meta.dataSource'));
            }
        });
    };
});
define('partials/forms/elements/multi-select/main',['App'], function(UMI) {
    

    return function() {
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
            changeRelations: function(type, id) {
                var object = this.get('object');
                var selectedObject = this.get('collection').findBy('id', id);
                var property = this.get('meta.dataSource');
                var relation = object.get(property);
                return relation.then(function(relation) {
                    if (type === 'select') {
                        relation.pushObject(selectedObject);
                    } else {
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
            selectedObjects: function() {
                var key = this.get('isLazy') ? 'id' : 'value';
                var collection = this.get('collection') || [];
                var selectedObjects = [];
                var selectedIds = this.get('selectedIds') || [];
                collection.forEach(function(item) {
                    var id = Ember.get(item, key);
                    if (selectedIds.contains(id)) {
                        selectedObjects.push(item);
                    }
                });
                return selectedObjects;
            }.property('selectedIds.@each'),
            /**
             * Несвязанные объекты. Появляются в выпадающем списке
             * @property notSelectedObjects
             */
            notSelectedObjects: function() {
                var key = this.get('isLazy') ? 'id' : 'value';
                var collection = this.get('collection');
                var notSelectedObjects = [];
                var ids;
                if (this.get('filterOn')) {
                    ids = this.get('filterIds') || [];
                    collection.forEach(function(item) {
                        var id = Ember.get(item, key);
                        if (ids.contains(id)) {
                            notSelectedObjects.push(item);
                        }
                    });
                } else {
                    ids = this.get('selectedIds') || [];
                    collection.forEach(function(item) {
                        var id = Ember.get(item, key);
                        if (!ids.contains(id)) {
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
            opened: function() {
                var isOpen = this.get('isOpen');
                var self = this;
                if (isOpen) {
                    this.set('inputInFocus', true);
                    $('body').on('click.umi.multiSelect', function(event) {
                        if (!$(event.target).closest('.umi-multi-select-list').length || !$(event.target).hasClass('umi-multi-select-input')) {
                            self.set('isOpen', false);
                        }
                    });
                } else {
                    $('body').off('.umi.multiSelect');
                    this.set('inputInFocus', false);
                    this.get('notSelectedObjects').setEach('hover', false);
                }
            }.observes('isOpen'),

            changeRelations: function() {
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedIds = this.get('selectedIds');
                if (this.get('isLazy')) {
                    object.set(property, selectedIds);
                } else {
                    selectedIds = Ember.typeOf(selectedIds) === 'array' ? JSON.stringify(selectedIds.sort()) : '';
                    selectedIds = selectedIds === '[]' ? '' : selectedIds;
                    object.set(property, selectedIds);
                }
            },

            actions: {
                toggleList: function() {
                    this.set('filterIds', []);
                    this.set('filterOn', null);
                    var isOpen = !this.get('isOpen');
                    this.set('isOpen', isOpen);
                },
                select: function(id) {
                    this.get('selectedIds').pushObject(id);
                    this.changeRelations('select', id);
                },
                unSelect: function(id) {
                    this.get('selectedIds').removeObject(id);
                    this.changeRelations('unSelect', id);
                },
                markHover: function(key) {
                    var collection = this.get('notSelectedObjects');
                    var hoverObject = collection.findBy('hover', true);
                    var index = 0;
                    if (hoverObject) {
                        hoverObject.set('hover', false);
                        index = collection.indexOf(hoverObject);
                        if (key === 'Down' && index < collection.length - 1) {
                            ++index;
                        } else if (key === 'Up' && index) {
                            --index;
                        }
                    }
                    collection.objectAt(index).set('hover', true);
                },
                selectHover: function() {
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
                toggleFocus: function() {
                    if (this.get('parentView.inputInFocus')) {
                        this.$().focus();
                    } else {
                        this.$().blur();
                    }
                }.observes('parentView.inputInFocus'),
                autocomplete: 'off',
                value: function() {
                    var selectedObject = this.get('parentView.selectedObjects');
                    var value;
                    if (selectedObject.length) {
                        value = '';
                    } else {
                        value = '';
                    }
                    return value;
                }.property('parentView.selectedObjects'),
                click: function() {
                    this.get('parentView').set('isOpen', true);
                },
                keyUp: function() {
                    var key = 'value';
                    var label = 'label';
                    var parentView = this.get('parentView');
                    if (parentView.get('isLazy')) {
                        key = 'id';
                        label = 'displayName';
                    }
                    var val = this.$().val();
                    if (!val) {
                        return;
                    }
                    parentView.set('filterOn', true);
                    var pattern = new RegExp("^" + val, "i");
                    var collection = parentView.get('collection');
                    var filterIds = [];
                    var selectedIds = parentView.get('selectedIds');
                    collection.forEach(function(item) {
                        if (pattern.test(Ember.get(item, label)) && !selectedIds.contains(Ember.get(item, key))) {
                            filterIds.push(Ember.get(item, key));
                        }
                    });
                    parentView.set('filterIds', filterIds);
                    parentView.set('isOpen', true);
                },
                keyDown: function(event) {
                    event.stopPropagation();
                    var key;
                    var parentView = this.get('parentView');
                    //TODO: вынести маппинг кнопок в метод UMI.Utils
                    switch (event.keyCode) {
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
                    switch (key) {
                        case 'Down':
                        case 'Up':
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
                blur: function() {
                    this.$()[0].value = '';
                },
                willDestroyElement: function() {
                    this.removeObserver('parentView.inputInFocus');
                }
            }),

            init: function() {
                this._super();
                var self = this;
                var property = this.get('meta.dataSource');
                var object = this.get('object');
                var store = self.get('controller.store');
                var allCollection;
                var selectedObjects;
                this.set('isLazy', this.get('meta.lazy'));

                if (this.get('isLazy')) {
                    this.reopen(lazyChoicesBehaviour);

                    selectedObjects = object.get(property);

                    var getCollection = function(relation) {
                        return store.all(relation.type);
                    };

                    object.eachRelationship(function(name, relation) {
                        if (name === property) {
                            allCollection = getCollection(relation);
                        }
                    });

                    return selectedObjects.then(function(result) {
                        var relatedObjectsId = result.mapBy('id') || [];
                        var loadedRelationshipsByName = result.mapBy('id') || [];
                        self.set('collection', allCollection);
                        self.set('selectedIds', relatedObjectsId);
                        Ember.set(object.get('loadedRelationshipsByName'), property, loadedRelationshipsByName);
                    });
                } else {
                    var propertyArray = object.get(property) || '[]';
                    try {
                        propertyArray = JSON.parse(propertyArray);
                    } catch (error) {
                        error.message = 'Incorrect value of field ' + property + '. Expected array or null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    self.set('collection', this.get('meta.choices'));
                    self.set('selectedIds', propertyArray);
                }
            },

            willDestroyElement: function() {
                this.removeObserver('isOpen');
            }
        });
    };
});
define('partials/forms/elements/checkbox/main',['App'], function(UMI) {
    

    return function() {
        UMI.CheckboxElementView = Ember.View.extend({
            template: function() {
                var self = this;
                var name = self.get('name');
                var attributeValue = self.get('attributeValue');
                var className = self.get('className');
                var isChecked = self.get('value');

                var hiddenInput = '<input type="hidden" name="' + name + '" value="0" />';
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" :
                    "") + ' name="' + name + '" value="' + attributeValue + '" class="' + className + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(hiddenInput + checkbox + label);
            }.property(),

            name: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.name');
            }.property('meta.attributes.name'),

            value: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'value');
            }.property('meta.value'),

            attributeValue: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.value');
            }.property('meta.attributes.value'),

            classNames: ['umi-element-checkbox'],

            actions: {
                change: function() {
                    var $el = this.$();
                    var checkbox = $el.find('input[type="checkbox"]')[0];
                    checkbox.checked = !checkbox.checked;
                    $(checkbox).trigger("change");
                }
            }
        });

        UMI.CheckboxCollectionElementView = Ember.View.extend({
            template: function() {
                var self = this;
                var isChecked;
                var object = self.get('object');
                var meta = self.get('meta');
                var name = Ember.get(meta, 'attributes.name');
                var value = Ember.get(meta, 'attributes.value');

                isChecked = Ember.get(object, Ember.get(meta, 'dataSource'));

                var checkbox = '<input type="checkbox" ' + (
                    isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(checkbox + label);
            }.property(),

            classNames: ['umi-element-checkbox'],

            setCheckboxValue: function() {
                var self = this;
                var $el = this.$();
                if ($el) {
                    $el.find('input[type="checkbox"]')[0].checked = self.get('object.' + self.get('meta.dataSource'));
                }
            },

            addObserverProperty: function() {
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function() {
                    Ember.run.once(self, 'setCheckboxValue');
                });
            },

            init: function() {
                this._super();
                this.addObserverProperty();
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            actions: {
                change: function() {
                    var self = this;
                    var $el = this.$();
                    var checkbox;
                    self.get('object').toggleProperty(self.get('meta.dataSource'));
                }
            }
        });
    };
});
define('partials/forms/elements/radio/main',['App'], function(UMI) {
    

    return function() {
        UMI.RadioElementView = Ember.View.extend({
            templateName: 'partials/radioElement',
            classNames: ['umi-element-radio-group'],

            addObserverProperty: function() {
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function() {
                    Ember.run.once(self, 'setSelectedRadioElement');
                });
            },

            setSelectedRadioElement: function() {
                var self = this;
                var $el = this.$();
                if ($el) {
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "";
                    var radio = $el[0].querySelector('input[type="radio"][value="' + objectValue + '"]');
                    if (radio) {
                        radio.checked = true;
                    }
                }
            },

            init: function() {
                this._super();
                Ember.warn('Field with the type of radio not supported lazy choices.', !this.get('meta.lazy'));

                if (Ember.typeOf(this.get('object')) === 'instance') {
                    this.addObserverProperty();
                }
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            radioElementView: Ember.View.extend({
                classNames: ['umi-element-radio'],

                template: function() {
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue;

                    if (Ember.typeOf(object) === 'instance') {
                        objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "";
                    } else {
                        objectValue = Ember.get(meta, 'value');
                    }

                    if (objectValue === value) {
                        isChecked = true;
                    }
                    var radio = '<input type="radio" ' + (
                        isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(radio + label);
                }.property(),

                label: function() {
                    Ember.warn('For field with type of radio label not defined in choices.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),

                actions: {
                    change: function() {
                        var self = this;
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');

                        if (Ember.typeOf(object) === 'instance') {
                            Ember.set(object, propertyName, value);
                        } else {
                            var radio = this.$().find('input[type="radio"]');
                            if (radio.length) {
                                radio[0].checked = true;
                            }
                        }
                    }
                }
            })
        });
    };
});
define('partials/forms/elements/text/main',['App'], function(UMI) {
    

    return function() {
        UMI.TextElementView = Ember.View.extend(UMI.InputValidate, {
            type: 'text',

            classNames: ['umi-element-text'],

            template: function() {
                var self = this;
                var template;
                var dataSource;
                var inputTemplate;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.set('validatorType', 'collection');
                    dataSource = this.get('meta.dataSource');
                    inputTemplate = '{{input typeBinding="view.type" value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                } else {
                    this.set('validatorType', null);
                    inputTemplate = '{{input typeBinding="view.type" value=view.meta.value name=view.meta.attributes.name}}';
                }

                var validate = this.validateErrorsTemplate();
                template = inputTemplate + validate;

                return Ember.Handlebars.compile(template);
            }.property()
        });
    };
});
define('partials/forms/elements/number/main',['App'], function(UMI) {
    

    return function() {
        UMI.NumberElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-number'],
            type: 'number'
        });
    };
});
define('partials/forms/elements/email/main',['App'], function(UMI) {
    

    return function() {
        UMI.EmailElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-email'],
            type: "email"
        });
    };
});
define('partials/forms/elements/password/main',['App'], function(UMI) {
    

    return function() {
        UMI.PasswordElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],
            type: 'text'
        });
    };
});
define('partials/forms/elements/time/main',['App'], function(UMI) {
    


    return function() {
        UMI.TimeElementComponent = Ember.Component.extend(UMI.InputValidate, {
            templateName: 'partials/timeElement',
            classNames: ['row', 'collapse'],

            didInsertElement: function() {
                var el = this.$();
                el.find('.icon-delete').click(function() {
                    el.find('input').val('');
                });

                this.$().find('input').timepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    timeFormat: 'HH:mm:ss',
                    currentText: 'Выставить текущее время'
                });
            },

            inputView: Ember.View.extend({
                template: function() {
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validatorType="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});
define('partials/forms/elements/date/main',['App'], function(UMI) {
    

    return function() {
        UMI.DateElementView = Ember.View.extend({
            templateName: 'partials/dateElement',

            classNames: ['row', 'collapse'],

            didInsertElement: function() {
                this.$().find('input').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd.mm.yy'
                });
            },

            actions: {
                clearValue: function() {
                    var self = this;
                    var el = self.$();
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else {
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
define('partials/forms/elements/datetime/main',['App'], function(UMI) {
    

    return function() {

        UMI.DateTimeElementView = Ember.View.extend({
            templateName: 'partials/dateTimeElement',

            classNames: ['row', 'collapse'],

            value: null,

            changeValue: function() {
                Ember.run.once(this, 'setValueForObject');
            }.observes('value'),

            setValueForObject: function() {
                var self = this;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    var value = self.get('value');
                    value = Ember.isNone(value) ? '' : value;
                    var valueInObject = self.get('object.' + self.get("meta.dataSource")) || '';
                    if (value) {
                        if (valueInObject) {
                            valueInObject = JSON.parse(valueInObject);
                        } else {
                            valueInObject = {date: null};
                        }
                        valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                        if (valueInObject.date !== value) {
                            var result = '';
                            if (value) {
                                result = {
                                    date: value,
                                    timezone_type: 3,
                                    timezone: "Europe/Moscow"
                                };
                                result = JSON.stringify(result);
                            }
                            self.get('object').set(self.get("meta.dataSource"), result);
                        }
                    } else {
                        if (valueInObject !== value) {
                            self.get('object').set(self.get("meta.dataSource"), '');
                        }
                    }
                }
            },

            setInputValue: function() {
                var self = this;
                var valueInObject = self.get('object.' + self.get("meta.dataSource"));
                var value = self.get('value');
                value = Ember.isNone(value) ? '' : value;
                if (valueInObject) {
                    valueInObject = JSON.parse(valueInObject);
                    valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                    if (valueInObject.date !== value) {
                        self.set('value', valueInObject.date);
                    }
                } else {
                    if (valueInObject !== value) {
                        self.set('value', '');
                    }
                }
            },

            init: function() {
                this._super();
                var value;
                var self = this;
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        value = self.get('object.' + self.get("meta.dataSource")) || '{}';
                        value = JSON.parse(value);
                        self.set("value", value.date || "");

                        self.addObserver('object.' + self.get('meta.dataSource'), function() {
                            Ember.run.once(self, 'setInputValue');
                        });
                    } else {
                        self.set("value", self.get('meta.value'));
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            didInsertElement: function() {
                this.$().find('input').datetimepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    secondText: 'Секунды',
                    currentText: 'Выставить текущее время',
                    timeFormat: 'HH:mm:ss',
                    dateFormat: 'dd.mm.yy'
                });
            },

            willDestroyElement: function() {
                var self = this;
                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.removeObserver('object.' + self.get('meta.dataSource'));
                }
            },

            actions: {
                clearValue: function() {
                    var self = this;
                    self.set('value', '');
                }
            }
        });
    };
});
define('partials/forms/elements/file/main',['App'], function(UMI) {
    

    return function() {

        UMI.FileElementView = Ember.View.extend({
            templateName: 'partials/fileElement',

            classNames: ['row', 'collapse'],

            value: null,

            popupParams: function() {
                return {
                    viewParams: {
                        popupType: 'fileManager',
                        title: UMI.i18n.getTranslate('Select file')
                    },

                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta'),
                        fileSelect: function(fileInfo) {
                            var self = this;
                            var object = self.get('object');
                            var image = Ember.get(fileInfo, 'url') || '';
                            var baseUrl = Ember.get(window, 'UmiSettings.projectAssetsUrl');
                            var pattern = new RegExp('^' + baseUrl, 'g');
                            object.set(self.get('meta.dataSource'), image.replace(pattern, ''));
                            self.get('controller').send('closePopup');
                        }
                    }
                };
            }.property(),

            actions: {
                clearValue: function() {
                    var self = this;
                    var el = self.$();
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else {
                        el.find('input').val('');
                    }
                },

                showPopup: function(params) {
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    object.clearValidateForProperty(property);
                    this.get('controller').send('showPopup', params);
                }
            },

            init: function() {
                this._super();
                var self = this;
                var object = this.get('object');

                if (Ember.typeOf(object) === 'instance') {
                    var dataSource = self.get('meta.dataSource');
                    this.set('value', object.get(dataSource));
                }
            },

            didInsertElement: function() {
                var self = this;
                var object = this.get('object');
                var dataSource = self.get('meta.dataSource');
                if (Ember.typeOf(object) === 'instance') {
                    Ember.run.next(self, function() {
                        this.addObserver('object.' + dataSource, function() {
                            this.set('value', object.get(dataSource));
                        });
                    });
                }
            },

            willDestroyElement: function() {
                var self = this;
                var object = this.get('object');

                if (Ember.typeOf(object) === 'instance') {
                    var dataSource = self.get('meta.dataSource');
                    self.removeObserver('object.' + dataSource);
                }
            }
        });
    };
});
define('partials/forms/elements/image/main',['App'], function(UMI) {
    

    return function() {
        UMI.ImageElementView = UMI.FileElementView.extend({});
    };
});
define('partials/forms/elements/textarea/main',['App'], function(UMI) {
    

    return function() {

        UMI.TextareaElementView = Ember.View.extend({
            templateName: 'partials/textareaElement',

            classNames: ['umi-element-textarea'],

            textareaView: function() {
                var viewParams = {
                    didInsertElement: function() {
                        this.allowResize();
                    },

                    willDestroyElement: function() {
                        this.get('parentView').$().off('mousedown.umi.textarea');
                    },

                    allowResize: function() {
                        var $textarea = this.$().find('textarea');
                        var minHeight = 60;
                        if ($textarea.length) {
                            $textarea.css({height: minHeight});
                        }
                        this.get('parentView').$().on('mousedown.umi.textarea', '.umi-element-textarea-resizer', function(event) {
                            if (event.button === 0) {
                                var $el = $(this);
                                $('html').addClass('s-unselectable');
                                $el.addClass('s-unselectable');
                                var posY = $textarea.offset().top;
                                $('body').on('mousemove.umi.textarea', function(event) {
                                    //TODO: Cделать метод глобальным
                                    //Подумать над тем, чтобы выделение сделаное до mouseMove не слетало
                                    //http://hashcode.ru/questions/86466/javascript-%D0%BA%D0%B0%D0%BA-%D0%B7%D0%B0%D0%BF%D1%80%D0%B5%D1%82%D0%B8%D1%82%D1%8C-%D0%B2%D1%8B%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%81%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B8%D0%BC%D0%BE%D0%B3%D0%BE-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%B1%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B5
                                    var removeSelection = function() {
                                        if (window.getSelection) {
                                            window.getSelection().removeAllRanges();
                                        } else if (document.selection && document.selection.clear) {
                                            document.selection.clear();
                                        }
                                    };
                                    removeSelection();

                                    var height = event.pageY - posY;
                                    if (height < minHeight) {
                                        height = minHeight
                                    }
                                    $textarea.css({height: height});
                                });
                                $('body').on('mouseup.umi.textarea', function() {
                                    $('body').off('mousemove.umi.textarea');
                                    $('body').off('mouseup.umi.textarea');
                                    $('html').removeClass('s-unselectable');
                                });
                            }
                        });
                    }
                };

                if (Ember.typeOf(this.get('object')) === 'instance') {
                    viewParams.template = function() {
                        var propertyName = this.get('parentView.meta.dataSource');
                        var textarea = '{{textarea placeholder=view.parentView.attributes.placeholder name=view.parentView.meta.attributes.name value=view.parentView.object.' + propertyName + '}}';
                        var validate = this.validateErrorsTemplate();
                        return Ember.Handlebars.compile(textarea + validate);
                    }.property();
                    return Ember.View.extend(UMI.InputValidate, viewParams);
                } else {
                    viewParams.template = function() {
                        var textarea = '{{textarea placeholder=view.parentView.attributes.placeholder name=view.parentView.meta.attributes.name value=view.parentView.attributes.value}}';
                        return Ember.Handlebars.compile(textarea);
                    }.property();
                    return Ember.View.extend(viewParams);
                }
            }.property()
        });
    };
});
define('partials/forms/elements/checkbox-group/main',['App'], function(UMI) {
    

    return function() {
        UMI.CheckboxGroupElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup',
            classNames: ['umi-element-checkbox-group']
        });

        UMI.CheckboxGroupCollectionElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup/collectionElement',
            classNames: ['umi-element-checkbox-group'],

            addObserverProperty: function() {
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function() {
                    Ember.run.once(self, 'setCheckboxesValue');
                });
            },

            setCheckboxesValue: function() {
                var self = this;
                var $el = this.$();
                if ($el) {
                    var checkboxes = $el.find('input[type="checkbox"]');
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "[]";
                    try {
                        objectValue = JSON.parse(objectValue);
                    } catch (error) {
                        error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = objectValue.contains(checkboxes[i].value);
                    }
                }
            },

            init: function() {
                this._super();
                Ember.warn('Field with type of checkboxGroup no supported lazy choices.', !this.get('meta.lazy'));
                this.addObserverProperty();
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            checkboxElementView: Ember.View.extend({
                classNames: ['umi-element-checkbox'],
                template: function() {
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "[]";
                    try {
                        objectValue = JSON.parse(objectValue);
                    } catch (error) {
                        error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    if (objectValue.contains(value)) {
                        isChecked = true;
                    }
                    var checkbox = '<input type="checkbox" ' + (
                        isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(checkbox + label);
                }.property(),
                label: function() {
                    Ember.warn('For the field with type of checkboxGroup label not defined in choices.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),
                actions: {
                    change: function() {
                        var self = this;
                        var $el = this.$();
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');
                        var objectValue = Ember.get(object, propertyName) || "[]";
                        try {
                            objectValue = JSON.parse(objectValue);
                        } catch (error) {
                            error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                            this.get('controller').send('backgroundError', error);
                        }

                        if (objectValue.contains(value)) {
                            objectValue = objectValue.without(value);
                        } else {
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
define('partials/forms/elements/color/main',['App'], function(UMI) {
    

    return function() {
        UMI.ColorElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-color'],
            type: "color"
        });
    };
});
define('partials/forms/elements/permissions/main',['App'], function(UMI) {
    

    return function() {
        UMI.PermissionsView = Ember.View.extend({
            templateName: 'partials/permissions',
            objectProperty: function() {
                var object = this.get('object');
                var dataSource = this.get('meta.dataSource');
                var property = object.get(dataSource) || "{}";
                try {
                    property = JSON.parse(property);
                    if (Ember.typeOf(property) !== 'object') {
                        property = {};
                    }
                } catch (error) {
                    this.get('controller').send('backgroundError', error);
                } finally {
                    return property;
                }
            }.property('object'),

            setObjectProperty: function(checkbox, path, isChecked) {
                var object = this.get('object');
                var objectProperty = this.get('objectProperty');
                var componentRoles = objectProperty[path];
                var childrenCheckboxes;
                var i;
                var childComponentName;
                var currentRole = checkbox.value;

                if (Ember.typeOf(componentRoles) !== 'array') {
                    componentRoles = objectProperty[path] = [];
                }

                function checkedParentCheckboxes(checkbox) {
                    if (checkbox) {
                        var checkedSiblingsCheckboxes = 0;
                        var checkboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        for (var i = 0; i < checkboxes.length; i++) {
                            if (checkboxes[i].checked) {
                                checkedSiblingsCheckboxes++;
                            }
                        }
                        if (checkedSiblingsCheckboxes === checkboxes.length) {
                            checkbox.indeterminate = false;
                        } else {
                            checkbox.indeterminate = true;
                        }

                        var parentCheckbox = $(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                        if (parentCheckbox.length) {
                            parentCheckbox[0].checked = true;

                            var parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var parentComponentRoles = objectProperty[parentComponentName];
                            if (Ember.typeOf(parentComponentRoles) !== 'array') {
                                parentComponentRoles = objectProperty[parentComponentName] = [];
                            }
                            if (!parentComponentRoles.contains(parentCheckbox[0].name)) {
                                parentComponentRoles.push(parentCheckbox[0].name);
                                parentComponentRoles.sort();
                            }
                            checkedParentCheckboxes(parentCheckbox[0]);
                        }
                    }
                }

                function checkedChildrenCheckboxes(checkbox) {
                    checkbox.indeterminate = false;
                    var childrenCheckboxes;
                    var childComponentName;
                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if (childrenCheckboxes.length) {
                        for (i = 0; i < childrenCheckboxes.length; i++) {
                            childrenCheckboxes[i].checked = true;
                            childrenCheckboxes[i].indeterminate = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            if (Ember.typeOf(objectProperty[childComponentName]) !== 'array') {
                                objectProperty[childComponentName] = [];
                            }
                            objectProperty[childComponentName].push(childrenCheckboxes[i].name);
                        }
                    }
                }

                function setParentCheckboxesIndeterminate(checkbox) {
                    var childrenCheckboxes;
                    var childrenCheckboxesChecked = 0;
                    var parentComponentName;
                    var parentComponentRoles;
                    var parentCheckbox = $(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                    if (parentCheckbox.length) {
                        if (parentCheckbox[0].checked) {
                            childrenCheckboxes = $(parentCheckbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                            if (childrenCheckboxes.length) {
                                for (var i = 0; i < childrenCheckboxes.length; i++) {
                                    if (childrenCheckboxes[i].checked) {
                                        ++childrenCheckboxesChecked;
                                    }
                                }
                            }
                            if (!childrenCheckboxesChecked) {
                                parentCheckbox[0].checked = false;
                                parentCheckbox[0].indeterminate = false;
                                parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                                parentComponentRoles = objectProperty[parentComponentName];
                                if (Ember.typeOf(parentComponentRoles) !== 'array') {
                                    parentComponentRoles = objectProperty[parentComponentName] = [];
                                }
                                parentComponentRoles = objectProperty[parentComponentName] = parentComponentRoles.without(parentCheckbox[0].name);
                                if (!parentComponentRoles.length) {
                                    delete objectProperty[parentComponentName];
                                }
                            } else {
                                parentCheckbox[0].indeterminate = true;
                            }
                        }
                        setParentCheckboxesIndeterminate(parentCheckbox[0]);
                    }
                }

                if (isChecked) {
                    if (!componentRoles.contains(currentRole)) {
                        componentRoles.push(currentRole);
                        componentRoles.sort();
                    }
                    checkedChildrenCheckboxes(checkbox);
                    checkedParentCheckboxes(checkbox);
                } else {
                    if (componentRoles.contains(currentRole)) {
                        objectProperty[path] = componentRoles.without(currentRole);
                        if (!objectProperty[path].length) {
                            delete objectProperty[path];
                        }
                    }

                    checkbox.indeterminate = false;

                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if (childrenCheckboxes.length) {
                        for (i = 0; i < childrenCheckboxes.length; i++) {
                            childrenCheckboxes[i].checked = false;
                            childrenCheckboxes[i].indeterminate = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var childComponentRoles = objectProperty[childComponentName];
                            if (Ember.typeOf(childComponentRoles) !== 'array') {
                                childComponentRoles = objectProperty[childComponentName] = [];
                            }
                            childComponentRoles = objectProperty[childComponentName] = childComponentRoles.without(childrenCheckboxes[i].name);
                            if (!childComponentRoles.length) {
                                delete objectProperty[childComponentName];
                            }
                        }
                    }

                    setParentCheckboxesIndeterminate(checkbox);
                }
                if (JSON.stringify(objectProperty) === '{}') {
                    objectProperty = [];
                }
                object.set(this.get('meta.dataSource'), JSON.stringify(objectProperty));
            },

            didInsertElement: function() {
                var self = this;
                var $el = this.$();
                var property = this.get('objectProperty');

                var checkedInput = function(objectProperty, componentName) {
                    var i;
                    var checkbox;
                    if (Ember.typeOf(objectProperty[componentName]) === 'array') {
                        for (i = 0; i < objectProperty[componentName].length; i++) {
                            checkbox = $el.find('[data-permissions-component-path="' + componentName + '"]').find('.umi-permissions-role-checkbox').filter('[name="' + objectProperty[componentName][i] + '"]');
                            if (checkbox.length) {
                                checkbox[0].checked = true;
                            }
                        }
                    }
                };

                function setCheckboxIndeterminate(checkbox) {
                    if (checkbox.checked) {
                        var childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        var checkedChildrenCheckboxes = 0;
                        for (var i = 0; i < childrenCheckboxes.length; i++) {
                            if (childrenCheckboxes[i].checked) {
                                checkedChildrenCheckboxes++;
                            }
                        }
                        if (checkedChildrenCheckboxes === childrenCheckboxes.length) {
                            checkbox.indeterminate = false;
                        } else {
                            checkbox.indeterminate = true;
                        }
                    }
                }

                for (var key in property) {
                    if (property.hasOwnProperty(key)) {
                        checkedInput(property, key);
                    }
                }

                var $checkboxes = $el.find('.umi-permissions-role-checkbox');
                for (var i = 0; i < $checkboxes.length; i++) {
                    setCheckboxIndeterminate($checkboxes[i]);
                }

                var accordion = $el.find('.accordion');
                accordion.each(function(index) {
                    var triggerButton = $(accordion[index]).find('.accordion-navigation-button');
                    var triggerBlock = $(accordion[index]).find('.content');
                    triggerButton.on('click.umi.permissions.triggerButton', function() {
                        triggerBlock.toggleClass('active');
                        triggerButton.find('.icon').toggleClass('icon-right icon-bottom');
                    });
                });

                $el.on('click.umi.permissions', '.umi-permissions-role-button-expand', function() {
                    var component = $(this).closest('li').children('.umi-permissions-component');
                    component.toggleClass('expand');
                    $(this).find('.icon').toggleClass('icon-right icon-bottom');
                    component.find('.umi-permissions-component').removeClass('expand');
                    component.find('.umi-permissions-role-button-expand').find('.icon').addClass('icon-right').removeClass('icon-bottom');
                });

                $el.on('change.umi.permissions', '.umi-permissions-role-checkbox', function() {
                    var isChecked = this.checked;
                    var componentName = $(this).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                    self.setObjectProperty(this, componentName, isChecked);
                });
            },
            willDestroyElement: function() {
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
define('partials/forms/elements/objectRelationElement/main',['App'], function(UMI) {
    

    return function() {
        UMI.ObjectRelationElementView = Ember.View.extend(UMI.SerializedValue, {
            templateName: 'partials/objectRelationElement',

            classNames: ['row', 'collapse'],
            /**
             * @extended UMI.SerializedValue
             */
            path: 'displayName',
            /**
             * @extended UMI.SerializedValue
             */
            objectIsObservable: true,

            popupParams: function() {
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta')
                    },

                    viewParams: {
                        title: this.get('meta.label'),
                        popupType: 'objectRelation'
                    }
                };
            }.property(),

            actions: {
                clearValue: function() {
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    self.set('value', '');
                    object.set(property, '');
                    object.validateProperty(property);
                },

                showPopup: function(params) {
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    object.clearValidateForProperty(property);
                    this.get('controller').send('showPopup', params);
                }
            },

            inputView: Ember.View.extend(UMI.InputValidate, {
                type: "text",

                classNames: ['umi-element-text'],

                template: function() {
                    var template;
                    if (Ember.typeOf(this.get('object')) === 'instance') {
                        this.set('validator', 'collection');
                        var input = '{{input type=view.type value=view.parentView.value placeholder=view.meta.placeholder name=view.meta.attributes.name disabled=true}}';
                        var validate = this.validateErrorsTemplate();
                        template = input + validate;
                    }
                    return Ember.Handlebars.compile(template);
                }.property()
            })
        });


        UMI.ObjectRelationLayoutController = Ember.ObjectController.extend({
            sideBarControl: Ember.computed.gt('collections.length', 1),

            collections: [],

            selectedCollection: null,

            tableControlSettings: function() {
                var self = this;
                var selectedCollectionId = self.get('selectedCollection.id');
                var object = self.get('model.object');
                var meta = self.get('model.meta');
                var property = object.get(Ember.get(meta, 'dataSource'));
                var activeObjectGuid;
                if (property) {
                    try {
                        property = JSON.parse(property);
                        activeObjectGuid = Ember.get(property, 'guid');
                    } catch (error) {
                        self.send('backgroundError', error);
                    }
                }

                return {
                    control: {
                        collectionName: selectedCollectionId,
                        meta: {
                            defaultFields: [
                                "displayName"
                            ],
                            activeObjectGuid: activeObjectGuid,
                            form: {
                                elements: [
                                    {
                                        type: "text",
                                        tag: "input",
                                        id: "displayName",
                                        label: "Имя отображения",//TODO: localize
                                        attributes: {
                                            name: "displayName",
                                            type: "text",
                                            value: null
                                        },
                                        valid: true,
                                        errors: [],
                                        dataSource: "displayName",
                                        value: null,
                                        validators: [],
                                        filters: []
                                    }
                                ]
                            }
                        },
                        behaviour: {
                            rowEvent: function(context, selectedObject) {
                                var dataSource = Ember.get(meta, 'dataSource');
                                var collectionName = Ember.get(selectedObject, 'constructor.typeKey');
                                var value = {
                                    collection: collectionName,
                                    guid: selectedObject.get('guid'),
                                    displayName: selectedObject.get('displayName')
                                };
                                object.set(dataSource, JSON.stringify(value));
                                context.send('closePopup');
                                //self.set('selectedCollection', self.get('collections').findBy('id', collectionName));
                            }
                        }
                    }
                };
            }.property('selectedCollection'),

            init: function() {
                var self = this;
                var object = self.get('object');
                var meta = self.get('meta');
                var dataSource = Ember.get(meta, 'dataSource');
                var computedProperty = object.get(dataSource);
                var collections = Ember.get(meta, 'collections');
                var collectionName;

                if (Ember.typeOf(collections) !== 'array') {
                    collections = [];
                }
                Ember.warn('Collection list is empty.', collections.length);
                self.set('collections', collections);

                if (computedProperty) {
                    try {
                        computedProperty = JSON.parse(computedProperty);
                        collectionName = Ember.get(computedProperty, 'meta.collectionName') || Ember.get(computedProperty, 'collection');
                        self.set('selectedCollection', collections.findBy('id', collectionName));
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                } else {
                    self.set('selectedCollection', collections[0]);
                }
            }
        });

        UMI.ObjectRelationLayoutView = Ember.View.extend({
            classNames: ['s-full-height'],
            templateName: 'partials/objectRelationLayout',
            sideMenu: Ember.View.extend({
                tagName: 'ul',
                classNames: ['side-nav'],
                templateName: 'partials/objectRelationLayout/sideMenu',
                itemView: Ember.View.extend({
                    tagName: 'li',
                    classNameBindings: ['isActive:active'],
                    isActive: function() {
                        return this.get('controller.selectedCollection.id') === this.get('item.id');
                    }.property('controller.selectedCollection'),
                    click: function() {
                        if (!this.get('isActive')) {
                            this.get('controller').set('selectedCollection', this.get('item'));
                        }
                    }
                })
            })
        });
    };
});
define('partials/forms/elements/singleCollectionObjectRelation/main',['App'], function(UMI) {
    

    return function() {
        UMI.SingleCollectionObjectRelationElementView = UMI.ObjectRelationElementView.extend({
            templateName: 'partials/singleCollectionObjectRelationElement',

            classNames: ['row', 'collapse'],

            objectIsObservable: false,

            popupParams: function() {
                return {
                    templateParams: {
                        meta: this.get('meta')
                    },

                    viewParams: {
                        title: this.get('meta.label'),
                        popupType: 'singleCollectionObjectRelation'
                    }
                };
            }.property(),

            actions: {
                clearValue: function() {
                    var self = this;
                    self.set('value', '');
                },

                showPopup: function(params) {
                    this.get('controller').send('showPopup', params);
                }
            }
        });

        UMI.SingleCollectionObjectRelationLayoutController = UMI.ObjectRelationLayoutController.extend({
            tableControlSettings: function() {
                var self = this;
                var selectedCollectionId = self.get('selectedCollection.id');
                var meta = self.get('model.meta');
                var activeObjectGuid;
                activeObjectGuid = Ember.get(meta, 'value');

                return {
                    control: {
                        collectionName: selectedCollectionId,
                        meta: {
                            defaultFields: [
                                "displayName"
                            ],
                            activeObjectGuid: activeObjectGuid,
                            form: {
                                elements: [
                                    {
                                        type: "text",
                                        tag: "input",
                                        id: "displayName",
                                        label: "Имя отображения",//TODO: localize
                                        attributes: {
                                            name: "displayName",
                                            type: "text",
                                            value: null
                                        },
                                        valid: true,
                                        errors: [],
                                        dataSource: "displayName",
                                        value: null,
                                        validators: [],
                                        filters: []
                                    }
                                ]
                            }
                        },
                        behaviour: {
                            rowEvent: function(context, selectedObject) {
                                var value = selectedObject.get('guid');
                                Ember.set(meta, 'value', value);
                                context.send('closePopup');
                            }
                        }
                    }
                };
            }.property('selectedCollection'),

            init: function() {
                var self = this;
                var meta = self.get('meta');
                var collection = Ember.get(meta, 'collection');
                var collections = [];

                collections = [collection];
                Ember.warn('Collection list is empty.', collections.length);
                self.set('collections', collections);
                self.set('selectedCollection', collection);
            }
        });

        UMI.SingleCollectionObjectRelationLayoutView = UMI.ObjectRelationLayoutView.extend({});
    };
});
define('forms/elements/main',[
    'App',

    'partials/forms/elements/mixins',

    'partials/forms/elements/wysiwyg/main', 'partials/forms/elements/select/main',
    'partials/forms/elements/multi-select/main', 'partials/forms/elements/checkbox/main',
    'partials/forms/elements/radio/main', 'partials/forms/elements/text/main', 'partials/forms/elements/number/main',
    'partials/forms/elements/email/main', 'partials/forms/elements/password/main', 'partials/forms/elements/time/main',
    'partials/forms/elements/date/main', 'partials/forms/elements/datetime/main', 'partials/forms/elements/file/main',
    'partials/forms/elements/image/main', 'partials/forms/elements/textarea/main',
    'partials/forms/elements/checkbox-group/main', 'partials/forms/elements/color/main',
    'partials/forms/elements/permissions/main', 'partials/forms/elements/objectRelationElement/main',
    'partials/forms/elements/singleCollectionObjectRelation/main'
], function(
        UMI, mixins, wysiwygElement, selectElement, multiSelectElement, checkboxElement, radioElement, textElement, numberElement, emailElement, passwordElement, timeElement, dateElement, datetimeElement, fileElement, imageElement, textareaElement, checkboxGroupElement, colorElement, permissions, objectRelationElement, singleCollectionObjectRelation
        ) {
        

        return function() {
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
            objectRelationElement();
            singleCollectionObjectRelation();
        };
    });
define('partials/forms/partials/magellan/main',['App'], function(UMI) {
    

    return function() {
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before', 's-unselectable'],
            focusId: null,
            elementView: Ember.View.extend({
                isFieldset: function() {
                    return this.get('content.type') === 'fieldset';
                }.property()
            }),
            buttonView: Ember.View.extend({
                tagName: 'a',
                classNameBindings: ['isFocus:focus'],
                isFocus: function() {
                    return this.get('model.id') === this.get('parentView.parentView.focusId');
                }.property('parentView.parentView.focusId'),
                click: function() {
                    var self = this;
                    var fieldset = document.getElementById('fieldset-' + this.get('model.id'));
                    $(fieldset).closest('.magellan-content').animate({'scrollTop': fieldset.parentNode.offsetTop - parseFloat(getComputedStyle(fieldset).marginTop)}, 0);
                    setTimeout(function() {
                        if (self.get('parentView.parentView.focusId') !== self.get('model.id')) {
                            self.get('parentView.parentView').set('focusId', self.get('model.id'));
                        }
                    }, 10);
                }
            }),
            init: function() {
                var elements = this.get('elements');
                elements = elements.filter(function(item) {
                    return item.type === 'fieldset';
                });
                this.set('focusId', elements.get('firstObject.id'));
            },
            didInsertElement: function() {
                var self = this;
                var scrollArea = $('.magellan-menu').parent().find('.magellan-content');//TODO: По хорошему нужно выбирать элемент через this.$()
                if (!scrollArea.length) {
                    return;
                }
                var fieldset = scrollArea.find('fieldset');
                Ember.run.next(self, function() {
                    var lastFieldset = fieldset[fieldset.length - 1];
                    var placeholderFieldset;
                    var lastFieldsetHeight = lastFieldset.offsetHeight;
                    var scrollAreaHeight = scrollArea[0].offsetHeight;
                    var setPlaceholderHeight = function(placeholder) {
                        lastFieldsetHeight = lastFieldset.offsetHeight;
                        scrollAreaHeight = scrollArea[0].offsetHeight;
                        placeholder.style.height = scrollAreaHeight - lastFieldsetHeight - 10 - parseInt($(lastFieldset).css('marginBottom')) + 'px';
                    };

                    if (scrollAreaHeight > lastFieldsetHeight) {
                        placeholderFieldset = document.createElement('div');
                        placeholderFieldset.className = 'umi-js-fieldset-placeholder';
                        placeholderFieldset = scrollArea[0].appendChild(placeholderFieldset);
                        setPlaceholderHeight(placeholderFieldset);
                        $(window).on('resize.umi.magellan.fieldsetPlaceholder', function() {
                            setPlaceholderHeight(placeholderFieldset);
                        });
                    }

                    scrollArea.on('scroll.umi.magellan', function() {
                        var scrollOffset = $(this).scrollTop();
                        var focusField;
                        var scrollElement;
                        for (var i = 0; i < fieldset.length; i++) {
                            scrollElement = fieldset[i].parentNode.offsetTop;
                            if (scrollElement - parseFloat(getComputedStyle(fieldset[i]).marginTop) <= scrollOffset && scrollOffset <= scrollElement + fieldset[i].offsetHeight) {
                                focusField = fieldset[i];
                            }
                        }
                        if (focusField) {
                            self.set('focusId', focusField.id.replace(/^fieldset-/g, ''));
                        }
                    });
                });
            }
        });
    };
});
define('partials/forms/partials/submitToolbar/main',['App', 'toolbar'], function(UMI) {
    

    return function() {
        UMI.SubmitToolbarView = Ember.View.extend({
            layoutName: 'partials/form/submitToolbar',
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function() {
                    var instance = UMI.SplitButtonView.extend({});
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                    behaviour.extendButton = behaviour.extendButton || {};
                    behaviour.extendButton.dataOptions = function() {
                        return 'side: top; align: right;';
                    }.property();
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
    function(UMI, magellan, submitToolbar) {
        

        /**
         * Базовый тип формы.
         * @example
         * Объявление формы:
         *  {{render 'formBase' model}}
         */
        return function() {

            magellan();
            submitToolbar();

            UMI.FormControllerMixin = Ember.Mixin.create(UMI.i18nInterface, {
                dictionaryNamespace: 'form',
                localDictionary: function() {
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
                submit: function() {
                    return false;
                },
                /**
                 * Проверяет наличие fieldset
                 * @method hasFieldset
                 * @return bool
                 */
                hasFieldset: function() {
                    return this.get('context.control.meta.elements').isAny('type', 'fieldset');
                }.property('context.control.meta'),

                elementView: Ember.View.extend({
                    classNameBindings: ['isField'],
                    isFieldset: function() {
                        return this.get('content.type') === 'fieldset';
                    }.property(),
                    isExpanded: true,
                    isField: function() {
                        if (!this.get('isFieldset')) {
                            return this.gridType();
                        }
                    }.property(),
                    /**
                     * @method gridType
                     */
                    gridType: function() {
                        var wideElements = ['wysiwyg', 'permissions'];
                        var widthClass = 'large-4 small-12';
                        if (wideElements.contains(this.get('content.type'))) {
                            widthClass = 'small-12';
                        }
                        return 'umi-columns ' + widthClass;
                    },

                    actions: {
                        expand: function() {
                            this.toggleProperty('isExpanded');
                        }
                    }
                })
            });

            UMI.FieldMixin = Ember.Mixin.create({
                classNameBindings: ['isError:error'],
                /**
                 * @property isError
                 * @hook
                 */
                isError: function() {}.property(),

                isRequired: function() {
                    var validators = this.get('meta.validators');
                    if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                        return ' *';
                    }
                }.property(),

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span></div>{{yield}}'),

                template: function() {
                    var meta;
                    var template;
                    try {
                        meta = this.get('meta');
                        template = this.get(Ember.String.camelize(meta.type) + 'Template') || '';
                        if (!template) {
                            throw new Error('For a field of type ' + meta.type + ' template method is not implemented.');
                        }
                    } catch (error) {
                        this.get('controller').send('backgroundError', error);// TODO: при первой загрузке сообщения не всплывают.
                    } finally {
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
                extendTemplate: function(template) {
                    return template;
                },

                textTemplate: function() {
                    return '{{view "textElement" object=view.object meta=view.meta}}';
                }.property(),

                emailTemplate: function() {
                    return '{{view "emailElement" object=view.object meta=view.meta}}';
                }.property(),

                passwordTemplate: function() {
                    return '{{view "passwordElement" object=view.object meta=view.meta}}';
                }.property(),

                numberTemplate: function() {
                    return '{{view "numberElement" object=view.object meta=view.meta}}';
                }.property(),

                colorTemplate: function() {
                    return '{{view "colorElement" object=view.object meta=view.meta}}';
                }.property(),

                timeTemplate: function() {
                    return '{{time-element object=view.object meta=view.meta}}';
                }.property(),

                dateTemplate: function() {
                    return '{{view "dateElement" object=view.object meta=view.meta}}';
                }.property(),

                datetimeTemplate: function() {
                    return '{{view "dateTimeElement" object=view.object meta=view.meta}}';
                }.property(),

                fileTemplate: function() {
                    return '{{view "fileElement" object=view.object meta=view.meta}}';
                }.property(),

                imageTemplate: function() {
                    return '{{view "imageElement" object=view.object meta=view.meta}}';
                }.property(),

                textareaTemplate: function() {
                    return '{{view "textareaElement" object=view.object meta=view.meta}}';
                }.property(),

                wysiwygTemplate: function() {
                    return '{{view "htmlEditor" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function() {
                    return '{{view "select" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                multiSelectTemplate: function() {
                    return '{{view "multiSelect" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                checkboxTemplate: function() {
                    return '{{view "checkboxElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function() {
                    return '{{view "checkboxGroupElement" object=view.object meta=view.meta}}';
                }.property(),

                radioTemplate: function() {
                    return '{{view "radioElement" object=view.object meta=view.meta}}';
                }.property(),

                submitTemplate: function() {
                    return '<span class="button right" {{action "submit" target="view"}}>{{view.meta.label}}</span>';
                }.property()
            });

            UMI.FormBaseController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                formElementsBinding: 'control.meta.elements'
            });

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
                classNames: ['s-margin-clear', 's-full-height', 'umi-form-control', 'umi-validator'],

                attributeBindings: ['action'],

                action: function() {
                    return this.get('context.control.meta.attributes.action');
                }.property('context.control.meta.attributes.action'),

                actions: {
                    submit: function(handler) {
                        var self = this;
                        if (handler) {
                            handler.addClass('loading');
                        }
                        var data = this.$().serialize();

                        $.ajax({
                            type: 'POST',
                            url: self.get('action'),
                            global: false,
                            data: data,

                            success: function(results) {
                                var meta = Ember.get(results, 'result.save');
                                var context = self.get('context');
                                if (meta) {
                                    Ember.set(context, 'control.meta', meta);
                                }
                                handler.removeClass('loading');
                                var params = {type: 'success', content: UMI.i18n.getTranslate('Saved') + '.'};
                                UMI.notification.create(params);
                            },

                            error: function(results) {
                                var result = Ember.get(results, 'responseJSON.result');
                                var meta = Ember.get(result, 'save');
                                var context = self.get('context');
                                if (meta && Ember.get(meta, 'type')) {
                                    Ember.set(context, 'control.meta', meta);
                                }
                                var error = Ember.get(result, 'error');
                                if (error && Ember.get(error, 'message')) {
                                    var params = {type: 'error', content: Ember.get(error, 'message')};
                                    UMI.notification.create(params);
                                }
                                handler.removeClass('loading');
                            }
                        });
                    }
                },

                submitToolbarView: UMI.SubmitToolbarView.extend({
                    elementView: Ember.View.extend(UMI.ToolbarElement, {
                        buttonView: function() {
                            var params = {};
                            if (this.get('context.behaviour.name') === 'save') {
                                params = {
                                    actions: {
                                        save: function() {
                                            this.get('parentView.parentView.parentView').send('submit', this.$());
                                        }
                                    }
                                };
                            }
                            return UMI.ButtonView.extend(params);
                        }.property()
                    })
                })
            });

            UMI.FieldBaseView = Ember.View.extend(UMI.FieldMixin, {
                layout: function() {
                    var type = this.get('meta.type');
                    var layout = '<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span></div>{{yield}}';

                    switch (type) {
                        case 'checkbox':
                            layout = '<div class="umi-form-element-without-label">{{yield}}{{view.isRequired}}</div>';
                            break;
                        case 'submit':
                            layout = '{{yield}}';
                            break;
                    }

                    return Ember.Handlebars.compile(layout);
                }.property(),

                isError: function() {
                    var errors = this.get('meta.errors');
                    if (Ember.typeOf(errors) === 'array' && errors.length) {
                        return errors.join('. ');
                    }
                }.property('meta.errors.@each'),

                singleCollectionObjectRelationTemplate: function() {
                    return '{{view "singleCollectionObjectRelationElement" object=view.object meta=view.meta}}';
                }.property()
            });
        };
    }
);

define('forms/formControl/main',['App'],

    function(UMI) {
        

        return function() {

            UMI.FormHelper = {
                getNestedProperties: function(elements, ignoreTypes) {
                    var propertyNames = [];
                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    if (Ember.typeOf(ignoreTypes) !== 'array') {
                        ignoreTypes = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (!ignoreTypes.contains(Ember.get(elements[i], 'type'))) {
                            var dataSource = Ember.get(elements[i], 'dataSource');
                            if (dataSource) {
                                propertyNames.push(dataSource);
                            }
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedPropertyNames = this.getNestedProperties(nestedElements, ignoreTypes);
                            propertyNames = propertyNames.concat(nestedPropertyNames);
                        }
                    }

                    return propertyNames;
                },

                filterLazyProperties: function(elements) {
                    var lazyProperties = [];

                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (elements[i].lazy) {
                            lazyProperties.push(Ember.get(elements[i], 'dataSource'));
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedLazyProperties = this.filterLazyProperties(nestedElements);
                            lazyProperties = lazyProperties.concat(nestedLazyProperties);
                        }
                    }

                    return lazyProperties;
                },

                fillMeta: function(meta, object) {
                    var _fillMeta = function(elements) {
                        var dataSource;
                        for (var i = 0; i < elements.length; i++) {
                            dataSource = elements[i].dataSource;
                            if (dataSource) {
                                elements[i].value = object.get(dataSource);
                            }

                            if (elements[i].hasOwnProperty('elements')) {
                                _fillMeta(elements[i].elements);
                            }
                        }
                    };

                    var elements = Ember.get(meta, 'elements');

                    _fillMeta(elements);
                    return meta;
                }
            };

            var objectFetch = function(routeData) {
                var meta = Ember.get(routeData, 'control.meta');
                var incompleteObject = Ember.get(routeData, 'object');
                var collectionName = incompleteObject.constructor.typeKey;
                var store = incompleteObject.get('store');
                var fields;
                var ignoreTypes = ['fieldset'];
                var promises = [];
                fields = UMI.FormHelper.getNestedProperties(Ember.get(meta, 'elements'), ignoreTypes);

                var request = UMI.OrmHelper.buildRequest(incompleteObject, fields);
                request.filters = {id: incompleteObject.get('id')};
                promises.push(store.updateCollection(collectionName, request));

                var lazyProperties = UMI.FormHelper.filterLazyProperties(Ember.get(meta, 'elements'));
                var manyRelationProperties = UMI.OrmHelper.getRelationProperties(incompleteObject, lazyProperties);

                for (collectionName in manyRelationProperties) {
                    if (manyRelationProperties.hasOwnProperty(collectionName)) {
                        promises.push(store.updateCollection(collectionName, {
                            fields: manyRelationProperties[collectionName]
                        }));
                    }
                }

                return Ember.RSVP.all(promises);
            };

            var FormControlPromiseService = Ember.Object.extend({
                execute: function(model) {
                    var defer = Ember.RSVP.defer();

                    objectFetch(model).then(
                        function(result) {
                            defer.resolve(result);
                        },
                        function(error) {
                            defer.reject(error);
                        }
                    );

                    return defer.promise;
                }
            });

            UMI.register('service:formControlPromise', FormControlPromiseService);
            UMI.inject('controller:action', 'editFormPromiseService', 'service:formControlPromise');

            var CreateFormControlPromiseService = Ember.Object.extend({
                execute: function(model) {
                    var defer = Ember.RSVP.defer();
                    var replacedModel = $.extend({}, model);
                    replacedModel.object = replacedModel.createObject;

                    objectFetch(replacedModel).then(
                        function(result) {
                            defer.resolve(result);
                        },
                        function(error) {
                            defer.reject(error);
                        }
                    );

                    return defer.promise;
                }
            });
            UMI.register('service:createFormControlPromise', CreateFormControlPromiseService);
            UMI.inject('controller:action', 'createFormPromiseService', 'service:createFormControlPromise');

            UMI.FormControlController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                needs: ['component'],

                formElementsBinding: 'control.meta.elements',

                objectBinding: 'model.object',

                settings: function() {
                    var settings = {};
                    settings = this.get('controllers.component.settings');
                    return settings;
                }.property(),

                inputElements: function() {
                    var elements = this.get('control.meta.elements');
                    var inputElements = [];
                    var i;

                    for (i = 0; i < elements.length; i++) {
                        if (Ember.get(elements[i], 'type') === 'fieldset' &&
                            Ember.typeOf(Ember.get(elements[i], 'elements')) === 'array') {
                            inputElements = inputElements.concat(elements[i].elements);
                        } else {
                            inputElements.push(elements[i]);
                        }
                    }

                    return inputElements;
                },

                validationErrors: function() {
                    var validErrors = this.get('object.validErrors');
                    var stack = [];
                    var key;
                    var inputElements = this.inputElements();
                    var validateErrorLabel = UMI.i18n.getTranslate('Object') + ' ' +
                        UMI.i18n.getTranslate('Not valid').toLowerCase() + '.';
                    var settings = {
                        type: 'error',
                        duration: false,
                        title: validateErrorLabel,
                        kind: 'validate',
                        close: false
                    };

                    for (key in validErrors) {
                        if (validErrors.hasOwnProperty(key) && !inputElements.findBy('dataSource', key)) {
                            stack.push('<div>' + key + ': ' + validErrors[key] + '</div>');
                        }
                    }

                    if (stack.length) {
                        settings.content = stack.join();
                        UMI.notification.create(settings);
                    } else {
                        UMI.notification.removeWithKind('validateError');
                    }
                }.observes('object.validErrors.@each'),

                actionWithCustomValidate: function(actionName, params) {
                    var elements = this.inputElements();
                    elements = elements.mapBy('dataSource');
                    params.fields = elements;
                    this.get('controllers.component').send(actionName, params);
                },

                actions: {
                    save: function(params) {
                        this.actionWithCustomValidate('save', params);
                    },

                    saveAndGoBack: function(params) {
                        this.actionWithCustomValidate('saveAndGoBack', params);
                    },

                    add: function(params) {
                        this.actionWithCustomValidate('add', params);
                    },

                    addAndGoBack: function(params) {
                        this.actionWithCustomValidate('addAndGoBack', params);
                    }
                }
            });

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                willDestroyElement: function() {
                    this.get('controller').removeObserver('object.validErrors.@each');
                }
            });

            UMI.FieldFormControlView = Ember.View.extend(UMI.FieldMixin, {
                layout: function() {
                    var type = this.get('meta.type');
                    var layout = '<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span>' +
                        '</div>{{yield}}';

                    switch (type) {
                        case 'checkbox':
                            layout = '<div class="umi-form-element-without-label">{{yield}}{{view.isRequired}}</div>';
                    }

                    return Ember.Handlebars.compile(layout);
                }.property(),

                isRequired: function() {
                    var object = this.get('object');
                    var dataSource = this.get('meta.dataSource');
                    var validators;
                    if (object) {
                        validators = this.get('object').validatorsForProperty(dataSource);
                        if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                            return ' *';
                        }
                    }
                }.property('object'),

                isError: function() {
                    var dataSource = this.get('meta.dataSource');
                    var isValid = !!this.get('object.validErrors.' + dataSource);

                    return isValid;
                }.property('object.validErrors.@each'),

                wysiwygTemplate: function() {
                    return '{{view "htmlEditorCollection" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function() {
                    return '{{view "selectCollection" object=view.object meta=view.meta}}';
                }.property(),

                checkboxTemplate: function() {
                    return '{{view "checkboxCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function() {
                    return '{{view "checkboxGroupCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                permissionsTemplate: function() {
                    return '{{view "permissions" object=view.object meta=view.meta}}';
                }.property(),

                objectRelationTemplate: function() {
                    return '{{view "objectRelationElement" object=view.object meta=view.meta}}';
                }.property(),

                pageRelationTemplate: function() {
                    return this.get('objectRelationTemplate');
                }.property()
            });
        };
    });

define('forms/main',[
    'App', './elements/main', './formBase/main', './formControl/main'
], function(UMI, elements, formBase, formControl) {
        

        elements();
        formBase();
        formControl();
    });
define('forms', ['forms/main'], function (main) { return main; });

define('notification/main',['App'], function(UMI) {
    

    UMI.Notification = Ember.Object.extend({
        settings: {
            'type': 'secondary',
            'title': 'UMI.CMS',
            'content': '',
            'close': true,
            'duration': 3000,
            'kind': 'default'
        },
        create: function(params) {
            var defaultSettings = this.get('settings');
            var settings = {};
            var param;
            for (param in defaultSettings) {
                if (defaultSettings.hasOwnProperty(param)) {
                    settings[param] = defaultSettings[param];
                }
            }

            if (params) {
                for (param in params) {
                    if (params.hasOwnProperty(param)) {
                        settings[param] = params[param];
                    }
                }
            }

            settings.id = UMI.notificationList.incrementProperty('notificationId');
            var data = UMI.notificationList.get('content');

            Ember.run.next(this, function() {
                data.pushObject(Ember.Object.create(settings));
            });
        },
        removeAll: function() {
            UMI.notificationList.set('content', []);
        },
        removeWithKind: function(kind) {
            var content = UMI.notificationList.get('content');
            content = content.filter(function(item) {
                if (Ember.get(item, 'kind') !== kind) {
                    return true;
                }
            });
            UMI.notificationList.set('content', content);
        }
    });

    UMI.notification = UMI.Notification.create({});

    UMI.NotificationList = Ember.ArrayController.extend({
        content: [],
        sortContent: function() {
            return this.get('content').sortBy('id');
        }.property('content.length'),
        notificationId: 0,
        closeAll: false,
        itemCount: function() {
            var content = this.get('content');
            if (content.get('length') > 1 && !this.get('closeAll')) {
                this.set('closeAll', true);
                content.pushObject(Ember.Object.create({
                    id: 'closeAll',
                    type: 'secondary',
                    kind: 'closeAll',
                    content: UMI.i18n.getTranslate('Close') + ' ' + (UMI.i18n.getTranslate('All') || '').toLowerCase()
                }));
            }
            if (content.get('length') <= 2 && this.get('closeAll')) {
                var object = content.findBy('id', 'closeAll');
                content.removeObject(object);
                this.set('closeAll', false);
            }
        }.observes('content.length')
    });

    UMI.notificationList = UMI.NotificationList.create({});

    UMI.AlertBox = Ember.View.extend({
        classNames: ['alert-box'],
        classNameBindings: ['content.type'],
        layoutName: 'partials/alert-box',
        didInsertElement: function() {
            var duration = this.get('content.duration');
            if (duration) {
                Ember.run.later(this, function() {
                    //this.$().slideDown();
                    var id = this.get('content.id');
                    var content = this.get('controller.content') || [];
                    var object = content.findBy('id', id);
                    content.removeObject(object);
                }, duration);
            }
        },
        actions: {
            close: function() {
                var content = this.get('controller.content');
                content.removeObject(this.get('content'));
            }
        }
    });

    UMI.AlertBoxCloseAll = Ember.View.extend({
        classNames: ['alert-box text-center alert-box-close-all'],
        classNameBindings: ['content.type'],
        layoutName: 'partials/alert-box/close-all',
        click: function() {
            UMI.notification.removeAll();
        }
    });

    UMI.NotificationListView = Ember.CollectionView.extend({
        tagName: 'div',
        classNames: ['umi-alert-wrapper'],
        createChildView: function(viewClass, attrs) {
            if (attrs.content.kind === 'closeAll') {
                viewClass = UMI.AlertBoxCloseAll;
            } else {
                viewClass = UMI.AlertBox;
            }
            return this._super(viewClass, attrs);
        },
        contentBinding: 'controller.sortContent',
        controller: UMI.notificationList
    });
});
define('notification', ['notification/main'], function (main) { return main; });

define('dialog/main',['App'], function(UMI) {
    

    UMI.DialogController = Ember.ObjectController.extend({
        deferred: null,
        open: function(params) {
            this.set('deferred', Ember.RSVP.defer());
            var deferred = this.get('deferred');
            if (Ember.get(params, 'proposeRemember')) {
                //проверить присутсвие запомненного действия
            }
            this.set('model', Ember.Object.create(params));
            return deferred.promise;
        },
        actions: {
            confirm: function() {
                this.set('model', null);
                var deferred = this.get('deferred');
                deferred.resolve('loaded');
            },
            close: function() {
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
        hasButtons: Ember.computed.any('model.confirm', 'model.reject'),
        showDialog: function() {
            if (this.get('model')) {
                $('body').append('<div class="umi-blur umi-ff-fix" />');
                setTimeout(function() {
                    $('.umi-main-view').addClass('umi-blur');
                }, 50);
                this.append();
            } else if (this.isVisible) {
                $('.umi-blur.umi-ff-fix').remove();
                $('.umi-main-view').removeClass('umi-blur');
                this.remove();
            }
        }.observes('model'),
        didInsertElement: function() {
            var element = this.$();
            var dialog = element.children('.umi-dialog');
            var screenSize = $(document).height();
            var dialogMarginTop = screenSize > dialog[0].offsetHeight ? -dialog[0].offsetHeight / 2 :
                -dialog[0].offsetHeight / 2 + dialog[0].offsetHeight - screenSize;
            dialog.css({'marginTop': dialogMarginTop});
            dialog.addClass('visible');
        },
        actions: {
            confirm: function() {
                var element = this.$();
                var dialog = element.children('.umi-dialog');
                dialog.removeClass('visible');
                return this.get('controller').send('confirm');
            },
            close: function() {
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

define('popup/controller',['App'], function(UMI) {
    

    return function() {

        UMI.PopupController = Ember.Controller.extend({
            templateParams: null,
            /**
             * @abstract
             * @property
             */
            popupType: null
        });
    };
});
define('popup/view',['App'], function(UMI) {
    

    return function() {

        UMI.PopupView = Ember.View.extend({
            controller: function() {
                return this.container.lookup('controller:popup');
            }.property(),

            classNames: ['umi-popup'],

            popupType: null,

            title: '',

            width: 800,

            height: 400,

            contentOverflow: ['overflow', 'hidden'],

            blur: true,

            fade: false,

            drag: true,

            resize: true,

            layoutName: 'partials/popup',

            templateName: function() {
                return 'partials/popup/' + this.get('popupType');
            }.property('popupType'),

            didInsertElement: function() {
                var self = this;
                if (self.get('blur')) {
                    self.addBlur();
                }

                if (self.get('fade')) {
                    self.fadeIn();
                }

                if (this.get('drag')) {
                    this.allowDrag();
                }

                if (this.get('resize')) {
                    this.allowResize();
                }

                if (this.get('contentOverflow') !== 'hidden' && Ember.typeOf(this.get('contentOverflow')) === 'array') {// TODO: WTF?
                    $('.umi-popup-content').css(this.get('contentOverflow')[0], this.get('contentOverflow')[1]);
                }

                this.setSize();
                this.setPosition();
            },

            /**
             * @hook
             */
            beforeClose: function() {
            },

            /**
             * @hook
             */
            afterClose: function() {
            },

            actions: {
                closePopup: function() {
                    this.beforeClose();
                    this.removeBlur();
                    this.get('controller').send('removePopupLayout');
                    this.afterClose();
                }
            },

            setSize: function() {
                var $el = this.$();
                $el.width(this.get('width'));
                $el.height(this.get('height'));
            },

            setPosition: function() {
                var $el = this.$();
                var styles = {};
                var elHeight = $el.height() / 2;
                var elWidth = $el.width() / 2;
                styles.marginTop = -( $(window).height() > elHeight ? elHeight : $(window).height() / 2 - 50);
                styles.marginLeft = -( $(window).width() > elWidth ? elWidth : $(window).width() / 2 - 50);
                $el.css(styles);
            },

            fadeIn: function() {
                var self = this;
                $('body').append('<div class="umi-popup-visible-overlay"></div>');
                $('body').on('click.umi.popup', '.umi-popup-visible-overlay', function() {
                    self.send('closePopup');
                    $('body').off('click.umi.popup');
                });
            },

            addBlur: function() {
                $('.umi-header').addClass('s-blur');
                $('.umi-content').addClass('s-blur');
                $('body').append('<div class="umi-popup-invisible-overlay"></div>');
            },

            removeBlur: function() {
                $('.umi-header').removeClass('s-blur');
                $('.umi-content').removeClass('s-blur');
                $('.umi-popup-invisible-overlay').remove();
                $('.umi-popup-visible-overlay').remove();
            },

            allowResize: function() {
                var that = this;
                $('.umi-popup-resizer').show();
                $('body').on('mousedown', '.umi-popup-resizer', function(event) {
                    if (event.button === 0) {
                        //$('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var posX = $('.umi-popup').offset().left;
                        var posY = $('.umi-popup').offset().top;

                        $('html').addClass('s-unselectable');
                        $('html').mousemove(function(event) {
                            var w = event.pageX - posX;
                            var h = event.pageY - posY;

                            if (w < that.get('width')) {
                                w = that.get('width')
                            }
                            if (h < that.get('height')) {
                                h = that.get('height')
                            }

                            $('.umi-popup').css({width: w, height: h});

                            $('html').on('mouseup', function() {
                                $('html').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                //$('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            allowDrag: function() {
                var that = this;
                $('.umi-popup-header').css({'cursor': 'move'});
                $('body').on('mousedown', '.umi-popup-header', function(event) {
                    $('.umi-popup').css('z-index', '10000');
                    $(this).parent().css('z-index', '10001');

                    var $that = $(this);
                    if (event.button === 0) {
                        //$('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var clickX = event.pageX - $(this).offset().left;
                        var clickY = event.pageY - $(this).offset().top;

                        var windowHeight = $(window).height() - 34;
                        var windowWidth = $(window).width() - 34;


                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event) {
                            var x = event.pageX - clickX;
                            var y = event.pageY - clickY;

                            //Запрет на вывод Popup за пределы экрана
                            if (y <= 0) {
                                return
                            }
                            if (y >= windowHeight) {
                                return
                            }
                            if (x <= 68 - that.width) {
                                return
                            } // 68 - чтобы не только крестик оставался, но и было за что без опаски схватить
                            if (x >= windowWidth) {
                                return
                            }

                            $that.parent().offset({left: x, top: y});

                            $('body').on('mouseup', function() {
                                $('body').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                //$('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            init: function() {
                this._super();
                var viewParams = this.get('controller.viewParams');
                if (Ember.typeOf(viewParams) === 'object') {
                    this.setProperties(viewParams);
                }
            }
        });
    };
});
define('popup/main',[
    './controller', './view', 'App'
], function(controller, view) {
        

        controller();
        view();

    });
define('popup', ['popup/main'], function (main) { return main; });

define('table/view',['App'], function(UMI) {
    

    return function() {
        UMI.TableView = Ember.View.extend(UMI.i18nInterface, {
            dictionaryNamespace: 'table',
            localDictionary: function() {
                var table = this.get('content.control') || {};
                return table.i18n;
            }.property('content'),
            templateName: 'partials/table',
            classNames: ['umi-table'],
            headers: [],
            rows: [],
            offset: 0,
            limit: 25,
            totalBinding: 'rows.length',
            error: null,
            visibleRows: function() {
                var self = this;
                var controller = self.get('controller');
                var rows = self.get('rows');
                var offset = self.get('offset');
                var limit = parseFloat(self.get('limit'));
                var begin;
                var end;
                if (offset) {
                    begin = limit * offset;
                } else {
                    begin = 0;
                }
                end = begin + limit;
                Ember.run.later(self, function() {
                    controller.send('hideLoader');
                }, 300);
                return rows.slice(begin, end);
            }.property('offset', 'limit'),

            iScroll: null,

            iScrollUpdate: function() {
                var iScroll = this.get('iScroll');
                if (iScroll) {
                    setTimeout(function() {
                        iScroll.refresh();
                        iScroll.scrollTo(0, 0);
                    }, 100);
                }
            }.observes('visibleRows'),

            didInsertElement: function() {
                if (!this.get('error')) {
                    var $table = this.$();
                    var tableContent = $table.find('.s-scroll-wrap')[0];
                    var tableHeader = $table.find('.umi-table-header')[0];
                    var scrollContent = new IScroll(tableContent, UMI.config.iScroll);
                    var tableContentRowSize = $(tableContent).find('.umi-table-content-sizer')[0];
                    this.set('iScroll', scrollContent);
                    scrollContent.on('scroll', function() {
                        tableHeader.style.marginLeft = this.x + 'px';
                    });

                    $(window).on('resize.umi.table', function() {
                        setTimeout(function() {
                            tableHeader.style.marginLeft = scrollContent.x + 'px';
                        }, 100);
                    });

                    // Событие изменения ширины колонки
                    $table.on('mousedown.umi.table', '.umi-table-header-column-resizer', function() {
                        $('html').addClass('s-unselectable');
                        var handler = this;
                        $(handler).addClass('on-resize');
                        var columnEl = handler.parentNode.parentNode;
                        var columnIndex = $(columnEl).closest('tr').children('.umi-table-td').index(columnEl);
                        var columnOffset = $(columnEl).offset().left;
                        var columnWidth;
                        var contentCell = tableContentRowSize.querySelectorAll('.umi-table-td')[columnIndex];
                        $('body').on('mousemove.umi.table', function(event) {
                            event.stopPropagation();
                            columnWidth = event.pageX - columnOffset;
                            if (columnWidth >= 60 && columnEl.offsetWidth > 59) {
                                columnEl.style.width = contentCell.style.width = columnWidth + 'px';
                            }
                        });

                        $('body').on('mouseup.umi.table', function() {
                            $('html').removeClass('s-unselectable');
                            $(handler).removeClass('on-resize');
                            $('body').off('mousemove.umi.table');
                            $('body').off('mouseup.umi.table');
                            scrollContent.refresh();
                            tableHeader.style.marginLeft = scrollContent.x + 'px';
                        });
                    });
                }
            },

            willDestroyElement: function() {
                var $table = this.$();
                $(window).off('resize.umi.table');
                this.removeObserver('content');
                this.removeObserver('visibleRows');
                $table.off('mousedown.umi.table');
            },

            paginationView: Ember.View.extend({
                classNames: ['s-unselectable', 'umi-toolbar'],
                templateName: 'partials/table/toolbar',
                counter: function() {
                    var label = (UMI.i18n.getTranslate('Of') || '').toLowerCase();
                    var limit = this.get('parentView.limit');
                    var offset = this.get('parentView.offset') + 1;
                    var total = this.get('parentView.total');
                    var maxCount = offset * limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('parentView.limit', 'parentView.offset', 'parentView.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        return this.get('parentView.parentView.offset');
                    }.property('parentView.parentView.offset'),

                    click: function() {
                        var self = this;
                        var controller = self.get('controller');
                        if (self.get('isActive')) {
                            controller.send('showLoader');
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').decrementProperty('offset');
                            });
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        var limit = this.get('parentView.parentView.limit');
                        var offset = this.get('parentView.parentView.offset') + 1;
                        var total = this.get('parentView.parentView.total');
                        return total > limit * offset;
                    }.property('parentView.parentView.limit', 'parentView.parentView.offset', 'parentView.parentView.total'),

                    click: function() {
                        var self = this;
                        var controller = self.get('controller');
                        if (self.get('isActive')) {
                            controller.send('showLoader');
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').incrementProperty('offset');
                            });
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function() {
                        return this.get('parentView.parentView.limit');
                    }.property('parentView.parentView.limit'),

                    type: 'text',

                    keyDown: function(event) {
                        var self = this;
                        var controller = self.get('controller');
                        if (event.keyCode === 13) {
                            controller.send('showLoader');
                            // При изменении количества строк на странице сбрасывается offset
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').setProperties({'offset': 0, 'limit': self.$()[0].value});
                            });
                        }
                    }
                })
            })
        });

        UMI.SiteAnalyzeTableView = UMI.TableView.extend({
            setContent: function() {
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data.data') || [];
                var index;
                if (data.length) {
                    headers = data.shift();
                    index = headers.indexOf('n/a');
                    headers.splice(index, 1);
                    for (var i = 0; i < data.length; i++) {
                        data[i].splice(index, 1);
                    }
                    this.setProperties({'headers': headers, 'rows': data});
                }
            }.observes('content').on('init')
        });

        UMI.BacklinksTableView = UMI.TableView.extend({
            setContent: function() {
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data');
                var rows = [];
                if (Ember.typeOf(data) === 'object' && 'error' in data) {
                    this.set('error', data.error);
                } else if (Ember.typeOf(data) === 'array') {
                    headers = [Ember.get(content, 'control.labels.vs_from')];
                    for (var i = 0; i < data.length; i++) {
                        rows.push([Ember.get(data[i], 'vs_from')]);
                    }
                    this.setProperties({'headers': headers, 'rows': rows});
                }
            }.observes('content').on('init')
        });

        UMI.YaHostTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var key;

                if (Ember.typeOf(labels) === 'object') {
                    for (key in labels) {
                        if (labels.hasOwnProperty(key)) {
                            headers.push(labels[key]);
                        }
                    }
                }
                if (Ember.typeOf(data) === 'object') {
                    for (key in data) {
                        if (data.hasOwnProperty(key)) {
                            var row = UMI.Utils.getStringValue(data[key]);
                            rows.push(row);
                        }
                    }
                }
                this.setProperties({'headers': headers, 'rows': [rows]});
            }.observes('content').on('init')
        });

        UMI.YaIndexesTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var i;
                var url = Ember.get(data, 'last-week-index-urls.url');

                headers.push(Ember.get(labels, 'last-week-index-urls'));

                if (Ember.typeOf(url) === 'array') {
                    for (i = 0; i < url.length; i++) {
                        rows.push([UMI.Utils.getStringValue(url[i])]);
                    }
                }
                this.setProperties({'headers': headers, 'rows': rows});
            }.observes('content').on('init')
        });

        UMI.YaTopsTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var i;
                var row;
                var topQueries = Ember.get(data, 'top-queries.top-clicks.top-info');

                headers.push(Ember.get(labels, 'query'));
                headers.push(Ember.get(labels, 'count'));
                headers.push(Ember.get(labels, 'position'));
                headers.push(Ember.get(labels, 'clicks-top-rank'));

                if (Ember.typeOf(topQueries) === 'array') {
                    for (i = 0; i < topQueries.length; i++) {
                        row = [];
                        row.push(UMI.Utils.getStringValue(topQueries[i].query));
                        row.push(UMI.Utils.getStringValue(topQueries[i].count));
                        row.push(UMI.Utils.getStringValue(topQueries[i].position));
                        row.push(UMI.Utils.getStringValue(topQueries[i]['clicks-top-rank']));
                        rows.push(row);
                    }
                }
                this.setProperties({'headers': headers, 'rows': rows});
            }.observes('content').on('init')
        });

        UMI.TableCountersView = UMI.TableView.extend({
            rowCount: function() {
                var rows = this.get('rows') || [];
                var row = rows[0] || {};
                var count = [];
                for (var key in row) {
                    if (row.hasOwnProperty(key)) {
                        count.push({});
                    }
                }
                return count;
            }.property('rows'),

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-content-tr'],
                cell: function() {
                    var object = this.get('row');
                    var cell = [];
                    for (var key in object) {
                        if (object.hasOwnProperty(key)) {
                            cell.push({'displayName': object[key]});
                        }
                    }
                }
            }),

            setContent: function() {
                var content = this.get('content');
                var headers = Ember.get(content, 'control.meta.labels');
                var headersList = [];
                var rows = Ember.get(content, 'control.meta.objects');
                for (var key in headers) {
                    if (headers.hasOwnProperty(key)) {
                        headersList.push(headers[key]);
                    }
                }
                this.setProperties({'headers': headersList, 'rows': rows});
            }.observes('content').on('init'),

            actions: {
                rowEvent: function(context) {
                    this.get('controller').transitionToRoute('context', Ember.get(context, 'id'));
                }
            }
        });
    };
});
define('table/main',[
    './view', 'App'
], function(view) {
    

    view();
});
define('table', ['table/main'], function (main) { return main; });

define('sideMenu/main',['App'], function(UMI) {
        

        UMI.SideMenuController = Ember.ObjectController.extend({
            needs: ['component'],
            objects: function() {
                return this.get('controllers.component.dataSource.objects');
            }.property('model')
        });


        UMI.SideMenuView = Ember.View.extend({
            layoutName: 'partials/sideMenu'
        });
    });
define('sideMenu', ['sideMenu/main'], function (main) { return main; });

define('updateLayout/main',[
    'App'
], function(UMI) {
    

    UMI.UpdateLayoutView = Ember.View.extend(UMI.i18nInterface, {
        data: null,

        dictionaryNamespace: 'updateLayout',

        localDictionary: function() {
            return this.get('data.control.i18n');
        }.property(),

        classNames: ['row', 's-full-height'],

        templateName: 'partials/updateLayout',

        buttonLabel: function() {
            var elements = this.get('data.control.submitToolbar');
            if (Ember.typeOf(elements) === 'array') {
                return Ember.get(elements[0], 'attributes.label');
            }
        }.property('data.control.submitToolbar'),

        actions: {
            update: function() {
                var self = this;
                var button = self.$().find('.button');
                var updateSource;

                try {
                    var componentController = self.get('container').lookup('controller:component');
                    if (componentController) {
                        updateSource = componentController.get('settings.actions.update.source');

                        $.ajax({
                            type: "POST",
                            url: updateSource,
                            global: false,
                            beforeSend: function() {
                                button.addClass('loading');
                            },
                            success: function(results) {
                                button.removeClass('loading');
                                updateSource = Ember.get(results, 'result.update');
                                if (updateSource) {
                                    window.location.href = updateSource;
                                } else {
                                    throw new Error('Update url not found.');
                                }
                            },
                            error: function(error) {
                                button.removeClass('loading');
                                self.get('controller').send('backgroundError', error);
                            }
                        });
                    } else {
                        throw new Error('Component controller not found.');
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            }
        }
    });
});
define('updateLayout', ['updateLayout/main'], function (main) { return main; });

define('application/main',[
    'App', 'topBar', 'divider', 'dock', 'toolbar', 'tableControl', 'fileManager', 'treeSimple', 'tree', 'forms',
    'notification', 'dialog', 'popup', 'DS', 'table', 'sideMenu', 'updateLayout', 'Foundation'
], function(UMI) {
        
        return function() {
            UMI.advanceReadiness();
        };
    });
require.config({
    paths: {
        text: 'vendor/requirejs-text/text',

        App: 'application/application',
        jquery: 'vendor/jquery/dist/jquery',
        jqueryUI: 'vendor/jquery-ui/jquery-ui',
        Modernizr: 'vendor/modernizr/modernizr',
        Handlebars: 'vendor/handlebars/handlebars',
        Ember: 'vendor/ember/ember',
        DS: 'vendor/ember-data/ember-data',
        timepicker: 'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon',
        moment: 'vendor/momentjs/min/moment-with-langs',
        FastClick: 'vendor/fastclick/lib/fastclick',
        iscroll: 'library/iScroll/iscroll-probe-5.1.1',
        ckEditor: 'library/ckeditor/ckeditor',
        elFinder: 'library/elFinder/elFinder',
        Foundation: 'library/foundation/foundation'
    },

    shim: {
        Modernizr: {exports: 'Modernizr'},
        jquery: {exports: 'jQuery'},
        jqueryUI: {exports: 'jQuery', deps: ['jquery']},
        elFinder: {exports: 'elFinder', deps: ['jquery', 'jqueryUI']},
        Ember: {exports: 'Ember', deps: ['Handlebars', 'jquery']},
        DS: {exports: 'DS', deps: ['Ember']},
        ckEditor: {exports: 'ckEditor'},
        timepicker: {exports: 'timepicker', deps: ['jquery', 'jqueryUI']},
        Foundation: {exports: 'Foundation', deps: ['jquery', 'FastClick']}
    },

    packages: [
        {name: 'accordion', location: 'partials/accordion'},
        {name: 'dialog', location: 'partials/dialog'},
        {name: 'divider', location: 'partials/divider'},
        {name: 'dock', location: 'partials/dock'},
        {name: 'fileManager', location: 'partials/fileManager'},
        {name: 'forms', location: 'partials/forms'},
        {name: 'notification', location: 'partials/notification'},
        {name: 'popup', location: 'partials/popup'},
        {name: 'search', location: 'partials/search'},
        {name: 'megaIndex', location: 'partials/seo/megaIndex'},
        {name: 'sideMenu', location: 'partials/sideMenu'},
        {name: 'yandexWebmaster', location: 'partials/seo/yandexWebmaster'},
        {name: 'table', location: 'partials/table'},
        {name: 'tableControl', location: 'partials/tableControl'},
        {name: 'toolbar', location: 'partials/toolbar'},
        {name: 'topBar', location: 'partials/topBar'},
        {name: 'tree', location: 'partials/tree'},
        {name: 'treeSimple', location: 'partials/treeSimple'},
        {name: 'updateLayout', location: 'partials/updateLayout'}
    ]
});

require(['jquery'], function() {
    

    var deffer = $.get(window.UmiSettings.authUrl);

    deffer.done(function(data) {
        var objectMerge = function(objectBase, objectProperty) {
            for (var key in objectProperty) {
                if (objectProperty.hasOwnProperty(key)) {
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        if (data.result) {
            objectMerge(window.UmiSettings, data.result.auth);
        }
        require(['application/main'], function(application) {
            application();
        });
    });

    deffer.fail(function(error) {
        require(['auth/main'], function(auth) {
            auth({accessError: error});
        });
    });
});

define("main", function(){});

