define(['App'], function(UMI) {
    'use strict';

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

                this.set('needDockMinimize', true);
                this.leaveDock();
            },

            leaveDock: function() {
                if (this.get('isBlocked') || !this.get('needDockMinimize')) {
                    return;
                }
                var self = this;
                var dock = self.$().find('.dock')[0];
                expanded = false;
                move.oldtime = false;
                move.proccess = false;

                this.set('needDockMinimize', false);

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

            didInsertElement: function() {
                if (Modernizr.touch) {
                    return;
                }
                var self = this;
                var $el = self.$();
                var $dock = self.$().closest('.dock');
                var $body = $(document.body);
                var $empty = $('<li class="umi-dock-button-empty">');

                $el.children('a').on('mousedown.umi.dock.button', function(e) {
                    e.preventDefault();
                    var cursorPosition = e.pageX;
                    var elPosition = $el.position().left;
                    var elStartPosition = elPosition;
                    var elIndex = 0;
                    var isMoved = false;

                    $body.on('mousemove.sort.umi.dock', function(e) {
                        elPosition = elPosition + e.pageX - cursorPosition;

                        if (!isMoved) {
                            self.set('parentView.isBlocked', true);
                            $el.addClass('umi-dock-button-dragging').after($empty);
                            $dock.addClass('sorting');
                            isMoved = true;
                        }

                        var tmpIndex = Math.round((elPosition - elStartPosition) / 120); // 120 - width of the element in the dock
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

                        $el.css({left: elPosition});
                        cursorPosition = e.pageX;

                    }).on('mouseup.sort.umi.dock', function() {
                        $body.off('.sort.umi.dock');

                        if (!isMoved) {
                            return;
                        }

                        $empty.after($el.removeClass('umi-dock-button-dragging').css({left: ''})).remove();

                        self.set('parentView.isBlocked', false);
                        self.get('parentView').leaveDock();

                        var mass = [];
                        $dock.removeClass('sorting').children('li').each(function() {
                            mass.push($(this).data('name'));
                        });
                        UMI.Utils.LS.set('dock', mass);
                    });
                });
            }
        });
    };
});
