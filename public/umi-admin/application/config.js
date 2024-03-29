define([], function() {
    'use strict';

    return function(UMI) {
        UMI.config = {
            iScroll: {
                scrollX: true,
                probeType: 3,
                mouseWheel: true,
                scrollbars: true,
                bounce: false,
                click: false,
                freeScroll: false,
                keyBindings: true,
                interactiveScrollbars: true,
                fadeScrollbars: true,
                disableMouse: false
            },

            elFinder: {
                url: '/admin/api/files/manager/action/connector',
                lang: 'ru',

                closeOnGetFileCallback: true,

                uiOptions: {
                    toolbar: [
                        ['back', 'forward'],
                        ['reload'],
                        ['getfile'],
                        // ['home', 'up'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['download'],
                        //                      ['info'], ['quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename'/*, 'edit'*/],
                        //                      ['extract', 'archive'], ['search'],
                        ['view'],
                        ['help']
                    ]
                }
            }
        };

        UMI.config.CkEditor = function() {
            var config = {};
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            config.toolbarGroups = [
                { name: 'clipboard', groups: ['clipboard', 'undo'] },
                { name: 'editing', groups: ['find', 'selection'] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document', groups: ['mode', 'document', 'doctools'] },
                { name: 'others' },
                '/',
                { name: 'styles' },
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                { name: 'colors' },
                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] }

            ];

            config.removeButtons = 'Underline,Subscript,Superscript';

            config.format_tags = 'p;h1;h2;h3;pre';

            config.removeDialogTabs = 'image:advanced;link:advanced';

            var locale = Ember.get(window, 'UmiSettings.locale') || '';
            locale = locale.split('-')[0];
            var allowedLocale = ['en', 'ru', 'de', 'es', 'it', 'uk'];
            if (allowedLocale.contains(locale)) {
                config.language = locale;
            } else {
                config.language = 'en';
            }

            config.height = '450px';

            config.baseFloatZIndex = 200;

            config.image_previewText = ' ';

            config.baseHref = Ember.get(window, 'UmiSettings.projectAssetsUrl');

            return config;
        };
    };
});