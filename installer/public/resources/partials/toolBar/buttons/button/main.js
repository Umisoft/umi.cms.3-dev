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
                    this.get('controller').send('sendActionForBehaviour', this.get('meta').behaviour);
                }
            });

            UMI.buttonBehaviour = Ember.Object.create({
                switchActivity: {
                    classNameBindings: ['controller.object.active::umi-disabled']
                }
            });
        };
    }
);