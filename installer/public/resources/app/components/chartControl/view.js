define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ChartControlView = Ember.View.extend({
            tagName: 'div',
            templateName: 'chartControl',
            classNames: 'umi-metrika-right',

            getCounter: function(counterId, resource, pageName){
                var that = this;
                $.ajax({
                    type: "GET",
                    url: "/admin/api/statistics/metrika/action/counter",
                    data: {
                        counterId: counterId,
                        resource: resource
                    },
                    cache: false,

                    beforeSend: function(){
                        $('.umi-counter-period').after(function(){
                            $('.umi-counter-content').remove();
                            return '<div class="umi-counter-content umi-loader"><i class="animate animate-loader-40" style="float:left"></i><h3 style="float:left; color: #4C617D;">�?дёт загрузка данных...</h3></div>';
                        });
                    },

                    success: function(response){
                        $('.umi-loader').remove();

                        $('.umi-counter-period').before(function(){
                            $('.umi-counter-header').remove();
                            return '<div class="umi-counter-header"><h3 style="float:left; color: #4C617D;">' + pageName + '</h3></div>';
                        });

                        if('errors' in response.result.counter){ //Проверяем наличие свойства error в ответе

                            console.log('ERROR');
                            $('.umi-counter-period').after(function(){
                                $('.umi-counter-content').remove();
                                return '<div class="umi-counter-content"><h3 style="float:left; color: #4C617D;">' + response.result.counter.errors.text + '</h3></div>';
                            });

                        } else{

                            console.log('SUCCESS');

                            $('.umi-date-from').val(function(){
                                return response.result.counter.date1
                            });

                            $('.umi-date-to').val(function(){
                                return response.result.counter.date2
                            });


                            var name = response.result.counter.report[0].displayName;
                            var type = response.result.counter.report[0].graph.type;
                            var axisX = response.result.counter.report[0].graph.axisX;
                            var axisY = response.result.counter.report[0].graph.axisY;

                            console.log('VARS', name, type, axisX, axisY);

                            console.log('response', response.result.counter);

                            var labels = [];
                            var datasets = [
                                {
                                    fillColor : "rgba(151,187,205,0.5)",
                                    strokeColor : "rgba(151,187,205,1)",
                                    pointColor : "rgba(151,187,205,1)",
                                    pointStrokeColor : "#fff",
                                    data : []
                                }
                            ];

                            for(var i = 0; i < response.result.counter.report[0].data.length; i++){
                                labels.push(response.result.counter.report[0].data[i][axisX]);
                                datasets[0].data.push(response.result.counter.report[0].data[i][axisY]);
                            }

                            var dataObject = {
                                labels: labels,
                                datasets: datasets
//                              labels : ["January","February","March","April","May","June","July"],
//                              datasets: [
//                                    {
//                                        fillColor : "rgba(220,220,220,0.5)",
//                                        strokeColor : "rgba(220,220,220,1)",
//                                        pointColor : "rgba(220,220,220,1)",
//                                        pointStrokeColor : "#fff",
//                                        data : [20130326, 20130325, 20130324, 20130323, 20130322, 20130321, 20130320]
//                                    }
//                                ]
                            };

                            console.log('dataObject', dataObject);
                            $('.umi-counter-info h3').text(name);
                            that.drawChart(type, dataObject);
                        }
                    }
                });
            },

            drawChart: function(type, lineChartData){
                var options = {
                    scaleShowLabels: false,
                    scaleSteps: 7
                };

                console.log('windowWidth - 250', $(window).width() - 250);
                document.getElementById("umi-metrika-canvas").width = $(window).width() - 250;
                console.log('width', document.getElementById("umi-metrika-canvas").width);
                document.getElementById("umi-metrika-canvas").height = 300;

                if(type){
                    type = type.charAt(0).toUpperCase() + type.slice(1);
                    var myLine = new Chart(document.getElementById("umi-metrika-canvas").getContext("2d"))[type](lineChartData, options);

                    $(window).on('resize.umi.chartControl', function(){
                        options.animation = false;
                        document.getElementById("umi-metrika-canvas").width = $(window).width() - 250;
                        myLine = new Chart(document.getElementById("umi-metrika-canvas").getContext("2d"))[type](lineChartData, options);
                    });
                }

            },

            didInsertElement: function(){
                var that = this;
                $('.umi-date').jdPicker({date_format: "dd/mm/YYYY"});

                $('.umi-component').on('click', '.umi-metrika-second-level-button', function(){
                    var pageName = $(this).text();
                    var resource = $(this).data('resource');
                    var counterId = that.get('controller.model.object.id');
                    that.getCounter(counterId, resource, pageName);
                });
            },

            willDestroyElement: function(){
                $(window).off('.umi.chartControl');
            }
        });
    };
});