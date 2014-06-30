

$(document).ready(function(){
  $('.carousel').carousel();
 $(document).click(function(event) {
    if ($(event.target).closest(".h-search-form").length) return;
        $('.h-search-form').removeClass('active').animate({'width':'12px'},200);
        $('.h-search-form').find('.input-group').css({'border':'none'});
    event.stopPropagation();
  });
  $('.h-search-form').click(function(){
    if(!$(this).hasClass('active')){
      $('.h-search-form').addClass('active').delay(200).animate({'width':'200px'},400);
      $('.h-search-form').find('.input-group').css({'border':'1px solid #fff'});
    }
  });
  $('a').hover(function(){
    $(this).animate({'opacity':'0.5'},200);
  },function(){
    $(this).animate({'opacity':'1'},100);
  });
  $('.list-blog .item').hover(function(){
    $(this).find('.desc').show(200);
  },function(){
    $(this).find('.desc').hide(200);
  });
  $('.header-height').height($('header').height());
  $(window).resize(function(){
    $('.header-height').height($('header').height());
  });
  $(window).scroll(function(){
    if($(document).scrollTop()>=$(window).height()){
      $('header').addClass('fixed');
    }else{
      $('header').removeClass('fixed');
    }
  });
  $('.top-scroll').click(function(){
    $('body').animate({scrollTop : 0},300);
    return false;
  });
});