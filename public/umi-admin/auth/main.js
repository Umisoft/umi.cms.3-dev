define(['auth/templates', 'Handlebars', 'jquery', 'Modernizr', 'Foundation'], function(templates) {
    'use strict';

    /**
     * @param {Object} [authParams]
     * @param {Object} [authParams.accessError] Объект ошибки доступа к ресурсу window.UmiSettings.authUrl
     * @param {Boolean} [authParams.appIsFreeze] Приложение уже загружено
     * @param {HTMLElement} appLayout корневой DOM элемент приложения
     */
    return function(authParams, callback) {
        /**
         * Сбрасываем настройки ajax установленые админ приложением (появляются после выхода из системы)
         */
        $.ajaxSetup({
            headers: {'X-Csrf-Token': null},
            error: function() {
            }
        });

        /**
         * Компонент авторизации
         * @module Auth
         */
        var Auth = {

            /**
             * Метод возвращает ошибки доступа к ресурсу window.UmiSettings.authUrl
             * @method accessError
             * @returns {*|error.message|string}
             */
            accessError: function() {
                if (authParams && authParams.accessError && authParams.accessError.status !== 401 && authParams.accessError.responseJSON) {
                    return authParams.accessError.responseJSON.result.error.message;
                }
            },

            /**
             * Шаблоны авторизации
             * @property TEMPLATES
             */
            TEMPLATES: {},

            /**
             * Шаблоны форм
             * @property forms
             */
            forms: {},

            /**
             * Получает форму для action
             * @param {null|String} action
             * @returns Object $.Deferred
             */
            getForm: function(action, parametrs) {
                var deferred = $.Deferred();
                action = action || 'form';
                var self = this;
                $.get(window.UmiSettings.baseApiURL + '/action/' + action + '?' + parametrs).then(function(results) {
                    self.forms[action] = results.result.form;
                    deferred.resolve();
                });
                return deferred;
            },

            /**
             *
             * @property validator
             */
            validator: {

                /**
                 * При некорректной авторизации метод "трясет" форму словно говоря НЕТ (не используется)
                 * @method shake
                 */
                shake: function() {
                    function shake(id, a, d) {
                        id.style.left = a.shift() + 'px';
                        if (a.length > 0) {
                            setTimeout(function() {
                                shake(id, a, d);
                            }, d);
                        }
                    }

                    var p = [10, 20, 10, 0, -10, -20, -10, 0];
                    p = p.concat(p.concat(p));
                    var i = document.getElementsByClassName('shake-it').item(0);
                    i.style.position = 'relative';
                    shake(i, p, 20);
                },

                /**
                 * Метод валидирует форму
                 * @method check
                 * @param form
                 * @returns {boolean}
                 */
                check: function(form) {
                    var i;
                    var element;
                    var pattern;
                    var valid = true;
                    var removeError = function(el) {
                        el.onfocus = function() {
                            $(this).closest('.columns').removeClass('error');
                        };
                    };

                    var toggleError = function(element, valid) {
                        if (valid) {
                            $(element).closest('.columns').removeClass('error');
                            element.onfocus = null;
                        } else {
                            $(element).closest('.columns').addClass('error');
                            removeError(element);
                            return true;
                        }
                    };

                    for (i = 0; i < form.elements.length; i++) {

                        element = form.elements[i];
                        if ((element.hasAttribute('required') || element.value) && element.hasAttribute('pattern')) {
                            pattern = new RegExp(element.getAttribute('pattern'));
                            if (!pattern.test(element.value)) {
                                valid = false;
                            }
                            if (toggleError(element, valid)) {
                                break;
                            }
                        } else if (element.hasAttribute('required')) {
                            if (!element.value) {
                                valid = false;
                            }
                            if (toggleError(element, valid)) {
                                break;
                            }
                        }
                    }
                    return valid;
                }
            },
            /**
             * Метод загрузает админ приложение после успешной авторизации,
             * и вызывает метод destroy() для приложение Auth
             * @method transition
             */
            transition: function() {
                window.applicationLoading = $.Deferred();
                // Событие при успешном переходе
                document.onmousemove = null;
                var authLayout = document.querySelector('.auth-layout');
                var maskLayout = document.querySelector('.auth-mask');
                $(authLayout).addClass('off is-transition');

                var removeAuth = function() {
                    // Анимация осуществляется с помощью css transition.
                    // Время анимации .7s
                    $(authLayout).addClass('fade-out');
                    setTimeout(function() {
                        authLayout.parentNode.removeChild(authLayout);
                        maskLayout.parentNode.removeChild(maskLayout);
                        Auth.destroy();
                        //Auth = null; TODO: Нужно удалять приложение Auth после авторизации
                    }, 800);
                };

                if (authParams.appIsFreeze) {
                    window.applicationLoading.resolve();
                    $(authParams.appLayout).removeClass('off fade-out fade-out-def');
                    removeAuth();
                } else {
                    require(['application/main'], function(application) {
                        application();
                        window.applicationLoading.then(function() {
                            removeAuth();
                        });
                    });
                }
            },
            /**
             * Старт приложения авторизации
             * @method init
             */
            init: function() {
                var self = this;
                var assetsUrl = window.UmiSettings && window.UmiSettings.assetsUrl;

                /**
                 * Регистрация хелпера ifCond, позволяющего сравнивать значения в шаблоне
                 * method registerHelper
                 */
                Handlebars.registerHelper('ifCond', function(v1, v2, options) {
                    if (v1 === v2) {
                        return options.fn(this);
                    }
                    return options.inverse(this);
                });

                /**
                 * Загружает шаблоны определеные в templates.js
                 * method templates
                 */
                templates(self);


                var currentLocale = self.localStorage('locale');
                var options;
                var currentLocaleLabel;

                if (!currentLocale && window.UmiSettings && window.UmiSettings.hasOwnProperty('locale')) {
                    currentLocale = window.UmiSettings.locale;
                }

                self.getForm('form', 'locale=' + currentLocale).then(function() {
                    try {
                        options = self.forms.form.elements[2].choices;
                        currentLocale = currentLocale || options[0].value;

                        for (var i = 0; i < options.length; i++) {
                            if (options[i].value === currentLocale) {
                                options[i].active = 'true';
                                currentLocaleLabel = options[i].label || options[i].value;
                            }
                        }
                    } catch (error) {
                        console.warn(error);
                    }

                    // Проверяем есть ли шаблон и если нет то собираем его
                    if (!document.querySelector('.auth-layout')) {
                        document.body.insertAdjacentHTML('beforeend', self.TEMPLATES.app({
                            assetsUrl: assetsUrl,
                            outlet: self.TEMPLATES.index(
                                {
                                    accessError: self.accessError,
                                    form: self.forms.form,
                                    currentLocale: currentLocale,
                                    currentLocaleLabel: currentLocaleLabel
                                }
                            )
                        }));
                    }
                    $('body').removeClass('loading');

                    $(document.querySelector('.auth-layout')).foundation();

                    $('input[name="login"]').focus();

                    var bubbles = document.querySelector('.bubbles');
                    var bubblesFront = document.querySelector('.bubbles-front');
                    var parallax = function(event) {
                        var bodyWidth = document.body.offsetWidth;
                        var bodyHeight = document.body.offsetHeight;
                        var deltaX = 0.04;
                        var deltaY = 0.04;
                        var left = (bodyWidth / 2 - event.clientX) * deltaX;
                        var top = (bodyHeight / 2 - event.clientY) * deltaY;
                        bubbles.style.marginLeft = left + 'px';
                        bubbles.style.marginTop = top + 'px';
                        bubblesFront.style.marginLeft = left * 0.2 + 'px';
                        bubblesFront.style.marginTop = top * 0.2 + 'px';
                    };

                    var bubblesHidden = true;
                    document.onmousemove = function(event) {
                        if (bubblesHidden) {
                            bubbles.className = 'bubbles visible';
                            bubblesFront.className = 'bubbles-front visible';
                            bubblesHidden = false;
                        }
                        parallax(event);
                    };

                    $(document).on('click.umi.auth', '.locale-select', function(event) {
                        event.preventDefault();

                        var locale = $(this).data('locale');

                        if (locale && self.localStorage('locale') !== locale) {
                            self.localStorage('locale', locale);
                            window.location.reload();
                        }
                    });

                    $(document).on('click.umi.auth', '.close', function() {
                        this.parentNode.parentNode.removeChild(this.parentNode);
                        return false;
                    });

                    var errorsBlock = document.querySelector('.errors-list');

                    $(document).on('submit.umi.auth', 'form', function() {
                        if (!self.validator.check(this)) {
                            return false;
                        }
                        var submitButton = this.querySelector('input[type="submit"]');
                        $(submitButton).addClass('loading');
                        var submit = this.elements.submit;
                        submit.setAttribute('disabled', 'disabled');
                        var data = $(this).serialize();
                        var action = this.getAttribute('action');
                        var deffer = $.post(action, data);

                        var authFail = function(error) {
                            $(submitButton).removeClass('loading');
                            submit.removeAttribute('disabled');
                            var errorList = {error: error.responseJSON.result.error.message};
                            errorsBlock.innerHTML = self.TEMPLATES.errors(errorList);
                            $(errorsBlock).children('.alert-box').addClass('visible');
                        };

                        deffer.done(function(data) {
                            if (data.result) {
                                $.ajaxSetup({
                                    headers: {'X-Csrf-Token': data.result.token}
                                });

                                var mergedSettings = $.extend({}, window.UmiSettings, data.result);
                                if ('Ember' in window) {
                                    Ember.set(window, 'UmiSettings', mergedSettings);
                                } else {
                                    window.UmiSettings = mergedSettings;
                                }
                            }

                            self.transition();
                        });
                        deffer.fail(function(error) {
                            authFail(error);
                        });
                        return false;
                    });

                    if (typeof callback === 'function') {
                        callback.call(self);
                    }
                });
            },

            destroy: function() {
                $(document).off('.umi.auth');
            },

            localStorage: function(key, val) {
                if (typeof val === 'undefined') {
                    return window.localStorage.getItem(key);
                } else {
                    return window.localStorage.setItem(key, val);
                }
            }
        };

        Auth.init();
    };
});