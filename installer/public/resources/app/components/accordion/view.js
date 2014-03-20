define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.AccordionView = Ember.View.extend({

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
                                getNavigation(counterId);
                            }
                        }
                    });
                })();

                //Получаем меню для выбранного счётчика
                function getNavigation(counterId){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/navigation",
                        data: {
                            counterId: counterId
                        },
                        cache: false,
                        success: function(response){
                            //                                console.log(response.result.navigation);
                            getCounter(counterId, response);
//                            renderNavigation(response)
                        }
                    });
                }

                function getCounter(counterId, navigationResponse){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/counter",
                        data: {
                            counterId: counterId,
                            resource: navigationResponse.result.navigation[0].children[0].resource
                        },
                        cache: false,
                        success: function(response){
                            console.log(response);
                        }
                    });
                }


//              http://localhost/admin/api/statistics/metrika/action/counter?counterId=1062209&resource=stat/traffic/summary






                function renderNavigation(response){
                    var firstLevelLength = response.result.navigation.length;
                    var secondLevelLength = '';
                    var firstLevel = '';
                    var secondLevel = '';

                    for(var j = 0; j < firstLevelLength; j++){
                        secondLevelLength = response.result.navigation[j].children.length;

                        for(var i = 0; i < secondLevelLength; i++){
                            secondLevel = secondLevel + '<ul class="umi-accordion-trigger"><li><a href="#">' + response.result.navigation[j].children[i].title + '</a></li></ul>';
                        }

                        firstLevel = firstLevel + '<li><span>' + response.result.navigation[j].title + '</span>' + secondLevel + '</li>';
                        secondLevel = '';
                    }

                    $('.umi-accordion').html(firstLevel);
                }

                $('.umi-accordion').on('mousedown', 'span', function(){
                    $('.umi-accordion-trigger').removeClass('active');
                    $(this).siblings('.umi-accordion-trigger').addClass('active');
                });
            }
        });
    };
});