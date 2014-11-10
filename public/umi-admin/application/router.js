define([], function() {
    'use strict';

    return function(UMI) {

        /**
         @module UMI
         @submodule Router
         **/
        UMI.Router.reopen({
            location: 'history',
            rootURL: window.UmiSettings.baseURL
        });

        /**
         @class map
         @constructor
         */
        UMI.Router.map(function() {
            this.resource('module', {path: '/:module'}, function() {
                this.route('errors', {path: '/:status'});
                this.resource('component', {path: '/:component'}, function() {
                    this.route('errors', {path: '/:status'});
                    this.resource('context', {path: '/:context'}, function() {
                        this.resource('action', {path: '/:action'});
                    });
                });
            });
        });

        /**
         * @class ApplicationRoute
         * @extends Ember.Route
         * @uses modelsFactory
         */
        UMI.ApplicationRoute = Ember.Route.extend({

            /**
             Инициализирует модели данных, модули и компоненты для Dock.

             @method model
             @return
             **/
            model: function(params, transition) {
                var self = this;
                var promise;

                try {
                    if (!UmiSettings.baseApiURL) {
                        throw new Error('BaseApiURL was not defined in UmiSettings.');
                    }

                    promise = $.get(UmiSettings.baseApiURL).then(function(results) {
                        if (results && results.result) {
                            var result = results.result;
                            self.controllerFor('application').set('settings', result);

                            if (result.collections) {
                                UMI.modelsFactory(result.collections);
                            }

                            if (result.modules) {
                                self.controllerFor('application').set('modules', result.modules);
                            }

                            if (result.i18n) {
                                UMI.i18n.setDictionary(result.i18n);
                            }
                        } else {
                            try {
                                throw new Error('Resource "' + UmiSettings.baseApiURL + '" not supported.');
                            } catch (error) {
                                transition.abort();
                                transition.send('dialogError', error);
                            }
                        }
                    }, function(error) {
                        var becameError = new Error(error);
                        error.stack = becameError.stack;
                        transition.send('dialogError', error);
                    });
                } catch (error) {
                    transition.abort();
                    transition.send('dialogError', error);
                } finally {
                    return promise;
                }
            },

            /**
             * Сохраняет обьект
             * @method saveObject
             * @param {Object} params Объект аргументов
             * params.object - сохраняемый объект (полностью объект системы)
             * params.handler - элемент (кнопка) вызвавший событие сохранение - JS DOM Element
             * @returns {promise} возвращает promise результатом которого является true в случае успешного сохранения
             */
            saveObject: function(params) {
                var self = this;
                var deferred = Ember.RSVP.defer();

                try {
                    params.object.save().then(
                        function() {
                            params.object.updateRelationshipsMap();

                            if (params.handler) {
                                $(params.handler).removeClass('loading');
                            }

                            deferred.resolve(params.object);
                        },

                        function(results) {
                            try {
                                results = results || {};

                                if (params.handler) {
                                    $(params.handler).removeClass('loading');
                                }

                                var store = self.get('store');
                                var collection;
                                var object;
                                var invalidObjects = Ember.get(results, 'responseJSON.result.error.invalidObjects');
                                var invalidObject;
                                var invalidProperties;
                                var i;

                                if (Ember.typeOf(invalidObjects) === 'array') {*******
                                    if (params.object.get('isValid')) {
                                        params.object.send('becameInvalid');
                                    }

                                    for (i = 0; i < invalidObjects.length; i++) {
                                        invalidObject = invalidObjects[i];
                                        invalidProperties = Ember.get(invalidObject, 'invalidProperties');
                                        collection = store.all(invalidObject.collection);
                                        object = collection.findBy('guid', invalidObject.guid);

                                        if (object) {
                                            object.setInvalidProperties(invalidProperties);
                                        }
                                    }
                                }
                                deferred.reject();
                            } catch (error) {
                                self.send('backgroundError', error);
                            }
                        }
                    );
                } catch (error) {
                    self.send('backgroundError', error);
                } finally {
                    return deferred.promise;
                }
            },

            beforeAdd: function(params) {
                var self = this;
                return self.saveObject(params).then(function(addObject) {
                    if (addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic') {
                        var parent = addObject.get('parent');

                        if (parent && 'isFulfilled' in parent) {
                            return parent.then(function(parent) {
                                var store = self.get('store');
                                var collectionName = parent.constructor.typeKey;
                                var request = UMI.OrmHelper.buildRequest(store, collectionName, ['childCount']);
                                request.filters = {id: 'equals(' + parent.get('id') + ')'};
                                return store.updateCollection(collectionName, request).then(function() {
                                    parent.trigger('needReloadHasMany', 'add', addObject);

                                    return addObject;
                                });
                            });
                        } else {
                            self.controllerFor('component').trigger('needReloadRootElements', 'add', addObject);

                            return addObject;
                        }
                    } else {
                        return addObject;
                    }
                });
            },

            actions: {
                willTransition: function() {
                    UMI.notification.removeAll();
                    this.send('showLoader');
                },

                didTransition: function() {
                    this.send('hideLoader');
                },

                error: function(error, transition) {
                    this.send('hideLoader');
                    return true;
                },

                showLoader: function() {
                    this.controllerFor('application').set('routeIsTransition', true);
                },

                hideLoader: function() {
                    this.controllerFor('application').set('routeIsTransition', false);
                },

                logout: function() {
                    var self = this;
                    var applicationLayout = document.querySelector('.umi-main-view');
                    var maskLayout = document.createElement('div');
                    maskLayout.className = 'auth-mask';
                    maskLayout = document.body.appendChild(maskLayout);

                    self.send('showLoader');
                    $(applicationLayout).addClass('off is-transition');

                    $.post(UmiSettings.baseApiURL + '/action/logout').then(function() {
                        require(['auth/main'], function(auth) {
                            auth({appIsFreeze: true, appLayout: applicationLayout}, function() {
                                $(applicationLayout).addClass('fade-out');

                                Ember.run.later('', function() {
                                    $(applicationLayout).addClass('fade-out-def').removeClass('is-transition fade-out');
                                    maskLayout.parentNode.removeChild(maskLayout);
                                    self.send('hideLoader');
                                }, 800);
                            });
                        });
                    });
                },

                dialogError: function(error) {
                    var settings = this.parseError(error);

                    if (settings !== 'silence') {
                        settings.close = true;
                        settings.title = error.status + '. ' + error.statusText;
                        UMI.dialog.open(settings).then();
                    }
                },

                /**
                 Метод генерирует фоновую ошибку (красный tooltip)
                 @method backgroundError
                 @property error Object {status: status, title: title, content: content, stack: stack}
                 */
                backgroundError: function(error) {
                    var settings = this.parseError(error);

                    if (settings !== 'silence') {
                        settings.type = 'error';
                        settings.duration = false;
                        UMI.notification.create(settings);
                    }
                },

                /**
                 Метод генерирует ошибку (выводится вместо шаблона)
                 @method templateLogs
                 @property error Object {status: status, title: title, content: content, stack: stack}
                 */
                templateLogs: function(error, parentRoute) {
                    parentRoute = parentRoute || 'module';
                    var dataError = this.parseError(error);

                    if (dataError !== 'silence') {
                        var model = Ember.Object.create(dataError);
                        this.intermediateTransitionTo(parentRoute + '.errors', model);
                    }
                },

                showPopup: function(params) {
                    Ember.warn('Param "popupType" is required for create popup.',
                        Ember.get(params, 'viewParams.popupType'));
                    var controller = this.controllerFor('popup');

                    if (Ember.typeOf(params) === 'object') {
                        controller.setProperties(params);
                    }

                    return this.render('popup', {
                        into: 'application',
                        outlet: 'popup',
                        controller: controller
                    });
                },

                closePopup: function() {
                    this.get('container').lookup('view:popup').send('closePopup');
                },

                removePopupLayout: function() {
                    return this.disconnectOutlet({
                        outlet: 'popup',
                        parentView: 'application'
                    });
                },

                /// global actions
                /**
                 * Сохраняет обьект вызывая метод saveObject
                 * @method save
                 */
                save: function(params) {
                    this.saveObject(params);
                },

                saveAndGoBack: function(params) {
                    var self = this;

                    self.saveObject(params).then(function(isSaved) {
                        if (isSaved) {
                            self.send('backToFilter');
                        }
                    });
                },

                add: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function(addObject) {
                        self.send('edit', addObject);
                    });
                },

                addAndGoBack: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function() {
                        self.send('backToFilter');
                    });
                },

                addAndCreate: function(params) {
                    var self = this;

                    return self.beforeAdd(params).then(function(addObject) {
                        var behaviour = {type: addObject.get('type')};

                        if (addObject.store.metadataFor(addObject.constructor.typeKey)
                            .collectionType === 'hierarchic') {
                            return addObject.get('parent').then(function(parent) {
                                self.send('create', parent, behaviour);
                            });
                        } else {
                            self.send('create', addObject, behaviour);
                        }
                    });
                },

                switchActivity: function(object) {
                    try {
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchActivitySource = this.controllerFor('component').get('settings').actions[(
                            object.get('active') ? 'de' : '') + 'activate'].source;
                        switchActivitySource = UMI.Utils.replacePlaceholder(object, switchActivitySource);

                        $.ajax({
                            url: switchActivitySource,
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function() {
                            object.reload();
                        });
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                },

                create: function(params) {
                    var type = params.behaviour.type;
                    var parentObject = params.object;
                    var contextId = 'root';

                    if (parentObject.constructor.typeKey) {
                        var meta = this.store.metadataFor(parentObject.constructor.typeKey) || {};

                        if (meta.collectionType === 'hierarchic') {
                            contextId = parentObject.get('id');
                        }
                    }

                    this.transitionTo('action', contextId, 'createForm', {queryParams: {'type': type}});
                },

                edit: function(object) {
                    this.transitionTo('action', object.get('id'), 'editForm');
                },

                viewOnSite: function(object) {
                    var link;

                    if (object) {
                        link = object._data.meta.pageUrl;
                    } else {
                        link = window.UmiSettings.baseSiteURL;
                    }

                    link = window.location.host + link;
                    var tab = window.open('//' + link.replace('\/\/', '\/'), '_blank');
                    tab.focus();
                },

                /**
                 * Восстанавливает объект из корзины
                 * @method untrash
                 * @param object
                 * @returns {*|Promise}
                 */
                untrash: function(object) {
                    var self = this;
                    var promise;
                    var serializeObject;
                    var untrashAction;
                    var collectionName;
                    var store = self.get('store');
                    var objectId;

                    try {
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        collectionName = object.constructor.typeKey;
                        untrashAction = self.controllerFor('component').get('settings').actions.untrash;

                        if (!untrashAction) {
                            throw new Error('Action untrash not supported for component.');
                        }

                        promise = $.ajax(
                            {
                                url: untrashAction.source + '?id=' + objectId + '&collection=' + collectionName,
                                type: 'POST',
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }
                        ).then(
                            function() {
                                var invokedObjects = [];
                                invokedObjects.push(object);
                                var collection = store.all(collectionName);

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var mpath = object.get('mpath');
                                    var parent;
                                    if (Ember.typeOf(mpath) === 'array' && mpath.length) {
                                        for (var i = 0; i < mpath.length; i++) {
                                            parent = collection.findBy('id', mpath[i] + '');
                                            if (parent) {
                                                invokedObjects.push(parent);
                                            }
                                        }
                                    }
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" ' +
                                    UMI.i18n.getTranslate('Restored').toLowerCase() + '.'};
                                UMI.notification.create(settings);
                            },
                            function() {
                                var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" ' +
                                    UMI.i18n.getTranslate('Not restored').toLowerCase() + '.'};
                                UMI.notification.create(settings);
                            }
                        );
                    } catch (error) {
                        self.send('backgroundError', error);
                    } finally {
                        return promise;
                    }
                },

                /**
                 * Удаляет объект (перемещает в корзину)
                 * @method trash
                 * @param object
                 * @returns {*|Promise}
                 */
                trash: function(object) {
                    var self = this;
                    var store = self.get('store');
                    var promise;
                    var serializeObject;
                    var isActiveContext;
                    var trashAction;
                    var objectId;

                    try {
                        objectId = object.get('id');
                        serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        isActiveContext = this.modelFor('context') === object;
                        trashAction = this.controllerFor('component').get('settings').actions.trash;

                        if (!trashAction) {
                            throw new Error('Action trash not supported for component.');
                        }

                        promise = $.ajax(
                            {
                                url: trashAction.source + '?id=' + objectId,
                                type: 'POST',
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }
                        ).then(
                            function() {
                                var collectionName = object.constructor.typeKey;
                                var invokedObjects = [];
                                invokedObjects.push(object);

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var collection = store.all(collectionName);

                                    collection.find(function(item) {
                                        var mpath = item.get('mpath') || [];

                                        if (mpath.contains(parseFloat(objectId)) && mpath.length > 1) {
                                            invokedObjects.push(item);
                                        }
                                    });
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {
                                    type: 'success',
                                    'content': UMI.i18n.getTranslate('Moved to trash') + ': "' +
                                        object.get('displayName') + '".'
                                };
                                UMI.notification.create(settings);

                                if (isActiveContext) {
                                    self.send('backToFilter');
                                }
                            },
                            function() {
                                var settings = {
                                    type: 'error',
                                    'content': UMI.i18n.getTranslate('Failed to move in the trash') + ': "' +
                                        object.get('displayName') + '".'
                                };

                                UMI.notification.create(settings);
                            }
                        );
                    } catch (error) {
                        this.send('backgroundError', error);
                    } finally {
                        return promise;
                    }
                },

                /**
                 * Спрашивает пользователя и в случае положительного ответа безвозвратно удаляет объект
                 * @method delete
                 * @param object
                 * @returns {*|Promise}
                 */
                'delete': function(object) {
                    var self = this;
                    var isActiveContext = this.modelFor('context') === object;
                    var data = {
                        'close': false,
                        'title': UMI.i18n.getTranslate('Delete') + ' "' + object.get('displayName') + '".',
                        'content': '<div>' +
                            UMI.i18n.getTranslate('The object will be deleted permanently, continue anyway') +
                            '?</div>',
                        'confirm': UMI.i18n.getTranslate('Delete'),
                        'reject': UMI.i18n.getTranslate('Cancel')
                    };

                    return UMI.dialog.open(data).then(
                        function() {
                            var collectionName = object.constructor.typeKey;
                            var store = object.get('store');
                            var objectId = object.get('id');

                            return object.destroyRecord().then(function() {
                                var invokedObjects = [];

                                if (store.metadataFor(collectionName).collectionType === 'hierarchic') {
                                    var collection = store.all(collectionName);

                                    collection.find(function(item) {
                                        var mpath = item.get('mpath') || [];

                                        if (mpath.contains(parseFloat(objectId)) && mpath.length > 1) {
                                            invokedObjects.push(item);
                                        }
                                    });
                                }

                                invokedObjects.invoke('unloadRecord');
                                var settings = {
                                    type: 'success',
                                    'content': '"' + object.get('displayName') + '" ' +
                                        UMI.i18n.getTranslate('Successfully removed').toLowerCase() + '.'
                                };
                                UMI.notification.create(settings);

                                if (isActiveContext) {
                                    self.send('backToFilter');
                                }
                            }, function() {
                                var settings = {
                                    type: 'error',
                                    'content': '"' + object.get('displayName') + '" ' +
                                        UMI.i18n.getTranslate('Failed to delete').toLowerCase() + '.'
                                };
                                UMI.notification.create(settings);
                            });
                        },
                        function() {}
                    );
                },
                /**
                 * Возвращает к списку
                 */
                backToFilter: function() {
                    this.transitionTo('context', 'root');
                },

                /**
                 * Импорт Rss ленты
                 */
                importFromRss: function(object) {
                    try {
                        var data = {
                            'content': '<div class="text-center"><i class="animate animate-loader-40"></i> ' +
                                UMI.i18n.getTranslate('Waiting') + '..</div>',
                            'close': false,
                            'type': 'check-process'
                        };

                        UMI.dialog.open(data).then(
                            function() {},
                            function() {}
                        );

                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));

                        var importFromRssSource = this.controllerFor('component').get('settings').actions.importFromRss
                            .source;
                        $.ajax({
                            url: importFromRssSource,
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(
                            function(results) {
                                var model = UMI.dialog.get('model');
                                model.setProperties(
                                    {
                                        'content': Ember.get(results, 'result.importFromRss.message'),
                                        'close': true,
                                        'reject': UMI.i18n.getTranslate('Close'),
                                        'type': null
                                    }
                                );
                            }
                        );
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                },

                /**
                 *
                 */
                switchRobots: function(object, currentState, defer) {
                    try {
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchRobotsSource = Ember.get(this.controllerFor('component').get('settings'), 'actions.' +
                            (currentState ? 'dis' : '') + 'allowRobots.source');

                        $.ajax({
                            url: switchRobotsSource + '?id=' + object.get('id'),
                            type: 'POST',
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function() {
                            defer.resolve();
                        });
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                }
            },

            /**
             Метод парсит ошибку и возвпращает ее в виде объекта (ошибки с Back-end)
             @method parseError
             @return Object|null|String {status: status, title: title, content: content, stack: stack}
             */
            parseError: function(error) {
                error = error || {};
                var status = Ember.get(error, 'status');
                var stack = Ember.get(error, 'stack');

                var parsedError = {
                    status: status,
                    title: UMI.i18n.getTranslate(error.statusText),
                    stack: stack
                };

                if (status === 403 || status === 401) {
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    this.send('logout');
                    return 'silence';
                }

                var content;
                var responseError;
                if (error.hasOwnProperty('responseJSON')) {
                    responseError = Ember.get(error, 'responseJSON.result.error');
                    if (responseError) {
                        content = Ember.get(responseError, 'message');
                    }
                } else {
                    content = Ember.get(error, 'responseText') || Ember.get(error, 'message');
                }
                parsedError.content = content;
                return parsedError;
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.IndexRoute = Ember.Route.extend({
            /**
             Выполняет редирект на роут `Module`.
             @method redirect
             @return
             **/
            redirect: function(model, transition) {
                var firstChild;
                if (transition.targetName === this.routeName) {
                    try {
                        firstChild = this.controllerFor('application').get('modules')[0];

                        if (!firstChild) {
                            throw new Error(UMI.i18n.getTranslate('Modules are not available.'));
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);//TODO: Проверить вывод ошибок
                    } finally {//TODO: Нужно дать пользователю выбрать компонент
                        return this.transitionTo('module', Ember.get(firstChild, 'name'));
                    }
                }
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.ModuleRoute = Ember.Route.extend({
            model: function(params) {
                var deferred;
                var modules;
                var module;

                try {
                    deferred = Ember.RSVP.defer();
                    modules = this.controllerFor('application').get('modules');
                    module = modules.findBy('name', params.module);

                    if (module) {
                        deferred.resolve(module);
                    } else {
                        throw new Error(UMI.i18n.getTranslate('Module') + ' "' + params.module + '" ' +
                            UMI.i18n.getTranslate('not found') + '.');
                    }
                } catch (error) {
                    deferred.reject(error);
                } finally {
                    return deferred.promise;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var self = this;
                    var deferred;
                    var firstChild;

                    try {
                        deferred = Ember.RSVP.defer();
                        firstChild = Ember.get(model, 'components')[0];

                        if (firstChild) {
                            deferred.resolve(self.transitionTo('component', Ember.get(firstChild, 'name')));
                        } else {
                            throw new Error(UMI.i18n.getTranslate('For') + ' ' +
                                UMI.i18n.getTranslate('Module').toLowerCase() + '"' + Ember.get(model, 'name') + '" ' +
                                UMI.i18n.getTranslate('Components').toLowerCase() + ' ' +
                                UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                        }
                    } catch (error) {
                        deferred.reject(Ember.run.next(self, function() {
                            this.send('templateLogs', error);
                        }));
                    } finally {
                        return deferred.promise;
                    }
                }
            },

            serialize: function(model) {
                return {module: Ember.get(model, 'slug')};
            }
        });

        UMI.ComponentRoute = Ember.Route.extend({
            /**
             * @method model
             * @param params
             * @param transition
             * @returns {*}
             */
            model: function(params, transition) {
                var self = this;
                var deferred;
                var components;
                var model;
                var componentName = transition.params.component.component;

                try {
                    deferred = Ember.RSVP.defer();
                    components = Ember.get(this.modelFor('module'), 'components');

                    // filterBy
                    for (var i = 0; i < components.length; i++) {
                        if (components[i].name === componentName) {
                            model = components[i];
                            break;
                        }
                    }

                    if (model) {
                        /**
                         * Ресурс компонента
                         */
                        $.ajax({
                            type: 'GET',
                            url: Ember.get(model, 'resource'),
                            global: false,
                            success: function(results) {
                                var componentController = self.controllerFor('component');

                                if (Ember.typeOf(results) === 'object' && Ember.get(results, 'result.layout')) {
                                    var settings = results.result.layout;
                                    var dataSource = Ember.get(settings, 'dataSource') || '';
                                    componentController.set('settings', settings);
                                    componentController.set('selectedContext', Ember.get(transition, 'params.context') ?
                                        Ember.get(transition, 'params.context.context') : 'root');

                                    if (Ember.get(dataSource, 'type') === 'lazy') {
                                        $.get(Ember.get(settings, 'actions.' + Ember.get(dataSource, 'action') +
                                                '.source')).then(
                                            function(results) {
                                                var data = Ember.get(results, 'result.' +
                                                    Ember.get(dataSource, 'action') + '.objects');

                                                Ember.set(dataSource, 'objects', data);
                                                deferred.resolve(model);
                                            },
                                            function(error) {
                                                deferred.reject(transition.send('backgroundError', error));
                                            }
                                        );
                                    } else {
                                        deferred.resolve(model);
                                    }
                                } else {
                                    var error = new Error(UMI.i18n.getTranslate('Resource') + ' "' +
                                        Ember.get(model, 'resource') + '" ' +
                                        UMI.i18n.getTranslate('Incorrect').toLowerCase() + '.');
                                    transition.send('backgroundError', error);
                                    deferred.reject();
                                }
                            },

                            error: function(error) {
                                deferred.reject(Ember.run.next(this, function() {
                                    transition.send('templateLogs', error);
                                }));
                            }
                        });
                    } else {
                        throw new URIError(UMI.i18n.getTranslate('Component') + ' "' + componentName + '" ' +
                            UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                    }
                } catch (error) {
                    deferred.reject(Ember.run.next(this, function() {
                        transition.send('templateLogs', error);
                    }));
                } finally {
                    return deferred.promise;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var context;

                    try {
                        var emptyControl = this.controllerFor('component')
                            .get('settings.contents.emptyContext.redirect');

                        if (emptyControl) {
                            context = Ember.get(emptyControl, 'params.slug');
                        } else {
                            context = 'root';
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);
                    } finally {
                        return this.transitionTo('context', context);
                    }
                }
            },

            serialize: function(model) {
                return {
                    component: Ember.get(model, 'name')
                };
            },

            /**
             * Отрисовываем компонент
             * Если есть sideBar - его тоже отрисовываем
             * @param controller
             */
            renderTemplate: function(controller) {
                this.render();

                if (controller.get('sideBarControl')) {
                    try {
                        this.render(controller.get('sideBarControl.name'), {
                            into: 'component',
                            outlet: 'sideBar'
                        });
                    } catch (error) {
                        this.send('templateLogs', error, 'component');
                    }
                }
            }
        });

        /**
         * Отрисовка выбранного объекта
         * @type {*|void|Object}
         */
        UMI.ContextRoute = Ember.Route.extend({
            model: function(params, transition) {
                var componentController;
                var collection;
                var RootModel;
                var model;

                try {
                    componentController = this.controllerFor('component');
                    collection = componentController.get('dataSource');
                    componentController.set('selectedContext', params.context);// TODO: зачем это вообще нужно?

                    if (params.context === 'root') {
                        RootModel = Ember.Object.extend({});
                        model = new Ember.RSVP.Promise(function(resolve) {
                            resolve(RootModel.create({'id': 'root', type: 'base'}));
                        });
                    } else {
                        switch (Ember.get(collection, 'type')) {
                            case 'static':
                            case 'lazy':
                                model = new Ember.RSVP.Promise(function(resolve, reject) {
                                    var objects = Ember.get(collection, 'objects');
                                    var object;
                                    // filterBy
                                    for (var i = 0; i < objects.length; i++) {
                                        if (objects[i].id === params.context) {
                                            object = objects[i];
                                            break;
                                        }
                                    }

                                    if (object) {
                                        resolve(object);
                                        resolve(object);
                                    } else {
                                        reject(UMI.i18n.getTranslate('Object') + ' ' +
                                            UMI.i18n.getTranslate('With').toLowerCase() + ' ID ' + params.context +
                                            ' ' + UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                                    }
                                });
                                break;
                            case 'collection':
                                if (this.store.hasRecordForId(Ember.get(collection, 'name'), params.context)) {
                                    model = this.store.getById(Ember.get(collection, 'name'), params.context);
                                } else {
                                    var self = this;
                                    model = new Ember.RSVP.Promise(function(resolve, reject) {
                                        var request = UMI.OrmHelper.buildRequest(self.store,
                                            Ember.get(collection, 'name'), ['displayName', 'trashed']);

                                        request['filters[id]'] = params.context;
                                        self.store.updateCollection(Ember.get(collection, 'name'), request).then(
                                            function(results) {
                                                var object = results.get('firstObject');
                                                if (object && !object.get('trashed')) {
                                                    resolve(object);
                                                } else {
                                                    var errorMessage = UMI.i18n.getTranslate('Object') + ' ' +
                                                        UMI.i18n.getTranslate('With').toLowerCase() +
                                                        ' "id" ' + params.context + ' ' +
                                                        UMI.i18n.getTranslate('Not found').toLowerCase() + '.';

                                                    reject(transition.send('templateLogs', {
                                                        statusText: UMI.i18n.getTranslate('Object') + ' ' +
                                                            UMI.i18n.getTranslate('Not found').toLowerCase(),
                                                        message: errorMessage
                                                    }, 'component'));
                                                }
                                            }
                                        );
                                    });
                                }
                                break;
                            default:
                                throw new Error(UMI.i18n.getTranslate('Incorrect') + ' dataSource.');
                        }
                    }
                } catch (error) {
                    Ember.run.next(this, function() {
                        transition.send('templateLogs', error, 'component');
                    });
                } finally {
                    return model;
                }
            },

            redirect: function(model, transition) {
                if (transition.targetName === this.routeName + '.index') {
                    var control;
                    var controlName;

                    try {
                        control = this.controllerFor('component').get('contentControls')[0];
                        controlName = Ember.get(control, 'id');
                        if (!controlName) {
                            throw new Error(UMI.i18n.getTranslate('The actions for this context is not available') +
                                '.');
                        }
                    } catch (error) {
                        transition.send('backgroundError', error);
                    } finally {
                        return this.transitionTo('action', controlName);
                    }
                }
            },

            serialize: function(model) {
                if (model) {
                    return {context: Ember.get(model, 'id')};
                }
            }
        });

        UMI.ActionRoute = Ember.Route.extend({
            queryParams: {
                type: {
                    refreshModel: true,
                    replace: true
                }
            },

            model: function(params, transition) {
                var self = this;
                var actionName;
                var contextModel;
                var componentController;
                var contentControls;
                var contentControl;
                var routeData;
                var createdParams;
                var deferred;
                var actionResource;
                var actionResourceName;
                var controlObject;

                try {
                    deferred = Ember.RSVP.defer();
                    actionName = params.action;
                    contextModel = this.modelFor('context');
                    componentController = this.controllerFor('component');
                    contentControls = componentController.get('contentControls');
                    contentControl = contentControls.findBy('id', actionName);

                    if (!contentControl) {
                        throw new Error(UMI.i18n.getTranslate('Action') + ' "' + actionName + '" ' +
                            UMI.i18n.getTranslate('Not found').toLowerCase() + '.');
                    }

                    contentControl = $.extend({}, contentControl);

                    routeData = {
                        'object': contextModel,
                        'control': contentControl
                    };
                    actionResourceName = Ember.get(contentControl, 'params.action');

                    if (!actionResourceName) {
                        deferred.resolve(routeData);
                    } else {
                        actionResource = Ember.get(componentController, 'settings.actions.' + actionResourceName +
                            '.source');

                        if (actionResource) {
                            controlObject = routeData.object;

                            if (actionName === 'createForm') {
                                createdParams = {};

                                if (componentController.get('dataSource.type') === 'collection') {
                                    var meta = this.store.metadataFor(componentController.get('dataSource.name')) || {};
                                    if (Ember.get(meta, 'collectionType') === 'hierarchic' &&
                                        routeData.object.get('id') !== 'root') {
                                        createdParams.parent = contextModel;
                                    }
                                }

                                if (transition.queryParams.type) {
                                    createdParams.type = transition.queryParams.type;
                                }

                                routeData.createObject = self.store.createRecord(
                                    componentController.get('dataSource.name'), createdParams
                                );

                                controlObject = routeData.createObject;
                            }

                            actionResource = UMI.Utils.replacePlaceholder(controlObject, actionResource);

                            $.ajax({
                                type: 'GET',
                                url: actionResource,
                                global: false,
                                success: function(results) {
                                    var dynamicControl;
                                    var dynamicControlName;

                                    if (actionName === 'dynamic') {
                                        dynamicControl = Ember.get(results, 'result') || {};

                                        for (var key in dynamicControl) {
                                            if (dynamicControl.hasOwnProperty(key)) {
                                                dynamicControlName = key;
                                            }
                                        }

                                        dynamicControl = dynamicControl[dynamicControlName] || {};
                                        dynamicControl.name = dynamicControlName;

                                        UMI.Utils.objectsMerge(routeData.control, dynamicControl);
                                    } else {
                                        if (actionName === 'createForm') {
                                            routeData.createObject.set('guid',
                                                Ember.get(results, 'result.' + actionResourceName + '.guid'));
                                            Ember.set(routeData.control, 'meta',
                                                Ember.get(results, 'result.' + actionResourceName + '.form'));
                                        } else {
                                            Ember.set(routeData.control, 'meta',
                                                Ember.get(results, 'result.' + actionResourceName));
                                        }
                                    }
                                    deferred.resolve(routeData);
                                },

                                error: function(error) {
                                    deferred.reject(transition.send('templateLogs', error, 'component'));
                                }
                            });
                        } else {
                            throw new Error(UMI.i18n.getTranslate('Action') + ' ' + Ember.get(contentControl, 'name') +
                                ' ' + UMI.i18n.getTranslate('Not available for the selected context').toLowerCase() +
                                '.');
                        }
                    }
                } catch (error) {
                    deferred.reject(transition.send('templateLogs', error, 'component'));
                } finally {
                    return deferred.promise;
                }
            },

            afterModel: function(model, transition) {
                try {
                    var defer = Ember.RSVP.defer();
                    var control = this.controllerFor('action');
                    var controlName = Ember.get(model, 'control.name');
                    var controlPromiseService = control[controlName + 'PromiseService'];

                    if (controlPromiseService) {
                        if (Ember.canInvoke(controlPromiseService, 'execute')) {
                            controlPromiseService.execute(model).then(
                                function(result) {
                                    defer.resolve(result);
                                },

                                function(error) {
                                    defer.reject(error);
                                }
                            );
                        } else {
                            defer.resolve();
                        }
                    } else {
                        defer.resolve();
                    }
                    return defer.promise;
                } catch (error) {
                    transition.send('backgroundError', error);
                }
            },

            serialize: function(routeData) {
                if (Ember.get(routeData, 'control')) {
                    return {action: Ember.get(routeData, 'control.id')};
                }
            },

            renderTemplate: function(controller, routeData) {
                try {
                    var templateType = Ember.get(routeData, 'control.name');

                    this.render(templateType, {
                        controller: controller
                    });
                } catch (error) {
                    this.send('templateLogs', error, 'component');
                }
            },

            setupController: function(controller, model) {
                this._super(controller, model);

                if (model.createObject) {
                    Ember.set(model, 'object', model.createObject);
                    Ember.set(model, 'createObject', null);
                }

                controller.set('model', model);
            },

            actions: {
                /**
                 Метод вызывается при уходе с роута.
                 @event willTransition
                 @param {Object} transition
                 */
                willTransition: function(transition) {
                    if (transition.params.action && transition.params.action.action !== 'createForm') {
                        this.get('controller').set('typeName', null);
                    }

                    var model = Ember.get(this.modelFor('action'), 'object');

                    if (Ember.get(model, 'isNew')) {
                        model.deleteRecord();
                    }

                    if (Ember.get(model, 'isDirty')) {
                        transition.abort();
                        var data = {
                            'close': false,
                            'title': UMI.i18n.getTranslate('The changes were not saved') + '.',
                            'content': UMI.i18n.getTranslate('Transition:unsaved changes') + '?',
                            'confirm': UMI.i18n.getTranslate('Stay on the page'),
                            'reject': UMI.i18n.getTranslate('Continue without saving')
                        };

                        return UMI.dialog.open(data).then(
                            function() {},
                            function() {
                                if (!model.get('isValid')) {
                                    model.set('validErrors', null);
                                    model.send('becameValid');
                                }

                                model.rollback();
                                transition.retry();
                            }
                        );
                    }
                    return true;
                }
            }
        });
    };
});