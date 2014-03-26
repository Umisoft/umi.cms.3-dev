define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.InputValidate = Ember.Mixin.create({
            validator: null,
            focusOut: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('templateData.keywords.object');
                    object.filterProperty(this.get('dataSource'));
                    object.validateProperty(this.get('dataSource'));
                }
            },
            focusIn: function(){
                if(this.get('validator') === 'collection'){
                    this.get('templateData.keywords.object').clearValidateForProperty(this.get('dataSource'));
                }
            }
        });
    };
});
