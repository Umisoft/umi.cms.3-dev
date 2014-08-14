define(['App'], function(UMI) {
        'use strict';

        return function() {
            /**
             * Mixin реализующий интерфейс действия по умолчанию для split button.
             */
            UMI.SplitButtonDefaultBehaviour = Ember.Mixin.create({
                pathInLocalStorage: function() {
                    var meta = this.get('meta') || {behaviour: {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name;
                }.property(),

                defaultBehaviourIndex: null,

                defaultBehaviour: function() {
                    var index = this.get('defaultBehaviourIndex');
                    var choices = this.get('meta.behaviour.choices') || [];
                    if (choices[index]) {
                        return choices[index];
                    } else if (index > 0) {
                        this.set('defaultBehaviourIndex', 0);
                    }
                    return choices[0];
                }.property('defaultBehaviourIndex'),

                defaultBehaviourIcon: function() {
                    if (this.get('defaultBehaviour')) {
                        return 'icon-' + this.get('defaultBehaviour.behaviour.name');
                    }
                }.property('defaultBehaviour'),

                actions: {
                    toggleDefaultBehaviour: function(index) {
                        if (this.get('defaultBehaviourIndex') !== index) {
                            this.set('defaultBehaviourIndex', index);
                            UMI.Utils.LS.set(this.get('pathInLocalStorage'), index);
                        }
                    }
                },

                init: function() {
                    this._super();
                    this.set('defaultBehaviourIndex', UMI.Utils.LS.get(this.get('pathInLocalStorage')) || 0);
                }
            });

            UMI.SplitButtonDefaultBehaviourForComponent = Ember.Mixin.create({
                pathInLocalStorage: function() {
                    var componentName = this.get('controller.componentName');
                    var meta = this.get('meta') || {behaviour: {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name + '.' + componentName;
                }.property('controller.componentName')
            });

            var containerSettings = Ember.Object.create({
                defaultBehaviourIndex: 0
            });

            UMI.SplitButtonSharedSettingsBehaviour = Ember.Mixin.create({
                containerSettings: containerSettings,

                defaultBehaviourIndex: Ember.computed.alias('containerSettings.defaultBehaviourIndex')
            });

            /**
             * элемент списка
             * @type {Class}
             */
            var ListItemView = Ember.View.extend({
                tagName: 'li',

                classNames: ['has-default-button'],

                label: function() {
                    return this.get('context.attributes.label');
                }.property('context.attributes.label'),

                icon: function() {
                    return 'icon-' + this.get('context.behaviour.name');
                }.property('context.behaviour.name'),

                isDefaultBehaviour: function() {
                    var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                    return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                }.property('parentView.defaultBehaviourIndex')
            });

            UMI.SplitButtonView = Ember.View.extend(UMI.SplitButtonDefaultBehaviour, {
                templateName: 'partials/splitButton',

                dropdownId: function() {
                    return Foundation.utils.random_str();
                }.property(),

                actions: {
                    /**
                     * @method sendActionForBehaviour
                     * @param behaviour
                     */
                    sendActionForBehaviour: function(behaviour) {
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                },

                _button: {
                    classNameBindings: ['meta.attributes.class'],

                    attributeBindings: ['meta.attributes.title'],

                    dataOptions: function() {
                        return 'align: right;';
                    }.property(),

                    label: function() {
                        return this.get('defaultBehaviour.attributes.label');
                    }.property('defaultBehaviour.attributes.label'),

                    title: Ember.computed.alias('meta.attributes.title'),

                    click: function(event) {
                        if ($(event.target).data('dropdown') === undefined) {
                            this.get('parentView').send('sendActionForBehaviour',
                                this.get('defaultBehaviour').behaviour);
                        }
                    }
                },

                extendButton: {},

                buttonView: function() {
                    var buttonView = Ember.View.extend(this.get('_button'));
                    buttonView.reopen(this.get('extendButton'));
                    return buttonView;
                }.property(),

                itemView: ListItemView
            });

            function SplitButtonBehaviour() {}

            SplitButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);

            SplitButtonBehaviour.prototype.contextMenu = {
                itemView: function() {
                    var baseItem = ListItemView.extend({
                        init: function() {
                            this._super();
                            var context = this.get('context');

                            if (Ember.get(context, 'behaviour.name') === 'switchActivity') {
                                this.reopen({
                                    label: function() {
                                        if (this.get('controller.object.active')) {
                                            return this.get('context.attributes.states.deactivate.label');
                                        } else {
                                            return this.get('context.attributes.states.activate.label');
                                        }
                                    }.property('context.attributes.label', 'controller.object.active')
                                });
                            }
                        }
                    });

                    return baseItem;
                }.property(),

                init: function() {
                    this._super();
                    var behaviour = {};
                    var i;
                    var action;
                    var choices = this.get('context.behaviour.choices');

                    if (Ember.typeOf(choices) === 'array') {
                        for (i = 0; i < choices.length; i++) {
                            action = '';
                            var behaviourAction = Ember.get(UMI.splitButtonBehaviour, choices[i].behaviour.name);
                            if (behaviourAction) {
                                action = behaviourAction.actions[choices[i].behaviour.name];
                                if (action) {
                                    if (Ember.typeOf(behaviour.actions) !== 'object') {
                                        behaviour.actions = {};
                                    }
                                    behaviour.actions[choices[i].behaviour.name] = action;
                                }
                            }
                        }
                    }

                    this.reopen(behaviour);
                }
            };

            UMI.splitButtonBehaviour = new SplitButtonBehaviour();
        };
    });