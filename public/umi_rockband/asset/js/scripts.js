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
		if (header.has('.fixed') && jQuery(document).scrollTop() <= headerY.height()) {
			header.removeClass('fixed');
			setHeader();
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
				data: $('input:not([name="redirectUrl"]), textarea', self).serialize(),
				url: self.action + '.json'
			}).done(function( data ) {
					var user = data.layout.contents.user;
					if(user) {
						jQuery(self).html('<h4>Регистрация успешно завершена.</h4>');
					} else {
						jQuery(self).html('<h4>Вы успешно авторизовались.</h4>');
						jQuery('header#top .login').remove();
					}
			}).fail(function( data ) {
					var errors = data.responseJSON.layout.contents.errors,
						formErrors = data.responseJSON.layout.contents.form.errors,
						formElements = data.responseJSON.layout.contents.form.elements;

					//Clear old messages
					jQuery('.hint', self).remove();
					jQuery('.form-group', self).removeClass('input-group-error');

					//Add new error messages
					for(var field in formErrors) {
						var badField = jQuery('input[name^="' + field + '"]', self).eq(0);
						badField.closest('.form-group').addClass('input-group-error');

						var error = '<div class="hint"><ul>';
						for (var i = 0; i < formErrors[field].length; i++) {
							error += '<li>' + formErrors[field][i] + '</li>';
						}
						error += '</ul></div>';

						badField.before(error);
					}

					//Add form errors
					var error = '<div class="hint"><ul>';
					for (var i = 0; i < errors.length; i++) {
						error += '<li>' + errors[i] + '</li>';
					}
					error += '</ul></div>';
					jQuery(self).before(error);

					//Refresh CSRF token
					var csrf = jQuery('input[name="csrf"]', self);
					if(csrf[0]) {
						for (var i = 0; i < formElements.length; i++) {
							if (formElements[i].type == 'csrf')
								csrf.value = formElements[i].attributes.value;
						}
					}

					//Refresh captcha data
					var captcha = jQuery('input[name="captcha"]', self);
					if(captcha[0]) {
						for (var i = 0; i < elements.length; i++) {
							if (formElements[i].type == 'captcha' && formElements[i].isHuman != true) {
								var captchaImg = captcha.parent().find('span > img').eq(0);
								captchaImg.attr('src', formElements[i].url + '?' + Math.random());
							}
						}
					}
			});
		return false;
	});

});
