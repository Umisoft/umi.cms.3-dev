define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeItemView = Ember.View.extend({
            tagName: 'div',
            classNames: ['umi-item'],
            classNameBindings: ['root', 'inActive', 'active'],

            root: function(){
                return this.get('model.id') === 'root';
            }.property('root'),

            inActive: function(){
                return !this.get('model.active');
            }.property('active'),

            active: function(){
                return this.get('controller.controllers.treeControl.activeContext.id') === this.get('model.id');
            }.property('controller.controllers.treeControl.activeContext.id')
        });

        UMI.TreeControlView = Ember.View.extend({
            tagName: 'div',
            classNames: ['row', 's-full-height'],
            didInsertElement: function(){
                //Выпадающее меню
                $('.umi-tree').on('mousedown', '.umi-tree-drop-down-toggler', function(){
                    console.log('openDrop-DownMenu');
                    event.stopPropagation();
                    $('.umi-tree-drop-down').remove();

                    var x = $(this).offset().left - 133;
                    var y = $(this).offset().top + 24;

                    $(this).append(document.querySelector('#umi-tree-menu').innerHTML);
                    $('.umi-tree-drop-down').offset({top: y, left: x});
                });

                $('.umi-tree').on('mousedown', '.umi-tree-drop-down-show-button', function(event){
                    event.stopPropagation();
                    $('.umi-tree-drop-down').remove();
                });



                //Скрытие выпадающего меню при клике вне его области
                $(document).click(function(event){
                    if(!$(event.target).closest(".umi-tree-drop-down").length){
                        $('.umi-tree-drop-down').hide();
                        event.stopPropagation();
                    }
                });

//                $('.umi-tree').on('mousedown', '.umi-tree-drop-down-show-button', function(){
//                    console.log('click');
//                    $(this).remove();
//                });

                //                $('html').on('mousedown', function(){
////                    console.log('Это событие будет всегда вызываться, делай unbind');
//                    //Нужно просто перенести внутрь вызова .umi-tree-drop-down. Но я сделаю это после того как ты наиграешься с открытием по наведению.
//                    $('.umi-tree-drop-down').remove();
//                });
//
//                $('.umi-tree-drop-down').mousedown(function(event){
//                    event.stopPropagation();
//                });


                //Переключение табов в выпадающем меню
                $('.umi-tree').on('mousedown', '.umi-tree-tab', function(event){
                    event.stopPropagation();
                    $('.umi-tree-tab, .umi-tree-tab-content').removeClass('active');
                    $(this).addClass('active');

                    var i = $(this).index('.umi-tree-drop-down .umi-tree-tab');
                    $('.umi-tree-tab-content').eq(i).addClass('active');
                });

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
                    function findNextSubling(element, type){
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

                        // Расскороем ноду имеющую потомков
                        var setExpanded = function(node){
                            // Предполагаем что div всегда будет первым потомком в li
                            // но та к делать не круто
                            Ember.View.views[node.firstElementChild.id].get('controller').set('isExpanded', true);
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
                                // 3) При наведении на центр необходимо раскрыть ноду если есть потомки
                                //    или спросить пользователя о ....
                                if(event.clientY > elemPositionTop + parseInt(elemHeight * 0.7, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    nextElement = findNextSubling(hoverElement, 'li');
                                    if(nextElement){
                                        placeholder = hoverElement.parentNode.insertBefore(placeholder, nextElement);
                                    } else{
                                        placeholder = hoverElement.parentNode.appendChild(placeholder);
                                    }
                                } else if(event.clientY < elemPositionTop + parseInt(elemHeight * 0.4, 10)){
                                    placeholder = placeholder.parentNode.removeChild(placeholder);
                                    placeholder = hoverElement.parentNode.insertBefore(placeholder, hoverElement);
                                } else{
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
            }
        });
    };
});