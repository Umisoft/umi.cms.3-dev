define(['App'], function(UMI){
    "use strict";

    return function(){
        Ember.TextField.reopen(UMI.InputValidate);
    };
});