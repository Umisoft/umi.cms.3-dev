define(['App', 'text!./splitButton.hbs'],
    function(UMI, splitButtonTemplate){
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

            UMI.SplitButtonView = Ember.View.extend(UMI.SplitButtonDefaultBehaviour, {
                template: Ember.Handlebars.compile(splitButtonTemplate),
                tagName: 'button',
                isOpen: false,
                classNames: ['s-margin-clear', 'dropdown'],//TODO: избавиться от класса после возвращения Foundation
                classNameBindings: ['meta.attributes.class', 'isOpen:open'],
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
                click: function(event){
                    var el = this.$();
                    if(event.target.getAttribute('id') === el[0].getAttribute('id') || ($(event.target).hasClass('icon') && event.target.getAttribute('id') === el[0].getAttribute('id'))){
                        this.get('controller').send('sendActionForBehaviour', this.get('defaultBehaviour').behaviour);
                    }
                },
                actions: {
                    open: function(){
                        var self = this;
                        var el = self.$();
                        setTimeout(function(){
                            self.toggleProperty('isOpen');
                            if(self.get('isOpen')){
                                $('html').on('click.splitButton', function(event){
                                    var targetElement = $(event.target).closest('.f-dropdown');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                        $('html').off('click.splitButton');
                                        self.set('isOpen', false);
                                    }
                                });
                            }
                        }, 0);
                    }
                },
                itemView: Ember.View.extend({
                    tagName: 'li',
                    isDefaultBehaviour: function(){
                        var defaultBehaviourIndex = this.get('parentView.defaultBehaviourIndex');
                        return defaultBehaviourIndex === this.get('_parentView.contentIndex');
                    }.property('parentView.defaultBehaviourIndex')
                })
            });

            UMI.splitButtonBehaviour = Ember.Object.create({});
        };
    }
);