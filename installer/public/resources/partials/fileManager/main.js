define(['App'], function(UMI){
    'use strict';

    UMI.FileManagerController = Ember.ObjectController.extend({
        needs: ['action'],
        data: function(){
            return this.get('controllers.action').get('model').get('action');
        }.property()
    });

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager'],

        template: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        didInsertElement: function(){
            var that = this;
            console.log('elfinder');
            $('#elfinder').elfinder({
                url : '/admin/api/files/manager/action/connector',
                lang: 'ru',

                getFileCallback : function(fileInfo){
                    var contentParams = {};
                    contentParams.fileInfo = fileInfo;
                    that.set('parentView.contentParams', contentParams);
                    $('.umi-popup').remove(); //TODO отправлять событие на закрытие Popup
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