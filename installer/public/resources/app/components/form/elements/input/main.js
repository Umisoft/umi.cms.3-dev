define(['App'], function(UMI){
    "use strict";

    Ember.TextField.reopen({
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
});
