require.config({
    baseUrl: '/resources',

    paths: {
        text:       'libs/requirejs-text/text',

        App:        'application/application',
        jquery:     'libs/jquery/dist/jquery',
        jqueryUI:   'libs/jquery-ui/jquery-ui.min',
        Modernizr:  'libs/modernizr/modernizr',
        Handlebars: 'libs/handlebars/handlebars',
        Ember:      'libs/ember/ember',
        DS:         'libs/ember-data/ember-data',
        iscroll:    'libsStatic/iscroll-probe-5.1.1',
        ckEditor:   'libs/ckeditor/ckeditor',
        timepicker: 'libs/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon',
        moment:     'libs/momentjs/min/moment-with-langs.min',
        elFinder:   'libsStatic/elFinder'
    },

    shim: {
        Modernizr:  {exports: 'Modernizr'},
        jquery:     {exports: 'jQuery'},
        jqueryUI:   {exports: 'jQuery', deps: ['jquery']},
        elFinder:   {exports: 'elFinder',   deps: ['jquery', 'jqueryUI']},
        Ember:      {exports: 'Ember',      deps: ['Handlebars', 'jquery']},
        DS:         {exports: 'DS',         deps: ['Ember']},
        ckEditor:   {exports: 'ckEditor'},
        timepicker: {exports: 'timepicker', deps: ['jquery', 'jqueryUI']}
    },

    packages: [
        {name: 'accordion',         location: "partials/accordion"},
        {name: 'chartControl',      location: "partials/chartControl"},
        {name: 'dialog',            location: "partials/dialog"},
        {name: 'divider',            location: "partials/divider"},
        {name: 'dock',              location: "partials/dock"},
        {name: 'fileManager',       location: "partials/fileManager"},
        {name: 'forms',              location: "partials/forms"},
        {name: 'notification',      location: "partials/notification"},
        {name: 'popup',             location: "partials/popup"},
        {name: 'search',            location: "partials/search"},
        {name: 'megaIndex',         location: "partials/seo/megaIndex"},
        {name: 'sideMenu',          location: "partials/sideMenu"},
        {name: 'yandexWebmaster',   location: "partials/seo/yandexWebmaster"},
        {name: 'table',             location: "partials/table"},
        {name: 'tableControl',      location: "partials/tableControl"},
        {name: 'toolbar',           location: "partials/toolbar"},
        {name: 'topBar',            location: "partials/topBar"},
        {name: 'tree',              location: "partials/tree"},
        {name: 'treeSimple',        location: "partials/treeSimple"}
    ]
});

require(['jquery'], function(){
    "use strict";

    var deffer = $.get(window.UmiSettings.authUrl);

    deffer.done(function(data){
        var objectMerge = function(objectBase, objectProperty){
            for(var key in objectProperty){
                if(objectProperty.hasOwnProperty(key)){
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        if(data.result){
            objectMerge(window.UmiSettings, data.result.auth);
        }
        require(['application/main'], function(application){
            application();
        });
    });

    deffer.fail(function(error){
        require(['auth/main'], function(auth){
            auth({accessError: error});
        });
    });
});
