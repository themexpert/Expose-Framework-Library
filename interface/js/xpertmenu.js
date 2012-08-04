/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
 
// JavaScript Document
jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend(jQuery.easing, {
    def: 'easeOutQuad',
    swing: function (x, t, b, c, d) {
        //alert(jQuery.easing.default);
        return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
    },
    easeInQuad: function (x, t, b, c, d) {
        return c * (t /= d) * t + b;
    },
    easeOutQuad: function (x, t, b, c, d) {
        return -c * (t /= d) * (t - 2) + b;
    },
    easeInOutQuad: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t + b;
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
    },
    easeInCubic: function (x, t, b, c, d) {
        return c * (t /= d) * t * t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    },
    easeInQuart: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t + b;
    },
    easeOutQuart: function (x, t, b, c, d) {
        return -c * ((t = t / d - 1) * t * t * t - 1) + b;
    },
    easeInOutQuart: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
        return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
    },
    easeInQuint: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t * t + b;
    },
    easeOutQuint: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
    },
    easeInOutQuint: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
    },
    easeInSine: function (x, t, b, c, d) {
        return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
    },
    easeOutSine: function (x, t, b, c, d) {
        return c * Math.sin(t / d * (Math.PI / 2)) + b;
    },
    easeInOutSine: function (x, t, b, c, d) {
        return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
    },
    easeInExpo: function (x, t, b, c, d) {
        return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
    },
    easeOutExpo: function (x, t, b, c, d) {
        return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
    },
    easeInOutExpo: function (x, t, b, c, d) {
        if (t == 0) return b;
        if (t == d) return b + c;
        if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
        return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
    },
    easeInCirc: function (x, t, b, c, d) {
        return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
    },
    easeOutCirc: function (x, t, b, c, d) {
        return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
    },
    easeInOutCirc: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
        return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
    },
    easeInElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * .3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    },
    easeOutElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * .3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    },
    easeInOutElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d / 2) == 2) return b + c;
        if (!p) p = d * (.3 * 1.5);
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
        return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
    },
    easeInBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * (t /= d) * t * ((s + 1) * t - s) + b;
    },
    easeOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
    },
    easeInOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        if ((t /= d / 2) < 1) return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
        return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
    },
    easeInBounce: function (x, t, b, c, d) {
        return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
    },
    easeOutBounce: function (x, t, b, c, d) {
        if ((t /= d) < (1 / 2.75)) {
            return c * (7.5625 * t * t) + b;
        } else if (t < (2 / 2.75)) {
            return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
        } else if (t < (2.5 / 2.75)) {
            return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
        } else {
            return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
        }
    },
    easeInOutBounce: function (x, t, b, c, d) {
        if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
        return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
    }
});
(function ($) {
    var _options = {
        childClass: ".childcontent",
        _action: "mouseenter",
        _hasClass: "has-submenu",
        _hideDelay: 300,
        _lavaActive: "active",
        _lavaClass: "fancy",
        _lavaTarget: 'div',
        _defaultLava: {},
        _easing: 'easeInOutQuad',
        _childLef: 100,
        _isFancy: true,
        _reduceLeft:30
    };
    var _globalTimer,_setLavaTimer,_globalTimerChild,_isChildShowing=false;
    var methods = {
        init: function (_opt) {
            $.extend(_options, _opt);
            $(_options.childClass).css('display', 'none');
            $(this).XpertMenu("bindEvents");
            if (_options._isFancy) {
                var _lavaHtml = "<" + _options._lavaTarget + " class='" + _options._lavaClass + "'><div class=\"fancy-inner\"></div></" + _options._lavaTarget + ">"
                $('body').append(_lavaHtml);
                var lavaTarget;
                $(this).find('li').each(function () {
                    if ($(this).hasClass('level-0') && $(this).hasClass(_options._lavaActive)) lavaTarget = $(this);
                });
                var _lavaOffset = lavaTarget.offset();
                $('.' + _options._lavaClass).css({
                    width: lavaTarget.width() + "px",
                    height: lavaTarget.height() + "px",
                    left: _lavaOffset.left + "px",
                    top: _lavaOffset.top + "px"
                });
                $.extend(_options._defaultLava, lavaTarget);
            }
        },
        bindEvents: function (){
            var _this = this;
            $(this).find('li').each(function(){
                if($(this).hasClass(_options._hasClass) && $(this).hasClass('level-0')){
                _this.XpertMenu("setDirection", $(this));
                }
            }); 
            
           $(this).find('li').bind("mouseenter", function (e) {
                var _tempList = $(this).parentsUntil("."+_options._hasClass);
                _tempList = _tempList[_tempList.length - 1];
                var _target = $(this);
                
                clearTimeout(_globalTimerChild);
                
                if(!$(_tempList).is(':animated') && !_target.find(_options.childClass).is(":visible")){
                
                _target.siblings('li').each(function (){
                    if ($(this).hasClass('level-0')) {
                        $(this).find(_options.childClass).slideUp(_options._hideDelay); //css('display','none');
                    }
                    else{
                        $(this).find(_options.childClass).fadeOut(_options._hideDelay);
                    }
                });
                
                if (_options._isFancy && _target.hasClass('level-0')){
                    _this.XpertMenu("changeLavaDirection", e);    
                } 
                
                if (_target.hasClass(_options._hasClass)) {
                        if (_target.hasClass('level-0')) {
                        _this.XpertMenu("showParentContent", e);
                        } else{
                        _isChildShowing = true;
                        _this.XpertMenu("showChildContent", e);
                        }   
                }
                
                }
            });
            
           $(this).find('li').bind({
                'mouseleave': function (e){
                    var _chiThis = this,_tarCurrent,_isParentBool = false;

                    clearTimeout(_globalTimerChild);

                    _globalTimerChild = setTimeout(function(){
                        _this.XpertMenu("defaultLavaPosition");
                    },_options._hideDelay*2);

                    if ($(this).hasClass(_options._hasClass)) {
                       
                       //if ($(_chiThis).hasClass('level-0'))
                        _isParentBool = true;
                        $(this).XpertMenu("hideElement",$(e.currentTarget).find(_options.childClass),_isParentBool);
                    }
                    
                },
                'click': function () {
                    // Written For Upgrading
                }
            });
        },
        showParentContent: function (_tarObj) { 
            var _currTar = $(_tarObj.currentTarget);
            _currTar.find(_options.childClass).eq(0).slideDown('300', _options._easing);
        },
        showChildContent: function (_tarObj) {
            var _currTar = $(_tarObj.currentTarget);
            var _childElement = _currTar.find(_options.childClass).eq(0);
            
            if(_childElement.css('display') != 'block'){
                _childElement.css({
                    'left': '0px',
                    "top": "5px",
                    "opacity": "0",
                    'z-index':'1000',
                    "display": "block"
                });
                
                var _setLeft = _options._childLef;
                var _marLeft = _childElement.offset().left + _childElement.width();
                var _maxLeft = $(this).offset().left + $(this).width();
                var _tempWidth = 0;
                
                if(_marLeft < _maxLeft){
                _tempWidth = _childElement.parent().width() - _options._reduceLeft;
                
                _childElement.animate({
                    'left': _tempWidth + "px",
                    "opacity": "1"
                }, _options._hideDelay, _options._easing);
                }else{
                _tempWidth = _childElement.width();
                _childElement.animate({
                    'left': -_tempWidth + "px",
                    "opacity": "1"
                }, _options._hideDelay, _options._easing);
                }
            
            }
        },
       hideElement:function(elem,_isParent){
            var _this = $(this);
            clearTimeout(_globalTimerChild);
            _globalTimerChild = setTimeout(function(){
            if(_isParent){
                elem.slideUp(_options._hideDelay);
                _this.XpertMenu("defaultLavaPosition"); 
            }else{
                elem.fadeOut(_options._hideDelay);
            }
            },_options._hideDelay*2);
                
        },
        changeLavaDirection: function (_tarObj) {
            clearTimeout(_setLavaTimer)
            var lavaTarget = $(_tarObj.currentTarget);
            var _currTarOffset = lavaTarget.offset();
            $('.' + _options._lavaClass + '').stop().animate({
                width: lavaTarget.width() + "px",
                left: _currTarOffset.left + "px",
                top: _currTarOffset.top + "px"
            }, _options._hideDelay);
        },
        defaultLavaPosition: function () {
            if (_options._isFancy) {
                _setLavaTimer = setTimeout(function(){
                    var lavaTarget = _options._defaultLava;
                    var _currTarOffset = lavaTarget.offset();
                    $('.' + _options._lavaClass + '').stop().animate({
                    width: lavaTarget.width() + "px",
                    left: _currTarOffset.left + "px",
                    top: _currTarOffset.top + "px"
                }, _options._hideDelay);
                },600);
            }
        },
        setDirection: function (_tarObj) {
            var _currTar = _tarObj;
            var _childContent = _currTar.find(_options.childClass).eq(0);
            var _parentLimit = $(this);
            
            var _limitClass = {
                min: _parentLimit.offset().left,
                max: _parentLimit.offset().left + _parentLimit.width()
            };
            var _maxLimit = _currTar.offset().left + _childContent.width();
            var _diff = 0;
            if (_maxLimit > _limitClass.max) {
                _diff = _maxLimit - _limitClass.max;
                if (_diff > 0) {
                    _diff = _childContent.offset().left - _diff;
                    _childContent.css('left', _diff + "px");
                }
            }
        }
    }
    $.fn.XpertMenu = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.XpertMenu');
        }
    };
})(jQuery);