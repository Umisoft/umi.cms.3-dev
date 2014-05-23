define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.InputValidate = Ember.Mixin.create({
            validator: null,

            focusOut: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('templateData.view.object');
                    object.filterProperty(this.get('dataSource'));
                    object.validateProperty(this.get('dataSource'));
                }
            },

            focusIn: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('templateData.view.object');
                    object.clearValidateForProperty(this.get('dataSource'));
                }
            },

            valueObject: function(){
                return this.get('object.' + this.get("meta.dataSource"));
            }.property('object', 'meta.dataSource'),

            changeValueObject: function(){
                this.get('object').set(this.get('meta.dataSource'), this.get('valueObject'));
            }.observes('valueObject')
        });
    };
});