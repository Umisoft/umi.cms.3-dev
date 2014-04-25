require.config({
    baseUrl: '/resources',

    paths: {
        text:       'libs/requirejs-text/text',

        App:        'application/application',

        Modernizr:  'libs/modernizr/modernizr',
        jQuery:     'libs/jquery/dist/jquery',
        jQueryUI:   'libs/jqueryui/ui/jquery-ui',
        Handlebars: 'libs/handlebars/handlebars',
        Ember:      'libs/ember/ember',
        DS:         'libs/ember-data/ember-data',

        iscroll:    'libs/iscroll-probe-5.1.1',
        ckEditor:   'libs/ckeditor/ckeditor',
        datepicker: 'libs/datepicker',
        timepicker: 'libs/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon',
        moment:     'libs/momentjs/min/moment-with-langs.min',
        elFinder:   'libs/elFinder',
        chartJs:    'libs/chartjs/Chart'
    },

    shim: {
        //Устанавливаем зависимости между библиотеками
        Modernizr:  {exports: 'Modernizr'},
        jQuery:     {exports: 'jQuery'},

        /*
        * jQueryUI
        * elfinder требует selectable, draggable, droppable
        * datetime требует datepicker, slider
        * */
        jQueryUI:   {exports: 'jQueryUI',   deps: ['jQuery']},
        elFinder:   {exports: 'elFinder',   deps: ['jQuery', 'jQueryUI']},
        Ember:      {exports: 'Ember',      deps: ['Handlebars', 'jQuery']},
        DS:         {exports: 'DS',         deps: ['Ember']},
        ckEditor:   {exports: 'ckEditor'},
        datepicker: {exports: 'datepicker', deps: ['jQuery']},
        timepicker: {exports: 'timepicker', deps: ['jQuery', 'jQueryUI']},
        chartJs:    {exports: 'chartJs'}
    },

    packages: [
        //Подключаем Partials. замена следуют по алфавиту, как и в структуре папок
        {name: 'accordion',         location: "partials/accordion"},
        {name: 'chartControl',      location: "partials/chartControl"},
        {name: 'dialog',            location: "partials/dialog"},
        {name: 'dock',              location: "partials/dock"},
        {name: 'fileManager',       location: "partials/fileManager"},
        {name: 'form',              location: "partials/form"},
        {name: 'notification',      location: "partials/notification"},
        {name: 'popup',             location: "partials/popup"},
        {name: 'search',            location: "partials/search"},
        {name: 'megaIndex',         location: "partials/seo/megaIndex"},
        {name: 'yandexWebmaster',   location: "partials/seo/yandexWebmaster"},
        //skeleton                  partials/skeleton
        {name: 'table',             location: "partials/table"},
        {name: 'tableControl',      location: "partials/tableControl"},
        {name: 'topBar',            location: "partials/topBar"},
        {name: 'tree',              location: "partials/tree"},
        {name: 'treeSimple',        location: "partials/treeSimple"}
    ]
});


if(UmiSettings.authenticated){
    require(['application/main'], function(application){
        "use strict";
        application();
    });
} else{
    require(['auth/main'], function(auth){
        "use strict";
        auth();
    });
}