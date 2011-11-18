/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        expose.php
 **/

jQuery.noConflict();

jQuery(window).ready(function($){
    
   $.blockUI({
        message: '<h1>Please Wait...</h1>',
        //timeout: 2000
   });

    //create basic html skeliton for admin
    $('div.m form.form-validate').append(
        '<div id="expose-wrapper" class="clearfix"><div class="expose-details"></div><div class="expose-tab-wrapper clearfix"><div class="expose-tab"><ul></ul></div><div class="expose-tab-content"></div></div></div>'
    );

    //loop through all h3 element and convert them to expose tab
    $('h3.title').each(function(){
        //lets cache some vaue
        var obj = $(this);
        var title = $(this).text(); // this title will use as class name

        $('<li><span>'+ title +'</span></li>').appendTo('.expose-tab ul').addClass(title);
        obj.next().removeClass().removeAttr('style').addClass(function(){
            if(title == 'assignments'){
                $(this).empty();
            }
            return 'panel ' + title + ' clearfix';
        }).appendTo('.expose-tab-content');
        //$(this).remove();
    });
    
    //finally remove the parent div of all accordion
    $('.width-40').remove();

    //lets insert template info input boxes to expose-details.
    $('.width-60:first').appendTo('.expose-details').removeClass('width-60 fltlft').addClass('expose-inner');
    
    //lets take the menu assingments div and append it to its own div under tab
    $('.width-60 .adminform').appendTo('div.assignments');

    //remove old parent div
    $('.width-60').remove();

    //lets remove template details label
    $('.expose-details label:last').remove();

    //now move the template description to expose-wrapper
    $('span.mod-desc').appendTo('#expose-wrapper');

    $('.remove-lbl').each(function(){
        //cache its contents
        var content = $(this);
        //remove the immediate before label;
        $(this).prev().remove();

        $(this).parent().replaceWith(content);
        //take its previous li node
        var prevli = $(this).prev();
        //now append to it
        $(this).appendTo(prevli);
    });

    $('<div class="clear"></div>').appendTo('.panel li');
    $('<span class="tips"></span>').appendTo('.expose-tab-wrapper label');

    $('select,input,.handle,textarea,radio').change(function(){
        $(this).parent().parent().addClass('highlight');
    });

    var toggle = $(".toggle").exposeToggle();


    $('.overlay-trigger').overlay({
        mask: {
		color: '#000',
		loadSpeed: 200,
		opacity: 0.5
	}
    });
         
    /*******************
    * initiate tab class
    *******************/
    $('.expose-tab ul').tabs('.expose-tab-content > .panel',{
        effect: 'fade', fadeOutSpeed: 200
    });

    $('.mod-tabs ul').tabs('.mod-inputs > .inputs',{
        effect: 'fade'
    });

    /****************************
    *  Live Google Font preview
    ****************************/

    var previewText = 'Grumpy wizards make toxic brew for the evil Queen and Jack.';
    $('div.typography .fonts-list').each(function(){
        $(this).parent().append('<div class="font-preview"><span>Live Preview</span>'+previewText+'</div>');
    });
    
    $('.gfonts').change(function(){
        var fontName = "";
        var fontUrl = '';
        var pos = '';
        fontUrl += $(this).val() + "";
        pos = fontUrl.search(':');
        //alert(pos);
        if(pos < 0){
            fontName = fontUrl.replace('+', ' ');
        }else{
            fontName = fontUrl.substr(0,pos);
            fontName = fontName.replace('+', ' ');
        }
        var link = ("<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=" + fontUrl + "' media='screen' />");

        $("head").append(link);
        $(this).parent().parent().find('.font-preview').css("font-family", fontName);

    });
    $.unblockUI();

});
