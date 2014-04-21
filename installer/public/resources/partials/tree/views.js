define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeItemView = Ember.View.extend({
            tagName: 'div',
            classNames: ['umi-item'],
            classNameBindings: ['root', 'inActive', 'active', 'contextMenuIsOpen'],

            root: function(){
                return this.get('model.id') === 'root';
            }.property('model.root'),

            inActive: function(){
                return !this.get('model.active');
            }.property('model.active'),

            active: function(){
                return this.get('controller.controllers.treeControl.activeContext.id') === this.get('model.id');
            }.property('controller.controllers.treeControl.activeContext.id'),

            savedDisplayName: function(){
                if(this.get('model.id') === 'root'){
                    return this.get('model.displayName');
                } else{
                    return this.get('model.content._data.displayName');
                }
            }.property('model.currentState.loaded.saved')//TODO: Отказаться от использования _data
        });

        UMI.TreeControlView = Ember.View.extend({
            tagName: 'div',
            classNames: ['row', 's-full-height'],
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
                            // Предполагаем что div всегда будет первым потомком в li
                            // но так делать не круто
                            var itemController = Ember.View.views[node.firstElementChild.id].get('controller');
                            if(itemController.get('model.childCount')){
                                itemController.set('isExpanded', true);
                            }
                        };
                        // Проверим находимся мы над деревом или нет
                        if($(elem).closest('.umi-tree').length){
                            hoverElement = $(elem).closest('li')[0];
                            // Устанавливаем плэйсхолдер рядом с элементом
                            if(hoverElement && hoverElement !== placeholder && !$(hoverElement).hasClass('root')){
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
            filtersView: Ember.View.extend({
                classNames: ['umi-tree-control-filters'],
                isOpen: false,
                actions: {
                    toggleOpen: function(){
                        var self = this;
                        var el = this.$();
                        this.toggleProperty('isOpen');
                        if(this.get('isOpen')){
                            $('body').on('click.umi.tree.filterMenu', function(event){
                                var targetElement = $(event.target).closest('.umi-tree-control-filters');
                                if(!targetElement.length || targetElement[0].getAttribute('id') !== el[0].getAttribute('id')){
                                    $('body').off('click.umi.tree.filterMenu');
                                    self.set('isOpen', false);
                                }
                            });
                        }
                    }
                }
            }),

            contextMenuView: Ember.View.extend({
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
            })
        });
    };
});