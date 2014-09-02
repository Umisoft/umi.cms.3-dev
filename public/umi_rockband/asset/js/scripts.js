jQuery(document).ready(function() {
    jQuery('.carousel').carousel();

	var searchForm = jQuery('.h-search-form').click(function() {
		jQuery('.form-control', this).focus();
	});
    jQuery('.form-control', searchForm).focus(function(){
		searchForm.addClass('active');
	}).blur(function() {
		searchForm.removeClass('active');
	});

	var headerY = jQuery('.header-height'),
		header = jQuery('header'),
		setHeader = function() {
		headerY.height(header.height() + 20 );
	};

	setHeader();
    jQuery(window).resize(function() {
		setHeader();
    });
    jQuery(window).scroll(function() {
        if (jQuery(document).scrollTop() <= header.height()-20) {
			header.filter('.fixed').each(function() {
				jQuery(this).removeClass('fixed');
				setHeader();
			});
        } else {
			header.addClass('fixed');
        }
    });
    jQuery('.top-scroll').click(function() {
		jQuery('html, body').animate({scrollTop: 0}, 300);
        return false;
    });
    jQuery('.radio').click(function() {
        jQuery(this).toggleClass('active');
        return false;
    });

	jQuery('button[name^="re:"]').on('click', function() {
		var form = jQuery(this).closest('#comments').find('form');
		form.attr('action', this.value);
		jQuery('input[name="displayName"]', form).val(this.name);
		var target = jQuery('textarea', form);
		jQuery('html, body').animate({scrollTop: jQuery(target).offset().top - 200}, 500, function() {
			target.focus();
		});
	});

	var comments = jQuery('.comment');
	if(comments) {
		for(var i = 0; i < comments.length; i = i+2) {
			jQuery(comments[i]).addClass('even')
		}
	}

});
