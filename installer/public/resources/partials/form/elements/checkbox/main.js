define(['App', 'text!./checkboxElement.hbs'], function(UMI, checkboxElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/checkbox-element'] = Ember.Handlebars.compile(checkboxElement);

    return function(){
        UMI.CheckboxElementComponent = Ember.Component.extend({
            classNames: ['umi-element-checkbox'],

            inputId: function(){
                //console.log('meta', this.get('meta'));
                return 'input-' + this.get('elementId');
            }.property()
//
//            actions: {
//                change: function(){
//                    var value = this.toggleProperty('checked');
//                    // Bugfix. В выпадающем списке в котором есть iScroll чекбокс не изменяет свое состояние.
//                    var el = this.$().children('input')[0];
//                    setTimeout(function(){
//                        el.checked = value;
//                    }, 0);
//                }
//            }
        });
    };
});