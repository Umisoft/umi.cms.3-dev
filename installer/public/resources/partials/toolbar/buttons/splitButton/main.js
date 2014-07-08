define(['App'],
    function(UMI){
        "use strict";

        return function(){
            /**
             * Mixin реализующий интерфейс действия по умолчанию для split button.
             */
            UMI.SplitButtonDefaultBehaviour = Ember.Mixin.create({
                pathInLocalStorage: function(){
                    var meta = this.get('meta') || {behaviour : {}};
                    return 'layout.defaultBehaviour.' + meta.type + '.' + meta.behaviour.name;
                }.property(),

                defaultBehaviourIndex: 0,

                defaultBehaviour: function(){
                    var index = this.get('defaultBehaviourIndex');
                    var meta = this.get('meta') || {behaviour : {choices: []}};
                    if(meta.behaviour.choices[index]){
                        return meta.behaviour.choices[index];
                    } else{
                        this.set('defaultBehaviourIndex', 0);
                    }
                }.property('defaultBehaviourIndex'),

                defaultBehaviourIcon: function(){
                    if(this.get('defaultBehaviour')){
                        return 'icon-' + this.get('defaultBehaviour.behaviour.name');
                    }
                }.property('defaultBehaviour'),

                actions: {
                    toggleDefaultBehaviour: function(index){
                        if(this.get('defaultBehaviourIndex') !== index){
                            this.set('defaultBehaviourIndex', index);
                            UMI.Utils.LS.set(this.get('pathInLocalStorage'), index);
                        }
                    }
                },

                init: function(){
                    this._super();
                    this.set('defaultBehaviourIndex', UMI.Utils.LS.get(this.get('pathInLocalStorage')) || 0);
                }
            });

            UMI.SplitButtonDefaultBehaviourForComponent = Ember.Mixin.create({
                pathInLocalStorage: function(){
                    var componentName = this.get('controller.componentName');
                    var meta = this.get('meta') || {behaviour : {}};
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

            UMI.SplitButtonView = Ember.View.extend(UMI.SplitButtonDefaultBehaviour, {
                templateName: 'partials/splitButton',
                tagName: 'span',
                isOpen: false,
                classNameBindings: ['meta.attributes.class', 'isOpen:open'],
                attributeBindings: ['title'],
                label: function(){
                    return this.get('meta.attributes.label');
                }.property('meta.attributes.label'),
                title: Ember.computed.alias('meta.attributes.title'),
                click: function(event){
                    var el = this.$();
                    if(event.target.getAttribute('id') === el[0].getAttribute('id') || ( ($(event.target).hasClass('icon') || $(event.target).hasClass('button-label')) && event.target.parentElement.getAttribute('id') === el[0].getAttribute('id'))){
                        this.send('sendActionForBehaviour', this.get('defaultBehaviour').behaviour);
                    }
                },
                actions: {
                    open: function(event){
                        var self = this;
                        var $el = self.$();
                        setTimeout(function(){
                            self.toggleProperty('isOpen');

                            if(self.get('isOpen')){
                                // закрывает список в случае клика мимо списка
                                $('html').on('click.splitButton', function(event){
                                    if($el.children('.dropdown-toggler')[0] === event.target){
                                        return;
                                    }
                                    var targetElement = $(event.target).closest('.f-dropdown');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== $el[0].getAttribute('id')){
                                        $('html').off('click.splitButton');
                                        self.set('isOpen', false);
                                    }
                                });
                            }
                        }, 0);
                    },

                    /**
                     * @method sendActionForBehaviour
                     * @param behaviour
                     */
                    sendActionForBehaviour: function(behaviour){
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                },

                itemView: Ember.View.extend({
                    tagName: 'li',
                    label: function(){
                        return this.get('context.attributes.label');
                    }.property('context.attributes.label'),
                    isDefaultBehaviour: function(){
                        var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                        return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                    }.property('parentView.defaultBehaviourIndex')
                })
            });

            UMI.splitButtonBehaviour = UMI.GlobalBehaviour.extend({
                dropUp: {
                    classNames: ['split-dropup']
                },

                contextMenu: {
                    itemView: function(){
                        var baseItem = Ember.View.extend({
                            tagName: 'li',
                            label: function(){
                                return this.get('context.attributes.label');
                            }.property('context.attributes.label'),
                            isDefaultBehaviour: function(){
                                var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                                return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                            }.property('parentView.defaultBehaviourIndex'),
                            init: function(){
                                this._super();
                                var context = this.get('context');
                                if(Ember.get(context, 'behaviour.name') === 'switchActivity'){
                                    this.reopen({
                                        label: function(){
                                            if(this.get('controller.object.active')){
                                                return this.get('context.attributes.states.deactivate.label');
                                            } else{
                                                return this.get('context.attributes.states.activate.label');
                                            }
                                        }.property('context.attributes.label', 'controller.object.active')
                                    });
                                }
                            }
                        });
                        return baseItem;
                    }.property()
                }
            }).create({});
        };
    }
);