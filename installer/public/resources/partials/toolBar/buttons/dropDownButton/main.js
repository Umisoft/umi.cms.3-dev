define(['App', 'text!./template.hbs'],
    function(UMI, template){
        "use strict";

        return function(){
            UMI.DropDownButtonView = Ember.View.extend({
                template: Ember.Handlebars.compile(template),
                tagName: 'a',
                classNames: ['umi-button', 'umi-toolbar-create-button'],
                didInsertElement: function(){
                    this.$().click(function(){
                        $(this).find('.umi-toolbar-create-list').toggle();
                    });
                }
            });
        };
    }
);