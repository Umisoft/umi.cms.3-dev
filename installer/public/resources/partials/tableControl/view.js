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
                    setTimeout(function(){
                        objects.then(function(){
                            iScroll.refresh();
                        });
                    }, 100);
                }
            }.observes('objects').on('didInsertElement'),
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
                    objects.then(function(){
                        // Добавим таймаут для iScroll по совету из документации:
                        //If you have a complex DOM it is sometimes smart to add a little delay from the onload event to iScroll initialization.
                        //Executing the iScroll with a 100 or 200 milliseconds delay gives the browser that little rest that can save your ass.
                        setTimeout(function(){
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
                        }, 100);
                    });
                }
            },
            willDestroyElement: function(){
                $(window).off('.umi.tableControl');
            }
        });

        UMI.TableCellView = Ember.View.extend({
            classNames: ['table-cell'],
            actions: {
                resizeColumn: function(){
                    var columnEl = this.$();
                    var handler = columnEl.children('.table-column-resizer');
                    var columnOfset = columnEl.offset().left;
                    var columnWidth;
                    $('body').on('mousemove.umi.tableControl', function(event){
                        event.stopPropagation();
                        if(columnEl[0].offsetWidth > 59){
                            columnWidth = event.pageX - columnOfset;
                            columnEl[0].style.width = columnWidth + 'px';
                        }
                    });
                    $('body').on('mouseup.umi.tableControl', function(){
                        $('body').off('mousemove');
                        $('body').off('.umi.tableControl.mouseup');
                    });
                }
            }
        });

        UMI.TableCellContentView = UMI.TableCellView.extend({
            template: function(){
                var meta = this.get('column');
                var object = this.get('object');
                var template;
                template = Ember.Handlebars.compile(object.get(meta.name) + '&nbsp;');
                return template;
            }.property('object','column')
        });
    };
});