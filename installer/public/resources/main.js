require.config({
    baseUrl: '/resources',

    paths: {
        Modernizr: 'libs/modernizr/modernizr',
        text: 'libs/requirejs-text/text',
        jQuery: 'libs/jquery/jquery',
        mouseWheel: 'libs/jquery-mousewheel/jquery.mousewheel',
        iscroll: 'libs/iscroll-probe-5.1.1',
        Handlebars: 'libs/handlebars/handlebars',
        Ember: 'libs/ember/ember',
        DS: 'libs/ember-data/ember-data',
        Foundation: 'deploy/foundation',
        ckEditor: 'libs/ckeditor/ckeditor',
        datepicker: 'libs/datepicker',
        moment: 'libs/momentjs/min/moment-with-langs.min',
        App: 'app/components/skeleton/main',
        //jQueryUI: 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min'
        elFinder: 'deploy/elFinder',
        chartJs: 'libs/chartjs/Chart'
    },

    shim: {
        Modernizr: {
            exports: 'Modernizr'
        },

        jQuery: {
            exports: 'jQuery'
        },

        //Требует elFinder
        /*jQueryUI: {
            deps: ['jQuery'],
            exports: 'jQueryUI'
        },*/

        elFinder: {
            deps: ['jQuery'],
            exports: 'elFinder'
        },

        Ember: {
            deps: ['Handlebars', 'jQuery'],
            exports: 'Ember'
        },

        Foundation: {
            deps: ['jQuery'],
            exports: 'Foundation'
        },

        DS: {
            deps: ['Ember'],
            exports: 'DS'
        },

        ckEditor: {
            exports: 'ckEditor'
        },

        datepicker: {
            deps: ['jQuery'],
            exports: 'datepicker'
        },

        chartJs: {
            exports: 'chartJs'
        }
    },

    packages: [
        {
            name: 'topBar',
            location: "app/components/topBar"
        },
        {
            name: 'dock',
            location: "app/components/dock"
        },
        {
            name: 'tableControl',
            location: "app/components/tableControl"
        },
        {
            name: 'table', //Аналог tableControl получающий данные по AJAX
            location: "app/components/table"
        },
        {
            name: 'tree',
            location: "app/components/tree"
        },
        {
            name: 'accordion',
            location: "app/components/accordion"
        },
        {
            name: 'form',
            location: "app/components/form"
        },
        {
            name: 'fileManager',
            location: "app/components/fileManager"
        },
        {
            name: 'search',
            location: "app/components/search"
        },
        {
            name: 'chartControl',
            location: "app/components/chartControl"
        },
        {
            name: 'popup',
            location: "app/components/popup"
        },
        {
            name: 'notification',
            location: "app/components/notification"
        },
        {
            name: 'dialog',
            location: "app/components/dialog"
        }
    ]
});


if(UmiSettings.authenticated){
    require(['app/main', 'DS', 'Modernizr', 'Foundation', 'iscroll', 'ckEditor', 'elFinder', 'datepicker', 'moment', 'chartJs'], function(application){
        "use strict";
        application();
    });
} else{
    require(['auth/main'], function(auth){
        "use strict";
        auth();
    });
}