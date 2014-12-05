define(
    ['admin/application/i18n', 'module/eip/core/application/view', 'topbar'],
    function(i18n, applicationView, topbar) {
        'use strict';

        return function(namespace) {
            i18n(namespace);
            applicationView(namespace);
            topbar(namespace);
        };
    }
);