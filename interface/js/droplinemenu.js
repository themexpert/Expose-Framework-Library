jQuery(document).ready(function($){

    var mainnav = $('#mainnav');
    var subnav = $('#subnav');

    //subnav.filter('ul').eq(0).hide();

    mainnav.on('mouseenter', 'li', function(){
        //get the menu unique id
        var fullid = $(this).attr('id');
        var idnum = fullid.replace('mainnav','');
        var subnavid = '#subnav' + idnum;
        var klass = subnav.find(subnavid).find('li').attr('class');

        subnav
            .find(subnavid)
                .fadeIn(300)
                    .siblings('ul').hide();

        if(klass == 'empty'){
            subnav.slideUp();
        } else{
            subnav.slideDown();
        }

    });

    $('#menu').on('mouseleave', function(){
        subnav.slideUp();
    });

});
