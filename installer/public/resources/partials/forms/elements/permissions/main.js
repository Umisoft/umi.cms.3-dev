define(['App', 'text!./permissions.hbs'], function(UMI, permissionsTemplate){
    "use strict";

    return function(){
        UMI.PermissionsView = Ember.View.extend({
            template: Ember.Handlebars.compile(permissionsTemplate)
        });
    };
});