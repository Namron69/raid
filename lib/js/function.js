function loadPrewEngine(){
    if(file.prev_url == ''){
        show_error('Не указан адрес сайта для превью');
        return;
    }
    
    $('.selectEngine').hide();
    
    var urlIf = 'class/iframeResult.php?prew'+(openPrj ? '&prj='+file.name : '');
    
    $('#PrewGF').show();
    
    if($('.addIframe iframe').length){
        $('#PrewGF').find('iframe').attr('src',urlIf);
    }
    else{
        $('.addIframe').append('<iframe src="'+urlIf+'" style="border: 0;" id="result_sourcePrew" onmouseover="$(\'.iframeTools\').fadeIn(200)"></iframe>');
        layer.init('doc');
    }
}
function updatePrewIf(ih){
    $('#result_sourcePrew').attr('src','class/iframeResult.php?prew'+(openPrj ? '&prj='+file.name : '')+(ih ? '&load' : ''))
}
function statusBar(text){
    $('.statusBar').text(text);
}
function BigAttr(name){
    var value = $('.GetOptionStroce input[name='+name+']').val();
    
    w_api.modal('Опции','<div class="textarea"><textarea style="width:100%;height:140px;resize: none" spellcheck="false" onkeyup="iframe.fun(\'change_attr\',{name:\''+name+'\',val:re_val(this.value)}); $(\'.GetOptionStroce input[name='+name+']\').val(this.value)">'+value+'</textarea></div>',{height: 260});
}
function re_val(value){
    return value.replace(/"/g,"'");
}
function scroll(data,id){
    var o = '';
    o += '<div class="scrollbarContent" id="scroll_'+id+'">';
    o += ' <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>';
    o += ' <div class="viewport">';
    o += '  <div class="overview">';
    o += '   '+data;
    o += '  </div>';
    o += ' </div>';
    o += '</div>';
    
    return o;
}

function initScroll(name,arr){
    
    var arr = $.extend({},arr);
    
    var f = {
        o:'.overview',
        v:'.viewport',
        obj: '#scroll_'+name
    }
    
    var i = {
        v: $(f.obj+' '+f.v),
        o: $(f.obj+' '+f.o),
        obj: $(f.obj)
    }
    
    i.v.height((arr.height) ? arr.height : i.obj.parent().height());
    
    i.obj.find(f.v+','+f.o).width(arr.width ? arr.width : (i.o.height() >= i.obj.height() ? i.obj.width() - 3 : i.obj.width()));
    
    if(arr.update) i.obj.tinyscrollbar_update(arr.update);
    else i.obj.tinyscrollbar();
}

function toolsAction(id,name){
    
    $('.toolsUl li').removeClass('active');
    $(id).addClass('active');
    iframe.fun('tools',name);
}

function abount(){
    var data = [
        '<div align="left"><img src="lib/images/abountLogo_53.png" /></div><br>',
        'Автор скрипта: <b>Korner</b><br>',
        'Версия: <b>1.06</b><br><br>',
        'RAID - создан <a href="http://qwarp.sl-cms.com">QWARP</a> студией совместно с <a href="http://sl-cms.com">SL-SYSTEM</a>, работающий над тем, чтобы сделать верстку сайтов удобной и быстрой. Мы верим, что RAID должен быть общедоступным ресурсом, открытым и доступным для всех и каждого.'
    ].join('');
    
    w_api.modal('О проекте',data,{width: 450,height: 297});
}
function htmlspecialchars(text)
{
   var chars = Array("&", "<", ">", '"', "'");
   var replacements = Array("&amp;", "&lt;", "&gt;", "&quot;", "'");
   for (var i=0; i<chars.length; i++)
   {
       var re = new RegExp(chars[i], "gi");
       if(re.test(text))
       {
           text = text.replace(re, replacements[i]);
       }
   }
   return text;
}