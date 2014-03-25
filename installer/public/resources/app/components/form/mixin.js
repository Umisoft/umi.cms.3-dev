define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.InputValidate = Ember.Mixin.create({
            validator: null,
            focusOut: function(){
                if(this.get('validator') === 'collection'){
                    this.get('templateData.keywords.object').validateProperty(this.get('dataSource'));
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
