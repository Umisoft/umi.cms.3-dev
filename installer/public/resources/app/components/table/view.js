define(['App'], function(UMI){
//    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'table',
            classNames: ['umi-table-ajax'],
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
                                return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Идёт загрузка данных...</h3></div>';
                            });
                        },

                        success: function(response){
                            $('.umi-loader').remove();
                            if(!response){
                                $('.umi-component').css({'position':'relative'}).append(function(){
                                    return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Данные отсутствуют</h3></div>';
                                });
                            }

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
                    var headersLength = headers.length;
                    var rowsLength = rows.length;

                    if(headersLength){
                        //Выводим заголовки
                        $('.umi-table-ajax-titles').prepend(function(){
                            var result = '';
                            for(var i = 0; i < headersLength; i++){
                                result = result + '<td class="umi-table-ajax-header-column"><div class="umi-table-ajax-title-div">' + headers[i] + '</div></td>';
                            }
                            return result;
                        });
                    }

                    if(rowsLength){
                        //Выводим содержимое таблицы
                        var result = '';
                        var row = [];

                        for(var j = 0; j < rowsLength; j++){
                            for(var i = 1; i < headersLength + 1; i++){
                                row.push('<td class="umi-table-ajax-cell-td"><div class="umi-table-ajax-cell-div">' + rows[j][i] + '</div></td>');
                            }
                            result = result + '<tr class="umi-table-ajax-tr" data-object-id="' + rows[j][0] + '">' + row + '<td class="umi-table-ajax-empty-column"></td></tr>';
                            row = [];
                        }

                        $('.umi-table-ajax-content tbody').append(function(){
                            return result;
                        });

                    } else{
                        $('.umi-metrika-empty-row').show();
                    }

                    setTimeout(function(){
                        var metrikaTableScroll = new IScroll('.umi-table-ajax-control-content-center', UMI.Utils.defaultIScroll);
                    }, 0);
                }


                $('.umi-table-ajax').on('click','.umi-table-ajax-tr',function(){
//                    $('.umi-table-ajax-control-content').hide();
//                    $('.umi-counter-info').show();

                    var counterId = $(this).data('object-id');

                    that.get('controller').transitionToRoute('context', counterId);
//                    getNavigation(counterId);
                });

            }
        });
    };
});