define(['App'], function(UMI){
    'use strict';
    return function(){
        UMI.TableControlView = Ember.View.extend({
            templateName: 'tableControl',
            classNames: ['umi-table-control'],
            iScroll: null,
            didInsertElement: function(){
                var tableControl = this.$();
                var tableContent = tableControl.find('.s-scroll-wrap');
                var scrollContent = new IScroll(tableContent[0], UMI.config.iScroll);
                this.set('iScroll', scrollContent);
            }
        });

        UMI.TableCellView = Ember.View.extend({
            classNames: ['table-cell'],
            attributeBindings: ['computedStyle:style'],
            computedStyle: function(){
                var columnWidth = this.get('column').width || 200;
                return 'width: ' + columnWidth + 'px;';
            }.property('column')
        });

        UMI.TableCellContentView = UMI.TableCellView.extend({
            template: function(){
                var meta = this.get('column');
                var object = this.get('object');
                var template;
                template = Ember.Handlebars.compile(object.get(meta.name) + '&nbsp;');
                return template;
            }.property('object','column')
        });
    };
});