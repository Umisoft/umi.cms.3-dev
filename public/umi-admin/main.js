require.config({
    paths: {
        text: 'vendor/requirejs-text/text',

        App: 'application/application',
        jquery: 'vendor/jquery/dist/jquery',
        jqueryUI: 'vendor/jquery-ui/jquery-ui',
        Modernizr: 'vendor/modernizr/modernizr',
        Handlebars: 'vendor/handlebars/handlebars',
        Ember: 'vendor/ember/ember',
        DS: 'vendor/ember-data/ember-data',
        timepicker: 'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon',
        moment: 'vendor/momentjs/min/moment-with-langs',
        FastClick: 'vendor/fastclick/lib/fastclick',
        iscroll: 'library/iScroll/iscroll-probe-5.1.1',
        ckEditor: 'library/ckeditor/ckeditor',
        elFinder: 'library/elFinder/elFinder',
        Foundation: 'library/foundation/foundation'
    },

    shim: {
        Modernizr: {exports: 'Modernizr'},
        jquery: {exports: 'jQuery'},
        jqueryUI: {exports: 'jQuery', deps: ['jquery']},
        elFinder: {exports: 'elFinder', deps: ['jquery', 'jqueryUI']},
        Ember: {exports: 'Ember', deps: ['Handlebars', 'jquery']},
        DS: {exports: 'DS', deps: ['Ember']},
        ckEditor: {exports: 'ckEditor'},
        timepicker: {exports: 'timepicker', deps: ['jquery', 'jqueryUI']},
        Foundation: {exports: 'Foundation', deps: ['jquery', 'FastClick']}
    },

    packages: [
        {name: 'accordion', location: 'partials/accordion'},
        {name: 'dialog', location: 'partials/dialog'},
        {name: 'divider', location: 'partials/divider'},
        {name: 'dock', location: 'partials/dock'},
        {name: 'fileManager', location: 'partials/fileManager'},
        {name: 'forms', location: 'partials/forms'},
        {name: 'notification', location: 'partials/notification'},
        {name: 'popup', location: 'partials/popup'},
        {name: 'search', location: 'partials/search'},
        {name: 'megaIndex', location: 'partials/seo/megaIndex'},
        {name: 'sideMenu', location: 'partials/sideMenu'},
        {name: 'yandexWebmaster', location: 'partials/seo/yandexWebmaster'},
        {name: 'table', location: 'partials/table'},
        {name: 'tableControl', location: 'partials/tableControl'},
        {name: 'toolbar', location: 'partials/toolbar'},
        {name: 'topBar', location: 'partials/topBar'},
        {name: 'tree', location: 'partials/tree'},
        {name: 'treeSimple', location: 'partials/treeSimple'},
        {name: 'updateLayout', location: 'partials/updateLayout'}
    ]
});

require(['jquery'], function() {
    'use strict';

    var deffer = $.get(window.UmiSettings.authUrl);

    deffer.done(function(data) {
        var objectMerge = function(objectBase, objectProperty) {
            for (var key in objectProperty) {
                if (objectProperty.hasOwnProperty(key)) {
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        if (data.result) {
            objectMerge(window.UmiSettings, data.result.auth);
        }
        require(['application/main'], function(application) {
            application();
        });
    });

    deffer.fail(function(error) {
        require(['auth/main'], function(auth) {
            auth({accessError: error});
        });
    });
});
