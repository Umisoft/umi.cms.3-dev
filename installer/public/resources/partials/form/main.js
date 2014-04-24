define(
    [
        'App',
        'text!./form.hbs',
        './mixins',

        './elements/wysiwyg/main',
        './elements/select/main',
        './elements/multi-select/main',
        './elements/magellan/main',

        /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
        './elements/checkbox/main',
        './elements/radio/main',
        './elements/text/main',
        './elements/number/main',
        './elements/email/main',
        './elements/password/main',
        './elements/time/main',
        './elements/date/main',
        './elements/datetime/main',
        './elements/file/main',
        './elements/image/main',
        './elements/textarea/main',
        './formTypes/createForm/main'
    ],
    function(
            UMI,
            formTpl,
            mixin,

            wysiwygElement,
            selectElement,
            multiSelectElement,
            magellanElement,

            /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
            checkboxElement,
            radioElement,
            textElement,
            numberElement,
            emailElement,
            passwordElement,
            timeElement,
            dateElement,
            datetimeElement,
            fileElement,
            imageElement,
            textareaElement,
            createForm
        ){
        'use strict';

        Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

        mixin();

        wysiwygElement();
        selectElement();
        multiSelectElement();
        magellanElement();

        /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
        checkboxElement();
        radioElement();
        textElement();
        numberElement();
        emailElement();
        passwordElement();
        timeElement();
        dateElement();
        datetimeElement();
        fileElement();
        imageElement();
        textareaElement();

        UMI.FormControlController = Ember.ObjectController.extend({
            needs: ['component'],

            settings: function(){
                var settings = {};
                settings = this.get('controllers.component.settings');
                return settings;
            }.property(),

            hasFieldset: function(){
                var hasFieldset;
                try{
                    hasFieldset = this.get('content.viewSettings.elements').isAny('type', 'fieldset');
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    this.send('templateLogs', errorObject, 'component');
                } finally{
                    return hasFieldset;
                }
            }.property('model.@each'),

            switchActivity: function(){
                return this.get('settings').actions.activate && this.get('settings').actions.deactivate;
            }.property('model.@each'),
            hasBackups: function(){
                return !!this.get('settings').actions.getBackupList;
            }.property('model.@each'),
            backups: function(){
                var backups = {};
                var object = this.get('object');
                var settings = this.get('settings');
                if(this.get('hasBackups')){
                    backups.displayName = settings.actions.getBackupList.displayName;
                    var currentVersion = {
                        objectId: object.get('id'),
                        date: object.get('updated'),
                        user: null,
                        id: 'current',
                        current: true,
                        isActive: true
                    };
                    var results = [currentVersion];
                    var params = '?id=' + object.get('id');

                    var promiseArray = DS.PromiseArray.create({
                        promise: $.get(settings.actions.getBackupList.source + params).then(function(data){
                            return results.concat(data.result.getBackupList.serviceBackup);
                        })
                    });
                    backups.list = Ember.ArrayProxy.create({
                        content: promiseArray
                    });
                    return backups;
                }
            }.property('model.@each'),
            access: function(){
                var globalAllow = [
                    {
                        "name": "create",
                        "allow": true
                    },
                    {
                        "name": "read",
                        "allow": true
                    },
                    {
                        "name": "update",
                        "allow": false
                    },
                    {
                        "name": "delete",
                        "allow": false
                    }
                ];
                var AccessObject = Ember.Object.extend({
                    displayName: 'Права доступа',
                    action: {
                        displayName: 'Добавить пользователя'
                    },
                    actions: [
                        {
                            "name": "create",
                            "displayName": "Добавление"
                        },
                        {
                            "name": "read",
                            "displayName": "Чтение"
                        },
                        {
                            "name": "update",
                            "displayName": "Редактирование"
                        },
                        {
                            "name": "delete",
                            "displayName": "Удаление"
                        }
                    ],
                    global: {
                        "displayName": "Все пользователи",
                        "actions": [
                            {
                                "name": "create",
                                "allow": true
                            },
                            {
                                "name": "read",
                                "allow": true
                            },
                            {
                                "name": "update",
                                "allow": false
                            },
                            {
                                "name": "delete",
                                "allow": false
                            }
                        ]
                    },
                    users: [
                        {
                            "id": 1,
                            "displayName": "Супервайзер",
                            "actions": [
                                {
                                    "name": "create",
                                    "allow": true
                                },
                                {
                                    "name": "read",
                                    "allow": true
                                },
                                {
                                    "name": "update",
                                    "allow": true
                                },
                                {
                                    "name": "delete",
                                    "allow": true
                                }
                            ]
                        },
                        {
                            "id": 2,
                            "displayName": "Администратор",
                            "actions": [
                                {
                                    "name": "create",
                                    "allow": true
                                },
                                {
                                    "name": "read",
                                    "allow": true
                                },
                                {
                                    "name": "update",
                                    "allow": false
                                },
                                {
                                    "name": "delete",
                                    "allow": false
                                }
                            ]
                        }
                    ],
                    usersAllow: function(){// Жесть!
                        var users = this.get('users');
                        var global = this.get('global');
                        global.actions.forEach(function(action){
                            var oldAllow = globalAllow.findBy('name', action.name);
                            if(action.allow !== oldAllow.allow){
                                oldAllow.allow = action.allow;
                                users.forEach(function(user){
                                    Ember.set(user.actions.findBy('name', action.name), 'allow', action.allow);
                                });
                            }
                        });
                    }.observes('global.actions.@each.allow')
                });
                return AccessObject.create({});
            }.property('model.object'),
            actions: {
                applyBackup: function(backup){
                    if(backup.isActive){
                        return;
                    }
                    var self = this;
                    var object = this.get('model.object');
                    var list = self.get('backups.list');
                    var setCurrent = function(){
                        list.setEach('isActive', false);
                        var current = list.findBy('id', backup.id);
                        Ember.set(current, 'isActive', true);
                    };
                    object.rollback();
                    if(backup.current){
                        setCurrent();
                    } else{
                        var params = '?id=' + backup.objectId + '&backupId=' + backup.id;
                        $.get(self.get('settings').actions.getBackup.source + params).then(function(data){
                            object.setProperties(data.result.getBackup);
                            setCurrent();
                        });
                    }
                },
                toggleProperty: function(property){
                    this.get('model.object').toggleProperty(property);
                },
                switchActivity: function(){
                    var object = this.get('object');
                    this.get('controllers.component').send('switchActivity', object);
                }
            }
        });

        UMI.FormElementController = Ember.ObjectController.extend({
            objectBinding: 'parentController.model.object',
            isFieldset: function(){
                return this.get('content.type') === 'fieldset';
            }.property(),
            isExpanded: true,
            actions: {
                expand: function(){
                    this.toggleProperty('isExpanded');
                }
            }
        });

        UMI.FormControlView = Ember.View.extend({
            tagName: 'form',
            templateName: 'formControl',
            classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],
            submit: function(){
                return false;
            }
        });

        UMI.FieldView = Ember.View.extend({
            classNames: ['umi-columns'],
            classNameBindings: ['wide', 'isError:error'],
            isError: function(){
                var meta = this.get('meta');
                return !!this.get('object.validErrors.' + meta.dataSource);
            }.property('object.validErrors'),
            wide: function(){
                return this.get('meta.type') === 'wysiwyg' ? 'small-12' : 'large-4 small-12';
            }.property('meta.type'),

            layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{meta.label}}</span></div>{{yield}}'),

            template: function(){
                var meta = this.get('meta');
                var template;

                switch(meta.type){
                    case 'wysiwyg':         template = '{{html-editor           object=object property="' + meta.dataSource + '" validator="collection" dataSource=meta.dataSource}}'; break;
                    case 'select':          template = '{{view "select"         object=object meta=meta}}'; break;
                    case 'multi-select':    template = '{{view "multiSelect"    object=object meta=meta}}'; break;

                    case 'text':            template = '{{text-element          object=object meta=meta}}'; break;
                    case 'email':           template = '{{email-element         object=object meta=meta}}'; break;
                    case 'password':        template = '{{password-element      object=object meta=meta}}'; break;
                    case 'checkbox':        template = '{{checkbox-element      object=object meta=meta}}'; break;
                    case 'radio':           template = '{{radio-element         object=object meta=meta}}'; break;
                    case 'time':            template = '{{time-element          object=object meta=meta}}'; break;
                    case 'date':            template = '{{date-element          object=object meta=meta}}'; break;
                    case 'file':            template = '{{file-element          object=object meta=meta}}'; break;
                    case 'image':           template = '{{image-element         object=object meta=meta}}'; break;
                    case 'number':          template = '{{number-element        object=object meta=meta}}'; break;
                    case 'textarea':        template = '{{textarea-element      object=object meta=meta}}'; break;

                    default:                template = '<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>'; break;
                }

                template += '{{#if object.validErrors.' + meta.dataSource + '}}' + '<small class="error">' + '{{#each error in object.validErrors.' + meta.dataSource + '}}' + '{{error.message}}' + '{{/each}}' + '</small>' + '{{/if}}';
                template = Ember.Handlebars.compile(template);
                return template;
            }.property('object', 'meta')
        });

        //TODO: Кнопка ни как не связана с формой- можно вынести в отдельный компонент
        UMI.SaveButtonView = Ember.View.extend({
            tagName: 'button',
            classNameBindings: ['model.isDirty::disabled', 'model.isValid::disabled'],

            click: function(event){
                if(this.get('model.isDirty') && this.get('model.isValid')){
                    var button = this.$();
                    var model = this.get('model');
                    button.addClass('loading');
                    var params = {
                        object: model,
                        handler: button[0]
                    };
                    this.get('controller').send('save', params);
                }
            }
        });

        UMI.FormControlDropUpView = Ember.View.extend({
            classNames: ['dropdown', 'coupled'],
            classNameBindings: ['isOpen:open'],
            isOpen: false,
            iScroll: null,
            actions: {
                open: function(){
                    var self = this;
                    var el = this.$();
                    this.toggleProperty('isOpen');
                    if(this.get('isOpen')){
                        setTimeout(function(){
                            $('body').on('click.umi.form.controlDropUp', function(event){
                                var targetElement = $(event.target).closest('.umi-dropup');
                                if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                    $('body').off('.umi.form.controlDropUp');
                                    self.set('isOpen', false);
                                }
                            });
                            if(self.get('iScroll')){
                                self.get('iScroll').refresh();
                            }
                        }, 0);
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
            }
        });
        createForm();
    }
);