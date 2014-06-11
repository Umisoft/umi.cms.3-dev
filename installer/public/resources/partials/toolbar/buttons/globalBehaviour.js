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
                    classNameBindings: ['controller.object.locked:hide'],
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
                }
            });
        };
    }
);