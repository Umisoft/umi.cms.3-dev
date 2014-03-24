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
                            getCounter(counterId, response);
                            renderNavigation(response)
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
                            secondLevel = secondLevel + '<ul class="umi-accordion-trigger"><li><a href="#" class="umi-metrica-second-level-button" data-resource="' + response.result.navigation[j].children[i].resource + '">' + response.result.navigation[j].children[i].displayName + '</a></li></ul>';
                        }

                        firstLevel = firstLevel + '<li><span>' + response.result.navigation[j].displayName + '</span>' + secondLevel + '</li>';
                        secondLevel = '';
                    }

                    $('.umi-accordion').html(firstLevel);

                    $('.umi-accordion').on('mousedown', '.umi-metrica-second-level-button', function(){
                        var navigationResponse = $(this).data('resource');
                        var counterId = that.get('controller.model.object.id');
                        getCounter(counterId, navigationResponse);
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


                $('.umi-accordion').on('mousedown', 'span', function(){
                    $('.umi-accordion-trigger').removeClass('active');
                    $(this).siblings('.umi-accordion-trigger').addClass('active');
                });
            }
        });
    };
});