define(['App'], function(UMI){
    'use strict';

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager s-full-height'],

        layout: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        /**
         * @hook
         * @param fileInfo
         */
        fileSelect: function(fileInfo){
            return fileInfo;
        },

        init: function(){
            this._super();

            var templateParams = this.get('templateParams');
            if(Ember.typeOf(templateParams) === 'object'){
                this.setProperties(templateParams);
            }
        },

        didInsertElement: function(){
            var self = this;
            $('#elfinder').elfinder({
                url : window.UmiSettings.baseApiURL + '/files/manager/action/connector',//self.get('controller.connector.source'),
                lang: 'ru',
                getFileCallback: function(fileInfo){
                    self.fileSelect(fileInfo);
                },

                uiOptions: {
                    toolbar : [
                        ['back', 'forward'], ['reload'], ['getfile'], ['mkdir', 'mkfile', 'upload'], ['download'], ['copy', 'cut', 'paste'], ['rm'], ['duplicate', 'rename', 'edit'], ['view'], ['help']
                    ]
                }
            }).elfinder('instance');

            $('.elfinder-navbar').on('mousedown.umi.fileManager', '.elfinder-navbar-div', function(){
                $('.elfinder-navbar').children().removeClass('ui-state-active');
                $(this).addClass('ui-state-active');
            });
        },

        willDestroyElement: function(){
            $(window).off('.umi.fileManager');
        }
    });
});