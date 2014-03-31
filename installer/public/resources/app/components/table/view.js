define(['App'], function(UMI){
//    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'table',
            classNames: ['umi-table-ajax'],
            headers: [],
            objectId: [],
            data: [],

            didInsertElement: function(){
                var that = this;

                //Получаем список счётчиков
                (function getCounters(){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/counters",
                        data: {},
                        cache: false,

                        beforeSend: function(){
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; overflow: hidden; z-index: 1; padding: 30px; width: 100%; height: 100%; background: #FFFFFF;"><i class="animate animate-loader-40"></i><h3>�?дёт загрузка данных...</h3></div>';
                            });
                        },

                        success: function(response){
                            $('.umi-loader').remove();
//                            if(!response){
//                                $('.umi-component').css({'position':'relative'}).append(function(){
//                                    return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Данные отсутствуют</h3></div>';
//                                });
//                            }

                            var headers = [];
                            headers.push(response.result.counters.labels.name, response.result.counters.labels.site, response.result.counters.labels['code_status']);

                            var rows = response.result.counters.counters;
                            var rowsLength = rows.length;
                            var result = [];

                            for(var i = 0; i < rowsLength; i++){
                                result.push([rows[i].id, rows[i].site, rows[i].name, rows[i]['code_status']]);
                            }
                            renderCounters(headers, result);
                        },

                        error: function(code){
                            $('.umi-loader').remove();
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Не удалось загрузить данные</h3></div>';
                            });
                        }
                    });
                })();


                //Выводим таблицу со списком счётчиков
                //headers - массив
                //rows - двумерный массив
                function renderCounters(headers, rows){

                    //Заносим заголовки в переменную для шаблонизатора
                    that.set('headers', headers);

                    //Заносим содержимое таблицы с удалением первой колонки (id) в переменную шаблонизатора
                    var rowsLength = rows.length;
                    var objectId = [];
                    that.set('data', rows);
                    that.set('objectId', objectId);
//                    console.log(that.get('objectId'));
                }

                $('.umi-table-ajax').on('click.umi.table','.umi-table-ajax-tr',function(){
                    var counterId = $(this).data('object-id');
                    that.get('controller').transitionToRoute('context', counterId);
                });

            },

            willDestroyElement: function(){
                $(window).off('.umi.table');
            },

            row: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-ajax-tr'],
                attributeBindings: ['objectId:data-object-id'],
                objectId: function(){
                    return this.get('object')[0];
                }.property('object'),
                cell: function(){
                    var object = this.get('object');
                    object.shift(0);
                    return object;
                }.property('object')
            })

        });
    };
});