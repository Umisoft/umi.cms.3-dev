define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSubmitElementMixin = Ember.Mixin.create({
                layout: Ember.Handlebars.compile('<span class="button right" ' +
                '{{action "submit" target="view.parentView"}}>{{view.meta.label}}</span>')
            });
        };
    }
);