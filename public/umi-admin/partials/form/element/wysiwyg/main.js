define(['App'], function(UMI) {
    'use strict';

    return function() {
        CKEDITOR.on('dialogDefinition', function(event) {
            var editor = event.editor;
            var dialogDefinition = event.data.definition;
            var tabCount = dialogDefinition.contents.length;
            var dialogName = event.data.name;

            var popupParams = {
                viewParams: {
                    popupType: 'fileManager',
                    title: UMI.i18n.getTranslate('Select file'),
                    fixed: true
                },
                templateParams: {
                    fileSelect: function(fileInfo) {
                        var self = this;
                        var image = Ember.get(fileInfo, 'url') || '';
                        var baseUrl = Ember.get(window, 'UmiSettings.projectAssetsUrl');
                        var pattern = new RegExp('^' + baseUrl, 'g');

                        window.CKEDITOR.tools.callFunction(editor._.filebrowserFn, image.replace(pattern, ''));
                        self.get('controller').send('closePopup');
                    }
                }
            };
            var browseButton;

            var showFileManager = function() {
                editor._.filebrowserSe = this;
                var $dialog = $('.cke_dialog');
                $dialog.addClass('s-blur');
                var $dialogCover = $('.cke_dialog_background_cover');
                $dialogCover.addClass('hide');

                var showDialogCK = function() {
                    $dialog.removeClass('s-blur');
                    $dialogCover.removeClass('hide');
                };
                popupParams.viewParams.beforeClose = showDialogCK;
                UMI.__container__.lookup('route:application').send('showPopup', popupParams);
            };

            for (var i = 0; i < tabCount; i++) {
                browseButton = dialogDefinition.contents[i];
                if (browseButton) {
                    browseButton = browseButton.get('browse');

                    if (browseButton !== null) {
                        browseButton.label = UMI.i18n.getTranslate('File manager');

                        if (i === 0) {
                            browseButton.style = 'display: inline-block; margin-top: 15px; margin-left: auto; margin-right: auto;';
                        }
                        browseButton.hidden = false;
                        browseButton.onClick = showFileManager;
                    }
                }
            }

            if (dialogName === 'link') {
                var infoTab = dialogDefinition.getContents('info');
                var protocol = infoTab.get('protocol');
                protocol.items.splice(0, 4);
                var linkUrlField = infoTab.get('url');
                linkUrlField.onKeyUp = function() {};
                linkUrlField.setup = function(data) {
                    this.allowOnChange = false;
                    if (data.url) {
                        this.setValue((typeof data.url.protocol === 'string' ? data.url.protocol : '') + data.url.url);
                    }
                    this.allowOnChange = true;
                };
                dialogDefinition.dialog.on('load', function(e) {
                    $(e.sender.parts.dialog.$).find('.cke_dialog_ui_hbox_first:first').hide();
                });
            }
        });

        UMI.HtmlEditorView = Ember.View.extend({
            classNames: ['ckeditor-row'],

            ckeditor: null,

            template: function() {
                var textarea = '{{textarea value=view.meta.attributes.value placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                return Ember.Handlebars.compile(textarea);
            }.property(),

            didInsertElement: function() {
                var config = UMI.config.CkEditor();
                var self = this;
                var el = this.$().children('textarea');
                el.css({'height': config.height});
                var editor = CKEDITOR.replace(el[0].id, config);
                self.set('ckeditor', editor);
            },

            willDestroyElement: function() {
                this.get('ckeditor').destroy();
            }
        });

        UMI.HtmlEditorCollectionView = Ember.View.extend({
            classNames: ['ckeditor-row'],

            ckeditor: null,

            textareaId: function() {
                return 'textarea-' + this.get('elementId');
            }.property(),

            template: function() {
                var textarea = '<textarea id="{{unbound view.textareaId}}" placeholder="{{unbound view.meta.placeholder}}" name="{{unbound view.meta.attributes.name}}">{{unbound view.object.' + this.get('meta.dataSource') + '}}</textarea>';
                return Ember.Handlebars.compile(textarea);
            }.property(),

            setTextareaValue: function(edit) {
                if (this.get('isDestroying') || this.get('isDestroyed')) {
                    return;
                }
                if (this.$() && this.$().children('textarea').length) {
                    var value = this.get('object.' + this.get('meta.dataSource'));
                    if (edit && edit.getData() !== value) {
                        edit.setData(value);
                    }
                }
            },

            updateContent: function(event, edit) {
                var self = this;
                if (event.editor.checkDirty()) {
                    self.get('object').set(self.get('meta.dataSource'), edit.getData());
                }
            },

            didInsertElement: function() {
                var config = UMI.config.CkEditor();
                var el = this.$().children('textarea');
                el.css({'height': config.height});

                Ember.run.next(this, function() {
                    var self = this;
                    var editor = CKEDITOR.replace(el[0].id, config);
                    self.set('ckeditor', editor);

                    editor.on('change', function(event) {
                        Ember.run.once(self, 'updateContent', event, editor);
                    });

                    self.addObserver('object.' + self.get('meta.dataSource'), function() {
                        Ember.run.next(self, 'setTextareaValue', editor);
                    });
                });
            },

            willDestroyElement: function() {
                var self = this;
                var editor = self.get('ckeditor');
                self.removeObserver('object.' + self.get('meta.dataSource'));
                if (typeof editor.commands.maximize !== 'undefined' && editor.commands.maximize.state === 1) {
                    editor.execCommand('maximize');
                }
                editor.destroy();
            }
        });

        UMI.FormHtmlEditorElementMixin = Ember.Mixin.create(UMI.FormElementMixin, UMI.FormElementValidateMixin, {
            classNames: 'small-12',

            elementView: UMI.HtmlEditorView.extend(UMI.FormElementValidateHandlerMixin, {})
        });

        UMI.FormHtmlEditorCollectionElementMixin = Ember.Mixin.create(UMI.FormHtmlEditorElementMixin, {
            elementView: UMI.HtmlEditorCollectionView.extend(UMI.FormElementValidateHandlerMixin, {})
        });
    };
});
