define(
    ['App'],
    function(UMI){
        "use strict";

        return function(){
            /**
             * Абстрактный класс поведения
             * @class
             * @abstract
             */
            UMI.GlobalBehaviour = Ember.Object.extend({
                save: {
                    label: function(){
                        if(this.get('controller.object.isDirty')){
                            return this.get('defaultBehaviour.attributes.label');
                        } else{
                            return this.get('meta.attributes.states.notModified.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.isDirty', 'defaultBehaviour'),
                    classNameBindings: ['controller.object.isDirty::disabled', 'controller.object.isValid::disabled'],
                    beforeSave: function(){
                        var model = this.get('controller.object');
                        if(!model.get('isDirty') || !model.get('isValid')){
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },
                    actions: {
                        save: function(){
                            var params = this.beforeSave();
                            if(params){
                                this.get('controller').send('save', params);
                            }
                        },

                        saveAndGoBack: function(){
                            var params = this.beforeSave();
                            if(params){
                                this.get('controller').send('saveAndGoBack', params);
                            }
                        }
                    }
                },

                create: {
                    actions: {
                        create: function(behaviour){
                            var model = this.get('controller.object');
                            this.get('controller').send('create', model, behaviour);
                        }
                    }
                },

                switchActivity: {
                    label: function(){
                        if(this.get('controller.object.active')){
                            return this.get('meta.attributes.states.deactivate.label');
                        } else{
                            return this.get('meta.attributes.states.activate.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.active'),
                    classNameBindings: ['controller.object.active::umi-disabled'],
                    actions: {
                        switchActivity: function(){
                            var model = this.get('controller.object');
                            this.get('controller').send('switchActivity', model);
                        }
                    }
                },

                backToFilter: {
                    actions: {
                        backToFilter: function(){
                            this.get('controller').send('backToFilter');
                        }
                    }
                },

                trash: {
                    actions: {
                        trash: function(){
                            var model = this.get('controller.object');
                            this.get('controller').send('trash', model);
                        }
                    }
                },

                "delete": {
                    actions: {
                        "delete": function(){
                            var model = this.get('controller.object');
                            this.get('controller').send('delete', model);
                        }
                    }
                },

                viewOnSite: {
                    actions: {
                        viewOnSite: function(){
                            var model = this.get('controller.object');
                            this.get('controller').send('viewOnSite', model);
                        }
                    }
                },

                edit: {
                    actions: {
                        edit: function(){
                            var model = this.get('controller.object');
                            this.get('controller').send('edit', model);
                        }
                    }
                },

                add: {
                    classNameBindings: ['controller.object.isValid::disabled'],
                    beforeAdd: function(){
                        var model = this.get('controller.object');
                        if(!model.get('isValid')){
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },
                    actions: {
                        add: function(){
                            var params = this.beforeAdd();
                            if(params){
                                this.get('controller').send('add', params);
                            }
                        },

                        addAndGoBack: function(){
                            var params = this.beforeAdd();
                            if(params){
                                this.get('controller').send('addAndGoBack', params);
                            }
                        },

                        addAndCreate: function(){
                            var params = this.beforeAdd();
                            if(params){
                                this.get('controller').send('addAndCreate', params);
                            }
                        }
                    }
                },

                switchRobots: {
                    isAllowedRobots: null,
                    label: function(){
                        if(this.get('isAllowedRobots')){
                            return this.get('meta.attributes.states.disallow.label');
                        } else{
                            return this.get('meta.attributes.states.allow.label');
                        }
                    }.property('meta.attributes.label', 'isAllowedRobots'),
                    iconClass: function(){
                        if(this.get('isAllowedRobots')){
                            return 'icon-allowRobots';
                        } else{
                            return 'icon-disallowRobots';
                        }
                    }.property('isAllowedRobots'),
                    actions: {
                        switchRobots: function(){
                            var self = this;
                            var defer = Ember.RSVP.defer();
                            var promise = defer.promise;
                            var currentState = this.get('isAllowedRobots');
                            var model = this.get('controller.object');
                            this.get('controller').send('switchRobots', model, currentState, defer);
                            promise.then(function(){
                                self.set('isAllowedRobots', !currentState);
                            });
                        }
                    },
                    checkIsAllowedRobots: function(){
                        var self = this;
                        var object = this.get('controller.object');
                        var componentController = this.get('container').lookup('controller:component');
                        var isAllowedRobotsSource;
                        var serializeObject;
                        if(componentController){
                            serializeObject = JSON.stringify(object.toJSON({includeId: true}));
                            isAllowedRobotsSource = componentController.get('settings.actions.isAllowedRobots.source');
                            return $.get(isAllowedRobotsSource + '?id=' + object.get('id')).then(function(results){
                                results = results || {};
                                self.set('isAllowedRobots', Ember.get(results, 'result.isAllowedRobots'));
                            });
                        }
                    },
                    checkIsAllowedRobotsChange: function(){
                        Ember.run.once(this, 'checkIsAllowedRobots');
                    }.observes('controller.object').on('init'),

                    willDestroyElement: function(){
                        this.removeObserver('controller.object');
                    }
                }
            });
        };
    }
);