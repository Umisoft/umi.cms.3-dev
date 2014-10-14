define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSubmitElementMixin = Ember.Mixin.create({
                layout: Ember.Handlebars.compile('<span class="button right" ' +
                '{{action "submit" target="view"}}>{{view.meta.label}}</span>'),

                actions: {
                    submit: function() {
                        this.get('parentView').send('submit', this.$());
                    }
                }
            });
        };
    }
);