define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormCollectionElementValidateMixin = Ember.Mixin.create(UMI.FormElementValidateMixin, {
                validateErrors: function() {
                    var dataSource = this.get('meta.dataSource');
                    var isValid = !!this.get('object.validErrors.' + dataSource);
                    return isValid;
                }.property('object.validErrors.@each'),

                isRequired: function() {
                    var object = this.get('object');
                    var dataSource = this.get('meta.dataSource');
                    var validators;

                    if (object) {
                        validators = this.get('object').validatorsForProperty(dataSource);
                        if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                            return ' *';
                        }
                    }
                }.property('object')
            });
        };
    }
);