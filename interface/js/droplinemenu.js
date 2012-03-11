jQuery(document).ready(function($){

    var mainnav = $('#exdl-mainnav');
    var subnav = $('#ex-subnav');

    //subnav.filter('ul').eq(0).hide();

    mainnav.on('mouseenter', 'li', function(){
        //get the menu unique id
        var fullid = $(this).attr('id');
        var idnum = fullid.replace('exdl-mainnav','');
        var subnavid = '#exdl-subnav' + idnum;
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

    $('#ex-menu').on('mouseleave', function(){
        subnav.slideUp();
    });

});
