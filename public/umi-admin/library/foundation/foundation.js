/*
 * Foundation Responsive Library
 * http://foundation.zurb.com
 * Copyright 2014, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

(function ($, window, document, undefined) {
  'use strict';

  var header_helpers = function (class_array) {
    var i = class_array.length;
    var head = $('head');

    while (i--) {
      if(head.has('.' + class_array[i]).length === 0) {
        head.append('<meta class="' + class_array[i] + '" />');
      }
    }
  };

  header_helpers([
    'foundation-mq-small',
    'foundation-mq-medium',
    'foundation-mq-large',
    'foundation-mq-xlarge',
    'foundation-mq-xxlarge',
    'foundation-data-attribute-namespace']);

  // Enable FastClick if present

  $(function() {
    if (typeof FastClick !== 'undefined') {
      // Don't attach to body if undefined
      if (typeof document.body !== 'undefined') {
        FastClick.attach(document.body);
      }
    }
  });

  // private Fast Selector wrapper,
  // returns jQuery object. Only use where
  // getElementById is not available.
  var S = function (selector, context) {
    if (typeof selector === 'string') {
      if (context) {
        var cont;
        if (context.jquery) {
          cont = context[0];
          if (!cont) return context;
        } else {
          cont = context;
        }
        return $(cont.querySelectorAll(selector));
      }

      return $(document.querySelectorAll(selector));
    }

    return $(selector, context);
  };

  // Namespace functions.

  var attr_name = function (init) {
    var arr = [];
    if (!init) arr.push('data');
    if (this.namespace.length > 0) arr.push(this.namespace);
    arr.push(this.name);

    return arr.join('-');
  };

  var add_namespace = function (str) {
    var parts = str.split('-'),
        i = parts.length,
        arr = [];

    while (i--) {
      if (i !== 0) {
        arr.push(parts[i]);
      } else {
        if (this.namespace.length > 0) {
          arr.push(this.namespace, parts[i]);
        } else {
          arr.push(parts[i]);
        }
      }
    }

    return arr.reverse().join('-');
  };

  // Event binding and data-options updating.

  var bindings = function (method, options) {
    var self = this,
        should_bind_events = !S(this).data(this.attr_name(true));


    if (S(this.scope).is('[' + this.attr_name() +']')) {
      S(this.scope).data(this.attr_name(true) + '-init', $.extend({}, this.settings, (options || method), this.data_options(S(this.scope))));

      if (should_bind_events) {
        this.events(this.scope);
      }

    } else {
      S('[' + this.attr_name() +']', this.scope).each(function () {
        var should_bind_events = !S(this).data(self.attr_name(true) + '-init');
        S(this).data(self.attr_name(true) + '-init', $.extend({}, self.settings, (options || method), self.data_options(S(this))));

        if (should_bind_events) {
          self.events(this);
        }
      });
    }
    // # Patch to fix #5043 to move this *after* the if/else clause in order for Backbone and similar frameworks to have improved control over event binding and data-options updating. 
    if (typeof method === 'string') {
      return this[method].call(this, options);
    }

  };

  var single_image_loaded = function (image, callback) {
    function loaded () {
      callback(image[0]);
    }

    function bindLoad () {
      this.one('load', loaded);

      if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
        var src = this.attr( 'src' ),
            param = src.match( /\?/ ) ? '&' : '?';

        param += 'random=' + (new Date()).getTime();
        this.attr('src', src + param);
      }
    }

    if (!image.attr('src')) {
      loaded();
      return;
    }

    if (image[0].complete || image[0].readyState === 4) {
      loaded();
    } else {
      bindLoad.call(image);
    }
  };

  /*
    https://github.com/paulirish/matchMedia.js
  */

  window.matchMedia = window.matchMedia || (function( doc ) {

    "use strict";

    var bool,
        docElem = doc.documentElement,
        refNode = docElem.firstElementChild || docElem.firstChild,
        // fakeBody required for <FF4 when executed in <head>
        fakeBody = doc.createElement( "body" ),
        div = doc.createElement( "div" );

    div.id = "mq-test-1";
    div.style.cssText = "position:absolute;top:-100em";
    fakeBody.style.background = "none";
    fakeBody.appendChild(div);

    return function (q) {

      div.innerHTML = "&shy;<style media=\"" + q + "\"> #mq-test-1 { width: 42px; }</style>";

      docElem.insertBefore( fakeBody, refNode );
      bool = div.offsetWidth === 42;
      docElem.removeChild( fakeBody );

      return {
        matches: bool,
        media: q
      };

    };

  }( document ));

  /*
   * jquery.requestAnimationFrame
   * https://github.com/gnarf37/jquery-requestAnimationFrame
   * Requires jQuery 1.8+
   *
   * Copyright (c) 2012 Corey Frang
   * Licensed under the MIT license.
   */

  (function($) {

  // requestAnimationFrame polyfill adapted from Erik Möller
  // fixes from Paul Irish and Tino Zijdel
  // http://paulirish.com/2011/requestanimationframe-for-smart-animating/
  // http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating

  var animating,
      lastTime = 0,
      vendors = ['webkit', 'moz'],
      requestAnimationFrame = window.requestAnimationFrame,
      cancelAnimationFrame = window.cancelAnimationFrame,
      jqueryFxAvailable = 'undefined' !== typeof jQuery.fx;

  for (; lastTime < vendors.length && !requestAnimationFrame; lastTime++) {
    requestAnimationFrame = window[ vendors[lastTime] + "RequestAnimationFrame" ];
    cancelAnimationFrame = cancelAnimationFrame ||
      window[ vendors[lastTime] + "CancelAnimationFrame" ] ||
      window[ vendors[lastTime] + "CancelRequestAnimationFrame" ];
  }

  function raf() {
    if (animating) {
      requestAnimationFrame(raf);

      if (jqueryFxAvailable) {
        jQuery.fx.tick();
      }
    }
  }

  if (requestAnimationFrame) {
    // use rAF
    window.requestAnimationFrame = requestAnimationFrame;
    window.cancelAnimationFrame = cancelAnimationFrame;

    if (jqueryFxAvailable) {
      jQuery.fx.timer = function (timer) {
        if (timer() && jQuery.timers.push(timer) && !animating) {
          animating = true;
          raf();
        }
      };

      jQuery.fx.stop = function () {
        animating = false;
      };
    }
  } else {
    // polyfill
    window.requestAnimationFrame = function (callback) {
      var currTime = new Date().getTime(),
        timeToCall = Math.max(0, 16 - (currTime - lastTime)),
        id = window.setTimeout(function () {
          callback(currTime + timeToCall);
        }, timeToCall);
      lastTime = currTime + timeToCall;
      return id;
    };

    window.cancelAnimationFrame = function (id) {
      clearTimeout(id);
    };

  }

  }( jQuery ));


  function removeQuotes (string) {
    if (typeof string === 'string' || string instanceof String) {
      string = string.replace(/^['\\/"]+|(;\s?})+|['\\/"]+$/g, '');
    }

    return string;
  }

  window.Foundation = {
    name : 'Foundation',

    version : '5.3.0',

    media_queries : {
      small : S('.foundation-mq-small').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
      medium : S('.foundation-mq-medium').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
      large : S('.foundation-mq-large').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
      xlarge: S('.foundation-mq-xlarge').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
      xxlarge: S('.foundation-mq-xxlarge').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, '')
    },

    stylesheet : $('<style></style>').appendTo('head')[0].sheet,

    global: {
      namespace: undefined
    },

    init : function (scope, libraries, method, options, response) {
      var args = [scope, method, options, response],
          responses = [];

      // check RTL
      this.rtl = /rtl/i.test(S('html').attr('dir'));

      // set foundation global scope
      this.scope = scope || this.scope;

      this.set_namespace();

      if (libraries && typeof libraries === 'string' && !/reflow/i.test(libraries)) {
        if (this.libs.hasOwnProperty(libraries)) {
          responses.push(this.init_lib(libraries, args));
        }
      } else {
        for (var lib in this.libs) {
          responses.push(this.init_lib(lib, libraries));
        }
      }

      return scope;
    },

    init_lib : function (lib, args) {
      if (this.libs.hasOwnProperty(lib)) {
        this.patch(this.libs[lib]);

        if (args && args.hasOwnProperty(lib)) {
            if (typeof this.libs[lib].settings !== 'undefined') {
                $.extend(true, this.libs[lib].settings, args[lib]);
            }
            else if (typeof this.libs[lib].defaults !== 'undefined') {
                $.extend(true, this.libs[lib].defaults, args[lib]);
            }
          return this.libs[lib].init.apply(this.libs[lib], [this.scope, args[lib]]);
        }

        args = args instanceof Array ? args : new Array(args);    // PATCH: added this line
        return this.libs[lib].init.apply(this.libs[lib], args);
      }

      return function () {};
    },

    patch : function (lib) {
      lib.scope = this.scope;
      lib.namespace = this.global.namespace;
      lib.rtl = this.rtl;
      lib['data_options'] = this.utils.data_options;
      lib['attr_name'] = attr_name;
      lib['add_namespace'] = add_namespace;
      lib['bindings'] = bindings;
      lib['S'] = this.utils.S;
    },

    inherit : function (scope, methods) {
      var methods_arr = methods.split(' '),
          i = methods_arr.length;

      while (i--) {
        if (this.utils.hasOwnProperty(methods_arr[i])) {
          scope[methods_arr[i]] = this.utils[methods_arr[i]];
        }
      }
    },

    set_namespace: function () {

      // Description:
      //    Don't bother reading the namespace out of the meta tag
      //    if the namespace has been set globally in javascript
      //
      // Example:
      //    Foundation.global.namespace = 'my-namespace';
      // or make it an empty string:
      //    Foundation.global.namespace = '';
      //
      //

      // If the namespace has not been set (is undefined), try to read it out of the meta element.
      // Otherwise use the globally defined namespace, even if it's empty ('')
      var namespace = ( this.global.namespace === undefined ) ? $('.foundation-data-attribute-namespace').css('font-family') : this.global.namespace;

      // Finally, if the namsepace is either undefined or false, set it to an empty string.
      // Otherwise use the namespace value.
      this.global.namespace = ( namespace === undefined || /false/i.test(namespace) ) ? '' : namespace;
    },

    libs : {},

    // methods that can be inherited in libraries
    utils : {

      // Description:
      //    Fast Selector wrapper returns jQuery object. Only use where getElementById
      //    is not available.
      //
      // Arguments:
      //    Selector (String): CSS selector describing the element(s) to be
      //    returned as a jQuery object.
      //
      //    Scope (String): CSS selector describing the area to be searched. Default
      //    is document.
      //
      // Returns:
      //    Element (jQuery Object): jQuery object containing elements matching the
      //    selector within the scope.
      S : S,

      // Description:
      //    Executes a function a max of once every n milliseconds
      //
      // Arguments:
      //    Func (Function): Function to be throttled.
      //
      //    Delay (Integer): Function execution threshold in milliseconds.
      //
      // Returns:
      //    Lazy_function (Function): Function with throttling applied.
      throttle : function (func, delay) {
        var timer = null;

        return function () {
          var context = this, args = arguments;

          if (timer == null) {
            timer = setTimeout(function () {
              func.apply(context, args);
              timer = null;
            }, delay);
          }
        };
      },

      // Description:
      //    Executes a function when it stops being invoked for n seconds
      //    Modified version of _.debounce() http://underscorejs.org
      //
      // Arguments:
      //    Func (Function): Function to be debounced.
      //
      //    Delay (Integer): Function execution threshold in milliseconds.
      //
      //    Immediate (Bool): Whether the function should be called at the beginning
      //    of the delay instead of the end. Default is false.
      //
      // Returns:
      //    Lazy_function (Function): Function with debouncing applied.
      debounce : function (func, delay, immediate) {
        var timeout, result;
        return function () {
          var context = this, args = arguments;
          var later = function () {
            timeout = null;
            if (!immediate) result = func.apply(context, args);
          };
          var callNow = immediate && !timeout;
          clearTimeout(timeout);
          timeout = setTimeout(later, delay);
          if (callNow) result = func.apply(context, args);
          return result;
        };
      },

      // Description:
      //    Parses data-options attribute
      //
      // Arguments:
      //    El (jQuery Object): Element to be parsed.
      //
      // Returns:
      //    Options (Javascript Object): Contents of the element's data-options
      //    attribute.
      data_options : function (el, data_attr_name) {
        data_attr_name = data_attr_name || 'options';
        var opts = {}, ii, p, opts_arr,
            data_options = function (el) {
              var namespace = Foundation.global.namespace;

              if (namespace.length > 0) {
                return el.data(namespace + '-' + data_attr_name);
              }

              return el.data(data_attr_name);
            };

        var cached_options = data_options(el);

        if (typeof cached_options === 'object') {
          return cached_options;
        }

        opts_arr = (cached_options || ':').split(';');
        ii = opts_arr.length;

        function isNumber (o) {
          return ! isNaN (o-0) && o !== null && o !== "" && o !== false && o !== true;
        }

        function trim (str) {
          if (typeof str === 'string') return $.trim(str);
          return str;
        }

        while (ii--) {
          p = opts_arr[ii].split(':');
          p = [p[0], p.slice(1).join(':')];

          if (/true/i.test(p[1])) p[1] = true;
          if (/false/i.test(p[1])) p[1] = false;
          if (isNumber(p[1])) {
            if (p[1].indexOf('.') === -1) {
              p[1] = parseInt(p[1], 10);
            } else {
              p[1] = parseFloat(p[1]);
            }
          }

          if (p.length === 2 && p[0].length > 0) {
            opts[trim(p[0])] = trim(p[1]);
          }
        }

        return opts;
      },

      // Description:
      //    Adds JS-recognizable media queries
      //
      // Arguments:
      //    Media (String): Key string for the media query to be stored as in
      //    Foundation.media_queries
      //
      //    Class (String): Class name for the generated <meta> tag
      register_media : function (media, media_class) {
        if(Foundation.media_queries[media] === undefined) {
          $('head').append('<meta class="' + media_class + '"/>');
          Foundation.media_queries[media] = removeQuotes($('.' + media_class).css('font-family'));
        }
      },

      // Description:
      //    Add custom CSS within a JS-defined media query
      //
      // Arguments:
      //    Rule (String): CSS rule to be appended to the document.
      //
      //    Media (String): Optional media query string for the CSS rule to be
      //    nested under.
      add_custom_rule : function (rule, media) {
        if (media === undefined && Foundation.stylesheet) {
          Foundation.stylesheet.insertRule(rule, Foundation.stylesheet.cssRules.length);
        } else {
          var query = Foundation.media_queries[media];

          if (query !== undefined) {
            Foundation.stylesheet.insertRule('@media ' +
              Foundation.media_queries[media] + '{ ' + rule + ' }');
          }
        }
      },

      // Description:
      //    Performs a callback function when an image is fully loaded
      //
      // Arguments:
      //    Image (jQuery Object): Image(s) to check if loaded.
      //
      //    Callback (Function): Function to execute when image is fully loaded.
      image_loaded : function (images, callback) {
        var self = this,
            unloaded = images.length;

        if (unloaded === 0) {
          callback(images);
        }

        images.each(function () {
          single_image_loaded(self.S(this), function () {
            unloaded -= 1;
            if (unloaded === 0) {
              callback(images);
            }
          });
        });
      },

      // Description:
      //    Returns a random, alphanumeric string
      //
      // Arguments:
      //    Length (Integer): Length of string to be generated. Defaults to random
      //    integer.
      //
      // Returns:
      //    Rand (String): Pseudo-random, alphanumeric string.
      random_str : function () {
        if (!this.fidx) this.fidx = 0;
        this.prefix = this.prefix || [(this.name || 'F'), (+new Date).toString(36)].join('-');

        return this.prefix + (this.fidx++).toString(36);
      }
    }
  };

  $.fn.foundation = function () {
    var args = Array.prototype.slice.call(arguments, 0);

    return this.each(function () {
      Foundation.init.apply(Foundation, [this].concat(args));
      return this;
    });
  };

}(jQuery, window, window.document));

(function($, window, document, undefined) {
    'use strict';

    var Foundation = window.Foundation = window.Foundation || {};
    Foundation.libs = Foundation.libs || {};

    /**
     * UMI расширяет поведение выпадающих списков Foundation, изменяя/добавляя следующее:
     * 1) Событие клика "мимо" списка навешивается в момент открытия списка, и снимается во время закрытия списка.
     *    В Foundation это событие всегда слушает клик, с самого старта приложения.
     * 2) Атрибут Id не является обязательным атрибутом для выборки, достаточно иметь один экземпляр списка на одном
     *    уровне вложености с кнопкой. Для этого нужно указать в атрибутах кнопки data-options="selectorById: false;"
     * 3) Кроме определенной в Foundation возможности определить сторону появления списка относительно кнопки,
     *    добавляется возможность указать выравнивание по стороне. Это добавляет к четырем возможным позициям положения
     *    списка, ещё четыре.
     * 4) Возможность быстрого выбора элемента из списка за один клик. На событие нажатие кнопки мыши, список
     *    раскрывается, и если отпустить кнопку над элементом списка, вызывается тригер события 'click' для этого
     *    элемента. Отпустив кнопку вне списка, происходит его закрытие.
     *    С помощью опций можно кастомизировать событие fastSelect.
     * 5) Возможность задать элемент списка, при клике на который происходит закрытие этого списка.
     * 6) Адаптивное поведение списка. При недостатке места для списка, он инвертирует своё положение если с другой
     *    стороны достаточно места.
     */
    Foundation.libs.dropdown = {
        name: 'dropdown',
        version: '5.3.0.umi-custom',

        settings: {
            /**
             * Класс изменяющий видимость списка
             * @param {string} activeClass
             */
            activeClass: 'open',

            /**
             * Список открывается при наведении
             * @param {bool} isHover
             */
            isHover: false,

            /**
             * Разрешает быстрый выбор при нажатии
             * @param {bool} fastSelect
             */
            fastSelect: true,

            fastSelectHoverSelector: 'li',

            fastSelectHoverClassName: 'hover',

            fastSelectTarget: 'a',

            /**
             * Определяет положение спсика относительно кнопки
             * @param {string} side
             */
            side: 'bottom',

            /**
             * Определяет выравнивание списка относительно кнопки
             * @param {string} align
             */
            align: 'left',

            /**
             * Выбор списка по уникальному id
             * @param {bool} selectorById
             */
            selectorById: true,

            /**
             * Задает списку минимальную ширину, равную ширине кнопки (хендлера) имеющего соответствующий селектор
             * @param {string} minWidthLikeElement класс елемента
             */
            minWidthLikeElement: '.button',

            /**
             * @param {bool} adaptiveBehaviour
             */
            adaptiveBehaviour: true,

            /**
             * @param {string} closeAfterClickOnElement
             */
            closeAfterClickOnElement: 'a',

            opened: function() {},

            closed: function() {}
        },

        selector: function() {
            return '[' + this.attr_name() + ']';
        },

        init: function(scope, method, options) {
            Foundation.inherit(this, 'throttle');

            this.bindings(method, options);
        },

        events: function(scope) {
            var self = this;
            var S = self.S;
            S(self.scope).off('.' + self.name);

            self.eventListener.mousedown.call(this, scope);
            self.eventListener.click.call(this, scope);
            self.eventListener.hover.call(this);
            self.eventListener.resize.call(this);
            self.eventListener.toggleObserver.call(this);
        },

        eventListener: {
            click: function(scope) {
                var self = this;
                var S = self.S;

                S(self.scope).on('click.fndtn.dropdown', self.selector(), function(e) {
                    var settings = S(this).data(self.attr_name(true) + '-init') || self.settings;
                    if (!settings.fastSelect && (!settings.isHover || Modernizr.touch)) {
                        e.preventDefault();
                        self.toggle($(this));
                    }
                });
            },

            mousedown: function(scope) {
                var self = this;
                var S = self.S;
                var dropdownSelector = '[' + self.attr_name() + '-content]';

                S(self.scope).on('mousedown.fndtn.dropdown', self.selector(), function(e) {
                    var settings = S(this).data(self.attr_name(true) + '-init') || self.settings;

                    if (settings.fastSelect && (!settings.isHover || Modernizr.touch)) {
                        e.preventDefault();
                        var $targetButton = $(this);
                        var $dropdown = self.toggle($targetButton);
                        var hoverSelector = settings.fastSelectHoverSelector;
                        var hoverClassName = settings.fastSelectHoverClassName;
                        var fastSelectTarget = settings.fastSelectTarget;

                        S(self.scope).on('mousemove.fndtn.dropdown.fast', dropdownSelector, function(event) {
                            $dropdown = $(this);

                            var $hoverElement = $(event.target).closest(hoverSelector);

                            if ($hoverElement.length) {
                                if (!$hoverElement.hasClass(hoverClassName)) {
                                    $dropdown.find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                                    $hoverElement.addClass(hoverClassName);
                                }
                            } else {
                                $dropdown.find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                            }
                        });

                        S(self.scope).on('mouseout.fndtn.dropdown.fast', dropdownSelector, function() {
                            $(this).find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                        });

                        S(self.scope).on('mouseup.fndtn.dropdown.fast', function(e) {
                            var fastClick;
                            var $target = $(e.target);

                            if ($dropdown.find(e.target).length) {
                                fastClick = $target.closest(fastSelectTarget);
                                if (fastClick.length) {
                                    fastClick.trigger('click');
                                }
                            } else if (!$target.closest($targetButton).length) {
                                self.closeall();
                            }
                            S(self.scope).off('.dropdown.fast');
                        });
                    }
                });
            },

            hover: function() {
                var self = this,
                    S = self.S;

                S(self.scope).on('mouseenter.fndtn.dropdown',
                    '[' + self.attr_name() + '], [' + self.attr_name() + '-content]', function(e) {
                    var $this = S(this);
                    var dropdown;
                    var target;

                    clearTimeout(self.timeout);

                    if ($this.data(self.dataAttr()) !== undefined) {
                        target = $this;
                        dropdown = self.getDropdown(target);
                    } else {
                        dropdown = $this;
                        target = S('[' + self.attr_name() + '="' + dropdown.attr('id') + '"]');
                        if (target.length === 0) {
                            target = dropdown.parent().children(self.selector());
                        }
                    }

                    var settings = target.data(self.attr_name(true) + '-init') || self.settings;

                    /*if (S(e.target).data(self.dataAttr()) !== undefined && settings.isHover) {

                    }*/
                    if (settings.isHover) {
                        self.closeall.call(self);
                        self.open.apply(self, [dropdown, target]);
                    }
                });

                S(self.scope).on('mouseleave.fndtn.dropdown',
                    '[' + self.attr_name() + '], [' + self.attr_name() + '-content]', function() {
                    var $this = S(this);

                    self.timeout = setTimeout(function() {
                        var settings;
                        var dropdown;

                        if ($this.data(self.dataAttr()) !== undefined) {
                            settings = $this.data(self.dataAttr() + '-init') || self.settings;
                            if (settings.isHover) {
                                dropdown = self.getDropdown($this);
                                self.close.call(self, dropdown);
                            }
                        } else {
                            var target = S('[' + self.attr_name() + '="' + $this.attr('id') + '"]');
                            if (target.length === 0) {
                                target = $this.parent().children(self.selector());
                            }
                            settings = target.data(self.attr_name(true) + '-init') || self.settings;
                            if (settings.isHover) {
                                self.close.call(self, $this);
                            }
                        }
                    }, 150);
                });
            },

            /**
             * Добавляет событие ловящее клик мимо открытого выпадающего списка и закрывает этот список.
             * @method onClickMissDropdown
             */
            onClickMissDropdown: function() {
                var self = this;
                var S = self.S;
                var dropdownSelector = '[' + self.attr_name() + '-content]';

                S(self.scope).on('click.fndtn.dropdown.miss', function(e) {
                    var parent = S(e.target).closest(dropdownSelector);

                    if (S(e.target).data(self.dataAttr()) || S(e.target).parent().data(self.dataAttr())) {
                        return;
                    }

                    if (parent.length > 0 && (S(e.target).is(dropdownSelector) ||
                        $.contains(parent.first()[0], e.target))) {
                        e.stopPropagation();
                        return;
                    }

                    self.close.call(self, S(dropdownSelector));
                    S(self.scope).off('click.fndtn.dropdown.miss');
                });
            },

            /**
             * Снимает событие "ловли" клика мимо открытого списка
             * @method offClickMissDropdown
             */
            offClickMissDropdown: function() {
                var self = this;
                var S = self.S;

                S(this.scope).off('click.fndtn.dropdown.miss');
            },

            resize: function() {
                var self = this;
                var S = self.S;

                S(window).off('.dropdown').on('resize.fndtn.dropdown', self.throttle(function() {
                    self.resize.call(self);
                }, 50));

                self.resize.call(self);
            },

            toggleObserver: function() {
                var self = this;
                var S = self.S;

                S(self.scope).on('opened.fndtn.dropdown', '[' + self.attr_name() + '-content]', function() {
                    self.settings.opened.call(this);
                });
                S(self.scope).on('closed.fndtn.dropdown', '[' + self.attr_name() + '-content]', function() {
                    self.settings.closed.call(this);
                });
            }
        },

        getDropdown: function(target) {
            var self = this;
            var S = self.S;
            var dropdown;
            var settings = S(target).data(self.attr_name(true) + '-init') || self.settings;
            var dropdownId;

            if (settings.selectorById) {
                dropdownId = target.data(this.dataAttr());
                if (dropdownId) {
                    dropdown = S('#' + dropdownId);
                } else {
                    console.warn('Id attr is undefined.');
                    return false;
                }
            } else {
                dropdown = $($(S(target)[0].parentNode).children('[data-' + self.name + '-content]')[0]);
            }

            return dropdown;
        },

        /**
         * Переключает видимость списка
         * @method toggle
         * @param {JQuery} target
         * @return {JQuery | null} dropdown
         */
        toggle: function(target) {
            var self = this;
            var dropdown = self.getDropdown(target);

            if (!dropdown || dropdown.length === 0) {
                return dropdown;
            }

            self.close.call(self, self.S('[' + self.attr_name() + '-content]').not(dropdown));

            if (dropdown.hasClass(self.settings.activeClass)) {
                self.close.call(self, dropdown);
                if (dropdown.data('target') !== target.get(0)) {
                    self.open.call(self, dropdown, target);
                }
            } else {
                self.open.call(self, dropdown, target);
            }
            return dropdown;
        },

        close: function(dropdown) {
            var self = this;

            dropdown.each(function() {
                if (self.S(this).hasClass(self.settings.activeClass)) {
                    self.S(this)
                        .off('click.fndtn.dropdown.closeOnClick')
                        .removeClass(self.settings.activeClass)
                        .removeAttr('style')
                        .prev('[' + self.attr_name() + ']')
                        .removeClass(self.settings.activeClass)
                        .removeData('target');

                    self.S(this).trigger('closed').trigger('closed.fndtn.dropdown', [dropdown]);
                }
            });

            this.eventListener.offClickMissDropdown.call(this);
        },

        closeall: function() {
            var self = this;
            $.each(self.S('[' + this.attr_name() + '-content]'), function() {
                self.close.call(self, self.S(this));
            });
        },

        /**
         * Открывает список
         * @method open
         * @param {JQuery} dropdown открываемый список
         * @param {JQuery} target хендлер (кнопка) вызвавший открытие списка
         */
        open: function(dropdown, target) {
            var self = this;
            var S = self.S;
            var settings = S(target).data(self.attr_name(true) + '-init') || self.settings;
            self.setDropdownStyle(dropdown.addClass(settings.activeClass), target);

            dropdown.prev('[' + self.attr_name() + ']').addClass(settings.activeClass);
            dropdown.data('target', target.get(0)).trigger('opened.fndtn.dropdown', [dropdown, target]);

            if (settings.closeAfterClickOnElement) {
                dropdown.on('click.fndtn.dropdown.closeOnClick', settings.closeAfterClickOnElement, function() {
                    self.close.call(self, S(dropdown));
                    dropdown.off('click.fndtn.dropdown.closeOnClick');
                });
            }

            setTimeout(function() {
                self.eventListener.onClickMissDropdown.call(self);
            }, 150);
        },

        resize: function() {
            var dropdown = this.S('[' + this.attr_name() + '-content].open');
            var target = this.S('[' + this.attr_name() + '="' + dropdown.attr('id') + '"]');

            if (dropdown.length && target.length) {
                this.setDropdownStyle(dropdown, target);
            }
        },

        /**
         * Возвращает имя атрибута data для хендлера
         * @return {string}
         */
        dataAttr: function() {
            if (this.namespace.length > 0) {
                return this.namespace + '-' + this.name;
            }
            return this.name;
        },

        /**
         * Устанавливает стили для выпадающего списка
         * @param {JQuery} dropdown
         * @param {JQuery} target
         * @return {JQuery} dropdown
         */
        setDropdownStyle: function(dropdown, target) {
            var settings = target.data(this.attr_name(true) + '-init') || this.settings;
            var leftOffset;
            var styleForSmall;

            this.clearIdx();

            if (this.small()) {//TODO: fix it
                leftOffset = Math.max((target.width() - dropdown.width()) / 2, 8);
                var p = this.styleForSide.bottom.call(dropdown, target);

                styleForSmall = {
                    position: 'absolute',
                    width: '95%',
                    'maxWidth': 'none',
                    top: p.top
                };

                if (settings.minWidthLikeElement) {
                    styleForSmall.minWidth = target.outerWidth() + 'px';
                }

                dropdown.attr('style', '').removeClass('drop-left drop-right drop-top').css(styleForSmall);
                dropdown.css(Foundation.rtl ? 'right' : 'left', leftOffset);
            } else {
                this.style(dropdown, target, settings);
            }

            return dropdown;
        },

        /**
         * Устанавливает стили для выпадающего списка
         * @param {JQuery} dropdown
         * @param {JQuery} target
         * @param {object} settings
         */
        style: function(dropdown, target, settings) {
            var side = settings.side;
            var align = settings.align;
            var dropdownStyles = {position: 'absolute'};

            if (settings.minWidthLikeElement) {
                dropdownStyles.minWidth = target.closest(settings.minWidthLikeElement).outerWidth() + 'px';
                dropdown.css({'minWidth': dropdownStyles.minWidth});
            }

            var checkPosition = this.checkPosition(dropdown, target, settings, side, align);
            side = checkPosition.side;
            align = checkPosition.align;

            var basePosition = this.styleFor._basePosition.call(dropdown, target);
            var computedStyle = this.styleFor.side[side].call(dropdown, target, basePosition);
            computedStyle = $.extend(computedStyle, this.styleFor.align[align].call(dropdown, target, basePosition));
            computedStyle = $.extend(dropdownStyles, computedStyle);

            dropdown.attr('style', '').css(dropdownStyles);
        },

        checkPosition: function(dropdown, target, settings, side, align) {
            if (settings.adaptiveBehaviour) {
                var screenSize = {
                    width: $(window).width(),
                    height: $(window).height()
                };
                var dropdownSize = {
                    width: dropdown.outerWidth(),
                    height: dropdown.outerHeight()
                };
                var dropdownPosition = dropdown.offset();
                var targetSize = {
                    width: target.outerWidth(),
                    height: target.outerHeight()
                };
                var targetOffset = target.offset();

                switch (side) {
                    case 'top':
                        if (dropdownSize.height > targetOffset.top &&
                            screenSize.height > dropdownSize.height + targetOffset.top + targetSize.height) {
                            side = 'bottom';
                        }
                        break;
                    case 'bottom':
                        if (targetOffset.top + dropdownSize.height + targetSize.height > screenSize.height &&
                            targetOffset.top - dropdownSize.height > 0) {
                            side = 'top';
                        }
                        break;
                    case 'left':
                        if (targetOffset.left - dropdownSize.width < 0 &&
                            targetOffset.left + targetSize.width + dropdownSize.width < screenSize.width) {
                            side = 'right';
                        }
                        break;
                    case 'right':
                        if (targetOffset.left + targetSize.width + dropdownSize.width > screenSize.width &&
                            targetOffset.left - dropdownSize.width > 0) {
                            side = 'left';
                        }
                        break;
                }

                switch (align) {
                    case 'top':
                        if (targetOffset.top + dropdownSize.height > screenSize.height &&
                            targetOffset.top > dropdownSize.height) {
                            align = 'bottom';
                        }
                        break;
                    case 'bottom':
                        if (targetOffset.top < dropdownSize.height &&
                            targetOffset.top + dropdownSize.height > screenSize.height) {
                            align = 'top';
                        }
                        break;
                    case 'left':
                        if (targetOffset.left + dropdownSize.width > screenSize.width &&
                            targetOffset.left + targetSize.width > dropdownSize.width) {
                            align = 'right';
                        }
                        break;
                    case 'right':
                        if (targetOffset.left + targetSize.width < dropdownSize.width &&
                            targetOffset.left + dropdownSize.width < screenSize.width) {
                            align = 'left';
                        }
                        break;
                }
            }

            return {side: side, align: align};
        },

        styleFor: {
            /**
             * Вычисляет положение кнопки относительно предка имеющего position отличный от static
             * @param {JQuery} target
             * @return {object}
             * @private
             */
            _basePosition: function(target) {
                var offsetClosestPositionedParent = this.offsetParent().offset();
                var basePosition = target.offset();

                basePosition.top -= offsetClosestPositionedParent.top;
                basePosition.left -= offsetClosestPositionedParent.left;

                return basePosition;
            },

            side: {
                top: function(target, basePosition) {
                    this.addClass('drop-top');

                    return {top: basePosition.top - this.outerHeight()};
                },

                bottom: function(target, basePosition) {
                    return {top: basePosition.top + target.outerHeight()};
                },

                left: function(target, basePosition) {
                    this.addClass('drop-left');

                    return {left: basePosition.left - this.outerWidth()};
                },

                right: function(target, basePosition) {
                    this.addClass('drop-right');

                    return {left: basePosition.left + target.outerWidth()};
                }
            },

            align: {
                top: function(target, basePosition) {
                    return {top: basePosition.top};
                },

                bottom: function(target, basePosition) {
                    return {top: basePosition.top - this.outerHeight() + target.outerHeight()};
                },

                left: function(target, basePosition) {
                    return {left: basePosition.left};
                },

                right: function(target, basePosition) {
                    return {left: basePosition.left - this.outerWidth() + target.outerWidth()};
                }
            }
        },

        clearIdx: function() {
            var sheet = Foundation.stylesheet;

            if (this.rule_idx) {
                sheet.deleteRule(this.rule_idx);
                sheet.deleteRule(this.rule_idx);
                delete this.rule_idx;
            }
        },

        small: function() {
            return matchMedia(Foundation.media_queries.small).matches &&
                !matchMedia(Foundation.media_queries.medium).matches;
        },

        off: function() {
            this.S(this.scope).off('.fndtn.dropdown');
            this.S(window).off('.fndtn.dropdown');
        }
    };
}(jQuery, window, window.document));
