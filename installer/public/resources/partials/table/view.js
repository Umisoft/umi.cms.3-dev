define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'table',
            classNames: ['umi-table'],
            headers: [],
            rows: [],
            rowCount: function(){
                var rows = this.get('rows') || [];
                var row = rows[0] || {};
                var count = [];
                for(var key in row){
                    if(row.hasOwnProperty(key)){
                        count.push({});
                    }
                }
                return count;
            }.property('rows'),
            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-content-tr'],
                cell: function(){
                    var object = this.get('row');
                    var cell = [];
                    for(var key in object){
                        if(object.hasOwnProperty(key)){
                            cell.push({'displayName': object[key]});
                        }
                    }

                    return cell;
                }.property('row'),
                click: function(){
                    this.get('parentView').send('rowEvent', this.get('row'));
                }
            }),
            didInsertElement: function(){
                var tableContent = this.$().find('.s-scroll-wrap')[0];
                var tableHeader = this.$().find('.umi-table-header')[0];
                var scrollContent = new IScroll(tableContent, UMI.config.iScroll);

                scrollContent.on('scroll', function(){
                    tableHeader.style.marginLeft = this.x + 'px';
                });

                $(window).on('resize.umi.table', function(){
                    setTimeout(function(){
                        tableHeader.style.marginLeft = scrollContent.x + 'px';
                    }, 100);
                });
            },
            willDestroyElement: function(){
                $(window).off('resize.umi.table');
                this.removeObserver('content');
            },
            actions: {
                rowEvent: function(context){
                    this.get('controller').transitionToRoute('context', Ember.get(context, 'id'));
                }
            }
        });

        UMI.TableCountersView = UMI.TableView.extend({
            setContent: function(){
                var content = this.get('content');
                var headers = Ember.get(content, 'control.meta.labels');
                var headersList = [];
                var rows = Ember.get(content, 'control.meta.objects');
                for(var key in headers){
                    if(headers.hasOwnProperty(key)){
                        headersList.push(headers[key]);
                    }
                }
                this.setProperties({'headers': headersList, 'rows': rows});
            }.observes('content').on('init')
        });
    };
});