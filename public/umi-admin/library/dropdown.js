(function($, window, document, undefined) {
    'use strict';

    var Foundation = window.Foundation = window.Foundation || {};
    Foundation.libs = Foundation.libs || {};

    /**
     * UMI расширяет поведение выпадающих списков Foundation.
     */
    Foundation.libs.dropdown = {
        name: 'dropdown',
        version: '5.3.0.umi-custom',

        settings: {
            activeClass: 'open',
            isHover: false,
            selectOnPress: false,
            selectorById: true,
            customEventListener: function() {},
            opened: function() {},
            closed: function() {}
        },

        selector: function() {
            return '[data-' + this.name + ']';
        },

        init: function(scope, method, options) {
            Foundation.inherit(this, 'throttle');

            this.bindings(method, options);
        },

        events: function(scope) {
            var self = this;
            var S = self.S;
            var settings = S(scope).data(self.attr_name(true) + '-init') || self.settings;

            S(self.scope).off('.' + self.name);

            if (settings.selectOnPress) {
                self.eventListener.mousedown.call(this, scope, settings);
            }
            this.eventListener.click.call(this, scope, settings);
            this.eventListener.clickOutOfDropdown.call(this, scope, settings);
        },

        eventListener: {
            click: function(scope, settings) {
                var self = this;
                var S = self.S;

                S(this.scope).on('click.fndtn.dropdown', this.selector(), function(e) {
                    if (!settings.isHover || Modernizr.touch) {
                        e.preventDefault();
                        self.toggle($(this));
                    }
                });
            },

            mousedown: function(scope, settings) {
                var self = this;
                var S = self.S;

                S(this.scope).on('mousedown.fndtn.dropdown', this.selector(), function(e) {
                    if (!settings.isHover || Modernizr.touch) {
                        e.preventDefault();
                        self.toggle($(this));
                    }
                });
            },

            clickOutOfDropdown: function(scope, settings) {
                var self = this;
                var S = self.S;

                S(this.scope).on('click.fndtn.dropdown', this.selector(), function(e) {
                    var parent = S(e.target).closest('[' + self.attr_name() + '-content]');

                    if (S(e.target).data(self.data_attr()) || S(e.target).parent().data(self.data_attr())) {
                        return;
                    }

                    if (!(S(e.target).data('revealId')) &&
                        (parent.length > 0 && (S(e.target).is('[' + self.attr_name() + '-content]') ||
                        $.contains(parent.first()[0], e.target)))) {
                        e.stopPropagation();
                        return;
                    }

                    self.close.call(self, S('[' + self.attr_name() + '-content]'));
                });
            }
        },

        getDropdown: function(target) {
            var self = this;
            var S = self.S;
            var dropdown;
            var settings = S(target).data(self.attr_name(true) + '-init') || self.settings;

            if (settings.selectorById) {
                dropdown = S('#' + target.data(this.data_attr()));
            } else {
                dropdown = $($(S(target)[0].parentNode).children('[data-' + self.name + '-content]')[0]);
            }

            return dropdown;
        },

        toggle: function(target) {
            var self = this;
            var dropdown = self.getDropdown(target);
            if (dropdown.length === 0) {
                return;
            }

            self.close.call(self, self.S('[' + self.attr_name() + '-content]').not(dropdown));

            if (dropdown.hasClass(self.settings.activeClass)) {
                self.close.call(self, dropdown);
                if (dropdown.data('target') !== target.get(0)) {
                    self.open.call(self, dropdown, target);
                }
            } else {
                self.open.call(self, dropdown, target);
            }
        },

        close: function(dropdown) {
            var self = this;

            dropdown.each(function() {
                if (self.S(this).hasClass(self.settings.activeClass)) {
                    self.S(this)
                        .css(Foundation.rtl ? 'right' : 'left', '-99999px')
                        .removeClass(self.settings.activeClass)
                        .prev('[' + self.attr_name() + ']')
                        .removeClass(self.settings.activeClass)
                        .removeData('target');

                    self.S(this).trigger('closed').trigger('closed.fndtn.dropdown', [dropdown]);
                }
            });
        },

        closeall: function() {
            var self = this;
            $.each(self.S('[' + this.attr_name() + '-content]'), function() {
                self.close.call(self, self.S(this));
            });
        },

        open: function(dropdown, target) {
            this.css(dropdown.addClass(this.settings.activeClass), target);
            dropdown.prev('[' + this.attr_name() + ']').addClass(this.settings.activeClass);
            dropdown.data('target', target.get(0)).trigger('opened')
                .trigger('opened.fndtn.dropdown', [dropdown, target]);
        },

        data_attr: function() {
            if (this.namespace.length > 0) {
                return this.namespace + '-' + this.name;
            }

            return this.name;
        },

        css: function(dropdown, target) {
            var left_offset = Math.max((target.width() - dropdown.width()) / 2, 8);

            this.clear_idx();

            if (this.small()) {
                var p = this.dirs.bottom.call(dropdown, target);

                dropdown.attr('style', '').removeClass('drop-left drop-right drop-top').css({
                    position: 'absolute',
                    width: '95%',
                    'max-width': 'none',
                    top: p.top
                });

                dropdown.css(Foundation.rtl ? 'right' : 'left', left_offset);
            } else {
                var settings = target.data(this.attr_name(true) + '-init') || this.settings;

                this.style(dropdown, target, settings);
            }

            return dropdown;
        },

        style: function(dropdown, target, settings) {
            var css = $.extend({position: 'absolute'},
                this.dirs[settings.align].call(dropdown, target, settings));

            dropdown.attr('style', '').css(css);
        },

        dirs: {
            _base: function(t) {
                var o_p = this.offsetParent(),
                    o = o_p.offset(),
                    p = t.offset();

                p.top -= o.top;
                p.left -= o.left;

                return p;
            },

            top: function(t, s) {
                var self = Foundation.libs.dropdown,
                    p = self.dirs._base.call(this, t),
                    pip_offset_base = 8;

                this.addClass('drop-top');

                if (t.outerWidth() < this.outerWidth() || self.small()) {
                    self.adjust_pip(pip_offset_base, p);
                }

                if (Foundation.rtl) {
                    return {left: p.left - this.outerWidth() + t.outerWidth(),
                        top: p.top - this.outerHeight()};
                }

                return {left: p.left, top: p.top - this.outerHeight()};
            },

            bottom: function(t, s) {
                var self = Foundation.libs.dropdown,
                    p = self.dirs._base.call(this, t),
                    pip_offset_base = 8;

                if (t.outerWidth() < this.outerWidth() || self.small()) {
                    self.adjust_pip(pip_offset_base, p);
                }

                if (self.rtl) {
                    return {left: p.left - this.outerWidth() + t.outerWidth(), top: p.top + t.outerHeight()};
                }

                return {left: p.left, top: p.top + t.outerHeight()};
            },

            left: function(t, s) {
                var p = Foundation.libs.dropdown.dirs._base.call(this, t);

                this.addClass('drop-left');

                return {left: p.left - this.outerWidth(), top: p.top};
            },

            right: function(t, s) {
                var p = Foundation.libs.dropdown.dirs._base.call(this, t);

                this.addClass('drop-right');

                return {left: p.left + t.outerWidth(), top: p.top};
            }
        },

        clear_idx: function() {
            var sheet = Foundation.stylesheet;

            if (this.rule_idx) {
                sheet.deleteRule(this.rule_idx);
                sheet.deleteRule(this.rule_idx);
                delete this.rule_idx;
            }
        },

        small: function() {
            return matchMedia(Foundation.media_queries.small).matches &&
                !matchMedia(Foundation.media_queries.medium).matches;
        },

        off: function() {}
    };
}(jQuery, window, window.document));
