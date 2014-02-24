define([
    'text!./templates/auth.hbs', 'text!./templates/index.hbs', 'text!./templates/errors.hbs', 'Handlebars'
], function(tpl, index, errors){
        'use strict';
        return function(Auth){
            Auth.TEMPLATES.app = Handlebars.compile(tpl);
            Auth.TEMPLATES.index = Handlebars.compile(index);
            Auth.TEMPLATES.lostPassword = Handlebars.compile(index);
            Auth.TEMPLATES.forgetLink = Handlebars.compile('{{#link-to "lostPassword" class="button"}}Забыли пароль?{{/link-to}}');
            Auth.TEMPLATES.indexLink = Handlebars.compile('{{#link-to "index" class="button"}}Войти в CMS{{/link-to}}');
            Auth.TEMPLATES.errors = Handlebars.compile(errors);
        };
    });