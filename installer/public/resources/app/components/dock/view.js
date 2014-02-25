define(['App'], function(UMI){
    'use strict';

    return function(){

        // Было бы круто чтобы переменная все же была свойством view
        var delayResetActive;

        UMI.DockView = Ember.View.extend({
            classNames: ['row' , 'umi-dock'],
            mouseLeave: function(){
                var self = this;
                var resetActive = function(self){
                    var content = self.get('controller.content');
                    content.findBy('isActive', true).set('isActive', false);
                    var activeModule = self.get('controller.activeModule');
                    content.findBy('slug', activeModule).set('isActive', true);
                };
                $('body').one('click', function(){
                    resetActive(self);
                });
            },
            mouseEnter: function(){
                if(delayResetActive){
                    clearTimeout(delayResetActive);
                }
            }
        });

        UMI.DockModuleButtonView = Ember.View.extend({
            tagName: 'dd',
            classNameBindings: ['active'],
            active: function(){
                return this.get('content.isActive');
            }.property('content.isActive'),
            mouseEnter: function(){
                var setActive = function(self){
                    if(!self.get('active')){
                        self.get('parentView.controller.content').findBy('isActive', true).set('isActive', false);
                    }
                    self.get('content').set('isActive', true);
                };
                var self = this;
                setActive(self);
            }
        });

        UMI.DockComponentsGroupView = Ember.View.extend({
            tagName: 'dd',
            classNames: ['content'],
            classNameBindings: ['active'],
            active: function(){
                return this.get('content.isActive');
            }.property('content.isActive')
        });

        UMI.DockZoomView = Ember.View.extend({
            classNames: ['zoom', 'umi-dock'],
            didInsertElement: function(){
                dockZoom.init(this.$().find('.dock')[0], false, this);
            }
        });

        var dockZoom = {
            init: function(el, opt, view){
                var self = this;
                dockZoom.opt = $.extend({}, dockZoom.opt, opt);
                self.el = el;
                self.$el = $(el);
                self.update().addEvent();
                self.buffer = document.createElement('div');
                self.buffer.className = 'dock-buffer';
                if(!self.el.style.marginLeft){
                    self.el.style.marginLeft = 0;
                }
                self.buffer = self.el.parentNode.appendChild(self.buffer);
                window.onresize = function(){
                    self.update();
                };
                //Костыль!
                setTimeout(function(){
                    self.update();
                }, 200);
                this.view = view;
            },
            opt: {
                'heightMin': 48,
                'deltaHeight': 95 - 48
            },
            update: function(){
                this.el.style.left = (this.el.parentNode.offsetWidth - this.el.offsetWidth) / 2 + 'px';
                this.el.className = 'dock navigation active';
                return this;
            },
            addEvent: function(){
                var self = this, def = {old: 0, cur: 0, def: 0, coeff: 1 }, firstLoad = true, intervalLeaveDock, intervalLeaveItem, isActive, afterClick, futureOffset, move = {};
                for(var i = 0; i < this.el.childNodes.length; i++){
                    if(this.el.childNodes[i].nodeType !== 1){
                        continue;
                    }
                    if(this.el.childNodes[i].className.indexOf('favorites') !== -1){
                        continue;
                    }
                    this.el.childNodes[i].onmousemove = function(e){
                        if(!firstLoad){
                            var elHover = this;
                            clearInterval(intervalLeaveDock);
                            !intervalLeaveItem || clearTimeout(intervalLeaveItem);
                            intervalLeaveItem = setTimeout(function(){
                                onHover(e, elHover);
                            }, 300);
                        }
                        function onHover(event, elm){
                            if(isActive || afterClick){
                                return false;
                            }
                            isActive = true;
                            move.proccess = false;
                            var posBegin = $(elm).position().left + elm.offsetWidth / 2 + parseInt(self.el.style.marginLeft);

                            $(elm.parentNode).find('img').stop().animate({height: 40, margin: '14px 36px 38px'}, {
                                duration: 280,
                                step: function(n, o){
                                    if(this.parentNode.parentNode == elm){
                                        self.el.style.marginLeft = posBegin - (o.elem.parentNode.parentNode.offsetLeft + o.elem.parentNode.offsetWidth / 2) + 'px';
                                    }
                                },
                                complete: function(){
                                    self.$el.addClass('full');
                                    move.proccess = true;
                                }
                            });
                        }

                        firstLoad = false;
                    };
                    this.el.childNodes[i].onmouseout = function(e){
                        clearInterval(intervalLeaveItem);
                    };
                    this.el.childNodes[i].firstElementChild.onclick = function(){
                        clearInterval(intervalLeaveItem);
                        afterClick = true;
                        // Все ниже- костыль
                        $(this.parentNode.parentNode).children('.active').removeClass('active');
                        $(this.parentNode).addClass('active');
                        var slug = this.getAttribute('href').split('/')[2];
                        self.view.get('controller').transitionToRoute('module', slug);
                        var components;
                        switch(slug){
                            case 'data-models':
                                components = '<nav><a href="#" class="active">Типы данных</a></nav>';
                                break;
                            case 'structure':
                                components = '<nav><a href="#" class="active">Структура</a></nav>';
                                break;
                        }
                        $('.pretty-dock-components').html(components);
                        setTimeout(function(){
                            leaveDock(self.el);
                        }, 500);
                        return false;
                    }
                }
                function leaveDock(el){
                    clearInterval(intervalLeaveItem);
                    isActive = false;
                    move.oldtime = false;
                    move.proccess = false;
                    $(el.parentNode).find('img').stop().animate({margin: '9px 11px 9px', height: 30}, {
                        duration: 130,
                        easing: 'linear',
                        complete: function(){

                        }
                    });
                    self.$el.animate({marginLeft: '0px'}, {duration: 130, easing: 'linear', complete: function(){
                        self.$el.removeClass('full');
                        afterClick = false;
                    }});
                }

                this.$el.mouseleave(function(e){
                    def.old = false;
                    if(!e.relatedTarget){
                        $(document.body).bind('mouseover', function(e){
                            if(self.$el.hasClass('full') && !($(e.target).closest('.dock')).size()){
                                leaveDock(self.el);
                            }
                            $(this).unbind('mouseover');
                        });
                        return false;
                    }
                    if(e.relatedTarget.className == 'dock-buffer'){
                        return false;
                    }
                    var el = this;
                    intervalLeaveDock = setTimeout(function(){
                        leaveDock(el);
                    }, 500);
                }).mousemove(function(e){
                    if(!move.oldtime){
                        move.oldtime = new Date();
                    }
                    move.curtime = new Date();
                    if(move.curtime - move.oldtime > 700 || move.proccess){
                        moving(this);
                    }

                    function moving(el){
                        move.proccess = true;
                        var isDropdown = $(e.target).closest('.dropdown-menu').size(), dockParent = el.parentNode, elOffsetLeft = el.offsetLeft, elWidth = el.offsetWidth, dockParentWidth = el.parentNode.offsetWidth;
                        def.cur = e.clientX;
                        if(def.old){
                            def.def = def.old - def.cur;
                        }

                        if(Math.abs(elOffsetLeft) + elWidth > dockParentWidth && !isDropdown){
                            if(def.def > 0){
                                // move left
                                def.coeff = Math.abs(elOffsetLeft) / (e.clientX);
                                futureOffset = Math.round(parseInt(el.style.marginLeft) + def.def * def.coeff);
                                if(def.coeff > 0 && futureOffset + parseInt(el.style.left) < -20){
                                    el.style.marginLeft = futureOffset + 'px';
                                }
                            } else if(def.def < 0){
                                // move right
                                def.coeff = Math.abs((elWidth - dockParentWidth + elOffsetLeft) / (dockParentWidth - e.clientX));
                                futureOffset = Math.round(parseInt(el.style.marginLeft) + def.def * def.coeff);

                                if(def.coeff > 0 && dockParentWidth < elWidth - 20 + ( futureOffset + (parseInt(el.style.left) ))){
                                    el.style.marginLeft = futureOffset + 'px';
                                }
                            }
                        }
                        def.old = e.clientX;
                    }
                });
                $(self.el.parentNode).on('mouseleave mousemove', '.dock-buffer', function(e){
                    if(e.type == 'mousemove'){
                        clearInterval(intervalLeaveDock);
                        intervalLeaveDock = setTimeout(function(){
                            leaveDock(self.el);
                        }, 300);
                    } else if(e.type == 'mouseleave'){
                        clearInterval(intervalLeaveDock);
                        if(!$(e.relatedTarget).closest('.dock').size()){
                            leaveDock(self.el);
                        }
                    }
                });
            }
        };
    };
});