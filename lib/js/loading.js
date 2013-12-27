function buld_loading(){
    
    var loadingHTML = [
                '<div class="buld_loading_bg" onclick="$(this).fadeOut(400)">',
                  '<div class="buld_loading_st"></div>',
                    '<div class="buld_loading_view_logo"></div>',
                 '</div>',
    ].join('');

    $(loadingHTML).prependTo('body');
    
    var pic = new Image();
    pic.src = 'lib/images/loading/load_logo_50.png';
    
    pic.onload = function(){
        $('.buld_loading_st').fadeOut(100);
        $('.buld_loading_view_logo').fadeIn(300,function(){
            
            $(document).ready(function(){
                $('.buld_loading_bg').fadeOut(400);
            })
            
        });
    }
        
}

buld_loading();