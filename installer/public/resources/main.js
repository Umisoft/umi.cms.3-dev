require.config({
    baseUrl: '/resources',

    paths: {
        text: 'libs/requirejs-text/text',

        App: 'app/components/skeleton/main',

        Modernizr: 'libs/modernizr/modernizr',
        jQuery: 'libs/jquery/jquery',
        Handlebars: 'libs/handlebars/handlebars',
        Ember: 'libs/ember/ember',
        DS: 'libs/ember-data/ember-data',
        Foundation: 'deploy/foundation',

        iscroll: 'libs/iscroll-probe-5.1.1',
        ckEditor: 'libs/ckeditor/ckeditor',
        datepicker: 'libs/datepicker',
        moment: 'libs/momentjs/min/moment-with-langs.min',
        elFinder: 'deploy/elFinder',
        chartJs: 'libs/chartjs/Chart'

        //jQueryUI: 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min' //TODO Сейчас подключается с файлами elFinder. Нужно перепелить.
    },

    shim: {
        //Устанавливаем зависимости между библиотеками
        Modernizr:  {exports: 'Modernizr'},
        jQuery:     {exports: 'jQuery'},
        elFinder:   {exports: 'elFinder',   deps: ['jQuery']},
        Ember:      {exports: 'Ember',      deps: ['Handlebars', 'jQuery']},
        Foundation: {exports: 'Foundation', deps: ['jQuery']},
        DS:         {exports: 'DS',         deps: ['Ember']},
        ckEditor:   {exports: 'ckEditor'},
        datepicker: {exports: 'datepicker', deps: ['jQuery']},
        chartJs:    {exports: 'chartJs'}
        //Требует elFinder jQueryUI: {deps: ['jQuery'],exports: 'jQueryUI'},
    },

    packages: [
        //Подключаем Partials. �?мена следуют по алфавиту, как и в структуре папок
        {name: 'accordion',         location: "app/components/accordion"},
        {name: 'chartControl',      location: "app/components/chartControl"},
        {name: 'dialog',            location: "app/components/dialog"},
        {name: 'dock',              location: "app/components/dock"},
        {name: 'fileManager',       location: "app/components/fileManager"},
        {name: 'form',              location: "app/components/form"},
        {name: 'notification',      location: "app/components/notification"},
        {name: 'popup',             location: "app/components/popup"},
        {name: 'search',            location: "app/components/search"},
        {name: 'megaIndex',         location: "app/components/seo/megaIndex"},
        {name: 'yandexWebmaster',   location: "app/components/seo/yandexWebmaster"},
        //skeleton                  app/components/skeleton
        {name: 'table',             location: "app/components/table"},
        {name: 'tableControl',      location: "app/components/tableControl"},
        {name: 'topBar',            location: "app/components/topBar"},
        {name: 'tree',              location: "app/components/tree"}
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