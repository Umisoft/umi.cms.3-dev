define(['App'], function(UMI) {
    'use strict';

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
                var buttonGroupUnlabeledWidth = 20;

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
                        buttonGroupUnlabeledWidth = $buttonGroup.width() + nextElementsWidth + 20;
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

                var togglePagination = function(needShow) {
                    if (needShow) {
                        $nextElements.removeClass('umi-hide-pagination-label');
                    } else {
                        $nextElements.addClass('umi-hide-pagination-label');
                    }
                };

                if ($buttonGroup.length) {
                    if (buttonGroupWidth >= $el.width()) {
                        toggleLabel();
                        if (buttonGroupUnlabeledWidth >= $el.width()) {
                            togglePagination();
                        } else {
                            togglePagination(true);
                        }
                    }
                }
                $(window).on('resize.umi.toolbar dividerResize.umi.toolbar', function() {
                    if (buttonGroupWidth >= $el.width()) {
                        toggleLabel();
                        if (buttonGroupUnlabeledWidth >= $el.width()) {
                            togglePagination();
                        } else {
                            togglePagination(true);
                        }
                    } else {
                        toggleLabel(true);
                    }
                });
            },
            willDestroyElement: function() {
                $(window).off('.umi.toolbar');
            }
        });
    };
});