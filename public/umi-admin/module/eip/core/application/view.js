define([], function() {
    "use strict";
    return function(namespace) {
        namespace.ApplicationView = Ember.View.extend({
            template: Ember.Handlebars.compile('{{view "topbar"}}')
        });
    };
});