/**
 * @package     Expose
 * @version     3.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

/**
 * Based on T3 GPL Framework
 */

var IMenu = new Class({
    initialize: function(options){
        this.options = $extend({
            animOn        : false,
            axis          : 'x',
            slideInterval : 0,
            slideSpeed    : 20
        }, options || {});

        window.addEvent('domready', this.start.bind(this));
        //window.addEvent('domready', function(){scrollTo(0, 1);} );
    },

    start: function() {
        this._backs = [];
        this._currenttoggle = null;
        this._last = null;

        this._links = $$('a');
        this._back = $('toolbar-back');
        this._close = $('toolbar-close');
        this._title = $('toolbar-title');
        this._boxes = $$('.toolbox');
        this._boxes2 = $$('.toolbox');
        if (!this._boxes || !this._boxes.length) return;
        this._overlay = $('ex-overlay');
        this._mainbox = $('ex-toolbar-main');
        if (this.options.animOn) {
            this._boxes.setStyle ('opacity', 0);
            this._overlay.setStyle ('opacity', 0);
            this._boxes.push ($('ex-toolbar-main'));
            this._boxes.push (this._overlay);
            this._fx = new Fx.Elements (this._boxes, {'onComplete':this.slidedone.bind(this)});
        }

        var top = (this._boxes && this._boxes.length)?this._boxes[0].getCoordinates().top:0;

        this._links.each(function(link) {
            if (link.href && link.hash && link.hash != "#") {
                link._box = $(link.hash.substr(1));
                if (!link._box || !link._box.hasClass ('toolbox')) return;
                link._box._link = link;
                link._h = top + link._box.getCoordinates().height;
                if (link.hasClass('ip-button')) {
                    link.addEvent ('click', function(e){
                        new Event(e).stop();
                        this.togglebox(link);
                        return false;
                    }.bind(this));
                } else {
                    link.addEvent ('click', function(e){
                        new Event(e).stop();
                        this.showbox(link, true);
                        return false;
                    }.bind(this));
                }
            }
        },this);

        if (this._back) {
            this._back.addEvent ('click', function(e){
                new Event(e).stop();
                this.back();
                return false;
            }.bind(this));
        }
        if (this._close) {
            this._close.addEvent ('click', function(e){
                new Event(e).stop();
                this.close();
                return false;
            }.bind(this));
        }

        this._overlay.addEvent ('click', function(e) {
            this.close();
            return false;
        }.bind(this));
    },

    slidedone: function () {
        if (this._currenttoggle == null) {
            //hide overlay
            this._overlay.setStyle ('display','none');
        }
    },

    togglebox: function (link) {
        if (this._currenttoggle == link) {
            this.close();
        }
        if (this._currenttoggle == null) {
            //show overlay
            this._overlay.setStyles ({'display':'block', 'height':$('ex-mobile').offsetHeight});
        }
        this.showbox (link, true);
        this._currenttoggle = link;
    },

    showbox: function (link, addback) {
        if (this.options.animOn) this.showbox2 (link, addback);
        else this.showbox1 (link, addback);
    },

    close: function () {
        if (this.options.animOn) this.close2 ();
        else this.close1 ();
    },

    showbox1: function (link, addback) {
        this._boxes2.setStyle ('display', 'none');
        link._box.setStyle ('display', 'block');
        this._mainbox.setStyle ('height', link._h);

        if (addback && this._last) {
            this._backs.push (this._last);
        }
        this._last = link;
        this.updatestatus(link);
    },

    close1: function () {
        this._boxes2.setStyle ('display', 'none');
        this._mainbox.setStyle ('height', 0);

        //hide overlay
        this._overlay.setStyle ('display','none');

        this._backs = [];
        this._currenttoggle = null;
        this._last = null;
    },

    showbox2: function (link, addback) {
        this._fx.stop();
        objs = {};
        for (i=0; i<this._boxes.length - 2; i++) {
            if (this._boxes[i] != link._box) {
                objs[i] = {'opacity': 0};
            } else {
                objs[i] = {'opacity': 1};
            }
        }

        objs[this._boxes.length - 2] = {'height': link._h};
        if (this._currenttoggle == null) {
            objs[this._boxes.length - 1] = {'opacity': 0.7};
        }
        this._fx.start (objs);

        if (addback && this._last) {
            this._backs.push (this._last);
        }
        this._last = link;
        this.updatestatus(link);
    },

    close2: function () {
        this._fx.stop();
        objs = {};
        for (i=0; i<this._boxes.length - 2; i++) {
            objs[i] = {'opacity': 0};
        }

        objs[this._boxes.length - 2] = {'height': 0};
        objs[this._boxes.length - 1] = {'opacity': 0};

        this._fx.start (objs);

        this._backs = [];
        this._currenttoggle = null;
        this._last = null;
    },

    updatestatus: function (link) {
        this._title.innerHTML = link.title;
        if ((lastlink = this._backs.getLast())) {
            this._back.innerHTML = lastlink.title;
            this._back.setStyle ('display', 'block');
        } else {
            this._back.innerHTML = '';
            this._back.setStyle ('display', 'none');
        }
    },

    back: function () {
        if ((link = this._backs.pop())){
            this.showbox (link);
        }
    }

})

new IMenu();