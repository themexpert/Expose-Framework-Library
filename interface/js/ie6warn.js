var sevenUp=function(){var osSupportsUpgrade=/(Windows NT 5.1|Windows NT 6.0|Windows NT 6.1|)/i.test(navigator.userAgent);var options={enableClosing:true,enableQuitBuggingMe:true,overlayColor:"#000000",lightboxColor:"#ffffff",borderColor:"#6699ff",downloadLink:osSupportsUpgrade?"http://www.microsoft.com/windows/internet-explorer":"http://getfirefox.com",overrideLightbox:false,lightboxHTML:null,showToAllBrowsers:false,usePlugin:false};function mergeInOptions(newOptions){if(newOptions){for(var i in options){if(newOptions[i]!==undefined){options[i]=newOptions[i];}}}}
function isCookieSet(){if(document.cookie.length>0){var i=document.cookie.indexOf("sevenup=");return(i!=-1);}
return false;}
return{overlayCSS:function(){return"display: block; position: absolute; top: 0%; left: 0%;"+"width: 100%; height: 100%; background-color: "+options.overlayColor+"; "+"filter: alpha(opacity: 80); z-index:1001;";},lightboxCSS:function(){return"display: block; position: absolute; top: 25%; left: 25%; width: 50%; "+"padding: 16px; border: 8px solid "+options.borderColor+"; "+"background-color:"+options.lightboxColor+"; "+"z-index:1002; overflow: hidden;";},lightboxContents:function(){var html=options.lightboxHTML;if(!html){html="<div style='width: 100%; height: 95%'>"+"<h2 style='text-align: center;'>Your web browser is outdated and unsupported</h2>"+"<div class='upgrade_msg' style='text-align: center;'>"+"You can easily upgrade to the latest version at<br> "+"<a style='color: #0000EE' href='"+options.downloadLink+"'>"+
options.downloadLink+"</a>"+"</div>"+"<h3 style='margin-top: 40px'>Why should I upgrade?</h3>"+"<ul>"+"<li><b>Websites load faster</b>, often double the speed of this older version</li>"+"<li><b>Websites look better</b>, so you see sites they way they were intended</li>"+"<li><b>Tabs</b> let you view multiple sites in one window</li>"+"<li><b>Safer browsing</b> with phishing protection</li>"+"</ul>"+"</div>";if(options.enableClosing){html+="<div style='font-size: 11px; text-align: right;'>";html+=options.enableQuitBuggingMe?("<a href='#' onclick='sevenUp.quitBuggingMe();' "+"style='color: #0000EE'>"+"Quit bugging me"+"</a>"):("<a href='#' onclick='sevenUp.close();' "+"style='color: #0000EE'>"+"close"+"</a>");html+="</div>";}}
return html;},test:function(newOptions,callback){mergeInOptions(newOptions);if(!isCookieSet()){var layerHTML="<div id='sevenUpCallbackSignal'></div>";if(options.overrideLightbox){layerHTML+=options.lightboxHTML;}else{layerHTML+="<div id='sevenUpOverlay' style='"+overlayCSS()+"'>"+"</div>"+"<div id='sevenUpLightbox' style='"+lightboxCSS()+"'>"+
lightboxContents()+"</div>";}
if(options.showToAllBrowsers!==true){layerHTML="<!--[if lt IE 7]>"+layerHTML+"<![endif]-->";}
var layer=document.createElement('div');layer.innerHTML=layerHTML;document.body.appendChild(layer);if(callback&&document.getElementById('sevenUpCallbackSignal')){callback(options);}}},quitBuggingMe:function(){var exp=new Date();exp.setTime(exp.getTime()+(7*24*3600000));document.cookie="sevenup=dontbugme; expires="+exp.toUTCString();this.close();},close:function(){var overlay=document.getElementById('sevenUpOverlay');var lightbox=document.getElementById('sevenUpLightbox');if(overlay){overlay.style.display='none';}
if(lightbox){lightbox.style.display='none';}},plugin:{}};}();
if(sevenUp){sevenUp.plugin.black={test:function(newOptions,callback){newOptions.overrideLightbox=true;newOptions.lightboxHTML=" \
      <div id='sevenUpLightbox' style='display:block;position:absolute;top:25%;text-align:center;z-index:1002;overflow:hidden;width:100%'> \
        <div style='width:550px;margin:0px auto;text-align:left;'> \
          <div style='background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/curve-top.gif);font-size:1px;height:18px;width:550px;'></div> \
          <div style='background:#1a1a1a;color:#999;font: 12px Arial, Helvetica, sans-serif;position:relative;text-align:center;width:550px;'> \
            <div style='background:transparent url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/close.gif);height:26px;position:absolute;right:6px;top:-10px;width:26px;'> \
              <a href='#' onclick='sevenUp.close()' style='display:block;height:26px;text-indent:-9999px;width:26px;'>Close</a> \
            </div> \
            <h1 style='background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/heading-main.gif) 0 18px no-repeat;font-size:1px;height:43px;margin:0 auto;;text-indent:-9999px;width:479px;'>Your web browser is updated</h1> \
            <p style='font-size:14px;margin:8px 0 11px;'>You can easily upgrade to the latest version</p> \
            <a href='http://www.microsoft.com/windows/internet-explorer'><img src='http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/IE.jpg' alt='Internet Explorer 8' style='border:0;'/></a> \
            <p style='margin:2px 0 22px;'><a href='http://www.microsoft.com/windows/internet-explorer' style='color:#999;text-decoration:none;'>Internet Explorer 8</a></p> \
            <div class='whyUpgrade' style='float:left;text-align:left;padding-left:35px;width:270px;'> \
              <h3 style='background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/heading-upgrade.gif);font-size:1px;height:13px;margin:0;text-indent:-9999px;width:146px;'>Why should I upgrade?</h3> \
              <dl style='line-height: 1.4;margin:7px 0 0 2px'> \
                <dt style='color:#e6e6e6'>Web sites load faster</dt> \
                <dd style='font-size:11px;margin-left:20px;'>often double the speed of this older version.</dd> \
                <dt style='color:#e6e6e6'>Web sites render correctly</dt> \
                <dd style='font-size:11px;margin-left:20px;'>with more web standards compliance.</dd> \
                <dt style='color:#e6e6e6'>Tabs Interface</dt> \
                <dd style='font-size:11px;margin-left:20px;'>lets you view multiple sites in one window.</dd> \
                <dt style='color:#e6e6e6'>Safer browsing</dt> \
                <dd style='font-size:11px;margin-left:20px;'>with better security and phishing protection.</dd> \
                <dt style='color:#e6e6e6'>Convenient Printing</dt> \
                <dd style='font-size:11px;margin-left:20px;'>with fit-to-page capability.</dd> \
              </dl> \
            </div> \
            <div class='otherBrowsers' style='float:left;font-size:14px;text-align:left;width:220px;margin-left:20px'> \
              <h3 style='background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/heading-browsers.gif);font-size:1px;height:13px;margin:0;text-indent:-9999px;width:152px;'>Explore other browsers</h3> \
              <ul style='list-style:none;margin:0;padding:9px 0 0 0'> \
            <li style='height:39px;background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/Chrome.jpg) no-repeat;'><a href='http://www.google.com/chrome' style='color:#e6e6e6;display:block;padding:4px 0 8px 44px;text-decoration:none;width:150px;'>Google Chrome</a></li> \
            <li style='height:39px;background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/Firefox.jpg) no-repeat;'><a href='http://getfirefox.com' style='color:#e6e6e6;display:block;padding:4px 0 8px 44px;text-decoration:none;width:140px;'>Mozilla Firefox</a></li> \
            <li style='height:39px;background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/Opera.jpg) no-repeat;'><a href='http://www.opera.com/download/' style='color:#e6e6e6;display:block;padding:4px 0 8px 44px;text-decoration:none;width:140px;'>Opera</a></li> \
            <li style='height:39px;background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/Safari.jpg) no-repeat;'><a href='http://www.apple.com/safari/download/' style='color:#e6e6e6;display:block;padding:4px 0 8px 44px;text-decoration:none;width:140px;'>Apple Safari</a></li> \
              </ul> \
            </div> \
            <div style='clear:both;'><a href='#' style='bottom:-10px;color:#e6e6e6;font-size:14px;position:absolute;right:14px;text-decoration:none;' ";if(newOptions.enableQuitBuggingMe===false){newOptions.lightboxHTML+="onclick='sevenUp.close()'>close";}else{newOptions.lightboxHTML+="onclick='sevenUp.quitBuggingMe()'>quit bugging me";}
newOptions.lightboxHTML+="</a></div> \
          </div> \
          <div style='background:url(http://dl.getdropbox.com/u/48374/sevenup/plugins/black/images/curve-bottom.gif);font-size:1px;height:18px;width:550px;'></div> \
        </div> \
      </div>";sevenUp.test(newOptions,callback);}};}