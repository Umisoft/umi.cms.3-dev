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
                var objects = this.get('controller.objects.content');
                var iScroll = this.get('iScroll');
                if(objects && iScroll){
                    if(Ember.isArray(objects)){
                        Ember.run.scheduleOnce('afterRender', self, function(){
                            iScroll.refresh();
                        });
                    } else{
                        objects.then(function(){
                            Ember.run.scheduleOnce('afterRender', self, function(){
                                iScroll.refresh();
                            });
                        });
                    }

                }
            }.observes('controller.objects').on('didInsertElement'),

            setColumnWidth: function(){
                // Вставаляем стили с шириной ячеек таблицы
                var tableId = 'table-control-column-style'; //TODO: Нужно динамически генерить уникальный ID
                var tableStyleColumns = document.querySelector('#' + tableId);

                // Создание тега styles если его ещё не существует
                if(!tableStyleColumns){
                    tableStyleColumns = document.createElement('style');
                    tableStyleColumns.id = tableId;
                    tableStyleColumns = document.body.appendChild(tableStyleColumns);
                }

                var resultStyle = '';
                var columns = this.get('controller.viewSettings').columns;
                var i;
                var tableControlClass = '.umi-table-control';

                for(i = 0; i < columns.length; i++){
                    resultStyle = resultStyle + tableControlClass + ' .column-id-' + columns[i].name + '{width: ' + columns[i].width + 'px;}';
                }
                tableStyleColumns.innerHTML = resultStyle;
            },

            didInsertElement: function(){
                var tableControl = this.$();

                var self = this;
                var objects = this.get('controller.objects.content');

                // Элементы позицию которых необходимо изменять при прокрутке/ресайзе таблицы
                var umiTableLeft = tableControl.find('.umi-table-control-content-fixed-left')[0];
                var umiTableRight = tableControl.find('.umi-table-control-content-fixed-right')[0];
                var umiTableHeader = tableControl.find('.umi-table-control-header-center')[0];

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
                                setTimeout(function(){
                                    umiTableLeft.style.marginTop = scrollContent.y + 'px';
                                    umiTableRight.style.marginTop = scrollContent.y + 'px';
                                    umiTableHeader.style.marginLeft = scrollContent.x + 'px';
                                }, 100);
                            });

                            // Событие изменения ширины колонки
                            tableControl.on('mousedown.umi.tableControl', '.umi-table-column-resizer', function(){
                                var handler = this;
                                $(handler).addClass('on-resize');
                                var columnEl = handler.parentNode.firstElementChild;
                                var columnName = columnEl.className;
                                columnName = columnName.substr(columnName.indexOf('column-id-') + 10);
                                var columnOfset = $(columnEl).offset().left;
                                var columnWidth;
                                $('body').on('mousemove.umi.tableControl', function(event){
                                    event.stopPropagation();
                                    columnWidth = event.pageX - columnOfset;
                                    if(columnWidth >= 60 && columnEl.offsetWidth > 59){
                                        return (function(){
                                            Ember.set(self.get('controller.viewSettings').columns.findBy('name', columnName), 'width', columnWidth);
                                            self.setColumnWidth();
                                        }());
                                    }
                                });

                                $('body').on('mouseup.umi.tableControl', function(){
                                    $(handler).removeClass('on-resize');
                                    $('body').off('mousemove');
                                    $('body').off('.umi.tableControl.mouseup');
                                    scrollContent.refresh();
                                });
                            });
                        });
                    });
                }

                // Событие изменения limit
                $('.umi-table-control-footer').on('keydown.umi.tableControl', '.umi-limit', function(event){
                    if(event.keyCode === 13){
                        self.get('controller').set('limit', this.value);
                    }
                });

                // Событие изменения limit
                $('.umi-table-control-footer').on('keydown.umi.tableControl', '.umi-pagination', function(event){
                    if(event.keyCode === 13){
                        self.get('controller').set('offset', this.value);
                    }
                });
            },
            willInsertElement: function(){
                this.setColumnWidth();
            },

            willDestroyElement: function(){
                $(window).off('.umi.tableControl');
            }
        });

        UMI.TableCellContentView = Ember.View.extend({
            classNames: ['umi-table-control-cell'],
            classNameBindings: ['columnId'],
            columnId: function(){
                return 'column-id-' + this.get('column').name;
            }.property(),
            template: function(){
                var meta = this.get('column');
                var object = this.get('object');
                var template;
                template = Ember.Handlebars.compile(object.get(meta.name) + '&nbsp;');
                return template;
            }.property('object','column'),
            didInsertElement: function(){
                console.log('didInsertElement');
            }
        });
    };
});