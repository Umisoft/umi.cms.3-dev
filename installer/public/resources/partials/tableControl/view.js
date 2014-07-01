define(['App', 'toolbar'], function(UMI){
    'use strict';
    return function(){

        UMI.TableControlView = Ember.View.extend({
            componentNameBinding: 'controller.controllers.component.name',
            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'tableControl',
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
                        var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                        var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                        var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];
                        iScroll.refresh();
                        umiTableLeft.style.marginTop = 0;
                        umiTableRight.style.marginTop = 0;
                        umiTableHeader.style.marginLeft = 0;
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
                var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];

                var umiTableContentRowSize = tableControl.find('.umi-table-control-content-row-size')[0];

                if(objects){
                    var tableContent = tableControl.find('.s-scroll-wrap');

                    objects.then(function(objects){
                        if(!objects.content.length){
                            return;
                        }

                        Ember.run.scheduleOnce('afterRender', self, function(){
                            var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll); //TODO Из-за этой строки не работают чекбоксы строк
                            self.set('iScroll', scrollContent);

                            scrollContent.on('scroll', function(){
                                umiTableLeft.style.marginTop = this.y + 'px';
                                umiTableRight.style.marginTop = this.y + 'px';
                                umiTableHeader.style.marginLeft = this.x + 'px';
                            });

                            // После ресайза страницы необходимо изменить отступы у элементов  umiTableLeft, umiTableRight, umiTableHeader
                            $(window).on('resize.umi.tableControl', function(){
                                umiTableLeft.style.marginTop = scrollContent.y + 'px';
                                umiTableRight.style.marginTop = scrollContent.y + 'px';
                                umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                            });

                            // Событие изменения ширины колонки
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-control-column-resizer', function(){
                                $('html').addClass('s-unselectable');
                                var handler = this; //Почему не that или self? Зачем плодить понятия?
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
                                var leftElements = umiTableLeft.querySelectorAll('.umi-table-control-column-fixed-cell');
                                var rightElements = umiTableRight.querySelectorAll('.umi-table-control-column-fixed-cell');
                                if(!isContentRow){
                                    el = tableContent[0].querySelectorAll('.umi-table-control-content-row')[i];
                                }
                                return [el, leftElements[i], rightElements[i]];
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
                this.get('controller').removeObserver('content.object.id');
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

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-control-content-row'],
                classNameBindings: ['object.type', 'object.active::umi-inactive'],
                attributeBindings: ['objectId'],
                objectIdBinding: 'object.id',
                click: function(){
                    if(this.get('object.meta.editLink')){
                        this.get('controller').transitionToRoute(this.get('object.meta.editLink').replace('/admin', ''));//TODO: fix replace
                    }
                }
            })
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-content-cell-div'],
            classNameBindings: ['columnId'],

            template: function(){
                var column;
                var object;
                var template = '';
                var value;
                try{
                    object = this.get('object');
                    column = this.get('column');
                    switch(column.type){
                        case 'checkbox':
                            value = object.get(column.dataSource);
                            template = '<span class="umi-checkbox-state-checked"></span>';
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
                            value = object.get(column.dataSource);
                            if(Ember.typeOf(value) === 'null'){
                                value = '';
                            }
                            template = value + '&nbsp;';
                            break;
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);
                } finally{
                    return Ember.Handlebars.compile(template);
                }
            }.property('column')
        });


        UMI.TableControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'table-context-toolbar'],
            elementView: UMI.ToolbarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.SplitButtonDefaultBehaviourForComponent, UMI.SplitButtonSharedSettingsBehaviour);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour;
                    var i;
                    var action;
                    if(behaviourName){
                        behaviour = UMI.splitButtonBehaviour.get(behaviourName) || {};
                    } else{
                        behaviour = {};
                    }
                    var choices = this.get('context.behaviour.choices');
                    if(behaviourName === 'contextMenu' && Ember.typeOf(choices) === 'array'){
                        for(i = 0; i < choices.length; i++){
                            var prefix = '';
                            var behaviourAction = UMI.splitButtonBehaviour.get(choices[i].behaviour.name);
                            if(behaviourAction.hasOwnProperty('_actions')){
                                prefix = '_';
                            }
                            action = behaviourAction[prefix + 'actions'][choices[i].behaviour.name];
                            if(action){
                                if(Ember.typeOf(behaviour.actions) !== 'object'){
                                    behaviour.actions = {};
                                }
                                behaviour.actions[choices[i].behaviour.name] = action;
                            }
                        }
                    }
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});