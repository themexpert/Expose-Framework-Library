// JavaScript Document
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

(function ($) {

  var _options = {
    action: 'click',
    transition: 'slide',
	dir:'left',
	defaultIcon:3
  },_mouseTimer,_action;

  var methods = {
    init: function (_opt) {
      $.extend(_options, _opt);
	  $(this).find('ul').eq(0).append('<li class="lava_back"></li>')
	  $(this).XpertMenu('animate_icon',$('.level-0').eq(Number(_options.defaultIcon)));
	  $('.childcontent').css('display','none');
      $(this).XpertMenu('bindEvents')
	  _action = _options.action;
    },

    bindEvents: function (){
		
		var _targetli = $(this).find('.mega');
		var _childContainer = $(this).find('.childcontent');
		
      	_targetli.bind(
        _options.action,function(e){
		  var _this = $(this)
		  $(this).siblings('li').each(function(){
		  $(this).find('.childcontent').slideUp(_options.delay);												 		  });
		  if( _options.action=='mouseenter' && $(this).hasClass('level-0')){
			$(this).XpertMenu('animate_icon',_this);	
		  }
          if ($(this).hasClass('has-submenu')){
            clearTimeout(_mouseTimer);
			if($(this).attr('dir') == undefined)
				$(this).XpertMenu('checkDirection',e);
			else
				$(this).XpertMenu('setDirection',e);
          }
	  	});
		
		_targetli.bind({
		'mouseleave':function(e){
			var _this = $(this);
			_mouseTimer = setTimeout(function(){
			if( _options.action=='mouseenter' && _this.hasClass('level-0')){
				$(this).XpertMenu('animate_icon',$('.level-0').eq(Number(_options.defaultIcon)));	
			}								  
			$(this).XpertMenu('hideContent',e);								 
			},_options.hideDelay);
			
		}});
		
		_childContainer.bind({
		'mouseenter':function(){
			if($(this).css('display')=='block'){
				clearTimeout(_mouseTimer);
			}
		},
		
		'mouseleave':function(e){
			_mouseTimer = setTimeout(function(){
				$(this).XpertMenu('hideContent',e);
				$(this).XpertMenu('animate_icon',$('.level-0').eq(Number(_options.defaultIcon)));
			},_options.hideDelay);
		}
		})
    },animate_icon:function(_this){
		_lioffset = _this.offset();
		$('.lava_back').animate({'left':_lioffset.left-150+'px','width':_this.width()+'px'},300,_options.easing);
	},
	setDirection:function(e){
		var _this = $(this);
		var _childthis = $(this).find('.childcontent').eq(0);
		var _dir = _options.dir||_this.attr('dir');
		var _defaultIcon = 3;
		
		if(_dir == 'left'){
			_childthis.css({'left':'10px'})
			
		}else if(_dir == 'right'){
			var _tempDir = _this.width() + _childthis.width() - 25;
			_tempDir = _tempDir * -1;
			console.info(_tempDir);
			_childthis.css({'left':_tempDir+'px'})
		}
		_childthis.slideDown('300');
	},
    showContent: function(){
    	console.info('click');
    },

    hideContent: function(e){
		var _hideElement = e.currentTarget;
		if($(_hideElement).hasClass('mega')){
			if($(_hideElement).find('.childcontent').length > 0){
				$(_hideElement).find('.childcontent').slideUp(_options.delay);
			}
		}else if($(_hideElement).hasClass('childcontent')){
			$(_hideElement).slideUp(_options.delay);
		}
		 
		
    },

    checkDirection: function (e) {
	  var _chkTar = e.currentTarget;
      var _parentinfo = {
        'width': $(_options.parent).width()
      };
      $.extend(_parentinfo, $(_options.parent).offset());
      $.extend(_parentinfo,{
        'limit': _parentinfo.width + _parentinfo.left
      });
	  
	  if(_options.transition == 'fade')
      $(_chkTar).find('.childcontent').eq(0).fadeIn('300'); //css('display','block');
	  else if(_options.transition == 'slide'){
	  $(_chkTar).find('.childcontent').eq(0).slideDown('300',_options.easing); //css('display','block');
	  }
      var _childinfo = {
        'width': $(_chkTar).find('.childcontent').width()
      };
      $.extend(_childinfo, $(_chkTar).find('.childcontent').offset());
      $.extend(_childinfo, {
        'limit': _childinfo.width + _childinfo.left
      });
      var _offsetDiff = _childinfo.limit - _parentinfo.limit;
      if (_offsetDiff > 0) {
        $(_chkTar).find('.childcontent').css('left', _offsetDiff / 2 + 'px')
      }
    },
  };
  $.fn.XpertMenu = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    }
    else {
      $.error('Method ' + method + ' does not exist on jQuery.Main Menu');
    }
  };
})(jQuery);