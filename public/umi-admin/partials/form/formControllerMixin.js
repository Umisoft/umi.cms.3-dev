define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormControllerMixin = Ember.Mixin.create(UMI.i18nInterface, {
                dictionaryNamespace: 'form',

                localDictionary: function() {
                    var form = this.get('control') || {};
                    return form.i18n;
                }.property(),

                InvalidElements: Ember.Set.create([]),

                needsValidateForm: false,

                validateForm: function() {
                    this.toggleProperty('needsValidateForm');
                    return this.get('InvalidElements.length');
                }
            });
        };
    }
);