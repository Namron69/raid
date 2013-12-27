var LastSelectLib = '';
var LasSelectEngine = 'sl';

$(document).ready(function(){
    layer.init('doc');
    window.onresize = function(event){
        layer.init('doc');
        initScroll('project',{height:layer.conteiner - 44,update:'relative'});
        initScroll('option',{height:layer.conteiner - 44,update:'relative'});
        initScroll('option_css',{height:layer.conteiner - 44,update:'relative'}); 
        initScroll('images',{height:layer.conteiner - 44,update:'relative'});
        initScroll('reobj',{height:layer.conteiner - 44,update:'relative'});
        initScroll('comp',{height:layer.conteiner - 44,update:'relative'});
    }

    initScroll('project',{height:layer.conteiner - 44});
    initScroll('option',{height:layer.conteiner - 44});
    initScroll('option_css',{height:layer.conteiner - 44});
    initScroll('images',{height:layer.conteiner - 44});
    initScroll('reobj',{height:layer.conteiner - 44});
    initScroll('comp',{height:layer.conteiner - 44});
    
    
    $('body').live('contextmenu',function(e){
        return false;
    });
    
    
    $(window).keypress(function(event){
        
        if(event.which == 115 && event.keyCode == 0 && event.ctrlKey){
            event.preventDefault();
            file.save();
        }
        if(event.which == 0 && event.keyCode == 37){
            //iframe.fun('back');
        }
        if(event.which == 0 && event.keyCode == 39){
            //iframe.fun('next');
        }
        
    });
    
    
    $('[uplName]').live('click',function(e){
        nameLib = $(this).attr('uplName');
        menu.open({x:e.pageX,y:e.pageY},{'Редактировать':"libraly.sLcat('"+nameLib+"')",'Удалить':"libraly.del('"+nameLib+"')"});
    });
    
    $('#scroll_images').live('contextmenu',function(e){
        menu.open({x:e.pageX,y:e.pageY},{'Загрузить':"image.dialog()",'Создать папку':"image.createDir()",'Запаковать в zip':"image.ziped()"});
        return false;
    });
    
    $('li.blib_select').live('click',function(){
        LastSelectLib = $(this).find('[uplName]').attr('uplName');
        $(this).parent().find('li').removeClass('active');
        $(this).addClass('active')
    }).click();
    
    $('.libralyOp').live('click',function(e){
        libraly.showLibraly();
    });
    
    $('.reobj_ul_list div.isop').live('click',function(){
        $(this).parents('li').toggleClass('active');
        initScroll('reobj',{height:layer.conteiner - 44,update:'relative'});
    });

    $('input.promt_modal_input').live('keyup',function(){
        $(this).val($(this).val().replace(/[^a-z_0-9\.]/gi,''));
    });
    
    $('ul.OptionControllObj li').live('mouseover',function(){
        $(this).parent().removeClass('up start down next back').addClass($(this).attr('class'));
    }).live('mouseout',function(){
        $(this).parent().removeClass('up start down next back');
    })

});