/**
 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
 *
 * @version 0.6.11
 * @codingstandard ftlabs-jsv2
 * @copyright The Financial Times Limited [All Rights Reserved]
 * @license MIT License (see LICENSE.txt)
 */
function FastClick(a){"use strict";var b,c=this;if(this.trackingClick=!1,this.trackingClickStart=0,this.targetElement=null,this.touchStartX=0,this.touchStartY=0,this.lastTouchIdentifier=0,this.touchBoundary=10,this.layer=a,!a||!a.nodeType)throw new TypeError("Layer must be a document node");this.onClick=function(){return FastClick.prototype.onClick.apply(c,arguments)},this.onMouse=function(){return FastClick.prototype.onMouse.apply(c,arguments)},this.onTouchStart=function(){return FastClick.prototype.onTouchStart.apply(c,arguments)},this.onTouchMove=function(){return FastClick.prototype.onTouchMove.apply(c,arguments)},this.onTouchEnd=function(){return FastClick.prototype.onTouchEnd.apply(c,arguments)},this.onTouchCancel=function(){return FastClick.prototype.onTouchCancel.apply(c,arguments)},FastClick.notNeeded(a)||(this.deviceIsAndroid&&(a.addEventListener("mouseover",this.onMouse,!0),a.addEventListener("mousedown",this.onMouse,!0),a.addEventListener("mouseup",this.onMouse,!0)),a.addEventListener("click",this.onClick,!0),a.addEventListener("touchstart",this.onTouchStart,!1),a.addEventListener("touchmove",this.onTouchMove,!1),a.addEventListener("touchend",this.onTouchEnd,!1),a.addEventListener("touchcancel",this.onTouchCancel,!1),Event.prototype.stopImmediatePropagation||(a.removeEventListener=function(b,c,d){var e=Node.prototype.removeEventListener;"click"===b?e.call(a,b,c.hijacked||c,d):e.call(a,b,c,d)},a.addEventListener=function(b,c,d){var e=Node.prototype.addEventListener;"click"===b?e.call(a,b,c.hijacked||(c.hijacked=function(a){a.propagationStopped||c(a)}),d):e.call(a,b,c,d)}),"function"==typeof a.onclick&&(b=a.onclick,a.addEventListener("click",function(a){b(a)},!1),a.onclick=null))}FastClick.prototype.deviceIsAndroid=navigator.userAgent.indexOf("Android")>0,FastClick.prototype.deviceIsIOS=/iP(ad|hone|od)/.test(navigator.userAgent),FastClick.prototype.deviceIsIOS4=FastClick.prototype.deviceIsIOS&&/OS 4_\d(_\d)?/.test(navigator.userAgent),FastClick.prototype.deviceIsIOSWithBadTarget=FastClick.prototype.deviceIsIOS&&/OS ([6-9]|\d{2})_\d/.test(navigator.userAgent),FastClick.prototype.needsClick=function(a){"use strict";switch(a.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(a.disabled)return!0;break;case"input":if(this.deviceIsIOS&&"file"===a.type||a.disabled)return!0;break;case"label":case"video":return!0}return/\bneedsclick\b/.test(a.className)},FastClick.prototype.needsFocus=function(a){"use strict";switch(a.nodeName.toLowerCase()){case"textarea":return!0;case"select":return!this.deviceIsAndroid;case"input":switch(a.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return!1}return!a.disabled&&!a.readOnly;default:return/\bneedsfocus\b/.test(a.className)}},FastClick.prototype.sendClick=function(a,b){"use strict";var c,d;document.activeElement&&document.activeElement!==a&&document.activeElement.blur(),d=b.changedTouches[0],c=document.createEvent("MouseEvents"),c.initMouseEvent(this.determineEventType(a),!0,!0,window,1,d.screenX,d.screenY,d.clientX,d.clientY,!1,!1,!1,!1,0,null),c.forwardedTouchEvent=!0,a.dispatchEvent(c)},FastClick.prototype.determineEventType=function(a){"use strict";return this.deviceIsAndroid&&"select"===a.tagName.toLowerCase()?"mousedown":"click"},FastClick.prototype.focus=function(a){"use strict";var b;this.deviceIsIOS&&a.setSelectionRange&&0!==a.type.indexOf("date")&&"time"!==a.type?(b=a.value.length,a.setSelectionRange(b,b)):a.focus()},FastClick.prototype.updateScrollParent=function(a){"use strict";var b,c;if(b=a.fastClickScrollParent,!b||!b.contains(a)){c=a;do{if(c.scrollHeight>c.offsetHeight){b=c,a.fastClickScrollParent=c;break}c=c.parentElement}while(c)}b&&(b.fastClickLastScrollTop=b.scrollTop)},FastClick.prototype.getTargetElementFromEventTarget=function(a){"use strict";return a.nodeType===Node.TEXT_NODE?a.parentNode:a},FastClick.prototype.onTouchStart=function(a){"use strict";var b,c,d;if(a.targetTouches.length>1)return!0;if(b=this.getTargetElementFromEventTarget(a.target),c=a.targetTouches[0],this.deviceIsIOS){if(d=window.getSelection(),d.rangeCount&&!d.isCollapsed)return!0;if(!this.deviceIsIOS4){if(c.identifier===this.lastTouchIdentifier)return a.preventDefault(),!1;this.lastTouchIdentifier=c.identifier,this.updateScrollParent(b)}}return this.trackingClick=!0,this.trackingClickStart=a.timeStamp,this.targetElement=b,this.touchStartX=c.pageX,this.touchStartY=c.pageY,a.timeStamp-this.lastClickTime<200&&a.preventDefault(),!0},FastClick.prototype.touchHasMoved=function(a){"use strict";var b=a.changedTouches[0],c=this.touchBoundary;return Math.abs(b.pageX-this.touchStartX)>c||Math.abs(b.pageY-this.touchStartY)>c?!0:!1},FastClick.prototype.onTouchMove=function(a){"use strict";return this.trackingClick?((this.targetElement!==this.getTargetElementFromEventTarget(a.target)||this.touchHasMoved(a))&&(this.trackingClick=!1,this.targetElement=null),!0):!0},FastClick.prototype.findControl=function(a){"use strict";return void 0!==a.control?a.control:a.htmlFor?document.getElementById(a.htmlFor):a.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")},FastClick.prototype.onTouchEnd=function(a){"use strict";var b,c,d,e,f,g=this.targetElement;if(!this.trackingClick)return!0;if(a.timeStamp-this.lastClickTime<200)return this.cancelNextClick=!0,!0;if(this.cancelNextClick=!1,this.lastClickTime=a.timeStamp,c=this.trackingClickStart,this.trackingClick=!1,this.trackingClickStart=0,this.deviceIsIOSWithBadTarget&&(f=a.changedTouches[0],g=document.elementFromPoint(f.pageX-window.pageXOffset,f.pageY-window.pageYOffset)||g,g.fastClickScrollParent=this.targetElement.fastClickScrollParent),d=g.tagName.toLowerCase(),"label"===d){if(b=this.findControl(g)){if(this.focus(g),this.deviceIsAndroid)return!1;g=b}}else if(this.needsFocus(g))return a.timeStamp-c>100||this.deviceIsIOS&&window.top!==window&&"input"===d?(this.targetElement=null,!1):(this.focus(g),this.deviceIsIOS4&&"select"===d||(this.targetElement=null,a.preventDefault()),!1);return this.deviceIsIOS&&!this.deviceIsIOS4&&(e=g.fastClickScrollParent,e&&e.fastClickLastScrollTop!==e.scrollTop)?!0:(this.needsClick(g)||(a.preventDefault(),this.sendClick(g,a)),!1)},FastClick.prototype.onTouchCancel=function(){"use strict";this.trackingClick=!1,this.targetElement=null},FastClick.prototype.onMouse=function(a){"use strict";return this.targetElement?a.forwardedTouchEvent?!0:a.cancelable?!this.needsClick(this.targetElement)||this.cancelNextClick?(a.stopImmediatePropagation?a.stopImmediatePropagation():a.propagationStopped=!0,a.stopPropagation(),a.preventDefault(),!1):!0:!0:!0},FastClick.prototype.onClick=function(a){"use strict";var b;return this.trackingClick?(this.targetElement=null,this.trackingClick=!1,!0):"submit"===a.target.type&&0===a.detail?!0:(b=this.onMouse(a),b||(this.targetElement=null),b)},FastClick.prototype.destroy=function(){"use strict";var a=this.layer;this.deviceIsAndroid&&(a.removeEventListener("mouseover",this.onMouse,!0),a.removeEventListener("mousedown",this.onMouse,!0),a.removeEventListener("mouseup",this.onMouse,!0)),a.removeEventListener("click",this.onClick,!0),a.removeEventListener("touchstart",this.onTouchStart,!1),a.removeEventListener("touchmove",this.onTouchMove,!1),a.removeEventListener("touchend",this.onTouchEnd,!1),a.removeEventListener("touchcancel",this.onTouchCancel,!1)},FastClick.notNeeded=function(a){"use strict";var b,c;if("undefined"==typeof window.ontouchstart)return!0;if(c=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1]){if(!FastClick.prototype.deviceIsAndroid)return!0;if(b=document.querySelector("meta[name=viewport]")){if(-1!==b.content.indexOf("user-scalable=no"))return!0;if(c>31&&window.innerWidth<=window.screen.width)return!0}}return"none"===a.style.msTouchAction?!0:!1},FastClick.attach=function(a){"use strict";return new FastClick(a)},"undefined"!=typeof define&&define.amd?define(function(){"use strict";return FastClick}):"undefined"!=typeof module&&module.exports?(module.exports=FastClick.attach,module.exports.FastClick=FastClick):window.FastClick=FastClick;/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{a=decodeURIComponent(a.replace(g," "))}catch(b){return}try{return h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setDate(k.getDate()+j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0!==a.cookie(b)?(a.cookie(b,"",a.extend({},c,{expires:-1})),!0):!1}});/*!
 * jQuery JavaScript Library v2.0.3
 * http://jquery.com/
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 *
 * Copyright 2005, 2013 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2013-07-03T13:30Z
 */
!function(a,b){function c(a){var b=a.length,c=fb.type(a);return fb.isWindow(a)?!1:1===a.nodeType&&b?!0:"array"===c||"function"!==c&&(0===b||"number"==typeof b&&b>0&&b-1 in a)}function d(a){var b=ob[a]={};return fb.each(a.match(hb)||[],function(a,c){b[c]=!0}),b}function e(){Object.defineProperty(this.cache={},0,{get:function(){return{}}}),this.expando=fb.expando+Math.random()}function f(a,c,d){var e;if(d===b&&1===a.nodeType)if(e="data-"+c.replace(sb,"-$1").toLowerCase(),d=a.getAttribute(e),"string"==typeof d){try{d="true"===d?!0:"false"===d?!1:"null"===d?null:+d+""===d?+d:rb.test(d)?JSON.parse(d):d}catch(f){}pb.set(a,c,d)}else d=b;return d}function g(){return!0}function h(){return!1}function i(){try{return T.activeElement}catch(a){}}function j(a,b){for(;(a=a[b])&&1!==a.nodeType;);return a}function k(a,b,c){if(fb.isFunction(b))return fb.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return fb.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(Cb.test(b))return fb.filter(b,a,c);b=fb.filter(b,a)}return fb.grep(a,function(a){return bb.call(b,a)>=0!==c})}function l(a,b){return fb.nodeName(a,"table")&&fb.nodeName(1===b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function m(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function n(a){var b=Nb.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function o(a,b){for(var c=a.length,d=0;c>d;d++)qb.set(a[d],"globalEval",!b||qb.get(b[d],"globalEval"))}function p(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(qb.hasData(a)&&(f=qb.access(a),g=qb.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;d>c;c++)fb.event.add(b,e,j[e][c])}pb.hasData(a)&&(h=pb.access(a),i=fb.extend({},h),pb.set(b,i))}}function q(a,c){var d=a.getElementsByTagName?a.getElementsByTagName(c||"*"):a.querySelectorAll?a.querySelectorAll(c||"*"):[];return c===b||c&&fb.nodeName(a,c)?fb.merge([a],d):d}function r(a,b){var c=b.nodeName.toLowerCase();"input"===c&&Kb.test(a.type)?b.checked=a.checked:("input"===c||"textarea"===c)&&(b.defaultValue=a.defaultValue)}function s(a,b){if(b in a)return b;for(var c=b.charAt(0).toUpperCase()+b.slice(1),d=b,e=_b.length;e--;)if(b=_b[e]+c,b in a)return b;return d}function t(a,b){return a=b||a,"none"===fb.css(a,"display")||!fb.contains(a.ownerDocument,a)}function u(b){return a.getComputedStyle(b,null)}function v(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=qb.get(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&t(d)&&(f[g]=qb.access(d,"olddisplay",z(d.nodeName)))):f[g]||(e=t(d),(c&&"none"!==c||!e)&&qb.set(d,"olddisplay",e?c:fb.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}function w(a,b,c){var d=Ub.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function x(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=fb.css(a,c+$b[f],!0,e)),d?("content"===c&&(g-=fb.css(a,"padding"+$b[f],!0,e)),"margin"!==c&&(g-=fb.css(a,"border"+$b[f]+"Width",!0,e))):(g+=fb.css(a,"padding"+$b[f],!0,e),"padding"!==c&&(g+=fb.css(a,"border"+$b[f]+"Width",!0,e)));return g}function y(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=u(a),g=fb.support.boxSizing&&"border-box"===fb.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=Qb(a,b,f),(0>e||null==e)&&(e=a.style[b]),Vb.test(e))return e;d=g&&(fb.support.boxSizingReliable||e===a.style[b]),e=parseFloat(e)||0}return e+x(a,b,c||(g?"border":"content"),d,f)+"px"}function z(a){var b=T,c=Xb[a];return c||(c=A(a,b),"none"!==c&&c||(Rb=(Rb||fb("<iframe frameborder='0' width='0' height='0'/>").css("cssText","display:block !important")).appendTo(b.documentElement),b=(Rb[0].contentWindow||Rb[0].contentDocument).document,b.write("<!doctype html><html><body>"),b.close(),c=A(a,b),Rb.detach()),Xb[a]=c),c}function A(a,b){var c=fb(b.createElement(a)).appendTo(b.body),d=fb.css(c[0],"display");return c.remove(),d}function B(a,b,c,d){var e;if(fb.isArray(b))fb.each(b,function(b,e){c||bc.test(a)?d(a,e):B(a+"["+("object"==typeof e?b:"")+"]",e,c,d)});else if(c||"object"!==fb.type(b))d(a,b);else for(e in b)B(a+"["+e+"]",b[e],c,d)}function C(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(hb)||[];if(fb.isFunction(c))for(;d=f[e++];)"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function D(a,b,c,d){function e(h){var i;return f[h]=!0,fb.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||g||f[j]?g?!(i=j):void 0:(b.dataTypes.unshift(j),e(j),!1)}),i}var f={},g=a===sc;return e(b.dataTypes[0])||!f["*"]&&e("*")}function E(a,c){var d,e,f=fb.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((f[d]?a:e||(e={}))[d]=c[d]);return e&&fb.extend(!0,a,e),a}function F(a,c,d){for(var e,f,g,h,i=a.contents,j=a.dataTypes;"*"===j[0];)j.shift(),e===b&&(e=a.mimeType||c.getResponseHeader("Content-Type"));if(e)for(f in i)if(i[f]&&i[f].test(e)){j.unshift(f);break}if(j[0]in d)g=j[0];else{for(f in d){if(!j[0]||a.converters[f+" "+j[0]]){g=f;break}h||(h=f)}g=g||h}return g?(g!==j[0]&&j.unshift(g),d[g]):void 0}function G(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];for(f=k.shift();f;)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}function H(){return setTimeout(function(){Bc=b}),Bc=fb.now()}function I(a,b,c){for(var d,e=(Hc[b]||[]).concat(Hc["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function J(a,b,c){var d,e,f=0,g=Gc.length,h=fb.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=Bc||H(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:fb.extend({},b),opts:fb.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:Bc||H(),duration:c.duration,tweens:[],createTween:function(b,c){var d=fb.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;for(K(k,j.opts.specialEasing);g>f;f++)if(d=Gc[f].call(j,a,k,j.opts))return d;return fb.map(k,I,j),fb.isFunction(j.opts.start)&&j.opts.start.call(a,j),fb.fx.timer(fb.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}function K(a,b){var c,d,e,f,g;for(c in a)if(d=fb.camelCase(c),e=b[d],f=a[c],fb.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=fb.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function L(a,c,d){var e,f,g,h,i,j,k=this,l={},m=a.style,n=a.nodeType&&t(a),o=qb.get(a,"fxshow");d.queue||(i=fb._queueHooks(a,"fx"),null==i.unqueued&&(i.unqueued=0,j=i.empty.fire,i.empty.fire=function(){i.unqueued||j()}),i.unqueued++,k.always(function(){k.always(function(){i.unqueued--,fb.queue(a,"fx").length||i.empty.fire()})})),1===a.nodeType&&("height"in c||"width"in c)&&(d.overflow=[m.overflow,m.overflowX,m.overflowY],"inline"===fb.css(a,"display")&&"none"===fb.css(a,"float")&&(m.display="inline-block")),d.overflow&&(m.overflow="hidden",k.always(function(){m.overflow=d.overflow[0],m.overflowX=d.overflow[1],m.overflowY=d.overflow[2]}));for(e in c)if(f=c[e],Dc.exec(f)){if(delete c[e],g=g||"toggle"===f,f===(n?"hide":"show")){if("show"!==f||!o||o[e]===b)continue;n=!0}l[e]=o&&o[e]||fb.style(a,e)}if(!fb.isEmptyObject(l)){o?"hidden"in o&&(n=o.hidden):o=qb.access(a,"fxshow",{}),g&&(o.hidden=!n),n?fb(a).show():k.done(function(){fb(a).hide()}),k.done(function(){var b;qb.remove(a,"fxshow");for(b in l)fb.style(a,b,l[b])});for(e in l)h=I(n?o[e]:0,e,k),e in o||(o[e]=h.start,n&&(h.end=h.start,h.start="width"===e||"height"===e?1:0))}}function M(a,b,c,d,e){return new M.prototype.init(a,b,c,d,e)}function N(a,b){var c,d={height:a},e=0;for(b=b?1:0;4>e;e+=2-b)c=$b[e],d["margin"+c]=d["padding"+c]=a;return b&&(d.opacity=d.width=a),d}function O(a){return fb.isWindow(a)?a:9===a.nodeType&&a.defaultView}var P,Q,R=typeof b,S=a.location,T=a.document,U=T.documentElement,V=a.jQuery,W=a.$,X={},Y=[],Z="2.0.3",$=Y.concat,_=Y.push,ab=Y.slice,bb=Y.indexOf,cb=X.toString,db=X.hasOwnProperty,eb=Z.trim,fb=function(a,b){return new fb.fn.init(a,b,P)},gb=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,hb=/\S+/g,ib=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,jb=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,kb=/^-ms-/,lb=/-([\da-z])/gi,mb=function(a,b){return b.toUpperCase()},nb=function(){T.removeEventListener("DOMContentLoaded",nb,!1),a.removeEventListener("load",nb,!1),fb.ready()};fb.fn=fb.prototype={jquery:Z,constructor:fb,init:function(a,c,d){var e,f;if(!a)return this;if("string"==typeof a){if(e="<"===a.charAt(0)&&">"===a.charAt(a.length-1)&&a.length>=3?[null,a,null]:ib.exec(a),!e||!e[1]&&c)return!c||c.jquery?(c||d).find(a):this.constructor(c).find(a);if(e[1]){if(c=c instanceof fb?c[0]:c,fb.merge(this,fb.parseHTML(e[1],c&&c.nodeType?c.ownerDocument||c:T,!0)),jb.test(e[1])&&fb.isPlainObject(c))for(e in c)fb.isFunction(this[e])?this[e](c[e]):this.attr(e,c[e]);return this}return f=T.getElementById(e[2]),f&&f.parentNode&&(this.length=1,this[0]=f),this.context=T,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):fb.isFunction(a)?d.ready(a):(a.selector!==b&&(this.selector=a.selector,this.context=a.context),fb.makeArray(a,this))},selector:"",length:0,toArray:function(){return ab.call(this)},get:function(a){return null==a?this.toArray():0>a?this[this.length+a]:this[a]},pushStack:function(a){var b=fb.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a,b){return fb.each(this,a,b)},ready:function(a){return fb.ready.promise().done(a),this},slice:function(){return this.pushStack(ab.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},map:function(a){return this.pushStack(fb.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:_,sort:[].sort,splice:[].splice},fb.fn.init.prototype=fb.fn,fb.extend=fb.fn.extend=function(){var a,c,d,e,f,g,h=arguments[0]||{},i=1,j=arguments.length,k=!1;for("boolean"==typeof h&&(k=h,h=arguments[1]||{},i=2),"object"==typeof h||fb.isFunction(h)||(h={}),j===i&&(h=this,--i);j>i;i++)if(null!=(a=arguments[i]))for(c in a)d=h[c],e=a[c],h!==e&&(k&&e&&(fb.isPlainObject(e)||(f=fb.isArray(e)))?(f?(f=!1,g=d&&fb.isArray(d)?d:[]):g=d&&fb.isPlainObject(d)?d:{},h[c]=fb.extend(k,g,e)):e!==b&&(h[c]=e));return h},fb.extend({expando:"jQuery"+(Z+Math.random()).replace(/\D/g,""),noConflict:function(b){return a.$===fb&&(a.$=W),b&&a.jQuery===fb&&(a.jQuery=V),fb},isReady:!1,readyWait:1,holdReady:function(a){a?fb.readyWait++:fb.ready(!0)},ready:function(a){(a===!0?--fb.readyWait:fb.isReady)||(fb.isReady=!0,a!==!0&&--fb.readyWait>0||(Q.resolveWith(T,[fb]),fb.fn.trigger&&fb(T).trigger("ready").off("ready")))},isFunction:function(a){return"function"===fb.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return null==a?String(a):"object"==typeof a||"function"==typeof a?X[cb.call(a)]||"object":typeof a},isPlainObject:function(a){if("object"!==fb.type(a)||a.nodeType||fb.isWindow(a))return!1;try{if(a.constructor&&!db.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(b){return!1}return!0},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},error:function(a){throw new Error(a)},parseHTML:function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||T;var d=jb.exec(a),e=!c&&[];return d?[b.createElement(d[1])]:(d=fb.buildFragment([a],b,e),e&&fb(e).remove(),fb.merge([],d.childNodes))},parseJSON:JSON.parse,parseXML:function(a){var c,d;if(!a||"string"!=typeof a)return null;try{d=new DOMParser,c=d.parseFromString(a,"text/xml")}catch(e){c=b}return(!c||c.getElementsByTagName("parsererror").length)&&fb.error("Invalid XML: "+a),c},noop:function(){},globalEval:function(a){var b,c=eval;a=fb.trim(a),a&&(1===a.indexOf("use strict")?(b=T.createElement("script"),b.text=a,T.head.appendChild(b).parentNode.removeChild(b)):c(a))},camelCase:function(a){return a.replace(kb,"ms-").replace(lb,mb)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b,d){var e,f=0,g=a.length,h=c(a);if(d){if(h)for(;g>f&&(e=b.apply(a[f],d),e!==!1);f++);else for(f in a)if(e=b.apply(a[f],d),e===!1)break}else if(h)for(;g>f&&(e=b.call(a[f],f,a[f]),e!==!1);f++);else for(f in a)if(e=b.call(a[f],f,a[f]),e===!1)break;return a},trim:function(a){return null==a?"":eb.call(a)},makeArray:function(a,b){var d=b||[];return null!=a&&(c(Object(a))?fb.merge(d,"string"==typeof a?[a]:a):_.call(d,a)),d},inArray:function(a,b,c){return null==b?-1:bb.call(b,a,c)},merge:function(a,c){var d=c.length,e=a.length,f=0;if("number"==typeof d)for(;d>f;f++)a[e++]=c[f];else for(;c[f]!==b;)a[e++]=c[f++];return a.length=e,a},grep:function(a,b,c){var d,e=[],f=0,g=a.length;for(c=!!c;g>f;f++)d=!!b(a[f],f),c!==d&&e.push(a[f]);return e},map:function(a,b,d){var e,f=0,g=a.length,h=c(a),i=[];if(h)for(;g>f;f++)e=b(a[f],f,d),null!=e&&(i[i.length]=e);else for(f in a)e=b(a[f],f,d),null!=e&&(i[i.length]=e);return $.apply([],i)},guid:1,proxy:function(a,c){var d,e,f;return"string"==typeof c&&(d=a[c],c=a,a=d),fb.isFunction(a)?(e=ab.call(arguments,2),f=function(){return a.apply(c||this,e.concat(ab.call(arguments)))},f.guid=a.guid=a.guid||fb.guid++,f):b},access:function(a,c,d,e,f,g,h){var i=0,j=a.length,k=null==d;if("object"===fb.type(d)){f=!0;for(i in d)fb.access(a,c,i,d[i],!0,g,h)}else if(e!==b&&(f=!0,fb.isFunction(e)||(h=!0),k&&(h?(c.call(a,e),c=null):(k=c,c=function(a,b,c){return k.call(fb(a),c)})),c))for(;j>i;i++)c(a[i],d,h?e:e.call(a[i],i,c(a[i],d)));return f?a:k?c.call(a):j?c(a[0],d):g},now:Date.now,swap:function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e}}),fb.ready.promise=function(b){return Q||(Q=fb.Deferred(),"complete"===T.readyState?setTimeout(fb.ready):(T.addEventListener("DOMContentLoaded",nb,!1),a.addEventListener("load",nb,!1))),Q.promise(b)},fb.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(a,b){X["[object "+b+"]"]=b.toLowerCase()}),P=fb(T),/*!
 * Sizzle CSS Selector Engine v1.9.4-pre
 * http://sizzlejs.com/
 *
 * Copyright 2013 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2013-06-03
 */
function(a,b){function c(a,b,c,d){var e,f,g,h,i,j,k,l,o,p;if((b?b.ownerDocument||b:O)!==G&&F(b),b=b||G,c=c||[],!a||"string"!=typeof a)return c;if(1!==(h=b.nodeType)&&9!==h)return[];if(I&&!d){if(e=tb.exec(a))if(g=e[1]){if(9===h){if(f=b.getElementById(g),!f||!f.parentNode)return c;if(f.id===g)return c.push(f),c}else if(b.ownerDocument&&(f=b.ownerDocument.getElementById(g))&&M(b,f)&&f.id===g)return c.push(f),c}else{if(e[2])return ab.apply(c,b.getElementsByTagName(a)),c;if((g=e[3])&&x.getElementsByClassName&&b.getElementsByClassName)return ab.apply(c,b.getElementsByClassName(g)),c}if(x.qsa&&(!J||!J.test(a))){if(l=k=N,o=b,p=9===h&&a,1===h&&"object"!==b.nodeName.toLowerCase()){for(j=m(a),(k=b.getAttribute("id"))?l=k.replace(wb,"\\$&"):b.setAttribute("id",l),l="[id='"+l+"'] ",i=j.length;i--;)j[i]=l+n(j[i]);o=nb.test(a)&&b.parentNode||b,p=j.join(",")}if(p)try{return ab.apply(c,o.querySelectorAll(p)),c}catch(q){}finally{k||b.removeAttribute("id")}}}return v(a.replace(kb,"$1"),b,c,d)}function d(){function a(c,d){return b.push(c+=" ")>z.cacheLength&&delete a[b.shift()],a[c]=d}var b=[];return a}function e(a){return a[N]=!0,a}function f(a){var b=G.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function g(a,b){for(var c=a.split("|"),d=a.length;d--;)z.attrHandle[c[d]]=b}function h(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||X)-(~a.sourceIndex||X);if(d)return d;if(c)for(;c=c.nextSibling;)if(c===b)return-1;return a?1:-1}function i(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function j(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function k(a){return e(function(b){return b=+b,e(function(c,d){for(var e,f=a([],c.length,b),g=f.length;g--;)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function l(){}function m(a,b){var d,e,f,g,h,i,j,k=S[a+" "];if(k)return b?0:k.slice(0);for(h=a,i=[],j=z.preFilter;h;){(!d||(e=lb.exec(h)))&&(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),d=!1,(e=mb.exec(h))&&(d=e.shift(),f.push({value:d,type:e[0].replace(kb," ")}),h=h.slice(d.length));for(g in z.filter)!(e=rb[g].exec(h))||j[g]&&!(e=j[g](e))||(d=e.shift(),f.push({value:d,type:g,matches:e}),h=h.slice(d.length));if(!d)break}return b?h.length:h?c.error(a):S(a,i).slice(0)}function n(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function o(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=Q++;return b.first?function(b,c,f){for(;b=b[d];)if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j,k=P+" "+f;if(g){for(;b=b[d];)if((1===b.nodeType||e)&&a(b,c,g))return!0}else for(;b=b[d];)if(1===b.nodeType||e)if(j=b[N]||(b[N]={}),(i=j[d])&&i[0]===k){if((h=i[1])===!0||h===y)return h===!0}else if(i=j[d]=[k],i[1]=a(b,c,g)||y,i[1]===!0)return!0}}function p(a){return a.length>1?function(b,c,d){for(var e=a.length;e--;)if(!a[e](b,c,d))return!1;return!0}:a[0]}function q(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(!c||c(f,d,e))&&(g.push(f),j&&b.push(h));return g}function r(a,b,c,d,f,g){return d&&!d[N]&&(d=r(d)),f&&!f[N]&&(f=r(f,g)),e(function(e,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=e||u(b||"*",h.nodeType?[h]:h,[]),r=!a||!e&&b?p:q(p,m,a,h,i),s=c?f||(e?a:o||d)?[]:g:r;if(c&&c(r,s,h,i),d)for(j=q(s,n),d(j,[],h,i),k=j.length;k--;)(l=j[k])&&(s[n[k]]=!(r[n[k]]=l));if(e){if(f||a){if(f){for(j=[],k=s.length;k--;)(l=s[k])&&j.push(r[k]=l);f(null,s=[],j,i)}for(k=s.length;k--;)(l=s[k])&&(j=f?cb.call(e,l):m[k])>-1&&(e[j]=!(g[j]=l))}}else s=q(s===g?s.splice(o,s.length):s),f?f(null,g,s,i):ab.apply(g,s)})}function s(a){for(var b,c,d,e=a.length,f=z.relative[a[0].type],g=f||z.relative[" "],h=f?1:0,i=o(function(a){return a===b},g,!0),j=o(function(a){return cb.call(b,a)>-1},g,!0),k=[function(a,c,d){return!f&&(d||c!==D)||((b=c).nodeType?i(a,c,d):j(a,c,d))}];e>h;h++)if(c=z.relative[a[h].type])k=[o(p(k),c)];else{if(c=z.filter[a[h].type].apply(null,a[h].matches),c[N]){for(d=++h;e>d&&!z.relative[a[d].type];d++);return r(h>1&&p(k),h>1&&n(a.slice(0,h-1).concat({value:" "===a[h-2].type?"*":""})).replace(kb,"$1"),c,d>h&&s(a.slice(h,d)),e>d&&s(a=a.slice(d)),e>d&&n(a))}k.push(c)}return p(k)}function t(a,b){var d=0,f=b.length>0,g=a.length>0,h=function(e,h,i,j,k){var l,m,n,o=[],p=0,r="0",s=e&&[],t=null!=k,u=D,v=e||g&&z.find.TAG("*",k&&h.parentNode||h),w=P+=null==u?1:Math.random()||.1;for(t&&(D=h!==G&&h,y=d);null!=(l=v[r]);r++){if(g&&l){for(m=0;n=a[m++];)if(n(l,h,i)){j.push(l);break}t&&(P=w,y=++d)}f&&((l=!n&&l)&&p--,e&&s.push(l))}if(p+=r,f&&r!==p){for(m=0;n=b[m++];)n(s,o,h,i);if(e){if(p>0)for(;r--;)s[r]||o[r]||(o[r]=$.call(j));o=q(o)}ab.apply(j,o),t&&!e&&o.length>0&&p+b.length>1&&c.uniqueSort(j)}return t&&(P=w,D=u),s};return f?e(h):h}function u(a,b,d){for(var e=0,f=b.length;f>e;e++)c(a,b[e],d);return d}function v(a,b,c,d){var e,f,g,h,i,j=m(a);if(!d&&1===j.length){if(f=j[0]=j[0].slice(0),f.length>2&&"ID"===(g=f[0]).type&&x.getById&&9===b.nodeType&&I&&z.relative[f[1].type]){if(b=(z.find.ID(g.matches[0].replace(xb,yb),b)||[])[0],!b)return c;a=a.slice(f.shift().value.length)}for(e=rb.needsContext.test(a)?0:f.length;e--&&(g=f[e],!z.relative[h=g.type]);)if((i=z.find[h])&&(d=i(g.matches[0].replace(xb,yb),nb.test(f[0].type)&&b.parentNode||b))){if(f.splice(e,1),a=d.length&&n(f),!a)return ab.apply(c,d),c;break}}return C(a,j)(d,b,!I,c,nb.test(a)),c}var w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N="sizzle"+-new Date,O=a.document,P=0,Q=0,R=d(),S=d(),T=d(),U=!1,V=function(a,b){return a===b?(U=!0,0):0},W=typeof b,X=1<<31,Y={}.hasOwnProperty,Z=[],$=Z.pop,_=Z.push,ab=Z.push,bb=Z.slice,cb=Z.indexOf||function(a){for(var b=0,c=this.length;c>b;b++)if(this[b]===a)return b;return-1},db="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",eb="[\\x20\\t\\r\\n\\f]",gb="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",hb=gb.replace("w","w#"),ib="\\["+eb+"*("+gb+")"+eb+"*(?:([*^$|!~]?=)"+eb+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+hb+")|)|)"+eb+"*\\]",jb=":("+gb+")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|"+ib.replace(3,8)+")*)|.*)\\)|)",kb=new RegExp("^"+eb+"+|((?:^|[^\\\\])(?:\\\\.)*)"+eb+"+$","g"),lb=new RegExp("^"+eb+"*,"+eb+"*"),mb=new RegExp("^"+eb+"*([>+~]|"+eb+")"+eb+"*"),nb=new RegExp(eb+"*[+~]"),ob=new RegExp("="+eb+"*([^\\]'\"]*)"+eb+"*\\]","g"),pb=new RegExp(jb),qb=new RegExp("^"+hb+"$"),rb={ID:new RegExp("^#("+gb+")"),CLASS:new RegExp("^\\.("+gb+")"),TAG:new RegExp("^("+gb.replace("w","w*")+")"),ATTR:new RegExp("^"+ib),PSEUDO:new RegExp("^"+jb),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+eb+"*(even|odd|(([+-]|)(\\d*)n|)"+eb+"*(?:([+-]|)"+eb+"*(\\d+)|))"+eb+"*\\)|)","i"),bool:new RegExp("^(?:"+db+")$","i"),needsContext:new RegExp("^"+eb+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+eb+"*((?:-\\d)?\\d*)"+eb+"*\\)|)(?=[^-]|$)","i")},sb=/^[^{]+\{\s*\[native \w/,tb=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,ub=/^(?:input|select|textarea|button)$/i,vb=/^h\d$/i,wb=/'|\\/g,xb=new RegExp("\\\\([\\da-f]{1,6}"+eb+"?|("+eb+")|.)","ig"),yb=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)};try{ab.apply(Z=bb.call(O.childNodes),O.childNodes),Z[O.childNodes.length].nodeType}catch(zb){ab={apply:Z.length?function(a,b){_.apply(a,bb.call(b))}:function(a,b){for(var c=a.length,d=0;a[c++]=b[d++];);a.length=c-1}}}B=c.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},x=c.support={},F=c.setDocument=function(a){var b=a?a.ownerDocument||a:O,c=b.defaultView;return b!==G&&9===b.nodeType&&b.documentElement?(G=b,H=b.documentElement,I=!B(b),c&&c.attachEvent&&c!==c.top&&c.attachEvent("onbeforeunload",function(){F()}),x.attributes=f(function(a){return a.className="i",!a.getAttribute("className")}),x.getElementsByTagName=f(function(a){return a.appendChild(b.createComment("")),!a.getElementsByTagName("*").length}),x.getElementsByClassName=f(function(a){return a.innerHTML="<div class='a'></div><div class='a i'></div>",a.firstChild.className="i",2===a.getElementsByClassName("i").length}),x.getById=f(function(a){return H.appendChild(a).id=N,!b.getElementsByName||!b.getElementsByName(N).length}),x.getById?(z.find.ID=function(a,b){if(typeof b.getElementById!==W&&I){var c=b.getElementById(a);return c&&c.parentNode?[c]:[]}},z.filter.ID=function(a){var b=a.replace(xb,yb);return function(a){return a.getAttribute("id")===b}}):(delete z.find.ID,z.filter.ID=function(a){var b=a.replace(xb,yb);return function(a){var c=typeof a.getAttributeNode!==W&&a.getAttributeNode("id");return c&&c.value===b}}),z.find.TAG=x.getElementsByTagName?function(a,b){return typeof b.getElementsByTagName!==W?b.getElementsByTagName(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){for(;c=f[e++];)1===c.nodeType&&d.push(c);return d}return f},z.find.CLASS=x.getElementsByClassName&&function(a,b){return typeof b.getElementsByClassName!==W&&I?b.getElementsByClassName(a):void 0},K=[],J=[],(x.qsa=sb.test(b.querySelectorAll))&&(f(function(a){a.innerHTML="<select><option selected=''></option></select>",a.querySelectorAll("[selected]").length||J.push("\\["+eb+"*(?:value|"+db+")"),a.querySelectorAll(":checked").length||J.push(":checked")}),f(function(a){var c=b.createElement("input");c.setAttribute("type","hidden"),a.appendChild(c).setAttribute("t",""),a.querySelectorAll("[t^='']").length&&J.push("[*^$]="+eb+"*(?:''|\"\")"),a.querySelectorAll(":enabled").length||J.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),J.push(",.*:")})),(x.matchesSelector=sb.test(L=H.webkitMatchesSelector||H.mozMatchesSelector||H.oMatchesSelector||H.msMatchesSelector))&&f(function(a){x.disconnectedMatch=L.call(a,"div"),L.call(a,"[s!='']:x"),K.push("!=",jb)}),J=J.length&&new RegExp(J.join("|")),K=K.length&&new RegExp(K.join("|")),M=sb.test(H.contains)||H.compareDocumentPosition?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)for(;b=b.parentNode;)if(b===a)return!0;return!1},V=H.compareDocumentPosition?function(a,c){if(a===c)return U=!0,0;var d=c.compareDocumentPosition&&a.compareDocumentPosition&&a.compareDocumentPosition(c);return d?1&d||!x.sortDetached&&c.compareDocumentPosition(a)===d?a===b||M(O,a)?-1:c===b||M(O,c)?1:E?cb.call(E,a)-cb.call(E,c):0:4&d?-1:1:a.compareDocumentPosition?-1:1}:function(a,c){var d,e=0,f=a.parentNode,g=c.parentNode,i=[a],j=[c];if(a===c)return U=!0,0;if(!f||!g)return a===b?-1:c===b?1:f?-1:g?1:E?cb.call(E,a)-cb.call(E,c):0;if(f===g)return h(a,c);for(d=a;d=d.parentNode;)i.unshift(d);for(d=c;d=d.parentNode;)j.unshift(d);for(;i[e]===j[e];)e++;return e?h(i[e],j[e]):i[e]===O?-1:j[e]===O?1:0},b):G},c.matches=function(a,b){return c(a,null,null,b)},c.matchesSelector=function(a,b){if((a.ownerDocument||a)!==G&&F(a),b=b.replace(ob,"='$1']"),!(!x.matchesSelector||!I||K&&K.test(b)||J&&J.test(b)))try{var d=L.call(a,b);if(d||x.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return c(b,G,null,[a]).length>0},c.contains=function(a,b){return(a.ownerDocument||a)!==G&&F(a),M(a,b)},c.attr=function(a,c){(a.ownerDocument||a)!==G&&F(a);var d=z.attrHandle[c.toLowerCase()],e=d&&Y.call(z.attrHandle,c.toLowerCase())?d(a,c,!I):b;return e===b?x.attributes||!I?a.getAttribute(c):(e=a.getAttributeNode(c))&&e.specified?e.value:null:e},c.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},c.uniqueSort=function(a){var b,c=[],d=0,e=0;if(U=!x.detectDuplicates,E=!x.sortStable&&a.slice(0),a.sort(V),U){for(;b=a[e++];)b===a[e]&&(d=c.push(e));for(;d--;)a.splice(c[d],1)}return a},A=c.getText=function(a){var b,c="",d=0,e=a.nodeType;if(e){if(1===e||9===e||11===e){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=A(a)}else if(3===e||4===e)return a.nodeValue}else for(;b=a[d];d++)c+=A(b);return c},z=c.selectors={cacheLength:50,createPseudo:e,match:rb,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(xb,yb),a[3]=(a[4]||a[5]||"").replace(xb,yb),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||c.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&c.error(a[0]),a},PSEUDO:function(a){var c,d=!a[5]&&a[2];return rb.CHILD.test(a[0])?null:(a[3]&&a[4]!==b?a[2]=a[4]:d&&pb.test(d)&&(c=m(d,!0))&&(c=d.indexOf(")",d.length-c)-d.length)&&(a[0]=a[0].slice(0,c),a[2]=d.slice(0,c)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(xb,yb).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=R[a+" "];return b||(b=new RegExp("(^|"+eb+")"+a+"("+eb+"|$)"))&&R(a,function(a){return b.test("string"==typeof a.className&&a.className||typeof a.getAttribute!==W&&a.getAttribute("class")||"")})},ATTR:function(a,b,d){return function(e){var f=c.attr(e,a);return null==f?"!="===b:b?(f+="","="===b?f===d:"!="===b?f!==d:"^="===b?d&&0===f.indexOf(d):"*="===b?d&&f.indexOf(d)>-1:"$="===b?d&&f.slice(-d.length)===d:"~="===b?(" "+f+" ").indexOf(d)>-1:"|="===b?f===d||f.slice(0,d.length+1)===d+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h;if(q){if(f){for(;p;){for(l=b;l=l[p];)if(h?l.nodeName.toLowerCase()===r:1===l.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){for(k=q[N]||(q[N]={}),j=k[a]||[],n=j[0]===P&&j[1],m=j[0]===P&&j[2],l=n&&q.childNodes[n];l=++n&&l&&l[p]||(m=n=0)||o.pop();)if(1===l.nodeType&&++m&&l===b){k[a]=[P,n,m];break}}else if(s&&(j=(b[N]||(b[N]={}))[a])&&j[0]===P)m=j[1];else for(;(l=++n&&l&&l[p]||(m=n=0)||o.pop())&&((h?l.nodeName.toLowerCase()!==r:1!==l.nodeType)||!++m||(s&&((l[N]||(l[N]={}))[a]=[P,m]),l!==b)););return m-=e,m===d||m%d===0&&m/d>=0}}},PSEUDO:function(a,b){var d,f=z.pseudos[a]||z.setFilters[a.toLowerCase()]||c.error("unsupported pseudo: "+a);return f[N]?f(b):f.length>1?(d=[a,a,"",b],z.setFilters.hasOwnProperty(a.toLowerCase())?e(function(a,c){for(var d,e=f(a,b),g=e.length;g--;)d=cb.call(a,e[g]),a[d]=!(c[d]=e[g])}):function(a){return f(a,0,d)}):f}},pseudos:{not:e(function(a){var b=[],c=[],d=C(a.replace(kb,"$1"));return d[N]?e(function(a,b,c,e){for(var f,g=d(a,null,e,[]),h=a.length;h--;)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),!c.pop()}}),has:e(function(a){return function(b){return c(a,b).length>0}}),contains:e(function(a){return function(b){return(b.textContent||b.innerText||A(b)).indexOf(a)>-1}}),lang:e(function(a){return qb.test(a||"")||c.error("unsupported lang: "+a),a=a.replace(xb,yb).toLowerCase(),function(b){var c;do if(c=I?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===H},focus:function(a){return a===G.activeElement&&(!G.hasFocus||G.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeName>"@"||3===a.nodeType||4===a.nodeType)return!1;return!0},parent:function(a){return!z.pseudos.empty(a)},header:function(a){return vb.test(a.nodeName)},input:function(a){return ub.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||b.toLowerCase()===a.type)},first:k(function(){return[0]}),last:k(function(a,b){return[b-1]}),eq:k(function(a,b,c){return[0>c?c+b:c]}),even:k(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:k(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:k(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:k(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},z.pseudos.nth=z.pseudos.eq;for(w in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})z.pseudos[w]=i(w);for(w in{submit:!0,reset:!0})z.pseudos[w]=j(w);l.prototype=z.filters=z.pseudos,z.setFilters=new l,C=c.compile=function(a,b){var c,d=[],e=[],f=T[a+" "];if(!f){for(b||(b=m(a)),c=b.length;c--;)f=s(b[c]),f[N]?d.push(f):e.push(f);f=T(a,t(e,d))}return f},x.sortStable=N.split("").sort(V).join("")===N,x.detectDuplicates=U,F(),x.sortDetached=f(function(a){return 1&a.compareDocumentPosition(G.createElement("div"))}),f(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||g("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),x.attributes&&f(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||g("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),f(function(a){return null==a.getAttribute("disabled")})||g(db,function(a,b,c){var d;return c?void 0:(d=a.getAttributeNode(b))&&d.specified?d.value:a[b]===!0?b.toLowerCase():null}),fb.find=c,fb.expr=c.selectors,fb.expr[":"]=fb.expr.pseudos,fb.unique=c.uniqueSort,fb.text=c.getText,fb.isXMLDoc=c.isXML,fb.contains=c.contains}(a);var ob={};fb.Callbacks=function(a){a="string"==typeof a?ob[a]||d(a):fb.extend({},a);var c,e,f,g,h,i,j=[],k=!a.once&&[],l=function(b){for(c=a.memory&&b,e=!0,i=g||0,g=0,h=j.length,f=!0;j&&h>i;i++)if(j[i].apply(b[0],b[1])===!1&&a.stopOnFalse){c=!1;break}f=!1,j&&(k?k.length&&l(k.shift()):c?j=[]:m.disable())},m={add:function(){if(j){var b=j.length;!function d(b){fb.each(b,function(b,c){var e=fb.type(c);"function"===e?a.unique&&m.has(c)||j.push(c):c&&c.length&&"string"!==e&&d(c)})}(arguments),f?h=j.length:c&&(g=b,l(c))}return this},remove:function(){return j&&fb.each(arguments,function(a,b){for(var c;(c=fb.inArray(b,j,c))>-1;)j.splice(c,1),f&&(h>=c&&h--,i>=c&&i--)}),this},has:function(a){return a?fb.inArray(a,j)>-1:!(!j||!j.length)},empty:function(){return j=[],h=0,this},disable:function(){return j=k=c=b,this},disabled:function(){return!j},lock:function(){return k=b,c||m.disable(),this},locked:function(){return!k},fireWith:function(a,b){return!j||e&&!k||(b=b||[],b=[a,b.slice?b.slice():b],f?k.push(b):l(b)),this},fire:function(){return m.fireWith(this,arguments),this},fired:function(){return!!e}};return m},fb.extend({Deferred:function(a){var b=[["resolve","done",fb.Callbacks("once memory"),"resolved"],["reject","fail",fb.Callbacks("once memory"),"rejected"],["notify","progress",fb.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return fb.Deferred(function(c){fb.each(b,function(b,f){var g=f[0],h=fb.isFunction(a[b])&&a[b];e[f[1]](function(){var a=h&&h.apply(this,arguments);a&&fb.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[g+"With"](this===d?c.promise():this,h?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?fb.extend(a,d):d}},e={};return d.pipe=d.then,fb.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b,c,d,e=0,f=ab.call(arguments),g=f.length,h=1!==g||a&&fb.isFunction(a.promise)?g:0,i=1===h?a:fb.Deferred(),j=function(a,c,d){return function(e){c[a]=this,d[a]=arguments.length>1?ab.call(arguments):e,d===b?i.notifyWith(c,d):--h||i.resolveWith(c,d)}};if(g>1)for(b=new Array(g),c=new Array(g),d=new Array(g);g>e;e++)f[e]&&fb.isFunction(f[e].promise)?f[e].promise().done(j(e,d,f)).fail(i.reject).progress(j(e,c,b)):--h;return h||i.resolveWith(d,f),i.promise()}}),fb.support=function(b){var c=T.createElement("input"),d=T.createDocumentFragment(),e=T.createElement("div"),f=T.createElement("select"),g=f.appendChild(T.createElement("option"));return c.type?(c.type="checkbox",b.checkOn=""!==c.value,b.optSelected=g.selected,b.reliableMarginRight=!0,b.boxSizingReliable=!0,b.pixelPosition=!1,c.checked=!0,b.noCloneChecked=c.cloneNode(!0).checked,f.disabled=!0,b.optDisabled=!g.disabled,c=T.createElement("input"),c.value="t",c.type="radio",b.radioValue="t"===c.value,c.setAttribute("checked","t"),c.setAttribute("name","t"),d.appendChild(c),b.checkClone=d.cloneNode(!0).cloneNode(!0).lastChild.checked,b.focusinBubbles="onfocusin"in a,e.style.backgroundClip="content-box",e.cloneNode(!0).style.backgroundClip="",b.clearCloneStyle="content-box"===e.style.backgroundClip,fb(function(){var c,d,f="padding:0;margin:0;border:0;display:block;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box",g=T.getElementsByTagName("body")[0];g&&(c=T.createElement("div"),c.style.cssText="border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px",g.appendChild(c).appendChild(e),e.innerHTML="",e.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%",fb.swap(g,null!=g.style.zoom?{zoom:1}:{},function(){b.boxSizing=4===e.offsetWidth}),a.getComputedStyle&&(b.pixelPosition="1%"!==(a.getComputedStyle(e,null)||{}).top,b.boxSizingReliable="4px"===(a.getComputedStyle(e,null)||{width:"4px"}).width,d=e.appendChild(T.createElement("div")),d.style.cssText=e.style.cssText=f,d.style.marginRight=d.style.width="0",e.style.width="1px",b.reliableMarginRight=!parseFloat((a.getComputedStyle(d,null)||{}).marginRight)),g.removeChild(c))}),b):b}({});var pb,qb,rb=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,sb=/([A-Z])/g;e.uid=1,e.accepts=function(a){return a.nodeType?1===a.nodeType||9===a.nodeType:!0},e.prototype={key:function(a){if(!e.accepts(a))return 0;var b={},c=a[this.expando];if(!c){c=e.uid++;try{b[this.expando]={value:c},Object.defineProperties(a,b)}catch(d){b[this.expando]=c,fb.extend(a,b)}}return this.cache[c]||(this.cache[c]={}),c},set:function(a,b,c){var d,e=this.key(a),f=this.cache[e];if("string"==typeof b)f[b]=c;else if(fb.isEmptyObject(f))fb.extend(this.cache[e],b);else for(d in b)f[d]=b[d];return f},get:function(a,c){var d=this.cache[this.key(a)];return c===b?d:d[c]},access:function(a,c,d){var e;return c===b||c&&"string"==typeof c&&d===b?(e=this.get(a,c),e!==b?e:this.get(a,fb.camelCase(c))):(this.set(a,c,d),d!==b?d:c)},remove:function(a,c){var d,e,f,g=this.key(a),h=this.cache[g];if(c===b)this.cache[g]={};else{fb.isArray(c)?e=c.concat(c.map(fb.camelCase)):(f=fb.camelCase(c),c in h?e=[c,f]:(e=f,e=e in h?[e]:e.match(hb)||[])),d=e.length;for(;d--;)delete h[e[d]]}},hasData:function(a){return!fb.isEmptyObject(this.cache[a[this.expando]]||{})},discard:function(a){a[this.expando]&&delete this.cache[a[this.expando]]}},pb=new e,qb=new e,fb.extend({acceptData:e.accepts,hasData:function(a){return pb.hasData(a)||qb.hasData(a)},data:function(a,b,c){return pb.access(a,b,c)},removeData:function(a,b){pb.remove(a,b)},_data:function(a,b,c){return qb.access(a,b,c)},_removeData:function(a,b){qb.remove(a,b)}}),fb.fn.extend({data:function(a,c){var d,e,g=this[0],h=0,i=null;if(a===b){if(this.length&&(i=pb.get(g),1===g.nodeType&&!qb.get(g,"hasDataAttrs"))){for(d=g.attributes;h<d.length;h++)e=d[h].name,0===e.indexOf("data-")&&(e=fb.camelCase(e.slice(5)),f(g,e,i[e]));qb.set(g,"hasDataAttrs",!0)}return i}return"object"==typeof a?this.each(function(){pb.set(this,a)}):fb.access(this,function(c){var d,e=fb.camelCase(a);if(g&&c===b){if(d=pb.get(g,a),d!==b)return d;if(d=pb.get(g,e),d!==b)return d;if(d=f(g,e,b),d!==b)return d}else this.each(function(){var d=pb.get(this,e);pb.set(this,e,c),-1!==a.indexOf("-")&&d!==b&&pb.set(this,a,c)})},null,c,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){pb.remove(this,a)})}}),fb.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=qb.get(a,b),c&&(!d||fb.isArray(c)?d=qb.access(a,b,fb.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=fb.queue(a,b),d=c.length,e=c.shift(),f=fb._queueHooks(a,b),g=function(){fb.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return qb.get(a,c)||qb.access(a,c,{empty:fb.Callbacks("once memory").add(function(){qb.remove(a,[b+"queue",c])})})}}),fb.fn.extend({queue:function(a,c){var d=2;return"string"!=typeof a&&(c=a,a="fx",d--),arguments.length<d?fb.queue(this[0],a):c===b?this:this.each(function(){var b=fb.queue(this,a,c);fb._queueHooks(this,a),"fx"===a&&"inprogress"!==b[0]&&fb.dequeue(this,a)})},dequeue:function(a){return this.each(function(){fb.dequeue(this,a)})},delay:function(a,b){return a=fb.fx?fb.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){var d,e=1,f=fb.Deferred(),g=this,h=this.length,i=function(){--e||f.resolveWith(g,[g])};for("string"!=typeof a&&(c=a,a=b),a=a||"fx";h--;)d=qb.get(g[h],a+"queueHooks"),d&&d.empty&&(e++,d.empty.add(i));return i(),f.promise(c)}});var tb,ub,vb=/[\t\r\n\f]/g,wb=/\r/g,xb=/^(?:input|select|textarea|button)$/i;fb.fn.extend({attr:function(a,b){return fb.access(this,fb.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){fb.removeAttr(this,a)})},prop:function(a,b){return fb.access(this,fb.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[fb.propFix[a]||a]})},addClass:function(a){var b,c,d,e,f,g=0,h=this.length,i="string"==typeof a&&a;if(fb.isFunction(a))return this.each(function(b){fb(this).addClass(a.call(this,b,this.className))});if(i)for(b=(a||"").match(hb)||[];h>g;g++)if(c=this[g],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(vb," "):" ")){for(f=0;e=b[f++];)d.indexOf(" "+e+" ")<0&&(d+=e+" ");c.className=fb.trim(d)}return this},removeClass:function(a){var b,c,d,e,f,g=0,h=this.length,i=0===arguments.length||"string"==typeof a&&a;if(fb.isFunction(a))return this.each(function(b){fb(this).removeClass(a.call(this,b,this.className))});if(i)for(b=(a||"").match(hb)||[];h>g;g++)if(c=this[g],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(vb," "):"")){for(f=0;e=b[f++];)for(;d.indexOf(" "+e+" ")>=0;)d=d.replace(" "+e+" "," ");c.className=a?fb.trim(d):""}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):this.each(fb.isFunction(a)?function(c){fb(this).toggleClass(a.call(this,c,this.className,b),b)}:function(){if("string"===c)for(var b,d=0,e=fb(this),f=a.match(hb)||[];b=f[d++];)e.hasClass(b)?e.removeClass(b):e.addClass(b);else(c===R||"boolean"===c)&&(this.className&&qb.set(this,"__className__",this.className),this.className=this.className||a===!1?"":qb.get(this,"__className__")||"")})},hasClass:function(a){for(var b=" "+a+" ",c=0,d=this.length;d>c;c++)if(1===this[c].nodeType&&(" "+this[c].className+" ").replace(vb," ").indexOf(b)>=0)return!0;return!1},val:function(a){var c,d,e,f=this[0];{if(arguments.length)return e=fb.isFunction(a),this.each(function(d){var f;1===this.nodeType&&(f=e?a.call(this,d,fb(this).val()):a,null==f?f="":"number"==typeof f?f+="":fb.isArray(f)&&(f=fb.map(f,function(a){return null==a?"":a+""})),c=fb.valHooks[this.type]||fb.valHooks[this.nodeName.toLowerCase()],c&&"set"in c&&c.set(this,f,"value")!==b||(this.value=f))});if(f)return c=fb.valHooks[f.type]||fb.valHooks[f.nodeName.toLowerCase()],c&&"get"in c&&(d=c.get(f,"value"))!==b?d:(d=f.value,"string"==typeof d?d.replace(wb,""):null==d?"":d)}}}),fb.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],!(!c.selected&&i!==e||(fb.support.optDisabled?c.disabled:null!==c.getAttribute("disabled"))||c.parentNode.disabled&&fb.nodeName(c.parentNode,"optgroup"))){if(b=fb(c).val(),f)return b;g.push(b)}return g},set:function(a,b){for(var c,d,e=a.options,f=fb.makeArray(b),g=e.length;g--;)d=e[g],(d.selected=fb.inArray(fb(d).val(),f)>=0)&&(c=!0);return c||(a.selectedIndex=-1),f}}},attr:function(a,c,d){var e,f,g=a.nodeType;if(a&&3!==g&&8!==g&&2!==g)return typeof a.getAttribute===R?fb.prop(a,c,d):(1===g&&fb.isXMLDoc(a)||(c=c.toLowerCase(),e=fb.attrHooks[c]||(fb.expr.match.bool.test(c)?ub:tb)),d===b?e&&"get"in e&&null!==(f=e.get(a,c))?f:(f=fb.find.attr(a,c),null==f?b:f):null!==d?e&&"set"in e&&(f=e.set(a,d,c))!==b?f:(a.setAttribute(c,d+""),d):void fb.removeAttr(a,c))},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(hb);if(f&&1===a.nodeType)for(;c=f[e++];)d=fb.propFix[c]||c,fb.expr.match.bool.test(c)&&(a[d]=!1),a.removeAttribute(c)},attrHooks:{type:{set:function(a,b){if(!fb.support.radioValue&&"radio"===b&&fb.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}},propFix:{"for":"htmlFor","class":"className"},prop:function(a,c,d){var e,f,g,h=a.nodeType;if(a&&3!==h&&8!==h&&2!==h)return g=1!==h||!fb.isXMLDoc(a),g&&(c=fb.propFix[c]||c,f=fb.propHooks[c]),d!==b?f&&"set"in f&&(e=f.set(a,d,c))!==b?e:a[c]=d:f&&"get"in f&&null!==(e=f.get(a,c))?e:a[c]},propHooks:{tabIndex:{get:function(a){return a.hasAttribute("tabindex")||xb.test(a.nodeName)||a.href?a.tabIndex:-1}}}}),ub={set:function(a,b,c){return b===!1?fb.removeAttr(a,c):a.setAttribute(c,c),c}},fb.each(fb.expr.match.bool.source.match(/\w+/g),function(a,c){var d=fb.expr.attrHandle[c]||fb.find.attr;fb.expr.attrHandle[c]=function(a,c,e){var f=fb.expr.attrHandle[c],g=e?b:(fb.expr.attrHandle[c]=b)!=d(a,c,e)?c.toLowerCase():null;return fb.expr.attrHandle[c]=f,g}}),fb.support.optSelected||(fb.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null}}),fb.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){fb.propFix[this.toLowerCase()]=this}),fb.each(["radio","checkbox"],function(){fb.valHooks[this]={set:function(a,b){return fb.isArray(b)?a.checked=fb.inArray(fb(a).val(),b)>=0:void 0}},fb.support.checkOn||(fb.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})});var yb=/^key/,zb=/^(?:mouse|contextmenu)|click/,Ab=/^(?:focusinfocus|focusoutblur)$/,Bb=/^([^.]*)(?:\.(.+)|)$/;fb.event={global:{},add:function(a,c,d,e,f){var g,h,i,j,k,l,m,n,o,p,q,r=qb.get(a);if(r){for(d.handler&&(g=d,d=g.handler,f=g.selector),d.guid||(d.guid=fb.guid++),(j=r.events)||(j=r.events={}),(h=r.handle)||(h=r.handle=function(a){return typeof fb===R||a&&fb.event.triggered===a.type?b:fb.event.dispatch.apply(h.elem,arguments)},h.elem=a),c=(c||"").match(hb)||[""],k=c.length;k--;)i=Bb.exec(c[k])||[],o=q=i[1],p=(i[2]||"").split(".").sort(),o&&(m=fb.event.special[o]||{},o=(f?m.delegateType:m.bindType)||o,m=fb.event.special[o]||{},l=fb.extend({type:o,origType:q,data:e,handler:d,guid:d.guid,selector:f,needsContext:f&&fb.expr.match.needsContext.test(f),namespace:p.join(".")},g),(n=j[o])||(n=j[o]=[],n.delegateCount=0,m.setup&&m.setup.call(a,e,p,h)!==!1||a.addEventListener&&a.addEventListener(o,h,!1)),m.add&&(m.add.call(a,l),l.handler.guid||(l.handler.guid=d.guid)),f?n.splice(n.delegateCount++,0,l):n.push(l),fb.event.global[o]=!0);
a=null}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,p,q=qb.hasData(a)&&qb.get(a);if(q&&(i=q.events)){for(b=(b||"").match(hb)||[""],j=b.length;j--;)if(h=Bb.exec(b[j])||[],n=p=h[1],o=(h[2]||"").split(".").sort(),n){for(l=fb.event.special[n]||{},n=(d?l.delegateType:l.bindType)||n,m=i[n]||[],h=h[2]&&new RegExp("(^|\\.)"+o.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;f--;)k=m[f],!e&&p!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,o,q.handle)!==!1||fb.removeEvent(a,n,q.handle),delete i[n])}else for(n in i)fb.event.remove(a,n+b[j],c,d,!0);fb.isEmptyObject(i)&&(delete q.handle,qb.remove(a,"events"))}},trigger:function(c,d,e,f){var g,h,i,j,k,l,m,n=[e||T],o=db.call(c,"type")?c.type:c,p=db.call(c,"namespace")?c.namespace.split("."):[];if(h=i=e=e||T,3!==e.nodeType&&8!==e.nodeType&&!Ab.test(o+fb.event.triggered)&&(o.indexOf(".")>=0&&(p=o.split("."),o=p.shift(),p.sort()),k=o.indexOf(":")<0&&"on"+o,c=c[fb.expando]?c:new fb.Event(o,"object"==typeof c&&c),c.isTrigger=f?2:3,c.namespace=p.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,c.result=b,c.target||(c.target=e),d=null==d?[c]:fb.makeArray(d,[c]),m=fb.event.special[o]||{},f||!m.trigger||m.trigger.apply(e,d)!==!1)){if(!f&&!m.noBubble&&!fb.isWindow(e)){for(j=m.delegateType||o,Ab.test(j+o)||(h=h.parentNode);h;h=h.parentNode)n.push(h),i=h;i===(e.ownerDocument||T)&&n.push(i.defaultView||i.parentWindow||a)}for(g=0;(h=n[g++])&&!c.isPropagationStopped();)c.type=g>1?j:m.bindType||o,l=(qb.get(h,"events")||{})[c.type]&&qb.get(h,"handle"),l&&l.apply(h,d),l=k&&h[k],l&&fb.acceptData(h)&&l.apply&&l.apply(h,d)===!1&&c.preventDefault();return c.type=o,f||c.isDefaultPrevented()||m._default&&m._default.apply(n.pop(),d)!==!1||!fb.acceptData(e)||k&&fb.isFunction(e[o])&&!fb.isWindow(e)&&(i=e[k],i&&(e[k]=null),fb.event.triggered=o,e[o](),fb.event.triggered=b,i&&(e[k]=i)),c.result}},dispatch:function(a){a=fb.event.fix(a);var c,d,e,f,g,h=[],i=ab.call(arguments),j=(qb.get(this,"events")||{})[a.type]||[],k=fb.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){for(h=fb.event.handlers.call(this,a,j),c=0;(f=h[c++])&&!a.isPropagationStopped();)for(a.currentTarget=f.elem,d=0;(g=f.handlers[d++])&&!a.isImmediatePropagationStopped();)(!a.namespace_re||a.namespace_re.test(g.namespace))&&(a.handleObj=g,a.data=g.data,e=((fb.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),e!==b&&(a.result=e)===!1&&(a.preventDefault(),a.stopPropagation()));return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,c){var d,e,f,g,h=[],i=c.delegateCount,j=a.target;if(i&&j.nodeType&&(!a.button||"click"!==a.type))for(;j!==this;j=j.parentNode||this)if(j.disabled!==!0||"click"!==a.type){for(e=[],d=0;i>d;d++)g=c[d],f=g.selector+" ",e[f]===b&&(e[f]=g.needsContext?fb(f,this).index(j)>=0:fb.find(f,this,null,[j]).length),e[f]&&e.push(g);e.length&&h.push({elem:j,handlers:e})}return i<c.length&&h.push({elem:this,handlers:c.slice(i)}),h},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,c){var d,e,f,g=c.button;return null==a.pageX&&null!=c.clientX&&(d=a.target.ownerDocument||T,e=d.documentElement,f=d.body,a.pageX=c.clientX+(e&&e.scrollLeft||f&&f.scrollLeft||0)-(e&&e.clientLeft||f&&f.clientLeft||0),a.pageY=c.clientY+(e&&e.scrollTop||f&&f.scrollTop||0)-(e&&e.clientTop||f&&f.clientTop||0)),a.which||g===b||(a.which=1&g?1:2&g?3:4&g?2:0),a}},fix:function(a){if(a[fb.expando])return a;var b,c,d,e=a.type,f=a,g=this.fixHooks[e];for(g||(this.fixHooks[e]=g=zb.test(e)?this.mouseHooks:yb.test(e)?this.keyHooks:{}),d=g.props?this.props.concat(g.props):this.props,a=new fb.Event(f),b=d.length;b--;)c=d[b],a[c]=f[c];return a.target||(a.target=T),3===a.target.nodeType&&(a.target=a.target.parentNode),g.filter?g.filter(a,f):a},special:{load:{noBubble:!0},focus:{trigger:function(){return this!==i()&&this.focus?(this.focus(),!1):void 0},delegateType:"focusin"},blur:{trigger:function(){return this===i()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return"checkbox"===this.type&&this.click&&fb.nodeName(this,"input")?(this.click(),!1):void 0},_default:function(a){return fb.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){a.result!==b&&(a.originalEvent.returnValue=a.result)}}},simulate:function(a,b,c,d){var e=fb.extend(new fb.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?fb.event.trigger(e,null,b):fb.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},fb.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)},fb.Event=function(a,b){return this instanceof fb.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.getPreventDefault&&a.getPreventDefault()?g:h):this.type=a,b&&fb.extend(this,b),this.timeStamp=a&&a.timeStamp||fb.now(),void(this[fb.expando]=!0)):new fb.Event(a,b)},fb.Event.prototype={isDefaultPrevented:h,isPropagationStopped:h,isImmediatePropagationStopped:h,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=g,a&&a.preventDefault&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=g,a&&a.stopPropagation&&a.stopPropagation()},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=g,this.stopPropagation()}},fb.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){fb.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return(!e||e!==d&&!fb.contains(d,e))&&(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),fb.support.focusinBubbles||fb.each({focus:"focusin",blur:"focusout"},function(a,b){var c=0,d=function(a){fb.event.simulate(b,a.target,fb.event.fix(a),!0)};fb.event.special[b]={setup:function(){0===c++&&T.addEventListener(a,d,!0)},teardown:function(){0===--c&&T.removeEventListener(a,d,!0)}}}),fb.fn.extend({on:function(a,c,d,e,f){var g,i;if("object"==typeof a){"string"!=typeof c&&(d=d||c,c=b);for(i in a)this.on(i,c,d,a[i],f);return this}if(null==d&&null==e?(e=c,d=c=b):null==e&&("string"==typeof c?(e=d,d=b):(e=d,d=c,c=b)),e===!1)e=h;else if(!e)return this;return 1===f&&(g=e,e=function(a){return fb().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=fb.guid++)),this.each(function(){fb.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,c,d){var e,f;if(a&&a.preventDefault&&a.handleObj)return e=a.handleObj,fb(a.delegateTarget).off(e.namespace?e.origType+"."+e.namespace:e.origType,e.selector,e.handler),this;if("object"==typeof a){for(f in a)this.off(f,c,a[f]);return this}return(c===!1||"function"==typeof c)&&(d=c,c=b),d===!1&&(d=h),this.each(function(){fb.event.remove(this,a,d,c)})},trigger:function(a,b){return this.each(function(){fb.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?fb.event.trigger(a,b,c,!0):void 0}});var Cb=/^.[^:#\[\.,]*$/,Db=/^(?:parents|prev(?:Until|All))/,Eb=fb.expr.match.needsContext,Fb={children:!0,contents:!0,next:!0,prev:!0};fb.fn.extend({find:function(a){var b,c=[],d=this,e=d.length;if("string"!=typeof a)return this.pushStack(fb(a).filter(function(){for(b=0;e>b;b++)if(fb.contains(d[b],this))return!0}));for(b=0;e>b;b++)fb.find(a,d[b],c);return c=this.pushStack(e>1?fb.unique(c):c),c.selector=this.selector?this.selector+" "+a:a,c},has:function(a){var b=fb(a,this),c=b.length;return this.filter(function(){for(var a=0;c>a;a++)if(fb.contains(this,b[a]))return!0})},not:function(a){return this.pushStack(k(this,a||[],!0))},filter:function(a){return this.pushStack(k(this,a||[],!1))},is:function(a){return!!k(this,"string"==typeof a&&Eb.test(a)?fb(a):a||[],!1).length},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=Eb.test(a)||"string"!=typeof a?fb(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&fb.find.matchesSelector(c,a))){c=f.push(c);break}return this.pushStack(f.length>1?fb.unique(f):f)},index:function(a){return a?"string"==typeof a?bb.call(fb(a),this[0]):bb.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){var c="string"==typeof a?fb(a,b):fb.makeArray(a&&a.nodeType?[a]:a),d=fb.merge(this.get(),c);return this.pushStack(fb.unique(d))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}}),fb.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return fb.dir(a,"parentNode")},parentsUntil:function(a,b,c){return fb.dir(a,"parentNode",c)},next:function(a){return j(a,"nextSibling")},prev:function(a){return j(a,"previousSibling")},nextAll:function(a){return fb.dir(a,"nextSibling")},prevAll:function(a){return fb.dir(a,"previousSibling")},nextUntil:function(a,b,c){return fb.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return fb.dir(a,"previousSibling",c)},siblings:function(a){return fb.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return fb.sibling(a.firstChild)},contents:function(a){return a.contentDocument||fb.merge([],a.childNodes)}},function(a,b){fb.fn[a]=function(c,d){var e=fb.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=fb.filter(d,e)),this.length>1&&(Fb[a]||fb.unique(e),Db.test(a)&&e.reverse()),this.pushStack(e)}}),fb.extend({filter:function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?fb.find.matchesSelector(d,a)?[d]:[]:fb.find.matches(a,fb.grep(b,function(a){return 1===a.nodeType}))},dir:function(a,c,d){for(var e=[],f=d!==b;(a=a[c])&&9!==a.nodeType;)if(1===a.nodeType){if(f&&fb(a).is(d))break;e.push(a)}return e},sibling:function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c}});var Gb=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,Hb=/<([\w:]+)/,Ib=/<|&#?\w+;/,Jb=/<(?:script|style|link)/i,Kb=/^(?:checkbox|radio)$/i,Lb=/checked\s*(?:[^=]|=\s*.checked.)/i,Mb=/^$|\/(?:java|ecma)script/i,Nb=/^true\/(.*)/,Ob=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,Pb={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};Pb.optgroup=Pb.option,Pb.tbody=Pb.tfoot=Pb.colgroup=Pb.caption=Pb.thead,Pb.th=Pb.td,fb.fn.extend({text:function(a){return fb.access(this,function(a){return a===b?fb.text(this):this.empty().append((this[0]&&this[0].ownerDocument||T).createTextNode(a))},null,a,arguments.length)},append:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=l(this,a);b.appendChild(a)}})},prepend:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=l(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},remove:function(a,b){for(var c,d=a?fb.filter(a,this):this,e=0;null!=(c=d[e]);e++)b||1!==c.nodeType||fb.cleanData(q(c)),c.parentNode&&(b&&fb.contains(c.ownerDocument,c)&&o(q(c,"script")),c.parentNode.removeChild(c));return this},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(fb.cleanData(q(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return fb.clone(this,a,b)})},html:function(a){return fb.access(this,function(a){var c=this[0]||{},d=0,e=this.length;if(a===b&&1===c.nodeType)return c.innerHTML;if("string"==typeof a&&!Jb.test(a)&&!Pb[(Hb.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(Gb,"<$1></$2>");try{for(;e>d;d++)c=this[d]||{},1===c.nodeType&&(fb.cleanData(q(c,!1)),c.innerHTML=a);c=0}catch(f){}}c&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=fb.map(this,function(a){return[a.nextSibling,a.parentNode]}),b=0;return this.domManip(arguments,function(c){var d=a[b++],e=a[b++];e&&(d&&d.parentNode!==e&&(d=this.nextSibling),fb(this).remove(),e.insertBefore(c,d))},!0),b?this:this.remove()},detach:function(a){return this.remove(a,!0)},domManip:function(a,b,c){a=$.apply([],a);var d,e,f,g,h,i,j=0,k=this.length,l=this,o=k-1,p=a[0],r=fb.isFunction(p);if(r||!(1>=k||"string"!=typeof p||fb.support.checkClone)&&Lb.test(p))return this.each(function(d){var e=l.eq(d);r&&(a[0]=p.call(this,d,e.html())),e.domManip(a,b,c)});if(k&&(d=fb.buildFragment(a,this[0].ownerDocument,!1,!c&&this),e=d.firstChild,1===d.childNodes.length&&(d=e),e)){for(f=fb.map(q(d,"script"),m),g=f.length;k>j;j++)h=d,j!==o&&(h=fb.clone(h,!0,!0),g&&fb.merge(f,q(h,"script"))),b.call(this[j],h,j);if(g)for(i=f[f.length-1].ownerDocument,fb.map(f,n),j=0;g>j;j++)h=f[j],Mb.test(h.type||"")&&!qb.access(h,"globalEval")&&fb.contains(i,h)&&(h.src?fb._evalUrl(h.src):fb.globalEval(h.textContent.replace(Ob,"")))}return this}}),fb.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){fb.fn[a]=function(a){for(var c,d=[],e=fb(a),f=e.length-1,g=0;f>=g;g++)c=g===f?this:this.clone(!0),fb(e[g])[b](c),_.apply(d,c.get());return this.pushStack(d)}}),fb.extend({clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=fb.contains(a.ownerDocument,a);if(!(fb.support.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||fb.isXMLDoc(a)))for(g=q(h),f=q(a),d=0,e=f.length;e>d;d++)r(f[d],g[d]);if(b)if(c)for(f=f||q(a),g=g||q(h),d=0,e=f.length;e>d;d++)p(f[d],g[d]);else p(a,h);return g=q(h,"script"),g.length>0&&o(g,!i&&q(a,"script")),h},buildFragment:function(a,b,c,d){for(var e,f,g,h,i,j,k=0,l=a.length,m=b.createDocumentFragment(),n=[];l>k;k++)if(e=a[k],e||0===e)if("object"===fb.type(e))fb.merge(n,e.nodeType?[e]:e);else if(Ib.test(e)){for(f=f||m.appendChild(b.createElement("div")),g=(Hb.exec(e)||["",""])[1].toLowerCase(),h=Pb[g]||Pb._default,f.innerHTML=h[1]+e.replace(Gb,"<$1></$2>")+h[2],j=h[0];j--;)f=f.lastChild;fb.merge(n,f.childNodes),f=m.firstChild,f.textContent=""}else n.push(b.createTextNode(e));for(m.textContent="",k=0;e=n[k++];)if((!d||-1===fb.inArray(e,d))&&(i=fb.contains(e.ownerDocument,e),f=q(m.appendChild(e),"script"),i&&o(f),c))for(j=0;e=f[j++];)Mb.test(e.type||"")&&c.push(e);return m},cleanData:function(a){for(var c,d,f,g,h,i,j=fb.event.special,k=0;(d=a[k])!==b;k++){if(e.accepts(d)&&(h=d[qb.expando],h&&(c=qb.cache[h]))){if(f=Object.keys(c.events||{}),f.length)for(i=0;(g=f[i])!==b;i++)j[g]?fb.event.remove(d,g):fb.removeEvent(d,g,c.handle);qb.cache[h]&&delete qb.cache[h]}delete pb.cache[d[pb.expando]]}},_evalUrl:function(a){return fb.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})}}),fb.fn.extend({wrapAll:function(a){var b;return fb.isFunction(a)?this.each(function(b){fb(this).wrapAll(a.call(this,b))}):(this[0]&&(b=fb(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){for(var a=this;a.firstElementChild;)a=a.firstElementChild;return a}).append(this)),this)},wrapInner:function(a){return this.each(fb.isFunction(a)?function(b){fb(this).wrapInner(a.call(this,b))}:function(){var b=fb(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=fb.isFunction(a);return this.each(function(c){fb(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){fb.nodeName(this,"body")||fb(this).replaceWith(this.childNodes)}).end()}});var Qb,Rb,Sb=/^(none|table(?!-c[ea]).+)/,Tb=/^margin/,Ub=new RegExp("^("+gb+")(.*)$","i"),Vb=new RegExp("^("+gb+")(?!px)[a-z%]+$","i"),Wb=new RegExp("^([+-])=("+gb+")","i"),Xb={BODY:"block"},Yb={position:"absolute",visibility:"hidden",display:"block"},Zb={letterSpacing:0,fontWeight:400},$b=["Top","Right","Bottom","Left"],_b=["Webkit","O","Moz","ms"];fb.fn.extend({css:function(a,c){return fb.access(this,function(a,c,d){var e,f,g={},h=0;if(fb.isArray(c)){for(e=u(a),f=c.length;f>h;h++)g[c[h]]=fb.css(a,c[h],!1,e);return g}return d!==b?fb.style(a,c,d):fb.css(a,c)},a,c,arguments.length>1)},show:function(){return v(this,!0)},hide:function(){return v(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){t(this)?fb(this).show():fb(this).hide()})}}),fb.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=Qb(a,"opacity");return""===c?"1":c}}}},cssNumber:{columnCount:!0,fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,c,d,e){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var f,g,h,i=fb.camelCase(c),j=a.style;return c=fb.cssProps[i]||(fb.cssProps[i]=s(j,i)),h=fb.cssHooks[c]||fb.cssHooks[i],d===b?h&&"get"in h&&(f=h.get(a,!1,e))!==b?f:j[c]:(g=typeof d,"string"===g&&(f=Wb.exec(d))&&(d=(f[1]+1)*f[2]+parseFloat(fb.css(a,c)),g="number"),null==d||"number"===g&&isNaN(d)||("number"!==g||fb.cssNumber[i]||(d+="px"),fb.support.clearCloneStyle||""!==d||0!==c.indexOf("background")||(j[c]="inherit"),h&&"set"in h&&(d=h.set(a,d,e))===b||(j[c]=d)),void 0)}},css:function(a,c,d,e){var f,g,h,i=fb.camelCase(c);return c=fb.cssProps[i]||(fb.cssProps[i]=s(a.style,i)),h=fb.cssHooks[c]||fb.cssHooks[i],h&&"get"in h&&(f=h.get(a,!0,d)),f===b&&(f=Qb(a,c,e)),"normal"===f&&c in Zb&&(f=Zb[c]),""===d||d?(g=parseFloat(f),d===!0||fb.isNumeric(g)?g||0:f):f}}),Qb=function(a,c,d){var e,f,g,h=d||u(a),i=h?h.getPropertyValue(c)||h[c]:b,j=a.style;return h&&(""!==i||fb.contains(a.ownerDocument,a)||(i=fb.style(a,c)),Vb.test(i)&&Tb.test(c)&&(e=j.width,f=j.minWidth,g=j.maxWidth,j.minWidth=j.maxWidth=j.width=i,i=h.width,j.width=e,j.minWidth=f,j.maxWidth=g)),i},fb.each(["height","width"],function(a,b){fb.cssHooks[b]={get:function(a,c,d){return c?0===a.offsetWidth&&Sb.test(fb.css(a,"display"))?fb.swap(a,Yb,function(){return y(a,b,d)}):y(a,b,d):void 0},set:function(a,c,d){var e=d&&u(a);return w(a,c,d?x(a,b,d,fb.support.boxSizing&&"border-box"===fb.css(a,"boxSizing",!1,e),e):0)}}}),fb(function(){fb.support.reliableMarginRight||(fb.cssHooks.marginRight={get:function(a,b){return b?fb.swap(a,{display:"inline-block"},Qb,[a,"marginRight"]):void 0}}),!fb.support.pixelPosition&&fb.fn.position&&fb.each(["top","left"],function(a,b){fb.cssHooks[b]={get:function(a,c){return c?(c=Qb(a,b),Vb.test(c)?fb(a).position()[b]+"px":c):void 0}}})}),fb.expr&&fb.expr.filters&&(fb.expr.filters.hidden=function(a){return a.offsetWidth<=0&&a.offsetHeight<=0},fb.expr.filters.visible=function(a){return!fb.expr.filters.hidden(a)}),fb.each({margin:"",padding:"",border:"Width"},function(a,b){fb.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+$b[d]+b]=f[d]||f[d-2]||f[0];return e}},Tb.test(a)||(fb.cssHooks[a+b].set=w)});var ac=/%20/g,bc=/\[\]$/,cc=/\r?\n/g,dc=/^(?:submit|button|image|reset|file)$/i,ec=/^(?:input|select|textarea|keygen)/i;fb.fn.extend({serialize:function(){return fb.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=fb.prop(this,"elements");return a?fb.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!fb(this).is(":disabled")&&ec.test(this.nodeName)&&!dc.test(a)&&(this.checked||!Kb.test(a))}).map(function(a,b){var c=fb(this).val();return null==c?null:fb.isArray(c)?fb.map(c,function(a){return{name:b.name,value:a.replace(cc,"\r\n")}}):{name:b.name,value:c.replace(cc,"\r\n")}}).get()}}),fb.param=function(a,c){var d,e=[],f=function(a,b){b=fb.isFunction(b)?b():null==b?"":b,e[e.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(c===b&&(c=fb.ajaxSettings&&fb.ajaxSettings.traditional),fb.isArray(a)||a.jquery&&!fb.isPlainObject(a))fb.each(a,function(){f(this.name,this.value)});else for(d in a)B(d,a[d],c,f);return e.join("&").replace(ac,"+")},fb.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){fb.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),fb.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}});var fc,gc,hc=fb.now(),ic=/\?/,jc=/#.*$/,kc=/([?&])_=[^&]*/,lc=/^(.*?):[ \t]*([^\r\n]*)$/gm,mc=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,nc=/^(?:GET|HEAD)$/,oc=/^\/\//,pc=/^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,qc=fb.fn.load,rc={},sc={},tc="*/".concat("*");try{gc=S.href}catch(uc){gc=T.createElement("a"),gc.href="",gc=gc.href}fc=pc.exec(gc.toLowerCase())||[],fb.fn.load=function(a,c,d){if("string"!=typeof a&&qc)return qc.apply(this,arguments);var e,f,g,h=this,i=a.indexOf(" ");return i>=0&&(e=a.slice(i),a=a.slice(0,i)),fb.isFunction(c)?(d=c,c=b):c&&"object"==typeof c&&(f="POST"),h.length>0&&fb.ajax({url:a,type:f,dataType:"html",data:c}).done(function(a){g=arguments,h.html(e?fb("<div>").append(fb.parseHTML(a)).find(e):a)}).complete(d&&function(a,b){h.each(d,g||[a.responseText,b,a])}),this},fb.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){fb.fn[b]=function(a){return this.on(b,a)}}),fb.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:gc,type:"GET",isLocal:mc.test(fc[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":tc,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":fb.parseJSON,"text xml":fb.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?E(E(a,fb.ajaxSettings),b):E(fb.ajaxSettings,a)},ajaxPrefilter:C(rc),ajaxTransport:C(sc),ajax:function(a,c){function d(a,c,d,h){var j,l,s,t,v,x=c;2!==u&&(u=2,i&&clearTimeout(i),e=b,g=h||"",w.readyState=a>0?4:0,j=a>=200&&300>a||304===a,d&&(t=F(m,w,d)),t=G(m,t,w,j),j?(m.ifModified&&(v=w.getResponseHeader("Last-Modified"),v&&(fb.lastModified[f]=v),v=w.getResponseHeader("etag"),v&&(fb.etag[f]=v)),204===a||"HEAD"===m.type?x="nocontent":304===a?x="notmodified":(x=t.state,l=t.data,s=t.error,j=!s)):(s=x,(a||!x)&&(x="error",0>a&&(a=0))),w.status=a,w.statusText=(c||x)+"",j?p.resolveWith(n,[l,x,w]):p.rejectWith(n,[w,x,s]),w.statusCode(r),r=b,k&&o.trigger(j?"ajaxSuccess":"ajaxError",[w,m,j?l:s]),q.fireWith(n,[w,x]),k&&(o.trigger("ajaxComplete",[w,m]),--fb.active||fb.event.trigger("ajaxStop")))}"object"==typeof a&&(c=a,a=b),c=c||{};var e,f,g,h,i,j,k,l,m=fb.ajaxSetup({},c),n=m.context||m,o=m.context&&(n.nodeType||n.jquery)?fb(n):fb.event,p=fb.Deferred(),q=fb.Callbacks("once memory"),r=m.statusCode||{},s={},t={},u=0,v="canceled",w={readyState:0,getResponseHeader:function(a){var b;if(2===u){if(!h)for(h={};b=lc.exec(g);)h[b[1].toLowerCase()]=b[2];b=h[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===u?g:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return u||(a=t[c]=t[c]||a,s[a]=b),this},overrideMimeType:function(a){return u||(m.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>u)for(b in a)r[b]=[r[b],a[b]];else w.always(a[w.status]);return this},abort:function(a){var b=a||v;return e&&e.abort(b),d(0,b),this}};if(p.promise(w).complete=q.add,w.success=w.done,w.error=w.fail,m.url=((a||m.url||gc)+"").replace(jc,"").replace(oc,fc[1]+"//"),m.type=c.method||c.type||m.method||m.type,m.dataTypes=fb.trim(m.dataType||"*").toLowerCase().match(hb)||[""],null==m.crossDomain&&(j=pc.exec(m.url.toLowerCase()),m.crossDomain=!(!j||j[1]===fc[1]&&j[2]===fc[2]&&(j[3]||("http:"===j[1]?"80":"443"))===(fc[3]||("http:"===fc[1]?"80":"443")))),m.data&&m.processData&&"string"!=typeof m.data&&(m.data=fb.param(m.data,m.traditional)),D(rc,m,c,w),2===u)return w;k=m.global,k&&0===fb.active++&&fb.event.trigger("ajaxStart"),m.type=m.type.toUpperCase(),m.hasContent=!nc.test(m.type),f=m.url,m.hasContent||(m.data&&(f=m.url+=(ic.test(f)?"&":"?")+m.data,delete m.data),m.cache===!1&&(m.url=kc.test(f)?f.replace(kc,"$1_="+hc++):f+(ic.test(f)?"&":"?")+"_="+hc++)),m.ifModified&&(fb.lastModified[f]&&w.setRequestHeader("If-Modified-Since",fb.lastModified[f]),fb.etag[f]&&w.setRequestHeader("If-None-Match",fb.etag[f])),(m.data&&m.hasContent&&m.contentType!==!1||c.contentType)&&w.setRequestHeader("Content-Type",m.contentType),w.setRequestHeader("Accept",m.dataTypes[0]&&m.accepts[m.dataTypes[0]]?m.accepts[m.dataTypes[0]]+("*"!==m.dataTypes[0]?", "+tc+"; q=0.01":""):m.accepts["*"]);for(l in m.headers)w.setRequestHeader(l,m.headers[l]);if(m.beforeSend&&(m.beforeSend.call(n,w,m)===!1||2===u))return w.abort();v="abort";for(l in{success:1,error:1,complete:1})w[l](m[l]);if(e=D(sc,m,c,w)){w.readyState=1,k&&o.trigger("ajaxSend",[w,m]),m.async&&m.timeout>0&&(i=setTimeout(function(){w.abort("timeout")},m.timeout));try{u=1,e.send(s,d)}catch(x){if(!(2>u))throw x;d(-1,x)}}else d(-1,"No Transport");return w},getJSON:function(a,b,c){return fb.get(a,b,c,"json")},getScript:function(a,c){return fb.get(a,b,c,"script")}}),fb.each(["get","post"],function(a,c){fb[c]=function(a,d,e,f){return fb.isFunction(d)&&(f=f||e,e=d,d=b),fb.ajax({url:a,type:c,dataType:f,data:d,success:e})}}),fb.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(a){return fb.globalEval(a),a}}}),fb.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),fb.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(d,e){b=fb("<script>").prop({async:!0,charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&e("error"===a.type?404:200,a.type)}),T.head.appendChild(b[0])},abort:function(){c&&c()}}}});var vc=[],wc=/(=)\?(?=&|$)|\?\?/;fb.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=vc.pop()||fb.expando+"_"+hc++;return this[a]=!0,a}}),fb.ajaxPrefilter("json jsonp",function(c,d,e){var f,g,h,i=c.jsonp!==!1&&(wc.test(c.url)?"url":"string"==typeof c.data&&!(c.contentType||"").indexOf("application/x-www-form-urlencoded")&&wc.test(c.data)&&"data");return i||"jsonp"===c.dataTypes[0]?(f=c.jsonpCallback=fb.isFunction(c.jsonpCallback)?c.jsonpCallback():c.jsonpCallback,i?c[i]=c[i].replace(wc,"$1"+f):c.jsonp!==!1&&(c.url+=(ic.test(c.url)?"&":"?")+c.jsonp+"="+f),c.converters["script json"]=function(){return h||fb.error(f+" was not called"),h[0]},c.dataTypes[0]="json",g=a[f],a[f]=function(){h=arguments},e.always(function(){a[f]=g,c[f]&&(c.jsonpCallback=d.jsonpCallback,vc.push(f)),h&&fb.isFunction(g)&&g(h[0]),h=g=b}),"script"):void 0}),fb.ajaxSettings.xhr=function(){try{return new XMLHttpRequest}catch(a){}};var xc=fb.ajaxSettings.xhr(),yc={0:200,1223:204},zc=0,Ac={};a.ActiveXObject&&fb(a).on("unload",function(){for(var a in Ac)Ac[a]();Ac=b}),fb.support.cors=!!xc&&"withCredentials"in xc,fb.support.ajax=xc=!!xc,fb.ajaxTransport(function(a){var c;return fb.support.cors||xc&&!a.crossDomain?{send:function(d,e){var f,g,h=a.xhr();if(h.open(a.type,a.url,a.async,a.username,a.password),a.xhrFields)for(f in a.xhrFields)h[f]=a.xhrFields[f];a.mimeType&&h.overrideMimeType&&h.overrideMimeType(a.mimeType),a.crossDomain||d["X-Requested-With"]||(d["X-Requested-With"]="XMLHttpRequest");for(f in d)h.setRequestHeader(f,d[f]);c=function(a){return function(){c&&(delete Ac[g],c=h.onload=h.onerror=null,"abort"===a?h.abort():"error"===a?e(h.status||404,h.statusText):e(yc[h.status]||h.status,h.statusText,"string"==typeof h.responseText?{text:h.responseText}:b,h.getAllResponseHeaders()))}},h.onload=c(),h.onerror=c("error"),c=Ac[g=zc++]=c("abort"),h.send(a.hasContent&&a.data||null)},abort:function(){c&&c()}}:void 0});var Bc,Cc,Dc=/^(?:toggle|show|hide)$/,Ec=new RegExp("^(?:([+-])=|)("+gb+")([a-z%]*)$","i"),Fc=/queueHooks$/,Gc=[L],Hc={"*":[function(a,b){var c=this.createTween(a,b),d=c.cur(),e=Ec.exec(b),f=e&&e[3]||(fb.cssNumber[a]?"":"px"),g=(fb.cssNumber[a]||"px"!==f&&+d)&&Ec.exec(fb.css(c.elem,a)),h=1,i=20;if(g&&g[3]!==f){f=f||g[3],e=e||[],g=+d||1;do h=h||".5",g/=h,fb.style(c.elem,a,g+f);while(h!==(h=c.cur()/d)&&1!==h&&--i)}return e&&(g=c.start=+g||+d||0,c.unit=f,c.end=e[1]?g+(e[1]+1)*e[2]:+e[2]),c}]};fb.Animation=fb.extend(J,{tweener:function(a,b){fb.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");for(var c,d=0,e=a.length;e>d;d++)c=a[d],Hc[c]=Hc[c]||[],Hc[c].unshift(b)},prefilter:function(a,b){b?Gc.unshift(a):Gc.push(a)}}),fb.Tween=M,M.prototype={constructor:M,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(fb.cssNumber[c]?"":"px")},cur:function(){var a=M.propHooks[this.prop];return a&&a.get?a.get(this):M.propHooks._default.get(this)},run:function(a){var b,c=M.propHooks[this.prop];return this.pos=b=this.options.duration?fb.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):M.propHooks._default.set(this),this}},M.prototype.init.prototype=M.prototype,M.propHooks={_default:{get:function(a){var b;return null==a.elem[a.prop]||a.elem.style&&null!=a.elem.style[a.prop]?(b=fb.css(a.elem,a.prop,""),b&&"auto"!==b?b:0):a.elem[a.prop]},set:function(a){fb.fx.step[a.prop]?fb.fx.step[a.prop](a):a.elem.style&&(null!=a.elem.style[fb.cssProps[a.prop]]||fb.cssHooks[a.prop])?fb.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},M.propHooks.scrollTop=M.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},fb.each(["toggle","show","hide"],function(a,b){var c=fb.fn[b];fb.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(N(b,!0),a,d,e)}}),fb.fn.extend({fadeTo:function(a,b,c,d){return this.filter(t).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=fb.isEmptyObject(a),f=fb.speed(b,c,d),g=function(){var b=J(this,fb.extend({},a),f);(e||qb.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,c,d){var e=function(a){var b=a.stop;delete a.stop,b(d)};return"string"!=typeof a&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,c=null!=a&&a+"queueHooks",f=fb.timers,g=qb.get(this);if(c)g[c]&&g[c].stop&&e(g[c]);else for(c in g)g[c]&&g[c].stop&&Fc.test(c)&&e(g[c]);for(c=f.length;c--;)f[c].elem!==this||null!=a&&f[c].queue!==a||(f[c].anim.stop(d),b=!1,f.splice(c,1));(b||!d)&&fb.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=qb.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=fb.timers,g=d?d.length:0;for(c.finish=!0,fb.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})
}}),fb.each({slideDown:N("show"),slideUp:N("hide"),slideToggle:N("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){fb.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),fb.speed=function(a,b,c){var d=a&&"object"==typeof a?fb.extend({},a):{complete:c||!c&&b||fb.isFunction(a)&&a,duration:a,easing:c&&b||b&&!fb.isFunction(b)&&b};return d.duration=fb.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in fb.fx.speeds?fb.fx.speeds[d.duration]:fb.fx.speeds._default,(null==d.queue||d.queue===!0)&&(d.queue="fx"),d.old=d.complete,d.complete=function(){fb.isFunction(d.old)&&d.old.call(this),d.queue&&fb.dequeue(this,d.queue)},d},fb.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},fb.timers=[],fb.fx=M.prototype.init,fb.fx.tick=function(){var a,c=fb.timers,d=0;for(Bc=fb.now();d<c.length;d++)a=c[d],a()||c[d]!==a||c.splice(d--,1);c.length||fb.fx.stop(),Bc=b},fb.fx.timer=function(a){a()&&fb.timers.push(a)&&fb.fx.start()},fb.fx.interval=13,fb.fx.start=function(){Cc||(Cc=setInterval(fb.fx.tick,fb.fx.interval))},fb.fx.stop=function(){clearInterval(Cc),Cc=null},fb.fx.speeds={slow:600,fast:200,_default:400},fb.fx.step={},fb.expr&&fb.expr.filters&&(fb.expr.filters.animated=function(a){return fb.grep(fb.timers,function(b){return a===b.elem}).length}),fb.fn.offset=function(a){if(arguments.length)return a===b?this:this.each(function(b){fb.offset.setOffset(this,a,b)});var c,d,e=this[0],f={top:0,left:0},g=e&&e.ownerDocument;if(g)return c=g.documentElement,fb.contains(c,e)?(typeof e.getBoundingClientRect!==R&&(f=e.getBoundingClientRect()),d=O(g),{top:f.top+d.pageYOffset-c.clientTop,left:f.left+d.pageXOffset-c.clientLeft}):f},fb.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=fb.css(a,"position"),l=fb(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=fb.css(a,"top"),i=fb.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),fb.isFunction(b)&&(b=b.call(a,c,h)),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},fb.fn.extend({position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===fb.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),fb.nodeName(a[0],"html")||(d=a.offset()),d.top+=fb.css(a[0],"borderTopWidth",!0),d.left+=fb.css(a[0],"borderLeftWidth",!0)),{top:b.top-d.top-fb.css(c,"marginTop",!0),left:b.left-d.left-fb.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){for(var a=this.offsetParent||U;a&&!fb.nodeName(a,"html")&&"static"===fb.css(a,"position");)a=a.offsetParent;return a||U})}}),fb.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(c,d){var e="pageYOffset"===d;fb.fn[c]=function(f){return fb.access(this,function(c,f,g){var h=O(c);return g===b?h?h[d]:c[f]:void(h?h.scrollTo(e?a.pageXOffset:g,e?g:a.pageYOffset):c[f]=g)},c,f,arguments.length,null)}}),fb.each({Height:"height",Width:"width"},function(a,c){fb.each({padding:"inner"+a,content:c,"":"outer"+a},function(d,e){fb.fn[e]=function(e,f){var g=arguments.length&&(d||"boolean"!=typeof e),h=d||(e===!0||f===!0?"margin":"border");return fb.access(this,function(c,d,e){var f;return fb.isWindow(c)?c.document.documentElement["client"+a]:9===c.nodeType?(f=c.documentElement,Math.max(c.body["scroll"+a],f["scroll"+a],c.body["offset"+a],f["offset"+a],f["client"+a])):e===b?fb.css(c,d,h):fb.style(c,d,e,h)},c,g?e:b,g,null)}})}),fb.fn.size=function(){return this.length},fb.fn.andSelf=fb.fn.addBack,"object"==typeof module&&module&&"object"==typeof module.exports?module.exports=fb:"function"==typeof define&&define.amd&&define("jquery",[],function(){return fb}),"object"==typeof a&&"object"==typeof a.document&&(a.jQuery=a.$=fb)}(window);/*!
 * Modernizr v2.7.1
 * www.modernizr.com
 *
 * Copyright (c) Faruk Ates, Paul Irish, Alex Sexton
 * Available under the BSD and MIT licenses: www.modernizr.com/license/
 */
window.Modernizr=function(a,b,c){function d(a){t.cssText=a}function e(a,b){return d(x.join(a+";")+(b||""))}function f(a,b){return typeof a===b}function g(a,b){return!!~(""+a).indexOf(b)}function h(a,b){for(var d in a){var e=a[d];if(!g(e,"-")&&t[e]!==c)return"pfx"==b?e:!0}return!1}function i(a,b,d){for(var e in a){var g=b[a[e]];if(g!==c)return d===!1?a[e]:f(g,"function")?g.bind(d||b):g}return!1}function j(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+z.join(d+" ")+d).split(" ");return f(b,"string")||f(b,"undefined")?h(e,b):(e=(a+" "+A.join(d+" ")+d).split(" "),i(e,b,c))}function k(){o.input=function(c){for(var d=0,e=c.length;e>d;d++)E[c[d]]=!!(c[d]in u);return E.list&&(E.list=!(!b.createElement("datalist")||!a.HTMLDataListElement)),E}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),o.inputtypes=function(a){for(var d,e,f,g=0,h=a.length;h>g;g++)u.setAttribute("type",e=a[g]),d="text"!==u.type,d&&(u.value=v,u.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(e)&&u.style.WebkitAppearance!==c?(q.appendChild(u),f=b.defaultView,d=f.getComputedStyle&&"textfield"!==f.getComputedStyle(u,null).WebkitAppearance&&0!==u.offsetHeight,q.removeChild(u)):/^(search|tel)$/.test(e)||(d=/^(url|email)$/.test(e)?u.checkValidity&&u.checkValidity()===!1:u.value!=v)),D[a[g]]=!!d;return D}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var l,m,n="2.7.1",o={},p=!0,q=b.documentElement,r="modernizr",s=b.createElement(r),t=s.style,u=b.createElement("input"),v=":)",w={}.toString,x=" -webkit- -moz- -o- -ms- ".split(" "),y="Webkit Moz O ms",z=y.split(" "),A=y.toLowerCase().split(" "),B={svg:"http://www.w3.org/2000/svg"},C={},D={},E={},F=[],G=F.slice,H=function(a,c,d,e){var f,g,h,i,j=b.createElement("div"),k=b.body,l=k||b.createElement("body");if(parseInt(d,10))for(;d--;)h=b.createElement("div"),h.id=e?e[d]:r+(d+1),j.appendChild(h);return f=["&#173;",'<style id="s',r,'">',a,"</style>"].join(""),j.id=r,(k?j:l).innerHTML+=f,l.appendChild(j),k||(l.style.background="",l.style.overflow="hidden",i=q.style.overflow,q.style.overflow="hidden",q.appendChild(l)),g=c(j,a),k?j.parentNode.removeChild(j):(l.parentNode.removeChild(l),q.style.overflow=i),!!g},I=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return H("@media "+b+" { #"+r+" { position: absolute; } }",function(b){d="absolute"==(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle).position}),d},J=function(){function a(a,e){e=e||b.createElement(d[a]||"div"),a="on"+a;var g=a in e;return g||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(a,""),g=f(e[a],"function"),f(e[a],"undefined")||(e[a]=c),e.removeAttribute(a))),e=null,g}var d={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return a}(),K={}.hasOwnProperty;m=f(K,"undefined")||f(K.call,"undefined")?function(a,b){return b in a&&f(a.constructor.prototype[b],"undefined")}:function(a,b){return K.call(a,b)},Function.prototype.bind||(Function.prototype.bind=function(a){var b=this;if("function"!=typeof b)throw new TypeError;var c=G.call(arguments,1),d=function(){if(this instanceof d){var e=function(){};e.prototype=b.prototype;var f=new e,g=b.apply(f,c.concat(G.call(arguments)));return Object(g)===g?g:f}return b.apply(a,c.concat(G.call(arguments)))};return d}),C.flexbox=function(){return j("flexWrap")},C.flexboxlegacy=function(){return j("boxDirection")},C.canvas=function(){var a=b.createElement("canvas");return!(!a.getContext||!a.getContext("2d"))},C.canvastext=function(){return!(!o.canvas||!f(b.createElement("canvas").getContext("2d").fillText,"function"))},C.webgl=function(){return!!a.WebGLRenderingContext},C.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:H(["@media (",x.join("touch-enabled),("),r,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=9===a.offsetTop}),c},C.geolocation=function(){return"geolocation"in navigator},C.postmessage=function(){return!!a.postMessage},C.websqldatabase=function(){return!!a.openDatabase},C.indexedDB=function(){return!!j("indexedDB",a)},C.hashchange=function(){return J("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},C.history=function(){return!(!a.history||!history.pushState)},C.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},C.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},C.rgba=function(){return d("background-color:rgba(150,255,150,.5)"),g(t.backgroundColor,"rgba")},C.hsla=function(){return d("background-color:hsla(120,40%,100%,.5)"),g(t.backgroundColor,"rgba")||g(t.backgroundColor,"hsla")},C.multiplebgs=function(){return d("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(t.background)},C.backgroundsize=function(){return j("backgroundSize")},C.borderimage=function(){return j("borderImage")},C.borderradius=function(){return j("borderRadius")},C.boxshadow=function(){return j("boxShadow")},C.textshadow=function(){return""===b.createElement("div").style.textShadow},C.opacity=function(){return e("opacity:.55"),/^0.55$/.test(t.opacity)},C.cssanimations=function(){return j("animationName")},C.csscolumns=function(){return j("columnCount")},C.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return d((a+"-webkit- ".split(" ").join(b+a)+x.join(c+a)).slice(0,-a.length)),g(t.backgroundImage,"gradient")},C.cssreflections=function(){return j("boxReflect")},C.csstransforms=function(){return!!j("transform")},C.csstransforms3d=function(){var a=!!j("perspective");return a&&"webkitPerspective"in q.style&&H("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b){a=9===b.offsetLeft&&3===b.offsetHeight}),a},C.csstransitions=function(){return j("transition")},C.fontface=function(){var a;return H('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&0===g.indexOf(d.split(" ")[0])}),a},C.generatedcontent=function(){var a;return H(["#",r,"{font:0/0 a}#",r,':after{content:"',v,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},C.video=function(){var a=b.createElement("video"),c=!1;try{(c=!!a.canPlayType)&&(c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""))}catch(d){}return c},C.audio=function(){var a=b.createElement("audio"),c=!1;try{(c=!!a.canPlayType)&&(c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,""))}catch(d){}return c},C.localstorage=function(){try{return localStorage.setItem(r,r),localStorage.removeItem(r),!0}catch(a){return!1}},C.sessionstorage=function(){try{return sessionStorage.setItem(r,r),sessionStorage.removeItem(r),!0}catch(a){return!1}},C.webworkers=function(){return!!a.Worker},C.applicationcache=function(){return!!a.applicationCache},C.svg=function(){return!!b.createElementNS&&!!b.createElementNS(B.svg,"svg").createSVGRect},C.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==B.svg},C.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(w.call(b.createElementNS(B.svg,"animate")))},C.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(w.call(b.createElementNS(B.svg,"clipPath")))};for(var L in C)m(C,L)&&(l=L.toLowerCase(),o[l]=C[L](),F.push((o[l]?"":"no-")+l));return o.input||k(),o.addTest=function(a,b){if("object"==typeof a)for(var d in a)m(a,d)&&o.addTest(d,a[d]);else{if(a=a.toLowerCase(),o[a]!==c)return o;b="function"==typeof b?b():b,"undefined"!=typeof p&&p&&(q.className+=" "+(b?"":"no-")+a),o[a]=b}return o},d(""),s=u=null,function(a,b){function c(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function d(){var a=s.elements;return"string"==typeof a?a.split(" "):a}function e(a){var b=r[a[p]];return b||(b={},q++,a[p]=q,r[q]=b),b}function f(a,c,d){if(c||(c=b),k)return c.createElement(a);d||(d=e(c));var f;return f=d.cache[a]?d.cache[a].cloneNode():o.test(a)?(d.cache[a]=d.createElem(a)).cloneNode():d.createElem(a),!f.canHaveChildren||n.test(a)||f.tagUrn?f:d.frag.appendChild(f)}function g(a,c){if(a||(a=b),k)return a.createDocumentFragment();c=c||e(a);for(var f=c.frag.cloneNode(),g=0,h=d(),i=h.length;i>g;g++)f.createElement(h[g]);return f}function h(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return s.shivMethods?f(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+d().join().replace(/[\w\-]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(s,b.frag)}function i(a){a||(a=b);var d=e(a);return!s.shivCSS||j||d.hasCSS||(d.hasCSS=!!c(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),k||h(a,d),a}var j,k,l="3.7.0",m=a.html5||{},n=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,o=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,p="_html5shiv",q=0,r={};!function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",j="hidden"in a,k=1==a.childNodes.length||function(){b.createElement("a");var a=b.createDocumentFragment();return"undefined"==typeof a.cloneNode||"undefined"==typeof a.createDocumentFragment||"undefined"==typeof a.createElement}()}catch(c){j=!0,k=!0}}();var s={elements:m.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",version:l,shivCSS:m.shivCSS!==!1,supportsUnknownElements:k,shivMethods:m.shivMethods!==!1,type:"default",shivDocument:i,createElement:f,createDocumentFragment:g};a.html5=s,i(b)}(this,b),o._version=n,o._prefixes=x,o._domPrefixes=A,o._cssomPrefixes=z,o.mq=I,o.hasEvent=J,o.testProp=function(a){return h([a])},o.testAllProps=j,o.testStyles=H,o.prefixed=function(a,b,c){return b?j(a,b,c):j(a,"pfx")},q.className=q.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(p?" js "+F.join(" "):""),o}(this,this.document);/*! http://mths.be/placeholder v2.0.7 by @mathias */
!function(a,b,c){function d(a){var b={},d=/^jQuery\d+$/;return c.each(a.attributes,function(a,c){c.specified&&!d.test(c.name)&&(b[c.name]=c.value)}),b}function e(a,d){var e=this,f=c(e);if(e.value==f.attr("placeholder")&&f.hasClass("placeholder"))if(f.data("placeholder-password")){if(f=f.hide().next().show().attr("id",f.removeAttr("id").data("placeholder-id")),a===!0)return f[0].value=d;f.focus()}else e.value="",f.removeClass("placeholder"),e==b.activeElement&&e.select()}function f(){var a,b=this,f=c(b),g=this.id;if(""==b.value){if("password"==b.type){if(!f.data("placeholder-textinput")){try{a=f.clone().attr({type:"text"})}catch(h){a=c("<input>").attr(c.extend(d(this),{type:"text"}))}a.removeAttr("name").data({"placeholder-password":!0,"placeholder-id":g}).bind("focus.placeholder",e),f.data({"placeholder-textinput":a,"placeholder-id":g}).before(a)}f=f.removeAttr("id").hide().prev().attr("id",g).show()}f.addClass("placeholder"),f[0].value=f.attr("placeholder")}else f.removeClass("placeholder")}var g,h,i="placeholder"in b.createElement("input"),j="placeholder"in b.createElement("textarea"),k=c.fn,l=c.valHooks;i&&j?(h=k.placeholder=function(){return this},h.input=h.textarea=!0):(h=k.placeholder=function(){var a=this;return a.filter((i?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":e,"blur.placeholder":f}).data("placeholder-enabled",!0).trigger("blur.placeholder"),a},h.input=i,h.textarea=j,g={get:function(a){var b=c(a);return b.data("placeholder-enabled")&&b.hasClass("placeholder")?"":a.value},set:function(a,d){var g=c(a);return g.data("placeholder-enabled")?(""==d?(a.value=d,a!=b.activeElement&&f.call(a)):g.hasClass("placeholder")?e.call(a,!0,d)||(a.value=d):a.value=d,g):a.value=d}},i||(l.input=g),j||(l.textarea=g),c(function(){c(b).delegate("form","submit.placeholder",function(){var a=c(".placeholder",this).each(e);setTimeout(function(){a.each(f)},10)})}),c(a).bind("beforeunload.placeholder",function(){c(".placeholder").each(function(){this.value=""})}))}(this,document,jQuery);/*
 * Foundation Responsive Library
 * http://foundation.zurb.com
 * Copyright 2013, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

(function ($, window, document, undefined) {
    'use strict';

    // Used to retrieve Foundation media queries from CSS.
    if ($('head').has('.foundation-mq-small').length === 0) {
        $('head').append('<meta class="foundation-mq-small">');
    }

    if ($('head').has('.foundation-mq-medium').length === 0) {
        $('head').append('<meta class="foundation-mq-medium">');
    }

    if ($('head').has('.foundation-mq-large').length === 0) {
        $('head').append('<meta class="foundation-mq-large">');
    }

    if ($('head').has('.foundation-mq-xlarge').length === 0) {
        $('head').append('<meta class="foundation-mq-xlarge">');
    }

    if ($('head').has('.foundation-mq-xxlarge').length === 0) {
        $('head').append('<meta class="foundation-mq-xxlarge">');
    }

    // Embed FastClick (this should be removed later)
    function FastClick(layer) {
        'use strict';
        var oldOnClick, self = this;
        this.trackingClick = false;
        this.trackingClickStart = 0;
        this.targetElement = null;
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.lastTouchIdentifier = 0;
        this.touchBoundary = 10;
        this.layer = layer;
        if (!layer || !layer.nodeType) {
            throw new TypeError('Layer must be a document node');
        }
        this.onClick = function () {
            return FastClick.prototype.onClick.apply(self, arguments)
        };
        this.onMouse = function () {
            return FastClick.prototype.onMouse.apply(self, arguments)
        };
        this.onTouchStart = function () {
            return FastClick.prototype.onTouchStart.apply(self, arguments)
        };
        this.onTouchMove = function () {
            return FastClick.prototype.onTouchMove.apply(self, arguments)
        };
        this.onTouchEnd = function () {
            return FastClick.prototype.onTouchEnd.apply(self, arguments)
        };
        this.onTouchCancel = function () {
            return FastClick.prototype.onTouchCancel.apply(self, arguments)
        };
        if (FastClick.notNeeded(layer)) {
            return
        }
        if (this.deviceIsAndroid) {
            layer.addEventListener('mouseover', this.onMouse, true);
            layer.addEventListener('mousedown', this.onMouse, true);
            layer.addEventListener('mouseup', this.onMouse, true)
        }
        layer.addEventListener('click', this.onClick, true);
        layer.addEventListener('touchstart', this.onTouchStart, false);
        layer.addEventListener('touchmove', this.onTouchMove, false);
        layer.addEventListener('touchend', this.onTouchEnd, false);
        layer.addEventListener('touchcancel', this.onTouchCancel, false);
        if (!Event.prototype.stopImmediatePropagation) {
            layer.removeEventListener = function (type, callback, capture) {
                var rmv = Node.prototype.removeEventListener;
                if (type === 'click') {
                    rmv.call(layer, type, callback.hijacked || callback, capture)
                } else {
                    rmv.call(layer, type, callback, capture)
                }
            };
            layer.addEventListener = function (type, callback, capture) {
                var adv = Node.prototype.addEventListener;
                if (type === 'click') {
                    adv.call(layer, type, callback.hijacked || (callback.hijacked = function (event) {
                        if (!event.propagationStopped) {
                            callback(event)
                        }
                    }), capture)
                } else {
                    adv.call(layer, type, callback, capture)
                }
            }
        }
        if (typeof layer.onclick === 'function') {
            oldOnClick = layer.onclick;
            layer.addEventListener('click', function (event) {
                oldOnClick(event)
            }, false);
            layer.onclick = null
        }
    }

    FastClick.prototype.deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0;
    FastClick.prototype.deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent);
    FastClick.prototype.deviceIsIOS4 = FastClick.prototype.deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);
    FastClick.prototype.deviceIsIOSWithBadTarget = FastClick.prototype.deviceIsIOS && (/OS ([6-9]|\d{2})_\d/).test(navigator.userAgent);
    FastClick.prototype.needsClick = function (target) {
        'use strict';
        switch (target.nodeName.toLowerCase()) {
            case'button':
            case'select':
            case'textarea':
                if (target.disabled) {
                    return true
                }
                break;
            case'input':
                if ((this.deviceIsIOS && target.type === 'file') || target.disabled) {
                    return true
                }
                break;
            case'label':
            case'video':
                return true
        }
        return(/\bneedsclick\b/).test(target.className)
    };
    FastClick.prototype.needsFocus = function (target) {
        'use strict';
        switch (target.nodeName.toLowerCase()) {
            case'textarea':
            case'select':
                return true;
            case'input':
                switch (target.type) {
                    case'button':
                    case'checkbox':
                    case'file':
                    case'image':
                    case'radio':
                    case'submit':
                        return false
                }
                return!target.disabled && !target.readOnly;
            default:
                return(/\bneedsfocus\b/).test(target.className)
        }
    };
    FastClick.prototype.sendClick = function (targetElement, event) {
        'use strict';
        var clickEvent, touch;
        if (document.activeElement && document.activeElement !== targetElement) {
            document.activeElement.blur()
        }
        touch = event.changedTouches[0];
        clickEvent = document.createEvent('MouseEvents');
        clickEvent.initMouseEvent('click', true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
        clickEvent.forwardedTouchEvent = true;
        targetElement.dispatchEvent(clickEvent)
    };
    FastClick.prototype.focus = function (targetElement) {
        'use strict';
        var length;
        if (this.deviceIsIOS && targetElement.setSelectionRange) {
            length = targetElement.value.length;
            targetElement.setSelectionRange(length, length)
        } else {
            targetElement.focus()
        }
    };
    FastClick.prototype.updateScrollParent = function (targetElement) {
        'use strict';
        var scrollParent, parentElement;
        scrollParent = targetElement.fastClickScrollParent;
        if (!scrollParent || !scrollParent.contains(targetElement)) {
            parentElement = targetElement;
            do {
                if (parentElement.scrollHeight > parentElement.offsetHeight) {
                    scrollParent = parentElement;
                    targetElement.fastClickScrollParent = parentElement;
                    break
                }
                parentElement = parentElement.parentElement
            } while (parentElement)
        }
        if (scrollParent) {
            scrollParent.fastClickLastScrollTop = scrollParent.scrollTop
        }
    };
    FastClick.prototype.getTargetElementFromEventTarget = function (eventTarget) {
        'use strict';
        if (eventTarget.nodeType === Node.TEXT_NODE) {
            return eventTarget.parentNode
        }
        return eventTarget
    };
    FastClick.prototype.onTouchStart = function (event) {
        'use strict';
        var targetElement, touch, selection;
        if (event.targetTouches.length > 1) {
            return true
        }
        targetElement = this.getTargetElementFromEventTarget(event.target);
        touch = event.targetTouches[0];
        if (this.deviceIsIOS) {
            selection = window.getSelection();
            if (selection.rangeCount && !selection.isCollapsed) {
                return true
            }
            if (!this.deviceIsIOS4) {
                if (touch.identifier === this.lastTouchIdentifier) {
                    event.preventDefault();
                    return false
                }
                this.lastTouchIdentifier = touch.identifier;
                this.updateScrollParent(targetElement)
            }
        }
        this.trackingClick = true;
        this.trackingClickStart = event.timeStamp;
        this.targetElement = targetElement;
        this.touchStartX = touch.pageX;
        this.touchStartY = touch.pageY;
        if ((event.timeStamp - this.lastClickTime) < 200) {
            event.preventDefault()
        }
        return true
    };
    FastClick.prototype.touchHasMoved = function (event) {
        'use strict';
        var touch = event.changedTouches[0], boundary = this.touchBoundary;
        if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
            return true
        }
        return false
    };
    FastClick.prototype.onTouchMove = function (event) {
        'use strict';
        if (!this.trackingClick) {
            return true
        }
        if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
            this.trackingClick = false;
            this.targetElement = null
        }
        return true
    };
    FastClick.prototype.findControl = function (labelElement) {
        'use strict';
        if (labelElement.control !== undefined) {
            return labelElement.control
        }
        if (labelElement.htmlFor) {
            return document.getElementById(labelElement.htmlFor)
        }
        return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea')
    };
    FastClick.prototype.onTouchEnd = function (event) {
        'use strict';
        var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;
        if (!this.trackingClick) {
            return true
        }
        if ((event.timeStamp - this.lastClickTime) < 200) {
            this.cancelNextClick = true;
            return true
        }
        this.lastClickTime = event.timeStamp;
        trackingClickStart = this.trackingClickStart;
        this.trackingClick = false;
        this.trackingClickStart = 0;
        if (this.deviceIsIOSWithBadTarget) {
            touch = event.changedTouches[0];
            targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
            targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent
        }
        targetTagName = targetElement.tagName.toLowerCase();
        if (targetTagName === 'label') {
            forElement = this.findControl(targetElement);
            if (forElement) {
                this.focus(targetElement);
                if (this.deviceIsAndroid) {
                    return false
                }
                targetElement = forElement
            }
        } else if (this.needsFocus(targetElement)) {
            if ((event.timeStamp - trackingClickStart) > 100 || (this.deviceIsIOS && window.top !== window && targetTagName === 'input')) {
                this.targetElement = null;
                return false
            }
            this.focus(targetElement);
            if (!this.deviceIsIOS4 || targetTagName !== 'select') {
                this.targetElement = null;
                event.preventDefault()
            }
            return false
        }
        if (this.deviceIsIOS && !this.deviceIsIOS4) {
            scrollParent = targetElement.fastClickScrollParent;
            if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
                return true
            }
        }
        if (!this.needsClick(targetElement)) {
            event.preventDefault();
            this.sendClick(targetElement, event)
        }
        return false
    };
    FastClick.prototype.onTouchCancel = function () {
        'use strict';
        this.trackingClick = false;
        this.targetElement = null
    };
    FastClick.prototype.onMouse = function (event) {
        'use strict';
        if (!this.targetElement) {
            return true
        }
        if (event.forwardedTouchEvent) {
            return true
        }
        if (!event.cancelable) {
            return true
        }
        if (!this.needsClick(this.targetElement) || this.cancelNextClick) {
            if (event.stopImmediatePropagation) {
                event.stopImmediatePropagation()
            } else {
                event.propagationStopped = true
            }
            event.stopPropagation();
            event.preventDefault();
            return false
        }
        return true
    };
    FastClick.prototype.onClick = function (event) {
        'use strict';
        var permitted;
        if (this.trackingClick) {
            this.targetElement = null;
            this.trackingClick = false;
            return true
        }
        if (event.target.type === 'submit' && event.detail === 0) {
            return true
        }
        permitted = this.onMouse(event);
        if (!permitted) {
            this.targetElement = null
        }
        return permitted
    };
    FastClick.prototype.destroy = function () {
        'use strict';
        var layer = this.layer;
        if (this.deviceIsAndroid) {
            layer.removeEventListener('mouseover', this.onMouse, true);
            layer.removeEventListener('mousedown', this.onMouse, true);
            layer.removeEventListener('mouseup', this.onMouse, true)
        }
        layer.removeEventListener('click', this.onClick, true);
        layer.removeEventListener('touchstart', this.onTouchStart, false);
        layer.removeEventListener('touchmove', this.onTouchMove, false);
        layer.removeEventListener('touchend', this.onTouchEnd, false);
        layer.removeEventListener('touchcancel', this.onTouchCancel, false)
    };
    FastClick.notNeeded = function (layer) {
        'use strict';
        var metaViewport;
        if (typeof window.ontouchstart === 'undefined') {
            return true
        }
        if ((/Chrome\/[0-9]+/).test(navigator.userAgent)) {
            if (FastClick.prototype.deviceIsAndroid) {
                metaViewport = document.querySelector('meta[name=viewport]');
                if (metaViewport && metaViewport.content.indexOf('user-scalable=no') !== -1) {
                    return true
                }
            } else {
                return true
            }
        }
        if (layer.style.msTouchAction === 'none') {
            return true
        }
        return false
    };
    FastClick.attach = function (layer) {
        'use strict';
        return new FastClick(layer)
    };
    if (typeof define !== 'undefined' && define.amd) {
        define(function () {
            'use strict';
            return FastClick
        })
    } else if (typeof module !== 'undefined' && module.exports) {
        module.exports = FastClick.attach;
        module.exports.FastClick = FastClick
    } else {
        window.FastClick = FastClick
    }


    // Enable FastClick
    if (typeof FastClick !== 'undefined') {
        FastClick.attach(document.body);
    }

    // private Fast Selector wrapper,
    // returns jQuery object. Only use where
    // getElementById is not available.
    var S = function (selector, context) {
        if (typeof selector === 'string') {
            if (context) {
                return $(context.querySelectorAll(selector));
            }

            return $(document.querySelectorAll(selector));
        }

        return $(selector, context);
    };

    /*
     https://github.com/paulirish/matchMedia.js
     */

    window.matchMedia = window.matchMedia || (function (doc, undefined) {

        "use strict";

        var bool,
            docElem = doc.documentElement,
            refNode = docElem.firstElementChild || docElem.firstChild,
        // fakeBody required for <FF4 when executed in <head>
            fakeBody = doc.createElement("body"),
            div = doc.createElement("div");

        div.id = "mq-test-1";
        div.style.cssText = "position:absolute;top:-100em";
        fakeBody.style.background = "none";
        fakeBody.appendChild(div);

        return function (q) {

            div.innerHTML = "&shy;<style media=\"" + q + "\"> #mq-test-1 { width: 42px; }</style>";

            docElem.insertBefore(fakeBody, refNode);
            bool = div.offsetWidth === 42;
            docElem.removeChild(fakeBody);

            return {
                matches: bool,
                media: q
            };

        };

    }(document));

    /*
     * jquery.requestAnimationFrame
     * https://github.com/gnarf37/jquery-requestAnimationFrame
     * Requires jQuery 1.8+
     *
     * Copyright (c) 2012 Corey Frang
     * Licensed under the MIT license.
     */

    (function ($) {

        // requestAnimationFrame polyfill adapted from Erik Möller
        // fixes from Paul Irish and Tino Zijdel
        // http://paulirish.com/2011/requestanimationframe-for-smart-animating/
        // http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating


        var animating,
            lastTime = 0,
            vendors = ['webkit', 'moz'],
            requestAnimationFrame = window.requestAnimationFrame,
            cancelAnimationFrame = window.cancelAnimationFrame;

        for (; lastTime < vendors.length && !requestAnimationFrame; lastTime++) {
            requestAnimationFrame = window[ vendors[lastTime] + "RequestAnimationFrame" ];
            cancelAnimationFrame = cancelAnimationFrame ||
                window[ vendors[lastTime] + "CancelAnimationFrame" ] ||
                window[ vendors[lastTime] + "CancelRequestAnimationFrame" ];
        }

        function raf() {
            if (animating) {
                requestAnimationFrame(raf);
                jQuery.fx.tick();
            }
        }

        if (requestAnimationFrame) {
            // use rAF
            window.requestAnimationFrame = requestAnimationFrame;
            window.cancelAnimationFrame = cancelAnimationFrame;
            jQuery.fx.timer = function (timer) {
                if (timer() && jQuery.timers.push(timer) && !animating) {
                    animating = true;
                    raf();
                }
            };

            jQuery.fx.stop = function () {
                animating = false;
            };
        } else {
            // polyfill
            window.requestAnimationFrame = function (callback, element) {
                var currTime = new Date().getTime(),
                    timeToCall = Math.max(0, 16 - ( currTime - lastTime )),
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

    }(jQuery));


    function removeQuotes(string) {
        if (typeof string === 'string' || string instanceof String) {
            string = string.replace(/^[\\/'"]+|(;\s?})+|[\\/'"]+$/g, '');
        }

        return string;
    }

    window.Foundation = {
        name: 'Foundation',

        version: '5.0.0',

        media_queries: {
            small: S('.foundation-mq-small').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
            medium: S('.foundation-mq-medium').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
            large: S('.foundation-mq-large').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
            xlarge: S('.foundation-mq-xlarge').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ''),
            xxlarge: S('.foundation-mq-xxlarge').css('font-family').replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, '')
        },

        stylesheet: $('<style></style>').appendTo('head')[0].sheet,

        init: function (scope, libraries, method, options, response) {
            var library_arr,
                args = [scope, method, options, response],
                responses = [];

            // check RTL
            this.rtl = /rtl/i.test(S('html').attr('dir'));

            // set foundation global scope
            this.scope = scope || this.scope;

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

        init_lib: function (lib, args) {
            if (this.libs.hasOwnProperty(lib)) {
                this.patch(this.libs[lib]);

                if (args && args.hasOwnProperty(lib)) {
                    return this.libs[lib].init.apply(this.libs[lib], [this.scope, args[lib]]);
                }

                return this.libs[lib].init.apply(this.libs[lib], args);
            }

            return function () {
            };
        },

        patch: function (lib) {
            lib.scope = this.scope;
            lib['data_options'] = this.lib_methods.data_options;
            lib['bindings'] = this.lib_methods.bindings;
            lib['S'] = S;
            lib.rtl = this.rtl;
        },

        inherit: function (scope, methods) {
            var methods_arr = methods.split(' ');

            for (var i = methods_arr.length - 1; i >= 0; i--) {
                if (this.lib_methods.hasOwnProperty(methods_arr[i])) {
                    this.libs[scope.name][methods_arr[i]] = this.lib_methods[methods_arr[i]];
                }
            }
        },

        random_str: function (length) {
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');

            if (!length) {
                length = Math.floor(Math.random() * chars.length);
            }

            var str = '';
            for (var i = 0; i < length; i++) {
                str += chars[Math.floor(Math.random() * chars.length)];
            }
            return str;
        },

        libs: {},

        // methods that can be inherited in libraries
        lib_methods: {
            throttle: function (fun, delay) {
                var timer = null;

                return function () {
                    var context = this, args = arguments;

                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        fun.apply(context, args);
                    }, delay);
                };
            },

            // parses data-options attribute
            data_options: function (el) {
                var opts = {}, ii, p, opts_arr, opts_len,
                    data_options = el.data('options');

                if (typeof data_options === 'object') {
                    return data_options;
                }

                opts_arr = (data_options || ':').split(';'),
                    opts_len = opts_arr.length;

                function isNumber(o) {
                    return !isNaN(o - 0) && o !== null && o !== "" && o !== false && o !== true;
                }

                function trim(str) {
                    if (typeof str === 'string') return $.trim(str);
                    return str;
                }

                // parse options
                for (ii = opts_len - 1; ii >= 0; ii--) {
                    p = opts_arr[ii].split(':');

                    if (/true/i.test(p[1])) p[1] = true;
                    if (/false/i.test(p[1])) p[1] = false;
                    if (isNumber(p[1])) p[1] = parseInt(p[1], 10);

                    if (p.length === 2 && p[0].length > 0) {
                        opts[trim(p[0])] = trim(p[1]);
                    }
                }

                return opts;
            },

            delay: function (fun, delay) {
                return setTimeout(fun, delay);
            },

            // test for empty object or array
            empty: function (obj) {
                if (obj.length && obj.length > 0)    return false;
                if (obj.length && obj.length === 0)  return true;

                for (var key in obj) {
                    if (hasOwnProperty.call(obj, key))    return false;
                }

                return true;
            },

            register_media: function (media, media_class) {
                if (Foundation.media_queries[media] === undefined) {
                    $('head').append('<meta class="' + media_class + '">');
                    Foundation.media_queries[media] = removeQuotes($('.' + media_class).css('font-family'));
                }
            },

            addCustomRule: function (rule, media) {
                if (media === undefined) {
                    Foundation.stylesheet.insertRule(rule, Foundation.stylesheet.cssRules.length);
                } else {
                    var query = Foundation.media_queries[media];
                    if (query !== undefined) {
                        Foundation.stylesheet.insertRule('@media ' +
                            Foundation.media_queries[media] + '{ ' + rule + ' }');
                    }
                }
            },

            loaded: function (image, callback) {
                function loaded() {
                    callback(image[0]);
                }

                function bindLoad() {
                    this.one('load', loaded);

                    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
                        var src = this.attr('src'),
                            param = src.match(/\?/) ? '&' : '?';

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
            },

            bindings: function (method, options) {
                var self = this,
                    should_bind_events = !S(this).data(this.name + '-init');

                if (typeof method === 'string') {
                    return this[method].call(this);
                }

                if (S(this.scope).is('[data-' + this.name + ']')) {
                    S(this.scope).data(this.name + '-init', $.extend({}, this.settings, (options || method), this.data_options(S(this.scope))));

                    if (should_bind_events) {
                        this.events(this.scope);
                    }

                } else {
                    S('[data-' + this.name + ']', this.scope).each(function () {
                        var should_bind_events = !S(this).data(self.name + '-init');

                        S(this).data(self.name + '-init', $.extend({}, self.settings, (options || method), self.data_options(S(this))));

                        if (should_bind_events) {
                            self.events(this);
                        }
                    });
                }
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

}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.abide = {
        name: 'abide',

        version: '5.0.0',

        settings: {
            focus_on_invalid: true,
            timeout: 1000,
            patterns: {
                alpha: /[a-zA-Z]+/,
                alpha_numeric: /[a-zA-Z0-9]+/,
                integer: /-?\d+/,
                number: /-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?/,

                // generic password: upper-case, lower-case, number/special character, and min 8 characters
                password: /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/,

                // amex, visa, diners
                card: /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/,
                cvv: /^([0-9]){3,4}$/,

                // http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#valid-e-mail-address
                email: /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,

                url: /(https?|ftp|file|ssh):\/\/(((([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?/,
                // abc.de
                domain: /^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/,

                datetime: /([0-2][0-9]{3})\-([0-1][0-9])\-([0-3][0-9])T([0-5][0-9])\:([0-5][0-9])\:([0-5][0-9])(Z|([\-\+]([0-1][0-9])\:00))/,
                // YYYY-MM-DD
                date: /(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))/,
                // HH:MM:SS
                time: /(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}/,
                dateISO: /\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}/,
                // MM/DD/YYYY
                month_day_year: /(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](19|20)\d\d/,

                // #FFF or #FFFFFF
                color: /^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/
            }
        },

        timer: null,

        init: function (scope, method, options) {
            this.bindings(method, options);
        },

        events: function (scope) {
            var self = this,
                form = $(scope).attr('novalidate', 'novalidate'),
                settings = form.data('abide-init');

            form
                .off('.abide')
                .on('submit.fndtn.abide validate.fndtn.abide', function (e) {
                    var is_ajax = /ajax/i.test($(this).attr('data-abide'));
                    return self.validate($(this).find('input, textarea, select').get(), e, is_ajax);
                })
                .find('input, textarea, select')
                .off('.abide')
                .on('blur.fndtn.abide change.fndtn.abide', function (e) {
                    self.validate([this], e);
                })
                .on('keydown.fndtn.abide', function (e) {
                    var settings = $(this).closest('form').data('abide-init');
                    clearTimeout(self.timer);
                    self.timer = setTimeout(function () {
                        self.validate([this], e);
                    }.bind(this), settings.timeout);
                });
        },

        validate: function (els, e, is_ajax) {
            var validations = this.parse_patterns(els),
                validation_count = validations.length,
                form = $(els[0]).closest('form'),
                submit_event = /submit/.test(e.type);

            for (var i = 0; i < validation_count; i++) {
                if (!validations[i] && (submit_event || is_ajax)) {
                    if (this.settings.focus_on_invalid) els[i].focus();
                    form.trigger('invalid');
                    $(els[i]).closest('form').attr('data-invalid', '');
                    return false;
                }
            }

            if (submit_event || is_ajax) {
                form.trigger('valid');
            }

            form.removeAttr('data-invalid');

            if (is_ajax) return false;

            return true;
        },

        parse_patterns: function (els) {
            var count = els.length,
                el_patterns = [];

            for (var i = count - 1; i >= 0; i--) {
                el_patterns.push(this.pattern(els[i]));
            }

            return this.check_validation_and_apply_styles(el_patterns);
        },

        pattern: function (el) {
            var type = el.getAttribute('type'),
                required = typeof el.getAttribute('required') === 'string';

            if (this.settings.patterns.hasOwnProperty(type)) {
                return [el, this.settings.patterns[type], required];
            }

            var pattern = el.getAttribute('pattern') || '';

            if (this.settings.patterns.hasOwnProperty(pattern) && pattern.length > 0) {
                return [el, this.settings.patterns[pattern], required];
            } else if (pattern.length > 0) {
                return [el, new RegExp(pattern), required];
            }

            pattern = /.*/;

            return [el, pattern, required];
        },

        check_validation_and_apply_styles: function (el_patterns) {
            var count = el_patterns.length,
                validations = [];

            for (var i = count - 1; i >= 0; i--) {
                var el = el_patterns[i][0],
                    required = el_patterns[i][2],
                    value = el.value,
                    is_equal = el.getAttribute('data-equalto'),
                    is_radio = el.type === "radio",
                    valid_length = (required) ? (el.value.length > 0) : true;

                if (is_radio && required) {
                    validations.push(this.valid_radio(el, required));
                } else if (is_equal && required) {
                    validations.push(this.valid_equal(el, required));
                } else {
                    if (el_patterns[i][1].test(value) && valid_length ||
                        !required && el.value.length < 1) {
                        $(el).removeAttr('data-invalid').parent().removeClass('error');
                        validations.push(true);
                    } else {
                        $(el).attr('data-invalid', '').parent().addClass('error');
                        validations.push(false);
                    }
                }
            }

            return validations;
        },

        valid_radio: function (el, required) {
            var name = el.getAttribute('name'),
                group = document.getElementsByName(name),
                count = group.length,
                valid = false;

            for (var i = 0; i < count; i++) {
                if (group[i].checked) valid = true;
            }

            for (var i = 0; i < count; i++) {
                if (valid) {
                    $(group[i]).removeAttr('data-invalid').parent().removeClass('error');
                } else {
                    $(group[i]).attr('data-invalid', '').parent().addClass('error');
                }
            }

            return valid;
        },

        valid_equal: function (el, required) {
            var from = document.getElementById(el.getAttribute('data-equalto')).value,
                to = el.value,
                valid = (from === to);

            if (valid) {
                $(el).removeAttr('data-invalid').parent().removeClass('error');
            } else {
                $(el).attr('data-invalid', '').parent().addClass('error');
            }

            return valid;
        }
    };
}(jQuery, this, this.document));;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.accordion = {
        name: 'accordion',

        version: '5.0.1',

        settings: {
            active_class: 'active',
            toggleable: true
        },

        init: function (scope, method, options) {
            this.bindings(method, options);
        },

        events: function () {
            $(this.scope).off('.accordion').on('click.fndtn.accordion', '[data-accordion] > dd > a', function (e) {
                var accordion = $(this).parent(),
                    target = $('#' + this.href.split('#')[1]),
                    siblings = $('> dd > .content', target.closest('[data-accordion]')),
                    settings = accordion.parent().data('accordion-init'),
                    active = $('> dd > .content.' + settings.active_class, accordion.parent());

                e.preventDefault();

                if (active[0] == target[0] && settings.toggleable) {
                    return target.toggleClass(settings.active_class);
                }

                siblings.removeClass(settings.active_class);
                target.addClass(settings.active_class);
            });
        },

        off: function () {
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.alert = {
        name: 'alert',

        version: '5.0.0',

        settings: {
            animation: 'fadeOut',
            speed: 300, // fade out speed
            callback: function () {
            }
        },

        init: function (scope, method, options) {
            this.bindings(method, options);
        },

        events: function () {
            $(this.scope).off('.alert').on('click.fndtn.alert', '[data-alert] a.close', function (e) {
                var alertBox = $(this).closest("[data-alert]"),
                    settings = alertBox.data('alert-init');

                e.preventDefault();
                alertBox[settings.animation](settings.speed, function () {
                    $(this).trigger('closed').remove();
                    settings.callback();
                });
            });
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.clearing = {
        name: 'clearing',

        version: '5.0.0',

        settings: {
            templates: {
                viewing: '<a href="#" class="clearing-close">&times;</a>' +
                    '<div class="visible-img" style="display: none"><img src="//:0">' +
                    '<p class="clearing-caption"></p><a href="#" class="clearing-main-prev"><span></span></a>' +
                    '<a href="#" class="clearing-main-next"><span></span></a></div>'
            },

            // comma delimited list of selectors that, on click, will close clearing,
            // add 'div.clearing-blackout, div.visible-img' to close on background click
            close_selectors: '.clearing-close',

            // event initializers and locks
            init: false,
            locked: false
        },

        init: function (scope, method, options) {
            var self = this;
            Foundation.inherit(this, 'throttle loaded');

            this.bindings(method, options);

            if ($(this.scope).is('[data-clearing]')) {
                this.assemble($('li', this.scope));
            } else {
                $('[data-clearing]', this.scope).each(function () {
                    self.assemble($('li', this));
                });
            }
        },

        events: function (scope) {
            var self = this;

            $(this.scope)
                .off('.clearing')
                .on('click.fndtn.clearing', 'ul[data-clearing] li',
                function (e, current, target) {
                    var current = current || $(this),
                        target = target || current,
                        next = current.next('li'),
                        settings = current.closest('[data-clearing]').data('clearing-init'),
                        image = $(e.target);

                    e.preventDefault();

                    if (!settings) {
                        self.init();
                        settings = current.closest('[data-clearing]').data('clearing-init');
                    }

                    // if clearing is open and the current image is
                    // clicked, go to the next image in sequence
                    if (target.hasClass('visible') &&
                        current[0] === target[0] &&
                        next.length > 0 && self.is_open(current)) {
                        target = next;
                        image = $('img', target);
                    }

                    // set current and target to the clicked li if not otherwise defined.
                    self.open(image, current, target);
                    self.update_paddles(target);
                })

                .on('click.fndtn.clearing', '.clearing-main-next',
                function (e) {
                    self.nav(e, 'next')
                })
                .on('click.fndtn.clearing', '.clearing-main-prev',
                function (e) {
                    self.nav(e, 'prev')
                })
                .on('click.fndtn.clearing', this.settings.close_selectors,
                function (e) {
                    Foundation.libs.clearing.close(e, this)
                })
                .on('keydown.fndtn.clearing',
                function (e) {
                    self.keydown(e)
                });

            $(window).off('.clearing').on('resize.fndtn.clearing',
                function () {
                    self.resize()
                });

            this.swipe_events(scope);
        },

        swipe_events: function (scope) {
            var self = this;

            $(this.scope)
                .on('touchstart.fndtn.clearing', '.visible-img', function (e) {
                    if (!e.touches) {
                        e = e.originalEvent;
                    }
                    var data = {
                        start_page_x: e.touches[0].pageX,
                        start_page_y: e.touches[0].pageY,
                        start_time: (new Date()).getTime(),
                        delta_x: 0,
                        is_scrolling: undefined
                    };

                    $(this).data('swipe-transition', data);
                    e.stopPropagation();
                })
                .on('touchmove.fndtn.clearing', '.visible-img', function (e) {
                    if (!e.touches) {
                        e = e.originalEvent;
                    }
                    // Ignore pinch/zoom events
                    if (e.touches.length > 1 || e.scale && e.scale !== 1) return;

                    var data = $(this).data('swipe-transition');

                    if (typeof data === 'undefined') {
                        data = {};
                    }

                    data.delta_x = e.touches[0].pageX - data.start_page_x;

                    if (typeof data.is_scrolling === 'undefined') {
                        data.is_scrolling = !!( data.is_scrolling || Math.abs(data.delta_x) < Math.abs(e.touches[0].pageY - data.start_page_y) );
                    }

                    if (!data.is_scrolling && !data.active) {
                        e.preventDefault();
                        var direction = (data.delta_x < 0) ? 'next' : 'prev';
                        data.active = true;
                        self.nav(e, direction);
                    }
                })
                .on('touchend.fndtn.clearing', '.visible-img', function (e) {
                    $(this).data('swipe-transition', {});
                    e.stopPropagation();
                });
        },

        assemble: function ($li) {
            var $el = $li.parent();

            if ($el.parent().hasClass('carousel')) return;
            $el.after('<div id="foundationClearingHolder"></div>');

            var holder = $('#foundationClearingHolder'),
                settings = $el.data('clearing-init'),
                grid = $el.detach(),
                data = {
                    grid: '<div class="carousel">' + grid[0].outerHTML + '</div>',
                    viewing: settings.templates.viewing
                },
                wrapper = '<div class="clearing-assembled"><div>' + data.viewing +
                    data.grid + '</div></div>';

            return holder.after(wrapper).remove();
        },

        open: function ($image, current, target) {
            var root = target.closest('.clearing-assembled'),
                container = $('div', root).first(),
                visible_image = $('.visible-img', container),
                image = $('img', visible_image).not($image);

            if (!this.locked()) {
                // set the image to the selected thumbnail
                image
                    .attr('src', this.load($image))
                    .css('visibility', 'hidden');

                this.loaded(image, function () {
                    image.css('visibility', 'visible');
                    // toggle the gallery
                    root.addClass('clearing-blackout');
                    container.addClass('clearing-container');
                    visible_image.show();
                    this.fix_height(target)
                        .caption($('.clearing-caption', visible_image), $image)
                        .center(image)
                        .shift(current, target, function () {
                            target.siblings().removeClass('visible');
                            target.addClass('visible');
                        });
                }.bind(this));
            }
        },

        close: function (e, el) {
            e.preventDefault();

            var root = (function (target) {
                if (/blackout/.test(target.selector)) {
                    return target;
                } else {
                    return target.closest('.clearing-blackout');
                }
            }($(el))), container, visible_image;

            if (el === e.target && root) {
                container = $('div', root).first();
                visible_image = $('.visible-img', container);
                this.settings.prev_index = 0;
                $('ul[data-clearing]', root)
                    .attr('style', '').closest('.clearing-blackout')
                    .removeClass('clearing-blackout');
                container.removeClass('clearing-container');
                visible_image.hide();
            }

            return false;
        },

        is_open: function (current) {
            return current.parent().prop('style').length > 0;
        },

        keydown: function (e) {
            var clearing = $('ul[data-clearing]', '.clearing-blackout');

            if (e.which === 39) this.go(clearing, 'next');
            if (e.which === 37) this.go(clearing, 'prev');
            if (e.which === 27) $('a.clearing-close').trigger('click');
        },

        nav: function (e, direction) {
            var clearing = $('ul[data-clearing]', '.clearing-blackout');

            e.preventDefault();
            this.go(clearing, direction);
        },

        resize: function () {
            var image = $('img', '.clearing-blackout .visible-img');

            if (image.length) {
                this.center(image);
            }
        },

        // visual adjustments
        fix_height: function (target) {
            var lis = target.parent().children(),
                self = this;

            lis.each(function () {
                var li = $(this),
                    image = li.find('img');

                if (li.height() > image.outerHeight()) {
                    li.addClass('fix-height');
                }
            })
                .closest('ul')
                .width(lis.length * 100 + '%');

            return this;
        },

        update_paddles: function (target) {
            var visible_image = target
                .closest('.carousel')
                .siblings('.visible-img');

            if (target.next().length > 0) {
                $('.clearing-main-next', visible_image)
                    .removeClass('disabled');
            } else {
                $('.clearing-main-next', visible_image)
                    .addClass('disabled');
            }

            if (target.prev().length > 0) {
                $('.clearing-main-prev', visible_image)
                    .removeClass('disabled');
            } else {
                $('.clearing-main-prev', visible_image)
                    .addClass('disabled');
            }
        },

        center: function (target) {
            if (!this.rtl) {
                target.css({
                    marginLeft: -(target.outerWidth() / 2),
                    marginTop: -(target.outerHeight() / 2)
                });
            } else {
                target.css({
                    marginRight: -(target.outerWidth() / 2),
                    marginTop: -(target.outerHeight() / 2)
                });
            }
            return this;
        },

        // image loading and preloading

        load: function ($image) {
            if ($image[0].nodeName === "A") {
                var href = $image.attr('href');
            } else {
                var href = $image.parent().attr('href');
            }

            this.preload($image);

            if (href) return href;
            return $image.attr('src');
        },

        preload: function ($image) {
            this
                .img($image.closest('li').next())
                .img($image.closest('li').prev());
        },

        img: function (img) {
            if (img.length) {
                var new_img = new Image(),
                    new_a = $('a', img);

                if (new_a.length) {
                    new_img.src = new_a.attr('href');
                } else {
                    new_img.src = $('img', img).attr('src');
                }
            }
            return this;
        },

        // image caption

        caption: function (container, $image) {
            var caption = $image.data('caption');

            if (caption) {
                container
                    .html(caption)
                    .show();
            } else {
                container
                    .text('')
                    .hide();
            }
            return this;
        },

        // directional methods

        go: function ($ul, direction) {
            var current = $('.visible', $ul),
                target = current[direction]();

            if (target.length) {
                $('img', target)
                    .trigger('click', [current, target]);
            }
        },

        shift: function (current, target, callback) {
            var clearing = target.parent(),
                old_index = this.settings.prev_index || target.index(),
                direction = this.direction(clearing, current, target),
                left = parseInt(clearing.css('left'), 10),
                width = target.outerWidth(),
                skip_shift;

            // we use jQuery animate instead of CSS transitions because we
            // need a callback to unlock the next animation
            if (target.index() !== old_index && !/skip/.test(direction)) {
                if (/left/.test(direction)) {
                    this.lock();
                    clearing.animate({left: left + width}, 300, this.unlock());
                } else if (/right/.test(direction)) {
                    this.lock();
                    clearing.animate({left: left - width}, 300, this.unlock());
                }
            } else if (/skip/.test(direction)) {
                // the target image is not adjacent to the current image, so
                // do we scroll right or not
                skip_shift = target.index() - this.settings.up_count;
                this.lock();

                if (skip_shift > 0) {
                    clearing.animate({left: -(skip_shift * width)}, 300, this.unlock());
                } else {
                    clearing.animate({left: 0}, 300, this.unlock());
                }
            }

            callback();
        },

        direction: function ($el, current, target) {
            var lis = $('li', $el),
                li_width = lis.outerWidth() + (lis.outerWidth() / 4),
                up_count = Math.floor($('.clearing-container').outerWidth() / li_width) - 1,
                target_index = lis.index(target),
                response;

            this.settings.up_count = up_count;

            if (this.adjacent(this.settings.prev_index, target_index)) {
                if ((target_index > up_count)
                    && target_index > this.settings.prev_index) {
                    response = 'right';
                } else if ((target_index > up_count - 1)
                    && target_index <= this.settings.prev_index) {
                    response = 'left';
                } else {
                    response = false;
                }
            } else {
                response = 'skip';
            }

            this.settings.prev_index = target_index;

            return response;
        },

        adjacent: function (current_index, target_index) {
            for (var i = target_index + 1; i >= target_index - 1; i--) {
                if (i === current_index) return true;
            }
            return false;
        },

        // lock management

        lock: function () {
            this.settings.locked = true;
        },

        unlock: function () {
            this.settings.locked = false;
        },

        locked: function () {
            return this.settings.locked;
        },

        off: function () {
            $(this.scope).off('.fndtn.clearing');
            $(window).off('.fndtn.clearing');
        },

        reflow: function () {
            this.init();
        }
    };

}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.dropdown = {
        name: 'dropdown',

        version: '5.0.0',

        settings: {
            active_class: 'open',
            is_hover: false,
            opened: function () {
            },
            closed: function () {
            }
        },

        init: function (scope, method, options) {
            Foundation.inherit(this, 'throttle');

            this.bindings(method, options);
        },

        events: function (scope) {
            var self = this;

            $(this.scope)
                .off('.dropdown')
                .on('click.fndtn.dropdown', '[data-dropdown]', function (e) {
                    var settings = $(this).data('dropdown-init');
                    e.preventDefault();

                    if (!settings.is_hover || Modernizr.touch) self.toggle($(this));
                })
                .on('mouseenter.fndtn.dropdown', '[data-dropdown], [data-dropdown-content]', function (e) {
                    var $this = $(this);
                    clearTimeout(self.timeout);

                    if ($this.data('dropdown')) {
                        var dropdown = $('#' + $this.data('dropdown')),
                            target = $this;
                    } else {
                        var dropdown = $this;
                        target = $("[data-dropdown='" + dropdown.attr('id') + "']");
                    }

                    var settings = target.data('dropdown-init');
                    if (settings.is_hover) self.open.apply(self, [dropdown, target]);
                })
                .on('mouseleave.fndtn.dropdown', '[data-dropdown], [data-dropdown-content]', function (e) {
                    var $this = $(this);
                    self.timeout = setTimeout(function () {
                        if ($this.data('dropdown')) {
                            var settings = $this.data('dropdown-init');
                            if (settings.is_hover) self.close.call(self, $('#' + $this.data('dropdown')));
                        } else {
                            var target = $('[data-dropdown="' + $(this).attr('id') + '"]'),
                                settings = target.data('dropdown-init');
                            if (settings.is_hover) self.close.call(self, $this);
                        }
                    }.bind(this), 150);
                })
                .on('click.fndtn.dropdown', function (e) {
                    var parent = $(e.target).closest('[data-dropdown-content]');

                    if ($(e.target).data('dropdown') || $(e.target).parent().data('dropdown')) {
                        return;
                    }
                    if (!($(e.target).data('revealId')) &&
                        (parent.length > 0 && ($(e.target).is('[data-dropdown-content]') ||
                            $.contains(parent.first()[0], e.target)))) {
                        e.stopPropagation();
                        return;
                    }

                    self.close.call(self, $('[data-dropdown-content]'));
                })
                .on('opened.fndtn.dropdown', '[data-dropdown-content]', this.settings.opened)
                .on('closed.fndtn.dropdown', '[data-dropdown-content]', this.settings.closed);

            $(window)
                .off('.dropdown')
                .on('resize.fndtn.dropdown', self.throttle(function () {
                    self.resize.call(self);
                }, 50)).trigger('resize');
        },

        close: function (dropdown) {
            var self = this;
            dropdown.each(function () {
                if ($(this).hasClass(self.settings.active_class)) {
                    $(this)
                        .css(Foundation.rtl ? 'right' : 'left', '-99999px')
                        .removeClass(self.settings.active_class);
                    $(this).trigger('closed');
                }
            });
        },

        open: function (dropdown, target) {
            this
                .css(dropdown
                    .addClass(this.settings.active_class), target);
            dropdown.trigger('opened');
        },

        toggle: function (target) {
            var dropdown = $('#' + target.data('dropdown'));
            if (dropdown.length === 0) {
                // No dropdown found, not continuing
                return;
            }

            this.close.call(this, $('[data-dropdown-content]').not(dropdown));

            if (dropdown.hasClass(this.settings.active_class)) {
                this.close.call(this, dropdown);
            } else {
                this.close.call(this, $('[data-dropdown-content]'))
                this.open.call(this, dropdown, target);
            }
        },

        resize: function () {
            var dropdown = $('[data-dropdown-content].open'),
                target = $("[data-dropdown='" + dropdown.attr('id') + "']");

            if (dropdown.length && target.length) {
                this.css(dropdown, target);
            }
        },

        css: function (dropdown, target) {
            var offset_parent = dropdown.offsetParent(),
                position = target.offset();

            position.top -= offset_parent.offset().top;
            position.left -= offset_parent.offset().left;

            if (this.small()) {
                dropdown.css({
                    position: 'absolute',
                    width: '95%',
                    'max-width': 'none',
                    top: position.top + target.outerHeight()
                });
                dropdown.css(Foundation.rtl ? 'right' : 'left', '2.5%');
            } else {
                if (!Foundation.rtl && $(window).width() > dropdown.outerWidth() + target.offset().left) {
                    var left = position.left;
                    if (dropdown.hasClass('right')) {
                        dropdown.removeClass('right');
                    }
                } else {
                    if (!dropdown.hasClass('right')) {
                        dropdown.addClass('right');
                    }
                    var left = position.left - (dropdown.outerWidth() - target.outerWidth());
                }

                dropdown.attr('style', '').css({
                    position: 'absolute',
                    top: position.top + target.outerHeight(),
                    left: left
                });
            }

            return dropdown;
        },

        small: function () {
            return matchMedia(Foundation.media_queries.small).matches && !matchMedia(Foundation.media_queries.medium).matches;
        },

        off: function () {
            $(this.scope).off('.fndtn.dropdown');
            $('html, body').off('.fndtn.dropdown');
            $(window).off('.fndtn.dropdown');
            $('[data-dropdown-content]').off('.fndtn.dropdown');
            this.settings.init = false;
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.interchange = {
        name: 'interchange',

        version: '5.0.0',

        cache: {},

        images_loaded: false,
        nodes_loaded: false,

        settings: {
            load_attr: 'interchange',

            named_queries: {
                'default': Foundation.media_queries.small,
                small: Foundation.media_queries.small,
                medium: Foundation.media_queries.medium,
                large: Foundation.media_queries.large,
                xlarge: Foundation.media_queries.xlarge,
                xxlarge: Foundation.media_queries.xxlarge,
                landscape: 'only screen and (orientation: landscape)',
                portrait: 'only screen and (orientation: portrait)',
                retina: 'only screen and (-webkit-min-device-pixel-ratio: 2),' +
                    'only screen and (min--moz-device-pixel-ratio: 2),' +
                    'only screen and (-o-min-device-pixel-ratio: 2/1),' +
                    'only screen and (min-device-pixel-ratio: 2),' +
                    'only screen and (min-resolution: 192dpi),' +
                    'only screen and (min-resolution: 2dppx)'
            },

            directives: {
                replace: function (el, path, trigger) {
                    // The trigger argument, if called within the directive, fires
                    // an event named after the directive on the element, passing
                    // any parameters along to the event that you pass to trigger.
                    //
                    // ex. trigger(), trigger([a, b, c]), or trigger(a, b, c)
                    //
                    // This allows you to bind a callback like so:
                    // $('#interchangeContainer').on('replace', function (e, a, b, c) {
                    //   console.log($(this).html(), a, b, c);
                    // });

                    if (/IMG/.test(el[0].nodeName)) {
                        var orig_path = el[0].src;

                        if (new RegExp(path, 'i').test(orig_path)) return;

                        el[0].src = path;

                        return trigger(el[0].src);
                    }
                    var last_path = el.data('interchange-last-path');

                    if (last_path == path) return;

                    return $.get(path, function (response) {
                        el.html(response);
                        el.data('interchange-last-path', path);
                        trigger();
                    });

                }
            }
        },

        init: function (scope, method, options) {
            Foundation.inherit(this, 'throttle');

            this.data_attr = 'data-' + this.settings.load_attr;

            this.bindings(method, options);
            this.load('images');
            this.load('nodes');
        },

        events: function () {
            var self = this;

            $(window)
                .off('.interchange')
                .on('resize.fndtn.interchange', self.throttle(function () {
                    self.resize.call(self);
                }, 50));

            return this;
        },

        resize: function () {
            var cache = this.cache;

            if (!this.images_loaded || !this.nodes_loaded) {
                setTimeout($.proxy(this.resize, this), 50);
                return;
            }

            for (var uuid in cache) {
                if (cache.hasOwnProperty(uuid)) {
                    var passed = this.results(uuid, cache[uuid]);

                    if (passed) {
                        this.settings.directives[passed
                            .scenario[1]](passed.el, passed.scenario[0], function () {
                            if (arguments[0] instanceof Array) {
                                var args = arguments[0];
                            } else {
                                var args = Array.prototype.slice.call(arguments, 0);
                            }

                            passed.el.trigger(passed.scenario[1], args);
                        });
                    }
                }
            }

        },

        results: function (uuid, scenarios) {
            var count = scenarios.length;

            if (count > 0) {
                var el = this.S('[data-uuid="' + uuid + '"]');

                for (var i = count - 1; i >= 0; i--) {
                    var mq, rule = scenarios[i][2];
                    if (this.settings.named_queries.hasOwnProperty(rule)) {
                        mq = matchMedia(this.settings.named_queries[rule]);
                    } else {
                        mq = matchMedia(rule);
                    }
                    if (mq.matches) {
                        return {el: el, scenario: scenarios[i]};
                    }
                }
            }

            return false;
        },

        load: function (type, force_update) {
            if (typeof this['cached_' + type] === 'undefined' || force_update) {
                this['update_' + type]();
            }

            return this['cached_' + type];
        },

        update_images: function () {
            var images = this.S('img[' + this.data_attr + ']'),
                count = images.length,
                loaded_count = 0,
                data_attr = this.data_attr;

            this.cache = {};
            this.cached_images = [];
            this.images_loaded = (count === 0);

            for (var i = count - 1; i >= 0; i--) {
                loaded_count++;
                if (images[i]) {
                    var str = images[i].getAttribute(data_attr) || '';

                    if (str.length > 0) {
                        this.cached_images.push(images[i]);
                    }
                }

                if (loaded_count === count) {
                    this.images_loaded = true;
                    this.enhance('images');
                }
            }

            return this;
        },

        update_nodes: function () {
            var nodes = this.S('[' + this.data_attr + ']:not(img)'),
                count = nodes.length,
                loaded_count = 0,
                data_attr = this.data_attr;

            this.cached_nodes = [];
            // Set nodes_loaded to true if there are no nodes
            // this.nodes_loaded = false;
            this.nodes_loaded = (count === 0);


            for (var i = count - 1; i >= 0; i--) {
                loaded_count++;
                var str = nodes[i].getAttribute(data_attr) || '';

                if (str.length > 0) {
                    this.cached_nodes.push(nodes[i]);
                }

                if (loaded_count === count) {
                    this.nodes_loaded = true;
                    this.enhance('nodes');
                }
            }

            return this;
        },

        enhance: function (type) {
            var count = this['cached_' + type].length;

            for (var i = count - 1; i >= 0; i--) {
                this.object($(this['cached_' + type][i]));
            }

            return $(window).trigger('resize');
        },

        parse_params: function (path, directive, mq) {
            return [this.trim(path), this.convert_directive(directive), this.trim(mq)];
        },

        convert_directive: function (directive) {
            var trimmed = this.trim(directive);

            if (trimmed.length > 0) {
                return trimmed;
            }

            return 'replace';
        },

        object: function (el) {
            var raw_arr = this.parse_data_attr(el),
                scenarios = [], count = raw_arr.length;

            if (count > 0) {
                for (var i = count - 1; i >= 0; i--) {
                    var split = raw_arr[i].split(/\((.*?)(\))$/);

                    if (split.length > 1) {
                        var cached_split = split[0].split(','),
                            params = this.parse_params(cached_split[0],
                                cached_split[1], split[1]);

                        scenarios.push(params);
                    }
                }
            }

            return this.store(el, scenarios);
        },

        uuid: function (separator) {
            var delim = separator || "-";

            function S4() {
                return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
            }

            return (S4() + S4() + delim + S4() + delim + S4()
                + delim + S4() + delim + S4() + S4() + S4());
        },

        store: function (el, scenarios) {
            var uuid = this.uuid(),
                current_uuid = el.data('uuid');

            if (current_uuid) return this.cache[current_uuid];

            el.attr('data-uuid', uuid);

            return this.cache[uuid] = scenarios;
        },

        trim: function (str) {
            if (typeof str === 'string') {
                return $.trim(str);
            }

            return str;
        },

        parse_data_attr: function (el) {
            var raw = el.data(this.settings.load_attr).split(/\[(.*?)\]/),
                count = raw.length, output = [];

            for (var i = count - 1; i >= 0; i--) {
                if (raw[i].replace(/[\W\d]+/, '').length > 4) {
                    output.push(raw[i]);
                }
            }

            return output;
        },

        reflow: function () {
            this.load('images', true);
            this.load('nodes', true);
        }

    };

}(jQuery, this, this.document));;
(function ($, window, document, undefined) {
    'use strict';

    var Modernizr = Modernizr || false;

    Foundation.libs.joyride = {
        name: 'joyride',

        version: '5.0.0',

        defaults: {
            expose: false,      // turn on or off the expose feature
            modal: true,      // Whether to cover page with modal during the tour
            tip_location: 'bottom',  // 'top' or 'bottom' in relation to parent
            nub_position: 'auto',    // override on a per tooltip bases
            scroll_speed: 1500,       // Page scrolling speed in milliseconds, 0 = no scroll animation
            scroll_animation: 'linear',   // supports 'swing' and 'linear', extend with jQuery UI.
            timer: 0,         // 0 = no timer , all other numbers = timer in milliseconds
            start_timer_on_click: true,      // true or false - true requires clicking the first button start the timer
            start_offset: 0,         // the index of the tooltip you want to start on (index of the li)
            next_button: true,      // true or false to control whether a next button is used
            tip_animation: 'fade',    // 'pop' or 'fade' in each tip
            pause_after: [],        // array of indexes where to pause the tour after
            exposed: [],        // array of expose elements
            tip_animation_fade_speed: 300,       // when tipAnimation = 'fade' this is speed in milliseconds for the transition
            cookie_monster: false,     // true or false to control whether cookies are used
            cookie_name: 'joyride', // Name the cookie you'll use
            cookie_domain: false,     // Will this cookie be attached to a domain, ie. '.notableapp.com'
            cookie_expires: 365,       // set when you would like the cookie to expire.
            tip_container: 'body',    // Where will the tip be attached
            tip_location_patterns: {
                top: ['bottom'],
                bottom: [], // bottom should not need to be repositioned
                left: ['right', 'top', 'bottom'],
                right: ['left', 'top', 'bottom']
            },
            post_ride_callback: function () {
            },    // A method to call once the tour closes (canceled or complete)
            post_step_callback: function () {
            },    // A method to call after each step
            pre_step_callback: function () {
            },    // A method to call before each step
            pre_ride_callback: function () {
            },    // A method to call before the tour starts (passed index, tip, and cloned exposed element)
            post_expose_callback: function () {
            },    // A method to call after an element has been exposed
            template: { // HTML segments for tip layout
                link: '<a href="#close" class="joyride-close-tip">&times;</a>',
                timer: '<div class="joyride-timer-indicator-wrap"><span class="joyride-timer-indicator"></span></div>',
                tip: '<div class="joyride-tip-guide"><span class="joyride-nub"></span></div>',
                wrapper: '<div class="joyride-content-wrapper"></div>',
                button: '<a href="#" class="small button joyride-next-tip"></a>',
                modal: '<div class="joyride-modal-bg"></div>',
                expose: '<div class="joyride-expose-wrapper"></div>',
                expose_cover: '<div class="joyride-expose-cover"></div>'
            },
            expose_add_class: '' // One or more space-separated class names to be added to exposed element
        },

        init: function (scope, method, options) {
            Foundation.inherit(this, 'throttle delay');

            this.settings = this.defaults;

            this.bindings(method, options)
        },

        events: function () {
            var self = this;

            $(this.scope)
                .off('.joyride')
                .on('click.fndtn.joyride', '.joyride-next-tip, .joyride-modal-bg', function (e) {
                    e.preventDefault();

                    if (this.settings.$li.next().length < 1) {
                        this.end();
                    } else if (this.settings.timer > 0) {
                        clearTimeout(this.settings.automate);
                        this.hide();
                        this.show();
                        this.startTimer();
                    } else {
                        this.hide();
                        this.show();
                    }

                }.bind(this))

                .on('click.fndtn.joyride', '.joyride-close-tip', function (e) {
                    e.preventDefault();
                    this.end();
                }.bind(this));

            $(window)
                .off('.joyride')
                .on('resize.fndtn.joyride', self.throttle(function () {
                    if ($('[data-joyride]').length > 0 && self.settings.$next_tip) {
                        if (self.settings.exposed.length > 0) {
                            var $els = $(self.settings.exposed);

                            $els.each(function () {
                                var $this = $(this);
                                self.un_expose($this);
                                self.expose($this);
                            });
                        }

                        if (self.is_phone()) {
                            self.pos_phone();
                        } else {
                            self.pos_default(false, true);
                        }
                    }
                }, 100));
        },

        start: function () {
            var self = this,
                $this = $('[data-joyride]', this.scope),
                integer_settings = ['timer', 'scrollSpeed', 'startOffset', 'tipAnimationFadeSpeed', 'cookieExpires'],
                int_settings_count = integer_settings.length;

            if (!$this.length > 0) return;

            if (!this.settings.init) this.events();

            this.settings = $this.data('joyride-init');

            // non configureable settings
            this.settings.$content_el = $this;
            this.settings.$body = $(this.settings.tip_container);
            this.settings.body_offset = $(this.settings.tip_container).position();
            this.settings.$tip_content = this.settings.$content_el.find('> li');
            this.settings.paused = false;
            this.settings.attempts = 0;

            // can we create cookies?
            if (typeof $.cookie !== 'function') {
                this.settings.cookie_monster = false;
            }

            // generate the tips and insert into dom.
            if (!this.settings.cookie_monster || this.settings.cookie_monster && $.cookie(this.settings.cookie_name) === null) {
                this.settings.$tip_content.each(function (index) {
                    var $this = $(this);
                    this.settings = $.extend({}, self.defaults, self.data_options($this))

                    // Make sure that settings parsed from data_options are integers where necessary
                    for (var i = int_settings_count - 1; i >= 0; i--) {
                        self.settings[integer_settings[i]] = parseInt(self.settings[integer_settings[i]], 10);
                    }
                    self.create({$li: $this, index: index});
                });

                // show first tip
                if (!this.settings.start_timer_on_click && this.settings.timer > 0) {
                    this.show('init');
                    this.startTimer();
                } else {
                    this.show('init');
                }

            }
        },

        resume: function () {
            this.set_li();
            this.show();
        },

        tip_template: function (opts) {
            var $blank, content;

            opts.tip_class = opts.tip_class || '';

            $blank = $(this.settings.template.tip).addClass(opts.tip_class);
            content = $.trim($(opts.li).html()) +
                this.button_text(opts.button_text) +
                this.settings.template.link +
                this.timer_instance(opts.index);

            $blank.append($(this.settings.template.wrapper));
            $blank.first().attr('data-index', opts.index);
            $('.joyride-content-wrapper', $blank).append(content);

            return $blank[0];
        },

        timer_instance: function (index) {
            var txt;

            if ((index === 0 && this.settings.start_timer_on_click && this.settings.timer > 0) || this.settings.timer === 0) {
                txt = '';
            } else {
                txt = $(this.settings.template.timer)[0].outerHTML;
            }
            return txt;
        },

        button_text: function (txt) {
            if (this.settings.next_button) {
                txt = $.trim(txt) || 'Next';
                txt = $(this.settings.template.button).append(txt)[0].outerHTML;
            } else {
                txt = '';
            }
            return txt;
        },

        create: function (opts) {
            var buttonText = opts.$li.attr('data-button') || opts.$li.attr('data-text'),
                tipClass = opts.$li.attr('class'),
                $tip_content = $(this.tip_template({
                    tip_class: tipClass,
                    index: opts.index,
                    button_text: buttonText,
                    li: opts.$li
                }));

            $(this.settings.tip_container).append($tip_content);
        },

        show: function (init) {
            var $timer = null;

            // are we paused?
            if (this.settings.$li === undefined
                || ($.inArray(this.settings.$li.index(), this.settings.pause_after) === -1)) {

                // don't go to the next li if the tour was paused
                if (this.settings.paused) {
                    this.settings.paused = false;
                } else {
                    this.set_li(init);
                }

                this.settings.attempts = 0;

                if (this.settings.$li.length && this.settings.$target.length > 0) {
                    if (init) { //run when we first start
                        this.settings.pre_ride_callback(this.settings.$li.index(), this.settings.$next_tip);
                        if (this.settings.modal) {
                            this.show_modal();
                        }
                    }

                    this.settings.pre_step_callback(this.settings.$li.index(), this.settings.$next_tip);

                    if (this.settings.modal && this.settings.expose) {
                        this.expose();
                    }

                    this.settings.tip_settings = $.extend({}, this.settings, this.data_options(this.settings.$li));

                    this.settings.timer = parseInt(this.settings.timer, 10);

                    this.settings.tip_settings.tip_location_pattern = this.settings.tip_location_patterns[this.settings.tip_settings.tip_location];

                    // scroll if not modal
                    if (!/body/i.test(this.settings.$target.selector)) {
                        this.scroll_to();
                    }

                    if (this.is_phone()) {
                        this.pos_phone(true);
                    } else {
                        this.pos_default(true);
                    }

                    $timer = this.settings.$next_tip.find('.joyride-timer-indicator');

                    if (/pop/i.test(this.settings.tip_animation)) {

                        $timer.width(0);

                        if (this.settings.timer > 0) {

                            this.settings.$next_tip.show();

                            this.delay(function () {
                                $timer.animate({
                                    width: $timer.parent().width()
                                }, this.settings.timer, 'linear');
                            }.bind(this), this.settings.tip_animation_fade_speed);

                        } else {
                            this.settings.$next_tip.show();

                        }


                    } else if (/fade/i.test(this.settings.tip_animation)) {

                        $timer.width(0);

                        if (this.settings.timer > 0) {

                            this.settings.$next_tip
                                .fadeIn(this.settings.tip_animation_fade_speed)
                                .show();

                            this.delay(function () {
                                $timer.animate({
                                    width: $timer.parent().width()
                                }, this.settings.timer, 'linear');
                            }.bind(this), this.settings.tip_animation_fadeSpeed);

                        } else {
                            this.settings.$next_tip.fadeIn(this.settings.tip_animation_fade_speed);
                        }
                    }

                    this.settings.$current_tip = this.settings.$next_tip;

                    // skip non-existant targets
                } else if (this.settings.$li && this.settings.$target.length < 1) {

                    this.show();

                } else {

                    this.end();

                }
            } else {

                this.settings.paused = true;

            }

        },

        is_phone: function () {
            return matchMedia(Foundation.media_queries.small).matches && !matchMedia(Foundation.media_queries.medium).matches;
        },

        hide: function () {
            if (this.settings.modal && this.settings.expose) {
                this.un_expose();
            }

            if (!this.settings.modal) {
                $('.joyride-modal-bg').hide();
            }

            // Prevent scroll bouncing...wait to remove from layout
            this.settings.$current_tip.css('visibility', 'hidden');
            setTimeout($.proxy(function () {
                this.hide();
                this.css('visibility', 'visible');
            }, this.settings.$current_tip), 0);
            this.settings.post_step_callback(this.settings.$li.index(),
                this.settings.$current_tip);
        },

        set_li: function (init) {
            if (init) {
                this.settings.$li = this.settings.$tip_content.eq(this.settings.start_offset);
                this.set_next_tip();
                this.settings.$current_tip = this.settings.$next_tip;
            } else {
                this.settings.$li = this.settings.$li.next();
                this.set_next_tip();
            }

            this.set_target();
        },

        set_next_tip: function () {
            this.settings.$next_tip = $(".joyride-tip-guide").eq(this.settings.$li.index());
            this.settings.$next_tip.data('closed', '');
        },

        set_target: function () {
            var cl = this.settings.$li.attr('data-class'),
                id = this.settings.$li.attr('data-id'),
                $sel = function () {
                    if (id) {
                        return $(document.getElementById(id));
                    } else if (cl) {
                        return $('.' + cl).first();
                    } else {
                        return $('body');
                    }
                };

            this.settings.$target = $sel();
        },

        scroll_to: function () {
            var window_half, tipOffset;

            window_half = $(window).height() / 2;
            tipOffset = Math.ceil(this.settings.$target.offset().top - window_half + this.settings.$next_tip.outerHeight());

            if (tipOffset > 0) {
                $('html, body').animate({
                    scrollTop: tipOffset
                }, this.settings.scroll_speed, 'swing');
            }
        },

        paused: function () {
            return ($.inArray((this.settings.$li.index() + 1), this.settings.pause_after) === -1);
        },

        restart: function () {
            this.hide();
            this.settings.$li = undefined;
            this.show('init');
        },

        pos_default: function (init, resizing) {
            var half_fold = Math.ceil($(window).height() / 2),
                tip_position = this.settings.$next_tip.offset(),
                $nub = this.settings.$next_tip.find('.joyride-nub'),
                nub_width = Math.ceil($nub.outerWidth() / 2),
                nub_height = Math.ceil($nub.outerHeight() / 2),
                toggle = init || false;

            // tip must not be "display: none" to calculate position
            if (toggle) {
                this.settings.$next_tip.css('visibility', 'hidden');
                this.settings.$next_tip.show();
            }

            if (typeof resizing === 'undefined') {
                resizing = false;
            }

            if (!/body/i.test(this.settings.$target.selector)) {

                if (this.bottom()) {
                    var leftOffset = this.settings.$target.offset().left;
                    if (Foundation.rtl) {
                        leftOffset = this.settings.$target.offset().width - this.settings.$next_tip.width() + leftOffset;
                    }
                    this.settings.$next_tip.css({
                        top: (this.settings.$target.offset().top + nub_height + this.settings.$target.outerHeight()),
                        left: leftOffset});

                    this.nub_position($nub, this.settings.tip_settings.nub_position, 'top');

                } else if (this.top()) {
                    var leftOffset = this.settings.$target.offset().left;
                    if (Foundation.rtl) {
                        leftOffset = this.settings.$target.offset().width - this.settings.$next_tip.width() + leftOffset;
                    }
                    this.settings.$next_tip.css({
                        top: (this.settings.$target.offset().top - this.settings.$next_tip.outerHeight() - nub_height),
                        left: leftOffset});

                    this.nub_position($nub, this.settings.tip_settings.nub_position, 'bottom');

                } else if (this.right()) {

                    this.settings.$next_tip.css({
                        top: this.settings.$target.offset().top,
                        left: (this.outerWidth(this.settings.$target) + this.settings.$target.offset().left + nub_width)});

                    this.nub_position($nub, this.settings.tip_settings.nub_position, 'left');

                } else if (this.left()) {

                    this.settings.$next_tip.css({
                        top: this.settings.$target.offset().top,
                        left: (this.settings.$target.offset().left - this.outerWidth(this.settings.$next_tip) - nub_width)});

                    this.nub_position($nub, this.settings.tip_settings.nub_position, 'right');

                }

                if (!this.visible(this.corners(this.settings.$next_tip)) && this.settings.attempts < this.settings.tip_settings.tip_location_pattern.length) {

                    $nub.removeClass('bottom')
                        .removeClass('top')
                        .removeClass('right')
                        .removeClass('left');

                    this.settings.tip_settings.tip_location = this.settings.tip_settings.tip_location_pattern[this.settings.attempts];

                    this.settings.attempts++;

                    this.pos_default();

                }

            } else if (this.settings.$li.length) {

                this.pos_modal($nub);

            }

            if (toggle) {
                this.settings.$next_tip.hide();
                this.settings.$next_tip.css('visibility', 'visible');
            }

        },

        pos_phone: function (init) {
            var tip_height = this.settings.$next_tip.outerHeight(),
                tip_offset = this.settings.$next_tip.offset(),
                target_height = this.settings.$target.outerHeight(),
                $nub = $('.joyride-nub', this.settings.$next_tip),
                nub_height = Math.ceil($nub.outerHeight() / 2),
                toggle = init || false;

            $nub.removeClass('bottom')
                .removeClass('top')
                .removeClass('right')
                .removeClass('left');

            if (toggle) {
                this.settings.$next_tip.css('visibility', 'hidden');
                this.settings.$next_tip.show();
            }

            if (!/body/i.test(this.settings.$target.selector)) {

                if (this.top()) {

                    this.settings.$next_tip.offset({top: this.settings.$target.offset().top - tip_height - nub_height});
                    $nub.addClass('bottom');

                } else {

                    this.settings.$next_tip.offset({top: this.settings.$target.offset().top + target_height + nub_height});
                    $nub.addClass('top');

                }

            } else if (this.settings.$li.length) {
                this.pos_modal($nub);
            }

            if (toggle) {
                this.settings.$next_tip.hide();
                this.settings.$next_tip.css('visibility', 'visible');
            }
        },

        pos_modal: function ($nub) {
            this.center();
            $nub.hide();

            this.show_modal();
        },

        show_modal: function () {
            if (!this.settings.$next_tip.data('closed')) {
                var joyridemodalbg = $('.joyride-modal-bg');
                if (joyridemodalbg.length < 1) {
                    $('body').append(this.settings.template.modal).show();
                }

                if (/pop/i.test(this.settings.tip_animation)) {
                    joyridemodalbg.show();
                } else {
                    joyridemodalbg.fadeIn(this.settings.tip_animation_fade_speed);
                }
            }
        },

        expose: function () {
            var expose,
                exposeCover,
                el,
                origCSS,
                origClasses,
                randId = 'expose-' + Math.floor(Math.random() * 10000);

            if (arguments.length > 0 && arguments[0] instanceof $) {
                el = arguments[0];
            } else if (this.settings.$target && !/body/i.test(this.settings.$target.selector)) {
                el = this.settings.$target;
            } else {
                return false;
            }

            if (el.length < 1) {
                if (window.console) {
                    console.error('element not valid', el);
                }
                return false;
            }

            expose = $(this.settings.template.expose);
            this.settings.$body.append(expose);
            expose.css({
                top: el.offset().top,
                left: el.offset().left,
                width: el.outerWidth(true),
                height: el.outerHeight(true)
            });

            exposeCover = $(this.settings.template.expose_cover);

            origCSS = {
                zIndex: el.css('z-index'),
                position: el.css('position')
            };

            origClasses = el.attr('class') == null ? '' : el.attr('class');

            el.css('z-index', parseInt(expose.css('z-index')) + 1);

            if (origCSS.position == 'static') {
                el.css('position', 'relative');
            }

            el.data('expose-css', origCSS);
            el.data('orig-class', origClasses);
            el.attr('class', origClasses + ' ' + this.settings.expose_add_class);

            exposeCover.css({
                top: el.offset().top,
                left: el.offset().left,
                width: el.outerWidth(true),
                height: el.outerHeight(true)
            });

            if (this.settings.modal) this.show_modal();

            this.settings.$body.append(exposeCover);
            expose.addClass(randId);
            exposeCover.addClass(randId);
            el.data('expose', randId);
            this.settings.post_expose_callback(this.settings.$li.index(), this.settings.$next_tip, el);
            this.add_exposed(el);
        },

        un_expose: function () {
            var exposeId,
                el,
                expose ,
                origCSS,
                origClasses,
                clearAll = false;

            if (arguments.length > 0 && arguments[0] instanceof $) {
                el = arguments[0];
            } else if (this.settings.$target && !/body/i.test(this.settings.$target.selector)) {
                el = this.settings.$target;
            } else {
                return false;
            }

            if (el.length < 1) {
                if (window.console) {
                    console.error('element not valid', el);
                }
                return false;
            }

            exposeId = el.data('expose');
            expose = $('.' + exposeId);

            if (arguments.length > 1) {
                clearAll = arguments[1];
            }

            if (clearAll === true) {
                $('.joyride-expose-wrapper,.joyride-expose-cover').remove();
            } else {
                expose.remove();
            }

            origCSS = el.data('expose-css');

            if (origCSS.zIndex == 'auto') {
                el.css('z-index', '');
            } else {
                el.css('z-index', origCSS.zIndex);
            }

            if (origCSS.position != el.css('position')) {
                if (origCSS.position == 'static') {// this is default, no need to set it.
                    el.css('position', '');
                } else {
                    el.css('position', origCSS.position);
                }
            }

            origClasses = el.data('orig-class');
            el.attr('class', origClasses);
            el.removeData('orig-classes');

            el.removeData('expose');
            el.removeData('expose-z-index');
            this.remove_exposed(el);
        },

        add_exposed: function (el) {
            this.settings.exposed = this.settings.exposed || [];
            if (el instanceof $ || typeof el === 'object') {
                this.settings.exposed.push(el[0]);
            } else if (typeof el == 'string') {
                this.settings.exposed.push(el);
            }
        },

        remove_exposed: function (el) {
            var search, count;
            if (el instanceof $) {
                search = el[0]
            } else if (typeof el == 'string') {
                search = el;
            }

            this.settings.exposed = this.settings.exposed || [];
            count = this.settings.exposed.length;

            for (var i = 0; i < count; i++) {
                if (this.settings.exposed[i] == search) {
                    this.settings.exposed.splice(i, 1);
                    return;
                }
            }
        },

        center: function () {
            var $w = $(window);

            this.settings.$next_tip.css({
                top: ((($w.height() - this.settings.$next_tip.outerHeight()) / 2) + $w.scrollTop()),
                left: ((($w.width() - this.settings.$next_tip.outerWidth()) / 2) + $w.scrollLeft())
            });

            return true;
        },

        bottom: function () {
            return /bottom/i.test(this.settings.tip_settings.tip_location);
        },

        top: function () {
            return /top/i.test(this.settings.tip_settings.tip_location);
        },

        right: function () {
            return /right/i.test(this.settings.tip_settings.tip_location);
        },

        left: function () {
            return /left/i.test(this.settings.tip_settings.tip_location);
        },

        corners: function (el) {
            var w = $(window),
                window_half = w.height() / 2,
            //using this to calculate since scroll may not have finished yet.
                tipOffset = Math.ceil(this.settings.$target.offset().top - window_half + this.settings.$next_tip.outerHeight()),
                right = w.width() + w.scrollLeft(),
                offsetBottom = w.height() + tipOffset,
                bottom = w.height() + w.scrollTop(),
                top = w.scrollTop();

            if (tipOffset < top) {
                if (tipOffset < 0) {
                    top = 0;
                } else {
                    top = tipOffset;
                }
            }

            if (offsetBottom > bottom) {
                bottom = offsetBottom;
            }

            return [
                el.offset().top < top,
                right < el.offset().left + el.outerWidth(),
                bottom < el.offset().top + el.outerHeight(),
                w.scrollLeft() > el.offset().left
            ];
        },

        visible: function (hidden_corners) {
            var i = hidden_corners.length;

            while (i--) {
                if (hidden_corners[i]) return false;
            }

            return true;
        },

        nub_position: function (nub, pos, def) {
            if (pos === 'auto') {
                nub.addClass(def);
            } else {
                nub.addClass(pos);
            }
        },

        startTimer: function () {
            if (this.settings.$li.length) {
                this.settings.automate = setTimeout(function () {
                    this.hide();
                    this.show();
                    this.startTimer();
                }.bind(this), this.settings.timer);
            } else {
                clearTimeout(this.settings.automate);
            }
        },

        end: function () {
            if (this.settings.cookie_monster) {
                $.cookie(this.settings.cookie_name, 'ridden', { expires: this.settings.cookie_expires, domain: this.settings.cookie_domain });
            }

            if (this.settings.timer > 0) {
                clearTimeout(this.settings.automate);
            }

            if (this.settings.modal && this.settings.expose) {
                this.un_expose();
            }

            this.settings.$next_tip.data('closed', true);

            $('.joyride-modal-bg').hide();
            this.settings.$current_tip.hide();
            this.settings.post_step_callback(this.settings.$li.index(), this.settings.$current_tip);
            this.settings.post_ride_callback(this.settings.$li.index(), this.settings.$current_tip);
            $('.joyride-tip-guide').remove();
        },

        off: function () {
            $(this.scope).off('.joyride');
            $(window).off('.joyride');
            $('.joyride-close-tip, .joyride-next-tip, .joyride-modal-bg').off('.joyride');
            $('.joyride-tip-guide, .joyride-modal-bg').remove();
            clearTimeout(this.settings.automate);
            this.settings = {};
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.magellan = {
        name: 'magellan',

        version: '5.0.0',

        settings: {
            active_class: 'active',
            threshold: 0
        },

        init: function (scope, method, options) {
            this.fixed_magellan = $("[data-magellan-expedition]");
            this.set_threshold();
            this.last_destination = $('[data-magellan-destination]').last();
            this.events();
        },

        events: function () {
            var self = this;

            $(this.scope)
                .off('.magellan')
                .on('arrival.fndtn.magellan', '[data-magellan-arrival]', function (e) {
                    var $destination = $(this),
                        $expedition = $destination.closest('[data-magellan-expedition]'),
                        active_class = $expedition.attr('data-magellan-active-class')
                            || self.settings.active_class;

                    $destination
                        .closest('[data-magellan-expedition]')
                        .find('[data-magellan-arrival]')
                        .not($destination)
                        .removeClass(active_class);
                    $destination.addClass(active_class);
                });

            this.fixed_magellan
                .off('.magellan')
                .on('update-position.fndtn.magellan', function () {
                    var $el = $(this);
                })
                .trigger('update-position');

            $(window)
                .off('.magellan')
                .on('resize.fndtn.magellan', function () {
                    this.fixed_magellan.trigger('update-position');
                }.bind(this))
                .on('scroll.fndtn.magellan', function () {
                    var windowScrollTop = $(window).scrollTop();
                    self.fixed_magellan.each(function () {
                        var $expedition = $(this);
                        if (typeof $expedition.data('magellan-top-offset') === 'undefined') {
                            $expedition.data('magellan-top-offset', $expedition.offset().top);
                        }
                        if (typeof $expedition.data('magellan-fixed-position') === 'undefined') {
                            $expedition.data('magellan-fixed-position', false);
                        }
                        var fixed_position = (windowScrollTop + self.settings.threshold) > $expedition.data("magellan-top-offset");
                        var attr = $expedition.attr('data-magellan-top-offset');

                        if ($expedition.data("magellan-fixed-position") != fixed_position) {
                            $expedition.data("magellan-fixed-position", fixed_position);
                            if (fixed_position) {
                                $expedition.addClass('fixed');
                                $expedition.css({position: "fixed", top: 0});
                            } else {
                                $expedition.removeClass('fixed');
                                $expedition.css({position: "", top: ""});
                            }
                            if (fixed_position && typeof attr != 'undefined' && attr != false) {
                                $expedition.css({position: "fixed", top: attr + "px"});
                            }
                        }
                    });
                });


            if (this.last_destination.length > 0) {
                $(window).on('scroll.fndtn.magellan', function (e) {
                    var windowScrollTop = $(window).scrollTop(),
                        scrolltopPlusHeight = windowScrollTop + $(window).height(),
                        lastDestinationTop = Math.ceil(self.last_destination.offset().top);

                    $('[data-magellan-destination]').each(function () {
                        var $destination = $(this),
                            destination_name = $destination.attr('data-magellan-destination'),
                            topOffset = $destination.offset().top - $destination.outerHeight(true) - windowScrollTop;
                        if (topOffset <= self.settings.threshold) {
                            $("[data-magellan-arrival='" + destination_name + "']").trigger('arrival');
                        }
                        // In large screens we may hit the bottom of the page and dont reach the top of the last magellan-destination, so lets force it
                        if (scrolltopPlusHeight >= $(self.scope).height() && lastDestinationTop > windowScrollTop && lastDestinationTop < scrolltopPlusHeight) {
                            $('[data-magellan-arrival]').last().trigger('arrival');
                        }
                    });
                });
            }
        },

        set_threshold: function () {
            if (typeof this.settings.threshold !== 'number') {
                this.settings.threshold = (this.fixed_magellan.length > 0) ?
                    this.fixed_magellan.outerHeight(true) : 0;
            }
        },

        off: function () {
            $(this.scope).off('.fndtn.magellan');
            $(window).off('.fndtn.magellan');
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.offcanvas = {
        name: 'offcanvas',

        version: '5.0.0',

        settings: {},

        init: function (scope, method, options) {
            this.events();
        },

        events: function () {
            $(this.scope).off('.offcanvas')
                .on('click.fndtn.offcanvas', '.left-off-canvas-toggle', function (e) {
                    e.preventDefault();
                    $(this).closest('.off-canvas-wrap').toggleClass('move-right');
                })
                .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
                    e.preventDefault();
                    $(".off-canvas-wrap").removeClass("move-right");
                })
                .on('click.fndtn.offcanvas', '.right-off-canvas-toggle', function (e) {
                    e.preventDefault();
                    $(this).closest(".off-canvas-wrap").toggleClass("move-left");
                })
                .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
                    e.preventDefault();
                    $(".off-canvas-wrap").removeClass("move-left");
                });
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));


$('.umi-divider').on('mousedown', function (event) {
    var $that = $(this);
    $('html').addClass('s-unselectable');

    $('html').on('mousemove', function (event) {
        event.preventDefault();
        if (event.pageX > 0 && event.pageX < $(window).width() / 2) {
            $that.offset({"left": event.pageX - 5});
            $('.left-off-canvas-menu').width(event.pageX);
            $('.off-canvas-wrap.move-right').width('calc(100% - ' + event.pageX + 'px)');
            $('.inner-wrap').css({"-webkit-transform": 'translate3d(' + event.pageX + 'px, 0, 0)'});
        }
    });

    $('html').on('mouseup', function (event) {
        if (event.pageX < 100) {
            $that.offset({"left": 0});
            $('.left-off-canvas-menu').width(0);
            $('.off-canvas-wrap.move-right').width('calc(100% - ' + 0 + 'px)');
            $('.inner-wrap').css({"-webkit-transform": 'translate3d(' + 0 + 'px, 0, 0)'});
        }
        $('html').off('mousemove');
        $('html').removeClass('s-unselectable');
    });
});;
(function ($, window, document, undefined) {
    'use strict';

    var noop = function () {
    };

    var Orbit = function (el, settings) {
        // Don't reinitialize plugin
        if (el.hasClass(settings.slides_container_class)) {
            return this;
        }

        var self = this,
            container,
            slides_container = el,
            number_container,
            bullets_container,
            timer_container,
            idx = 0,
            animate,
            timer,
            locked = false,
            adjust_height_after = false;

        slides_container.children().first().addClass(settings.active_slide_class);

        self.update_slide_number = function (index) {
            if (settings.slide_number) {
                number_container.find('span:first').text(parseInt(index) + 1);
                number_container.find('span:last').text(slides_container.children().length);
            }
            if (settings.bullets) {
                bullets_container.children().removeClass(settings.bullets_active_class);
                $(bullets_container.children().get(index)).addClass(settings.bullets_active_class);
            }
        };

        self.update_active_link = function (index) {
            var link = $('a[data-orbit-link="' + slides_container.children().eq(index).attr('data-orbit-slide') + '"]');
            link.parents('ul').find('[data-orbit-link]').removeClass(settings.bullets_active_class);
            link.addClass(settings.bullets_active_class);
        };

        self.build_markup = function () {
            slides_container.wrap('<div class="' + settings.container_class + '"></div>');
            container = slides_container.parent();
            slides_container.addClass(settings.slides_container_class);

            if (settings.navigation_arrows) {
                container.append($('<a href="#"><span></span></a>').addClass(settings.prev_class));
                container.append($('<a href="#"><span></span></a>').addClass(settings.next_class));
            }

            if (settings.timer) {
                timer_container = $('<div>').addClass(settings.timer_container_class);
                timer_container.append('<span>');
                timer_container.append($('<div>').addClass(settings.timer_progress_class));
                timer_container.addClass(settings.timer_paused_class);
                container.append(timer_container);
            }

            if (settings.slide_number) {
                number_container = $('<div>').addClass(settings.slide_number_class);
                number_container.append('<span></span> ' + settings.slide_number_text + ' <span></span>');
                container.append(number_container);
            }

            if (settings.bullets) {
                bullets_container = $('<ol>').addClass(settings.bullets_container_class);
                container.append(bullets_container);
                bullets_container.wrap('<div class="orbit-bullets-container"></div>');
                slides_container.children().each(function (idx, el) {
                    var bullet = $('<li>').attr('data-orbit-slide', idx);
                    bullets_container.append(bullet);
                });
            }

            if (settings.stack_on_small) {
                container.addClass(settings.stack_on_small_class);
            }

            self.update_slide_number(0);
            self.update_active_link(0);
        };

        self._goto = function (next_idx, start_timer) {
            // if (locked) {return false;}
            if (next_idx === idx) {
                return false;
            }
            if (typeof timer === 'object') {
                timer.restart();
            }
            var slides = slides_container.children();

            var dir = 'next';
            locked = true;
            if (next_idx < idx) {
                dir = 'prev';
            }
            if (next_idx >= slides.length) {
                next_idx = 0;
            }
            else if (next_idx < 0) {
                next_idx = slides.length - 1;
            }

            var current = $(slides.get(idx));
            var next = $(slides.get(next_idx));

            current.css('zIndex', 2);
            current.removeClass(settings.active_slide_class);
            next.css('zIndex', 4).addClass(settings.active_slide_class);

            slides_container.trigger('before-slide-change.fndtn.orbit');
            settings.before_slide_change();
            self.update_active_link(next_idx);

            var callback = function () {
                var unlock = function () {
                    idx = next_idx;
                    locked = false;
                    if (start_timer === true) {
                        timer = self.create_timer();
                        timer.start();
                    }
                    self.update_slide_number(idx);
                    slides_container.trigger('after-slide-change.fndtn.orbit', [
                        {slide_number: idx, total_slides: slides.length}
                    ]);
                    settings.after_slide_change(idx, slides.length);
                };
                if (slides_container.height() != next.height() && settings.variable_height) {
                    slides_container.animate({'height': next.height()}, 250, 'linear', unlock);
                } else {
                    unlock();
                }
            };

            if (slides.length === 1) {
                callback();
                return false;
            }

            var start_animation = function () {
                if (dir === 'next') {
                    animate.next(current, next, callback);
                }
                if (dir === 'prev') {
                    animate.prev(current, next, callback);
                }
            };

            if (next.height() > slides_container.height() && settings.variable_height) {
                slides_container.animate({'height': next.height()}, 250, 'linear', start_animation);
            } else {
                start_animation();
            }
        };

        self.next = function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            self._goto(idx + 1);
        };

        self.prev = function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            self._goto(idx - 1);
        };

        self.link_custom = function (e) {
            e.preventDefault();
            var link = $(this).attr('data-orbit-link');
            if ((typeof link === 'string') && (link = $.trim(link)) != "") {
                var slide = container.find('[data-orbit-slide=' + link + ']');
                if (slide.index() != -1) {
                    self._goto(slide.index());
                }
            }
        };

        self.link_bullet = function (e) {
            var index = $(this).attr('data-orbit-slide');
            if ((typeof index === 'string') && (index = $.trim(index)) != "") {
                self._goto(parseInt(index));
            }
        }

        self.timer_callback = function () {
            self._goto(idx + 1, true);
        }

        self.compute_dimensions = function () {
            var current = $(slides_container.children().get(idx));
            var h = current.height();
            if (!settings.variable_height) {
                slides_container.children().each(function () {
                    if ($(this).height() > h) {
                        h = $(this).height();
                    }
                });
            }
            slides_container.height(h);
        };

        self.create_timer = function () {
            var t = new Timer(
                container.find('.' + settings.timer_container_class),
                settings,
                self.timer_callback
            );
            return t;
        };

        self.stop_timer = function () {
            if (typeof timer === 'object') timer.stop();
        };

        self.toggle_timer = function () {
            var t = container.find('.' + settings.timer_container_class);
            if (t.hasClass(settings.timer_paused_class)) {
                if (typeof timer === 'undefined') {
                    timer = self.create_timer();
                }
                timer.start();
            }
            else {
                if (typeof timer === 'object') {
                    timer.stop();
                }
            }
        };

        self.init = function () {
            self.build_markup();
            if (settings.timer) {
                timer = self.create_timer();
                timer.start();
            }
            animate = new FadeAnimation(settings, slides_container);
            if (settings.animation === 'slide')
                animate = new SlideAnimation(settings, slides_container);
            container.on('click', '.' + settings.next_class, self.next);
            container.on('click', '.' + settings.prev_class, self.prev);
            container.on('click', '[data-orbit-slide]', self.link_bullet);
            container.on('click', self.toggle_timer);
            if (settings.swipe) {
                container.on('touchstart.fndtn.orbit', function (e) {
                    if (!e.touches) {
                        e = e.originalEvent;
                    }
                    var data = {
                        start_page_x: e.touches[0].pageX,
                        start_page_y: e.touches[0].pageY,
                        start_time: (new Date()).getTime(),
                        delta_x: 0,
                        is_scrolling: undefined
                    };
                    container.data('swipe-transition', data);
                    e.stopPropagation();
                })
                    .on('touchmove.fndtn.orbit', function (e) {
                        if (!e.touches) {
                            e = e.originalEvent;
                        }
                        // Ignore pinch/zoom events
                        if (e.touches.length > 1 || e.scale && e.scale !== 1) return;

                        var data = container.data('swipe-transition');
                        if (typeof data === 'undefined') {
                            data = {};
                        }

                        data.delta_x = e.touches[0].pageX - data.start_page_x;

                        if (typeof data.is_scrolling === 'undefined') {
                            data.is_scrolling = !!( data.is_scrolling || Math.abs(data.delta_x) < Math.abs(e.touches[0].pageY - data.start_page_y) );
                        }

                        if (!data.is_scrolling && !data.active) {
                            e.preventDefault();
                            var direction = (data.delta_x < 0) ? (idx + 1) : (idx - 1);
                            data.active = true;
                            self._goto(direction);
                        }
                    })
                    .on('touchend.fndtn.orbit', function (e) {
                        container.data('swipe-transition', {});
                        e.stopPropagation();
                    })
            }
            container.on('mouseenter.fndtn.orbit', function (e) {
                if (settings.timer && settings.pause_on_hover) {
                    self.stop_timer();
                }
            })
                .on('mouseleave.fndtn.orbit', function (e) {
                    if (settings.timer && settings.resume_on_mouseout) {
                        timer.start();
                    }
                });

            $(document).on('click', '[data-orbit-link]', self.link_custom);
            $(window).on('resize', self.compute_dimensions);
            $(window).on('load', self.compute_dimensions);
            $(window).on('load', function () {
                container.prev('.preloader').css('display', 'none');
            });
            slides_container.trigger('ready.fndtn.orbit');
        };

        self.init();
    };

    var Timer = function (el, settings, callback) {
        var self = this,
            duration = settings.timer_speed,
            progress = el.find('.' + settings.timer_progress_class),
            start,
            timeout,
            left = -1;

        this.update_progress = function (w) {
            var new_progress = progress.clone();
            new_progress.attr('style', '');
            new_progress.css('width', w + '%');
            progress.replaceWith(new_progress);
            progress = new_progress;
        };

        this.restart = function () {
            clearTimeout(timeout);
            el.addClass(settings.timer_paused_class);
            left = -1;
            self.update_progress(0);
        };

        this.start = function () {
            if (!el.hasClass(settings.timer_paused_class)) {
                return true;
            }
            left = (left === -1) ? duration : left;
            el.removeClass(settings.timer_paused_class);
            start = new Date().getTime();
            progress.animate({'width': '100%'}, left, 'linear');
            timeout = setTimeout(function () {
                self.restart();
                callback();
            }, left);
            el.trigger('timer-started.fndtn.orbit')
        };

        this.stop = function () {
            if (el.hasClass(settings.timer_paused_class)) {
                return true;
            }
            clearTimeout(timeout);
            el.addClass(settings.timer_paused_class);
            var end = new Date().getTime();
            left = left - (end - start);
            var w = 100 - ((left / duration) * 100);
            self.update_progress(w);
            el.trigger('timer-stopped.fndtn.orbit');
        };
    };

    var SlideAnimation = function (settings, container) {
        var duration = settings.animation_speed;
        var is_rtl = ($('html[dir=rtl]').length === 1);
        var margin = is_rtl ? 'marginRight' : 'marginLeft';
        var animMargin = {};
        animMargin[margin] = '0%';

        this.next = function (current, next, callback) {
            current.animate({marginLeft: '-100%'}, duration);
            next.animate(animMargin, duration, function () {
                current.css(margin, '100%');
                callback();
            });
        };

        this.prev = function (current, prev, callback) {
            current.animate({marginLeft: '100%'}, duration);
            prev.css(margin, '-100%');
            prev.animate(animMargin, duration, function () {
                current.css(margin, '100%');
                callback();
            });
        };
    };

    var FadeAnimation = function (settings, container) {
        var duration = settings.animation_speed;
        var is_rtl = ($('html[dir=rtl]').length === 1);
        var margin = is_rtl ? 'marginRight' : 'marginLeft';

        this.next = function (current, next, callback) {
            next.css({'margin': '0%', 'opacity': '0.01'});
            next.animate({'opacity': '1'}, duration, 'linear', function () {
                current.css('margin', '100%');
                callback();
            });
        };

        this.prev = function (current, prev, callback) {
            prev.css({'margin': '0%', 'opacity': '0.01'});
            prev.animate({'opacity': '1'}, duration, 'linear', function () {
                current.css('margin', '100%');
                callback();
            });
        };
    };


    Foundation.libs = Foundation.libs || {};

    Foundation.libs.orbit = {
        name: 'orbit',

        version: '5.0.0',

        settings: {
            animation: 'slide',
            timer_speed: 10000,
            pause_on_hover: true,
            resume_on_mouseout: false,
            animation_speed: 500,
            stack_on_small: false,
            navigation_arrows: true,
            slide_number: true,
            slide_number_text: 'of',
            container_class: 'orbit-container',
            stack_on_small_class: 'orbit-stack-on-small',
            next_class: 'orbit-next',
            prev_class: 'orbit-prev',
            timer_container_class: 'orbit-timer',
            timer_paused_class: 'paused',
            timer_progress_class: 'orbit-progress',
            slides_container_class: 'orbit-slides-container',
            bullets_container_class: 'orbit-bullets',
            bullets_active_class: 'active',
            slide_number_class: 'orbit-slide-number',
            caption_class: 'orbit-caption',
            active_slide_class: 'active',
            orbit_transition_class: 'orbit-transitioning',
            bullets: true,
            timer: true,
            variable_height: false,
            swipe: true,
            before_slide_change: noop,
            after_slide_change: noop
        },

        init: function (scope, method, options) {
            var self = this;

            if (typeof method === 'object') {
                $.extend(true, self.settings, method);
            }

            if ($(scope).is('[data-orbit]')) {
                var $el = $(scope);
                var opts = self.data_options($el);
                new Orbit($el, $.extend({}, self.settings, opts));
            }

            $('[data-orbit]', scope).each(function (idx, el) {
                var $el = $(el);
                var opts = self.data_options($el);
                new Orbit($el, $.extend({}, self.settings, opts));
            });
        }
    };


}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.reveal = {
        name: 'reveal',

        version: '5.0.0',

        locked: false,

        settings: {
            animation: 'fadeAndPop',
            animation_speed: 250,
            close_on_background_click: true,
            close_on_esc: true,
            dismiss_modal_class: 'close-reveal-modal',
            bg_class: 'reveal-modal-bg',
            open: function () {
            },
            opened: function () {
            },
            close: function () {
            },
            closed: function () {
            },
            bg: $('.reveal-modal-bg'),
            css: {
                open: {
                    'opacity': 0,
                    'visibility': 'visible',
                    'display': 'block'
                },
                close: {
                    'opacity': 1,
                    'visibility': 'hidden',
                    'display': 'none'
                }
            }
        },

        init: function (scope, method, options) {
            Foundation.inherit(this, 'delay');

            this.bindings(method, options);
        },

        events: function (scope) {
            var self = this;

            $('[data-reveal-id]', this.scope)
                .off('.reveal')
                .on('click.fndtn.reveal', function (e) {
                    e.preventDefault();

                    if (!self.locked) {
                        var element = $(this),
                            ajax = element.data('reveal-ajax');

                        self.locked = true;

                        if (typeof ajax === 'undefined') {
                            self.open.call(self, element);
                        } else {
                            var url = ajax === true ? element.attr('href') : ajax;

                            self.open.call(self, element, {url: url});
                        }
                    }
                });

            $(this.scope)
                .off('.reveal')
                .on('click.fndtn.reveal', this.close_targets(), function (e) {

                    e.preventDefault();

                    if (!self.locked) {
                        var settings = $('[data-reveal].open').data('reveal-init'),
                            bg_clicked = $(e.target)[0] === $('.' + settings.bg_class)[0];

                        if (bg_clicked && !settings.close_on_background_click) {
                            return;
                        }

                        self.locked = true;
                        self.close.call(self, bg_clicked ? $('[data-reveal].open') : $(this).closest('[data-reveal]'));
                    }
                });

            if ($('[data-reveal]', this.scope).length > 0) {
                $(this.scope)
                    // .off('.reveal')
                    .on('open.fndtn.reveal', this.settings.open)
                    .on('opened.fndtn.reveal', this.settings.opened)
                    .on('opened.fndtn.reveal', this.open_video)
                    .on('close.fndtn.reveal', this.settings.close)
                    .on('closed.fndtn.reveal', this.settings.closed)
                    .on('closed.fndtn.reveal', this.close_video);
            } else {
                $(this.scope)
                    // .off('.reveal')
                    .on('open.fndtn.reveal', '[data-reveal]', this.settings.open)
                    .on('opened.fndtn.reveal', '[data-reveal]', this.settings.opened)
                    .on('opened.fndtn.reveal', '[data-reveal]', this.open_video)
                    .on('close.fndtn.reveal', '[data-reveal]', this.settings.close)
                    .on('closed.fndtn.reveal', '[data-reveal]', this.settings.closed)
                    .on('closed.fndtn.reveal', '[data-reveal]', this.close_video);
            }

            $('body').on('keyup.fndtn.reveal', function (event) {
                var open_modal = $('[data-reveal].open'),
                    settings = open_modal.data('reveal-init');
                if (event.which === 27 && settings.close_on_esc) { // 27 is the keycode for the Escape key
                    open_modal.foundation('reveal', 'close');
                }
            });

            return true;
        },

        open: function (target, ajax_settings) {
            if (target) {
                if (typeof target.selector !== 'undefined') {
                    var modal = $('#' + target.data('reveal-id'));
                } else {
                    var modal = $(this.scope);

                    ajax_settings = target;
                }
            } else {
                var modal = $(this.scope);
            }

            if (!modal.hasClass('open')) {
                var open_modal = $('[data-reveal].open');

                if (typeof modal.data('css-top') === 'undefined') {
                    modal.data('css-top', parseInt(modal.css('top'), 10))
                        .data('offset', this.cache_offset(modal));
                }

                modal.trigger('open');

                if (open_modal.length < 1) {
                    this.toggle_bg();
                }

                if (typeof ajax_settings === 'undefined' || !ajax_settings.url) {
                    this.hide(open_modal, this.settings.css.close);
                    this.show(modal, this.settings.css.open);
                } else {
                    var self = this,
                        old_success = typeof ajax_settings.success !== 'undefined' ? ajax_settings.success : null;

                    $.extend(ajax_settings, {
                        success: function (data, textStatus, jqXHR) {
                            if ($.isFunction(old_success)) {
                                old_success(data, textStatus, jqXHR);
                            }

                            modal.html(data);
                            $(modal).foundation('section', 'reflow');

                            self.hide(open_modal, self.settings.css.close);
                            self.show(modal, self.settings.css.open);
                        }
                    });

                    $.ajax(ajax_settings);
                }
            }
        },

        close: function (modal) {

            var modal = modal && modal.length ? modal : $(this.scope),
                open_modals = $('[data-reveal].open');

            if (open_modals.length > 0) {
                this.locked = true;
                modal.trigger('close');
                this.toggle_bg();
                this.hide(open_modals, this.settings.css.close);
            }
        },

        close_targets: function () {
            var base = '.' + this.settings.dismiss_modal_class;

            if (this.settings.close_on_background_click) {
                return base + ', .' + this.settings.bg_class;
            }

            return base;
        },

        toggle_bg: function () {
            if ($('.' + this.settings.bg_class).length === 0) {
                this.settings.bg = $('<div />', {'class': this.settings.bg_class})
                    .appendTo('body');
            }

            if (this.settings.bg.filter(':visible').length > 0) {
                this.hide(this.settings.bg);
            } else {
                this.show(this.settings.bg);
            }
        },

        show: function (el, css) {
            // is modal
            if (css) {
                if (el.parent('body').length === 0) {
                    var placeholder = el.wrap('<div style="display: none;" />').parent();
                    el.on('closed.fndtn.reveal.wrapped', function () {
                        el.detach().appendTo(placeholder);
                        el.unwrap().unbind('closed.fndtn.reveal.wrapped');
                    });

                    el.detach().appendTo('body');
                }

                if (/pop/i.test(this.settings.animation)) {
                    css.top = $(window).scrollTop() - el.data('offset') + 'px';
                    var end_css = {
                        top: $(window).scrollTop() + el.data('css-top') + 'px',
                        opacity: 1
                    };

                    return this.delay(function () {
                        return el
                            .css(css)
                            .animate(end_css, this.settings.animation_speed, 'linear', function () {
                                this.locked = false;
                                el.trigger('opened');
                            }.bind(this))
                            .addClass('open');
                    }.bind(this), this.settings.animation_speed / 2);
                }

                if (/fade/i.test(this.settings.animation)) {
                    var end_css = {opacity: 1};

                    return this.delay(function () {
                        return el
                            .css(css)
                            .animate(end_css, this.settings.animation_speed, 'linear', function () {
                                this.locked = false;
                                el.trigger('opened');
                            }.bind(this))
                            .addClass('open');
                    }.bind(this), this.settings.animation_speed / 2);
                }

                return el.css(css).show().css({opacity: 1}).addClass('open').trigger('opened');
            }

            // should we animate the background?
            if (/fade/i.test(this.settings.animation)) {
                return el.fadeIn(this.settings.animation_speed / 2);
            }

            return el.show();
        },

        hide: function (el, css) {
            // is modal
            if (css) {
                if (/pop/i.test(this.settings.animation)) {
                    var end_css = {
                        top: -$(window).scrollTop() - el.data('offset') + 'px',
                        opacity: 0
                    };

                    return this.delay(function () {
                        return el
                            .animate(end_css, this.settings.animation_speed, 'linear', function () {
                                this.locked = false;
                                el.css(css).trigger('closed');
                            }.bind(this))
                            .removeClass('open');
                    }.bind(this), this.settings.animation_speed / 2);
                }

                if (/fade/i.test(this.settings.animation)) {
                    var end_css = {opacity: 0};

                    return this.delay(function () {
                        return el
                            .animate(end_css, this.settings.animation_speed, 'linear', function () {
                                this.locked = false;
                                el.css(css).trigger('closed');
                            }.bind(this))
                            .removeClass('open');
                    }.bind(this), this.settings.animation_speed / 2);
                }

                return el.hide().css(css).removeClass('open').trigger('closed');
            }

            // should we animate the background?
            if (/fade/i.test(this.settings.animation)) {
                return el.fadeOut(this.settings.animation_speed / 2);
            }

            return el.hide();
        },

        close_video: function (e) {
            var video = $(this).find('.flex-video'),
                iframe = video.find('iframe');

            if (iframe.length > 0) {
                iframe.attr('data-src', iframe[0].src);
                iframe.attr('src', 'about:blank');
                video.hide();
            }
        },

        open_video: function (e) {
            var video = $(this).find('.flex-video'),
                iframe = video.find('iframe');

            if (iframe.length > 0) {
                var data_src = iframe.attr('data-src');
                if (typeof data_src === 'string') {
                    iframe[0].src = iframe.attr('data-src');
                } else {
                    var src = iframe[0].src;
                    iframe[0].src = undefined;
                    iframe[0].src = src;
                }
                video.show();
            }
        },

        cache_offset: function (modal) {
            var offset = modal.show().height() + parseInt(modal.css('top'), 10);

            modal.hide();

            return offset;
        },

        off: function () {
            $(this.scope).off('.fndtn.reveal');
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
/*jslint unparam: true, browser: true, indent: 2 */
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.tab = {
        name: 'tab',

        version: '5.0.1',

        settings: {
            active_class: 'active'
        },

        init: function (scope, method, options) {
            this.bindings(method, options);
        },

        events: function () {
            $(this.scope).off('.tab').on('mouseenter.fndtn.tab', '[data-tab] > dd > a', function (e) {
                e.preventDefault();

                var tab = $(this).parent(),
                    target = $('#' + this.href.split('#')[1]),
                    siblings = tab.siblings(),
                    settings = tab.closest('[data-tab]').data('tab-init');

                tab.addClass(settings.active_class);
                siblings.removeClass(settings.active_class);
                target.siblings().removeClass(settings.active_class).end().addClass(settings.active_class);
            });
        },

        off: function () {
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.tooltip = {
        name: 'tooltip',

        version: '5.0.0',

        settings: {
            additional_inheritable_classes: [],
            tooltip_class: '.tooltip',
            append_to: 'body',
            touch_close_text: 'Tap To Close',
            disable_for_touch: false,
            tip_template: function (selector, content) {
                return '<span data-selector="' + selector + '" class="'
                    + Foundation.libs.tooltip.settings.tooltip_class.substring(1)
                    + '">' + content + '<span class="nub"></span></span>';
            }
        },

        cache: {},

        init: function (scope, method, options) {
            this.bindings(method, options);
        },

        events: function () {
            var self = this;

            if (Modernizr.touch) {
                $(this.scope)
                    .off('.tooltip')
                    .on('click.fndtn.tooltip touchstart.fndtn.tooltip touchend.fndtn.tooltip',
                    '[data-tooltip]', function (e) {
                        var settings = $.extend({}, self.settings, self.data_options($(this)));
                        if (!settings.disable_for_touch) {
                            e.preventDefault();
                            $(settings.tooltip_class).hide();
                            self.showOrCreateTip($(this));
                        }
                    })
                    .on('click.fndtn.tooltip touchstart.fndtn.tooltip touchend.fndtn.tooltip',
                    this.settings.tooltip_class, function (e) {
                        e.preventDefault();
                        $(this).fadeOut(150);
                    });
            } else {
                $(this.scope)
                    .off('.tooltip')
                    .on('mouseenter.fndtn.tooltip mouseleave.fndtn.tooltip',
                    '[data-tooltip]', function (e) {
                        var $this = $(this);

                        if (/enter|over/i.test(e.type)) {
                            self.showOrCreateTip($this);
                        } else if (e.type === 'mouseout' || e.type === 'mouseleave') {
                            self.hide($this);
                        }
                    });
            }
        },

        showOrCreateTip: function ($target) {
            var $tip = this.getTip($target);

            if ($tip && $tip.length > 0) {
                return this.show($target);
            }

            return this.create($target);
        },

        getTip: function ($target) {
            var selector = this.selector($target),
                tip = null;

            if (selector) {
                tip = $('span[data-selector="' + selector + '"]' + this.settings.tooltip_class);
            }

            return (typeof tip === 'object') ? tip : false;
        },

        selector: function ($target) {
            var id = $target.attr('id'),
                dataSelector = $target.attr('data-tooltip') || $target.attr('data-selector');

            if ((id && id.length < 1 || !id) && typeof dataSelector != 'string') {
                dataSelector = 'tooltip' + Math.random().toString(36).substring(7);
                $target.attr('data-selector', dataSelector);
            }

            return (id && id.length > 0) ? id : dataSelector;
        },

        create: function ($target) {
            var $tip = $(this.settings.tip_template(this.selector($target), $('<div></div>').html($target.attr('title')).html())),
                classes = this.inheritable_classes($target);

            $tip.addClass(classes).appendTo(this.settings.append_to);
            if (Modernizr.touch) {
                $tip.append('<span class="tap-to-close">' + this.settings.touch_close_text + '</span>');
            }
            $target.removeAttr('title').attr('title', '');
            this.show($target);
        },

        reposition: function (target, tip, classes) {
            var width, nub, nubHeight, nubWidth, column, objPos;

            tip.css('visibility', 'hidden').show();

            width = target.data('width');
            nub = tip.children('.nub');
            nubHeight = nub.outerHeight();
            nubWidth = nub.outerHeight();

            objPos = function (obj, top, right, bottom, left, width) {
                return obj.css({
                    'top': (top) ? top : 'auto',
                    'bottom': (bottom) ? bottom : 'auto',
                    'left': (left) ? left : 'auto',
                    'right': (right) ? right : 'auto',
                    'width': (width) ? width : 'auto'
                }).end();
            };

            objPos(tip, (target.offset().top + target.outerHeight() + 10), 'auto', 'auto', target.offset().left, width);

            if (this.small()) {
                objPos(tip, (target.offset().top + target.outerHeight() + 10), 'auto', 'auto', 12.5, $(this.scope).width());
                tip.addClass('tip-override');
                objPos(nub, -nubHeight, 'auto', 'auto', target.offset().left);
            } else {
                var left = target.offset().left;
                if (Foundation.rtl) {
                    left = target.offset().left + target.offset().width - tip.outerWidth();
                }
                objPos(tip, (target.offset().top + target.outerHeight() + 10), 'auto', 'auto', left, width);
                tip.removeClass('tip-override');
                if (classes && classes.indexOf('tip-top') > -1) {
                    objPos(tip, (target.offset().top - tip.outerHeight()), 'auto', 'auto', left, width)
                        .removeClass('tip-override');
                } else if (classes && classes.indexOf('tip-left') > -1) {
                    objPos(tip, (target.offset().top + (target.outerHeight() / 2) - nubHeight * 2.5), 'auto', 'auto', (target.offset().left - tip.outerWidth() - nubHeight), width)
                        .removeClass('tip-override');
                } else if (classes && classes.indexOf('tip-right') > -1) {
                    objPos(tip, (target.offset().top + (target.outerHeight() / 2) - nubHeight * 2.5), 'auto', 'auto', (target.offset().left + target.outerWidth() + nubHeight), width)
                        .removeClass('tip-override');
                }
            }

            tip.css('visibility', 'visible').hide();
        },

        small: function () {
            return matchMedia(Foundation.media_queries.small).matches;
        },

        inheritable_classes: function (target) {
            var inheritables = ['tip-top', 'tip-left', 'tip-bottom', 'tip-right', 'noradius'].concat(this.settings.additional_inheritable_classes),
                classes = target.attr('class'),
                filtered = classes ? $.map(classes.split(' '),function (el, i) {
                    if ($.inArray(el, inheritables) !== -1) {
                        return el;
                    }
                }).join(' ') : '';

            return $.trim(filtered);
        },

        show: function ($target) {
            var $tip = this.getTip($target);

            this.reposition($target, $tip, $target.attr('class'));
            $tip.fadeIn(150);
        },

        hide: function ($target) {
            var $tip = this.getTip($target);

            $tip.fadeOut(150);
        },

        // deprecate reload
        reload: function () {
            var $self = $(this);

            return ($self.data('fndtn-tooltips')) ? $self.foundationTooltips('destroy').foundationTooltips('init') : $self.foundationTooltips('init');
        },

        off: function () {
            $(this.scope).off('.fndtn.tooltip');
            $(this.settings.tooltip_class).each(function (i) {
                $('[data-tooltip]').get(i).attr('title', $(this).text());
            }).remove();
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));
;
(function ($, window, document, undefined) {
    'use strict';

    Foundation.libs.topbar = {
        name: 'topbar',

        version: '5.0.1',

        settings: {
            index: 0,
            sticky_class: 'sticky',
            custom_back_text: true,
            back_text: 'Back',
            is_hover: true,
            mobile_show_parent_link: false,
            scrolltop: true // jump to top when sticky nav menu toggle is clicked
        },

        init: function (section, method, options) {
            Foundation.inherit(this, 'addCustomRule register_media throttle');
            var self = this;

            self.register_media('topbar', 'foundation-mq-topbar');

            this.bindings(method, options);

            $('[data-topbar]', this.scope).each(function () {
                var topbar = $(this),
                    settings = topbar.data('topbar-init'),
                    section = $('section', this),
                    titlebar = $('> ul', this).first();

                topbar.data('index', 0);

                var topbarContainer = topbar.parent();
                if (topbarContainer.hasClass('fixed') || topbarContainer.hasClass(settings.sticky_class)) {
                    self.settings.sticky_class = settings.sticky_class;
                    self.settings.stick_topbar = topbar;
                    topbar.data('height', topbarContainer.outerHeight());
                    topbar.data('stickyoffset', topbarContainer.offset().top);
                } else {
                    topbar.data('height', topbar.outerHeight());
                }

                if (!settings.assembled) self.assemble(topbar);

                if (settings.is_hover) {
                    $('.has-dropdown', topbar).addClass('not-click');
                } else {
                    $('.has-dropdown', topbar).removeClass('not-click');
                }

                // Pad body when sticky (scrolled) or fixed.
                self.addCustomRule('.f-topbar-fixed { padding-top: ' + topbar.data('height') + 'px }');

                if (topbarContainer.hasClass('fixed')) {
                    $('body').addClass('f-topbar-fixed');
                }
            });

        },

        toggle: function (toggleEl) {
            var self = this;

            if (toggleEl) {
                var topbar = $(toggleEl).closest('[data-topbar]');
            } else {
                var topbar = $('[data-topbar]');
            }

            var settings = topbar.data('topbar-init');

            var section = $('section, .section', topbar);

            if (self.breakpoint()) {
                if (!self.rtl) {
                    section.css({left: '0%'});
                    $('>.name', section).css({left: '100%'});
                } else {
                    section.css({right: '0%'});
                    $('>.name', section).css({right: '100%'});
                }

                $('li.moved', section).removeClass('moved');
                topbar.data('index', 0);

                topbar
                    .toggleClass('expanded')
                    .css('height', '');
            }

            if (settings.scrolltop) {
                if (!topbar.hasClass('expanded')) {
                    if (topbar.hasClass('fixed')) {
                        topbar.parent().addClass('fixed');
                        topbar.removeClass('fixed');
                        $('body').addClass('f-topbar-fixed');
                    }
                } else if (topbar.parent().hasClass('fixed')) {
                    if (settings.scrolltop) {
                        topbar.parent().removeClass('fixed');
                        topbar.addClass('fixed');
                        $('body').removeClass('f-topbar-fixed');

                        window.scrollTo(0, 0);
                    } else {
                        topbar.parent().removeClass('expanded');
                    }
                }
            } else {
                if (topbar.parent().hasClass(self.settings.sticky_class)) {
                    topbar.parent().addClass('fixed');
                }

                if (topbar.parent().hasClass('fixed')) {
                    if (!topbar.hasClass('expanded')) {
                        topbar.removeClass('fixed');
                        topbar.parent().removeClass('expanded');
                        self.update_sticky_positioning();
                    } else {
                        topbar.addClass('fixed');
                        topbar.parent().addClass('expanded');
                    }
                }
            }
        },

        timer: null,

        events: function (bar) {
            var self = this;
            $(this.scope)
                .off('.topbar')
                .on('click.fndtn.topbar', '[data-topbar] .toggle-topbar', function (e) {
                    e.preventDefault();
                    self.toggle(this);
                })
                .on('click.fndtn.topbar', '[data-topbar] li.has-dropdown', function (e) {
                    var li = $(this),
                        target = $(e.target),
                        topbar = li.closest('[data-topbar]'),
                        settings = topbar.data('topbar-init');

                    if (target.data('revealId')) {
                        self.toggle();
                        return;
                    }

                    if (self.breakpoint()) return;
                    if (settings.is_hover && !Modernizr.touch) return;

                    e.stopImmediatePropagation();

                    if (li.hasClass('hover')) {
                        li
                            .removeClass('hover')
                            .find('li')
                            .removeClass('hover');

                        li.parents('li.hover')
                            .removeClass('hover');
                    } else {
                        li.addClass('hover');

                        if (target[0].nodeName === 'A' && target.parent().hasClass('has-dropdown')) {
                            e.preventDefault();
                        }
                    }
                })
                .on('click.fndtn.topbar', '[data-topbar] .has-dropdown>a', function (e) {
                    if (self.breakpoint()) {

                        e.preventDefault();

                        var $this = $(this),
                            topbar = $this.closest('[data-topbar]'),
                            section = topbar.find('section, .section'),
                            dropdownHeight = $this.next('.dropdown').outerHeight(),
                            $selectedLi = $this.closest('li');

                        topbar.data('index', topbar.data('index') + 1);
                        $selectedLi.addClass('moved');

                        if (!self.rtl) {
                            section.css({left: -(100 * topbar.data('index')) + '%'});
                            section.find('>.name').css({left: 100 * topbar.data('index') + '%'});
                        } else {
                            section.css({right: -(100 * topbar.data('index')) + '%'});
                            section.find('>.name').css({right: 100 * topbar.data('index') + '%'});
                        }

                        topbar.css('height', $this.siblings('ul').outerHeight(true) + topbar.data('height'));
                    }
                });

            $(window).off('.topbar').on('resize.fndtn.topbar', self.throttle(function () {
                self.resize.call(self);
            }, 50)).trigger('resize');

            $('body').off('.topbar').on('click.fndtn.topbar touchstart.fndtn.topbar', function (e) {
                var parent = $(e.target).closest('li').closest('li.hover');

                if (parent.length > 0) {
                    return;
                }

                $('[data-topbar] li').removeClass('hover');
            });

            // Go up a level on Click
            $(this.scope).on('click.fndtn.topbar', '[data-topbar] .has-dropdown .back', function (e) {
                e.preventDefault();

                var $this = $(this),
                    topbar = $this.closest('[data-topbar]'),
                    section = topbar.find('section, .section'),
                    settings = topbar.data('topbar-init'),
                    $movedLi = $this.closest('li.moved'),
                    $previousLevelUl = $movedLi.parent();

                topbar.data('index', topbar.data('index') - 1);

                if (!self.rtl) {
                    section.css({left: -(100 * topbar.data('index')) + '%'});
                    section.find('>.name').css({left: 100 * topbar.data('index') + '%'});
                } else {
                    section.css({right: -(100 * topbar.data('index')) + '%'});
                    section.find('>.name').css({right: 100 * topbar.data('index') + '%'});
                }

                if (topbar.data('index') === 0) {
                    topbar.css('height', '');
                } else {
                    topbar.css('height', $previousLevelUl.outerHeight(true) + topbar.data('height'));
                }

                setTimeout(function () {
                    $movedLi.removeClass('moved');
                }, 300);
            });
        },

        resize: function () {
            var self = this;
            $('[data-topbar]').each(function () {
                var topbar = $(this),
                    settings = topbar.data('topbar-init');

                var stickyContainer = topbar.parent('.' + self.settings.sticky_class);
                var stickyOffset;

                if (!self.breakpoint()) {
                    var doToggle = topbar.hasClass('expanded');
                    topbar
                        .css('height', '')
                        .removeClass('expanded')
                        .find('li')
                        .removeClass('hover');

                    if (doToggle) {
                        self.toggle(topbar);
                    }
                }

                if (stickyContainer.length > 0) {
                    if (stickyContainer.hasClass('fixed')) {
                        // Remove the fixed to allow for correct calculation of the offset.
                        stickyContainer.removeClass('fixed');

                        stickyOffset = stickyContainer.offset().top;
                        if ($(document.body).hasClass('f-topbar-fixed')) {
                            stickyOffset -= topbar.data('height');
                        }

                        topbar.data('stickyoffset', stickyOffset);
                        stickyContainer.addClass('fixed');
                    } else {
                        stickyOffset = stickyContainer.offset().top;
                        topbar.data('stickyoffset', stickyOffset);
                    }
                }

            });
        },

        breakpoint: function () {
            return !matchMedia(Foundation.media_queries['topbar']).matches;
        },

        assemble: function (topbar) {
            var self = this,
                settings = topbar.data('topbar-init'),
                section = $('section', topbar),
                titlebar = $('> ul', topbar).first();

            // Pull element out of the DOM for manipulation
            section.detach();

            $('.has-dropdown>a', section).each(function () {
                var $link = $(this),
                    $dropdown = $link.siblings('.dropdown'),
                    url = $link.attr('href');

                if (settings.mobile_show_parent_link && url && url.length > 1) {
                    var $titleLi = $('<li class="title back js-generated"><h5><a href="#"></a></h5></li><li><a class="parent-link js-generated" href="' + url + '">' + $link.text() + '</a></li>');
                } else {
                    var $titleLi = $('<li class="title back js-generated"><h5><a href="#"></a></h5></li>');
                }

                // Copy link to subnav
                if (settings.custom_back_text == true) {
                    $('h5>a', $titleLi).html(settings.back_text);
                } else {
                    $('h5>a', $titleLi).html('&laquo; ' + $link.html());
                }
                $dropdown.prepend($titleLi);
            });

            // Put element back in the DOM
            section.appendTo(topbar);

            // check for sticky
            this.sticky();

            this.assembled(topbar);
        },

        assembled: function (topbar) {
            topbar.data('topbar-init', $.extend({}, topbar.data('topbar-init'), {assembled: true}));
        },

        height: function (ul) {
            var total = 0,
                self = this;

            $('> li', ul).each(function () {
                total += $(this).outerHeight(true);
            });

            return total;
        },

        sticky: function () {
            var $window = $(window),
                self = this;

            $(window).on('scroll', function () {
                self.update_sticky_positioning();
            });
        },

        update_sticky_positioning: function () {
            var klass = '.' + this.settings.sticky_class;
            var $window = $(window);

            if ($(klass).length > 0) {
                var distance = this.settings.sticky_topbar.data('stickyoffset');
                if (!$(klass).hasClass('expanded')) {
                    if ($window.scrollTop() > (distance)) {
                        if (!$(klass).hasClass('fixed')) {
                            $(klass).addClass('fixed');
                            $('body').addClass('f-topbar-fixed');
                        }
                    } else if ($window.scrollTop() <= distance) {
                        if ($(klass).hasClass('fixed')) {
                            $(klass).removeClass('fixed');
                            $('body').removeClass('f-topbar-fixed');
                        }
                    }
                }
            }
        },

        off: function () {
            $(this.scope).off('.fndtn.topbar');
            $(window).off('.fndtn.topbar');
        },

        reflow: function () {
        }
    };
}(jQuery, this, this.document));