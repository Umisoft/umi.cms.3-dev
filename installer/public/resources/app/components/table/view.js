define(['App'], function(UMI){
//    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'table',
            classNames: ['umi-table-ajax'],
            didInsertElement: function(){

                //Получаем список счётчиков
                (function getCounters(){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/counters",
                        data: {},
                        cache: false,
                        success: function(response){

                            var counterLength = response.result.counters.counters.length;
                            for(var k = 0; k < counterLength; k++){
                                var counterId = response.result.counters.counters[k].id;
                                var counterName = response.result.counters.counters[k].name;
                                var counterSite = response.result.counters.counters[k].site;
                                var counterCodeStatus = response.result.counters.counters[k]['code_status'];

                                var cell1, cell2, cell3;
                                cell1 = '<td class="umi-table-ajax-cell-td"><div class="umi-table-ajax-cell-div">' + counterName + '</div></td>';
                                cell2 = '<td class="umi-table-ajax-cell-td"><div class="umi-table-ajax-cell-div">' + counterSite + '</div></td>';
                                cell3 = '<td class="umi-table-ajax-cell-td"><div class="umi-table-ajax-cell-div">' + counterCodeStatus+ '</div></td>';

                                var rows;
                                $('.umi-table-ajax-content tbody').append(function(){
                                    rows = rows + '<tr class="umi-table-ajax-tr" data-object-id="counterId">' + cell1 + cell2 + cell3 + '<td class="umi-table-ajax-empty-column"></td></tr>';
                                    return rows;
                                });

                                $('.umi-table-ajax-tr').click(function(){
                                    var counterId = $(this).data('object-id');
                                    $('.umi-table-ajax-control-content').hide();
                                    $('#piechart').show();

                                    var pieData = [
                                        {
                                            value: 30,
                                            color:"#F38630"
                                        },
                                        {
                                            value : 50,
                                            color : "#E0E4CC"
                                        },
                                        {
                                            value : 100,
                                            color : "#69D2E7"
                                        }
                                    ];

                                    var pieOptions = {
                                        //Boolean - Whether we should show a stroke on each segment
                                        segmentShowStroke : true,
                                        segmentStrokeColor : none,
                                        segmentStrokeWidth : 2,
                                        animation : false,
                                        animationSteps : 100,
                                        animationEasing : "easeOutBounce",
                                        animateRotate : true,
                                        animateScale : false,
                                        onAnimationComplete : null
                                    }

                                    var myPie = new Chart(document.getElementById("canvas").getContext("2d")).Pie(pieData, pieOptions);

                                });
                            }
                        }
                    });
                })();
            }
        });
    };
});