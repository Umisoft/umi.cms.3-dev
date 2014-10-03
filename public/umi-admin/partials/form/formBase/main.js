define(
    [
        'App',
        'partials/form/partial/magellan/main',
        'partials/form/partial/submitToolbar/main',
        './form.controller.mixin',
        './form.controller',
        './form.view.mixin',
        './form.view'
    ],
    function(UMI, magellan, submitToolbar, formControllerMixin, formController, formViewMixin, formView) {
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

            formControllerMixin();
            formController();

            formViewMixin();
            formView();
        };
    }
);
