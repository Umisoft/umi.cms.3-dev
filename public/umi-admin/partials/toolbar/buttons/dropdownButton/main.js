define(['App', 'moment'],
    function(UMI, moment){
        "use strict";

        return function(){
            UMI.DropdownButtonView = Ember.View.extend({
                templateName: 'partials/dropdownButton',

                tagName: 'a',

                classNameBindings: 'meta.attributes.class',

                attributeBindings: ['title'],

                title: Ember.computed.alias('meta.attributes.title'),

                iconClass: function(){
                    return 'icon-' + this.get('meta.behaviour.name');
                }.property('meta.behaviour.name'),

                didInsertElement: function(){
                    var $el = this.$();
                    $el.on('click.umi.dropdown', function(event){
                        if(!$(event.target).closest('.f-dropdown').length){
                            event.stopPropagation();
                            var $button = $(this);
                            $button.toggleClass('open');
                            setTimeout(function(){
                                if($button.hasClass('open')){
                                    $('body').on('click.umi.dropdown.close', function(bodyEvent){
                                        bodyEvent.stopPropagation();
                                        var $buttonDropdown = $(bodyEvent.target).closest('.dropdown');
                                        if(!$buttonDropdown.length || $buttonDropdown[0].getAttribute('id') !== $button[0].getAttribute('id')){
                                            $('body').off('click.umi.dropdown.close');
                                            $button.removeClass('open');
                                        }
                                    });
                                }
                            }, 0);
                        }
                    });
                },
                willDestroyElement: function(){
                    var $el = this.$();
                    $el.off('click.umi.dropdown');
                },
                actions: {
                    sendActionForBehaviour: function(behaviour){
                        this.send(behaviour.name, {behaviour: behaviour});
                    }
                }
            });

            function DropdownButtonBehaviour(){}
            DropdownButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);
            DropdownButtonBehaviour.prototype.backupList = {
                classNames: ['coupled'],
                classNameBindings: ['isOpen:open'],
                isOpen: false,
                iScroll: null,
                tagName: 'div',
                templateName: 'partials/dropdownButton/backupList',

                noBackupsLabel: null,

                getBackupList: function(){
                    var backupList;
                    var self = this;
                    var object = self.get('controller.object');
                    var settings = self.get('controller.settings');
                    var getBackupListAction = UMI.Utils.replacePlaceholder(object, settings.actions.getBackupList.source);
                    var date = object.get('updated');
                    try{
                        date = JSON.parse(date);
                    } catch(error){}
                    var currentVersion = {
                        objectId: object.get('id'),
                        id: 'current',
                        current: true,
                        isActive: true
                    };

                    var promiseArray = DS.PromiseArray.create({
                        promise: $.get(getBackupListAction).then(function(data){
                            var results = [];
                            var serviceBackupList = Ember.get(data, 'result.getBackupList.collection.serviceBackup');
                            var users = Ember.get(data, 'result.getBackupList.collection.user');
                            var user;
                            var currentEditor;

                            UMI.i18n.setDictionary(Ember.get(data, 'result.getBackupList.i18n'), 'form.backupList');
                            self.set('noBackupsLabel', UMI.i18n.getTranslate('No backups', 'form.backupList'));
                            if(!serviceBackupList || !serviceBackupList.length){
                                return [];
                            }

                            var setCurrentEditor = function(currentEditor){
                                currentEditor.then(function(currentEditor){
                                    Ember.set(currentVersion, 'user', Ember.get(currentEditor, 'displayName'));
                                });
                            };

                            currentEditor = object.get('editor');
                            if(Ember.typeOf(currentEditor) === 'instance'){
                                setCurrentEditor(currentEditor);
                            } else{
                                currentEditor = object.get('owner');
                                if(Ember.typeOf(currentEditor) === 'instance'){
                                    setCurrentEditor(currentEditor);
                                }
                            }

                            Ember.set(currentVersion, 'created', {date: UMI.i18n.getTranslate('Current version', 'form.backupList')});
                            results.push(currentVersion);
                            if(Ember.typeOf(serviceBackupList) === 'array'){
                                for(var i = 0; i < serviceBackupList.length; i++){
                                    user = users.findBy('id', serviceBackupList[i].owner);
                                    serviceBackupList[i].user = user.displayName;
                                    Ember.set(serviceBackupList[i], 'created.date',  moment(Ember.get(serviceBackupList[i], 'created.date')).format('DD.MM.YYYY h:mm:ss'));
                                }
                                results = results.concat(serviceBackupList);
                            }
                            return results;
                        })
                    });

                    backupList = Ember.ArrayProxy.create({
                        content: promiseArray
                    });
                    return backupList;
                },

                backupList: null,

                actions: {
                    open: function(){
                        var self = this;
                        var el = this.$();
                        this.toggleProperty('isOpen');
                        if(this.get('isOpen')){
                            setTimeout(function(){
                                $('body').on('click.umi.controlDropUp', function(event){
                                    var targetElement = $(event.target).closest('.umi-dropup');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                        $('body').off('click.umi.controlDropUp');
                                        self.set('isOpen', false);
                                    }
                                });
                                if(self.get('iScroll')){
                                    self.get('iScroll').refresh();
                                }
                            }, 0);
                        }
                    },
                    applyBackup: function(backup){
                        if(backup.isActive){
                            return;
                        }
                        var self = this;
                        var object = this.get('controller.object');
                        var list = self.get('backupList');
                        var current = list.findBy('id', backup.id);
                        var setCurrent = function(){
                            list.setEach('isActive', false);
                            Ember.set(current, 'isActive', true);
                        };
                        var backupObjectAction;
                        if(backup.current){
                            object.rollback();
                            setCurrent();
                        } else{
                            backupObjectAction = UMI.Utils.replacePlaceholder(current, Ember.get(self.get('controller.settings'), 'actions.getBackup.source'));
                            $.get(backupObjectAction).then(function(data){
                                object.rollback();
                                delete data.result.getBackup.version;
                                delete data.result.getBackup.id;
                                // При обновлении свойств не вызываются методы desialize для атрибутов модели
                                self.get('controller.store').modelFor(object.constructor.typeKey).eachTransformedAttribute(function(name, type){
                                    if(type === 'CustomDateTime' && data.result.getBackup.hasOwnProperty(name) && Ember.typeOf(data.result.getBackup[name]) === 'object'){
                                        Ember.set(data.result.getBackup[name], 'date', moment(data.result.getBackup[name].date).format('DD.MM.YYYY h:mm:ss'));
                                        data.result.getBackup[name] = JSON.stringify(data.result.getBackup[name]);
                                    }
                                });
                                object.setProperties(data.result.getBackup);
                                setCurrent();
                            });
                        }
                    }
                },
                didInsertElement: function(){
                    var el = this.$();
                    var scroll;
                    var scrollElement = el.find('.s-scroll-wrap');
                    if(scrollElement.length){
                        scroll = new IScroll(scrollElement[0], UMI.config.iScroll);
                    }
                    this.set('iScroll', scroll);
                    var self = this;
                    self.set('backupList', self.getBackupList());
                    self.get('controller.object').off('didUpdate');
                    self.get('controller.object').on('didUpdate', function(){
                        self.set('backupList', self.getBackupList());
                    });

                    self.get('controller').addObserver('object', function() {//TODO: check event
                        if(self.get('controller.control.name') === 'editForm'){
                            self.set('backupList', self.getBackupList());
                        }
                    });
                },
                willDestroyElement: function(){
                    this.get('controller').removeObserver('object');
                    this.get('controller.object').off('didUpdate');
                }
            };


            DropdownButtonBehaviour.prototype.form = {
                classNames: ['coupled'],

                classNameBindings: ['isOpen:open'],

                isOpen: false,

                tagName: 'div',

                templateName: 'partials/dropdownButton/form',

                iconClass: function(){
                    return 'icon-edit'; //+ this.get('meta.behaviour.name');
                }.property('meta.behaviour.name'),

                actions: {
                    open: function(){
                        var self = this;
                        var el = this.$();
                        this.toggleProperty('isOpen');
                        if(this.get('isOpen')){
                            setTimeout(function(){
                                $('body').on('click.umi.controlDropDown.form', function(event){
                                    var targetElement = $(event.target).closest('.umi-dropdown');
                                    if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                        $('body').off('click.umi.controlDropDown.form');
                                        self.set('isOpen', false);
                                    }
                                });
                            }, 0);
                        }
                    }
                },

                formView: Ember.View.extend({
                    tagName: 'form',

                    templateName: 'partials/dropdownButton/formLayout',

                    attributeBindings: ['action'],

                    action: function(){
                        return this.get('form.attributes.action');
                    }.property('form.attributes.action'),

                    submit: function(){
                        return false;
                    },

                    object: function(){
                        var contextObject = this.get('controller.object');
                        var object = contextObject.toJSON({includeId: true});
                        return object;
                    }.property('controller.object'),

                    actions: {
                        submit: function(handler){
                            var self = this;
                            var object = self.get('object');
                            if(handler){
                                handler.addClass('loading');
                            }
                            var data = this.$().serializeArray();
                            var name;
                            for(var i = 0; i < data.length; i++){
                                name = data[i].name;
                                if(name){
                                    object[name] = data[i].value;
                                }
                            }
                            var serializeObject = JSON.stringify(object);
                            $.ajax({
                                url: self.get('action'),
                                type: "POST",
                                data: serializeObject,
                                contentType: 'application/json; charset=UTF-8'
                            }).then(function(results){
                                console.log(results);
                            });
                        }
                    }
                }),

                form: null,

                getForm: function(){
                    var self = this;
                    var meta = self.get('meta');
                    if(self.get('isDestroying') || self.get('isDestroyed')){
                        return;
                    }
                    var action = Ember.get(self.get('controller.settings'), 'actions.' + Ember.get(meta, 'behaviour.action') + '.source');
                    return $.get(action).then(function(results){
                        var form = Ember.get(results, 'result.' + Ember.get(meta, 'behaviour.action'));
                        self.set('form', form);
                    });
                },

                didInsertElement: function(){
                    var self = this;

                    self.set('form', self.getForm());

                    self.addObserver('controller.object', function() {//TODO: check event
                        Ember.run.next(self, function(){
                            this.set('form', self.getForm());
                        });
                    });
                },

                willDestroyElement: function(){
                    this.removeObserver('controller.object');
                }
            };

            UMI.dropdownButtonBehaviour = new DropdownButtonBehaviour();
        };
    }
);