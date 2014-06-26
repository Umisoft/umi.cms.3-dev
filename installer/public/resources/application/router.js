define([], function(){
    'use strict';
    return function(UMI){
        /**
         @module UMI
         @submodule Router
         **/
        UMI.Router.reopen({
            location: 'auto',
            rootURL: window.UmiSettings.baseURL
        });

        /**
         @class map
         @constructor
         */
        UMI.Router.map(function(){
            this.resource('module', {path: '/:module'}, function(){
                this.route('errors', {path: '/:status'});
                this.resource('component', {path: '/:component'}, function(){
                    this.route('errors', {path: '/:status'});
                    this.resource('context', {path: '/:context'}, function(){
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
            model: function(params, transition){
                var self = this;
                var promise;

                try{
                    if(!UmiSettings.baseApiURL){
                        throw new Error('Для UmiSettings не задан baseApiURL');
                    }
                    promise = $.get(UmiSettings.baseApiURL).then(function(results){
                        if(results && results.result){
                            var result = results.result;
                            self.controllerFor('application').set('settings', result);
                            if(result.collections){
                                UMI.modelsFactory(result.collections);
                            }
                            if(result.modules){
                                self.controllerFor('application').set('modules', result.modules);
                            }
                            if(result.i18n){
                                UMI.i18n.setDictionary(result.i18n);
                            }
                        } else{
                            try{
                                throw new Error('Запрашиваемый ресурс ' + UmiSettings.baseApiURL + ' некорректен.');
                            } catch(error){
                                transition.abort();
                                transition.send('dialogError', error);
                            }
                        }
                    }, function(error){
                        var becameError = new Error(error);
                        error.stack = becameError.stack;
                        transition.send('dialogError', error);
                    });
                } catch(error){
                    transition.abort();
                    transition.send('dialogError', error);
                } finally{
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
            saveObject: function(params){
                var isNewObject;
                var self = this;
                var deferred;

                if(!params.object.get('isValid')){
                    if(params.handler){
                        $(params.handler).removeClass('loading');
                    }
                    deferred = Ember.RSVP.defer();
                    return deferred.resolve(false);
                }

                if(params.object.get('currentState.stateName') === 'root.loaded.created.uncommitted'){
                    isNewObject = true;
                }

                return params.object.save().then(
                    function(){
                        params.object.updateRelationhipsMap();

                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }

                        return params.object;
                    },

                    function(results){
                        var self = this;
                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }

                        var data = {
                            'close': false,
                            'title': results.errors,
                            'content': results.message,
                            'confirm': 'Загрузить объект с сервера'
                        };

                        return UMI.dialog.open(data).then(
                            function(){
                                //https://github.com/emberjs/data/issues/1632
                                //params.object.transitionTo('updated.uncommitted');
                                //                                    console.log(params.object.get('currentState.stateName'), results, self);
                                /* params.object.rollback();
                                 params.object.reload();*/
                                return false;
                            }
                        );
                    }
                );
            },

            beforeAdd: function(params){
                var self = this;
                return self.saveObject(params).then(function(addObject){
                    if(addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic'){
                        var parent = addObject.get('parent');
                        if(parent && 'isFulfilled' in parent){
                            return parent.then(function(parent){
                                parent.reload().then(function(parent){
                                    parent.trigger('needReloadHasMany');
                                });
                                return addObject;
                            });
                        } else{
                            // Обновление связей рутовой ноды в дереве.
                            // TODO: подумать как можно избежать обращения к контроллеру дерева.
                            self.get('container').lookup('controller:treeControl').get('root')[0].updateChildren(addObject.get('id'), 'root');
                            return addObject;
                        }
                    } else{
                        return addObject;
                    }
                });
            },

            actions: {
                logout: function(){
                    var applicationLayout = document.querySelector('.umi-main-view');
                    var maskLayout = document.createElement('div');
                    maskLayout.className = 'auth-mask';
                    maskLayout = document.body.appendChild(maskLayout);
                    $(applicationLayout).addClass('off is-transition');
                    $.post(UmiSettings.baseApiURL + '/action/logout').then(function(){
                        require(['auth/main'], function(auth){
                            auth({appIsFreeze: true, appLayout: applicationLayout});
                            $(applicationLayout).addClass('fade-out');
                            Ember.run.later('', function(){
                                $(applicationLayout).removeClass('is-transition');
                                maskLayout.parentNode.removeChild(maskLayout);
                            }, 800);
                        });
                    });
                },

                dialogError: function(error){
                    var settings = this.parseError(error);
                    if(settings !== 'silence'){
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
                backgroundError: function(error){
                    var settings = this.parseError(error);
                    if(settings !== 'silence'){
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
                templateLogs: function(error, parentRoute){
                    parentRoute = parentRoute || 'module';
                    var dataError = this.parseError(error);
                    if(dataError !== 'silence'){
                        var model = Ember.Object.create(dataError);
                        this.intermediateTransitionTo(parentRoute + '.errors', model);
                    }
                },

                showPopup: function(popupType, object, meta){
                    UMI.PopupView.create({
                        container: this.container,
                        popupType: popupType,
                        object: object,
                        meta: meta
                    }).append();
                },

                /// global actions
                /**
                 * Сохраняет обьект вызывая метод saveObject
                 * @method save
                 */
                save: function(params){
                    this.saveObject(params);
                },

                saveAndGoBack: function(params){
                    var self = this;
                    self.saveObject(params).then(function(isSaved){
                        if(isSaved){
                            self.send('backToFilter');
                        }
                    });
                },

                add: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(addObject){
                        self.send('edit', addObject);
                    });
                },

                addAndGoBack: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(){
                        self.send('backToFilter');
                    });
                },

                addAndCreate: function(params){
                    var self = this;
                    return self.beforeAdd(params).then(function(addObject){
                        var behaviour = {typeName: addObject.get('type')};
                        if(addObject.store.metadataFor(addObject.constructor.typeKey).collectionType === 'hierarchic'){
                            return addObject.get('parent').then(function(parent){
                                self.send('create', parent, behaviour);
                            });
                        } else{
                            self.send('create', addObject, behaviour);
                        }
                    });
                },

                switchActivity: function(object){
                    try{
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchActivitySource = this.controllerFor('component').get('settings').actions[(object.get('active') ? 'de' : '') + 'activate'].source;
                        switchActivitySource = UMI.Utils.replacePlaceholder(object, switchActivitySource);
                        $.ajax({
                            url: switchActivitySource,
                            type: "POST",
                            data: serializeObject,
                            contentType: 'application/json; charset=UTF-8'
                        }).then(function(){
                            object.reload();
                        });
                    } catch(error){
                        this.send('backgroundError', error);
                    }
                },

                create: function(parentObject, behaviour){
                    var typeName = behaviour.typeName;
                    var contextId = 'root';
                    if(parentObject.constructor.typeKey){
                        var meta = this.store.metadataFor(parentObject.constructor.typeKey) || {};
                        if(meta.hasOwnProperty('collectionType') && meta.collectionType === 'hierarchic'){
                            contextId = parentObject.get('id');
                        }
                    }
                    this.transitionTo('action', contextId, 'createForm', {queryParams: {'typeName': typeName}});
                },

                edit: function(object){
                    this.transitionTo('action', object.get('id'), 'editForm');
                },

                viewOnSite: function(object){
                    var link;
                    if(object){
                        link = object._data.meta.pageUrl;
                    } else{
                        link = window.UmiSettings.baseSiteURL;
                    }

                    link = window.location.host + link;
                    var tab = window.open('//' + link.replace('\/\/', '\/'), '_blank');
                    tab.focus();
                },

                /**
                 * Удаляет объект (перемещает в корзину)
                 * @method trash
                 * @param object
                 * @returns {*|Promise}
                 */
                trash: function(object){
                    var self = this;
                    var isActiveContext = this.modelFor('context') === object;
                    return object.destroyRecord().then(function(){
                        var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" удалено в корзину.'};
                        UMI.notification.create(settings);
                       if(isActiveContext){
                           self.send('backToFilter');
                       }
                    }, function(){
                        var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" не удалось поместить в корзину.'};
                        UMI.notification.create(settings);
                    });
                },

                /**
                 * Спрашивает пользователя и в случае положительного ответа безвозвратно удаляет объект
                 * @method delete
                 * @param object
                 * @returns {*|Promise}
                 */
                "delete": function(object){
                    var self = this;
                    var isActiveContext = this.modelFor('context') === object;
                    var data = {
                        'close': false,
                        'title': 'Удаление "' + object.get('displayName') + '".',
                        'content': '<div>Объект будет удалён без возможности востановления, всё равно продолжить?</div>',
                        'confirm': 'Удалить',
                        'reject': 'Отмена',
                        'proposeRemember': 'delete'
                    };
                    return UMI.dialog.open(data).then(
                        function(){
                            return object.destroyRecord().then(function(){
                                var settings = {type: 'success', 'content': '"' + object.get('displayName') + '" успешно удалено.'};
                                UMI.notification.create(settings);
                                if(isActiveContext){
                                    self.send('backToFilter');
                                }
                            }, function(){
                                var settings = {type: 'error', 'content': '"' + object.get('displayName') + '" не удалось удалить.'};
                                UMI.notification.create(settings);
                            });
                        },
                        function(){}
                    );
                },
                /**
                 * Возвращает к списку
                 */
                backToFilter: function(){
                    this.transitionTo('context', 'root');
                }
            },

            /**
             Метод парсит ошибку и возвпращает её в виде объекта (ошибки с Back-end)
             @method parseError
             @return Object|null|String {status: status, title: title, content: content, stack: stack}
             */
            parseError: function(error){
                var parsedError = {
                    status: error.status,
                    title: error.statusText,
                    stack: error.stack
                };

                if(error.status === 403 || error.status === 401){
                    // TODO: вынести на уровень настройки AJAX (для того чтобы это касалось и кастомных компонентов)
                    this.send('logout');
                    return 'silence';
                }

                var content;
                if(error.hasOwnProperty('responseJSON')){
                    if(error.responseJSON.hasOwnProperty('result') && error.responseJSON.result.hasOwnProperty('error')){
                        content = error.responseJSON.result.error.message;
                    }
                } else{
                    content = error.responseText || error.message;
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
            redirect: function(model, transition){
                var firstChild;
                if(transition.targetName === this.routeName){
                    try{
                        firstChild = this.controllerFor('application').get('modules')[0];
                        if(!firstChild){
                            throw new Error('Ни одного модуля системы не найдено');
                        }
                    } catch(error){
                        transition.send('backgroundError', error);//TODO: Проверить вывод ошибок
                    } finally{
                        return this.transitionTo('module', Ember.get(firstChild, 'name'));//TODO: Нужно дать пользователю выбрать компонент
                    }
                }
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.ModuleRoute = Ember.Route.extend({
            model: function(params, transition){
                var deferred;
                var modules;
                var module;
                try{
                    deferred = Ember.RSVP.defer();
                    modules = this.controllerFor('application').get('modules');
                    module = modules.findBy('name', params.module);
                    if(module){
                        deferred.resolve(module);
                    } else{
                        throw new Error('The module "' + params.module + '" was not found.');
                    }
                } catch(error){
                    deferred.reject(error);
                } finally{
                    return deferred.promise;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var self = this;
                    var deferred;
                    var firstChild;
                    try{
                        deferred = Ember.RSVP.defer();
                        firstChild = Ember.get(model, 'components')[0];
                        if(firstChild){
                            deferred.resolve(self.transitionTo('component', Ember.get(firstChild, 'name')));
                        } else{
                            throw new Error('For module "' + Ember.get(model, 'name') + '" components not found.');
                        }
                    } catch(error){
                        deferred.reject(Ember.run.next(self, function(){this.send('templateLogs', error);}));
                    } finally{
                        return deferred.promise;
                    }
                }
            },

            serialize: function(model){
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
            model: function(params, transition){
                var self = this;
                var deferred;
                var components;
                var model;
                var componentName = transition.params.component.component;
                try{
                    deferred = Ember.RSVP.defer();
                    components = Ember.get(this.modelFor('module'), 'components');
                    // filterBy
                    for(var i = 0; i < components.length; i++){
                        if(components[i].name === componentName){
                            model = components[i];
                            break;
                        }
                    }
                    if(model){
                        /**
                         * Ресурс компонента
                         */
                        Ember.$.get(Ember.get(model, 'resource')).then(function(results){
                            var componentController = self.controllerFor('component');
                            if(Ember.typeOf(results) === 'object' && Ember.get(results, 'result.layout')){
                                var settings = results.result.layout;
                                componentController.set('settings', settings);
                                componentController.set('selectedContext', Ember.get(transition,'params.context') ? Ember.get(transition, 'params.context.context') : 'root');
                                deferred.resolve(model);
                            } else{
                                var error = new Error('Ресурс "' + Ember.get(model, 'resource') + '" некорректен.');
                                transition.send('backgroundError', error);
                                deferred.reject();
                            }
                        }, function(error){
                            deferred.reject(Ember.run.next(this, function(){transition.send('templateLogs', error);}));
                        });
                    } else{
                        throw new URIError('The component "' + componentName + '" was not found.');
                    }
                } catch(error){
                    deferred.reject(Ember.run.next(this, function(){transition.send('templateLogs', error);}));
                } finally{
                    return deferred.promise;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var context;
                    try{
                        var emptyControl = this.controllerFor('component').get('settings.contents.emptyContext.redirect');
                        if(emptyControl){
                            context = Ember.get(emptyControl, 'params.slug');
                        } else{
                            context ='root';
                        }
                    } catch(error){
                        transition.send('backgroundError', error);
                    } finally{
                        return this.transitionTo('context', context);
                    }
                }
            },

            serialize: function(model){
                return {component: Ember.get(model, 'name')};
            },

            /**
             * Отрисовываем компонент
             * Если есть sideBar - его тоже отрисовываем
             * @param controller
             */
            renderTemplate: function(controller){
                this.render();

                if(controller.get('sideBarControl')){
                    try{
                        this.render(controller.get('sideBarControl.name'), {
                            into: 'component',
                            outlet: 'sideBar'
                        });
                    } catch(error){
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
            model: function(params, transition){
                var componentController;
                var collection;
                var RootModel;
                var model;

                try{
                    componentController = this.controllerFor('component');
                    collection = componentController.get('dataSource');
                    componentController.set('selectedContext', params.context);// TODO: зачем это вообще нужно?

                    if(params.context === 'root'){
                        RootModel = Ember.Object.extend({});
                        model = new Ember.RSVP.Promise(function(resolve){
                            resolve(RootModel.create({'id': 'root', type: 'base'}));
                        });
                    } else{
                        switch(Ember.get(collection, 'type')){
                            case 'static':
                                model = new Ember.RSVP.Promise(function(resolve, reject){
                                    var objects = Ember.get(collection, 'objects');
                                    var object;
                                    // filterBy
                                    for(var i = 0; i < objects.length; i++){
                                        if(objects[i].id === params.context){
                                            object = objects[i];
                                            break;
                                        }
                                    }
                                    if(object){
                                        resolve(object);
                                    } else{
                                        reject('Не найден объект с ID ' + params.context);
                                    }
                                });
                                break;
                            case 'collection':
                                if(this.store.hasRecordForId(Ember.get(collection, 'name'), params.context)){
                                    model = this.store.getById(Ember.get(collection, 'name'), params.context);
                                    model = model.reload();
                                } else{
                                    model = this.store.find(Ember.get(collection, 'name'), params.context);
                                }
                                break;
                            default:
                                throw new Error('Неизвестный тип dataSource компонента.');
                        }
                    }
                } catch(error){
                    Ember.run.next(this, function(){transition.send('templateLogs', error);});
                } finally{
                    return model;
                }
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var control;
                    var controlName;
                    try{
                        control = this.controllerFor('component').get('contentControls')[0];
                        controlName = Ember.get(control, 'name');
                        if(!controlName){
                            throw new Error('Действия для данного контекста не доступны.');
                        }
                    } catch(error){
                        transition.send('backgroundError', error);
                    } finally{
                        return this.transitionTo('action', controlName);
                    }
                }
            },

            serialize: function(model){
                if(model){
                    return {context: Ember.get(model, 'id')};
                }
            }
        });

        UMI.ActionRoute = Ember.Route.extend({
            queryParams: {
                typeName: {
                    refreshModel: true,
                    replace: true
                }
            },

            model: function(params, transition){
                var self = this;
                var actionName;
                var contextModel;
                var componentController;
                var collectionName;
                var contentControls;
                var contentControl;
                var routeData;
                var actionParams = {};
                var createdParams;
                var deferred;
                var actionResource;
                var actionResourceName;

                try{
                    deferred = Ember.RSVP.defer();
                    actionName = params.action;
                    contextModel = this.modelFor('context');
                    componentController = this.controllerFor('component');
                    collectionName = componentController.get('collectionName');
                    contentControls = componentController.get('contentControls');
                    contentControl = contentControls.findBy('name', actionName);
                    routeData = {
                        'object': contextModel,
                        'control': contentControl
                    };

                    if(Ember.get(contentControl, 'isStatic')){
                        // Понадобится когда не будет необходимости менять метаданные контрола в зависимости от контекста
                        deferred.resolve(routeData);
                    } else{
                        // Временно для табличного контрола
                        if(actionName === 'children' || actionName === 'filter'){
                            Ember.$.getJSON('/resources/modules/news/categories/children/resources.json').then(function(results){
                                routeData.control = {
                                    name: actionName,
                                    meta: results.getFilter
                                };
                                deferred.resolve(routeData);
                            });
                        } else{
                            actionResourceName = Ember.get(contentControl, 'params.action');
                            actionResource = Ember.get(componentController, 'settings.actions.' + actionResourceName + '.source');

                            if(actionResource){
                                actionResource = UMI.Utils.replacePlaceholder(routeData.object, actionResource);

                                if(actionName === 'createForm'){
                                    createdParams = contextModel.get('id') !== 'root' ? {parent: contextModel} : {};
                                    if(transition.queryParams.typeName){
                                        createdParams.type = transition.queryParams.typeName;
                                    }
                                    routeData.createObject = self.store.createRecord(collectionName, createdParams);
                                    if(transition.queryParams.typeName){
                                        actionParams.type = transition.queryParams.typeName;
                                    } else{
                                        throw new Error("Тип создаваемого объекта не был указан.");
                                    }
                                }

                                Ember.$.get(actionResource).then(function(results){
                                    var dynamicControl;
                                    var dynamicControlName;
                                    if(actionName === 'dynamic'){
                                        dynamicControl = Ember.get(results, 'result') || {};
                                        for(var key in dynamicControl){
                                            if(dynamicControl.hasOwnProperty(key)){
                                                dynamicControlName = key;
                                            }
                                        }
                                        dynamicControl = dynamicControl[dynamicControlName] || {};
                                        dynamicControl.name = dynamicControlName;

                                        UMI.Utils.objectsMerge(routeData.control, dynamicControl);
                                    } else{
                                        Ember.set(routeData.control, 'meta', Ember.get(results, 'result.' + actionResourceName));
                                    }
                                    deferred.resolve(routeData);
                                }/*, function(error){
                                 Сообщение ошибки в таких случаях возникает на уровне ajaxSetup, получается две одинаковых. Нужно научить ajax наследованию
                                 deferred.reject(transition.send('backgroundError', error));
                                 }*/);
                            } else{
                                throw new Error('Дествие ' + Ember.get(contentControl, 'name') + ' для данного котекста недоступно.');
                            }
                        }
                    }
                } catch(error){
                    deferred.reject(transition.send('backgroundError', error));
                } finally{
                    return deferred.promise;
                }
            },

            serialize: function(routeData){
                if(Ember.get(routeData, 'control')){
                    return {action: Ember.get(routeData, 'control.name')};
                }
            },

            renderTemplate: function(controller, routeData){
                try{
                    var templateType = Ember.get(routeData, 'control.name');
                    this.render(templateType, {
                        controller: controller
                    });
                } catch(error){
                    this.send('templateLogs', errorObject, 'component');
                }
            },

            setupController: function(controller, model){
                this._super(controller, model);
                if(model.createObject){
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
                willTransition: function(transition){
                    if(transition.params.action && transition.params.action.action !== 'createForm'){
                        this.get('controller').set('typeName', null);
                    }
                    var model = this.modelFor('action').object;
                    if(this.modelFor('action').object.get('isNew')){
                        this.modelFor('action').object.deleteRecord();
                    }
                    if(model.get('isDirty')){
                        transition.abort();
                        var data = {
                            'close': false,
                            'title': 'Изменения не были сохранены.',
                            'content': 'Переход на другую страницу вызовет потерю несохраненых изменений. Остаться на странице чтобы сохранить изменения?',
                            'confirm': 'Остаться на странице',
                            'reject': 'Продолжить без сохранения'
                        };
                        return UMI.dialog.open(data).then(
                            function(){/*При положительном ответе делать ничего не нужно*/ },
                            function(){
                                if(!model.get('isValid')){
                                    model.set('validErrors', null);
                                    model.send('becameValid');
                                }
                                model.rollback();
                                transition.retry();
                            }
                        );
                    }
                }
            }
        });
    };
});