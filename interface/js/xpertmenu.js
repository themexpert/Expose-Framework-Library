// JavaScript Document
	(function($){
		var _options = {
			childClass :".childcontent",
			_action:"click",
			_hasClass:"has-submenu",
			_hideDelay:300,
			_defaultIcon:0,
			_lavaTarget:'<li class="lavaLamp"></li>',
			_defaultLava:{},
			_parentInfo:"body"
		};
		var _globalTimer;
		var methods = {	
		init:function(_opt){
			
			$.extend(_options,_opt);
			$(_options.childClass).css('display','none');
			$(this).XpertMenu("bindEvents");
			$(this).find('ul').eq(0).append(_options._lavaTarget);
			var lavaTarget = $(this).find('ul .level-0').eq(_options._defaultIcon);
			var _lavaOffset = lavaTarget.offset();
			$('.lavaLamp').css({width:lavaTarget.width()+"px",height:lavaTarget.height()+"px",left:_lavaOffset.left+"px",top:_lavaOffset.top+"px",		});
			$.extend(_options._defaultLava,lavaTarget);
			
		},
		bindEvents:function(){
			//$(this)options.action
			var _this = this;
			$(this).find('li').bind(_options._action,function(e){
				e.stopPropagation();
				$(this).siblings('li').each(function(){
					$(this).find(_options.childClass).slideUp('300');//css('display','none');
				});
		
				if($(this).hasClass(_options._hasClass)){
					
					if($(this).hasClass('level-0')){
						console.info($(this));
						_this.XpertMenu("setDirection",e);
						_this.XpertMenu("showParentContent",e);
						_this.XpertMenu("changeLavaDirection",e);
					}	
					else
						_this.XpertMenu("showChildContent",e);
				}
			});
			
			$(this).find('li').bind({
				'mouseleave':function(e){
					var _chiThis = this;
					if($(this).hasClass(_options._hasClass)){						   
							if($(_chiThis).hasClass('level-0')){
								_this.XpertMenu("hideParentElement",e);
								_this.XpertMenu("defaultLavaPosition");
							}
							else
							_this.XpertMenu("hideChildElement",e);
						
					}
				},
				'click':function(){
					// Written For Upgrading
				}
			});
			
		},
		setDirection:function(_tarObj){
			var _currTar = $(_tarObj.currentTarget);
			var _childContent = _currTar.find(_options.childClass).eq(0);
			var _parentLimit = $(this);
			var _limitClass = {min:_parentLimit.offset().left,max:_parentLimit.offset().left+_parentLimit.width()};
			var _maxLimit = _currTar.offset().left + _childContent.width();
			var _diff = 0;
			if(_maxLimit > _limitClass.max){
				_diff = _maxLimit -  _limitClass.max;
				if(_diff > 0){
					_diff = _childContent.offset().left - _diff;
					_childContent.css('left',_diff+"px");
				}
			}
			
		},
		showParentContent:function(_tarObj){
			var _currTar = $(_tarObj.currentTarget);
			_currTar.find(_options.childClass).eq(0).slideDown('300');
		},
		showChildContent:function(_tarObj){
			var _currTar = $(_tarObj.currentTarget);
			var _childElement = _currTar.find(_options.childClass).eq(0);
			_childElement.css({'left':'0px',"top":"10px","opacity":"0","display":"block"});
			var _setLeft = _currTar.width();
			_childElement.animate({'left':_setLeft+"px","opacity":"1"},_options._hideDelay)
			//.slideDown('300');
		},
		hideParentElement:function(_tarObj){
			var _currTar = $(_tarObj.currentTarget);
			_currTar.find(_options.childClass).slideUp('300');
		},
		hideChildElement:function(_tarObj){
			var _currTar = $(_tarObj.currentTarget);
			var _childElement = _currTar.find(_options.childClass).eq(0);
			var _setLeft = _currTar.width();
			_childElement.animate({'left':"0px","opacity":"0"},_options._hideDelay,function(){$(this).css('display','none')})
			
		},
		changeLavaDirection:function(_tarObj){
			var lavaTarget =  $(_tarObj.currentTarget);
			var _currTarOffset = lavaTarget.offset();
			$('.lavaLamp').animate({width:lavaTarget.width()+"px",left:_currTarOffset.left+"px",top:_currTarOffset.top+"px"},_options._hideDelay);
			
		},
		defaultLavaPosition:function(){
			var lavaTarget =  _options._defaultLava;
			var _currTarOffset = lavaTarget.offset();
			$('.lavaLamp').animate({width:lavaTarget.width()+"px",left:_currTarOffset.left+"px",top:_currTarOffset.top+"px"},_options._hideDelay);
		}
		
	
	}
	
	$.fn.XpertMenu = function (method) {
		if (methods[method]) {
		  return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || !method) {
		  return methods.init.apply(this, arguments);
		}
		else {
		  $.error('Method ' + method + ' does not exist on jQuery.XpertMenu');
		}
	};
	
	})(jQuery);