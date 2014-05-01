define(['App'], function(UMI){
    'use strict';
    return function(){
        UMI.TableControlView = Ember.View.extend({
            templateName: 'tableControl',
            classNames: ['umi-table-control'],
            iScroll: null,
            /**
             * При изменении данных вызывает ресайз скрола.
             */
            scrollUpdate: function(){
                var self = this;
                var tableControl = this.$();
                var objects = this.get('controller.objects.content');
                var iScroll = this.get('iScroll');

                var scrollUpdate = function(){
                    Ember.run.scheduleOnce('afterRender', self, function(){
                        // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                        var umiTableLeft = tableControl.find('.umi-table-control-control-content-fixed-left')[0];
                        var umiTableRight = tableControl.find('.umi-table-control-control-content-fixed-right')[0];
                        var umiTableHeader = tableControl.find('.umi-table-control-control-header-center')[0];
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

            didInsertElement: function(){
                var tableControl = this.$();

                var self = this;
                var objects = this.get('controller.objects.content');

                // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                var umiTableLeft = tableControl.find('.umi-table-control-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-control-header-center')[0];

                var umiTableContentRowSize = tableControl.find('.umi-table-control-control-content-row-size')[0];

                if(objects){
                    var tableContent = tableControl.find('.s-scroll-wrap');

                    objects.then(function(objects){
                        if(!objects.content.length){
                            return;
                        }

                        Ember.run.scheduleOnce('afterRender', self, function(){
                            var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll);
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
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-control-control-column-resizer', function(){
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
                                    $(handler).removeClass('on-resize');
                                    $('body').off('mousemove');
                                    $('body').off('mouseup.umi.tableControl');
                                    scrollContent.refresh();
                                });
                            });

                            // Hover event
                            var getHoverElements = function(el){
                                var isContentRow = $(el).hasClass('umi-table-control-content-row');
                                var rows = el.parentNode.querySelectorAll(isContentRow ? '.umi-table-control-control-content-row' : '.umi-table-control-control-column-fixed-cell');
                                var i;

                                for(i = 0; i < rows.length; i++){
                                    if(rows[i] === el){
                                        break;
                                    }
                                }
                                var leftElements = umiTableLeft.querySelectorAll('.umi-table-control-control-column-fixed-cell');
                                var rightElements = umiTableRight.querySelectorAll('.umi-table-control-control-column-fixed-cell');
                                if(!isContentRow){
                                    el = tableContent[0].querySelectorAll('.umi-table-control-control-content-row')[i];
                                }
                                return [el, leftElements[i], rightElements[i]];
                            };

                            tableControl.on('mouseenter.umi.tableControl', '.umi-table-control-control-content-row, .umi-table-control-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).addClass('hover');
                            });

                            tableControl.on('mouseleave.umi.tableControl', '.umi-table-control-control-content-row, .umi-table-control-control-column-fixed-cell', function(){
                                var elements = getHoverElements(this);
                                $(elements).removeClass('hover');
                            });
                            // Drag and Drop
                        });
                    });
                }







                //TODO Вынести в библиотеку
                window.RAF = (function(){
                    return window.requestAnimationFrame || //                        window.webkitRequestAnimationFrame ||
                        //                        window.mozRequestAnimationFrame    ||
                        //                        window.oRequestAnimationFrame      ||
                        //                        window.msRequestAnimationFrame     ||
                        function(callback){
                            window.setTimeout(callback, 16.666666666666668);
                        };
                })();

                var dragColumn = function(){
                    var currentColumn;
                    var currentColumnWidth;
                    var detachHeaderCell = null;
                    var detachHeaderTitleCells = [];
                    var detachHeaderFilterCells = [];
                    var detachTableCell = null;
                    var detachTableCells = [];

                    $('.umi-component').on('mousedown', '.umi-table-control-header-column', function(event){
                        if(event.button === 0){ //Проверка на нажатие левой кнопки мыши

                            $('html').addClass('s-unselectable');

                            currentColumnWidth = $(this).find('.umi-table-control-title-div').width();
                            currentColumn = $(this).index('.umi-table-control-header-column');


                            //Удаление столбца с сохранением его содержимого
                            var detachHeaderCellsGhost = '';
                            var detachTableCellsGhost = '';
                            //TODO Сделать код универсальным - перебор строк, а не для кажой по-отдельности
                            detachHeaderCell = document.querySelector('.umi-table-control-row').querySelectorAll('td')[currentColumn];
                            detachHeaderTitleCells.push(detachHeaderCell); //Массив ссылок на ячейки колонки
                            //Оптимизация. Сохранение всех элементов в единую строку для вставки всех данных без дополнительного цикла и одним обращением к DOM
                            detachHeaderCellsGhost = detachHeaderCellsGhost + '<tr><td>' + detachHeaderCell.innerHTML + '</td></tr>'; //Содержимое с тегами
                            detachHeaderCell.remove();


                            detachHeaderCell = document.querySelector('.umi-table-control-row.filters').querySelectorAll('td')[currentColumn];
                            detachHeaderFilterCells.push(detachHeaderCell); //Массив ссылок на ячейки колонки
                            //Оптимизация. Сохранение всех элементов в единую строку для вставки всех данных без дополнительного цикла и одним обращением к DOM
                            detachHeaderCellsGhost = detachHeaderCellsGhost + '<tr><td>' + detachHeaderCell.innerHTML + '</td></tr>'; //Содержимое с тегами
                            //TODO IE не работает
                            detachHeaderCell.remove();


                            $('.umi-table').find('.umi-table-control-tr').each(function(index, element){
                                detachTableCell = element.querySelectorAll('td')[currentColumn];
                                detachTableCells.push(detachTableCell); //Массив ссылок на ячейки колонки
                                //Оптимизация. Сохранение всех элементов в единую строку для вставки всех данных без дополнительного цикла и одним обращением к DOM
                                detachTableCellsGhost = detachTableCellsGhost + '<tr class="ghost-tr"><td><div class="ghost-div">' + detachTableCell.querySelector('div').textContent + '</div></td></tr>'; //Содержимое с тегами
                                detachTableCell.remove();
                            });


                            //Вставка колонки
                            var columnInsert = function(currentColumn){
                                //Вставляем элемент перед выбранной колонкой
                                //TODO Сделать код универсальным - перебор строк в заголовке, а не для кажой по-отдельности
                                $('.umi-table-control-header-column').before(function(){
                                    if($(this).index('.umi-table-control-header-column') === currentColumn){
                                        return detachHeaderTitleCells.shift();
                                    } else {
                                        console.log('Условие не отработало');
                                    }
                                });

                                $('.umi-table-control-filter-column').before(function(){
                                    if($(this).index('.umi-table-control-filter-column') === currentColumn){
                                        return detachHeaderFilterCells.shift();
                                    }
                                });

                                $('.umi-table').find('.umi-table-control-tr').each(function(){
                                    $(this).find('.umi-table-control-cell-td').eq(currentColumn).before(function(){
                                        return detachTableCells.shift();
                                    });
                                });
                            };


                            //Добавление плавающего призрака
                            var addGhost = function(){
                                $('body').append(function(){
                                    return '<div class="ghost"><table class="umi-table-ghost-header">' + detachHeaderCellsGhost + '</table><table class="umi-table-ghost-content">' + detachTableCellsGhost + '</table></div>';
                                });
                                document.querySelectorAll('.ghost div')[0].style.width = currentColumnWidth + 'px';
                                document.querySelector('.ghost').style.width = currentColumnWidth + 20 + 'px';
                            };


                            //Устанавливаем позицию призрака при первоначальной вставке в DOM
                            var ghostPosition = function(event){
                                window.RAF(function(){
                                    var x = event.pageX - $('.ghost').width() / 2 + 'px';
                                    var y = event.pageY - 15 + 'px';
                                    var ghost = document.querySelector('.ghost').style; //TODO в консоли периодически вылезает ошибка о попытке прочесть style у несуществующего элемента
                                    ghost.top = y;
                                    ghost.left = x;
                                });
                            };

                            addGhost();
                            ghostPosition(event);


                            //Удаление/Добавление колонки-призрака
                            var addGhostColumn = function(currentColumn){
                                window.RAF(function(){
                                    $('.tableGhost').remove();
                                    var umiHeader = document.querySelector('.umi-table-control-row');
                                    var headerCell = umiHeader.insertCell(0);
                                    headerCell.innerHTML = '<div></div>';
                                    headerCell.className = 'tableGhost';
                                    headerCell.querySelector('div').style.width = currentColumnWidth - 2 + 20 + 'px';
                                    var nextHeaderSibling = umiHeader.querySelectorAll('td')[currentColumn + 1];

                                    var umiFilter = document.querySelector('.umi-table-control-row.filters');
                                    var filterCell = umiFilter.insertCell(0);
                                    filterCell.innerHTML = '<div></div>';
                                    filterCell.className = 'tableGhost';
                                    filterCell.querySelector('div').style.width = currentColumnWidth - 2 + 20 + 'px';
                                    var nextFilterSibling = umiFilter.querySelectorAll('td')[currentColumn + 1];


                                    var umiTable = document.querySelector('.umi-table-control-content-row');
                                    var cell = umiTable.insertCell(0);
                                    cell.innerHTML = '<div></div>';
                                    cell.className = 'tableGhost';
                                    cell.setAttribute('rowspan', 100);
                                    cell.querySelector('div').style.width = currentColumnWidth + 20 + 'px';
                                    var nextSibling = umiTable.querySelectorAll('td')[currentColumn + 1];

                                    umiHeader.insertBefore(headerCell, nextHeaderSibling);
                                    umiFilter.insertBefore(filterCell, nextFilterSibling);
                                    umiTable.insertBefore(cell, nextSibling);
                                });
                            };

                            addGhostColumn(currentColumn);

                            var arrSum = function(array, elem){
                                var result = 0;
                                for(var i = 0; i < elem; i++){
                                    result = result + array[i];
                                }
                                return result;
                            };


                            var umiTableControl = $('.umi-table-control-control');
                            $(document).on('mousemove.umi.tableControl', 'body, .ghost', function(event){
                                ghostPosition(event);

                                //Позиция курсора относительно таблицы
                                var tableCursorX = event.pageX - umiTableControl.offset().left;
                                //TODO Подправить формулу определения местонахождения ghostColumn при перетаскивании
                                //Находим координаты в который будет вызываться перестановка tableGhostColumn
                                var leftColumnCenter = arrSum(columnsWidth, currentColumn) - columnsWidth[currentColumn - 1] / 2; //Сумма всех ширин колонок до текущей - половина ширины предыдущей
                                var rightColumnCenter = arrSum(columnsWidth, currentColumn) + columnsWidth[currentColumn] + columnsWidth[currentColumn + 1] / 2; //Сумма всех ширин колонок до текущей + ширина текущей + половина ширины следующей

                                //Рассчёт позиции вставки колонки
                                //                                var columnTargetRight = arrSum(columnsWidth, currentColumn + 1);
                                //                                var columnTargetLeft = arrSum(columnsWidth, currentColumn);


                                //                                console.log('tableCursorX: ' + tableCursorX);
                                //                                console.log('leftColumnCenter: ' + leftColumnCenter);
                                //                                console.log('rightColumnCenter: ' + rightColumnCenter);

                                if(columnsWidth.length > currentColumn > 0){
                                    if(tableCursorX < leftColumnCenter){
                                        currentColumn = currentColumn - 1;
                                        addGhostColumn(currentColumn);
                                    }

                                    if(tableCursorX > rightColumnCenter){
                                        currentColumn = currentColumn + 1;
                                        addGhostColumn(currentColumn);
                                    }
                                }
                            });


                            //Удаление призрака, вставка столбца
                            $(document).on('mouseup.umi.tableControl', function(){
                                $(document).off('mousemove');
                                $('.ghost').remove();
                                $('.tableGhost').remove();

                                columnInsert(currentColumn);

                                $('html').removeClass('s-unselectable');
                            });
                        }
                    });
                };
                dragColumn();
            },

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
                    if(this.get('isActive')){
                        this.toggleProperty('sortAscending');
                    }
                    var sortAscending = this.get('sortAscending');
                    this.get('controller').send('orderByProperty', propertyName, sortAscending);
                }
            })
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-content-cell-div'],
            classNameBindings: ['columnId'],

            template: function(){
                var meta = this.get('column');
                var object = this.get('object');
                var template;
                if(meta.name === 'displayName'){
                    template = '{{#link-to "action" object.id "editForm" class="edit-link"}}' + object.get(meta.name) + '{{/link-to}}';
                } else{
                    template = object.get(meta.name) + '&nbsp;';
                }
                return Ember.Handlebars.compile(template);
            }.property('object','column')
        });
    };
});