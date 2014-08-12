(function($, window, document, undefined) {
    'use strict';

    var Foundation = window.Foundation = window.Foundation || {};
    Foundation.libs = Foundation.libs || {};

    /**
     * UMI расширяет поведение выпадающих списков Foundation, изменяя/добавляя следующее:
     * 1) Событие клика "мимо" списка навешивается в момент открытия списка, и снимается во время закрытия списка.
     *    В Foundation это событие всегда слушает клик, с самого старта приложения.
     * 2) Атрибут Id не является обязательным атрибутом для выборки, достаточно иметь один экземпляр списка на одном
     *    уровне вложености с кнопкой. Для этого нужно указать в атрибутах кнопки data-options="selectorById: false;"
     * 3) Кроме определенной в Foundation возможности отределить сторону появления списка относительно кнопки,
     *    добавляется возможность указать выравнивание по стороне.
     */
    Foundation.libs.dropdown = {
        name: 'dropdown',
        version: '5.3.0.umi-custom',

        settings: {
            /**
             * Класс изменяющий видимость списка
             * @param {string} activeClass
             */
            activeClass: 'open',

            /**
             * Список открывается при наведении
             * @param {bool} isHover
             */
            isHover: false,

            /**
             * Разрешает быстрый выбор при нажатии
             * @param {bool} selectOnPressIsAllowed
             */
            selectOnPressIsAllowed: false,
            /**
             * Определяет положение спсика относительно кнопки
             * @param {string} side
             */
            side: 'bottom',

            /**
             * Определяет выравнивание списка относительно кнопки
             * @param {string} align
             */
            align: 'left',

            /**
             * Выбор списка по уникальному id
             * @param {bool} selectorById
             */
            selectorById: true,

            /**
             * Задает списку минимальную ширину, равную ширине кнопки (хендлера) имеющего соответствующий селектор
             * @param {string} minWidthLikeElement класс елемента
             */
            minWidthLikeElement: '.button',

            /**
             * @param {bool} adaptiveBehaviour
             */
            adaptiveBehaviour: true,

            customEventListener: function() {},

            opened: function() {},

            closed: function() {}
        },

        selector: function() {
            return '[' + this.attr_name() + ']';
        },

        init: function(scope, method, options) {
            Foundation.inherit(this, 'throttle');

            this.bindings(method, options);
        },

        events: function(scope) {
            var self = this;
            var S = self.S;
            var settings = S(scope).data(self.attr_name(true) + '-init') || self.settings;

            S(self.scope).off('.' + self.name);

            if (settings.selectOnPress) {
                self.eventListener.mousedown.call(this, scope, settings);
            }
            this.eventListener.click.call(this, scope, settings);

            this.eventListener.resize.call(this);
        },

        eventListener: {
            click: function(scope, settings) {
                var self = this;
                var S = self.S;

                S(this.scope).on('click.fndtn.dropdown', this.selector(), function(e) {
                    if (!settings.isHover || Modernizr.touch) {
                        e.preventDefault();
                        self.toggle($(this));
                    }
                });
            },

            mousedown: function(scope, settings) {
                var self = this;
                var S = self.S;

                S(this.scope).on('mousedown.fndtn.dropdown', this.selector(), function(e) {
                    if (!settings.isHover || Modernizr.touch) {
                        e.preventDefault();
                        self.toggle($(this));
                    }
                });
            },

            /**
             * Добавляет событие ловящее клик мимо открытого выпадающего списка и закрывает этот список.
             * @method onClickMissDropdown
             */
            onClickMissDropdown: function() {
                var self = this;
                var S = self.S;
                var dropdownSelector = '[' + self.attr_name() + '-content]';

                S(this.scope).on('click.fndtn.dropdown.miss', function(e) {
                    var parent = S(e.target).closest(dropdownSelector);

                    if (S(e.target).data(self.dataAttr()) || S(e.target).parent().data(self.dataAttr())) {
                        return;
                    }

                    if (parent.length > 0 && (S(e.target).is(dropdownSelector) ||
                        $.contains(parent.first()[0], e.target))) {
                        e.stopPropagation();
                        return;
                    }

                    self.close.call(self, S(dropdownSelector));
                });
            },

            /**
             * Снимает событие "ловли" клика мимо открытого списка
             * @method offClickMissDropdown
             */
            offClickMissDropdown: function() {
                var self = this;
                var S = self.S;

                S(this.scope).off('click.fndtn.dropdown.miss');
            },

            resize: function() {
                var self = this;
                var S = self.S;

                S(window).off('.dropdown').on('resize.fndtn.dropdown', self.throttle(function() {
                    self.resize.call(self);
                }, 50));

                self.resize.call(self);
            }
        },

        getDropdown: function(target) {
            var self = this;
            var S = self.S;
            var dropdown;
            var settings = S(target).data(self.attr_name(true) + '-init') || self.settings;
            var dropdownId;

            if (settings.selectorById) {
                dropdownId = target.data(this.dataAttr());
                if (dropdownId) {
                    dropdown = S('#' + dropdownId);
                } else {
                    console.warn('Id attr is undefined.');
                    return false;
                }
            } else {
                dropdown = $($(S(target)[0].parentNode).children('[data-' + self.name + '-content]')[0]);
            }

            return dropdown;
        },

        /**
         * Переключает видимость списка
         * @method toggle
         * @param {JQuery} target
         */
        toggle: function(target) {
            var self = this;
            var dropdown = self.getDropdown(target);
            if (!dropdown || dropdown.length === 0) {
                return;
            }

            self.close.call(self, self.S('[' + self.attr_name() + '-content]').not(dropdown));

            if (dropdown.hasClass(self.settings.activeClass)) {
                self.close.call(self, dropdown);
                if (dropdown.data('target') !== target.get(0)) {
                    self.open.call(self, dropdown, target);
                }
            } else {
                self.open.call(self, dropdown, target);
            }
        },

        close: function(dropdown) {
            var self = this;

            dropdown.each(function() {
                if (self.S(this).hasClass(self.settings.activeClass)) {
                    self.S(this)
                        .removeClass(self.settings.activeClass)
                        .removeAttr('style')
                        .prev('[' + self.attr_name() + ']')
                        .removeClass(self.settings.activeClass)
                        .removeData('target');

                    self.S(this).trigger('closed').trigger('closed.fndtn.dropdown', [dropdown]);
                }
            });

            this.eventListener.offClickMissDropdown.call(this);
        },

        closeall: function() {
            var self = this;
            $.each(self.S('[' + this.attr_name() + '-content]'), function() {
                self.close.call(self, self.S(this));
            });
        },

        /**
         * Открывает список
         * @method open
         * @param {JQuery} dropdown открываемый список
         * @param {JQuery} target хендлер (кнопка) вызвавший открытие списка
         */
        open: function(dropdown, target) {
            this.setDropdownStyle(dropdown.addClass(this.settings.activeClass), target);

            dropdown.prev('[' + this.attr_name() + ']').addClass(this.settings.activeClass);
            dropdown.data('target', target.get(0)).trigger('opened')
                .trigger('opened.fndtn.dropdown', [dropdown, target]);

            this.eventListener.onClickMissDropdown.call(this);
        },

        resize: function() {
            var dropdown = this.S('[' + this.attr_name() + '-content].open');
            var target = this.S('[' + this.attr_name() + '="' + dropdown.attr('id') + '"]');

            if (dropdown.length && target.length) {
                this.setDropdownStyle(dropdown, target);
            }
        },

        /**
         * Возвращает имя атрибута data для хендлера
         * @return {string}
         */
        dataAttr: function() {
            if (this.namespace.length > 0) {
                return this.namespace + '-' + this.name;
            }
            return this.name;
        },

        /**
         * Устанавливает стили для выпадающего списка
         * @param {JQuery} dropdown
         * @param {JQuery} target
         * @return {JQuery} dropdown
         */
        setDropdownStyle: function(dropdown, target) {
            var settings = target.data(this.attr_name(true) + '-init') || this.settings;
            var leftOffset;
            var styleForSmall;

            this.clearIdx();

            if (this.small()) {//TODO: fix it
                leftOffset = Math.max((target.width() - dropdown.width()) / 2, 8);
                var p = this.styleForSide.bottom.call(dropdown, target);

                styleForSmall = {
                    position: 'absolute',
                    width: '95%',
                    'maxWidth': 'none',
                    top: p.top
                };

                if (settings.minWidthLikeElement) {
                    styleForSmall.minWidth = target.outerWidth() + 'px';
                }

                dropdown.attr('style', '').removeClass('drop-left drop-right drop-top').css(styleForSmall);
                dropdown.css(Foundation.rtl ? 'right' : 'left', leftOffset);
            } else {
                this.style(dropdown, target, settings);
            }

            return dropdown;
        },

        /**
         * Устанавливает стили для выпадающего списка
         * @param {JQuery} dropdown
         * @param {JQuery} target
         * @param {object} settings
         */
        style: function(dropdown, target, settings) {
            var side = settings.side;
            var align = settings.align;
            var dropdownStyles = {position: 'absolute'};

            if (settings.minWidthLikeElement) {
                dropdownStyles.minWidth = target.closest(settings.minWidthLikeElement).outerWidth() + 'px';
                dropdown.css({'minWidth': dropdownStyles.minWidth});
            }

            var checkPosition = this.checkPosition(dropdown, target, settings, side, align);
            side = checkPosition.side;
            align = checkPosition.align;

            var basePosition = this.styleFor._basePosition.call(dropdown, target);
            var computedStyle = this.styleFor.side[side].call(dropdown, target, basePosition);
            computedStyle = $.extend(computedStyle, this.styleFor.align[align].call(dropdown, target, basePosition));
            computedStyle = $.extend(dropdownStyles, computedStyle);

            dropdown.attr('style', '').css(dropdownStyles);
        },

        checkPosition: function(dropdown, target, settings, side, align) {
            if (settings.adaptiveBehaviour) {
                var screenSize = {
                    width: $(window).width(),
                    height: $(window).height()
                };
                var dropdownSize = {
                    width: dropdown.outerWidth(),
                    height: dropdown.outerHeight()
                };
                var dropdownPosition = dropdown.offset();
                var targetSize = {
                    width: target.outerWidth(),
                    height: target.outerHeight()
                };
                var targetOffset = target.offset();

                switch (side) {
                    case 'top':
                        if (dropdownSize.height > targetOffset.top &&
                            screenSize.height > dropdownSize.height + targetOffset.top + targetSize.height) {
                            side = 'bottom';
                        }
                        break;
                    case 'bottom':
                        if (targetOffset.top + dropdownSize.height + targetSize.height > screenSize.height &&
                            targetOffset.top - dropdownSize.height > 0) {
                            side = 'top';
                        }
                        break;
                    case 'left':
                        if (targetOffset.left - dropdownSize.width < 0 &&
                            targetOffset.left + targetSize.width + dropdownSize.width < screenSize.width) {
                            side = 'right';
                        }
                        break;
                    case 'right':
                        if (targetOffset.left + targetSize.width + dropdownSize.width > screenSize.width &&
                            targetOffset.left - dropdownSize.width > 0) {
                            side = 'left';
                        }
                        break;
                }

                switch (align) {
                    case 'top':
                        if (targetOffset.top + dropdownSize.height > screenSize.height &&
                            targetOffset.top > dropdownSize.height) {
                            align = 'bottom';
                        }
                        break;
                    case 'bottom':
                        if (targetOffset.top < dropdownSize.height &&
                            targetOffset.top + dropdownSize.height > screenSize.height) {
                            align = 'top';
                        }
                        break;
                    case 'left':
                        if (targetOffset.left + dropdownSize.width > screenSize.width &&
                            targetOffset.left + targetSize.width > dropdownSize.width) {
                            align = 'right';
                        }
                        break;
                    case 'right':
                        if (targetOffset.left + targetSize.width < dropdownSize.width &&
                            targetOffset.left + dropdownSize.width < screenSize.width) {
                            align = 'left';
                        }
                        break;
                }
            }

            return {side: side, align: align};
        },

        styleFor: {
            /**
             * Вычисляет положение кнопки относительно предка имеющего position отличный от static
             * @param {JQuery} target
             * @return {object}
             * @private
             */
            _basePosition: function(target) {
                var offsetClosestPositionedParent = this.offsetParent().offset();
                var basePosition = target.offset();

                basePosition.top -= offsetClosestPositionedParent.top;
                basePosition.left -= offsetClosestPositionedParent.left;

                return basePosition;
            },

            side: {
                top: function(target, basePosition) {
                    this.addClass('drop-top');

                    return {top: basePosition.top - this.outerHeight()};
                },

                bottom: function(target, basePosition) {
                    return {top: basePosition.top + target.outerHeight()};
                },

                left: function(target, basePosition) {
                    this.addClass('drop-left');

                    return {left: basePosition.left - this.outerWidth()};
                },

                right: function(target, basePosition) {
                    this.addClass('drop-right');

                    return {left: basePosition.left + target.outerWidth()};
                }
            },

            align: {
                top: function(target, basePosition) {
                    return {top: basePosition.top};
                },

                bottom: function(target, basePosition) {
                    return {top: basePosition.top - this.outerHeight() + target.outerHeight()};
                },

                left: function(target, basePosition) {
                    return {left: basePosition.left};
                },

                right: function(target, basePosition) {
                    return {left: basePosition.left - this.outerWidth() + target.outerWidth()};
                }
            }
        },

        clearIdx: function() {
            var sheet = Foundation.stylesheet;

            if (this.rule_idx) {
                sheet.deleteRule(this.rule_idx);
                sheet.deleteRule(this.rule_idx);
                delete this.rule_idx;
            }
        },

        small: function() {
            return matchMedia(Foundation.media_queries.small).matches &&
                !matchMedia(Foundation.media_queries.medium).matches;
        },

        off: function() {
            this.S(this.scope).off('.fndtn.dropdown');
            this.S(window).off('.fndtn.dropdown');
        }
    };
}(jQuery, window, window.document));
