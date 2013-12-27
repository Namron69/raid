function getAttributes(elem){
    var attributes = {};

    $.each(elem.attributes, function(index, attr) {
        attributes[attr.name] = attr.value;
    }); 

    return attributes;
}

var rad = new rad_manager();

function rad_manager(){
    
    this.getCodeRAD = '';
    this.lastSelectID = null;
    this.lastESelect = null;
    this.startObj = null;
    this.arrNext = {};
    this.arrCount = 0;
    this.copy_last = null;
    this.tool = {
        select: true,
        del: false,
        del_is: false,
        clone: false,
        copy: false,
        paste: false,
        click:false
    }
    
    this.back_fun = function(name,data){
        parent['iframe'][name](data);
    }
    this.back_vars = function(name,data){
        parent['iframe'][name] = data;
    }
    
    this.open = function(arr){
        window.location = 'class/iframeResult.php?'+(arr.prj ? 'prj='+arr.prj+'&' : '')+(arr.js ? 'js='+arr.js+'&' : '')+'open='+escape(arr.url);
    }
    this.reload = function(arr){
        window.location = 'class/iframeResult.php?prj='+arr.prj+(arr.js ? '&js='+arr.js : '');
    }
    this.save = function(callback){
        if(callback) callback($('#globalConteinerCode').html());
    }
    this.tools = function(name){
        $.each(rad.tool,function(k,v){
            if(k == name) rad.tool[k] = true;
            else rad.tool[k] = false;
        });
    }
    
    this.bindRad = function(){
        
        rad.getCodeRAD = $("#globalConteinerCode *");
        
        rad.getCodeRAD.unbind();
        
        rad.getCodeRAD.bind('mouseover',function(event){
            if (event.target==this){
                $(this).addClass('activeHover');
            }
            
        }).bind('mouseout',function(event){
            if (event.target==this){
                $(this).removeClass('activeHover');
            }
        }).click(function(event){
            if (event.target==this){
                rad.lastESelect = $(this);
                rad.startObj = $(this);
                
                if(rad.tool.del){
                    rad.delete_ob();
                }
                else if(rad.tool.clone){
                    rad.clone();
                }
                else if(rad.tool.del_is){
                    rad.delete_is_ob();
                }
                else if(rad.tool.copy){
                    rad.copy();
                }
                else if(rad.tool.paste){
                    rad.paste();
                }
                else{
                    rad.start();
                }
                
                if(!rad.tool.click){
                    return false;
                }
                
            }
        });
        
        
        $('a').bind('click',function(){
            if(!rad.tool.click){
                return false;
            }
        });
    }
    this.delete_ob = function(){
        rad.lastESelect.remove();
    }
    this.clear = function(){
        rad.lastESelect.html('');
    }
    this.delete_attr = function(name){
        rad.lastESelect.removeAttr(name);
    }
    this.change_attr = function(arr){
        rad.lastESelect.attr(arr.name,arr.val);
    }
    this.clone = function(){
        var clone_ob = rad.lastESelect.removeClass('activeSelect').clone();
        rad.lastESelect.addClass('activeSelect').before(clone_ob);
        rad.bindRad();
    }
    this.copy = function(){
        rad.copy_last = null;
        rad.copy_last = rad.lastESelect.removeClass('activeSelect').clone();
        rad.lastESelect.addClass('activeSelect');
    }
    this.paste = function(){
        rad.lastESelect.append(rad.copy_last);
        rad.bindRad();
    }
    this.edit = function(callback){
        if(callback) callback(rad.lastESelect.html());
    }
    this.save_edit = function(data){
        rad.lastESelect.html(data);
        rad.bindRad();
    }
    this.add_attr = function(name){
        rad.lastESelect.attr(name,'');
        rad.get_option();
    }
    this.select_index = function(arr){
        rad.startObj = rad.lastESelect.find(arr.tag).eq(arr.index);
        rad.start();
    }
    this.hover_index = function(arr){
        rad.lastESelect.find(arr.tag).eq(arr.index).addClass('activeHover');
    }
    this.out_index = function(arr){
        rad.lastESelect.find(arr.tag).eq(arr.index).removeClass('activeHover');
    }
    this.delete_index = function(arr){
        rad.lastESelect.find(arr.tag).eq(arr.index).remove();
    }
    this.show_hide = function(){
        if( rad.lastESelect.is(':visible') ) {
            rad.lastESelect.hide().addClass('raidHiddenElem');
        }
        else {
            rad.lastESelect.show().removeClass('raidHiddenElem');
        }
    }
    this.showAllHidden = function(){
        $('#globalConteinerCode .raidHiddenElem').show();
    }
    this.copy_html = function(callback){
        data = rad.lastESelect.wrap('<div></div>').parent().html();
        rad.lastESelect.parent().replaceWith(data);
        rad.bindRad();
        if(callback) callback(data);
    }
    this.delete_is_ob = function(){
        rad.lastESelect.replaceWith(rad.lastESelect.html());
        rad.bindRad();
    }
    this.code = function(arr){
        
        if(arr.type > 1) arr.data = arr.data.replace(/\{\%code\%\}/g,'');
        
        switch (arr.type){ 
        	case 1:
                exp = arr.data.split("{%code%}");
                
                if(exp[0].substr(26, 17) == '<!--code_start-->'){
                    if(!arr.attr){
                        rad.lastESelect.after(exp[0]+'<!--code_end--></div>').before('<div style="display:none"><!--code_start-->'+exp[1]);
                    } 
                }
                else{
                    if(arr.attr) rad.lastESelect.attr(arr.attr,exp[0]+rad.lastESelect.attr(arr.attr)+exp[1]);
                    else{
                        data = rad.lastESelect.wrap('<div></div>').parent().html();
                        rad.lastESelect.parent().replaceWith(exp[0]+data+exp[1]);
                    }
                } 
        	break;
        
        	case 2:
                if(arr.attr) rad.lastESelect.attr(arr.attr,arr.data)
                else rad.lastESelect.html(arr.data);
        	break;
        
        	case 3:
                if(arr.attr) rad.lastESelect.attr(arr.attr,arr.data)
                else rad.lastESelect.replaceWith(arr.data);
        	break;
            
            case 4:
                if(arr.attr) rad.lastESelect.attr(arr.attr,rad.lastESelect.attr(arr.attr)+arr.data)
                else rad.lastESelect.append(arr.data);
        	break;
            
            case 5:
                if(arr.attr) rad.lastESelect.attr(arr.attr,arr.data+rad.lastESelect.attr(arr.attr))
                else rad.lastESelect.prepend(arr.data);
        	break;
        
        	default :
        }
        
        if(arr.attr) rad.get_option();
        rad.bindRad();
    }
    this.get_option = function(){
        var find = rad.lastESelect.find('*');
        var finded = {};
        var count = 0;
        var tagname = rad.lastESelect.get(0).tagName;
        
        $.each(find,function(){
            count += 1;
            if(count <= 15) finded[count] = {tag:$(this).get(0).tagName,index:rad.lastESelect.find($(this).get(0).tagName).index(this)};
        });
        
        rad.back_fun('option',{
            name:tagname,
            attr:getAttributes(rad.lastESelect.get(0)),
            find:finded,
            img:tagname == 'IMG' ? {w:rad.lastESelect.width(),h:rad.lastESelect.height()} : {},
            index:rad.lastESelect.index()
        });
    }
    this.up_down = function(arr){
        var i   = rad.lastESelect.index(),
            obj = $(rad.lastESelect).parent().find(arr.tag).eq(arr.p ? i-1 : i+1);
        
        if(obj.length){
            rad.getCodeRAD.removeClass('activeSelect');
            rad.lastESelect = $(obj).addClass('activeSelect');
            rad.get_option();
        }
    }
    this.back = function(){
        if($(rad.lastESelect).parent().not('#globalConteinerCode').length){
                
            rad.getCodeRAD.removeClass('activeSelect');
            
            rad.lastESelect = $(rad.lastESelect).parent();
            
            rad.arrCount += 1;
            rad.arrNext[rad.arrCount] = rad.lastESelect;
            
            $(rad.lastESelect).addClass('activeSelect');
            
            rad.get_option();
        }
    }
    this.next = function(){
        if(rad.arrCount > 0){
            rad.getCodeRAD.removeClass('activeSelect');
                
            rad.arrCount -= 1;
            
            rad.lastESelect = $(rad.arrNext[rad.arrCount]);
            
            $(rad.lastESelect).addClass('activeSelect');
            
            rad.get_option();
        }
    }
    this.start = function(){
        rad.getCodeRAD.removeClass('activeSelect');
        
        rad.lastESelect = rad.startObj;
        
        $(rad.lastESelect).addClass('activeSelect');
        
        rad.arrCount = 0;
        rad.arrNext[rad.arrCount] = rad.lastESelect;
        
        rad.get_option();
    }
}

$(window).keypress(function(event){
    if(event.which == 0 && event.keyCode == 37){
        rad.back();
    }
    if(event.which == 0 && event.keyCode == 39){
        rad.next();
    }
    
});