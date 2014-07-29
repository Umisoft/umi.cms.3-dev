define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.DividerView = Ember.View.extend({
            classNames: ['off-canvas-wrap', 'umi-divider-wrapper', 's-full-height'],

            didInsertElement: function(){
                this.fakeDidInsertElement();
            },

            willDestroyElement:  function(){
                this.removeObserver('model');
            },

            modelChange: function(){
                this.fakeDidInsertElement();
            }.observes('model'),

            fakeDidInsertElement: function(){
                var $el = this.$();
                var $divider = $el.find('.umi-divider');
                var $sidebar = $el.find('.umi-divider-left');
                var $content = $el.find('.umi-divider-right');

                $el.off('mousedown.umi.divider.toggle');
                $divider.off('mousedown.umi.divider.proccess');

                if($sidebar.length){
                    $el.on('mousedown.umi.divider.toggle', '.umi-divider-left-toggle', function(){
                        $sidebar.toggleClass('hide');
                        $(this).children('.icon').toggleClass('icon-left').toggleClass('icon-right');
                    });

                    $divider.on('mousedown.umi.divider.proccess', function(event){
                        if(event.button === 0){
                            $sidebar.removeClass('divider-virgin');
                            $('html').addClass('s-unselectable');

                            $('html').on('mousemove.umi.divider.proccess', function(event){
                                var sidebarWidth = event.pageX - $el.offset().left;
                                sidebarWidth = sidebarWidth < 150 ? 150 : sidebarWidth;
                                sidebarWidth = sidebarWidth > 500 ? 500 : sidebarWidth;

                                if(sidebarWidth > $el.width() - 720){
                                    $el.find('.magellan-content').find('.umi-columns').removeClass('large-4').addClass('large-12');
                                }else{
                                    $el.find('.magellan-content').find('.umi-columns').removeClass('large-12').addClass('large-4');
                                }

                                $content.css({marginLeft: sidebarWidth + 1});
                                $sidebar.css({width: sidebarWidth});

                                $('html').on('mouseup.umi.divider.proccess', function(){
                                    $('html').off('mousemove.umi.divider.proccess');
                                    $('html').removeClass('s-unselectable');
                                });
                            });
                        }
                    });
                } else{
                    $content.css({'marginLeft': ''});
                }
            }
        });
    };
});
