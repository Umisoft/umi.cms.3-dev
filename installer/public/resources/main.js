require.config({
    baseUrl: '/resources',

    paths: {
        text: 'libs/requirejs-text/text',

        App: 'application/application',

        Modernizr: 'libs/modernizr/modernizr',
        jQuery: 'libs/jquery/dist/jquery',
        Handlebars: 'libs/handlebars/handlebars',
        Ember: 'libs/ember/ember',
        DS: 'libs/ember-data/ember-data',

        iscroll: 'libs/iscroll-probe-5.1.1',
        ckEditor: 'libs/ckeditor/ckeditor',
        datepicker: 'libs/datepicker',
        moment: 'libs/momentjs/min/moment-with-langs.min',
        elFinder: 'build/js/elFinder',
        chartJs: 'libs/chartjs/Chart'

        //jQueryUI: 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min' //TODO Сейчас подключается с файлами elFinder. Нужно перепелить.
    },

    shim: {
        //Устанавливаем зависимости между библиотеками
        Modernizr:  {exports: 'Modernizr'},
        jQuery:     {exports: 'jQuery'},
        elFinder:   {exports: 'elFinder',   deps: ['jQuery']},
        Ember:      {exports: 'Ember',      deps: ['Handlebars', 'jQuery']},
        DS:         {exports: 'DS',         deps: ['Ember']},
        ckEditor:   {exports: 'ckEditor'},
        datepicker: {exports: 'datepicker', deps: ['jQuery']},
        chartJs:    {exports: 'chartJs'}
        //Требует elFinder jQueryUI: {deps: ['jQuery'],exports: 'jQueryUI'},
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
        {name: 'tree',              location: "partials/tree"}
    ]
});


if(UmiSettings.authenticated){
    require(['application/main', 'DS', 'Modernizr', 'iscroll', 'ckEditor', 'elFinder', 'datepicker', 'moment', 'chartJs'], function(application){
        "use strict";
        application();
    });
} else{
    require(['auth/main'], function(auth){
        "use strict";
        auth();
    });
}