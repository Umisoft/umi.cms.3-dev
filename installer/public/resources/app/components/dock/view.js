define(['App'], function(UMI){
    'use strict';

    return function(){

        var expanded = false;
        var move = {};
        var def = {old: 0, cur: 0, def: 0, coeff: 1 };

        UMI.DockView = Ember.View.extend({
            classNames: ['umi-dock'],
            didInsertElement: function(){
                var self = this;
                var moduleImages = [];
                var promiseImages = [];
                var dock = self.$().find('.dock')[0];
                /**
                 * Метод загружает изображения формируя массив promise
                 * */
                var imageLoad = function(src){
                    var deferred = Ember.RSVP.defer();
                    promiseImages.push(deferred.promise);
                    var image = document.createElement('img');
                    image.onload = function(){
                        this.parentNode.removeChild(this);
                        deferred.resolve('loaded');
                    };
                    image.src = src;
                    document.body.appendChild(image);
                };

                this.get('childViews').filter(function(view){
                    var icon = view.get('icon');
                    if(icon){
                        moduleImages.push(icon);
                    }
                });

                for(var i = 0; i < moduleImages.length; i++){
                    imageLoad(moduleImages[i]);
                }
                Ember.RSVP.all(promiseImages).then(
                    function(){
                        dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                        $(dock).addClass('active');
                    }
                );

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
            },
            mouseLeave: function(event){
                var self = this;
                var dock = self.$().find('.dock')[0];
                def.old = false;
                var leaveDock = function(dock){
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
                };
                if(!event.relatedTarget){
                    //TODO: Add unbind event for body
                    $(document.body).bind('mouseover', function(e){
                        if($(dock).hasClass('full') && !($(e.target).closest('.dock')).size()){
                            leaveDock(dock);
                        }
                        $(this).unbind('mouseover');
                    });
                    return;
                }
                leaveDock(dock);
                var content = self.get('controller.content');
                content.findBy('isActive', true).set('isActive', false);
                var activeModule = self.get('controller.activeModule');
                content.findBy('name', activeModule).set('isActive', true);
            }
        });

        UMI.DockModuleButtonView = Ember.View.extend({
            tagName: 'li',
            classNames: ['umi-dock-button'],
            classNameBindings: ['active'],
            active: function(){
                return this.get('model.isActive');
            }.property('model.isActive'),
            icon: function(){
                return '/resources/modules/' + this.get('model.name') + '/icon.png';
            }.property('model.name'),
            mouseEnter: function(){
                var dock = this.$().closest('.dock');
                var $el = this.$();
                if(!expanded){
                    expanded = true;
                    move.proccess = false;
                    var posBegin = $el.position().left + $el[0].offsetWidth / 2 + (parseInt(dock[0].style.marginLeft, 10) || 0);

                    $($el[0].parentNode).find('img').stop().animate({height: 48, margin: '6px 36px 28px'}, {
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
                var setActive = function(self){
                    if(!self.get('active')){
                        self.get('parentView.controller.content').findBy('isActive', true).set('isActive', false);
                    }
                    self.get('model').set('isActive', true);
                };
                var self = this;
                setActive(self);
            }
        });

        UMI.DockComponentsGroupView = Ember.View.extend({
            tagName: 'nav',
            classNames: ['components-nav'],
            classNameBindings: ['active'],
            active: function(){
                return this.get('content.isActive');
            }.property('content.isActive')
        });
    };
});