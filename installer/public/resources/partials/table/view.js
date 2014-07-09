define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'partials/table',
            classNames: ['umi-table'],
            headers: [],
            rows: [],
            offset: 0,
            limit: 25,
            totalBinding: 'rows.length',
            visibleRows: function(){
                var rows = this.get('rows');
                var offset = this.get('offset');
                var limit = parseFloat(this.get('limit'));
                var begin;
                var end;
                if(offset){
                    begin = limit * offset;
                } else{
                    begin = 0;
                }
                end = begin + limit;
                return rows.slice(begin, end);
            }.property('offset', 'limit'),

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

            paginationView: Ember.View.extend({
                classNames: ['s-unselectable', 'umi-toolbar'],
                templateName: 'partials/table/toolbar',
                counter: function(){
                    var label = 'из';
                    var limit = this.get('parentView.limit');
                    var offset = this.get('parentView.offset') + 1;
                    var total = this.get('parentView.total');
                    var maxCount = offset*limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('parentView.limit', 'parentView.offset', 'parentView.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        return this.get('parentView.parentView.offset');
                    }.property('parentView.parentView.offset'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('parentView.parentView').decrementProperty('offset');
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function(){
                        var limit = this.get('parentView.parentView.limit');
                        var offset = this.get('parentView.parentView.offset') + 1;
                        var total = this.get('parentView.parentView.total');
                        return total > limit * offset;
                    }.property('parentView.parentView.limit', 'parentView.parentView.offset', 'parentView.parentView.total'),

                    click: function(){
                        if(this.get('isActive')){
                            this.get('parentView.parentView').incrementProperty('offset');
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function(){
                        return this.get('parentView.parentView.limit');
                    }.property('parentView.parentView.limit'),

                    type: 'text',

                    keyDown: function(event){
                        if(event.keyCode === 13){
                            // При изменении количества строк на странице сбрасывается offset
                            this.get('parentView.parentView').setProperties({'offset': 0, 'limit': this.$()[0].value});
                        }
                    }
                })
            })
        });

        UMI.SiteAnalyzeTableView = UMI.TableView.extend({
            setContent: function(){
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data');
                if(data.length){
                    headers = data.shift();
                    this.setProperties({'headers': headers, 'rows': data});
                }
            }.observes('content').on('init')
        });

        UMI.BacklinksTableView = UMI.TableView.extend({
            setContent: function(){
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data');
                var rows = [];
                if(data.length){
                    headers = Ember.get(content, 'control.headers');
                    for(var i = 0; i < data.length; i++){
                        rows.push([Ember.get(data[0],'vs_from')]);
                    }
                    this.setProperties({'headers': headers, 'rows': rows});
                }
            }.observes('content').on('init')
        });

        UMI.TableCountersView = UMI.TableView.extend({
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
                }
            }),

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
            }.observes('content').on('init'),

            actions: {
                rowEvent: function(context){
                    this.get('controller').transitionToRoute('context', Ember.get(context, 'id'));
                }
            }
        });
    };
});