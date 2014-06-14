define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TextElementView = Ember.View.extend({
            classNames: ['umi-element-text'],
            template: function(){
                return Ember.Handlebars.compile('{{input type="text" value=view.meta.attributes.value name=view.meta.attributes.name}}');
            }.property()
        });

        UMI.TextCollectionElementView = UMI.TextElementView.extend(UMI.InputValidate, {
            template: function(){
                var dataSource = this.get('meta.dataSource');
                var input = '{{input type="text" value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                var validate = this.validateErrorsTemplate();
                return Ember.Handlebars.compile(input + validate);
            }.property()
        });
    };
});