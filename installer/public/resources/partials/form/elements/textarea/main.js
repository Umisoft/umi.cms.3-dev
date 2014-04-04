define(['App'], function(UMI){
    "use strict";

    return function(){
        Ember.TextArea.reopen(UMI.InputValidate);
    };
});