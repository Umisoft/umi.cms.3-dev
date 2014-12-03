define(['jquery', 'Ember', 'module/eip/core/application/main', 'module/eip/core/router/router', 'topbar'], function($, Ember, application, router, topbar) {
    'use strict';

    var UMI = {};

    var createRootElement = function() {
        $('body').prepend('<div id="umi-eip-application"></div>');
    };

    createRootElement();

    /**
     * Для отключения "магии" переименования моделей Ember.Data
     * @class Inflector.inflector
     */
    //Ember.Inflector.inflector = new Ember.Inflector();

    UMI = Ember.Application.create({
        rootElement: '#umi-eip-application',

        Resolver: Ember.DefaultResolver.extend({
            resolveTemplate: function(parsedName) {
                parsedName.fullNameWithoutType = 'UMI/' + parsedName.fullNameWithoutType;
                return this._super(parsedName);
            }
        })
    });

    UMI.deferReadiness();

    application(UMI);
    router(UMI);
    topbar(UMI);

    UMI.advanceReadiness();

    return UMI;
});