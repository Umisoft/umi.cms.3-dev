define(['App', 'text!./template.hbs'],
    function(UMI, template){
        "use strict";

        return function(){
            UMI.DropDownButtonView = Ember.View.extend({
                template: Ember.Handlebars.compile(template),
                tagName: 'a',
                classNameBindings: 'button.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('button.attributes.title'),
                didInsertElement: function(){
                    this.$().click(function(){
                        $(this).find('.umi-toolbar-create-list').toggle();
                    });
                }
            });
        };
    }
);