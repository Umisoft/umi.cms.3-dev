define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.OffcanvasView = Ember.View.extend({
            classNames: ['off-canvas-wrap', 'umi-divider-wrapper', 's-full-height'],

            //Вызывается при первой загрузке
            didInsertElement: function(){
                this.fakeDidInsertElement();
            },

            //Вызывается при последующих загрузках
            modelChange: function(){
                this.fakeDidInsertElement();
            }.observes('model'),

            fakeDidInsertElement: function(){
                if($('.umi-divider-left').length){
                    $('.umi-divider-left-toggle').removeClass('umi-disabled'); //Включаем кнопку
                    var showSideBarState = true;
                    var floatSideBarState = false;
                    var sideBarWidth = 251; //Отступ контентной области слева при окрытом sideBar

                    var showSideBar = function(){
                        if(!showSideBarState){
                            $('.umi-divider-left').hide();
                            $('.umi-divider-right').css({width: '100%'});
                        }

                        if(showSideBarState && !floatSideBarState){
                            $('.umi-divider-left').show();
                            $('.umi-divider-right').css({width: $(window).width() - sideBarWidth});
                        }

                        if(showSideBarState && floatSideBarState){
                            $('.umi-divider-left').show();
                            $('.umi-divider-right').css({width: '100%'});
                        }
                    };

                    $('.umi-divider-left-toggle:not(.umi-disabled)').mousedown(function(){
                        showSideBarState = !showSideBarState;
                        showSideBar();
                    });

                    var floatDivider = function(){
                        floatSideBarState = true;
                        $('.umi-divider-left').addClass('umi-divider-left-float'); //Делаем SideBar плавающим
                        $('.umi-divider-left .umi-divider').hide();                //Скрываем полоску для изменения ширины (на подумать - сейчас отталкиваюсь от того, что на мобильных устройствах в ней нет необходимости, а случайные нажатия могут быть)
                    };

                    $('body').on('mousedown', '.umi-divider', function(event){
                        if(event.button === 0){
                            $('html').addClass('s-unselectable');

                            $('html').mousemove(function(event){
                                var w = event.pageX + 5 - 3; //Плюс ширина полоски изменения ширины SideBar

                                if(w < 150){w = 150}
                                if(w > $(window).width() - 720){
                                    $('.magellan-content').find('.umi-columns').removeClass('large-4').addClass('large-12');
                                }else{
                                    $('.magellan-content').find('.umi-columns').removeClass('large-12').addClass('large-4');
                                }
                                if(w > $(window).width() - 320){w = $(window).width() - 320}

                                $('.umi-divider-left').css({width: w});
                                $('.umi-divider-right').css({width: $(window).width() - w - 1});

                                $('html').on('mouseup', function(){
                                    sideBarWidth = w + 1;
                                    $('html').off('mousemove');
                                    $('html').removeClass('s-unselectable');
                                });
                            });
                        }
                    });

                    var moveDivider = function(){
                        floatSideBarState = false;

                        $('.umi-divider-left').removeClass('umi-divider-left-float');

                        if(showSideBarState){
                            $('.umi-divider-right').css({width: $(window).width() - sideBarWidth});
                        }

                        $('.umi-divider-left .umi-divider').show();
                    };

                    var checkWindowWidth = function(){
                        if($(window).width() < (320 + 250)){
                            floatDivider();
                        }else{
                            moveDivider();
                        }
                    };

                    checkWindowWidth();
                    $(window).on('resize', function(){
                        checkWindowWidth();
                    });

                } else{
                    $('.umi-divider-left-toggle').addClass('umi-disabled'); //Делаем кнопку неактивной
                    $('.umi-divider-left').hide();
                    $('.umi-divider-right').css({width: '100%'});
                }

                $('.umi-component').height(function(){
                    return $(window).height() - 141;
                });

                $(window).on('resize', function(){
                    $('.umi-component').height(function(){
                        return $(window).height() - 141;
                    });
                });
            }
        });
    };
});