define(['App', 'text!./button.hbs'],
    function(UMI, buttonTemplate){
        "use strict";

        return function(){
            UMI.ButtonView = Ember.View.extend({
                template: Ember.Handlebars.compile(buttonTemplate),
                tagName: 'a',
                classNames: ['s-margin-clear'],//TODO: избавиться от класса после возвращения Foundation
                classNameBindings: 'meta.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
                click: function(){
                    this.get('controller').send('sendAction', this.get('meta').behaviour);
                }
            });

            UMI.buttonBehaviour = Ember.Object.create({
                switchActivity: {
                    classNameBindings: ['controller.object.active::umi-disabled']
                }
            });

            //TODO: Вынести нижеследующие компоненты в отдельные файлы
            UMI.FormControlDropUpView = Ember.View.extend({
                classNames: ['dropdown', 'coupled'],
                classNameBindings: ['isOpen:open'],
                isOpen: false,
                iScroll: null,
                actions: {
                    open: function(){
                        var self = this;
                        var el = this.$();
                        this.toggleProperty('isOpen');
                        if(this.get('isOpen')){
                            setTimeout(function(){
                                $('body').on('click.umi.form.controlDropUp', function(event){
                                    var targetElement = $(event.target).closest('.umi-dropup');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                        $('body').off('.umi.form.controlDropUp');
                                        self.set('isOpen', false);
                                    }
                                });
                                if(self.get('iScroll')){
                                    self.get('iScroll').refresh();
                                }
                            }, 0);
                        }
                    }
                },
                didInsertElement: function(){
                    var el = this.$();
                    var scroll;
                    var scrollElement = el.find('.s-scroll-wrap');
                    if(scrollElement.length){
                        scroll = new IScroll(scrollElement[0], UMI.config.iScroll);
                    }
                    this.set('iScroll', scroll);
                }
            });
        };
    }
);