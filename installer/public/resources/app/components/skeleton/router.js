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
                this.resource('component', {path: '/:component'}, function(){
                    this.resource('action', {path: '/:action'}, function(){
                        this.resource('context', {path: '/:context'});
                    });
                    this.route('search');
                });
            });
            this.route('logout', {path: '/auth/logout'});
        });

        //		UMI.ErrorState = Ember.Mixin.create({//TODO: Обрабатывать все типы ошибок, и разные роуты
        //			actions: {
        //				error: function(error, originRoute){
        //					this.transitionTo('error', 'error');
        //				}
        //			}
        //		});

        /**
         * @class ApplicationRoute
         * @extends Ember.Route
         * @uses Utils.modelsFactory
         */
        UMI.ApplicationRoute = Ember.Route.extend({
            /**
             Инициализирует модели данных, модули и компоненты для Dock.

             @method model
             @return
             **/
            model: function(){
                var self = this;
                var baseResource = UmiSettings.baseURL + '/api/settings';
                return $.getJSON(baseResource).then(function(results){
                    var result = results.result;
                    if(result.collections){
                        UMI.Utils.modelsFactory(result.collections);
                    }
                    if(result.resources){
                        UMI.Utils.setModelsResources(result.resources);
                    }
                    if(result.records){
                        var model;
                        for(model in result.records){
                            if(result.records.hasOwnProperty(model)){
                                self.store.pushMany(model, result.records[model]);
                            }
                        }
                    }

                    if(result.modules){
                        self.controllerFor('dock').set('modules', result);
                    }
                }, function(error){
                    throw new Error('Не получен ресурс приложения ' + baseResource + '.' + error);
                });
            },
            actions: {
                /**
                 * Метод вызывается каждый раз при смене роута в приложении.
                 * При переходе на роут `logout` выполняется abort для перехода,
                 * приложение становится неактивным и происходит смена шаблона на шаблон авторизации.
                 * @event willTransition
                 * @param {Object} transition
                 */
                willTransition: function(transition){
                    if(transition.targetName === 'logout'){
                        transition.abort();
                        var applicationLayout = document.querySelector('.umi-main-view');
                        var maskLayout = document.createElement('div');
                        maskLayout.className = 'auth-mask';
                        maskLayout = document.body.appendChild(maskLayout);
                        $(applicationLayout).addClass('off');
                        $.post('/admin/api/users/user/logout.json');
                        require(['auth/main'], function(auth){
                            auth();
                            $(applicationLayout).addClass('fade-out');
                            Ember.run.later('', function(){
                                UMI.reset();
                                UMI.deferReadiness();
                                maskLayout.parentNode.removeChild(maskLayout);
                            }, 2000);
                        });
                    }
                },
                /**
                 Сохраняет обьект

                 @method save
                 @param {Object} params Объект аргументов
                        params.object - сохраняемый объект
                        params.handler - элемент (кнопка) вызвавший событие сохранение
                 */
                save: function(params){
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
                            if(params.handler){
                                $(params.handler).removeClass('loading');
                            }
                            var message;
                            var fullMessage;
                            if(results.responseJSON.result.error){
                                message = results.responseJSON.result.error.message;
                            }
                            if(results.responseJSON.result.stack){
                                fullMessage = results.responseJSON.result.stack.message;
                            }
                            UMI.notification.create({
                                type: 'alert',
                                text: message,
                                fullText: fullMessage
                            });
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
                var modules = this.controllerFor('dock').get('content');
                var module = modules.findBy('name', params.module);
                // некрасивое решение
                this.controllerFor('dock').set('activeModule', module.get('name'));
                modules.setEach('isActive', false);
                // Для добавления класса active вкладке модуля в dock добавим атрибут isActive
                module.set('isActive', true);
                return module;
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var firstChild = model.get('components.firstObject');
                    return this.transitionTo('component', firstChild.get('name'));
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
                var components = this.modelFor('module').get('components');
                var model = components.findBy('name', transition.params.component.component);
                var componentResource = window.UmiSettings.baseApiURL + '/' + transition.params.module.module + '/' + transition.params.component.component + '/settings';
                return Ember.$.get(componentResource).then(function(results){
                    if(results.result.error){
                        throw 'Ресурс компонента не найден';
                    }
                    var componentController = self.controllerFor('component');
                    var settings = results.result.settings;
                    var tree = settings.controls.findBy('name', 'tree');
                    componentController.set('hasTree', !!tree);
                    if(!!tree){
                        /**
                         * Колекция для дерева
                         */
                        var treeControl = self.controllerFor('treeControl');
                        treeControl.set('collections', [{'type': model.get('collection'), 'displayName': tree.displayName}]);
                        treeControl.set('routeParams', transition.params);
                    }
                    /**
                     * Режимы
                     */
                    var controls = settings.controls;
                    var context = settings.layout;
                    componentController.set('controls', controls);
                    componentController.set('context', context);
                    componentController.set('selectedContext', transition.params.context ? transition.params.context.context : 'root');
                    return model;
                }, function(error){
                    throw new Error('Не получен ресурс компонета ' + componentResource + '.' + error);
                });
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var defaultAction = this.controllerFor('component').get('contentControls.firstObject.name');
                    return this.transitionTo('action', defaultAction);
                }
            },
            serialize: function(model){
                return {component: model.get('name')};
            }
        });

        UMI.ActionRoute = Ember.Route.extend({
            model: function(params){
                var modes = this.controllerFor('component').get('contentControls');
                return modes.findBy('name', params.action);
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var self = this;
                    var contextId = this.controllerFor('component').get('selectedContext');
                    return self.transitionTo('context', contextId);
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
                var collectionName = self.modelFor('component').get('collection');
                var oldContext = this.controllerFor('component').get('selectedContext');
                this.controllerFor('component').set('selectedContext', params.context);
                /**
                 * Редирект на Action если контекст не имеет action
                 */
                var activeAction = this.modelFor('action');
                var firstAction = this.controllerFor('component').get('contentControls.firstObject');
                if(oldContext !== params.context && firstAction.get('name') !== activeAction.get('name')){
                    return this.transitionTo('action', firstAction.get('name'));
                }

                if(params.context === 'root'){
                    var RootModel = Ember.Object.extend({
                        children: function(){
                            return self.store.find(collectionName, {'filters[parent]': 'null()'});
                        }.property()
                    });
                    model = new Ember.RSVP.Promise(function(resolve, reject){
                        resolve(RootModel.create({'id': 'root'}));
                    });
                } else{
                    model = this.store.find(collectionName, params.context);
                }
                return model.then(function(model){
                    routeData.object = model;
                    /**
                     * Раскрытие текущей ветки в дереве
                     */
                    self.controllerFor('treeControl').set('activeContext', model);
                    /**
                     * Мета информация для action
                     */
                    var actionResource = window.UmiSettings.baseApiURL + '/' + transition.params.module.module + '/' + transition.params.component.component + '/' + self.modelFor('component').get('collection')  + '/' + transition.params.action.action;
                    if(transition.params.action.action === 'form'){
                        return Ember.$.get(actionResource).then(function(results){
                            var viewSettings = {};
                            viewSettings[transition.params.action.action] = results.result[transition.params.action.action];
                            routeData.viewSettings = viewSettings;
                            return routeData;
                        }, function(error){
                            throw new Error('Не получена мета информация для action form ' + actionResource + '.' + error);
                        });
                    }
                    // Временное решение для таблицы
                    if(transition.params.action.action === 'children'){
                        return Ember.$.getJSON('/resources/modules/news/categories/children/resources.json').then(function(results){
                            routeData.viewSettings = results.settings;
                            return routeData;
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
            }
        });

        UMI.SearchRoute = Ember.Route.extend({
            model: function(params){
                //console.log(params);
            }
        });
    };
});