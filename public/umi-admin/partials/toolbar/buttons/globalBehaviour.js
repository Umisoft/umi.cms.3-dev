define(['App'], function(UMI) {
        'use strict';

        return function() {
            /**
             * Абстрактный класс поведения
             * @class
             * @abstract
             */
            function GlobalBehaviour() {
            }

            GlobalBehaviour.prototype = {
                save: {
                    extendButton: {
                        label: function() {
                            if (this.get('controller.object.isDirty')) {
                                return this.get('defaultBehaviour.attributes.label');
                            } else {
                                return this.get('meta.attributes.states.notModified.label');
                            }
                        }.property('meta.attributes.label', 'controller.object.isDirty', 'defaultBehaviour'),

                        classNameBindings: ['controller.object.isDirty::disabled',
                            'controller.object.isValid::disabled']
                    },

                    beforeSave: function() {
                        var model = this.get('controller.object');
                        if (!model.get('isDirty') || !model.get('isValid')) {
                            return false;
                        }
                        var button = this.$().children('.button');
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },

                    actions: {
                        save: function() {
                            var params = this.beforeSave();
                            if (params) {
                                this.get('controller').send('save', params);
                            }
                        },

                        saveAndGoBack: function() {
                            var params = this.beforeSave();
                            if (params) {
                                this.get('controller').send('saveAndGoBack', params);
                            }
                        }
                    }
                },

                'create': {
                    actions: {
                        'create': function(params) {
                            var behaviour = params.behaviour;
                            var object = params.object || this.get('controller.object');
                            this.get('controller').send('create', {behaviour: behaviour, object: object});
                        }
                    }
                },

                switchActivity: {
                    label: function() {
                        if (this.get('controller.object.active')) {
                            return this.get('meta.attributes.states.deactivate.label');
                        } else {
                            return this.get('meta.attributes.states.activate.label');
                        }
                    }.property('meta.attributes.label', 'controller.object.active'),

                    classNameBindings: ['controller.object.active::umi-disabled'],

                    iconClass: function() {
                        var iconClass = 'inactive';
                        if (this.get('controller.object.active')) {
                            iconClass = 'active';
                        }
                        return 'icon-' + iconClass;
                    }.property('meta.behaviour.name', 'controller.object.active'),

                    actions: {
                        switchActivity: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('switchActivity', model);
                        }
                    }
                },

                backToFilter: {
                    classNames: ['wide-medium', 'umi-toolbar-button-border'],

                    actions: {
                        backToFilter: function() {
                            this.get('controller').send('backToFilter');
                        }
                    }
                },

                trash: {
                    actions: {
                        trash: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('trash', model);
                        }
                    }
                },

                untrash: {
                    actions: {
                        untrash: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('untrash', model);
                        }
                    }
                },

                'delete': {
                    actions: {
                        'delete': function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('delete', model);
                        }
                    }
                },

                viewOnSite: {
                    actions: {
                        viewOnSite: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('viewOnSite', model);
                        }
                    }
                },

                edit: {
                    actions: {
                        edit: function(params) {
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('edit', model);
                        }
                    }
                },

                add: {
                    classNameBindings: ['controller.object.isValid::disabled'],

                    beforeAdd: function(params) {
                        params = params || {};
                        var model = params.object || this.get('controller.object');
                        if (!model.get('isValid')) {
                            return false;
                        }
                        var button = this.$();
                        button.addClass('loading');
                        params = {
                            object: model,
                            handler: button[0]
                        };
                        return params;
                    },

                    actions: {
                        add: function(params) {
                            params = params || {};
                            var addParams = this.beforeAdd(params.object);
                            if (addParams) {
                                this.get('controller').send('add', addParams);
                            }
                        },

                        addAndGoBack: function() {
                            var params = this.beforeAdd();
                            if (params) {
                                this.get('controller').send('addAndGoBack', params);
                            }
                        },

                        addAndCreate: function() {
                            var params = this.beforeAdd();
                            if (params) {
                                this.get('controller').send('addAndCreate', params);
                            }
                        }
                    }
                },

                importFromRss: {
                    actions: {
                        importFromRss: function() {
                            var model = this.get('controller.object');
                            this.get('controller').send('importFromRss', model);
                        }
                    }
                },

                switchRobots: {
                    isAllowedRobots: null,

                    label: function() {
                        var title;
                        if (this.get('isAllowedRobots')) {
                            title = this.get('meta.attributes.states.disallow.label');
                        } else {
                            title = this.get('meta.attributes.states.allow.label');
                        }
                        return title;
                    }.property('meta.attributes.label', 'isAllowedRobots'),

                    iconClass: function() {
                        if (this.get('isAllowedRobots')) {
                            return 'icon-allowRobots';
                        } else {
                            return 'icon-disallowRobots';
                        }
                    }.property('isAllowedRobots'),

                    actions: {
                        switchRobots: function() {
                            var self = this;
                            var defer = Ember.RSVP.defer();
                            var promise = defer.promise;
                            var currentState = this.get('isAllowedRobots');
                            var model = this.get('controller.object');
                            this.get('controller').send('switchRobots', model, currentState, defer);
                            promise.then(function() {
                                self.set('isAllowedRobots', !currentState);
                            });
                        }
                    },

                    checkIsAllowedRobots: function() {
                        var self = this;
                        var object = this.get('controller.object');
                        var componentController = this.get('container').lookup('controller:component');
                        var isAllowedRobotsSource;

                        if (componentController) {
                            isAllowedRobotsSource = componentController.get('settings.actions.isAllowedRobots.source');
                            return $.get(isAllowedRobotsSource + '?id=' + object.get('id')).then(function(results) {
                                results = results || {};
                                self.set('isAllowedRobots', Ember.get(results, 'result.isAllowedRobots'));
                            });
                        }
                    },

                    willDestroyElement: function() {
                        this.removeObserver('label');
                    },

                    labelChange: function() {
                        var $el = this.$();
                        if ($el && $el.attr('title')) {
                            $el.attr('title', this.get('label'));
                        }
                    }.observes('label').on('didInsertElement'),

                    init: function() {
                        this._super();
                        Ember.run.once(this, 'checkIsAllowedRobots');
                    }
                }
            };

            UMI.globalBehaviour = new GlobalBehaviour();
        };
    });