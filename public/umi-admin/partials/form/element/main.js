define(
    [
        'App',
        'partials/form/element/mixins', 'partials/form/element/form.element.mixin',
        'partials/form/element/wysiwyg/main', 'partials/form/element/select/main',
        'partials/form/element/multi-select/main', 'partials/form/element/checkbox/main',
        'partials/form/element/radio/main', 'partials/form/element/text/main', 'partials/form/element/number/main',
        'partials/form/element/email/main', 'partials/form/element/password/main', 'partials/form/element/time/main',
        'partials/form/element/date/main', 'partials/form/element/datetime/main', 'partials/form/element/file/main',
        'partials/form/element/image/main', 'partials/form/element/textarea/main',
        'partials/form/element/checkbox-group/main', 'partials/form/element/color/main',
        'partials/form/element/permissions/main', 'partials/form/element/objectRelationElement/main',
        'partials/form/element/singleCollectionObjectRelation/main',
        'partials/form/element/passwordWithConfirmation/main', 'partials/form/element/hidden/main',
        'partials/form/element/fieldset/main', 'partials/form/element/submit/main'
    ],
    function(UMI, mixins, formElementMixin, wysiwygElement, selectElement, multiSelectElement, checkboxElement,
        radioElement, textElement, numberElement, emailElement, passwordElement, timeElement, dateElement,
        datetimeElement, fileElement, imageElement, textareaElement, checkboxGroupElement, colorElement, permissions,
        objectRelationElement, singleCollectionObjectRelation, passwordWithConfirmationElement, hiddenElement,
        fieldsetElement, submitElement) {
        'use strict';

        return function() {
            mixins();
            formElementMixin();

            wysiwygElement();
            selectElement();
            multiSelectElement();
            checkboxElement();
            radioElement();
            textElement();
            numberElement();
            emailElement();
            passwordElement();
            timeElement();
            dateElement();
            datetimeElement();
            fileElement();
            imageElement();
            textareaElement();
            checkboxGroupElement();
            colorElement();
            permissions();
            objectRelationElement();
            singleCollectionObjectRelation();
            passwordWithConfirmationElement();
            hiddenElement();
            fieldsetElement();
            submitElement();
        };
    }
);