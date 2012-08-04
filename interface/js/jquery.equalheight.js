/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

(function (f) {

    f.fn.equalHeight = function (a) {
        var c = 0,
            b = [];
        this.each(function () {
            var c = a ? f(this).find(a) : f(this);
            b.push(c);
            //c.css("min-height", "")
        });

        //calculate max height
        this.each(function () {
            c = Math.max(c, f(this).outerHeight())
        });

        return this.each(function (a) {
            var total = b[a].length;
            if(total > 1) return;

            var a = b[a],
                g = f(this),
                g = a.height() + ( c - g.outerHeight() );

            a.css("min-height", g + "px")
        })
    };

})(jQuery);