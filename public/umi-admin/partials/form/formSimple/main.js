define(
    [
        'App',
        'partials/form/partial/magellan/main',
        'partials/form/partial/submitToolbar/main',
        './formSimpleController',
        './formSimpleView'
    ],
    function(UMI, magellan, submitToolbar, formSimpleController, formSimpleView) {
        'use strict';

        /**
         * Базовый тип формы.
         * @example
         * Объявление формы:
         *  {{render 'formBase' model}}
         */
        return function() {
            magellan();
            submitToolbar();

            formSimpleController();
            formSimpleView();
        };
    }
);
