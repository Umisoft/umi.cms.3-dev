define(['App', 'toolbar'], function(UMI){
    'use strict';
    return function(){
        UMI.TreeControlView = Ember.View.extend({
            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/treeControl',
            /**
             * Имена классов
             * @property classNames
             */
            classNames: ['row', 's-full-height'],
            /**
             * @property iScroll
             */
            iScroll: null,

            /**
             * Observer для активного контекста
             * @observer activeContext
             */
            activeContextChange: function(){
                Ember.run.once(this, 'expandBranch');
            }.observes('controller.activeContext').on('didInsertElement'),
            /**
             * Раскрывает загруженную ветвь дерева и выставляет значение expandBranch
             * @method expandBranch
             */
            expandBranch: function(){
                var $el = this.$();
                var activeContext = this.get('controller.activeContext');
                var checkExpandItem = function(id){
                    if($el.length){
                        var itemView = $el.find('[data-id='+ id +']');
                        return itemView.length ? true : false;
                    }
                };
                if(activeContext){
                    var mpath = [];
                    var contextMpath = [];
                    var needsExpandedItems = [];
                    mpath.push('root');
                    if(activeContext.get('id') !== 'root' && activeContext.get('mpath')){
                        contextMpath = activeContext.get('mpath').without(parseFloat(activeContext.get('id'))) || [];
                    }
                    contextMpath = mpath.concat(contextMpath);
                    for(var i = 0, size = contextMpath.length; i < size; i++){
                        if(checkExpandItem(contextMpath[i])){
                            this.send('expandItem', contextMpath[i]);
                        } else{
                            needsExpandedItems.push(contextMpath[i]);
                        }
                    }
                    this.get('controller').set('needsExpandedItems', needsExpandedItems);

                }
            },

            actions: {
                expandItem: function(id){
                    if(this.$()){
                        var itemView = this.$().find('[data-id='+ id +']');
                        if(itemView.length){
                            itemView = Ember.View.views[itemView[0].id];
                            if(itemView && !itemView.get('isExpanded')){
                                itemView.set('isExpanded', true);
                            }
                        }
                    }
                }
            },
            /**
             * Метод устанавливающий события после рендинга шаблона.
             * @method didInsertElement
             */
            didInsertElement: function(){
                var scrollContainer = this.$().find('.umi-tree-wrapper')[0];
                var contentScroll = new IScroll(scrollContainer, UMI.config.iScroll);
                this.set('iScroll', contentScroll);
                var self = this;

                var dragAndDrop = function(event, el){
                    var draggableNode = el.parentNode.parentNode;
                    var placeholder = document.createElement('li');
                    var ghost = document.createElement('span');
                    // Смещение призрака относительно курсора
                    var ghostPositionOffset = 2;

                    $('html').addClass('s-unselectable');
                    // Для компонента tree класс выставлюющий потомкам cursor=default
                    self.$().addClass('drag-inside');
                    // Добавим плейсхолдер на место перемещаемой ноды
                    placeholder.className = 'umi-tree-placeholder';
                    placeholder.setAttribute('data-id', el.parentNode.parentNode.getAttribute('data-id'));
                    $(draggableNode).addClass('hide');
                    placeholder = draggableNode.parentNode.insertBefore(placeholder, draggableNode);

                    // Добавим призрак
                    ghost.className = 'umi-tree-ghost';
                    ghost.innerHTML = '<i class="' + el.className + '"></i>' + $(el.parentNode).children('a').text();
                    ghost = document.body.appendChild(ghost);

                    /**
                     * Устанавливает позицию призрака
                     * */
                    var ghostPosition = function(event){
                        ghost.style.top = event.pageY + ghostPositionOffset + 'px';
                        ghost.style.left = event.pageX + ghostPositionOffset + 'px';
                    };
                    ghostPosition(event);

                    /**
                     * Возвращает соседний элемент определеного типа
                     *
                     * @param {Object} Элемент для которого нужно найти следующих соседей
                     * @param {string} Тип элемента который требуется найти
                     * @returns {Object|Null} Возвращаем найденный элемент
                     * */
                    function findNextSibling(element, type){
                        type = type.toUpperCase();
                        var nextElement = element.nextElementSibling;
                        while(nextElement && nextElement.tagName !== type){
                            nextElement = nextElement.nextElementSibling;
                        }
                        return nextElement;
                    }

                    var delayBeforeExpand;
                    $(document).on('mousemove', 'body, .umi-tree-ghost', function(event){
                        if(delayBeforeExpand){
                            clearTimeout(delayBeforeExpand);
                        }
                        ghostPosition(event);
                        var nextElement;
                        var hoverElement;
                        var elemHeight;
                        var elemPositionTop;
                        // Вычисляем элемент под курсором мыши
                        var elem = document.elementFromPoint(event.clientX, event.clientY);

                        // Раскрытие ноды имеющую потомков
                        var setExpanded = function(node){
                            var itemView = Ember.View.views[node.id];
                            if(itemView.get('controller.model.childCount')){
                                itemView.set('isExpanded', true);
                            }
                        };
                        // Проверим находимся мы над деревом или нет
                        if($(elem).closest('.umi-tree').length){
                            hoverElement = $(elem).closest('li')[0];
                            // Устанавливаем плэйсхолдер рядом с элементом
                            if(hoverElement && hoverElement !== placeholder && hoverElement.getAttribute('data-id') !== 'root'){
                                elemHeight = hoverElement.offsetHeight;
                                elemPositionTop = hoverElement.getBoundingClientRect().top;
                                // Помещаем плэйсхолдер:
                                // 1) после ноды - Если позиция курсора на ноде ниже ~70% ее высоты
                                // 2) перед нодой - Если позиция курсора на ноде выше ~30% ее высоты
                                // 3) "внутрь" ноды - если навели на центр. При задержке пользователя на центре раскрываем ноду.
                                if(event.clientY > elemPositionTop + parseInt(elemHeight * 0.7, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    nextElement = findNextSibling(hoverElement, 'li');
                                    if(nextElement){
                                        placeholder = hoverElement.parentNode.insertBefore(placeholder, nextElement);
                                    } else{
                                        placeholder = hoverElement.parentNode.appendChild(placeholder);
                                    }
                                } else if(event.clientY < elemPositionTop + parseInt(elemHeight * 0.3, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    placeholder = hoverElement.parentNode.insertBefore(placeholder, hoverElement);
                                } else{
                                    var emptyChildList = document.createElement('ul');
                                    emptyChildList.className = 'umi-tree-list';
                                    emptyChildList.setAttribute('data-parent-id', hoverElement.getAttribute('data-id'));
                                    placeholder = placeholder.parentNode.removeChild(placeholder);

                                    placeholder = emptyChildList.appendChild(placeholder);
                                    emptyChildList = hoverElement.appendChild(emptyChildList);
                                    delayBeforeExpand = setTimeout(function(){
                                        setExpanded(hoverElement);
                                    }, 500);
                                }
                            }
                        }
                    });

                    $(document).on('mouseup', function(event){
                        var elem = document.elementFromPoint(event.clientX, event.clientY);
                        var prevSiblingId = null;
                        var list = $(elem).closest('.umi-tree-list')[0];

                        // Удаляем обработчик события
                        $(document).off('mousemove', 'body, .umi-tree-ghost');
                        $(document).off('mouseup');
                        //Удаление призрака
                        ghost.parentNode.removeChild(ghost);

                        // Если курсор над плейсхолдером считаем что перемещение удачное
                        if(list && !$(list).hasClass('umi-tree')){
                            /**
                             * Находим предыдущего соседа
                             */
                            (function findPrevSibling(el){
                                var sibling = el.previousElementSibling;
                                if(sibling && ($(sibling).hasClass('hide') || sibling.tagName !== 'LI')){
                                    findPrevSibling(sibling);
                                } else{
                                    prevSiblingId = sibling ? sibling.getAttribute('data-id') : null;
                                }
                            }(placeholder));

                            var nextSibling = [];
                            /**
                             * Фильтр элементов списка
                             */
                            (function findNextSibling(element){
                                var sibling = element.nextElementSibling;
                                if(sibling){
                                    if($(sibling).hasClass('hide') || sibling.tagName !== 'LI'){
                                        findNextSibling(sibling);
                                    } else{
                                        nextSibling.push(sibling.getAttribute('data-id'));
                                    }
                                }
                            }(placeholder));
                            var parentId = list.getAttribute('data-parent-id');
                            self.get('controller').send('updateSortOrder', placeholder.getAttribute('data-id'), parentId, prevSiblingId, nextSibling);
                            self.send('expandItem', parentId);
                        }
                        // Удаление плэйсхолдера
                        if(placeholder.parentNode){
                            placeholder.parentNode.removeChild(placeholder);
                        }
                        $(draggableNode).removeClass('hide');
                        $('html').removeClass('s-unselectable');
                    });
                };

                var timeoutForDrag;
                this.$().on('mousedown', '.icon.move', function(event){
                    if(event.originalEvent.which !== 1){
                        return;
                    }
                    var el = this;
                    timeoutForDrag = setTimeout(function(){
                        dragAndDrop(event, el);
                    }, 200);
                });

                this.$().on('mouseup', '.icon.move', function(){
                    if(timeoutForDrag){
                        clearTimeout(timeoutForDrag);
                    }
                });
            },

            willDestroyElement: function(){
                this.get('controller').removeObserver('activeContext');
                this.removeObserver('context');
                this.removeObserver('expandedBranches');
            }
        });

        UMI.TreeControlItemView = Ember.View.extend({
            item: Ember.computed.alias('context'),

            /**
             * Имя шаблона
             * @property templateName
             */
            templateName: 'partials/treeControl/treeItem',
            /**
             * Имя тега элемента
             * @property tagName
             */
            tagName: 'li',
            /**
             * @property classNames
             */
            classNames: ['umi-tree-list-li'],
            /**
             * @property classNameBindings
             */
            classNameBindings: ['item.isDragged:hide', 'item.isDeleted:hide'],
            /**
             * @property attributeBindings
             */
            attributeBindings: ['dataId:data-id'],
            /**
             * @property dataId
             */
            dataId: function(){
                return this.get('item.id');
            }.property('item.id'),

            /**
             * Для неактивных элементов добавляется класс inActive
             * @property inActive
             */
            inActive: function(){
                return this.get('item.active') === false ? true : false;
            }.property('item.active'),

            isExpanded: false,

            expandActiveContext: function(){
                if(!this.get('isExpanded')){
                    var id = this.get('item.id');
                    console.log(this.get('controller.needsExpandedItems'));
                    if(id === 'root'){
                        this.set('isExpanded', true);
                    } else if(needsExpandedItems && needsExpandedItems.contains(parseFloat(id))){
                        Ember.set(this.get('controller.params'), 'needsExpandedItems', needsExpandedItems.without(parseFloat(id)));
                        this.set('isExpanded', true);
                    }
                }
            },

            actions: {
                expanded: function(){
                    this.toggleProperty('isExpanded');
                }
            },

            didInsertElement: function(){
                Ember.run.once(this, 'expandActiveContext');
            }
        });

        UMI.TreeControlContextToolbarController = Ember.ObjectController.extend({});

        UMI.TreeControlContextToolbarView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'umi-tree-context-toolbar', 'right'],
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
                    behaviour.actions.sendActionForBehaviour = function(contextBehaviour){
                        var object = this.get('controller.model');
                        this.send(contextBehaviour.name, {behaviour: contextBehaviour, object: object});
                    };
                    behaviour.classNames = ['tiny white square'];
                    behaviour.label = null;
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});