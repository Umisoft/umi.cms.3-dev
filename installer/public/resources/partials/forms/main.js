define(
    ['App', './formBase/main', './formControl/main'],
    function(UMI, formBase, formControl){
        'use strict';

        formBase();
        formControl();

        //TODO: Вынести нижеследующие компоненты в отдельные файлы
        UMI.SaveButtonView = Ember.View.extend({
            tagName: 'button',

            classNames: ['s-margin-clear'],

            classNameBindings: ['model.isDirty::disabled', 'model.isValid::disabled'],

            click: function(event){
                if(this.get('model.isDirty') && this.get('model.isValid')){
                    var button = this.$();
                    var model = this.get('model');
                    button.addClass('loading');
                    var params = {
                        object: model,
                        handler: button[0]
                    };
                    this.get('controller').send('save', params);
                }
            }
        });

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
    }
);