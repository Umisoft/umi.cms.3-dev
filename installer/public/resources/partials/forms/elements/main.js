define(
    [
        'App',

        'partials/forms/elements/mixins',

        'partials/forms/elements/wysiwyg/main',
        'partials/forms/elements/select/main',
        'partials/forms/elements/multi-select/main',
        'partials/forms/elements/checkbox/main',
        'partials/forms/elements/radio/main',
        'partials/forms/elements/text/main',
        'partials/forms/elements/number/main',
        'partials/forms/elements/email/main',
        'partials/forms/elements/password/main',
        'partials/forms/elements/time/main',
        'partials/forms/elements/date/main',
        'partials/forms/elements/datetime/main',
        'partials/forms/elements/file/main',
        'partials/forms/elements/image/main',
        'partials/forms/elements/textarea/main',
        'partials/forms/elements/checkbox-group/main'
    ],
    function(
        UMI,

        mixins,

        wysiwygElement,
        selectElement,
        multiSelectElement,
        checkboxElement,
        radioElement,
        textElement,
        numberElement,
        emailElement,
        passwordElement,
        timeElement,
        dateElement,
        datetimeElement,
        fileElement,
        imageElement,
        textareaElement,
        checkboxGroupElement
    ){
        "use strict";

        return function(){
            mixins();

            wysiwygElement();
            selectElement();
            multiSelectElement();
            /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
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
        };
    }
);