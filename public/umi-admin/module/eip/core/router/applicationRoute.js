define([], function() {
    'use strict';

    return function(appNamespace) {
        appNamespace.Router.reopen({
            location: 'none'
        });
    };
});