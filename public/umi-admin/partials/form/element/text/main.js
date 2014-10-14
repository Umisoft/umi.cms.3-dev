define(['App', './slugCollectionElement'], function(UMI, slugCollectionElement) {
    'use strict';

    return function() {
        UMI.FormTextElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "textElement" object=view.object meta=view.meta}}')
        });

        UMI.TextElementView = Ember.View.extend(UMI.FormElementValidatable, {
            type: 'text',

            classNames: ['umi-element-text'],

            focusOut: function() {
                this.checkValidate();
            },

            focusIn: function() {
                this.clearValidate();
            },

            template: function() {
                var self = this;
                var template;
                var dataSource;
                var inputTemplate;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.set('validatorType', 'collection');
                    dataSource = this.get('meta.dataSource');
                    inputTemplate = '{{input typeBinding="view.type" value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                } else {
                    this.set('validatorType', null);
                    inputTemplate = '{{input typeBinding="view.type" value=view.meta.value name=view.meta.attributes.name}}';
                }

                var validate = this.validateErrorsTemplate();
                template = inputTemplate + validate;

                return Ember.Handlebars.compile(template);
            }.property()
        });

        slugCollectionElement();
    };
});