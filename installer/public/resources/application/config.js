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

                //                getFileCallback : function(file) {
                //                    window.opener.CKEDITOR.tools.callFunction(funcNum, file);
                //                    window.close();
                //                },

                closeOnGetFileCallback : true,
//                editorCallback : function(url) {
//                    console.log('elFinder', url);
//                    document.querySelector('.umi-input-wrapper-file .umi-file').value = url;
//                },
                getFileCallback : function(fileInfo){
                    console.log('getFileCallback', fileInfo);
//                    window.opener.CKEDITOR.tools.callFunction(funcNum, url);
                    document.querySelector('.umi-input-wrapper .umi-file').value = fileInfo.path;
                    document.querySelector('.umi-input-wrapper img').src = fileInfo.tmb;
//                    window.close();
                },

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

        CKEDITOR.editorConfig = function( config ) {
            // Define changes to default configuration here.
            // For the complete reference:
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            config.filebrowserBrowseUrl = '/admin/api/files/manager/action/connector';
            // The toolbar groups arrangement, optimized for two toolbar rows.
            config.toolbarGroups = [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }
            ];

            // Remove some buttons, provided by the standard plugins, which we don't
            // need to have in the Standard(s) toolbar.
            config.removeButtons = 'Underline,Subscript,Superscript';

            // Se the most common block elements.
            config.format_tags = 'p;h1;h2;h3;pre';

            // Make dialogs simpler.
            config.removeDialogTabs = 'image:advanced;link:advanced';
        };
    };
});