define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TextElementView = Ember.View.extend({
            type: 'text',

            classNames: ['umi-element-text'],

            template: function() {
                var self = this;
                var dataSource;
                var inputTemplate;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    dataSource = this.get('meta.dataSource');
                    inputTemplate = '{{input typeBinding="view.type" value=view.object.' + dataSource + ' placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                } else {
                    inputTemplate = '{{input typeBinding="view.type" value=view.meta.value name=view.meta.attributes.name}}';
                }

                return Ember.Handlebars.compile(inputTemplate);
            }.property()
        });

        UMI.FormTextElementMixin = Ember.Mixin.create(UMI.FormElementMixin, UMI.FormElementValidateMixin, {
            classNames: ['small-12', 'large-4'],

            elementView: UMI.TextElementView.extend(UMI.FormElementValidateHandlerMixin, {})
        });
    };
});