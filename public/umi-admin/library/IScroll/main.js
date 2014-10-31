/* Extends IScroll */
define(['iscroll'], function() {
    'use strict';
    IScroll.prototype.scrollTo = function(x, y, time, easing) {
        easing = easing || IScroll.utils.ease.circular;

        this.isInTransition = this.options.useTransition && time > 0;

        if (!time || (this.options.useTransition && easing.style)) {
            this._transitionTimingFunction(easing.style);
            this._transitionTime(time);
            this._translate(x, y);
        } else {
            this._animate(x, y, time, easing.fn);
        }

        if (this.options.probeType == 3) {
            this._execEvent('scroll');
        }
    };
});
