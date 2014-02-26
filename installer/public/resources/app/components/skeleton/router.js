define([], function(){
    'use strict';

    return function(UMI){
        UMI.Router.map(function(){
            this.resource('module', {path: '/:module'}, function(){
                this.resource('component', {path: '/:component'}, function(){
                    this.resource('treeActive', {path: '/:treeActive'}, function(){
                        this.resource('mode', {path: '/:mode'}, function(){
                            this.resource('content', {path: '/:content'});
                        });
                    });
                });
            });
            this.route('logout', {path: '/auth/logout'});
        });

        UMI.Router.reopen({
            location: 'history',
            rootURL: UmiSettings.baseURL
        });

        //		UMI.ErrorState = Ember.Mixin.create({//TODO: Обрабатывать все типы ошибок, и разные роуты
        //			actions: {
        //				error: function(error, originRoute){
        //					this.transitionTo('error', 'error');
        //				}
        //			}
        //		});

        /**
         * Application Route
         *
         * @instance
         * */
        UMI.ApplicationRoute = Ember.Route.extend({
            /**
             * Метод `model` инициализирует модели данных, модули и компоненты для Dock.
             */
            model: function(){
                var self = this;
                var baseResource = UmiSettings.baseURL + '/api/settings.json';
                return $.getJSON(baseResource).then(function(results){
                    var result = results.result;
                    if(result.collections){
                        UMI.modelsFactory(result.collections);
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
                        self.controllerFor('dock').set('modules', result.modules);
                    }
                });
            },
            actions: {
                /**
                 * Метод вызывается каждый раз при смене роута в приложении.
                 * При переходе на роут `logout` выполняется abort для перехода,
                 * приложение становится неактивным и происходит смена шаблона на шаблон авторизации.
                 * */
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
                }
            }
        });

        UMI.IndexRoute = Ember.Route.extend({
            redirect: function(model, transition){
                if(transition.targetName === this.routeName){
                    var firstChild = this.controllerFor('dock').get('content.firstObject');
                    return this.transitionTo('module', 'news');//firstChild.get('name'));
                }
            }
        });

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
                    //return this.transitionTo('component', firstChild.get('name'));
                }
            },
            serialize: function(model){
                return {module: model.get('slug')};
            }
        });

        UMI.ComponentRoute = Ember.Route.extend({
            model: function(params, transition){
                var self = this;
                var components = this.modelFor('module').get('components');
                var model = components.findBy('slug', transition.params.component.component);
                var componentResource = window.UmiSettings.baseURL + '/api/' + transition.params.module.module + '/' + transition.params.component.component + '/settings.json';
                /**
                 * Получим ресурсы для компонента
                 */
                return $.getJSON(componentResource).then(function(results){
                    if(results.result.error){

                    } else if(results.result){
                        var nameModel;
                        var modes;
                        var hasTree;

                        for(nameModel in results.records){
                            if(results.records.hasOwnProperty(nameModel)){
                                self.store.pushMany(nameModel, results.records[nameModel]);
                            }
                        }
                        hasTree = results.treeSettings ? true : false;
                        self.controllerFor('component').set('hasTree', hasTree);
                        if(hasTree){
                            self.controllerFor('component').set('treeSettings', results.treeSettings);
                            self.controllerFor('component').set('treeType', results.treeSettings.root.type);
                        }
                        /**
                         * Установим режимы для компонента
                         * */
                        self.controllerFor('componentMode').set('id', results.treeSettings.root.ids[0]);
                        modes = self.store.all('componentMode').findBy('id', model.get('id'));
                        self.controllerFor('componentMode').set('modes', modes);
                    }
                    return model;
                });
            },

            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    //var rootTreeNode = this.store.all(this.controllerFor('component').get('treeType')).findBy('id', this.controllerFor('componentMode').get('id'));
                    //return this.transitionTo('treeActive', rootTreeNode);
                }
            },

            serialize: function(model){
                return {component: model.get('slug')};
            }
        });

        UMI.TreeActiveRoute = Ember.Route.extend({
            model: function(params){
                return this.store.find(this.controllerFor('component').get('treeType'), params.treeActive);
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var modes = this.controllerFor('componentMode').get('content');
                    var mode = modes.findBy('current', true);
                    return this.transitionTo('mode', mode.get('slug'));
                }
            },
            serialize: function(model){
                if(model){
                    return {treeActive: model.get('id')};
                }
            }
        });

        UMI.ModeRoute = Ember.Route.extend({
            model: function(params){
                var modes = this.controllerFor('componentMode').get('content');
                return modes.findBy('slug', params.mode);
            },
            redirect: function(model, transition){
                if(transition.targetName === this.routeName + '.index'){
                    var self = this;
                    var activeNode = this.modelFor('treeActive');

                    /**
                     * Сравниваем тип активного элемента в дереве с типом модели указанным в выбранном режиме
                     * если совпадают: перенаправляем на contentRoute соответсвующий активному объекту в дереве
                     * если не совпадают: перенаправляем на первый объект находящийся в указанной связи с выбранным объектом дерева
                     * */
                    if(activeNode.constructor.typeKey === this.modelFor('mode').get('contentType')){
                        return self.transitionTo('content', activeNode.get('id'));
                    } else{
                        var objects = this.modelFor('treeActive').get(model.get('contentType'));
                        return objects.then(function(objects){
                            return self.transitionTo('content', objects.get('firstObject.id'));
                        });
                    }
                }
            },
            serialize: function(model){
                if(model){
                    return {mode: model.get('slug')};
                }
            }
        });

        UMI.ContentRoute = Ember.Route.extend({
            model: function(params){
                var self = this;
                var activeMode = this.modelFor('mode');
                var results = {};
                return $.getJSON(activeMode.get('resources')).then(function(data){
                    for(var object in data.objects){
                        if(data.objects.hasOwnProperty(object)){
                            self.store.pushMany(object, data.objects[object]);
                        }
                    }
                    return self.store.find(self.modelFor('mode').get('contentType'), params.content).then(function(model){
                        results.object = model;
                        results.templateType = data.templateType;
                        results.meta = data.meta;
                        return results;
                    });
                });
            },
            serialize: function(results){
                if(results){
                    return {content: results.object.get('id')};
                }
            },
            renderTemplate: function(controller, results){
                this.render(results.templateType);
            },
            redirect: function(model, transition){
                if(transition.params.content.content === 'search'){
                    console.log('search');
                }
            }
        });
    };
});