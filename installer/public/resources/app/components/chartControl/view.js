define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ChartControlView = Ember.View.extend({
            tagName: 'div',
            templateName: 'chartControl',
            classNames: 'umi-metrika-right',
            didInsertElement: function(){

                var el = this.$().children('.umi-date');
                el.jdPicker({date_format: "dd/mm/YYYY"});

                var lineChartData = {
                    labels : ["January","February","March","April","May","June","July"],
                    datasets : [
                        {
                            fillColor : "rgba(220,220,220,0.5)",
                            strokeColor : "rgba(220,220,220,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            data : [65,59,90,81,56,55,40]
                        },
                        {
                            fillColor : "rgba(151,187,205,0.5)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            data : [28,48,40,19,96,27,100]
                        }
                    ]
                };

                document.getElementById("umi-metrika-canvas").width = $(window).width() - 220;
                document.getElementById("umi-metrika-canvas").height = 300;

                var myLine = new Chart(document.getElementById("umi-metrika-canvas").getContext("2d")).Line(lineChartData);

                window.onresize = function(event){
                    console.log('window');
                    var options = {
                        animation: false
                    };
                    document.getElementById("umi-metrika-canvas").width = $(window).width() - 220;
//                    document.getElementById("umi-metrika-canvas").height = $(window).height() - 200;

                    var myLine = new Chart(document.getElementById("umi-metrika-canvas").getContext("2d")).Line(lineChartData, options);
                };
            }
        });
    };
});