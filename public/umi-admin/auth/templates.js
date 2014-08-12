define([
    'text!./templates/auth.hbs', 'text!./templates/index.hbs', 'text!./templates/errors.hbs', 'Handlebars'
], function(tpl, index, errors) {
    'use strict';
    return function(Auth) {
        Auth.TEMPLATES.app = Handlebars.compile(tpl);
        Auth.TEMPLATES.index = Handlebars.compile(index);
        Auth.TEMPLATES.errors = Handlebars.compile(errors);
    };
});