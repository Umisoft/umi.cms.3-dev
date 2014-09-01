define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TableView = Ember.View.extend(UMI.i18nInterface, {
            dictionaryNamespace: 'table',
            localDictionary: function() {
                var table = this.get('content.control') || {};
                return table.i18n;
            }.property('content'),
            templateName: 'partials/table',
            classNames: ['umi-table'],
            headers: [],
            rows: [],
            offset: 0,
            limit: 25,
            totalBinding: 'rows.length',
            error: null,
            visibleRows: function() {
                var self = this;
                var controller = self.get('controller');
                var rows = self.get('rows');
                var offset = self.get('offset');
                var limit = parseFloat(self.get('limit'));
                var begin;
                var end;
                if (offset) {
                    begin = limit * offset;
                } else {
                    begin = 0;
                }
                end = begin + limit;
                Ember.run.later(self, function() {
                    controller.send('hideLoader');
                }, 300);
                return rows.slice(begin, end);
            }.property('offset', 'limit'),

            iScroll: null,

            iScrollUpdate: function() {
                var iScroll = this.get('iScroll');
                if (iScroll) {
                    setTimeout(function() {
                        iScroll.refresh();
                        iScroll.scrollTo(0, 0);
                    }, 100);
                }
            }.observes('visibleRows'),

            didInsertElement: function() {
                if (!this.get('error')) {
                    var $table = this.$();
                    var tableContent = $table.find('.s-scroll-wrap')[0];
                    var tableHeader = $table.find('.umi-table-header')[0];
                    var scrollContent = new IScroll(tableContent, UMI.config.iScroll);
                    var tableContentRowSize = $(tableContent).find('.umi-table-content-sizer')[0];
                    this.set('iScroll', scrollContent);
                    scrollContent.on('scroll', function() {
                        tableHeader.style.marginLeft = this.x + 'px';
                    });

                    $(window).on('resize.umi.table', function() {
                        setTimeout(function() {
                            tableHeader.style.marginLeft = scrollContent.x + 'px';
                        }, 100);
                    });

                    // Событие изменения ширины колонки
                    $table.on('mousedown.umi.table', '.umi-table-header-column-resizer', function() {
                        $('html').addClass('s-unselectable');
                        var handler = this;
                        $(handler).addClass('on-resize');
                        var columnEl = handler.parentNode.parentNode;
                        var columnIndex = $(columnEl).closest('tr').children('.umi-table-td').index(columnEl);
                        var columnOffset = $(columnEl).offset().left;
                        var columnWidth;
                        var contentCell = tableContentRowSize.querySelectorAll('.umi-table-td')[columnIndex];
                        $('body').on('mousemove.umi.table', function(event) {
                            event.stopPropagation();
                            columnWidth = event.pageX - columnOffset;
                            if (columnWidth >= 60 && columnEl.offsetWidth > 59) {
                                columnEl.style.width = contentCell.style.width = columnWidth + 'px';
                            }
                        });

                        $('body').on('mouseup.umi.table', function() {
                            $('html').removeClass('s-unselectable');
                            $(handler).removeClass('on-resize');
                            $('body').off('mousemove.umi.table');
                            $('body').off('mouseup.umi.table');
                            scrollContent.refresh();
                            tableHeader.style.marginLeft = scrollContent.x + 'px';
                        });
                    });
                }
            },

            willDestroyElement: function() {
                var $table = this.$();
                $(window).off('resize.umi.table');
                this.removeObserver('content');
                this.removeObserver('visibleRows');
                $table.off('mousedown.umi.table');
            },

            paginationView: Ember.View.extend({
                classNames: ['s-unselectable', 'umi-toolbar'],
                templateName: 'partials/table/toolbar',
                counter: function() {
                    var label = (UMI.i18n.getTranslate('Of') || '').toLowerCase();
                    var limit = this.get('parentView.limit');
                    var offset = this.get('parentView.offset') + 1;
                    var total = this.get('parentView.total');
                    var maxCount = offset * limit;
                    var start = maxCount - limit + 1;
                    maxCount = maxCount < total ? maxCount : total;
                    return start + '-' + maxCount + ' ' + label + ' ' + total;
                }.property('parentView.limit', 'parentView.offset', 'parentView.total'),

                prevButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        return this.get('parentView.parentView.offset');
                    }.property('parentView.parentView.offset'),

                    click: function() {
                        var self = this;
                        var controller = self.get('controller');
                        if (self.get('isActive')) {
                            controller.send('showLoader');
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').decrementProperty('offset');
                            });
                        }
                    }
                }),

                nextButtonView: Ember.View.extend({
                    classNames: ['button', 'secondary', 'tiny'],
                    classNameBindings: ['isActive::disabled'],

                    isActive: function() {
                        var limit = this.get('parentView.parentView.limit');
                        var offset = this.get('parentView.parentView.offset') + 1;
                        var total = this.get('parentView.parentView.total');
                        return total > limit * offset;
                    }.property('parentView.parentView.limit', 'parentView.parentView.offset', 'parentView.parentView.total'),

                    click: function() {
                        var self = this;
                        var controller = self.get('controller');
                        if (self.get('isActive')) {
                            controller.send('showLoader');
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').incrementProperty('offset');
                            });
                        }
                    }
                }),

                limitView: Ember.View.extend({
                    tagName: 'input',
                    classNames: ['s-margin-clear'],
                    attributeBindings: ['value', 'type'],

                    value: function() {
                        return this.get('parentView.parentView.limit');
                    }.property('parentView.parentView.limit'),

                    type: 'text',

                    keyDown: function(event) {
                        var self = this;
                        var controller = self.get('controller');
                        if (event.keyCode === 13) {
                            controller.send('showLoader');
                            // При изменении количества строк на странице сбрасывается offset
                            Ember.run.next(self, function() {
                                self.get('parentView.parentView').setProperties({'offset': 0, 'limit': self.$()[0].value});
                            });
                        }
                    }
                })
            })
        });

        UMI.SiteAnalyzeTableView = UMI.TableView.extend({
            setContent: function() {
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data.data') || [];
                var index;
                if (data.length) {
                    headers = data.shift();
                    index = headers.indexOf('n/a');
                    headers.splice(index, 1);
                    for (var i = 0; i < data.length; i++) {
                        data[i].splice(index, 1);
                    }
                    this.setProperties({'headers': headers, 'rows': data});
                }
            }.observes('content').on('init')
        });

        UMI.BacklinksTableView = UMI.TableView.extend({
            setContent: function() {
                var content = this.get('content');
                var headers;
                var data = Ember.get(content, 'control.data');
                var rows = [];
                if (Ember.typeOf(data) === 'object' && 'error' in data) {
                    this.set('error', data.error);
                } else if (Ember.typeOf(data) === 'array') {
                    headers = [Ember.get(content, 'control.labels.vs_from')];
                    for (var i = 0; i < data.length; i++) {
                        rows.push([Ember.get(data[i], 'vs_from')]);
                    }
                    this.setProperties({'headers': headers, 'rows': rows});
                }
            }.observes('content').on('init')
        });

        UMI.YaHostTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var key;

                if (Ember.typeOf(labels) === 'object') {
                    for (key in labels) {
                        if (labels.hasOwnProperty(key)) {
                            headers.push(labels[key]);
                        }
                    }
                }
                if (Ember.typeOf(data) === 'object') {
                    for (key in data) {
                        if (data.hasOwnProperty(key)) {
                            var row = UMI.Utils.getStringValue(data[key]);
                            rows.push(row);
                        }
                    }
                }
                this.setProperties({'headers': headers, 'rows': [rows]});
            }.observes('content').on('init')
        });

        UMI.YaIndexesTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var i;
                var url = Ember.get(data, 'last-week-index-urls.url');

                headers.push(Ember.get(labels, 'last-week-index-urls'));

                if (Ember.typeOf(url) === 'array') {
                    for (i = 0; i < url.length; i++) {
                        rows.push([UMI.Utils.getStringValue(url[i])]);
                    }
                }
                this.setProperties({'headers': headers, 'rows': rows});
            }.observes('content').on('init')
        });

        UMI.YaTopsTableView = UMI.TableView.extend({
            setContent: function() {
                var control = this.get('content.control');
                var headers = [];
                var rows = [];
                var labels = Ember.get(control, 'labels');
                var data = Ember.get(control, 'data');
                var i;
                var row;
                var topQueries = Ember.get(data, 'top-queries.top-clicks.top-info');

                headers.push(Ember.get(labels, 'query'));
                headers.push(Ember.get(labels, 'count'));
                headers.push(Ember.get(labels, 'position'));
                headers.push(Ember.get(labels, 'clicks-top-rank'));

                if (Ember.typeOf(topQueries) === 'array') {
                    for (i = 0; i < topQueries.length; i++) {
                        row = [];
                        row.push(UMI.Utils.getStringValue(topQueries[i].query));
                        row.push(UMI.Utils.getStringValue(topQueries[i].count));
                        row.push(UMI.Utils.getStringValue(topQueries[i].position));
                        row.push(UMI.Utils.getStringValue(topQueries[i]['clicks-top-rank']));
                        rows.push(row);
                    }
                }
                this.setProperties({'headers': headers, 'rows': rows});
            }.observes('content').on('init')
        });

        UMI.TableCountersView = UMI.TableView.extend({
            rowCount: function() {
                var rows = this.get('rows') || [];
                var row = rows[0] || {};
                var count = [];
                for (var key in row) {
                    if (row.hasOwnProperty(key)) {
                        count.push({});
                    }
                }
                return count;
            }.property('rows'),

            rowView: Ember.View.extend({
                tagName: 'tr',
                classNames: ['umi-table-content-tr'],
                cell: function() {
                    var object = this.get('row');
                    var cell = [];
                    for (var key in object) {
                        if (object.hasOwnProperty(key)) {
                            cell.push({'displayName': object[key]});
                        }
                    }
                }
            }),

            setContent: function() {
                var content = this.get('content');
                var headers = Ember.get(content, 'control.meta.labels');
                var headersList = [];
                var rows = Ember.get(content, 'control.meta.objects');
                for (var key in headers) {
                    if (headers.hasOwnProperty(key)) {
                        headersList.push(headers[key]);
                    }
                }
                this.setProperties({'headers': headersList, 'rows': rows});
            }.observes('content').on('init'),

            actions: {
                rowEvent: function(context) {
                    this.get('controller').transitionToRoute('context', Ember.get(context, 'id'));
                }
            }
        });
    };
});