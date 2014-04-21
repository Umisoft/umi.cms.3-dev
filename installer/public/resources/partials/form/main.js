define(
    [
        'App',
        'text!./form.hbs',
        './mixins',
        './elements/input/main',
        './elements/checkbox/main',
        './elements/textarea/main',
        './elements/htmlEditor/main',
        './elements/select/main',
        './elements/multiSelect/main',
        './elements/datepicker/main',
        './elements/magellan/main'
    ],
    function(UMI, formTpl, mixin, inputElement, checkboxElement, textareaElement, htmlEditorElement, selectElement, multiSelectElement, datepickerElement, magellanElement){
        'use strict';

        Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

        mixin();
        inputElement();
        checkboxElement();
        textareaElement();
        htmlEditorElement();
        selectElement();
        multiSelectElement();
        datepickerElement();
        magellanElement();

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
                return !!this.get('settings').actions.switchActivity;
            }.property('model.@each'),
            hasBackups: function(){
                return !!this.get('settings').actions.getBackupList;
            }.property('model.@each'),
            backups: function(){// TODO: Выполняется лишний раз при уходе с роута http://youtrack.umicloud.ru/issue/cms-308
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
                    if(backup.current){
                        object.rollback();
                        setCurrent();
                    } else{
                        var params = '?id=' + backup.objectId + '&backupId=' + backup.id;
                        $.get(self.get('settings').actions.getBackup.source + params).then(function(data){
                            object.setProperties(data.result.backup);
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
            layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{meta.label}}</label></div>{{yield}}'),
            template: function(){
                var meta = this.get('meta');
                var template;
                switch(meta.type){
                    case 'text':
                        template = '{{input type="text" value=object.' + meta.dataSource + ' placeholder=meta.placeholder validator="collection" dataSource=meta.dataSource}}';
                        break;
                    case 'textarea':
                        template = '{{textarea value=object.' + meta.dataSource + ' placeholder=meta.placeholder validator="collection" dataSource=meta.dataSource}}';
                        break;
                    case 'wysiwyg':
                        template = '{{html-editor object=object property="' + meta.dataSource + '" validator="collection" dataSource=meta.dataSource}}';
                        break;
                    case 'number': // TODO: Поле типа "number" в firefox не работает
                        template = '{{input type="number" value=object.' + meta.dataSource + ' validator="collection" dataSource=meta.dataSource}}';
                        break;
                    case 'checkbox':
                        template = '{{input type="checkbox" checked=object.' + meta.dataSource + ' name=meta.name validator="collection" dataSource=meta.dataSource}}<label for="' + meta.name + '"></label>';
                        break;
                    case 'select':
                        template = '{{view "select" object=object meta=meta}}';
                        break;
                    case 'multi-select':
                        template = '{{view "multiSelect" object=object meta=meta}}';
                        break;
                    case 'datetime':
                        template = '{{date-picker object=object property="' + meta.dataSource + '"}}';
                        break;
                    case 'file':
                        template = '<div class="umi-input-wrapper-file">{{input type="file" class="umi-file" value=object.' + meta.dataSource + '}}<i class="icon icon-cloud"></i></div>';
                        break;
                    default:
                        template = '<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>';
                        break;
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

    });