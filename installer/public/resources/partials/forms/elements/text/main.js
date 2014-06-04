define(['App', 'text!./textElement.hbs'], function(UMI, textElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/text-element'] = Ember.Handlebars.compile(textElement);

    return function(){
        UMI.TextElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-text'],

            inputView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});