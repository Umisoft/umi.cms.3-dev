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

        CKEDITOR.on('dialogDefinition', function(event){
            var dialogDefinition = event.data.definition;

            var tabCount = dialogDefinition.contents.length;
            for(var i = 0; i < tabCount; i++) {
                var browseButton = dialogDefinition.contents[i].get('browse');

                if (browseButton !== null) {
                    browseButton.hidden = false;
                    browseButton.onClick = function(dialog, i) {
                        var appController = UMI.lookup('controller:application');
                        //appController.send('showPopup', 'htmlEditor');
                    };
                }
            }
        });

        UMI.config.CkEditor = function(){
            var config = {};
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            //var fileManagerURl = Ember.get(window, 'UmiSettings.baseURL') + '/files/manager/root/fileManager';
            //config.filebrowserBrowseUrl =  fileManagerURl;
            //config.filebrowserImageBrowseUrl = fileManagerURl;

            //CKEDITOR.ui.dialog.button.eventProcessors = CKEDITOR.tools.extend({}, CKEDITOR.ui.dialog.uiElement.prototype.eventProcessors,
            //    { onClick : function( dialog, func ) { this.on( 'click', function(){console.log('asd');} ); } }, true);


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

            return config;
        };
    };
});