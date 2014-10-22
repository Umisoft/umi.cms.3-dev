define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            var objectFetch = function(routeData) {
                var meta = Ember.get(routeData, 'control.meta');
                var incompleteObject = Ember.get(routeData, 'object');
                var collectionName = incompleteObject.constructor.typeKey;
                var store = incompleteObject.get('store');
                var fields;
                var ignoreTypes = ['fieldset'];
                var promises = [];
                fields = UMI.FormHelper.getNestedProperties(Ember.get(meta, 'elements'), ignoreTypes);

                var request = UMI.OrmHelper.buildRequest(incompleteObject, fields);
                request.filters = {id: incompleteObject.get('id')};
                promises.push(store.updateCollection(collectionName, request));

                var lazyProperties = UMI.FormHelper.filterLazyProperties(Ember.get(meta, 'elements'));
                var manyRelationProperties = UMI.OrmHelper.getRelationProperties(incompleteObject, lazyProperties);

                for (collectionName in manyRelationProperties) {
                    if (manyRelationProperties.hasOwnProperty(collectionName)) {
                        promises.push(store.updateCollection(collectionName, {
                            fields: manyRelationProperties[collectionName]
                        }));
                    }
                }

                return Ember.RSVP.all(promises);
            };

            var FormControlPromiseService = Ember.Object.extend({
                execute: function(model) {
                    var defer = Ember.RSVP.defer();

                    objectFetch(model).then(
                        function(result) {
                            defer.resolve(result);
                        },
                        function(error) {
                            defer.reject(error);
                        }
                    );

                    return defer.promise;
                }
            });

            UMI.register('service:formControlPromise', FormControlPromiseService);
            UMI.inject('controller:action', 'editFormPromiseService', 'service:formControlPromise');

            var CreateFormControlPromiseService = Ember.Object.extend({
                execute: function(model) {
                    var defer = Ember.RSVP.defer();
                    var replacedModel = $.extend({}, model);
                    replacedModel.object = replacedModel.createObject;

                    objectFetch(replacedModel).then(
                        function(result) {
                            defer.resolve(result);
                        },
                        function(error) {
                            defer.reject(error);
                        }
                    );

                    return defer.promise;
                }
            });

            UMI.register('service:createFormControlPromise', CreateFormControlPromiseService);
            UMI.inject('controller:action', 'createFormPromiseService', 'service:createFormControlPromise');
        };
    }
);