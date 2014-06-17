define(['App', 'text!./template.hbs'], function(UMI, template){
    "use strict";

    return function(){
        UMI.SiblingsNavigationView = Ember.View.extend({
            classNames: ['umi-table-control-pagination'],

            template: Ember.Handlebars.compile(template),

            counter: function(){
                var label = 'из';
                var index = 1;
                var total = 100;
                return index + ' ' + label + ' ' + total;
            }.property(),

            prevButtonView: Ember.View.extend({
                classNames: ['button', 'secondary', 'tiny'],
                classNameBindings: ['isActive::disabled'],

                isActive: function(){
                    return this.get('controller.offset');
                }.property('controller.offset'),

                click: function(){
                    if(this.get('isActive')){
                        this.get('controller').decrementProperty('offset');
                    }
                }
            }),

            nextButtonView: Ember.View.extend({
                classNames: ['button', 'secondary', 'tiny'],
                classNameBindings: ['isActive::disabled'],

                isActive: function(){
                    var limit = this.get('controller.limit');
                    var offset = this.get('controller.offset') + 1;
                    var total = this.get('controller.total');
                    return total > limit * offset;
                }.property('controller.limit', 'controller.offset', 'controller.total'),

                click: function(){
                    if(this.get('isActive')){
                        this.get('controller').incrementProperty('offset');
                    }
                }
            })
        });
    };
});