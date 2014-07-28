define([], function(){
    "use strict";

    return function(UMI){
        UMI.config = {
            iScroll: {
                scrollX: true,
                probeType: 3,
                mouseWheel: true,
                scrollbars: true,
                bounce: false,
                click: true,
                freeScroll: false,
                keyBindings: true,
                interactiveScrollbars: true
            },

            elFinder: {
                url : '/admin/api/files/manager/action/connector',
                lang: 'ru',

                closeOnGetFileCallback : true,

                uiOptions: {
                    toolbar : [
                        ['back', 'forward'], ['reload'], ['getfile'],
                        // ['home', 'up'],
                        ['mkdir', 'mkfile', 'upload'], ['download'],
//                      ['info'], ['quicklook'],
                        ['copy', 'cut', 'paste'], ['rm'], ['duplicate', 'rename', 'edit'],
//                      ['extract', 'archive'], ['search'],
                        ['view'], ['help']
                    ]
                }
            }
        };

        CKEDITOR.on('dialogDefinition', function(event){
            var editor = event.editor;
            var dialogDefinition = event.data.definition;
            var tabCount = dialogDefinition.contents.length;
            var dialogName = event.data.name;

            var popupParams = {
                viewParams: {
                    popupType: 'fileManager'
                },
                templateParams: {
                    fileSelect: function(fileInfo){
                        var self = this;
                        window.CKEDITOR.tools.callFunction(editor._.filebrowserFn, Ember.get(fileInfo, 'url'));
                        self.get('controller').send('closePopup');
                    }
                }
            };

            for(var i = 0; i < tabCount; i++) {
                var browseButton = dialogDefinition.contents[i].get('browse');

                if (browseButton !== null) {
                    browseButton.hidden = false;
                    browseButton.onClick = function(dialog, i){
                        editor._.filebrowserSe = this;
                        var $dialog = $('.cke_dialog');
                        $dialog.addClass('umi-blur');
                        var $dialogCover = $('.cke_dialog_background_cover');
                        $dialogCover.addClass('hide');

                        var showDialogCK = function(){
                            $dialog.removeClass('umi-blur');
                            $dialogCover.removeClass('hide');
                        };
                        popupParams.viewParams.beforeClose = showDialogCK;
                        UMI.__container__.lookup('route:application').send('showPopup', popupParams);
                    };
                }
            }
        });

        UMI.config.CkEditor = function(){
            var config = {};
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            config.toolbarGroups = [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }
            ];

            config.removeButtons = 'Underline,Subscript,Superscript';

            config.format_tags = 'p;h1;h2;h3;pre';

            config.removeDialogTabs = 'image:advanced;link:advanced';

            var locale = Ember.get(window, 'UmiSettings.locale') || '';

            config.language = locale.split('-')[0];

            config.height = '450px';

            config.baseFloatZIndex = 200;

            config.image_previewText = ' ';

            return config;
        };
    };
});