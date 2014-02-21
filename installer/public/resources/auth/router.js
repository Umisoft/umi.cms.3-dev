define(function(){
	'use strict';

	return function(UMI){
		UMI.Router.map(function(){
			this.route('lostPassword');
		});

		UMI.Router.reopen({
			location: 'history',
			rootURL: '/admin/'
		});

		UMI.ErrorState = Ember.Mixin.create({//TODO: Обрабатывать все типы ошибок, и разные роуты
			actions: {
				error: function(error, originRoute){
					this.transitionTo('error', 'error');
				}
			}
		});

		UMI.ApplicationRoute = Ember.Route.extend({
			renderTemplate: function(){
				this.render('auth');
			}
		});

		UMI.IndexRoute = Ember.Route.extend({
			renderTemplate: function(){
				this.render('index', {
					outlet: 'content'
				});
				this.render('forgetLink', {
					outlet: 'links'
				});
			}
		});

		UMI.LostPasswordRoute = Ember.Route.extend({
			renderTemplate: function(){
				this.render('lostPassword', {
					outlet: 'content'
				});
				this.render('indexLink', {
					outlet: 'links'
				});
			}
		});
	};
});