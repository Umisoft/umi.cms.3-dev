define(['App'], function(UMI) {
    'use strict';

    return function() {

        var expanded = false;
        var move = {};
        var def = {old: 0, cur: 0, def: 0, coeff: 1 };
        var intervalLeaveItem;

        var dockViewMixin = Ember.Mixin.create({
            templateName: 'partials/dock',

            classNames: ['umi-dock', 's-unselectable'],

            isTouchDevice: function() {
                return Modernizr.touch;
            }.property(),

            didInsertElement: function() {
                var self = this;
                var dock = self.$().find('.dock')[0];
                dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                $(dock).addClass('active');

                if (!dock.style.marginLeft) {
                    dock.style.marginLeft = 0;
                }

                $(window).on('resize.umi.dock', function() {
                    setTimeout(function() {
                        dock.style.left = (dock.parentNode.offsetWidth - dock.offsetWidth) / 2 + 'px';
                    }, 0);
                })
                .on('dockChange.umi.dock', function() {
                    $(dock).delay(200).animate({left: (dock.parentNode.offsetWidth - dock.offsetWidth) / 2}, 500);
                });

                self.changeLayout();
            },

            willDestroyElement: function() {
                $(window).off('.umi.dock');
            },

            changeLayout: function() {
                $('body').removeClass(function(index, classes) {
                    return (classes.match(/dock-\w+/ig) || []).join(' ');
                })
                .addClass('dock-' + this.get('controller.activeMode.name'));

                $(window).trigger('headerHeightChange');
            }
        });

        var dockModuleButtonViewMixin = Ember.Mixin.create({
            tagName: 'li',

            classNames: ['umi-dock-button'],

            attributeBindings: ['name:data-name'],

            name: function() {
                return this.get('module.name');
            }.property('module.name'),

            didInsertElement: function() {
                if (this.get('parentView.isTouchDevice')) {
                    return;
                }
                var self = this;
                var $el = self.$();
                var $dock = self.$().closest('.dock');
                var $body = $(document.body);
                var $empty = $('<li class="umi-dock-button-empty">');

                $el.children('a').on('mousedown.umi.dock.button', function(e) {
                    e.preventDefault();
                    var cursorPositionX = e.pageX;
                    var cursorPositionY = e.pageY;
                    var elPosition = $el.position();
                    var elStartPositionLeft = elPosition.left;
                    var isMoved = false;
                    var isRemoved = false;
                    var isResorted = false;
                    var elementWidth = $el.width() + 2;


                    $empty.width(elementWidth);

                    $body.on('mousemove.sort.umi.dock', function(e) {
                        elPosition.left = elPosition.left + e.pageX - cursorPositionX;
                        elPosition.top = elPosition.top + e.pageY - cursorPositionY;

                        if (!isMoved) {
                            self.set('parentView.isBlocked', true);
                            $el.addClass('umi-dock-button-dragging').after($empty);
                            $dock.addClass('sorting');
                            isMoved = true;
                        }

                        var minTopShift = 20; // Смещение до начала удаления
                        var maxTopShift = 100; // Смещение до конца удаления
                        var specElPositionTop = elPosition.top - minTopShift;
                        if (elPosition.top > minTopShift) {
                            if (elPosition.top >= maxTopShift + minTopShift) {
                                isRemoved = true;
                            } else {
                                isRemoved = false;
                                if (specElPositionTop < 3) {
                                    specElPositionTop = 0;
                                }
                                var opacity = 1 - specElPositionTop / maxTopShift;
                                if (opacity < 0.1) {
                                    opacity = 0.1;
                                }
                                $el.css({top: specElPositionTop, opacity: opacity});
                            }
                        } else {
                            var shift = Math.round(elPosition.left - elStartPositionLeft);
                            var $nearElement = null;
                            if (shift > 0) {
                                $nearElement = $empty.nextAll('li:not(.umi-dock-button-dragging):first');
                            }
                            if (shift < 0) {
                                $nearElement = $empty.prevAll('li:not(.umi-dock-button-dragging):first');
                            }
                            if ($nearElement && $nearElement.length) {
                                var nearElementWidth = $nearElement.width();

                                if (Math.abs(shift) > nearElementWidth / 2) {
                                    if (shift > 0) {
                                        $nearElement.after($empty);
                                    }
                                    else {
                                        $nearElement.before($empty);
                                    }
                                    elStartPositionLeft = $empty.position().left;
                                    isResorted = true;
                                }
                            }
                            $el.css({left: elPosition.left});
                        }

                        cursorPositionX = e.pageX;
                        cursorPositionY = e.pageY;

                    }).on('mouseup.sort.umi.dock', function() {
                        $body.off('.sort.umi.dock');

                        if (!isMoved) {
                            return;
                        }

                        $dock.removeClass('sorting');
                        self.set('parentView.isBlocked', false);

                        if (isRemoved) {
                            var elName = $el.data().name;
                            var newHidden = UMI.Utils.LS.get('dock.hiddenModules') || [];

                            newHidden.push(elName);
                            $el.add($empty).remove();

                            self.get('controller').hideModule(elName);
                            UMI.Utils.LS.set('dock.hiddenModules', newHidden);

                        } else {

                            $empty.after(
                                $el.removeClass('umi-dock-button-dragging').css({left: '', top: '', opacity: ''})
                            ).remove();

                            var parentView = self.get('parentView');
                            if (typeof parentView.leaveDock === 'function') parentView.leaveDock();

                            if (isResorted) {
                                isResorted = false;
                                var newOrder = [];

                                $dock.children('li').each(function() {
                                    newOrder.push($(this).data().name);
                                });

                                UMI.Utils.LS.set('dock.sortedOrder', newOrder);
                                self.get('controller').resortModules(newOrder);
                            }
                        }
                    });
                });
            }
        });

        var dockModes = {
            // Type Ext
            dynamic: {

                classNames: ['dynamic'],

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

                    self.changeLayout();
                },
                mouseLeave: function(event) {
                    var self = this;
                    var dock = self.$().find('.dock')[0];
                    def.old = false;

                    if (this.get('parentView.isTouchDevice')) {
                        return;
                    }

                    if (!event.relatedTarget) {
                        $(document.body).bind('mouseover', function(e) {
                            if ($(dock).hasClass('full') && !($(e.target).closest('.dock')).size()) {
                                self.leaveDock();
                            }
                            $(this).unbind('mouseover');
                        });
                        return;
                    }

                    this.set('needDockMinimize', true);
                    this.leaveDock();
                },

                leaveDock: function() {
                    if (this.get('parentView.isTouchDevice') ||
                        this.get('isBlocked') || !this.get('needDockMinimize')) {
                        return;
                    }

                    var self = this;
                    var dock = self.$().find('.dock')[0];
                    expanded = false;
                    move.oldtime = false;
                    move.proccess = false;

                    this.set('needDockMinimize', false);

                    $(dock).find('.umi-dock-module-icon').stop()
                        .animate({margin: '9px 11px 9px', height: 30, width: 30}, {
                        duration: 130,
                        easing: 'linear'
                    });

                    $(dock).animate({marginLeft: '0px'}, {duration: 130, easing: 'linear', complete: function() {
                        $(dock).removeClass('full');
                    }});
                },

                isBlocked: false,

                needDockMinimize: false,

                // Button Ext
                dockModuleButtonView: Ember.View.extend(dockModuleButtonViewMixin, {
                    mouseEnter: function() {
                        var self = this;
                        var dock = self.$().closest('.dock');
                        var $el = self.$();

                        if (self.get('parentView.isTouchDevice')) {
                            return;
                        }

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
                                                dock[0].style.marginLeft = posBegin -
                                                    (o.elem.parentNode.parentNode.offsetLeft +
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
                    }
                })
            },

            // Type Ext
            small: {
                classNames: ['small'],

                // Button Ext
                dockModuleButtonView: Ember.View.extend(dockModuleButtonViewMixin, {})
            },

            // Type Ext
            big: {
                classNames: ['big'],

                // Button Ext
                dockModuleButtonView: Ember.View.extend(dockModuleButtonViewMixin, {})
            },

            // Type Ext
            list: {
                classNames: ['list'],

                // Button Ext
                dockModuleButtonView: Ember.View.extend(dockModuleButtonViewMixin, {})
            }
        };

        UMI.DockView = Ember.View.extend({
            layout: Ember.Handlebars.compile('{{view view.modeView}}'),

            modeView: function() {
                var activeModeName = this.get('controller.activeMode.name');
                var mixin = dockModes[activeModeName];
                return Ember.View.extend(dockViewMixin, mixin);
            }.property('controller.activeMode.name'),

            _templateChanged: function() {
                this.rerender();
            }.observes('controller.activeMode.name'),

            willDestroyElement: function() {
                this.removeObserver('controller.activeMode.name');
            }
        });
    };
});
