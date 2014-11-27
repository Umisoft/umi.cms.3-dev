define(['App'], function(UMI) {
    'use strict';
    return function() {
        UMI.TopBarModulesListController = Ember.ObjectController.extend({
            needs: 'dock',

            modulesBinding: 'controllers.dock._modules',

            reorder: function(newOrder, showName) {
                var dockController = this.get('controllers.dock');
                dockController.showModule(showName);
                dockController.resortModules(newOrder);
            }
        });

        UMI.TopBarModulesListView = Ember.View.extend({
            templateName: 'partials/topBarModulesList',

            didInsertElement: function() {
                var self = this;
                var $body = $('body');
                var iconSize = 30; // Ширина и высота иконки

                self.$().on('mousedown.umi.modulesList', '.umi-top-bar-dropdown-modules-item', function(e) {
                    e.preventDefault();
                    var $el = $(this);
                    var dragging = false;
                    var name = $el.data().name;
                    var $dock = $('.dock');
                    var isInDock = $el.hasClass('inDock');
                    var emptyPosition;
                    var emptyWidth;
                    var emptyHeight;
                    var needAdding = false;
                    var $icon;
                    var $empty;

                    $body.on('mousemove.umi.modulesList', function(e) {
                        if (isInDock) {
                            $el.parent().addClass('noDragging');

                        } else {
                            if (!dragging) {
                                var $firstLi = $dock.find('li:first');
                                emptyWidth = $firstLi.width();
                                emptyHeight = $firstLi.height();

                                $empty = $('<li class="umi-dock-button-empty button-placeholder" data-name="' +
                                name + '">');
                                $empty.width(emptyWidth).height(emptyHeight);

                                $dock.addClass('sorting');
                                $firstLi.before($empty);
                                emptyPosition = $empty.offset();

                                $icon = $el.find('i').clone().addClass('dragging').appendTo($body);
                                dragging = true;
                            }

                            $icon.css({left: e.pageX - iconSize * 0.8, top: e.pageY - iconSize * 0.8});

                            var shift = Math.round(e.pageX - emptyPosition.left - emptyWidth / 2 - iconSize * 0.7);
                            var $nearElement = null;
                            if (shift > 0) {
                                $nearElement = $empty.nextAll('li:not(.umi-dock-button-dragging):first');
                            }
                            if (shift < 0) {
                                $nearElement = $empty.prevAll('li:not(.umi-dock-button-dragging):first');
                            }
                            if ($nearElement && $nearElement.length) {
                                if (Math.abs(shift) > emptyWidth) {
                                    if (shift > 0) {
                                        $nearElement.after($empty);
                                    }
                                    else {
                                        $nearElement.before($empty);
                                    }
                                    emptyPosition = $empty.offset();
                                }
                            }

                            var newX = e.pageX - iconSize * 0.3;
                            var newY = e.pageY - iconSize * 0.3;
                            if (newX > emptyPosition.left && newX < emptyPosition.left + emptyWidth &&
                                newY > emptyPosition.top && newY < emptyPosition.top + emptyHeight) {
                                $empty.addClass('hovered');
                                needAdding = true;
                            } else {
                                $empty.removeClass('hovered');
                                needAdding = false;
                            }
                        }
                    }).on('mouseup.umi.modulesList', function() {
                        $body.off('.umi.modulesList');
                        if (isInDock) {
                            $el.parent().removeClass('noDragging');

                        } else {
                            if (needAdding) {
                                var newOrder = [];

                                $dock.children('li').each(function() {
                                    newOrder.push($(this).data().name);
                                });

                                UMI.Utils.LS.set('dock.sortedOrder', newOrder);

                                var hided = UMI.Utils.LS.get('dock.hiddenModules');

                                hided.splice(hided.indexOf(name), 1);
                                UMI.Utils.LS.set('dock.hiddenModules', hided);

                                self.get('controller').reorder(newOrder, name);
                            } else {
                                $empty.remove();
                            }
                            $icon.remove();
                            $dock.removeClass('sorting');
                            dragging = false;
                        }
                    });
                });
            }
        });
    };
});
