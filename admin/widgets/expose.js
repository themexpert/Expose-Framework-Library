/**
 *
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 *
 **/

jQuery.noConflict();

jQuery(document).ready(function($){

    //create basic html skeleton for admin
    var skeleton = '<div id="expose-wrapper" class="clearfix"><div class="expose-tab-wrapper clearfix"><div class="expose-tab"><ul></ul></div><div class="expose-tab-content"></div></div></div>';

    $('#element-box form.form-validate').append(skeleton);

    //Mission Control admin template bug fix
    $('#mc-component form.form-validate').append(skeleton);


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
    $('.width-60:first').appendTo('.template-name').removeClass('width-60 fltlft').addClass('inner');

    //lets take the menu assingments div and append it to its own div under tab
    $('.width-60 .adminform').appendTo('div.assignments');

    //remove old parent div
    $('.width-60').remove();

    //lets remove template details
    $('.template-name ul li').each(function(index,val){
        // Only show: Template name and Language dropdown
        if(index ==0 || index == 2){
            $(this).show();
        }else {
            $(this).hide();
        }
    });
    $('.template-name legend').remove();
    $('.template-name label:last').remove();
    $('.mod-desc').remove();

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

    /*$('#expose-wrapper').find('select,input,.handle,textarea,radio').change(function(){
        $(this).parent().parent().addClass('highlight');
    });*/



    $(".toggle").exposeToggle();
    $('.toggleContainer').bind('iPhoneDragEnd',function(){
        $(this).parent().find('input').each(function(){
            ($(this).attr('value') == 0) ? $(this).attr('value',1) : $(this).attr('value',0);
        });
    });


    $("p[rel]").overlay({
        mask: {
		color: '#000',
		loadSpeed: 200,
		opacity: 0.5

	    }
    });

    //Beautify select dorpdown.
    $("select").uniform();

    $('body').css('display','block');

    /********************************************
     * detect active tab and set it on page load
     *******************************************/
    var tabs =  $('.expose-tab li');
    tabs.click(function(){
        var klass = $(this).attr('class');
        klass = klass.replace(' current','');
        $.cookie('active_tab',klass, { expires: 30 });
    });

    function getCurrentIndex(){
         $('.expose-tab li').each(function(i){
            //get the current class name
            var klass = $(this).attr('class');
            //get class name form cookie
            if($.cookie('active_tab')){
                var activeClass = $.cookie('active_tab');
            }else{
                var activeClass = 'overview';
            }

            //check the match
            if(activeClass == klass){
                val = i;
            }
        });
        return val;
    }

     /*******************
    * initiate tab class
    *******************/
    $('.expose-tab ul').tabs('.expose-tab-content > .panel',{
        effect: 'fade',
        fadeOutSpeed: 200,
        initialIndex: getCurrentIndex()
    });

    $('.mod-tabs ul').tabs('.mod-inputs > .inputs',{
        effect: 'fade'
    });

    /****************************
    *  Layout Selector
    ****************************/
    $('#layout-selector span').click(function(){
        var el = $(this);
        jQuery('#layout-selector span.active').removeClass('active');

        var value = el.attr('class');
        $('#jform_params_layouts').val(value);

        el.addClass('active');

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
        var ids = $(this).attr('id');
        fontUrl += $(this).val() + "";
        pos = fontUrl.search(':');
        //alert(pos);
        if(pos < 0){
            fontName = fontUrl.replace('+', ' ');
        }else{
            fontName = fontUrl.substr(0,pos);
            fontName = fontName.replace('+', ' ');
        }
        $.cookie(ids,fontName);

        if($.cookie(ids)){
            var link = ("<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=" + fontUrl + "' media='screen' />");
            $("head").append(link);
        $(this).parent().parent().parent().find('.font-preview').css("font-family", fontName);
        }



    });

//    Hide next li baed on toggle click event
    $('.hidenext .toggleContainer').each(function(){
        //get the parent node
        var parent = $(this).parent().parent();
        //get the input value
        var inputVal = parent.find('input.toggle').attr('value');
        if(inputVal == 0){
            parent.next().css('display','none');
        }else{
            parent.next().css('display','block');
        }
    });

    $('.hidenext .toggleContainer').click(function(){
        //get the parent node
        var parent = $(this).parent().parent();
        //get the input value
        var inputVal = parent.find('input.toggle').attr('value');
        if(inputVal == 0){
            parent.next().css('display','none');
        }else{
            parent.next().css('display','block');
        }
    });

    //remove numbering tab for sidebar position
    $('#sidebar_a').prev('ul').hide();
    $('#sidebar_b').prev('ul').hide();

});
