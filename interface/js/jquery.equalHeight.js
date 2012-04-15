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

            var a = b[a];
            a.css("min-height", c + "px")
        })
    };

})(jQuery);