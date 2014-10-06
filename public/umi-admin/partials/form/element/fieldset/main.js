define(
    ['App'],

    function(UMI) {
        'use strict';

        return function() {
            UMI.FormFieldsetElementMixin = Ember.Mixin.create(UMI.FormElementsMixin, {
                tagName: 'fieldset',

                classNameBindings: ['elementClass'],

                isExpanded: true,

                layout: Ember.Handlebars.compile('<legend {{action "expand" target="view"}} class="s-unselectable">' +
                    '<i {{bind-attr class=":icon view.isExpanded:icon-bottom:icon-right"}}></i>{{formElement.label}}' +
                    '</legend>{{yield}}'),

                template: Ember.Handlebars.compile('<div {{bind-attr class="view.isExpanded::hide"}}>' +
                    '{{#each formElement in view.meta.elements}}' +
                    '{{view view.elementView metaBinding="formElement" objectBinding="formElement"}}' +
                    '{{/each}}</div>'),

                elementClass: function() {
                    return 'umi-fieldset-' + this.get('meta.id');
                }.property('meta.id'),

                actions: {
                    expand: function() {
                        this.toggleProperty('isExpanded');
                    }
                }
            });

            UMI.FormFieldsetCollectionElementMixin = Ember.Mixin.create(UMI.FormFieldsetElementMixin,
                UMI.FormCollectionElementsMixin, {
                    template: Ember.Handlebars.compile('<div {{bind-attr class="view.isExpanded::hide"}}>' +
                    '{{#each formElement in view.meta.elements}}' +
                    '{{view view.elementView metaBinding="formElement" objectBinding="view.object"}}' +
                    '{{/each}}</div>')
                });
        };
    }
);
