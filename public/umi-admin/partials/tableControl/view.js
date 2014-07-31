define(['App', 'toolbar'], function(UMI){
    'use strict';
    return function(){

        UMI.TableControlViewMixin = Ember.Mixin.create({
            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/tableControl',
            /**
             * Классы для view
             * @classNames
             */
            classNames: ['umi-table-control'],
            /**
             * @property iScroll
             */
            iScroll: null,
            /**
             * При изменении данных вызывает ресайз скрола.
             * @method scrollUpdate
             * @observer
             */
            scrollUpdate: function(){
                var self = this;
                var tableControl = this.$();
                var objects = this.get('controller.objects.content');
                var iScroll = this.get('iScroll');

                var scrollUpdate = function(){
                    Ember.run.scheduleOnce('afterRender', self, function(){
                        // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                        //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                        var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                        var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];
                        //umiTableLeft.style.marginTop = 0;
                        umiTableRight.style.marginTop = 0;
                        umiTableHeader.style.marginLeft = 0;
                        setTimeout(function(){
                            iScroll.refresh();
                            iScroll.scrollTo(0, 0);
                        }, 0);
                    });
                };

                if(objects && iScroll){
                    objects.then(function(){
                        scrollUpdate();
                    });
                }
            }.observes('controller.objects').on('didInsertElement'),
            /**
             * Событие вызываемое после вставки шаблона в DOM
             * @method didInsertElement
             */
            didInsertElement: function(){
                var tableControl = this.$();

                var self = this;
                var objects = this.get('controller.objects.content');

                // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                //var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];

                var umiTableContentRowSize = tableControl.find('.umi-table-control-content-row-size')[0];

                if(objects){
                    var tableContent = tableControl.find('.s-scroll-wrap');

                    objects.then(function(objects){
                        if(!objects.length){
                            return;
                        }

                        Ember.run.scheduleOnce('afterRender', self, function(){
                            var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll);
                            self.set('iScroll', scrollContent);

                            scrollContent.on('scroll', function(){
                                //umiTableLeft.style.marginTop = this.y + 'px';
                                umiTableRight.style.marginTop = this.y + 'px';
                                umiTableHeader.style.marginLeft = this.x + 'px';
                            });

                            // После ресайза страницы необходимо изменить отступы у элементов  umiTableLeft, umiTableRight, umiTableHeader
                            $(window).on('resize.umi.tableControl', function(){
                                setTimeout(function(){
                                    //umiTableLeft.style.marginTop = scrollContent.y + 'px';
                                    umiTableRight.style.marginTop = scrollContent.y + 'px';
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                }, 100);// TODO: заменить на событие окончания ресайза iScroll
                            });

                            // Событие изменения ширины колонки
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-control-column-resizer', function(){
                                $('html').addClass('s-unselectable');
                                var handler = this;
                                $(handler).addClass('on-resize');
                                var columnEl = handler.parentNode.parentNode;
                                var columnName = columnEl.className;
                                columnName = columnName.substr(columnName.indexOf('column-id-'));
                                var columnOffset = $(columnEl).offset().left;
                                var columnWidth;
                                var contentCell = umiTableContentRowSize.querySelector('.' + columnName);

                                $('body').on('mousemove.umi.tableControl', function(event){
                                    event.stopPropagation();
                                    columnWidth = event.pageX - columnOffset;
                                    if(columnWidth >= 60 && columnEl.offsetWidth > 59){
                                        columnEl.style.width = contentCell.style.width = columnWidth + 'px';
                                    }
                                });

                                $('body').on('mouseup.umi.tableControl', function(){
                                    $('html').removeClass('s-unselectable');
                                    $(handler).removeClass('on-resize');
                                    $('body').off('mousemove');
                                    $('body').off('mouseup.umi.tableControl');
                                    scrollContent.refresh();
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                });
                            });

                            // Hover event
                            var getHoverElements = function(el){
                                var isContentRow = $(el).hasClass('umi-table-control-content-row');
                                var rows = el.parentNode.querySelectorAll(isContentRow ? '.umi-table-control-content-row' : '.umi-table-control-column-fixed-cell');

                                for(var i = 0; i < rows.length; i++){
                                    if(rows[i] === el){
                                        break;
                                    }
                                }
                                //var leftElements = umiTableLeft.querySelectorAll('.umi-table-control-column-fixed-cell');
                                var rightElements = umiTableRight.querySelectorAll('.umi-table-control-column-fixed-cell');
                                if(!isContentRow){
                                    el = tableContent[0].querySelectorAll('.umi-table-control-content-row')[i];
                                }
                                return [el, rightElements[i]];//[el, leftElements[i], rightElements[i]];
                            };

                            tableControl.on('mouseenter.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).addClass('hover');
                            });

                            tableControl.on('mouseleave.umi.tableControl', '.umi-table-control-content-row, .umi-table-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).removeClass('hover');
                            });
                            // Drag and Drop
                        });
                    });
                }
            },
            /**
             * Событие вызываемое после удаления шаблона из DOM
             * @method willDestroyElement
             */
            willDestroyElement: function(){
                $(window).off('.umi.tableControl');
                // Удаляем Observes для контоллера
                this.get('controller').removeObserver('query');
            },

            paginationView: Ember.View.extend({
                classNames: ['right', 'umi-table-control-pagination'],

                counter: function(){
                    var label = 'из';
                    var limit = this.get('controller.limit');
                    var offset = this.get('controller.offset') + 1;
                    var total = this.get('controller.total');
                    var maxCount = offset*limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('controller.limit', 'controller.offset', 'controller.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        return this.get('controller.offset');
                    }.property('controller.offset'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('controller').decrementProperty('offset');
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        var limit = this.get('controller.limit');
                        var offset = this.get('controller.offset') + 1;
                        var total = this.get('controller.total');
                        return total > limit * offset;
                    }.property('controller.limit', 'controller.offset', 'controller.total'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('controller').incrementProperty('offset');
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function(){
                        return this.get('controller.limit');
                    }.property('controller.limit'),

                    type: 'text',

                    keyDown: function(event){
                        if(event.keyCode === 13){
                            // При изменении количества строк на странице сбрасывается offset
                            this.get('controller').setProperties({'offset': 0, 'limit': this.$()[0].value});
                        }
                    }
                })
            }),

            sortHandlerView: Ember.View.extend({
                classNames: ['button', 'flat', 'tiny', 'square', 'sort-handler'],
                classNameBindings: ['isActive:active'],
                sortAscending: true,

                isActive: function(){
                    var orderByProperty = this.get('controller.orderByProperty');
                    if(orderByProperty){
                        return this.get('propertyName') === orderByProperty.property;
                    }
                }.property('controller.orderByProperty'),

                click: function(){
                    var propertyName = this.get('propertyName');

                    $('.umi-table-control-header-cell .icon-top-thin:not(.active)').removeClass('icon-top-thin').addClass('icon-bottom-thin');

                    if(this.get('isActive')){
                        this.toggleProperty('sortAscending');
                    }

                    var sortAscending = this.get('sortAscending');
                    this.get('controller').send('orderByProperty', propertyName, sortAscending);
                }
            }),

            hasRowEvent: false,
            /**
             * @hook
             * @method rowEvent
             */
            rowEvent: function(){},

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-control-content-row'],
                classNameBindings: ['object.type', 'isActive::umi-inactive', 'parentView.hasRowEvent:s-pointer', 'isSelected:selected'],
                isActive: function(){//TODO: оптимизировать. Нет необходимости для каждого объекта проверять метаданные.
                    var object = this.get('object');
                    var hasActiveProperty  = false;
                    object.eachAttribute(function(name){
                        if(name === 'active'){
                            hasActiveProperty = true;
                        }
                    });
                    if(hasActiveProperty){
                        return object.get('active');
                    } else{
                        return true;
                    }
                }.property('object.active'),

                isSelected: function(){
                    var objectGuid = this.get('object.guid');
                    return objectGuid === this.get('controller.control.meta.activeObjectGuid');
                }.property('controller.control.activeObjectGuid'),

                attributeBindings: ['objectId'],

                objectIdBinding: 'object.id',

                click: function(){
                    if(this.get('parentView.hasRowEvent') && !this.get('isSelected')){
                        this.get('parentView').rowEvent(this.get('object'));
                    }
                }
            }),

            filterRowView: Ember.View.extend({
                filterType: null,
                template: function(){
                    var column = this.get('column');
                    var template = '';
                    switch(Ember.get(column, 'attributes.type')){
                        case 'text':
                            this.set('filterType', 'text');
                            template = '<input type="text" class="table-control-filter-input"/>';
                            break;
                    }
                    return Ember.Handlebars.compile(template);
                }.property('column'),
                didInsertElement: function(){
                    var self = this;
                    var $el = this.$();
                    var $input = $el.children('input');
                    var filterType = this.get('filterType');
                    $input.on('focus', function(){
                        $(this).closest('.umi-table-control-row').find('.table-control-filter-input').val('');
                    });
                    switch(filterType){
                        case 'text':
                            $input.on('keypress.umi.tableControl.filters', function(event){
                               if(event.keyCode === 13){
                                   self.setFilter('like(%' + this.value + '%)');
                               }
                           });
                            break;
                    }
                },
                setFilter: function(filter){
                    this.get('controller').setFilters(this.get('column.dataSource'), filter);
                }
            })
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-content-cell-div'],

            classNameBindings: ['columnId'],

            promise: null,

            template: function(){
                var column;
                var object;
                var template = '';
                var value;
                var self = this;
                var properties;
                function propertyHtmlEncode(value){
                    if(Ember.typeOf(value) === 'null'){
                        value = '';
                    } else{
                        value = UMI.Utils.htmlEncode(value);
                    }
                    return value;
                }
                try{
                    object = this.get('object');
                    column = this.get('column');
                    switch(column.type){
                        case 'checkbox':
                            template = '<span {{bind-attr class="view.object.' + column.dataSource + ':umi-checkbox-state-checked:umi-checkbox-state-unchecked"}}></span>&nbsp;';
                            break;
                        case 'checkboxGroup':
                        case 'multiSelect':
                            value = object.get(column.dataSource);
                            if(Ember.typeOf(value) === 'array'){
                                template = value.join(', ');
                            }
                            break;
                        case 'datetime':
                            value = object.get(column.dataSource);
                            if(value){
                                try{
                                    value = JSON.parse(value);
                                    template = Ember.get(value, 'date');
                                } catch(error){
                                    this.get('controller').send('backgroundError', error);
                                }
                            }
                            break;
                        default:
                            properties = column.dataSource.split('.');
                            if(this.checkRelation(properties[0])){
                                if(properties.length > 1){
                                    value = object.get(properties[0]);
                                    if(Ember.typeOf(value) === 'instance'){
                                        value.then(function(object){
                                            value = object.get(properties[1]);
                                            value = propertyHtmlEncode(value);
                                            self.set('promiseProperty', value);
                                        });
                                        template = '{{view.promiseProperty}}';
                                    }
                                } else{
                                    template = '{{view.object.' + column.dataSource + '.displayName}}';
                                }
                            } else{
                                value = object.get(column.dataSource);
                                value = propertyHtmlEncode(value);
                                template = value;
                            }
                            break;
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);
                } finally{
                    return Ember.Handlebars.compile(template);
                }
            }.property('column'),

            checkRelation: function(property){
                var object = this.get('object');
                var isRelation = false;
                object.eachRelationship(function(name, relatedModel){
                    if(property === name){
                        isRelation = true;
                    }
                });
                return isRelation;
            }
        });

        UMI.TableControlView = Ember.View.extend(UMI.TableControlViewMixin, {
            componentNameBinding: 'controller.controllers.component.name',

            hasRowEvent: function(){
                var objectsEditable = this.get('controller.control.params.objectsEditable');
                objectsEditable = objectsEditable === false ? false : true;
                return objectsEditable;
            }.property('controller.control.params.objectsEditable'),

            rowEvent: function(object){
                if(object.get('meta.editLink')){
                    this.get('controller').transitionToRoute(object.get('meta.editLink').replace(Ember.get(window, 'UmiSettings.baseURL'), ''));
                }
            },

            willDestroyElement: function(){
                this.get('controller').removeObserver('objects.@each.isDeleted');
                this.get('controller').removeObserver('content.object.id');
            }
        });

        UMI.TableControlSharedView = Ember.View.extend(UMI.TableControlViewMixin, {
            componentNameBinding: null,

            hasRowEvent: true,

            rowEvent: function(object){
                this.get('controller').send('executeBehaviour', 'rowEvent', object);
            },

            willDestroyElement: function(){
                this.get('controller').removeObserver('control.collectionName');
            }
        });

        UMI.TableControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'table-context-toolbar'],
            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent, UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = {};
                    var splitButtonBehaviour;
                    var i;
                    var action;
                    if(behaviourName){
                        splitButtonBehaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                        for(var key in splitButtonBehaviour){
                            if(splitButtonBehaviour.hasOwnProperty(key)){
                                behaviour[key] = splitButtonBehaviour[key];
                            }
                        }
                    }
                    var choices = this.get('context.behaviour.choices');
                    if(behaviourName === 'contextMenu' && Ember.typeOf(choices) === 'array'){
                        for(i = 0; i < choices.length; i++){
                            action = '';
                            var behaviourAction = Ember.get(UMI.splitButtonBehaviour, choices[i].behaviour.name);
                            if(behaviourAction){
                                action = behaviourAction.actions[choices[i].behaviour.name];
                                if(action){
                                    if(Ember.typeOf(behaviour.actions) !== 'object'){
                                        behaviour.actions = {};
                                    }
                                    behaviour.actions[choices[i].behaviour.name] = action;
                                }
                            }
                        }
                    }
                    behaviour.classNames = ['white square'];
                    behaviour.label = null;
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});