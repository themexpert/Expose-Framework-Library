(function (f) {

    f.fn.equalHeight = function (a) {
        var c = 0,
            b = [];
        this.each(function () {
            var c = a ? f(this).find(a + ":first") : f(this);
            b.push(c);
            c.css("min-height", "")
        });
        this.each(function () {
            c = Math.max(c, f(this).outerHeight())
        });
        return this.each(function (a) {
            var g = f(this),
                a = b[a],
                g = a.height() + (c - g.outerHeight());
            a.css("min-height", g + "px")
        })
    };

})(jQuery);