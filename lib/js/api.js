//=========================================//
// LOADING
//=========================================//

var api_seting = {
    my_modal: false,
    se_time: 0,
	is_loading_var:'#loading_info',
	is_loading_info:'Загрузка',
	error_var:'#error_conteiner',
	error_num:error_num = 0,
	error_speed:400,
	error_nav:'.info_nav',
	big_info_var:'.big_ingo',
	big_in_data:'#big_info_data',
	big_in_open:'.fast_start',
	big_in_close:'.close_big_info',
	big_in_speed:500,
	big_in_out_speed:300,
	big_in_fade:'slow',
	modal_win_var:'#modal_win',
	modal_win:'.modal_win',
	modal_win_speed:300,
	tabs_li_var:'.tabs_li_hash_',
	tabs_content_var:'#tabs_',
	tabs_hide_class:'.tabs_hash_',
	tabs_active_class:'active',
	sesion:'Сесия истекла, обновите страницу.',
    group:'Нет прав доступа к модулю!',
	show_operator:false
}

function is_loading(args){
    
    var in_i = $('#loading_info');

    if(!in_i.length){
        
        var in_i = [
            '<div id="loading_info"></div>'
	        ].join('');
        $(in_i).appendTo('body');
        
    }
    
    var defaults = { info:"", callback:null };
    args         = $.extend(defaults, args);
    
    var is_info     = (args.info) ? args.info : api_seting.is_loading_info,
        elem        = $(api_seting.is_loading_var),
        status      = elem.css('display');
        
    if(status == 'none')
    elem.fadeIn(100);
    else{
        close_is_loading(function(){
            is_loading(is_info);
        });
    }
}
function close_is_loading(callback){
    $(api_seting.is_loading_var).fadeOut(100);
}

//=========================================//
// ERROR
//=========================================//

function show_error(error,callback){
    
    if(!$('.show_error_layer_bg').length){
        var er = [
            '<div class="show_error_layer_bg"></div>',
            '<div class="show_error_text"><div onclick="hide_error()"></div></div>'
	    ].join('');
        
        $(er).appendTo('body');	
    }
    
    var elem = $('.show_error_layer_bg');
    
    go = function(){
        elem.fadeIn(300,function(){
            $('.show_error_text').fadeIn(100).find('div').text(error);
        }).animate({opacity:0.5},400);
    }
    
    gets = function(){
        elem.animate({opacity:1},200).animate({opacity:0.5},400);
        $('.show_error_text div').text(error);
    }
    
    if(elem.is((':visible'))) gets();
    else go();
    
    if(callback) callback();

}
function hide_error(){
    $('.show_error_text').fadeOut(200);
    $('.show_error_layer_bg').fadeOut(400);
}

//=========================================//
// OPERATOR
//=========================================//

function strpos( haystack, needle){

	var i = haystack.indexOf( needle );
	return i >= 0 ? true : false;
}


function operator(result,ej){
    var status = false;
    
    if(api_seting.show_operator){
        is_modal('Результат!',result);
        api_seting.show_operator = false;
        return false;
    }

    if(strpos(result, '<b>Parse error</b>:') || strpos(result, '<b>Fatal error</b>:') || strpos(result, '<b>Warning</b>:') || strpos(result, '<b>Catchable fatal error</b>:')){
        
        found = result.match(/<b>(.*?)<\/b>:(.*?)<br \/>/ig);
        
        result = found[0];
        
        is_modal('Ошибка!',result);
        return false;
    }
    
    if(strpos(result, 'seting = {')){
        
        found = result.match(/seting = \{(.*?)\}/g);
        
        result = found[0];

        status = true;
        result = eval(result);
        
        if(result.info)   show_error(result.info);
        if(result.sesion) show_error(api_seting.sesion);
        if(result.group)  show_error(api_seting.group);
        if(result.sql)    is_modal('Ошибка MYSQL!',result.sql);
    }

    if(ej && !status){
        if(result.status)   return true; 
        else is_modal('Ошибка!',result);
    }
    else{
        if(!result.info && !result.sql && !result.sesion && !result.group) return true;
    }
    
}

//=========================================//
// MODAL_WINDOW
//=========================================//
function buld_modal(params){
    var elem = $(api_seting.modal_win_var);
    var hashw = Math.floor(Math.random() * (999 - 0) + 0);
    
    /*
    if(elem.length){
        close_modal(function(){
            buld_modal(params);
        }); 

	    return false;
    }
    */
    
    var buttonHTML = '';
		$.each(params.buttons,function(name,obj){

			buttonHTML += '<div class="win_button"><div><div><div>'+name+'</div></div></div></div>';

			if(!obj.action){
				obj.action = function(){};
			}
	});
    
    var promt = '';

    if(params.promt || params.promt == ''){
        promt = '<div class="input"><div><input type="text" id="promt_modal" value="'+params.promt+'" '+(!params.reinput ? 'class="promt_modal_input"' : '')+'/></div></div>';
    }
    
    if(api_seting.my_modal){
        var markup = api_seting.my_modal({title:params.title,message:params.message,promt:promt,button:buttonHTML});
    }
    else{
        var markup = [
			'<div id="modal_win" style="z-index:998" class="win_',hashw,'"><div class="modal_win" onclick="is_modal_cur(this)">',
			'<div class="top_big_win">',
            '<div class="top_big_win_2">',
            '<div class="top_big_win_3">',
			'<h3 class="title_win">',params.title,'</h3>',
            '</div></div></div>',
            '<div class="cen_big_win">',
            '<div class="cen_big_win_2">',
            '<div class="cen_big_win_3">',
			'<div class="conteiner_modal">',params.message,promt,'</div>',
			'<div id="modal_buttons">',
			buttonHTML,
			'<div class="clear"></div></div></div></div></div>',
            '<div class="bot_big_win">',
            '<div class="bot_big_win_2">',
            '<div class="bot_big_win_3">',
            '</div></div></div></div></div>'
	    ].join('');
    }
    
    $(markup).hide().appendTo('body').fadeIn();
    
        var win = $('.win_'+hashw).find(api_seting.modal_win);
        
        if(params.width) win.css({'width':params.width});
        
        
        var height = win.height();
        var width  = win.width();
    
        var top    = (height / 2);
        var left   = (width  / 2);

        win.css({
            'top'        :'45%',
            'margin-top' :'-' + top + 'px',
            'margin-left':'-' + left + 'px'
        });
 
        win.animate({top:'50%',opacity:'1'},api_seting.modal_win_speed);
        
        
        get_pos = function(){
            var height = win.height();
    
            var top    = (height / 2);
            
            win.animate({marginTop:'-'+top+'px'},api_seting.modal_win_speed);
        }
        
        setTimeout("get_pos()",300);
    
        var buttons = $('.win_'+hashw).find('#modal_buttons .win_button');
			i = 0;

		$.each(params.buttons,function(name,obj){
			buttons.eq(i++).click(function(){
               
                if(params.promt || params.promt == ''){
                    obj.action($('.win_'+hashw).find('#promt_modal').val());
                }
                else{
                    obj.action(i);
                }
				
				close_modal('.win_'+hashw);
				return false;
			});
		});
}

function close_modal(obj,callback){
    var elem = $(obj);
    
    $(obj).find(api_seting.modal_win).animate({top:'45%',opacity:'0'},api_seting.modal_win_speed,function(){
        
        elem.fadeOut(function(){
			$(this).remove();
            if(callback) callback();
		});
        
    });
    
}

function is_modal_cur(id){
    setTimeout(function(){
        var height = $(id).height();
    
        var top    = (height / 2);
            
        $(id).animate({marginTop:'-'+top+'px'},api_seting.modal_win_speed);
    },300);
    
}

//=========================================//
// WARNING
//=========================================//
function buld_warning(params){
    var elem = $('#warning_modal');

    if(elem.length){
			return false;
    }
    
    var buttonHTML = '';
		$.each(params.buttons,function(name,obj){

			buttonHTML += '<div class="w_button">'+name+'</div>';

			if(!obj.action){
				obj.action = function(){};
			}
	});
    
    var markup = [
            '<div id="warning_modal" style="z-index:999">',
             '<div id="warning_win">',
             '<div class="top">',
              '<div class="content">',
               '<div class="warning_conteiner">',
                '<div class="warning_ico"></div>',
                '<div class="warning_title"></div>',
                '<div id="warning_content">',params.message,'</div>',
               '</div></div>',
             '</div>',
             '<div class="bot">',
              '<div id="warning_buttons">',
              buttonHTML,
              '<div class="clear"></div>',
             '</div>',
            '</div>',
            '</div>',
            '</div>'
	].join('');
    
    $(markup).hide().appendTo('body').fadeIn();
    
        var win = $('#warning_win');
        var height = win.height();
        var width  = win.width();
    
        var top    = (height / 2);
        var left   = (width  / 2);

        win.css({
             'top'        :'45%',
             'margin-top' :'-' + top + 'px',
             'margin-left':'-' + left + 'px'
             });
 
        win.animate({top:'50%',opacity:'1'},500);

        var buttons = $('#warning_buttons .w_button');
	    
        i = 0;

		$.each(params.buttons,function(name,obj){
			buttons.eq(i++).click(function(){
			 
             obj.action();
			 close_warning();
			 return false;
			});
		});
}

function close_warning(){
    var elem = $('#warning_modal');
    
    $('#warning_win').animate({top:'45%',opacity:'0'},500,function(){
        
        elem.fadeOut(function(){
			$(this).remove();
		});
        
    });
}

//=========================================//
// FORM
//=========================================//

function select_show_list(id){
    
    var select = $(id);

    var elem = $(id).find('div.list_select');
    
    var status = elem.css('display');
    
    if(status == 'none'){
        var zindex = eval(select.css('z-index')) + 1;
        
        select.css({'z-index':zindex});
        
        elem.find('ul').width(select.width());
        
        elem.slideDown();
    }
    else{
        var zindex = eval(select.css('z-index')) - 1;
        
        
        
        elem.slideUp(function(){
            select.css({'z-index':zindex});
        });
    }
    
}

function select_list(id,value,name,y){
    var list = $('.list_select_'+id);
    
    var elem = $('.select_name_'+id);
    
    elem.find('span').text(name);
    elem.find('input').val(value);
    
    list.removeClass('selected');
    $(y).addClass('selected');
}

function checkbox(id){
    var elem = $(id);
    
    var status = elem.find('input').val();
    
    if(status == 'yes'){
        elem.removeClass('check');
        elem.find('input').val('no');
    }
    else{
        elem.addClass('check');
        elem.find('input').val('yes');
    }
    
}

function tabs(hash,id,y){
    
    $(api_seting.tabs_li_var+hash).removeClass(api_seting.tabs_active_class);
    $(y).addClass(api_seting.tabs_active_class);
    
    $(api_seting.tabs_hide_class+hash).hide();
    $(api_seting.tabs_content_var+id).show();

}


//=========================================//
// API
//=========================================//


function conect(params){
    
    params = $.extend({url:false,nodisplay:false,post:''},params);
    
    if(!params.url) {
        show_error('Не указан url');
        return false;
    }
    
    sf = params.url.substring(0,1);
    
    //if(sf == '/') params.url = params.url.substring(1,params.url.length);
    
    if(!params.nodisplay) is_loading();
    else $('#is_loading_hide').fadeIn(100);
    
    if(params.post){
        $.post(params.url,params.post,function(data){
            if(operator(data,params.status)){
                if(params.action) params.action(data);
            }
            else{
                if(params.error) params.error(data);
            }
            
            if(!params.nodisplay) close_is_loading();
            else $('#is_loading_hide').fadeOut(100);
        });
    }
    else{
        $.ajax({
            url: params.url,
            success: function(data) {
                if(operator(data,params.status)){
                    if(params.action) params.action(data);
                }
                else{
                    if(params.error) params.error(data);
                }
                
                if(!params.nodisplay) close_is_loading();
                else $('#is_loading_hide').fadeOut(100);
            },
            error: function(){
                show_error('Не удалось установить соединение!');
                if(!params.nodisplay) close_is_loading();
                else $('#is_loading_hide').fadeOut(100);
            }
        });
    }
    
}

function show_content(obj1,obj2,callback){
    $(obj1).fadeOut(200,function(){
        $(obj2).fadeIn(200,function(){
            if(callback) callback();
        });
    })
}

function show_load_content(obj1,callback){
    $(obj1).animate({opacity:'0.5'},200,function(){
        $(this).animate({opacity:'1'},200);
        if(callback) callback(obj1);
    })
}

function is_modal(title,massage,callback){
    buld_modal({
        'title'  :title,
        'message':massage,
        'width'  :600,
        'buttons':{
            'Закрыть':{
                'action':function(){ if(callback) callback(); }
            }
        }
    });
}

function is_modal_seting(title,massage,callback,width){
    buld_modal({
        'title'  :title,
        'message':massage,
        'width'  :((width) ? width : 600),
        'buttons':{
            'Отмена':{},
            'Сохранить':{
                'action':function(){ if(callback) callback(); }
            }
        }
    });
}

function is_confirm(title,massage,callback){
    buld_modal({
        'title'  :title,
        'message':massage,
        'buttons':{
            'Нет':{},
            'Да':{
                'action':function(){ if(callback) callback(); }
            }
        }
    });
}

function is_promt(title,massage,promt,action,option){
    buld_modal({
        'title'  :title,
        'message':massage,
        'promt'  :promt,
        'buttons':{
            'Нет':{},
            'Да':{
                'action':function(e){ if(action) action(e) }
            }
        },
        'reinput':option
    });
}

function warning_confirm(massage,callback){
    buld_warning({
        'message':massage,
        'buttons':{
            'Нет':{},
            'Да':{
                'action':function(){ if(callback) callback(); }
            }
        }
    });
}

function warning(massage,callback){
    buld_warning({
        'message':massage,
        'buttons':{
            'Закрыть':{
                'action':function(){ if(callback) callback(); }
            }
        }
    });
}

function ShowOrHide(id,type){
    var status = $(id).css('display');
    
    if(status == 'none'){
        if(!type)
           $(id).slideDown('fast');
        else
           $(id).show();
    }
    else{
        if(!type)
           $(id).slideUp('fast');
        else
           $(id).hide();
    }
}

function replase(id){
    var val = $(id).val().replace(/[^a-z0-9_]/g,'');
    $(id).val(val);
}
