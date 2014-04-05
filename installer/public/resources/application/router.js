define([], function(){
    'use strict';
    return function(UMI){
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
        UMI.Router.map(function(){
            this.resource('module', {path: '/:module'}, function(){
                this.route('error', {path: '/:status'});
                this.resource('component', {path: '/:component'}, function(){
                    this.route('error', {path: '/:status'});
                    this.resource('action', {path: '/:action'}, function(){
                        this.resource('context', {path: '/:context'});
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
            model: function(){
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
                }, function(errors){
                    var message;
                    if(errors.responseJSON.hasOwnProperty('result') && errors.responseJSON.result.hasOwnProperty('error')){
                        message = errors.responseJSON.result.error.message;
                    }
                    var data = {
                        'close': false,
                        'title': errors.status + '. ' + errors.statusText,
                        'content': message
                    };
                    return UMI.dialog.open(data).then();
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
                targetBlank: function(url){
                    url = '//' + window.location.host + '/' + url;
                    var tab = window.open(url, '_blank');
                    tab.focus();
                },
                /**
                 Сохраняет обьект

                 @method save
                 @param {Object} params Объект аргументов
                 params.object - сохраняемый объект
                 params.handler - элемент (кнопка) вызвавший событие сохранение
                 */
                save: function(params){
                    if(!params.object.get('isValid')){
                        if(params.handler){
                            $(params.handler).removeClass('loading');
                        }
                        return;
                    }
                    params.object.save().then(
                        function(){
                            if(params.handler){
                                $(params.handler).removeClass('loading');
                            }
                            /*UMI.notification.create({
                             type: 'success',
                             text: 'Изменения успешно сохранены.',
                             duration: 3000
                             });*/
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
                }
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
            model: function(params){
                var deferred = Ember.RSVP.defer();
                var modules = this.controllerFor('dock').get('content');
                var module = modules.findBy('name', params.module);
                if(module){
                    this.controllerFor('dock').set('activeModule', module);
                    deferred.resolve(module);
                } else{
                    deferred.reject({
                        'status': 404,
                        'statusText': 'Module not found.',
                        'message': 'The module "' + params.module + '" was not found.'
                    });
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
                        deferred.reject(self.transitionTo('module.error', error));
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
                    }, function(errors){
                        var message;
                        if(errors.responseJSON.hasOwnProperty('result') && errors.responseJSON.result.hasOwnProperty('error')){
                            message = errors.responseJSON.result.error.message;
                        }
                        deferred.reject({
                            'status': errors.status,
                            'statusText': errors.statusText,
                            'message': message
                        });
                    });
                } else{
                    deferred.reject({
                        'status': 404,
                        'statusText': 'Component not found.',
                        'message': 'The component "' + transition.params.component.component + '" was not found.'
                    });
                }
                return deferred.promise;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var deferred = Ember.RSVP.defer();
                    var self = this;
                    var defaultAction = this.controllerFor('component').get('contentControls.firstObject.name');
                    if(defaultAction){
                        deferred.resolve(self.transitionTo('action', defaultAction));
                    } else{
                        var error = {
                            'status': 404,
                            'statusText': 'Actions not found.',
                            'message': 'For component "' + model.get('name') + '" actions not found.'
                        };
                        deferred.reject(self.transitionTo('component.error', error));
                    }
                    return deferred.promise;
                }
            },
            serialize: function(model){
                return {component: model.get('name')};
            },
            renderTemplate: function(controller){
                this.render();
                if(controller.get('sideBarControl')){
                    this.render(controller.get('sideBarControl.name'), {
                        into: 'component',
                        outlet: 'sideBar'
                    });
                }
            }
        });

        UMI.ActionRoute = Ember.Route.extend({
            model: function(params, transition){
                if(transition.params.hasOwnProperty('context') && this.controllerFor('component').get('selectedContext') !== transition.params.context.context){
                    this.controllerFor('component').set('selectedContext', transition.params.context.context);
                }
                var self = this;
                var deferred = Ember.RSVP.defer();
                var actions = this.controllerFor('component').get('contentControls');
                var action = actions.findBy('name', params.action);
                if(action){
                    deferred.resolve(action);
                } else{
                    deferred.reject({
                        'status': 404,
                        'statusText': 'Action not found.',
                        'message': 'The action "' + params.action + '" for component "' + self.modelFor("component").get('name') + '" was not found.'
                    });
                }
                return deferred.promise;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var contextId = this.controllerFor('component').get('selectedContext');
                    return this.transitionTo('context', contextId);
                }
            },
            serialize: function(model){
                if(model){
                    return {action: model.get('name')};
                }
            }
        });

        UMI.ContextRoute = Ember.Route.extend({
            model: function(params, transition){
                var self = this;
                var model;
                var routeData = {};
                var RootModel;
                var componentController = this.controllerFor('component');
                var collectionName = componentController.get('collectionName');
                var oldContext = componentController.get('selectedContext');
                componentController.set('selectedContext', params.context);
                /**
                 * Редирект на Action если контекст не имеет action
                 */
                var activeAction = this.modelFor('action');
                var firstAction = componentController.get('contentControls.firstObject');
                if(oldContext !== params.context && firstAction.get('name') !== activeAction.get('name')){
                    return this.transitionTo('action', firstAction.get('name'));
                }

                // Вот это место мне особенно не нравится
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
                            resolve(RootModel.create({'id': 'root'}));
                        });
                    } else{
                        model = this.store.find(collectionName, params.context);
                    }
                }

                return model.then(function(model){
                    routeData.object = model;
                    /**
                     * Мета информация для action
                     */
                    var actionName = transition.params.action.action;
                    var actionParams = {};
                    var collectionName = componentController.get('collectionName');
                    if(collectionName){
                        actionParams.collection = collectionName;
                    }
                    if(actionName === 'form'){
                        actionParams.form = 'edit';
                    }
                    if(model.get('type')){
                        actionParams.type = model.get('type');
                    }

                    // Временное решение для таблицы
                    if(actionName === 'children' || actionName === 'filter'){
                        return Ember.$.getJSON('/resources/modules/news/categories/children/resources.json').then(function(results){
                            routeData.viewSettings = results.settings;
                            return routeData;
                        });
                    } else if(actionName === 'form'){
                        actionParams = actionParams ? '?' + $.param(actionParams) : '';
                        var actionResource = componentController.get('settings').actions[actionName].source + actionParams;

                        return Ember.$.get(actionResource).then(function(results){
                            routeData.viewSettings = results.result;
                            return routeData;
                        }, function(error){
                            throw new Error('Не получена мета информация для action form ' + actionResource + '.' + error);
                        });
                    }
                    return routeData;
                });
            },
            serialize: function(routeData){
                if(routeData.object){
                    return {context: routeData.object.get('id')};
                }
            },
            renderTemplate: function(){
                var templateType = this.modelFor('action').get('name');
                this.render(templateType);
            },
            actions: {
                /**
                 Метод вызывается при уходе с роута.
                 @event willTransition
                 @param {Object} transition
                 */
                willTransition: function(transition){
                    var model = this.modelFor('context').object;

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