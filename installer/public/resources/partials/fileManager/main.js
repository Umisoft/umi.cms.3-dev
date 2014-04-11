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
//          http://localhost/admin/api/files/manager/settings
            var action = this.controller.get('data');
            $('#elfinder').elfinder({
                url : '/admin/api/files/manager/action/connector',
                lang: 'ru',
                uiOptions: {
                    toolbar : [
                        ['back', 'forward'],
//                        ['reload'],
                        // ['home', 'up'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['download'],
    //                    ['info'],
    //                    ['quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit'],
                        ['extract', 'archive'],
    //                    ['search'],
                        ['view'],
                        ['help']
                    ]
                }
            }).elfinder('instance');

            $('.elfinder-navbar').on('mousedown.umi.fileManager', '.elfinder-navbar-div', function(){
                $('.elfinder-navbar').children().removeClass('ui-state-active');
                $(this).addClass('ui-state-active');
            });

            var fileManagerLeft;
            var fileManagerRight;

          Ember.run.schedule('afterRender',function(){
            fileManagerLeft = new IScroll('.elfinder-navbar', UMI.config.iScroll);
            fileManagerRight = new IScroll('.elfinder-cwd', UMI.config.iScroll);
            $(window).on('resize.umi.fileManager', function(){
                fileManagerLeft.refresh();
                fileManagerRight.refresh();
            });
          });

        },

        willDestroyElement: function(){
            $(window).off('.umi.fileManager');
        }
    });
});