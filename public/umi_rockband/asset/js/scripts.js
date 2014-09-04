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
        if (jQuery(document).scrollTop() <= headerY.height()) {
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

	jQuery(".modal-content form[action]").submit(function() {
		var self = this;
			$.ajax({
				type: self.method,
				data: $('input:visible, textarea, input[name="csrf"]', self).serialize(),
				url: self.action + '.json'
			}).done(function( data ) {
					console.log( "Without errors:", data );
			}).fail(function( data ) {
					//the Enter
					alert('Form sended');

					var errors = data.responseJSON.layout.contents.form.errors,
						elements = data.responseJSON.layout.contents.form.elements;

					//Clear old messages
					jQuery('.hint', self).remove();
					jQuery('.form-group', self).removeClass('input-group-error');

					//Add new error messages
					for(var prop in errors) {
						var badField = jQuery('input[name^="' + prop + '"]', self).eq(0);
						badField.closest('.form-group').addClass('input-group-error');

						var error = '<div class="hint"><ul>';
						for (var i = 0; i < errors[prop].length; i++) {
							error += '<li>' + errors[prop][i] + '</li>';
						}
						error += '</ul></div>';

						badField.before(error);
					}

					//Refresh CSRF token
					var csrf = jQuery('input[name="csrf"]', self);
					if(csrf[0]) {
						for (var i = 0; i < elements.length; i++) {
							if (elements[i].type == 'csrf')
								csrf.value = elements[i].attributes.value;
						}
					}

					//Refresh captha data
			});
		return false;
	});

});
