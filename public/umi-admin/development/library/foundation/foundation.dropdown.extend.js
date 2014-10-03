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
     * 3) Кроме определенной в Foundation возможности определить сторону появления списка относительно кнопки,
     *    добавляется возможность указать выравнивание по стороне. Это добавляет к четырем возможным позициям положения
     *    списка, ещё четыре.
     * 4) Возможность быстрого выбора элемента из списка за один клик. На событие нажатие кнопки мыши, список
     *    раскрывается, и если отпустить кнопку над элементом списка, вызывается тригер события 'click' для этого
     *    элемента. Отпустив кнопку вне списка, происходит его закрытие.
     *    С помощью опций можно кастомизировать событие fastSelect.
     * 5) Возможность задать элемент списка, при клике на который происходит закрытие этого списка.
     * 6) Адаптивное поведение списка. При недостатке места для списка, он инвертирует своё положение если с другой
     *    стороны достаточно места.
     * 7) Runtime регистрация компонентов.
     * 8) Класс заданный в activeClass может присваиваться так же и кнопке вызвавшей появление списка
     * (опция respondActiveClassForButton).
     * 9) Можно указать элемент ширина которого будет минимальной для списка.
     */
    Foundation.libs.dropdown = {
        name: 'dropdown',
        version: '5.3.0.umi-custom',

        settings: {
            /**
             * В Foundation регистрируется только те экземпляры компонентов, которые были в DOM на момент инициализации
             * приложения. Для того чтобы зарегистрировать компоненты, появляющиеся в процессе работы с приложением
             * нужно заново инициализировать Foundation. Это может быть довольно затратной операцией, вынуждающая к
             * дублированию вызова Foundation. runtimeInit позволяет регистрировать компоненты появляющиеся во время
             * работы приложения.
             */
            runtimeInit: true,
            /**
             * Класс изменяющий видимость списка
             * @param {string} activeClass
             */
            activeClass: 'open',

            /**
             * Назначать activeClass для кнопки (хендлера)
             * @property respondActiveClassForElement
             */
            respondActiveClassForButton: true,

            /**
             * Список открывается при наведении
             * @param {bool} isHover
             */
            isHover: false,

            /**
             * Разрешает быстрый выбор при нажатии
             * @param {bool} fastSelect
             */
            fastSelect: true,

            fastSelectHoverSelector: 'li',

            fastSelectHoverClassName: 'hover',

            fastSelectTarget: 'a',

            /**
             * Определяет положение спиcка относительно кнопки
             * @param {string} side
             */
            side: 'bottom',

            /**
             * Определяет выравнивание спиcка относительно кнопки
             * @param {string} align
             */
            align: 'left',

            /**
             * Выбор списка по уникальному id
             * @param {bool} selectorById
             */
            selectorById: true,

            /**
             * Заменяет элемент вызвавщий список, на его предка с указанным селектором, для корректного расчета стилей
             * @param {string | null} replaceTarget
             */
            replaceTarget: null,

            /**
             * Селектор кнопки
             */
            buttonSelector: '.button',

            /**
             * Задает списку минимальную ширину, равную ширине кнопки или её предка имеющего соответствующий селектор
             * @param {string | null} minWidthLikeElement класс елемента
             */
            minWidthLikeElement: '.button',

            /**
             * Задаёт списку максимальную ширину, равную ширине кнопки или её предка имеющего соответствующий селектор
             * @param {string | null} maxWidthLikeElement класс елемента
             */
            maxWidthLikeElement: null,

            /**
             * @param {bool} adaptiveBehaviour
             */
            adaptiveBehaviour: true,

            /**
             * Проверять позицию относительно родительского элемента имеющего данный селектор. По умолчанию это первый
             * предок имеющий position отличный от static
             * @param {string | null} checkPositionRegardingElement
             */
            checkPositionRegardingElement: null,

            /**
             * @param {string} closeAfterClickOnElement
             */
            closeAfterClickOnElement: 'a',

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
            S(self.scope).off('.' + self.name);

            self.eventListener.mousedown.call(this, scope);
            self.eventListener.click.call(this, scope);
            self.eventListener.hover.call(this);
            self.eventListener.resize.call(this);
            self.eventListener.toggleObserver.call(this);
        },

        eventListener: {
            click: function(scope) {
                var self = this;
                var S = self.S;

                S(self.scope).on('click.fndtn.dropdown', self.selector(), function(e) {
                    var settings = self.getSettings(this);
                    if (!settings.fastSelect && (!settings.isHover || Modernizr.touch)) {
                        e.preventDefault();
                        self.toggle($(this), settings);
                    }
                });
            },

            mousedown: function(scope) {
                var self = this;
                var S = self.S;
                var dropdownSelector = '[' + self.attr_name() + '-content]';

                S(self.scope).on('mousedown.fndtn.dropdown', self.selector(), function(e) {
                    var settings = self.getSettings(this);

                    if (settings.fastSelect && (!settings.isHover || Modernizr.touch)) {
                        e.preventDefault();
                        var $targetButton = $(this);
                        var $dropdown = self.toggle($targetButton, settings);
                        var hoverSelector = settings.fastSelectHoverSelector;
                        var hoverClassName = settings.fastSelectHoverClassName;
                        var fastSelectTarget = settings.fastSelectTarget;

                        S(self.scope).on('mousemove.fndtn.dropdown.fast', dropdownSelector, function(event) {
                            $dropdown = $(this);

                            var $hoverElement = $(event.target).closest(hoverSelector);

                            if ($hoverElement.length) {
                                if (!$hoverElement.hasClass(hoverClassName)) {
                                    $dropdown.find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                                    $hoverElement.addClass(hoverClassName);
                                }
                            } else {
                                $dropdown.find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                            }
                        });

                        S(self.scope).on('mouseout.fndtn.dropdown.fast', dropdownSelector, function() {
                            $(this).find(hoverSelector + '.' + hoverClassName).removeClass(hoverClassName);
                        });

                        S(self.scope).on('mouseup.fndtn.dropdown.fast', function(e) {
                            var fastClick;
                            var $target = $(e.target);

                            if ($dropdown.find(e.target).length) {
                                fastClick = $target.closest(fastSelectTarget);
                                if (fastClick.length) {
                                    fastClick.trigger('click');
                                }
                            } else if (!$target.closest($targetButton).length) {
                                self.closeall();
                            }
                            S(self.scope).off('.dropdown.fast');
                        });
                    }
                });
            },

            hover: function() {
                var self = this,
                    S = self.S;

                S(self.scope).on('mouseenter.fndtn.dropdown',
                    '[' + self.attr_name() + '], [' + self.attr_name() + '-content]', function(e) {
                    var $this = S(this);
                    var dropdown;
                    var target;

                    clearTimeout(self.timeout);

                    if ($this.data(self.dataAttr()) !== undefined) {
                        target = $this;
                        dropdown = self.getDropdown(target);
                    } else {
                        dropdown = $this;
                        target = S('[' + self.attr_name() + '="' + dropdown.attr('id') + '"]');
                        if (target.length === 0) {
                            target = dropdown.parent().children(self.selector());
                        }
                    }

                    var settings = self.getSettings(target);

                    if (settings.isHover) {
                        self.closeall.call(self);
                        self.open.apply(self, [dropdown, target]);
                    }
                });

                S(self.scope).on('mouseleave.fndtn.dropdown',
                    '[' + self.attr_name() + '], [' + self.attr_name() + '-content]', function() {
                    var $this = S(this);

                    self.timeout = setTimeout(function() {
                        var settings;
                        var dropdown;

                        if ($this.data(self.dataAttr()) !== undefined) {
                            settings = self.getSettings($this);

                            if (settings.isHover) {
                                dropdown = self.getDropdown($this, settings);
                                self.close.call(self, dropdown);
                            }
                        } else {
                            var target = S('[' + self.attr_name() + '="' + $this.attr('id') + '"]');
                            if (target.length === 0) {
                                target = $this.parent().children(self.selector());
                            }
                            settings = self.getSettings(target);
                            if (settings.isHover) {
                                self.close.call(self, $this);
                            }
                        }
                    }, 150);
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

                S(self.scope).on('click.fndtn.dropdown.miss', function(e) {
                    var parent = S(e.target).closest(dropdownSelector);

                    if (S(e.target).data(self.dataAttr()) || S(e.target).parent().data(self.dataAttr())) {
                        return;
                    }

                    if (parent.length > 0 && (S(e.target).is(dropdownSelector) ||
                        $.contains(parent.first()[0], e.target))) {
                        return;
                    }

                    self.close.call(self, S(dropdownSelector));
                    S(self.scope).off('click.fndtn.dropdown.miss');
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
            },

            toggleObserver: function() {
                var self = this;
                var S = self.S;

                S(self.scope).on('opened.fndtn.dropdown', '[' + self.attr_name() + '-content]', function() {
                    self.settings.opened.call(this);
                });
                S(self.scope).on('closed.fndtn.dropdown', '[' + self.attr_name() + '-content]', function() {
                    self.settings.closed.call(this);
                });
            }
        },

        getDropdown: function(target, settings) {
            var self = this;
            var S = self.S;
            var dropdown;
            settings = settings || self.getSettings(target);
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
                dropdown = S(target).closest(settings.buttonSelector).parent().children('[data-' + self.name + '-content]');
            }

            return dropdown;
        },

        /**
         * Переключает видимость списка
         * @method toggle
         * @param {JQuery} target
         * @param {object} settings
         * @return {JQuery | null} dropdown
         */
        toggle: function(target, settings) {
            var self = this;
            var dropdown = self.getDropdown(target, settings);
            var isOtherTarget;

            if (!dropdown || dropdown.length === 0) {
                return dropdown;
            }

            self.close.call(self, self.S('[' + self.attr_name() + '-content]').not(dropdown));

            if (dropdown.hasClass(settings.activeClass)) {
                isOtherTarget = dropdown.data('target') !== target.get(0);
                self.close.call(self, dropdown);

                if (isOtherTarget) {
                    self.open.call(self, dropdown, target);
                }
            } else {
                self.open.call(self, dropdown, target);
            }
            return dropdown;
        },

        close: function(dropdown) {
            var self = this;
            var S = self.S;

            dropdown.each(function() {
                var target = $(S(this).data('target'));
                var settings;

                if (target.length) {
                    settings = self.getSettings(target);
                } else {
                    settings = self.settings;
                }

                if (S(this).hasClass(settings.activeClass)) {
                    S(this)
                        .off('click.fndtn.dropdown.closeOnClick')
                        .removeClass(settings.activeClass)
                        .removeAttr('style')
                        .removeData('target');

                    if (target.length && settings.respondActiveClassForButton) {
                        target = target.closest(settings.buttonSelector);
                    } else {
                        target = S(this).prev('[' + self.attr_name() + ']');
                    }

                    if (target.length) {
                        target.removeClass(settings.activeClass);
                    }

                    S(this).trigger('closed').trigger('closed.fndtn.dropdown', [S(this)]);
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
            var self = this;
            var S = self.S;
            var settings = self.getSettings(target);
            var button;
            self.setDropdownStyle(dropdown.addClass(settings.activeClass), target);

            if (settings.respondActiveClassForButton) {
                button = target.closest(settings.buttonSelector);
            } else {
                button = dropdown.prev('[' + self.attr_name() + ']');
            }
            button.addClass(settings.activeClass);

            dropdown.data('target', target.get(0)).trigger('opened.fndtn.dropdown', [dropdown, target]);

            if (settings.closeAfterClickOnElement) {
                dropdown.on('click.fndtn.dropdown.closeOnClick', settings.closeAfterClickOnElement, function() {
                    self.close.call(self, S(dropdown));
                    dropdown.off('click.fndtn.dropdown.closeOnClick');
                });
            }

            setTimeout(function() {
                self.eventListener.onClickMissDropdown.call(self);
            }, 150);
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

        getSettings: function(scope) {
            var self = this;
            var S = self.S;
            var settings = S(scope).data(self.attr_name(true) + '-init');

            if (self.settings.runtimeInit && !settings) {
                S(scope).data(self.attr_name(true) + '-init', $.extend({}, self.settings, self.data_options(S(scope))));
                settings = S(scope).data(self.attr_name(true) + '-init');
            } else if (!settings) {
                settings = self.settings;
            }

            return settings;
        },

        /**
         * Устанавливает стили для выпадающего списка
         * @param {JQuery} dropdown
         * @param {JQuery} target
         * @return {JQuery} dropdown
         */
        setDropdownStyle: function(dropdown, target) {
            var settings = this.getSettings(target);
            this.clearIdx();
            this.style(dropdown, target, settings);

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
            var minWidthLikeElement;
            var maxWidthLikeElement;
            var replaceTarget;

            if (settings.replaceTarget) {
                replaceTarget = target.closest(settings.replaceTarget);
                if (replaceTarget.length) {
                    target = replaceTarget;
                }
            }

            if (settings.minWidthLikeElement) {
                minWidthLikeElement = target.closest(settings.minWidthLikeElement);
                if (minWidthLikeElement.length) {
                    dropdownStyles.minWidth = minWidthLikeElement.outerWidth() + 'px';
                    dropdown.css({'minWidth': dropdownStyles.minWidth});
                }
            }

            if (settings.maxWidthLikeElement) {
                maxWidthLikeElement = target.closest(settings.maxWidthLikeElement);
                if (maxWidthLikeElement.length) {
                    dropdownStyles.maxWidth = maxWidthLikeElement.outerWidth() - 10 + 'px';
                    dropdown.css({'maxWidth': dropdownStyles.maxWidth});
                }
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

                var targetSize = {
                    width: target.outerWidth(),
                    height: target.outerHeight()
                };

                var targetOffset = target.offset();

                var closestTarget;
                if (settings.checkPositionRegardingElement) {
                    closestTarget = target.closest(settings.checkPositionRegardingElement);
                    target = closestTarget.length ? closestTarget : target;
                    screenSize.width = target.outerWidth();//TODO: check parent with overflow: hidden
                } else if (settings.minWidthLikeElement && !target.is(settings.minWidthLikeElement)) {
                    closestTarget = target.closest(settings.minWidthLikeElement);
                    target = closestTarget.length ? closestTarget : target;
                }

                //TODO: optimize
                targetSize.width = target.outerWidth();
                targetOffset.left = target.offset().left;

                for (var key in targetOffset) {
                    if (targetOffset.hasOwnProperty(key)) {
                        targetOffset[key] = Math.ceil(targetOffset[key]);
                    }
                }

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
