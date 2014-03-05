define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeItemView = Ember.View.extend({
            tagName: 'div',
            handle: '.icon', // Что это??
            classNames: ['umi-item'],
            classNameBindings: ['root'],
            root: function(){
                return this.get('controller.model.root');
            }.property('controller.model')
        });

        UMI.TreeControlView = Ember.View.extend({
            tagName: 'div',
            classNames: ['row', 's-full-height'],
            didInsertElement: function(){
                //Выпадающее меню
                $('.umi-tree').on('mousedown', '.umi-tree-drop-down-toggler', function(){
                    event.stopPropagation();
                    $('.umi-tree-drop-down').remove();

                    var x = $(this).offset().left - 133;
                    var y = $(this).offset().top + 24;

                    $(this).append(document.querySelector('#umi-tree-menu').innerHTML);
                    $('.umi-tree-drop-down').offset({top: y, left: x});
                });

                //Скрытие выпадающего меню при клике вне его области
                $('html').on('mousedown', function(){
                    $('.umi-tree-drop-down').remove();
                });

                $('.umi-tree-drop-down').mousedown(function(event){
                    event.stopPropagation();
                });


                //Переключение табов в выпадающем меню
                $('.umi-tree').on('mousedown', '.umi-tree-tab', function(event){
                    event.stopPropagation();
                    $('.umi-tree-tab, .umi-tree-tab-content').removeClass('active');
                    $(this).addClass('active');

                    var i = $(this).index('.umi-tree-drop-down .umi-tree-tab');
                    $('.umi-tree-tab-content').eq(i).addClass('active');
                });

                var self = this;
                this.$().on('mousedown', '.icon.move', function(event){
                    var draggableNode = this.parentNode.parentNode;
                    var placeholder = document.createElement('li');
                    var ghost = document.createElement('span');
                    // Смещение призрака относительно курсора
                    var ghostPositionOffset = 2;

                    $('html').addClass('s-unselectable');
                    // Для компонента tree класс выставлюющий потомкам cursor=default
                    self.$().addClass('drag-inside');
                    // Добавим плейсхолдер на место перемещаемой ноды
                    placeholder.className = 'umi-tree-placeholder';
                    placeholder.setAttribute('data-id', this.parentNode.parentNode.getAttribute('data-id'));
                    placeholder.setAttribute('data-index', this.parentNode.parentNode.getAttribute('data-index') || 0);
                    $(draggableNode).addClass('hide');
                    placeholder = draggableNode.parentNode.insertBefore(placeholder, draggableNode);

                    // Добавим призрак
                    ghost.className = 'umi-tree-ghost';
                    ghost.innerHTML = '<i class="' + this.className + '"></i>' + $(this.parentNode).children('a').text();
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
                        var siblingId = null;
                        var list = $(elem).closest('.umi-tree-list')[0];

                        // Удаляем обработчик события
                        $(document).off('mousemove', 'body, .umi-tree-ghost');
                        $(document).off('mouseup');
                        //Удаление призрака
                        ghost.parentNode.removeChild(ghost);

                        // Если курсор над плейсхолдером считаем что перемещение удачное
                        if(list){
                            /**
                             * Находим предыдущего соседа
                             */
                            (function findFirstSibling(el){
                                var sibling = el.previousElementSibling;
                                if(sibling && ($(sibling).hasClass('hide') || sibling.tagName !== 'LI')){
                                    findFirstSibling(sibling);
                                } else{
                                    siblingId = sibling ? sibling.getAttribute('data-id') : null;
                                }
                            }(placeholder));

                            var i;
                            var ids = [];
                            var children = [];
                            var allChild = placeholder.parentNode.children;
                            /**
                             * Фильтр элементов списка
                             */
                            for(i = 0; i < allChild.length; i++){
                                if(allChild[i].tagName === 'LI' && !$(allChild[i]).hasClass('hide')){
                                    children.push(allChild[i]);
                                }
                            }
                            for(i = 0; i < children.length; i++){
                                if(parseInt(children[i].getAttribute('data-index'), 10) !== i){
                                    ids.push(children[i].getAttribute('data-id'));
                                }
                            }

                            self.get('controller').send('updateSortOrder', placeholder.getAttribute('data-id'), list.getAttribute('data-parent-id'), siblingId, ids);
                        }
                        // Удаление плэйсхолдера
                        if(placeholder.parentNode){
                            placeholder.parentNode.removeChild(placeholder);
                        }
                        $(draggableNode).removeClass('hide');
                        $('html').removeClass('s-unselectable');
                    });
                });
            }
        });
    };
});