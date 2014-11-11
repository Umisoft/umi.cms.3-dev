define(['App'], function(UMI) {
    'use strict';

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        classNames: ['umi-file-manager s-full-height'],

        layout: Ember.Handlebars.compile('<div id="elfinder"></div>'),

        /**
         * @hook
         * @param fileInfo
         */
        fileSelect: function(fileInfo) {
            return fileInfo;
        },

        init: function() {
            this._super();

            var templateParams = this.get('templateParams');
            if (Ember.typeOf(templateParams) === 'object') {
                this.setProperties(templateParams);
            }
        },

        didInsertElement: function() {
            var self = this;
            $('#elfinder').elfinder($.extend({},
                UMI.config.elFinder, {
                    url: window.UmiSettings.baseApiURL + '/files/manager/action/connector',
                    getFileCallback: function(fileInfo) {
                        self.fileSelect(fileInfo);
                    }/*,
                    handlers: {
                        init: function() {
                            var $wrapper = $('<div class="umi-tree-wrapper"></div>');
                            $('.elfinder-tree').wrap($wrapper);
                            var iScrollConfiguration = $.extend({disableMouse: true}, UMI.config.iScroll);
                            var contentScroll = new IScroll($wrapper[0], iScrollConfiguration);
                        }

                    }*/
            })).elfinder('instance');

            $('.elfinder-navbar').on('mousedown.umi.fileManager', '.elfinder-navbar-div', function() {
                $('.elfinder-navbar').children().removeClass('ui-state-active');
                $(this).addClass('ui-state-active');
            });
        },

        willDestroyElement: function() {
            $(window).off('.umi.fileManager');
        }
    });
});