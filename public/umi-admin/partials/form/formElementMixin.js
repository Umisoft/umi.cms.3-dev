define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {

            UMI.FormElementMixin = Ember.Mixin.create({
                classNames: ['umi-columns'],

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}' +
                '{{view.isRequired}}</span></div>{{yield}}')
            });
        };
    }
);