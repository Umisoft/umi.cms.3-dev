!function(a,b,c,d){"use strict";var e=b.Foundation=b.Foundation||{};e.libs=e.libs||{},e.libs.dropdown={name:"dropdown",version:"5.3.0.umi-custom",settings:{activeClass:"open",isHover:!1,fastSelect:!0,fastSelectHoverSelector:"li",fastSelectHoverClassName:"hover",fastSelectTarget:"a",side:"bottom",align:"left",selectorById:!0,minWidthLikeElement:".button",adaptiveBehaviour:!0,closeAfterClickOnElement:"a",opened:function(){},closed:function(){}},selector:function(){return"["+this.attr_name()+"]"},init:function(a,b,c){e.inherit(this,"throttle"),this.bindings(b,c)},events:function(a){var b=this,c=b.S;c(b.scope).off("."+b.name),b.eventListener.mousedown.call(this,a),b.eventListener.click.call(this,a),b.eventListener.hover.call(this),b.eventListener.resize.call(this),b.eventListener.toggleObserver.call(this)},eventListener:{click:function(){var b=this,c=b.S;c(b.scope).on("click.fndtn.dropdown",b.selector(),function(d){var e=c(this).data(b.attr_name(!0)+"-init")||b.settings;e.fastSelect||e.isHover&&!Modernizr.touch||(d.preventDefault(),b.toggle(a(this)))})},mousedown:function(){var b=this,c=b.S,d="["+b.attr_name()+"-content]";c(b.scope).on("mousedown.fndtn.dropdown",b.selector(),function(e){var f=c(this).data(b.attr_name(!0)+"-init")||b.settings;if(f.fastSelect&&(!f.isHover||Modernizr.touch)){e.preventDefault();var g=a(this),h=b.toggle(g),i=f.fastSelectHoverSelector,j=f.fastSelectHoverClassName,k=f.fastSelectTarget;c(b.scope).on("mousemove.fndtn.dropdown.fast",d,function(b){h=a(this);var c=a(b.target).closest(i);c.length?c.hasClass(j)||(h.find(i+"."+j).removeClass(j),c.addClass(j)):h.find(i+"."+j).removeClass(j)}),c(b.scope).on("mouseout.fndtn.dropdown.fast",d,function(){a(this).find(i+"."+j).removeClass(j)}),c(b.scope).on("mouseup.fndtn.dropdown.fast",function(d){var e,f=a(d.target);h.find(d.target).length?(e=f.closest(k),e.length&&e.trigger("click")):f.closest(g).length||b.closeall(),c(b.scope).off(".dropdown.fast")})}})},hover:function(){var a=this,b=a.S;b(a.scope).on("mouseenter.fndtn.dropdown","["+a.attr_name()+"], ["+a.attr_name()+"-content]",function(){var c,e,f=b(this);clearTimeout(a.timeout),f.data(a.dataAttr())!==d?(e=f,c=a.getDropdown(e)):(c=f,e=b("["+a.attr_name()+'="'+c.attr("id")+'"]'),0===e.length&&(e=c.parent().children(a.selector())));var g=e.data(a.attr_name(!0)+"-init")||a.settings;g.isHover&&(a.closeall.call(a),a.open.apply(a,[c,e]))}),b(a.scope).on("mouseleave.fndtn.dropdown","["+a.attr_name()+"], ["+a.attr_name()+"-content]",function(){var c=b(this);a.timeout=setTimeout(function(){var e,f;if(c.data(a.dataAttr())!==d)e=c.data(a.dataAttr()+"-init")||a.settings,e.isHover&&(f=a.getDropdown(c),a.close.call(a,f));else{var g=b("["+a.attr_name()+'="'+c.attr("id")+'"]');0===g.length&&(g=c.parent().children(a.selector())),e=g.data(a.attr_name(!0)+"-init")||a.settings,e.isHover&&a.close.call(a,c)}},150)})},onClickMissDropdown:function(){var b=this,c=b.S,d="["+b.attr_name()+"-content]";c(b.scope).on("click.fndtn.dropdown.miss",function(e){var f=c(e.target).closest(d);if(!c(e.target).data(b.dataAttr())&&!c(e.target).parent().data(b.dataAttr())){if(f.length>0&&(c(e.target).is(d)||a.contains(f.first()[0],e.target)))return void e.stopPropagation();b.close.call(b,c(d)),c(b.scope).off("click.fndtn.dropdown.miss")}})},offClickMissDropdown:function(){var a=this,b=a.S;b(this.scope).off("click.fndtn.dropdown.miss")},resize:function(){var a=this,c=a.S;c(b).off(".dropdown").on("resize.fndtn.dropdown",a.throttle(function(){a.resize.call(a)},50)),a.resize.call(a)},toggleObserver:function(){var a=this,b=a.S;b(a.scope).on("opened.fndtn.dropdown","["+a.attr_name()+"-content]",function(){a.settings.opened.call(this)}),b(a.scope).on("closed.fndtn.dropdown","["+a.attr_name()+"-content]",function(){a.settings.closed.call(this)})}},getDropdown:function(b){var c,d,e=this,f=e.S,g=f(b).data(e.attr_name(!0)+"-init")||e.settings;if(g.selectorById){if(d=b.data(this.dataAttr()),!d)return console.warn("Id attr is undefined."),!1;c=f("#"+d)}else c=a(a(f(b)[0].parentNode).children("[data-"+e.name+"-content]")[0]);return c},toggle:function(a){var b=this,c=b.getDropdown(a);return c&&0!==c.length?(b.close.call(b,b.S("["+b.attr_name()+"-content]").not(c)),c.hasClass(b.settings.activeClass)?(b.close.call(b,c),c.data("target")!==a.get(0)&&b.open.call(b,c,a)):b.open.call(b,c,a),c):c},close:function(a){var b=this;a.each(function(){b.S(this).hasClass(b.settings.activeClass)&&(b.S(this).off("click.fndtn.dropdown.closeOnClick").removeClass(b.settings.activeClass).removeAttr("style").prev("["+b.attr_name()+"]").removeClass(b.settings.activeClass).removeData("target"),b.S(this).trigger("closed").trigger("closed.fndtn.dropdown",[a]))}),this.eventListener.offClickMissDropdown.call(this)},closeall:function(){var b=this;a.each(b.S("["+this.attr_name()+"-content]"),function(){b.close.call(b,b.S(this))})},open:function(a,b){var c=this,d=c.S,e=d(b).data(c.attr_name(!0)+"-init")||c.settings;c.setDropdownStyle(a.addClass(e.activeClass),b),a.prev("["+c.attr_name()+"]").addClass(e.activeClass),a.data("target",b.get(0)).trigger("opened.fndtn.dropdown",[a,b]),e.closeAfterClickOnElement&&a.on("click.fndtn.dropdown.closeOnClick",e.closeAfterClickOnElement,function(){c.close.call(c,d(a)),a.off("click.fndtn.dropdown.closeOnClick")}),setTimeout(function(){c.eventListener.onClickMissDropdown.call(c)},150)},resize:function(){var a=this.S("["+this.attr_name()+"-content].open"),b=this.S("["+this.attr_name()+'="'+a.attr("id")+'"]');a.length&&b.length&&this.setDropdownStyle(a,b)},dataAttr:function(){return this.namespace.length>0?this.namespace+"-"+this.name:this.name},setDropdownStyle:function(a,b){var c,d,f=b.data(this.attr_name(!0)+"-init")||this.settings;if(this.clearIdx(),this.small()){c=Math.max((b.width()-a.width())/2,8);var g=this.styleForSide.bottom.call(a,b);d={position:"absolute",width:"95%",maxWidth:"none",top:g.top},f.minWidthLikeElement&&(d.minWidth=b.outerWidth()+"px"),a.attr("style","").removeClass("drop-left drop-right drop-top").css(d),a.css(e.rtl?"right":"left",c)}else this.style(a,b,f);return a},style:function(b,c,d){var e=d.side,f=d.align,g={position:"absolute"};d.minWidthLikeElement&&(g.minWidth=c.closest(d.minWidthLikeElement).outerWidth()+"px",b.css({minWidth:g.minWidth}));var h=this.checkPosition(b,c,d,e,f);e=h.side,f=h.align;var i=this.styleFor._basePosition.call(b,c),j=this.styleFor.side[e].call(b,c,i);j=a.extend(j,this.styleFor.align[f].call(b,c,i)),j=a.extend(g,j),b.attr("style","").css(g)},checkPosition:function(c,d,e,f,g){if(e.adaptiveBehaviour){var h={width:a(b).width(),height:a(b).height()},i={width:c.outerWidth(),height:c.outerHeight()},j=(c.offset(),{width:d.outerWidth(),height:d.outerHeight()}),k=d.offset();switch(f){case"top":i.height>k.top&&h.height>i.height+k.top+j.height&&(f="bottom");break;case"bottom":k.top+i.height+j.height>h.height&&k.top-i.height>0&&(f="top");break;case"left":k.left-i.width<0&&k.left+j.width+i.width<h.width&&(f="right");break;case"right":k.left+j.width+i.width>h.width&&k.left-i.width>0&&(f="left")}switch(g){case"top":k.top+i.height>h.height&&k.top>i.height&&(g="bottom");break;case"bottom":k.top<i.height&&k.top+i.height>h.height&&(g="top");break;case"left":k.left+i.width>h.width&&k.left+j.width>i.width&&(g="right");break;case"right":k.left+j.width<i.width&&k.left+i.width<h.width&&(g="left")}}return{side:f,align:g}},styleFor:{_basePosition:function(a){var b=this.offsetParent().offset(),c=a.offset();return c.top-=b.top,c.left-=b.left,c},side:{top:function(a,b){return this.addClass("drop-top"),{top:b.top-this.outerHeight()}},bottom:function(a,b){return{top:b.top+a.outerHeight()}},left:function(a,b){return this.addClass("drop-left"),{left:b.left-this.outerWidth()}},right:function(a,b){return this.addClass("drop-right"),{left:b.left+a.outerWidth()}}},align:{top:function(a,b){return{top:b.top}},bottom:function(a,b){return{top:b.top-this.outerHeight()+a.outerHeight()}},left:function(a,b){return{left:b.left}},right:function(a,b){return{left:b.left-this.outerWidth()+a.outerWidth()}}}},clearIdx:function(){var a=e.stylesheet;this.rule_idx&&(a.deleteRule(this.rule_idx),a.deleteRule(this.rule_idx),delete this.rule_idx)},small:function(){return matchMedia(e.media_queries.small).matches&&!matchMedia(e.media_queries.medium).matches},off:function(){this.S(this.scope).off(".fndtn.dropdown"),this.S(b).off(".fndtn.dropdown")}}}(jQuery,window,window.document);