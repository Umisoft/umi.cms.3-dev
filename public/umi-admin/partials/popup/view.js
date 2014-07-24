define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.PopupView = Ember.View.extend({
            controller: function(){
                return this.container.lookup('controller:popup');
            }.property(),

            popupTypeBinding: 'controller.popupType',

            classNames: ['umi-popup'],

            title: '',

            width: 800,

            height: 400,

            contentOverflow: ['overflow', 'hidden'],

            blur: true,

            fade: false,

            drag: true,

            resize: true,

            layoutName: 'partials/popup',

            templateName: function(){
                return 'partials/popup/' + this.get('popupType');
            }.property('popupType'),


            //Параметры приходящие из childView
            contentParams: {},

            checkContentParams: function(){
                this.get('object').set(this.get('meta.dataSource'), this.contentParams.fileInfo.path);
            }.observes('contentParams'),

            didInsertElement: function(){
                if(this.blur){this.addBlur()}
                if(this.fade){this.fadeIn()}
                if(this.drag){this.allowDrag()}
                if(this.resize){this.allowResize()}
                if(this.contentOverflow !== 'hidden'){
                    $('.umi-popup-content').css(this.contentOverflow[0], this.contentOverflow[1]);
                }
                this.setSize();
            },

            actions: {
                closePopup: function(){
                    this.get('controller').send('closePopup');
                }
            },

            setSize: function(){
                this.$().width(this.width);
                this.$().height(this.height);
            },

            fadeIn: function(){
                var self = this;
                $('body').append('<div class="umi-popup-visible-overlay"></div>');
                $('body').on('click.umi.popup', '.umi-popup-visible-overlay', function(){
                    self.send('closePopup');
                    $('body').off('click.umi.popup');
                });
            },

            addBlur: function(){
                $('.umi-header').addClass('s-blur');
                $('.umi-content').addClass('s-blur');
                $('body').append('<div class="umi-popup-invisible-overlay"></div>');
            },

            removeBlur: function(){
                $('.umi-header').removeClass('s-blur');
                $('.umi-content').removeClass('s-blur');
                $('.umi-popup-invisible-overlay').remove();
                $('.umi-popup-visible-overlay').remove();
            },

            allowResize: function(){
                var that = this;
                $('.umi-popup-resizer').show();
                $('body').on('mousedown', '.umi-popup-resizer', function(event){
                    if(event.button === 0){
                        $('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var posX = $('.umi-popup').offset().left;
                        var posY = $('.umi-popup').offset().top;

                        $('html').addClass('s-unselectable');
                        $('html').mousemove(function(event){
                            var w = event.pageX - posX;
                            var h = event.pageY - posY;

                            if(w < that.get('width')){w = that.get('width')}
                            if(h < that.get('height')){h = that.get('height')}

                            $('.umi-popup').css({width: w, height: h});

                            $('html').on('mouseup', function(){
                                $('html').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                $('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            allowDrag: function(){
                var that = this;
                $('.umi-popup-header').css({'cursor':'move'});
                $('body').on('mousedown', '.umi-popup-header', function(event){
                    $('.umi-popup').css('z-index', '10000');
                    $(this).parent().css('z-index', '10001');

                    var $that = $(this);
                    if(event.button === 0){
                        $('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var clickX = event.pageX - $(this).offset().left;
                        var clickY = event.pageY - $(this).offset().top;

                        var windowHeight = $(window).height() - 34;
                        var windowWidth = $(window).width() - 34;


                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event){
                            var x = event.pageX - clickX;
                            var y = event.pageY - clickY;

                            //Запрет на вывод Popup за пределы экрана
                                if(y <= 0){return}
                                if(y >= windowHeight){return}
                                if(x <= 68 - that.width){return} // 68 - чтобы не только крестик оставался, но и было за что без опаски схватить
                                if(x >= windowWidth){return}

                            $that.parent().offset({left: x, top: y});

                            $('body').on('mouseup', function(){
                                $('body').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                $('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            }
        });
    };
});