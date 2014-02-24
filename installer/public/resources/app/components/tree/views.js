define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeItemView = Ember.View.extend({
            tagName: 'div',
            handle: '.icon',
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
                var self = this;
                //Выпадающее меню
                self.$().on('click', '.umi-tree-tab', function(event){
                    event.stopPropagation();
                    $('.umi-tree-tab, .umi-tree-tab-content').removeClass('active');
                    $(this).addClass('active');

                    var i = $(this).index('.umi-tree-drop-down .umi-tree-tab');
                    $('.umi-tree-tab-content').eq(i).addClass('active');
                });

                //Переключение кнопки сортировки вверх-вниз
                this.$().on('click', '.umi-tree-drop-down-toggler a', function(){
                    self.$().find('.icon-bottom-thin, .icon-top-thin').toggleClass('icon-bottom-thin icon-top-thin');
                    $('.umi-tree-drop-down').toggle();
                });

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
                        var indexes = [];
                        var list = $(elem).closest('.umi-tree-list')[0];

                        // Удаляем обработчик события
                        $(document).off('mousemove', 'body, .umi-tree-ghost');
                        $(document).off('mouseup');
                        //Удаление призрака
                        ghost.parentNode.removeChild(ghost);

                        // Если курсор над плейсхолдером считаем что перемещение удачное
                        if(list){
                            var parentList = placeholder.parentNode;
                            $(parentList).children('li:not(.hide)').each(function(index){
                                indexes[jQuery(this).data('id')] = index + 1;// Index начнется с 1
                            });
                            self.get('controller').send('updateSortOrder', indexes, placeholder.getAttribute('data-id'), list.getAttribute('data-parent-id'));
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