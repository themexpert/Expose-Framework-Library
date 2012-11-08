var AccTabs = (function() {
		jQuery(function() {
			//for each DIV containing a set of tabs...
			jQuery(".module-tabs").each(
				function(t){
					var tabsDiv=jQuery(this);
					var list='';
					//for the h2 in each tab div
					jQuery(tabsDiv).find("h3").each(
						function(h){
							var tabId="tab" + t + "-" + h;
							jQuery(this).attr({"id": tabId, "tabindex": "-1"});
							list+='<li><a href="#' + tabId + '">' + jQuery(this).text() + '</a></li>';
						}
					);
					jQuery(tabsDiv).prepend('<ul class="nav nav-tabs">' + list + '</ul>').find(">div").addClass("tab").hide();
					jQuery(tabsDiv).find(".tab:first").show();
					jQuery(tabsDiv).find(".nav-tabs>li:first").toggleClass("active").find("a").prepend('<span>active Tab: </span>');
					//for each tabs menu link
					jQuery(tabsDiv).find(">ul>li>a").each(
						function(a){
							jQuery(this).click(
								function(e){
									e.preventDefault();
									jQuery(tabsDiv).find(">ul>li.active").removeClass("active").find(">a>span").remove();
									jQuery(this).blur();
									jQuery(tabsDiv).find(".tab:visible").hide();
									jQuery(tabsDiv).find(".tab").eq(a).show();
									//jQuery(this).prepend('<span>active Tab: </span>').parent().addClass("active");
									jQuery(this).prepend('<span></span>').parent().addClass("active");
									//set focus to target h2 inside the relevant, previously hidden tab -- NOTE: focus is being set AFTER the span is written to the tabs menu li anchor
									jQuery(jQuery(this).attr("href")).focus();
								}
							);
						}
					);
				}
			);
		});
	})();
