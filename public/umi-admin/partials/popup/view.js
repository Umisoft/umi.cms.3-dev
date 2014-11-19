define(['App'], function(UMI) {
    'use strict';

    return function() {

        UMI.PopupView = Ember.View.extend({
            controller: function() {
                return this.container.lookup('controller:popup');
            }.property(),

            classNames: ['umi-popup'],

            popupType: null,

            title: '',

            width: 800,

            height: 400,

            contentOverflow: ['overflow', 'hidden'],

            hasScroll: false,

            blur: false,

            fade: false,

            drag: true,

            resize: true,

            fixed: false,

            layoutName: 'partials/popup',

            templateName: function() {
                return 'partials/popup/' + this.get('popupType');
            }.property('popupType'),

            didInsertElement: function() {
                var self = this;

                self.addOverlay();

                if (self.get('blur')) {
                    self.addBlur();
                }

                if (self.get('fade')) {
                    self.fadeIn();
                }

                if (this.get('drag')) {
                    this.allowDrag();
                }

                if (this.get('resize')) {
                    this.allowResize();
                }

                if (this.get('contentOverflow') !== 'hidden' && Ember.typeOf(this.get('contentOverflow')) === 'array') {
                    $('.umi-popup-content').css(this.get('contentOverflow')[0], this.get('contentOverflow')[1]);
                }

                if (this.get('hasScroll')) {
                    this.initScroll();
                }

                this.setSize();
                this.setPosition();
            },

            /**
             * @hook
             */
            beforeClose: function() {
            },

            /**
             * @hook
             */
            afterClose: function() {
            },

            actions: {
                closePopup: function() {
                    this.beforeClose();
                    this.removeBlur();
                    this.removeOverlay();
                    this.get('controller').send('removePopupLayout');
                    this.afterClose();
                }
            },

            setSize: function() {
                var $el = this.$();
                $el.width(this.get('width'));
                $el.height(this.get('height'));
            },

            setPosition: function() {
                var $el = this.$();
                var styles = {};
                var elHeight = $el.height() / 2;
                var elWidth = $el.width() / 2;
                styles.marginTop = -($(window).height() > elHeight ? elHeight : $(window).height() / 2 - 50);
                styles.marginLeft = -($(window).width() > elWidth ? elWidth : $(window).width() / 2 - 50);
                if (this.fixed) {
                    styles.position = 'fixed';
                }
                $el.css(styles);
            },

            fadeIn: function() {
                var self = this;
                $('body').append('<div class="umi-popup-visible-overlay"></div>');
                $('body').on('click.umi.popup', '.umi-popup-visible-overlay', function() {
                    self.send('closePopup');
                    $('body').off('click.umi.popup');
                });
            },

            addOverlay: function() {
                $('body').append('<div class="umi-popup-invisible-overlay"></div>');
            },

            removeOverlay: function() {
                $('.umi-popup-invisible-overlay').remove();
            },

            addBlur: function() {
                $('.umi-header').addClass('s-blur');
                $('.umi-content').addClass('s-blur');
            },

            removeBlur: function() {
                $('.umi-header').removeClass('s-blur');
                $('.umi-content').removeClass('s-blur');
                $('.umi-popup-visible-overlay').remove();
            },

            allowResize: function() {
                var that = this;
                $('.umi-popup-resizer').show();
                $('body').on('mousedown', '.umi-popup-resizer', function(event) {
                    if (event.button === 0) {
                        //$('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var posX = $('.umi-popup').offset().left;
                        var posY = $('.umi-popup').offset().top;

                        $('html').addClass('s-unselectable');
                        $('html').mousemove(function(event) {
                            var w = event.pageX - posX;
                            var h = event.pageY - posY;

                            if (w < that.get('width')) {
                                w = that.get('width');
                            }
                            if (h < that.get('height')) {
                                h = that.get('height');
                            }

                            $('.umi-popup').css({width: w, height: h});

                            $('html').on('mouseup', function() {
                                $('html').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                //$('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            allowDrag: function() {
                var that = this;
                $('.umi-popup-header').css({'cursor': 'move'});
                $('body').on('mousedown', '.umi-popup-header', function(event) {
                    $('.umi-popup').css('z-index', '10000');
                    $(this).parent().css('z-index', '10001');

                    var $that = $(this);
                    if (event.button === 0) {
                        //$('body').append('<div class="umi-popup-invisible-overlay"></div>');
                        var clickX = event.pageX - $(this).offset().left;
                        var clickY = event.pageY - $(this).offset().top;

                        var windowHeight = $(window).height() - 34;
                        var windowWidth = $(window).width() - 34;


                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event) {
                            var x = event.pageX - clickX;
                            var y = event.pageY - clickY;

                            //Запрет на вывод Popup за пределы экрана
                            if (y <= 0) {
                                return;
                            }
                            if (y >= windowHeight) {
                                return;
                            }
                            if (x <= 68 - that.width) {
                                return;
                            } // 68 - чтобы не только крестик оставался, но и было за что без опаски схватить
                            if (x >= windowWidth) {
                                return;
                            }

                            $that.parent().offset({left: x, top: y});

                            $('body').on('mouseup', function() {
                                $('body').off('mousemove');
                                $('html').removeClass('s-unselectable');
                                //$('.umi-popup-invisible-overlay').remove();
                            });
                        });
                    }
                });
            },

            initScroll: function() {
                var $el = this.$();
                var $popupContent = $el.find('.s-scroll-wrap');
                var scrollContent = new IScroll($popupContent[0], UMI.config.iScroll);
                this.set('iScroll', scrollContent);
            },

            init: function() {
                this._super();
                var viewParams = this.get('controller.viewParams');
                if (Ember.typeOf(viewParams) === 'object') {
                    this.setProperties(viewParams);
                }
            }
        });
    };
});
