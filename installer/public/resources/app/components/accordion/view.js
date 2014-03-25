define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.AccordionView = Ember.View.extend({
            templateName: 'accordion',
            classNames: 'umi-accordion-side-bar',
            didInsertElement: function(){
                var that = this;
                var counterId = this.get('controller.model.object.id');
                getNavigation(counterId);

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
//                            getCounter(counterId, response);
                            renderNavigation(response)
                        }
                    });
                }


                //Отрисовываем аккордион
                function renderNavigation(response){
                    var firstLevelLength = response.result.navigation.length;
                    var secondLevelLength = '';
                    var firstLevel = '';
                    var secondLevel = '';

                    for(var j = 0; j < firstLevelLength; j++){
                        secondLevelLength = response.result.navigation[j].children.length;

                        for(var i = 0; i < secondLevelLength; i++){
                            secondLevel = secondLevel + '<ul class="umi-accordion-trigger"><li><a href="#" class="umi-metrika-second-level-button" data-resource="' + response.result.navigation[j].children[i].resource + '">' + response.result.navigation[j].children[i].displayName + '</a></li></ul>';
                        }

                        firstLevel = firstLevel + '<li><span>' + response.result.navigation[j].displayName + '</span>' + secondLevel + '</li>';
                        secondLevel = '';
                    }

                    $('.umi-accordion').html(firstLevel);
                }


                //Получаем данные для конкретного счётчика
//              http://localhost/admin/api/statistics/metrika/action/counter?counterId=1062209&resource=stat/traffic/summary
                function getCounter(counterId, resource){
                    $.ajax({
                        type: "GET",
                        url: "/admin/api/statistics/metrika/action/counter",
                        data: {
                            counterId: counterId,
                            resource: resource
                        },
                        cache: false,
                        success: function(response){
                            console.log(response.result.counter);
                        }
                    });
                }


                $('.umi-accordion').on('mousedown', 'span', function(){
                    $('.umi-accordion-trigger').removeClass('active');
                    $(this).siblings('.umi-accordion-trigger').addClass('active');
                });

                $('.umi-accordion').on('mousedown', '.umi-metrika-second-level-button', function(){
                    var id = that.get('controller.model.object.id');
                    var resource = $(this).data('resource');
                    getCounter(id, resource);
                });
            }
        });
    };
});