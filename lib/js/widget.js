var w_api = new widget_api();

function widget_api(){
    this.active_modal = '';
    this.login_val    = '';
    this.password_val = '';
    
    this.align_height = function(id){
        $(id).css({marginTop:'-'+($(id).height()/2)+'px'});
    }
    this.align_width = function(id){
        $(id).css({marginLeft:'-'+($(id).width()/2)+'px'});
    }
    
    this.info = function(title,text,option){
        
        var option = $.extend({
            zIndex: 900
        },option);
        
        var in_i = [
            '<div class="position_f top left width height" style="z-index: ',option.zIndex,';" onclick="$(this).fadeOut(300,function(){ $(this).remove() })">',
             '<div class="w_info_wrap">',
              '<div class="w_info_top"><h3>',title,'</h3></div>',
               '<div class="w_info_bot">',text,'</div>',
              '</div>',
            '</div>'
        ].join('');
        
        $(in_i).appendTo('body').hide().fadeIn(300);
        
        this.align_height('.w_info_wrap');
    }
    
    this.minimodal = function(data,option){
        
        var option = $.extend({
            zIndex: 900
        },option);
        
        var in_i = [
            '<div class="minimodal_bg" style="z-index:',option.zIndex,';" onclick="$(this).fadeOut(300,function(){ $(this).remove() })">',
             '<div class="minimodal">',
              '<div class="tl">',
               '<div class="tr">',
                '<div class="tc">',
     
                '</div>',
               '</div>',
              '</div>',
               '<div class="cl">',
               '<div class="cr">',
                '<div class="cc">',
                data,
                '</div>',
               '</div>',
              '</div>',
              '<div class="bl">',
               '<div class="br">',
                '<div class="bc">',
                '</div>',
               '</div>',
              '</div>',
             '</div>',
             '</div>'
        ].join('');
        
        $(in_i).appendTo('body').hide().fadeIn(300);
        
        this.align_height('.minimodal');
        this.align_width('.minimodal');
    }
    
    this.login = function(option){
        
        var option = $.extend({
            zIndex: 900,
            hide: true,
            callback:function(){
                
            }
        },option);

        var in_i = [
            '<div class="position_f top left width height w_login_bg" style="z-index: ',option.zIndex,';">', 
             '<div class="w_login_wrap">',
              '<div class="w_login_top"></div>',
               '<div class="w_login_cen">',
                '<input type="text" id="w_login_val" value="',w_api.login_val,'" />',
                '<input type="password" id="w_password_val" value="',w_api.password_val,'" />',
                '<div class="w_login_btn"></div>',
                '<div class="clear"></div>',
               '</div>',
              '<div class="w_login_bot"></div>',
             '</div>',
            '</div>'
        ].join('');
        
        $(in_i).appendTo('body').hide().fadeIn(300);
        
        this.align_height('.w_login_wrap');
        
        $('.w_login_btn').click(function(){
            
            w_api.login_val = $('#w_login_val').val();
            w_api.password_val = $('#w_password_val').val();
            
            option.callback(w_api.login_val,w_api.password_val);
            
            if(option.hide){    
                $('.w_login_bg').fadeOut(300,function(){
                    $(this).remove();
                });
            }
            
            return false;
        });
    }
    
    this.modal = function(title,data,arr){
       
       arr = $.extend({
           containment: 'body' 
       }, arr);
        
       var hash = Math.floor(Math.random() * (999 - 0) + 0);
       
       w_api.active_modal = '#modal_id_'+hash;
       
       var w_w = $(document).width();
       var w_h = $(document).height();
       
       var size = {
            maxHeight: 600,
			maxWidth: 700,
			minHeight: 140,
			minWidth: 200,
       };
       
       var def_w = (arr.width) ? (arr.width < size.minWidth ? size.minWidth : (arr.width > size.maxWidth ? size.maxWidth : arr.width)) : 500;
       var def_h = (arr.height) ? (arr.height < size.minHeight ? size.minHeight : (arr.height > size.maxHeight ? size.maxHeight : arr.height)) : 250;
       
       var scroll = [
             '<div class="scrollbarContent" id="scroll_',hash,'">',
             ' <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>',
             ' <div class="viewport">',
             '  <div class="overview">',
             '   ',data,
             '  </div>',
             ' </div>',
             '</div>'
       ].join('');
        
       var in_i = [
            '<div class="modal_bg" id="modal_id_',hash,'">',
             '<div class="modal_wins" style="width:',def_w,'px">',
              '<div class="top_win_l">',
               '<div class="top_win_r">',
                '<div class="top_win_c">',
                 '<div class="top_h">',
                 '<h3>',title,'</h3>',
                 '<ul class="btn_win_li">',
                  '<li class="slide"><span></span></li>',
                  '<li class="close"><span></span></li>',
                 '</ul>',
                 '</div>',
                '</div>',
               '</div>',
              '</div>',
  
              '<div class="cen_win_l">',
               '<div class="cen_win_r">',
                '<div class="data_win" style="height:',def_h-104,'px;">',
                scroll,
                '</div>',
               '</div>',
              '</div>',
  
              '<div class="bot_win_l">',
               '<div class="bot_win_r">',
                '<div class="bot_win_c">',
                '</div>',
               '</div>',
              '</div>',
             '</div>',
            '</div>'
        ].join('');
        
        $(in_i).appendTo('body').hide().fadeIn(300);
        
        var elem = $('#modal_id_'+hash);
        
        elem.find('.modal_wins').css({top:(w_h/2)-((def_h-60)/2),left:(w_w/2)-(def_w/2)})
        
        elem.find('.modal_wins').draggable({handle:'.top_h',containment: arr.containment});
        
        var x = elem.find('.data_win').width();
        var y = elem.find('.data_win').height();
        
        elem.find('.viewport').height(y).width(x);
        elem.find('.overview').width(x);
        
        $('#scroll_'+hash).tinyscrollbar();
        
        elem.find('li.close').click(function(){
            elem.fadeOut(300,function(){
                $(this).remove();
            })
        });
        
        elem.find('li.slide').click(function(){
            if(elem.find('.data_win').is(':visible')){
                elem.find('.data_win').slideUp(300)
            }
            else{
                elem.find('.data_win').slideDown(300);
            }
        })
    }
    
    this.code = function(data,option){
        
        var option = $.extend({
            zIndex: 600
        },option);
        
        var in_i = [
            '<div class="wcode_bg" style="z-index: ',option.zIndex,';"><div class="wcode">',
             '<div class="tl"><div class="tr"><div class="tc"><div class="close"></div></div></div></div>',
             '<div class="cl"><div class="cr"><div class="cc data_wcode">',
             
             '<div class="scrollbarContent" id="scroll_code">',
             ' <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>',
             ' <div class="viewport">',
             '  <div class="overview">',
             '   ',data,
             '  </div>',
             ' </div>',
             '</div>',

             '</div></div></div>',
             '<div class="bl"><div class="br"><div class="bc"></div></div></div>',
             '</div></div>'
        ].join('');
        
        var d_w = $(window).width();
        var d_h = $(window).height();
        
        $(in_i).appendTo('body').hide().fadeIn(300);
        
        $('.wcode').css({width:(d_w-400),height:(d_h - 100),marginLeft:'-'+((d_w-400)/2)+'px',marginTop:'-'+((d_h-100)/2)+'px'});
        $('.data_wcode').height((d_h - 100)-34);
        
        var x = $('.data_wcode').width();
        var y = $('.data_wcode').height();
        
        $('.data_wcode').find('.viewport').height(y).width(x);
        
        $('#scroll_code').tinyscrollbar();
        
        $('.wcode').find('.close').click(function(){
            $('.wcode_bg').fadeOut(300,function(){
                $('.wcode').remove();
                block.source = false;
            });
        })
    }
}