define([], function(){
    'use strict';
    return function(UMI){
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null,
            settingsAllowed: function(){
                return 'baseSettingsURL' in window.UmiSettings;
            }.property()
        });

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            collectionName: function(){
                var settings = this.get('settings');
                return settings.collectionName;
            }.property('settings'),
            settings: null,
            /**
             Выбранный контекcт, соответствующий модели роута 'Context'
             @property selectedContext
             @type String
             @default null
             */
            selectedContext: null,
            /**
             Вычисляемое свойсво возвращающее массив контролов для текущего контекста
             @method contentControls
             @return Array Массив
             */
            contentControls: function(){
                var self = this;
                var contentControls = [];
                var settings = this.get('settings');
                try{
                    var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                    var controls = settings.layout.contents[selectedContext];
                    var key;
                    var control;
                    for(key in controls){
                        if(controls.hasOwnProperty(key)){
                            control = controls[key];
                            control.name = key;
                            contentControls.push(Ember.Object.create(control));
                        }
                    }
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function(){
                        self.send('templateLogs', errorObject, 'component');
                    });
                }

                return contentControls;
            }.property('settings', 'selectedContext'),
            /**
             Контрол компонента в области сайд бара
             @property sideBarControl
             @type Boolean
             @default false
             */
            sideBarControl: function(){
                var sideBarControl;
                var self = this;
                try{
                    var settings = this.get('settings');
                    if(settings && settings.layout.hasOwnProperty('sideBar')){
                        var control;
                        for(control in settings.layout.sideBar){
                            if(settings.layout.sideBar.hasOwnProperty(control)){
                                sideBarControl = settings.layout.sideBar[control];
                                sideBarControl.name = control;
                                sideBarControl = Ember.Object.create(sideBarControl);
                            }
                        }
                    }
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    Ember.run.next(function(){
                        self.send('templateLogs', errorObject, 'component');
                    });
                }
                return sideBarControl;
            }.property('settings')
        });

        UMI.ContextController = Ember.ObjectController.extend({});

        UMI.ActionController = Ember.ObjectController.extend({
            queryParams: ['typeName'],
            typeName: null
        });

        UMI.ComponentView = Ember.View.extend({
            classNames: ['umi-content', 's-full-height'],

            didInsertElement: function(){
                //Хак-ускоритель переключения mode - переключает класс кнопки без задержки
                $('.umi-mode .button-group .button').mousedown(function(){
                    $('.umi-mode .button-group .button').removeClass('active');
                    $(this).addClass('active');
                });


                if(this.get('controller.sideBarControl')){
                    var showSideBarState = true; //{{!sideBarControl}}
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
//                    showSideBar();


                    $('.umi-divider-left-toggle').mousedown(function(){
                        showSideBarState = !showSideBarState;
                        showSideBar();
                    });


                    var floatDivider = function(){
                        floatSideBarState = true;
                        $('.umi-divider-left').addClass('umi-divider-left-float'); //Делаем SideBar плавающим
                        $('.umi-divider-left .umi-divider').hide();                //Скрываем полоску для изменения ширины (на подумать - сейчас отталкиваюсь от того, что на моильных устройствах в ней нет необходимости, а случайные нажатия могут быть)
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

                        $('.umi-divider-left .umi-divider').show();                //Показываем тянучку
                    };


                    var checkWindowWidth = function(){
                        if($(window).width() < (320 + 250)){
                            floatDivider();
                        }else{
                            moveDivider();
                        }
                    };


                    checkWindowWidth();
                    window.onresize = function(){
                        checkWindowWidth();
                    };

                } else{
                    $('.umi-divider-left').hide();
                    $('.umi-divider-right').css({width: '100%'});
                }

                $('.umi-component').height(function(){
                    return $(window).height() - 144;
                });

                window.onresize = function(){
                    $('.umi-component').height(function(){
                        return $(window).height() - 144;
                    });
                };
            }
        });
    };
});