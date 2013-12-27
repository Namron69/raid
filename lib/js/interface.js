var in_ce = new in_ce_manager();

function in_ce_manager(){
    
    var in_ce_id = '.interface';
    var in_ce_conteiner = '.i_content';
    var i_height = 0;
    
    this.init = function(){
        var ini = $(in_ce_id);
        var winH = $(window).height();
        
        ini.find('.conteiner').height(winH-30);
        ini.find('.i_content').height(winH-70);
        
        in_ce.i_height = winH-70;
    }
    
    this.close = function(){
        $(in_ce_id).fadeOut(300);
    }
    
    this.open = function(url,arr){
        
        var arr = $.extend({},arr);
        
        if(arr.data){
            $(in_ce_conteiner).html(arr.data);
            $(in_ce_id).fadeIn(300);
            in_ce.init();
            in_ce.btn(arr);
        }
        else{
            conect({
                url:url,
                post:arr.post,
                action:function(data){
                    $(in_ce_conteiner).html(arr.scroll ? scroll(data,'in_ce_sc') : data);
                    $(in_ce_id).fadeIn(300);
                    in_ce.init();
                    in_ce.btn(arr);
                    if(arr.scroll) initScroll('in_ce_sc');
                }
            });
        }
    }
    
    this.btn = function(arr){
        var result = '';
        
        if(arr.name) result += '<li>'+arr.name+'</li>';
        
        if(arr.btn){
        
            $.each(arr.btn,function(name,fun){
                result += '<li onclick="'+fun+'">'+name+'</li>';
            })
        }
        
        $(in_ce_id).find('.i_name').html(result);
    }
}