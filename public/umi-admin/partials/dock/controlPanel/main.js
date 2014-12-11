define(['App'], function(UMI) {
    'use strict';
    return function() {
        UMI.DockControlPanelController = Ember.ObjectController.extend({
            needs: 'dock',

            modesBinding: 'controllers.dock.modes',

            activeModeBinding: 'controllers.dock.activeMode',

            actions: {
                changeDockMode: function(newActiveMode) {
                    var modes = this.get('modes');
                    var activeModule = this.get('activeMode');
                    Ember.set(activeModule, 'isActive', false);
                    Ember.set(newActiveMode, 'isActive', true);
                    UMI.Utils.LS.set('dock.activeModeName', newActiveMode.name);
                }
            }
        });

        UMI.DockControlPanelView = Ember.View.extend({
            templateName: 'partials/dockControlPanel',
            tagName: 'ul',
            classNames: ['f-dropdown-double', 'small-labels']
        });
    };
});
