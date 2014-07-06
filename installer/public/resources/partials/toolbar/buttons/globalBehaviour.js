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
                        create: function(params){
                            var behaviour = params.behaviour;
                            var object = params.object || this.get('controller.object');
                            this.get('controller').send('create', {behaviour: behaviour, object: object});
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
                        switchActivity: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
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
                        trash: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('trash', model);
                        }
                    }
                },

                "delete": {
                    actions: {
                        "delete": function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('delete', model);
                        }
                    }
                },

                viewOnSite: {
                    actions: {
                        viewOnSite: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('viewOnSite', model);
                        }
                    }
                },

                edit: {
                    actions: {
                        edit: function(params){
                            params = params || {};
                            var model = params.object || this.get('controller.object');
                            this.get('controller').send('edit', model);
                        }
                    }
                },

                add: {
                    classNameBindings: ['controller.object.isValid::disabled'],
                    beforeAdd: function(params){
                        params = params || {};
                        var model = params.object || this.get('controller.object');
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
                        add: function(params){
                            params = params || {};
                            var addParams = this.beforeAdd(params.object);
                            if(addParams){
                                this.get('controller').send('add', addParams);
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
                }
            });
        };
    }
);