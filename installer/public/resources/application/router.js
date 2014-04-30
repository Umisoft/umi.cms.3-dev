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
                return $.get(UmiSettings.baseApiURL).then(function(results){
                    var result = results.result;
                    self.controllerFor('application').set('settings', result);
                    if(result.collections){
                        UMI.modelsFactory(result.collections);
                    }
                    if(result.modules){
                        self.controllerFor('dock').set('modules', result.modules);
                    }
                }, function(error){
                    var becameError = new Error();
                    error.stack = becameError.stack;
                    transition.send('templateLogs', error);
                });
            },
            actions: {
                logout: function(){
                    var applicationLayout = document.querySelector('.umi-main-view');
                    var maskLayout = document.createElement('div');
                    maskLayout.className = 'auth-mask';
                    maskLayout = document.body.appendChild(maskLayout);
                    $(applicationLayout).addClass('off');
                    $.post(UmiSettings.baseApiURL + '/action/logout');
                    require(['auth/main'], function(auth){
                        auth();
                        $(applicationLayout).addClass('fade-out');
                        Ember.run.later('', function(){
                            UMI.reset();
                            UMI.deferReadiness();
                            maskLayout.parentNode.removeChild(maskLayout);
                        }, 800);
                    });
                },

                /**
                 Сохраняет обьект

                 @method save
                 @param {Object} params Объект аргументов
                 params.object - сохраняемый объект
                 params.handler - элемент (кнопка) вызвавший событие сохранение
                 */
                save: function(params){
                    var isNewObject;
                    var self = this;
                    if(!params.object.get('isValid')){
                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }
                        return;
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
                            if(isNewObject){
                                if(params.object.store.metadataFor(params.object.constructor.typeKey).collectionType === 'hierarchic'){
                                    var parent = params.object.get('parent');
                                    if(parent && 'isFulfilled' in parent){
                                        return parent.then(function(parent){
                                            parent.reload().then(function(parent){
                                                parent.trigger('needReloadHasMany');
                                            });
                                            self.send('edit', params.object);
                                        });
                                    } else{
                                        // Обновление связей рутовой ноды в дереве. TODO: подумать как можно избежать обращения к контроллеру дерева.
                                        self.get('container').lookup('controller:treeControl').get('root')[0].updateChildren(params.object.get('id'), 'root');

                                        self.send('edit', params.object);
                                    }
                                }
                                self.send('edit', params.object);
                            }
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
                                    console.log(params.object.get('currentState.stateName'), results, self);
                                    /* params.object.rollback();
                                     params.object.reload();*/
                                }
                            );
                        }
                    );
                },

                dialogError: function(error){
                    var settings = this.parseError(error);
                    settings.close = true;
                    settings.title = error.status + '. ' + error.statusText;
                    UMI.dialog.open(settings).then();
                },
                /**
                 Метод генерирует фоновую ошибку
                 @method backgroundError
                 @property error Object {status: status, title: title, content: content, stack: stack}
                 */
                backgroundError: function(error){
                    var settings = this.parseError(error);
                    settings.type = 'error';
                    settings.duration = false;
                    UMI.notification.create(settings);
                },

                templateLogs: function(error, parentRoute){
                    parentRoute = parentRoute || 'module';
                    var dataError = this.parseError(error);
                    var model = Ember.Object.create(dataError);
                    this.intermediateTransitionTo(parentRoute + '.errors', model);
                },

                /// global actions
                switchActivity: function(object){
                    try{
                        var serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                        var switchActivitySource = this.controllerFor('component').get('settings').actions[(object.get('active') ? 'de' : '') + 'activate'].source;
                        $.ajax({
                            url: switchActivitySource + '?id=' + object.get('id'),
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

                create: function(parentObject, actionParam){
                    var typeName = actionParam.typeName;
                    this.transitionTo('action', parentObject.get('id'), 'createForm', {queryParams: {'typeName': typeName}});
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

                trash: function(object, type){
                    console.log('trash');
                },

                showPopup: function(popupType, object, meta){
                    UMI.PopupView.create({
                        container: this.container,
                        popupType: popupType,
                        object: object,
                        meta: meta
                    }).append();
                }
            },
            /**
             Метод парсит ошибку и возвпращает её в виде объекта
             @method parseError
             @return Object|null {status: status, title: title, content: content, stack: stack}
             */
            parseError: function(error){
                var parsedError = {
                    status: error.status,
                    title: error.statusText,
                    stack: error.stack
                };

                if(error.status === 403 || error.status === 401){
                    this.send('logout');
                    return;
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
                if(transition.targetName === this.routeName){
                    var firstChild = this.controllerFor('dock').get('content.firstObject');
                    return this.transitionTo('module', 'news');//firstChild.get('name'));
                }
            }
        });

        /**
         * @class IndexRoute
         * @extends Ember.Route
         */
        UMI.ModuleRoute = Ember.Route.extend({
            model: function(params, transition){
                var deferred = Ember.RSVP.defer();
                var modules = this.controllerFor('dock').get('content');
                var module = modules.findBy('name', params.module);
                if(module){
                    this.controllerFor('dock').set('activeModule', module);
                    deferred.resolve(module);
                } else{
                    var error = {
                        'status': 404,
                        'statusText': 'Module not found.',
                        'message': 'The module "' + params.module + '" was not found.'
                    };
                    transition.send('templateLogs', error);
                    deferred.reject();
                }
                return deferred.promise;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var self = this;
                    var deferred = Ember.RSVP.defer();
                    var firstChild = model.get('components.firstObject');
                    if(firstChild){
                        deferred.resolve(self.transitionTo('component', firstChild.get('name')));
                    } else{
                        var error = {
                            'status': 404,
                            'statusText': 'Components not found.',
                            'message': 'For module "' + model.get('name') + '" components not found.'
                        };
                        Ember.run.next(function(){
                            transition.send('templateLogs', error);
                        });
                        deferred.reject();
                    }
                    return deferred.promise;
                }
            },
            serialize: function(model){
                return {module: model.get('slug')};
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
                var deferred = Ember.RSVP.defer();
                var components = this.modelFor('module').get('components');
                var model = components.findBy('name', transition.params.component.component);
                if(model){
                    Ember.$.get(model.get('resource')).then(function(results){
                        var componentController = self.controllerFor('component');
                        var settings = results.result.settings;
                        componentController.set('settings', settings);
                        componentController.set('selectedContext', transition.params.context ? transition.params.context.context : 'root');
                        deferred.resolve(model);
                    }, function(error){
                        transition.send('templateLogs', error);
                        deferred.reject();
                    });
                } else{
                    var error = new URIError('The component "' + transition.params.component.component + '" was not found.');
                    error.statusText = 'Component not found.';
                    transition.send('templateLogs', error);
                    deferred.reject();
                }
                return deferred.promise;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    this.transitionTo('context', 'root');
                }
            },
            serialize: function(model){
                return {component: model.get('name')};
            },
            renderTemplate: function(controller){
                this.render();
                if(controller.get('sideBarControl')){
                    try{
                        this.render(controller.get('sideBarControl.name'), {
                            into: 'component',
                            outlet: 'sideBar'
                        });
                    } catch(error){
                        var errorObject = {
                            'statusText': error.name,
                            'message': error.message,
                            'stack': error.stack
                        };
                        this.send('templateLogs', errorObject, 'component');
                    }
                }
            }
        });

        UMI.ContextRoute = Ember.Route.extend({
            model: function(params, transition){
                var componentController = this.controllerFor('component');
                var collectionName = componentController.get('collectionName');
                var RootModel;
                var model;

                componentController.set('selectedContext', params.context);

                if(!collectionName){
                    RootModel = Ember.Object.extend({});
                    model = new Ember.RSVP.Promise(function(resolve){
                        resolve(RootModel.create({'id': params.context}));
                    });
                } else{
                    if(params.context === 'root'){
                        RootModel = Ember.Object.extend({
                            children: function(){
                                if(collectionName){
                                    if(componentController.get('sideBarControl') && componentController.get('sideBarControl').get('name') === 'tree'){
                                        return self.store.find(collectionName, {'filters[parent]': 'null()'});
                                    } else{
                                        return self.store.find(collectionName);
                                    }
                                }
                            }.property()
                        });
                        model = new Ember.RSVP.Promise(function(resolve){
                            resolve(RootModel.create({'id': 'root', type: 'base'}));
                        });
                    } else{
                        model = this.store.find(collectionName, params.context);
                    }
                }
                return model;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var firstControl = this.controllerFor('component').get('contentControls')[0];
                    return this.transitionTo('action', firstControl.name);
                }
            },
            serialize: function(model){
                if(model){
                    return {context: model.get('id')};
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
                var actionName = params.action;
                var contextModel = this.modelFor('context');
                var componentController = this.controllerFor('component');
                var collectionName = componentController.get('collectionName');
                var actions = componentController.get('contentControls');
                var action = actions.findBy('name', actionName);
                var data = {
                    'object': contextModel,
                    'action': action
                };
                if(action){
                    /**
                     * Мета информация для action
                     */
                    var actionParams = {};
                    if(contextModel.get('type')){
                        actionParams.type = contextModel.get('type');
                    }
                    if(actionName === 'createForm'){
                        var createdParams =  contextModel.get('id') !== 'root' ? {parent: contextModel} : null;
                        data.createObject = self.store.createRecord(collectionName, createdParams);
                        if(transition.queryParams.typeName){
                            actionParams.type = transition.queryParams.typeName;
                        } else{
                            throw new Error("Тип создаваемого объекта не был указан.");
                        }
                    }
                    // Временное решение для таблицы
                    if(actionName === 'children' || actionName === 'filter'){
                        return Ember.$.getJSON('/resources/modules/news/categories/children/resources.json').then(function(results){
                            data.viewSettings = results.settings;
                            return data;
                        });
                    } else if(actionName === 'editForm' || actionName === 'createForm'){
                        actionParams = actionParams ? '?' + $.param(actionParams) : '';
                        var actionResource = componentController.get('settings').actions['get' + Ember.String.capitalize(actionName)].source + actionParams;

                        return Ember.$.get(actionResource).then(function(results){
                            data.viewSettings = results.result['get' + Ember.String.capitalize(actionName)];
                            return data;
                        }, function(error){
                            transition.send('templateLogs', error, 'component');
                        });
                    }
                    return data;
                } else{
                    this.transitionTo('context', contextModel.get('id'));
                }
            },
            serialize: function(data){
                if(data.action){
                    return {action: data.action.get('name')};
                }
            },
            renderTemplate: function(controller, model){
                try{
                    var templateType = model.action.get('name');
                    this.render(templateType, {
                        controller: controller
                    });
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    this.send('templateLogs', errorObject, 'component');
                }
            },
            setupController: function(controller, model){
                this._super(controller, model);
                var context = this.modelFor('context');
                var actions = this.controllerFor('component').get('contentControls');
                var action = actions.findBy('name', model.action.get('name'));
                if(!action){
                    return this.transitionTo('context', context.get('id'));
                }
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
                    if(this.modelFor('action').object.get('isNew')){// TODO: не удаляется мусор
                        this.modelFor('action').createObject.deleteRecord();
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


        if('baseSettingsURL' in window.UmiSettings){
            UMI.Router.map(function(){
                this.resource('settings', {path: '/configure'}, function(){
                    this.route('component', {path: '/:component'});
                });
            });


            UMI.SettingsRoute = Ember.Route.extend({
                model: function(){
                    return $.get(window.UmiSettings.baseSettingsURL).then(
                        function(settings){
                            var treeControl = settings.result.components;
                            return treeControl;
                        }
                    );
                }
            });

            UMI.SettingsComponentRoute = Ember.Route.extend({
                model: function(params){
                    var settings = this.modelFor('settings');
                    var findDepth = function findDepth(components, propertyName, propertyValue){
                        var i;
                        var component;
                        var result;
                        for(i = 0; i < components.length; i++){
                            if(components[i][propertyName] === propertyValue){
                                component = components[i];
                            }
                            if('components' in components[i]){
                                result = findDepth(components[i].components, propertyName, propertyValue);
                                if(result){
                                    component = result;
                                }
                            }
                        }
                        return component;
                    };
                    var component = findDepth(settings, 'name', params.component);
                    return $.get(component.resource).then(function(data){
                        Ember.set(component, 'form', data.result.form);
                        return component;
                    });
                },
                serialize: function(){
                }
            });
        }
    };
});