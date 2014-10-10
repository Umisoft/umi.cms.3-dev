define(
    [
        'App',
        'partials/form/partial/magellan/main',
        'partials/form/partial/submitToolbar/main',
        './formSimpleController',
        './formSimpleElementsMixin',
        './formSimpleView'
    ],
    function(UMI, magellan, submitToolbar, formSimpleController, formSimpleElementsMixin, formSimpleView) {
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
            formSimpleElementsMixin();
            formSimpleView();
        };
    }
);
