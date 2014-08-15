define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TextElementView = Ember.View.extend(UMI.InputValidate, {
            type: 'text',

            classNames: ['umi-element-text'],

            template: function() {
                var self = this;
                var template;
                var dataSource;
                var inputTemplate;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.set('validatorType', 'collection');
                    dataSource = this.get('meta.dataSource');
                    inputTemplate = '{{input type=view.type value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                } else {
                    this.set('validatorType', null);
                    inputTemplate = '{{input type=view.type value=view.meta.value name=view.meta.attributes.name}}';
                }

                var validate = this.validateErrorsTemplate();
                template = inputTemplate + validate;

                return Ember.Handlebars.compile(template);
            }.property()
        });
    };
});