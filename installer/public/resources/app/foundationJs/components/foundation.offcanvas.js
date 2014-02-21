;(function ($, window, document, undefined) {
  'use strict';

  Foundation.libs.offcanvas = {
    name : 'offcanvas',

    version : '5.0.0',

    settings : {},

    init : function (scope, method, options) {
      this.events();
    },

    events : function () {
      $(this.scope).off('.offcanvas')
        .on('click.fndtn.offcanvas', '.left-off-canvas-toggle', function (e) {
          e.preventDefault();
          $(this).closest('.off-canvas-wrap').toggleClass('move-right');
        })
        .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
          e.preventDefault();
          $(".off-canvas-wrap").removeClass("move-right");
        })
        .on('click.fndtn.offcanvas', '.right-off-canvas-toggle', function (e) {
          e.preventDefault();
          $(this).closest(".off-canvas-wrap").toggleClass("move-left");
        })
        .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
          e.preventDefault();
          $(".off-canvas-wrap").removeClass("move-left");
        });
    },

    reflow : function () {}
  };
}(jQuery, this, this.document));


$('.umi-divider').on('mousedown', function(event){
    var $that = $(this);
    $('html').addClass('s-unselectable');

    $('html').on('mousemove',function(event){
        event.preventDefault();
        if(event.pageX > 0 && event.pageX < $(window).width() / 2){
            $that.offset({"left": event.pageX - 5});
            $('.left-off-canvas-menu').width(event.pageX);
            $('.off-canvas-wrap.move-right').width('calc(100% - ' + event.pageX + 'px)');
            $('.inner-wrap').css({"-webkit-transform":'translate3d(' + event.pageX + 'px, 0, 0)'});
        }
    });

    $('html').on('mouseup',function(event){
        if(event.pageX < 100){
            $that.offset({"left": 0});
            $('.left-off-canvas-menu').width(0);
            $('.off-canvas-wrap.move-right').width('calc(100% - ' + 0 + 'px)');
            $('.inner-wrap').css({"-webkit-transform":'translate3d(' + 0 + 'px, 0, 0)'});
        }
        $('html').off('mousemove');
        $('html').removeClass('s-unselectable');
    });
});