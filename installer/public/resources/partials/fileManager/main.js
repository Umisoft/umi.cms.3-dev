define(['App'], function(UMI){
    'use strict';

    UMI.FileManagerControlController = Ember.ObjectController.extend({
        needs: ['component'],
        connector: function(){
            return this.get('controllers.component').get('settings.actions.connector');
        }.property('model')
    });

    UMI.FileManagerControlView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager'],

        layout: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        didInsertElement: function(){
            var self = this;
            $('#elfinder').elfinder({
                url : self.get('controller.connector.source'),
                lang: 'ru',
                getFileCallback : function(fileInfo){
                    var contentParams = {};
                    contentParams.fileInfo = fileInfo;
                    self.set('parentView.contentParams', contentParams);
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