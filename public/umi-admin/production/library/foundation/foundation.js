!function(a,b,c,d){"use strict";function e(a){return("string"==typeof a||a instanceof String)&&(a=a.replace(/^['\\/"]+|(;\s?})+|['\\/"]+$/g,"")),a}var f=function(b){for(var c=b.length,d=a("head");c--;)0===d.has("."+b[c]).length&&d.append('<meta class="'+b[c]+'" />')};f(["foundation-mq-small","foundation-mq-medium","foundation-mq-large","foundation-mq-xlarge","foundation-mq-xxlarge","foundation-data-attribute-namespace"]),a(function(){"undefined"!=typeof FastClick&&"undefined"!=typeof c.body&&FastClick.attach(c.body)});var g=function(b,d){if("string"==typeof b){if(d){var e;if(d.jquery){if(e=d[0],!e)return d}else e=d;return a(e.querySelectorAll(b))}return a(c.querySelectorAll(b))}return a(b,d)},h=function(a){var b=[];return a||b.push("data"),this.namespace.length>0&&b.push(this.namespace),b.push(this.name),b.join("-")},i=function(a){for(var b=a.split("-"),c=b.length,d=[];c--;)0!==c?d.push(b[c]):this.namespace.length>0?d.push(this.namespace,b[c]):d.push(b[c]);return d.reverse().join("-")},j=function(b,c){var d=this,e=!g(this).data(this.attr_name(!0));return g(this.scope).is("["+this.attr_name()+"]")?(g(this.scope).data(this.attr_name(!0)+"-init",a.extend({},this.settings,c||b,this.data_options(g(this.scope)))),e&&this.events(this.scope)):g("["+this.attr_name()+"]",this.scope).each(function(){var e=!g(this).data(d.attr_name(!0)+"-init");g(this).data(d.attr_name(!0)+"-init",a.extend({},d.settings,c||b,d.data_options(g(this)))),e&&d.events(this)}),"string"==typeof b?this[b].call(this,c):void 0},k=function(a,b){function c(){b(a[0])}function d(){if(this.one("load",c),/MSIE (\d+\.\d+);/.test(navigator.userAgent)){var a=this.attr("src"),b=a.match(/\?/)?"&":"?";b+="random="+(new Date).getTime(),this.attr("src",a+b)}}return a.attr("src")?void(a[0].complete||4===a[0].readyState?c():d.call(a)):void c()};b.matchMedia=b.matchMedia||function(a){var b,c=a.documentElement,d=c.firstElementChild||c.firstChild,e=a.createElement("body"),f=a.createElement("div");return f.id="mq-test-1",f.style.cssText="position:absolute;top:-100em",e.style.background="none",e.appendChild(f),function(a){return f.innerHTML='&shy;<style media="'+a+'"> #mq-test-1 { width: 42px; }</style>',c.insertBefore(e,d),b=42===f.offsetWidth,c.removeChild(e),{matches:b,media:a}}}(c),function(){function a(){c&&(f(a),h&&jQuery.fx.tick())}for(var c,d=0,e=["webkit","moz"],f=b.requestAnimationFrame,g=b.cancelAnimationFrame,h="undefined"!=typeof jQuery.fx;d<e.length&&!f;d++)f=b[e[d]+"RequestAnimationFrame"],g=g||b[e[d]+"CancelAnimationFrame"]||b[e[d]+"CancelRequestAnimationFrame"];f?(b.requestAnimationFrame=f,b.cancelAnimationFrame=g,h&&(jQuery.fx.timer=function(b){b()&&jQuery.timers.push(b)&&!c&&(c=!0,a())},jQuery.fx.stop=function(){c=!1})):(b.requestAnimationFrame=function(a){var c=(new Date).getTime(),e=Math.max(0,16-(c-d)),f=b.setTimeout(function(){a(c+e)},e);return d=c+e,f},b.cancelAnimationFrame=function(a){clearTimeout(a)})}(jQuery),b.Foundation={name:"Foundation",version:"5.3.0",media_queries:{small:g(".foundation-mq-small").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),medium:g(".foundation-mq-medium").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),large:g(".foundation-mq-large").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),xlarge:g(".foundation-mq-xlarge").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),xxlarge:g(".foundation-mq-xxlarge").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,"")},stylesheet:a("<style></style>").appendTo("head")[0].sheet,global:{namespace:d},init:function(a,b,c,d,e){var f=[a,c,d,e],h=[];if(this.rtl=/rtl/i.test(g("html").attr("dir")),this.scope=a||this.scope,this.set_namespace(),b&&"string"==typeof b&&!/reflow/i.test(b))this.libs.hasOwnProperty(b)&&h.push(this.init_lib(b,f));else for(var i in this.libs)h.push(this.init_lib(i,b));return a},init_lib:function(b,c){return this.libs.hasOwnProperty(b)?(this.patch(this.libs[b]),c&&c.hasOwnProperty(b)?("undefined"!=typeof this.libs[b].settings?a.extend(!0,this.libs[b].settings,c[b]):"undefined"!=typeof this.libs[b].defaults&&a.extend(!0,this.libs[b].defaults,c[b]),this.libs[b].init.apply(this.libs[b],[this.scope,c[b]])):(c=c instanceof Array?c:new Array(c),this.libs[b].init.apply(this.libs[b],c))):function(){}},patch:function(a){a.scope=this.scope,a.namespace=this.global.namespace,a.rtl=this.rtl,a.data_options=this.utils.data_options,a.attr_name=h,a.add_namespace=i,a.bindings=j,a.S=this.utils.S},inherit:function(a,b){for(var c=b.split(" "),d=c.length;d--;)this.utils.hasOwnProperty(c[d])&&(a[c[d]]=this.utils[c[d]])},set_namespace:function(){var b=this.global.namespace===d?a(".foundation-data-attribute-namespace").css("font-family"):this.global.namespace;this.global.namespace=b===d||/false/i.test(b)?"":b},libs:{},utils:{S:g,throttle:function(a,b){var c=null;return function(){var d=this,e=arguments;null==c&&(c=setTimeout(function(){a.apply(d,e),c=null},b))}},debounce:function(a,b,c){var d,e;return function(){var f=this,g=arguments,h=function(){d=null,c||(e=a.apply(f,g))},i=c&&!d;return clearTimeout(d),d=setTimeout(h,b),i&&(e=a.apply(f,g)),e}},data_options:function(b,c){function d(a){return!isNaN(a-0)&&null!==a&&""!==a&&a!==!1&&a!==!0}function e(b){return"string"==typeof b?a.trim(b):b}c=c||"options";var f,g,h,i={},j=function(a){var b=Foundation.global.namespace;return a.data(b.length>0?b+"-"+c:c)},k=j(b);if("object"==typeof k)return k;for(h=(k||":").split(";"),f=h.length;f--;)g=h[f].split(":"),g=[g[0],g.slice(1).join(":")],/true/i.test(g[1])&&(g[1]=!0),/false/i.test(g[1])&&(g[1]=!1),d(g[1])&&(g[1]=-1===g[1].indexOf(".")?parseInt(g[1],10):parseFloat(g[1])),2===g.length&&g[0].length>0&&(i[e(g[0])]=e(g[1]));return i},register_media:function(b,c){Foundation.media_queries[b]===d&&(a("head").append('<meta class="'+c+'"/>'),Foundation.media_queries[b]=e(a("."+c).css("font-family")))},add_custom_rule:function(a,b){if(b===d&&Foundation.stylesheet)Foundation.stylesheet.insertRule(a,Foundation.stylesheet.cssRules.length);else{var c=Foundation.media_queries[b];c!==d&&Foundation.stylesheet.insertRule("@media "+Foundation.media_queries[b]+"{ "+a+" }")}},image_loaded:function(a,b){var c=this,d=a.length;0===d&&b(a),a.each(function(){k(c.S(this),function(){d-=1,0===d&&b(a)})})},random_str:function(){return this.fidx||(this.fidx=0),this.prefix=this.prefix||[this.name||"F",(+new Date).toString(36)].join("-"),this.prefix+(this.fidx++).toString(36)}}},a.fn.foundation=function(){var a=Array.prototype.slice.call(arguments,0);return this.each(function(){return Foundation.init.apply(Foundation,[this].concat(a)),this})}}(jQuery,window,window.document),function(a,b,c,d){"use strict";var e=b.Foundation=b.Foundation||{};e.libs=e.libs||{},e.libs.dropdown={name:"dropdown",version:"5.3.0.umi-custom",settings:{runtimeInit:!0,activeClass:"open",respondActiveClassForButton:!0,isHover:!1,fastSelect:!0,fastSelectHoverSelector:"li",fastSelectHoverClassName:"hover",fastSelectTarget:"a",side:"bottom",align:"left",selectorById:!0,buttonSelector:".button",minWidthLikeElement:".button",adaptiveBehaviour:!0,checkPositionRegardingElement:null,closeAfterClickOnElement:"a",opened:function(){},closed:function(){}},selector:function(){return"["+this.attr_name()+"]"},init:function(a,b,c){e.inherit(this,"throttle"),this.bindings(b,c)},events:function(a){var b=this,c=b.S;c(b.scope).off("."+b.name),b.eventListener.mousedown.call(this,a),b.eventListener.click.call(this,a),b.eventListener.hover.call(this),b.eventListener.resize.call(this),b.eventListener.toggleObserver.call(this)},eventListener:{click:function(){var b=this,c=b.S;c(b.scope).on("click.fndtn.dropdown",b.selector(),function(c){var d=b.getSettings(this);d.fastSelect||d.isHover&&!Modernizr.touch||(c.preventDefault(),b.toggle(a(this),d))})},mousedown:function(){var b=this,c=b.S,d="["+b.attr_name()+"-content]";c(b.scope).on("mousedown.fndtn.dropdown",b.selector(),function(e){var f=b.getSettings(this);if(f.fastSelect&&(!f.isHover||Modernizr.touch)){e.preventDefault();var g=a(this),h=b.toggle(g,f),i=f.fastSelectHoverSelector,j=f.fastSelectHoverClassName,k=f.fastSelectTarget;c(b.scope).on("mousemove.fndtn.dropdown.fast",d,function(b){h=a(this);var c=a(b.target).closest(i);c.length?c.hasClass(j)||(h.find(i+"."+j).removeClass(j),c.addClass(j)):h.find(i+"."+j).removeClass(j)}),c(b.scope).on("mouseout.fndtn.dropdown.fast",d,function(){a(this).find(i+"."+j).removeClass(j)}),c(b.scope).on("mouseup.fndtn.dropdown.fast",function(d){var e,f=a(d.target);h.find(d.target).length?(e=f.closest(k),e.length&&e.trigger("click")):f.closest(g).length||b.closeall(),c(b.scope).off(".dropdown.fast")})}})},hover:function(){var a=this,b=a.S;b(a.scope).on("mouseenter.fndtn.dropdown","["+a.attr_name()+"], ["+a.attr_name()+"-content]",function(){var c,e,f=b(this);clearTimeout(a.timeout),f.data(a.dataAttr())!==d?(e=f,c=a.getDropdown(e)):(c=f,e=b("["+a.attr_name()+'="'+c.attr("id")+'"]'),0===e.length&&(e=c.parent().children(a.selector())));var g=a.getSettings(e);g.isHover&&(a.closeall.call(a),a.open.apply(a,[c,e]))}),b(a.scope).on("mouseleave.fndtn.dropdown","["+a.attr_name()+"], ["+a.attr_name()+"-content]",function(){var c=b(this);a.timeout=setTimeout(function(){var e,f;if(c.data(a.dataAttr())!==d)e=a.getSettings(c),e.isHover&&(f=a.getDropdown(c,e),a.close.call(a,f));else{var g=b("["+a.attr_name()+'="'+c.attr("id")+'"]');0===g.length&&(g=c.parent().children(a.selector())),e=a.getSettings(g),e.isHover&&a.close.call(a,c)}},150)})},onClickMissDropdown:function(){var b=this,c=b.S,d="["+b.attr_name()+"-content]";c(b.scope).on("click.fndtn.dropdown.miss",function(e){var f=c(e.target).closest(d);if(!c(e.target).data(b.dataAttr())&&!c(e.target).parent().data(b.dataAttr())){if(f.length>0&&(c(e.target).is(d)||a.contains(f.first()[0],e.target)))return void e.stopPropagation();b.close.call(b,c(d)),c(b.scope).off("click.fndtn.dropdown.miss")}})},offClickMissDropdown:function(){var a=this,b=a.S;b(this.scope).off("click.fndtn.dropdown.miss")},resize:function(){var a=this,c=a.S;c(b).off(".dropdown").on("resize.fndtn.dropdown",a.throttle(function(){a.resize.call(a)},50)),a.resize.call(a)},toggleObserver:function(){var a=this,b=a.S;b(a.scope).on("opened.fndtn.dropdown","["+a.attr_name()+"-content]",function(){a.settings.opened.call(this)}),b(a.scope).on("closed.fndtn.dropdown","["+a.attr_name()+"-content]",function(){a.settings.closed.call(this)})}},getDropdown:function(a,b){var c,d=this,e=d.S;b=b||d.getSettings(a);var f;if(b.selectorById){if(f=a.data(this.dataAttr()),!f)return console.warn("Id attr is undefined."),!1;c=e("#"+f)}else c=e(a).closest(b.buttonSelector).parent().children("[data-"+d.name+"-content]");return c},toggle:function(a,b){var c,d=this,e=d.getDropdown(a,b);return e&&0!==e.length?(d.close.call(d,d.S("["+d.attr_name()+"-content]").not(e)),e.hasClass(b.activeClass)?(c=e.data("target")!==a.get(0),d.close.call(d,e),c&&d.open.call(d,e,a)):d.open.call(d,e,a),e):e},close:function(b){var c=this,d=c.S;b.each(function(){var b,e=a(d(this).data("target"));b=e.length?c.getSettings(e):c.settings,d(this).hasClass(b.activeClass)&&(d(this).off("click.fndtn.dropdown.closeOnClick").removeClass(b.activeClass).removeAttr("style").removeData("target"),e=e.length&&b.respondActiveClassForButton?e.closest(b.buttonSelector):d(this).prev("["+c.attr_name()+"]"),e.length&&e.removeClass(b.activeClass),d(this).trigger("closed").trigger("closed.fndtn.dropdown",[d(this)]))}),this.eventListener.offClickMissDropdown.call(this)},closeall:function(){var b=this;a.each(b.S("["+this.attr_name()+"-content]"),function(){b.close.call(b,b.S(this))})},open:function(a,b){var c,d=this,e=d.S,f=d.getSettings(b);d.setDropdownStyle(a.addClass(f.activeClass),b),c=f.respondActiveClassForButton?b.closest(f.buttonSelector):a.prev("["+d.attr_name()+"]"),c.addClass(f.activeClass),a.data("target",b.get(0)).trigger("opened.fndtn.dropdown",[a,b]),f.closeAfterClickOnElement&&a.on("click.fndtn.dropdown.closeOnClick",f.closeAfterClickOnElement,function(){d.close.call(d,e(a)),a.off("click.fndtn.dropdown.closeOnClick")}),setTimeout(function(){d.eventListener.onClickMissDropdown.call(d)},150)},resize:function(){var a=this.S("["+this.attr_name()+"-content].open"),b=this.S("["+this.attr_name()+'="'+a.attr("id")+'"]');a.length&&b.length&&this.setDropdownStyle(a,b)},dataAttr:function(){return this.namespace.length>0?this.namespace+"-"+this.name:this.name},getSettings:function(b){var c=this,d=c.S,e=d(b).data(c.attr_name(!0)+"-init");return c.settings.runtimeInit&&!e?(d(b).data(c.attr_name(!0)+"-init",a.extend({},c.settings,c.data_options(d(b)))),e=d(b).data(c.attr_name(!0)+"-init")):e||(e=c.settings),e},setDropdownStyle:function(a,b){var c=this.getSettings(b);return this.clearIdx(),this.style(a,b,c),a},style:function(b,c,d){var e=d.side,f=d.align,g={position:"absolute"};d.minWidthLikeElement&&(g.minWidth=c.closest(d.minWidthLikeElement).outerWidth()+"px",b.css({minWidth:g.minWidth}));var h=this.checkPosition(b,c,d,e,f);e=h.side,f=h.align;var i=this.styleFor._basePosition.call(b,c),j=this.styleFor.side[e].call(b,c,i);j=a.extend(j,this.styleFor.align[f].call(b,c,i)),j=a.extend(g,j),b.attr("style","").css(g)},checkPosition:function(c,d,e,f,g){if(e.adaptiveBehaviour){var h,i={width:a(b).width(),height:a(b).height()},j={width:c.outerWidth(),height:c.outerHeight()},k={width:d.outerWidth(),height:d.outerHeight()},l=d.offset();e.checkPositionRegardingElement?(h=d.closest(e.checkPositionRegardingElement),d=h.length?h:d,i.width=d.outerWidth()):e.minWidthLikeElement&&!d.is(e.minWidthLikeElement)&&(h=d.closest(e.minWidthLikeElement),d=h.length?h:d),k.width=d.outerWidth(),l.width=d.offset().width;for(var m in l)l.hasOwnProperty(m)&&(l[m]=Math.ceil(l[m]));switch(f){case"top":j.height>l.top&&i.height>j.height+l.top+k.height&&(f="bottom");break;case"bottom":l.top+j.height+k.height>i.height&&l.top-j.height>0&&(f="top");break;case"left":l.left-j.width<0&&l.left+k.width+j.width<i.width&&(f="right");break;case"right":l.left+k.width+j.width>i.width&&l.left-j.width>0&&(f="left")}switch(g){case"top":l.top+j.height>i.height&&l.top>j.height&&(g="bottom");break;case"bottom":l.top<j.height&&l.top+j.height>i.height&&(g="top");break;case"left":l.left+j.width>i.width&&l.left+k.width>j.width&&(g="right");break;case"right":l.left+k.width<j.width&&l.left+j.width<i.width&&(g="left")}}return{side:f,align:g}},styleFor:{_basePosition:function(a){var b=this.offsetParent().offset(),c=a.offset();return c.top-=b.top,c.left-=b.left,c},side:{top:function(a,b){return this.addClass("drop-top"),{top:b.top-this.outerHeight()}},bottom:function(a,b){return{top:b.top+a.outerHeight()}},left:function(a,b){return this.addClass("drop-left"),{left:b.left-this.outerWidth()}},right:function(a,b){return this.addClass("drop-right"),{left:b.left+a.outerWidth()}}},align:{top:function(a,b){return{top:b.top}},bottom:function(a,b){return{top:b.top-this.outerHeight()+a.outerHeight()}},left:function(a,b){return{left:b.left}},right:function(a,b){return{left:b.left-this.outerWidth()+a.outerWidth()}}}},clearIdx:function(){var a=e.stylesheet;this.rule_idx&&(a.deleteRule(this.rule_idx),a.deleteRule(this.rule_idx),delete this.rule_idx)},small:function(){return matchMedia(e.media_queries.small).matches&&!matchMedia(e.media_queries.medium).matches},off:function(){this.S(this.scope).off(".fndtn.dropdown"),this.S(b).off(".fndtn.dropdown")}}}(jQuery,window,window.document);