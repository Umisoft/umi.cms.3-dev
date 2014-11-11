define([
    'text!./templates/auth.hbs', 'text!./templates/index.hbs', 'text!./templates/errors.hbs',
    'text!./templates/badBrowser.hbs', 'Handlebars'
], function(tpl, index, errors, badBrowser) {
    'use strict';
    return function(Auth) {
        Auth.TEMPLATES.app = Handlebars.compile(tpl);
        Auth.TEMPLATES.index = Handlebars.compile(index);
        Auth.TEMPLATES.errors = Handlebars.compile(errors);
        Auth.TEMPLATES.badBrowser = Handlebars.compile(badBrowser);
    };
});