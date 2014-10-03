define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormTextareaElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "textareaElement" object=view.object meta=view.meta}}')
        });

        UMI.TextareaElementView = Ember.View.extend({
            templateName: 'partials/textareaElement',

            classNames: ['umi-element-textarea'],

            textareaView: function() {
                var viewParams = {
                    didInsertElement: function() {
                        this.allowResize();
                    },

                    willDestroyElement: function() {
                        this.get('parentView').$().off('mousedown.umi.textarea');
                    },

                    allowResize: function() {
                        var $textarea = this.$().find('textarea');
                        var minHeight = 60;
                        if ($textarea.length) {
                            $textarea.css({height: minHeight});
                        }
                        this.get('parentView').$().on('mousedown.umi.textarea', '.umi-element-textarea-resizer', function(event) {
                            if (event.button === 0) {
                                var $el = $(this);
                                $('html').addClass('s-unselectable');
                                $el.addClass('s-unselectable');
                                var posY = $textarea.offset().top;
                                $('body').on('mousemove.umi.textarea', function(event) {
                                    //TODO: Cделать метод глобальным
                                    //Подумать над тем, чтобы выделение сделаное до mouseMove не слетало
                                    //http://hashcode.ru/questions/86466/javascript-%D0%BA%D0%B0%D0%BA-%D0%B7%D0%B0%D0%BF%D1%80%D0%B5%D1%82%D0%B8%D1%82%D1%8C-%D0%B2%D1%8B%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%81%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B8%D0%BC%D0%BE%D0%B3%D0%BE-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%B1%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B5
                                    var removeSelection = function() {
                                        if (window.getSelection) {
                                            window.getSelection().removeAllRanges();
                                        } else if (document.selection && document.selection.clear) {
                                            document.selection.clear();
                                        }
                                    };
                                    removeSelection();

                                    var height = event.pageY - posY;
                                    if (height < minHeight) {
                                        height = minHeight
                                    }
                                    $textarea.css({height: height});
                                });
                                $('body').on('mouseup.umi.textarea', function() {
                                    $('body').off('mousemove.umi.textarea');
                                    $('body').off('mouseup.umi.textarea');
                                    $('html').removeClass('s-unselectable');
                                });
                            }
                        });
                    }
                };

                if (Ember.typeOf(this.get('object')) === 'instance') {
                    viewParams.template = function() {
                        var propertyName = this.get('parentView.meta.dataSource');
                        var textarea = '{{textarea placeholder=view.parentView.attributes.placeholder name=view.parentView.meta.attributes.name value=view.parentView.object.' + propertyName + '}}';
                        var validate = this.validateErrorsTemplate();
                        return Ember.Handlebars.compile(textarea + validate);
                    }.property();
                    return Ember.View.extend(UMI.InputValidate, viewParams);
                } else {
                    viewParams.template = function() {
                        var textarea = '{{textarea placeholder=view.parentView.attributes.placeholder name=view.parentView.meta.attributes.name value=view.parentView.attributes.value}}';
                        return Ember.Handlebars.compile(textarea);
                    }.property();
                    return Ember.View.extend(viewParams);
                }
            }.property()
        });
    };
});