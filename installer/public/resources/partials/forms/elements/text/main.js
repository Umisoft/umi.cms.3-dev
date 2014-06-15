define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TextElementView = Ember.View.extend(UMI.InputValidate, {
            type: "text",
            classNames: ['umi-element-text'],
            template: function(){
                var template;
                if(Ember.typeOf(this.get('object')) === 'instance'){
                    this.set('validator', 'collection');
                    var dataSource = this.get('meta.dataSource');
                    var input = '{{input type=view.type value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                    var validate = this.validateErrorsTemplate();
                    template = input + validate;
                } else{
                    template = '{{input type=view.text value=view.meta.attributes.value name=view.meta.attributes.name}}';
                }
                return Ember.Handlebars.compile(template);
            }.property()
        });
    };
});