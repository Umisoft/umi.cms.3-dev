define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before', 's-unselectable'],

            focusId: null,

            buttonView: Ember.View.extend({
                tagName: 'a',

                classNameBindings: ['isFocus:focus'],

                isFocus: function() {
                    return this.get('model.id') === this.get('parentView.focusId');
                }.property('parentView.focusId'),

                click: function() {
                    var self = this;
                    var $fieldset = $('.umi-fieldset-' + this.get('model.id'));
                    var scrollContent = self.get('parentView.scrollContent');
                    scrollContent.scrollTo(0, - $fieldset.position().top +
                    parseInt($fieldset.css('marginTop').replace('px', '')));
                    setTimeout(function() {
                        if (self.get('parentView.focusId') !== self.get('model.id')) {
                            self.get('parentView').set('focusId', self.get('model.id'));
                        }
                    }, 10);
                }
            }),

            scrollContent: null,

            init: function() {
                this._super();
                var elements = this.get('elements');
                this.set('focusId', elements.get('firstObject.id'));
            },

            didInsertElement: function() {
                var self = this;
                var scrollArea = this.$().parent().find('.magellan-content');
                var $body = $('body');

                if (!scrollArea.length) {
                    return;
                }

                var fieldset = scrollArea.find('fieldset');

                Ember.run.next(self, function() {
                    var lastFieldset = fieldset[fieldset.length - 1];
                    var placeholderFieldset;
                    var lastFieldsetHeight = lastFieldset.offsetHeight;
                    var scrollAreaHeight = scrollArea[0].offsetHeight;

                    var setPlaceholderHeight = function(placeholder) {
                        lastFieldsetHeight = lastFieldset.offsetHeight;
                        scrollAreaHeight = scrollArea.parent().height();
                        placeholder.style.height = scrollAreaHeight - lastFieldsetHeight - 10 -
                        parseInt($(lastFieldset).css('marginBottom')) + 'px';
                        $body.trigger('resize.umi.formcontroll');
                    };

                    if (scrollAreaHeight > lastFieldsetHeight) {
                        placeholderFieldset = document.createElement('div');
                        placeholderFieldset.className = 'umi-js-fieldset-placeholder';
                        placeholderFieldset = scrollArea[0].appendChild(placeholderFieldset);
                        setPlaceholderHeight(placeholderFieldset);
                        $(window).on('resize.umi.magellan.fieldsetPlaceholder', function() {
                            setPlaceholderHeight(placeholderFieldset);
                        });
                    }

                    self.set('scrollContent', new IScroll(scrollArea.parent()[0],
                        $.extend({}, UMI.config.iScroll, {
                                preventDefaultException: {
                                    tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|LABEL|SPAN)$/,
                                    className: /^(cke_resizer)$/
                            }
                        }
                    )));
                    $body.on('resize.umi.formcontroll', function() {
                        self.get('scrollContent').refresh();
                    });

                    scrollArea.on('scroll.umi.magellan', function() {
                        var scrollOffset = $(this).scrollTop();
                        var focusField;
                        var scrollElement;

                        for (var i = 0; i < fieldset.length; i++) {
                            scrollElement = fieldset[i].offsetTop;
                            if (scrollElement - parseFloat(getComputedStyle(fieldset[i]).marginTop) <= scrollOffset &&
                                scrollOffset <= scrollElement + fieldset[i].offsetHeight) {
                                focusField = fieldset[i];
                            }
                        }

                        if (focusField) {
                            self.set('focusId', focusField.className.replace(/umi-fieldset-|ember-view|\s/g, ''));
                        }
                    });

                    scrollArea.find('legend').on('click.umi.magellan', function() {
                        $body.trigger('resize.umi.formcontroll');
                    });

                });
            }
        });
    };
});
