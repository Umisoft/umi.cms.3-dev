define(['Ember', 'module/eip/core/router/applicationRoute'], function(Ember, applicationRoute) {
    'use strict';

    return function(appNamespace) {
        appNamespace.Router.reopen({
            location: 'none'
        });

        applicationRoute(appNamespace);
    };
});
