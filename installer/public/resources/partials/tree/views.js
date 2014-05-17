define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlView = Ember.View.extend({
            classNames: ['row', 's-full-height'],

            expandedBranchesChange: function(){
                var expandedBranches = this.get('controller.expandedBranches');
                for(var i = 0; i < expandedBranches.length; i++){
                    this.send('expandItem', expandedBranches[i]);
                }
            }.observes('controller.expandedBranches'),

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

            didInsertElement: function(){
                var scrollContainer = this.$().find('.umi-tree-wrapper')[0];
                new IScroll(scrollContainer, UMI.config.iScroll);
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
                                // 1) после ноды - Если позиция курсора на ноде ниже ~70% её высоты
                                // 2) перед нодой - Если позиция курсора на ноде выше ~30% её высоты
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
                this.get('controller').removeObserver('controllers.component.collectionName');
                this.get('controller').removeObserver('activeContext');
                this.removeObserver('controller.expandedBranches');
            }

//
//            filtersView: Ember.View.extend({
//                classNames: ['umi-tree-control-filters'],
//                isOpen: false,
//                actions: {
//                    toggleOpen: function(){
//                        var self = this;
//                        var el = this.$();
//                        this.toggleProperty('isOpen');
//                        if(this.get('isOpen')){
//                            $('body').on('click.umi.tree.filterMenu', function(event){
//                                var targetElement = $(event.target).closest('.umi-tree-control-filters');
//                                if(!targetElement.length || targetElement[0].getAttribute('id') !== el[0].getAttribute('id')){
//                                    $('body').off('click.umi.tree.filterMenu');
//                                    self.set('isOpen', false);
//                                }
//                            });
//                        }
//                    }
//                }
//            })
        });

        UMI.TreeItemView = Ember.View.extend({
            templateName: 'treeItem',
            tagName: 'li',
            classNameBindings: ['controller.model.isDragged:hide'],
            attributeBindings: ['dataId:data-id'],

            dataId: function(){
                return this.get('controller.model.id');
            }.property('controller.model'),

            inActive: function(){
                return !this.get('controller.model.active');
            }.property('controller.model.active'),

            active: function(){// TODO: можно сделать через lookup http://jsbin.com/iFEvATE/2/edit
                return this.get('controller.controllers.treeControl.activeContext.id') === this.get('controller.model.id');
            }.property('controller.controllers.treeControl.activeContext.id'),

            savedDisplayName: function(){
                if(this.get('controller.model.id') === 'root'){
                    return this.get('controller.model.displayName');
                } else{
                    return this.get('controller.model._data.displayName');
                }
            }.property('controller.model.currentState.loaded.saved'),//TODO: Отказаться от использования _data

            expandActiveContext: function(){
                Ember.run.once(this, function(){
                    var expandedBranches = this.get('controller.controllers.treeControl.expandedBranches') || [];
                    if(expandedBranches){
                        if(this.get('controller.model.id') === 'root'){
                            return this.set('isExpanded', true);
                        }
                        var contains = expandedBranches.contains(parseFloat(this.get('controller.model.id')));
                        if(contains){
                            return this.set('isExpanded', true);
                        }
                    }
                });
            },

            actions: {
                expanded: function(){
                    var id = this.get('controller.model.id');
                    id = id === 'root' ? id : parseFloat(id);
                    var treeControl = this.get('controller.controllers.treeControl');
                    var expandedBranches = treeControl.get('expandedBranches');
                    this.set('isExpanded', !this.get('isExpanded'));
                    if(this.get('isExpanded')){
                        expandedBranches.push(id);
                    } else{
                        expandedBranches = expandedBranches.without(id);
                    }
                    treeControl.set('expandedBranches', expandedBranches);
                }
            },

            didInsertElement: function(){
                this.expandActiveContext();
            }
        });

        UMI.TreeControlContextMenuView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['button-group', 'umi-tree-context-menu', 'right'],
            layoutName: 'treeControlContextMenu',
            isOpen: false,

            setParentIsOpen: function(){
                this.get('parentView').set('contextMenuIsOpen', this.get('isOpen'));
            }.observes('isOpen'),

            actions: {
                open: function(){
                    var self = this;
                    var el = this.$();
                    this.toggleProperty('isOpen');
                    if(this.get('isOpen')){
                        $('body').on('click.umi.tree.contextMenu', function(event){
                            var targetElement = $(event.target).closest('.umi-tree-context-menu');
                            if(!targetElement.length || targetElement[0].getAttribute('id') !== el[0].getAttribute('id')){
                                $('body').off('click.umi.tree.contextMenu');
                                self.set('isOpen', false);
                            }
                        });
                    }
                }
            },

            itemView: Ember.View.extend({
                tagName: 'li',
                isFastAction: function(){
                    var selectAction = this.get('controller.controllers.treeControl.selectAction');
                    return selectAction ? this.get('action').type === selectAction.type : false;
                }.property('controller.controllers.treeControl.selectAction')
            })
        });
    };
});