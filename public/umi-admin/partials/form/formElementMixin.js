define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {

            UMI.FormElementMixin = Ember.Mixin.create({
                classNames: ['umi-columns'],

                /**
                 * @property isRequired
                 * @hook
                 */
                isRequired: function() {
                    var validators = this.get('meta.validators');
                    if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                        return ' *';
                    }
                }.property('meta.validators'),

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}' +
                '{{view.isRequired}}</span></div>{{yield}}')
            });
        };
    }
);