define(
    [
        'App', './controllers', './view', './partials/popup/main'
    ], function(UMI, controllers, view, popup) {
        'use strict';

        controllers();
        view();
        popup();
    }
);