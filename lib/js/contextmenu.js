var menu = new menu_class();

function menu_class(){
    this.id = '#d_menu_';
    this.max_w = 155;
    this.max_h = 50;
    this.w = 0;
    this.h = 0;
    
    this.open = function(seting,button,or){
        
        seting         = $.extend({}, seting);
        button         = $.extend({}, button);
        or             = (!or) ? 'x' : or;
        
        menu.w = $(document).width();
        menu.h = $(document).height();
        
        elem = $(menu.id + seting.name);
        
        if(!elem.length){
            menu.create(seting,button,or)
        }
        else{
            elem.hide().css({left:seting.x,top:seting.y}).fadeIn('fast');
        }
    }
    
    this.create = function(seting,button,or){
        
        buttonHTML = '';
        
        $.each(button,function(name,fun){

			buttonHTML += '<li onclick="'+fun+'">'+name+'</li>';

			if(!fun.action){
				fun.action = function(){};
			}
	    });
        
        var in_c = [
            '<div class="d_menu '+or+' '+(((menu.w - seting.x) > menu.max_w) ? '' : 'wr')+'" id="d_menu_'+seting.name+'"><div><div>',
            '<ul class="ul">'+buttonHTML+'</ul>',
            '</div></div></div>'
	        ].join('');

            $(in_c).hide().appendTo('body').css({left:seting.x,top:seting.y}).fadeIn('fast');
            menu.get_cordinate(seting,or)
    }
    
    this.get_cordinate = function(seting,or){
        
        m_h = $(menu.id + seting.name).height();
        
        left = ((menu.w - seting.x) > menu.max_w) ? seting.x : seting.x - menu.max_w;
        top  = ((menu.h - seting.y) > m_h) ?  seting.y : seting.y - m_h;
        
        $(menu.id + seting.name).css({left:((or == 'y') ? left - (menu.max_w / 2):left),top:((or == 'y') ? (((menu.h - seting.y) > m_h) ? top +20:top-20): top)});
        
        if((menu.h - seting.y) < m_h) $(menu.id + seting.name).addClass('hb wn');
    }
    
}
$(document).ready(function() {
    $('body').live('mousedown',function(){
        $('.d_menu').fadeOut('fast',function(){
            $(this).remove();
        });
    });
});