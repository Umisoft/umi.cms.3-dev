define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.LinkToObjectElementView = Ember.View.extend({
            templateName: 'partials/LinkToObjectElement',
            classNames: ['row', 'collapse']
        });
    };
});