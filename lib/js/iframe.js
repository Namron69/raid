var iframe = new iframe_manager();

function iframe_manager(){
    
    this.iname = 'result_sourceView';
    this.last_url = '';
    this.last_reobj = 0;
    this.fS = false;
    this.fSL = {w:0,h:0};
    this.attrAD = false;
    
    this.fun = function(name,data){
        document.getElementById(this.iname).contentWindow['rad'][name](data);
        file.chenge();
    }
    this.vars = function(name,data){
        document.getElementById(this.iname).contentWindow['rad'][name] = data;
    }
    
    this.open_url = function(){
        is_promt('Адрес','Введите URL адрес сайта',iframe.last_url,function(url){
            iframe.last_url = url;
            
            buld_modal({
                'title'  :'Javascript',
                'message':'Хотите открыть страницу со скриптами',
                'buttons':{
                    'Нет':{
                        'action':function(){
                            iframe.fun('open',{prj:openPrj,url:url,js:1});
                        }
                    },
                    'Да':{
                        'action':function(){
                            iframe.fun('open',{prj:openPrj,url:url});
                        }
                    }
                }
            });
        
            
            file.chenge();
        },true)
    }
    this.selectReObj = function(se){
        iframe.last_reobj = se;
        
        $('div.libReObj').delay(300).fadeIn(200,function(){
            $('#resultReObj').html(iframe.build_code());
            initScroll('reobj',{height:layer.conteiner - 44,update:'relative'});
        });
    }
    this.reobj = function(id){
        iframe.getsource(id,function(data){
            iframe.fun('code',{type:iframe.last_reobj,data:data,attr:(iframe.attrAD ? iframe.attrAD : false)});
        });
    }
    this.getsource = function(id,callback){
        conect({
            url:'class/libraly.class.php?action=get_source',
            post:{name:LastSelectLib,id:id},
            action:function(data){
                if(callback) callback(data);
            },
            nodisplay:true
        });
    }
    this.back_point = function(){
        conect({
            url:'class/file.class.php?action=back_point',
            post:{name:file.name},
            action:function(){
                iframe.fun('reload',file.name);
            },
            nodisplay:true
        });
    }
    this.js = function(t){
        iframe.fun('open',{prj:openPrj,url:url,js:t});
    }
    this.build_code = function(){
        var code = '<ul class="ul tableList reobj_ul_list">';
        var p = 1;
        $.each(arrMenuInit[LastSelectLib],function(namelib,array){
            
            p = p > 2 ? 1 : p;
            
            code += [
                '<li class="p_'+p+'"> <div class="lineB" style="padding: 4px 0px"> <img class="left" src="lib/images/objects_ico.png"> ',
                '<div style="min-width: 100px; font-weight: bold" class="left textPad">'+namelib+'</div>',
                '<div class="right isop"></div>',
                '<div class="clear"></div><div class="coe hide">',
            ].join('');
            
            $.each(array,function(index,arr){
            
                $.each(arr,function(fun,name){
                
                    code += '<div onclick="$(\'.libReObj\').fadeOut(200);'+fun+'">'+name+'</div>';
                            
                });
                        
            });
            
            code += '</div> </div> </li>';
            
            p++;          
        });
        
        return code+'</ul>';
    }
    this.edit = function(){
        iframe.fun('edit',function(data){
            data = unescape(data);
            data = data.replace(/&lt;\?/g,'<\?');
            data = data.replace(/\?&gt;/g,'\?>');
            
            data = data.replace(/&amp;lt;\?/g,'<\?');
            data = data.replace(/\?&amp;gt;/g,'\?>');
            
            var data = scroll('<div class="textarea_c"><textarea onkeyup="libraly.obj_area_key(event);" style="overflow: hidden;" id="obj_area_id" spellcheck="false" class="textarea_scrin">'+data+'</textarea></div>','objListX372663')
    
            in_ce.open('',{name:'Значение',btn:{'Сохранить':"iframe.save_edit()"},data:data});
            initScroll('objListX372663',{height: in_ce.i_height});
            libraly.obj_area_init();
        })
    }
    this.copy_html = function(){
        buld_modal({
            'title'  :'Сохранить',
            'message':'Хотите сохранить выделенный код вместе со стилями',
            'buttons':{
                'Нет':{
                    'action':function(){
                        iframe.copy_html_action(1);
                    }
                },
                'Да':{
                    'action':function(){
                        iframe.copy_html_action(2);
                    }
                }
            }
        });
            
    }
    this.copy_html_action = function(ac){
        iframe.fun('copy_html',function(data){
            /*
            elem = $('#'+iframe.iname).contents().find('#globalConteinerCode').find(arr.tag).eq(arr.index);
            data = elem.wrap('<div></div>').parent().html();
            elem.parent().replaceWith(data);
            iframe.fun('bindRad');
            */
            is_promt('Название','Введите названия html файла','',function(filename){
                conect({
                    url:'class/file.class.php?action=copy_html',
                    post:{ac:ac,data:data,filename:filename},
                    action:function(){
                        
                    },
                    nodisplay:true
                });
            });
        })
    }
    this.save_html_main = function(){
        is_promt('Название','Введите названия html файла','',function(filename){
            conect({
                url:'class/file.class.php?action=copy_html',
                post:{ac:1,data:$('#'+iframe.iname).contents().find('#globalConteinerCode').html(),filename:filename},
                action:function(){
                    
                },
                nodisplay:true
            });
        });
    }
    this.save_edit = function(){
        var data = $('#obj_area_id').val();
        data = data.replace(/<\?/g,'&lt;?');
        data = data.replace(/\?>/g,'?&gt;');
            
        iframe.fun('save_edit',data);
        in_ce.close();
    }
    this.add_attr = function(){
        is_promt('Атрибут','Введите название атрибута','',function(name){
            iframe.fun('add_attr',name);
        })
    }
    this.option = function(arr){
        iframe.attrAD = false;
        
        var list_li = '';
        var list_tag= '';
        var style = {};
        
        $.each(arr.attr,function(k,v){
            if(k == 'class'){
                v = v.replace(/activeHover\s/g,'');
                v = v.replace(/activeSelect\s/g,'');
                v = v.replace(/activeSelect/g,'');
                v = v.replace(/activeHover/g,'');
                
                style['class'] = v;
            } 
            
            
            
            list_li += [
                '<div class="GetOptionStroce">',
                '<img src="lib/images/delete.png" class="left" style="vertical-align: middle; margin-top: 5px; cursor:pointer" onclick="iframe.fun(\'delete_attr\',\'',k,'\'); $(this).parent().slideUp()" />',
                '<div class="name left"><b>',(k.length > 6 ? '<span tooltip="'+k+'">'+k.substr(0,6)+'...</span>' : k+':'),'</b></div>',
                '<div class="value right"><input type="text" name="',k,'" value="',re_val(v),'" spellcheck="false" onkeyup="iframe.fun(\'change_attr\',{name:\'',k,'\',val:this.value});" style="width: 125px" /></div>',
                '<div class="si_ld">',
                '<b onclick="$(\'div.selectReObj\').fadeIn(200); iframe.attrAD = \'',k,'\';"></b>',
                '<b onclick="BigAttr(\'',k,'\')"></b>',
                '</div>',
                '<div class="clear"></div>',
                '</div>'
            ].join('');
            
            if(k == 'id') style['id'] = v;    
        });
        
        
        
        var controll = [
            '<ul class="OptionControllObj">',
            '<li class="back" onclick="iframe.fun(\'back\')"></li>',
            '<li class="start" onclick="iframe.fun(\'start\')"></li>',
            '<li class="next" onclick="iframe.fun(\'next\')"></li>',
            '<li class="up" onclick="iframe.fun(\'up_down\',{tag:\'',arr.name,'\',p:true})"></li>',
            '<li class="down" onclick="iframe.fun(\'up_down\',{tag:\'',arr.name,'\'})"></li>',
            '</ul>'
        ].join('');
        
        var panel = [
            '<div class="grayBtn" onclick="iframe.copy_html()" style="margin-right: 4px"><img src="lib/images/save_ico.png" tooltip="Сохранить код" /></div>',
            '<div class="grayBtn" onclick="iframe.add_attr()" style="margin-right: 4px"><img src="lib/images/add_attr_ico.png" tooltip="Добавить атрибут" /></div>',
            '<div class="grayBtn" onclick="iframe.edit()" style="margin-right: 4px"><img src="lib/images/edit_ico.png" tooltip="Редактировать" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'copy\')" style="margin-right: 4px"><img src="lib/images/copy_ico.png" tooltip="Копировать" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'paste\')" style="margin-right: 4px"><img src="lib/images/paste_ico.png" tooltip="Вставить" /></div>',
            '<div class="grayBtn" onclick="$(\'div.selectReObj\').fadeIn(200);iframe.attrAD=false;" style="margin-right: 4px"><img src="lib/images/code_ico.png" tooltip="Вставить код" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'clone\')" style="margin-right: 0px;"><img src="lib/images/clone_ico.png" tooltip="Клонировать" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'show_hide\')" style="margin-right: 4px; margin-top: 3px"><img src="lib/images/hide_show_ico.png" tooltip="Спрятать/Показать" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'clear\')" style="margin-right: 4px; margin-top: 3px"><img src="lib/images/clear_ico.png" tooltip="Очистить" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'delete_is_ob\')" style="margin-right: 4px; margin-top: 3px"><img src="lib/images/delete_is_ico.png" tooltip="Удалить тег" /></div>',
            '<div class="grayBtn" onclick="iframe.fun(\'delete_ob\')" style="margin-right: 0px; margin-top: 3px"><img src="lib/images/delete_ico.png" tooltip="Удалить все" /></div>',
        ].join('');
        
        $.each(arr.find,function(index,tags){
            list_tag += '<li class="p_1"> <div class="lineB" style="padding: 4px 0px"> <img class="left" src="lib/images/objects_ico.png"> <div style="min-width: 100px; font-weight: bold" class="left textPad">'+tags.tag+'</div> <div class="right"> <div onclick="iframe.fun(\'select_index\',{tag:\''+tags.tag+'\',index:'+tags.index+'});" onmouseover="iframe.fun(\'hover_index\',{tag:\''+tags.tag+'\',index:'+tags.index+'});" onmouseout="iframe.fun(\'out_index\',{tag:\''+tags.tag+'\',index:'+tags.index+'});" class="grayBtn"><img src="lib/images/edit_ico.png" style="margin-right: 0px" tooltip="Выделить" /></div> <div onclick="iframe.fun(\'delete_index\',{tag:\''+tags.tag+'\',index:'+tags.index+'}); $(this).parents(\'li\').slideUp(function(){ $(this).remove(); initScroll(\'option\',{height:layer.conteiner - 44,update:\'relative\'}); })" class="grayBtn"><img src="lib/images/delete_ico.png" style="margin-right: 0px" tooltip="Удалить" /></div> </div> <div class="clear"></div> </div> </li>';
        })
        
        if(arr.name == 'IMG'){
            list_li = '<div style="background: #212121" class=""><div class="padding_10">Width: <b>'+arr.img.w+'px</b> - Height: <b>'+arr.img.h+'px</b></div></div>' + list_li;
        }
        
        var result = controll+'<b class="nameObjOp">Обьект: '+arr.name+'</b><div style="width:220px">'+panel+'</div><b class="nameObjOp"></b>'+list_li+'<ul class="ul tableList">'+list_tag+'</ul>';
        
        $('.visualSeting').html(result);
        initScroll('option',{height:layer.conteiner - 44});
        
        conect({
            url:'class/file.class.php?action=getstyle',
            post:{styles:style,tag:arr.name},
            action:function(data){
                $('.visualSetingCss').html(data);
                initScroll('option_css',{height:layer.conteiner - 44});
            },
            nodisplay:true
        });
        
    }
    this.copy_css = function(style,tag){
        conect({
            url:'class/file.class.php?action=css_copy',
            post:{styles:style,tag:tag},
            action:function(data){
                
                var data = scroll('<div class="textarea_c"><textarea onkeyup="libraly.obj_area_key(event);" style="overflow: hidden;" id="obj_area_id" spellcheck="false" class="textarea_scrin">'+data+'</textarea></div>','objListX372663')
    
                in_ce.open('',{name:'Стили',data:data});
                initScroll('objListX372663',{height: in_ce.i_height});
                libraly.obj_area_init();
            
            },
            nodisplay:true
        });
    }
    this.show_big = function(event,url){
        
        var ielem = $('#show_big_iframe');
        var wh = $(window).height();
        
        if(!ielem.length){
            var dig = [
                '<div id="show_big_iframe" class="position_f">',
                '<iframe src="',url,'" width="200" height="200" frameborder="0" scrolling="no"></iframe>',
                '</div>'
            ].join('');
            
            ielem = $(dig).appendTo('body');
            
        }
        else{
            ielem.find('iframe').attr('src',url);
        }
        
        ielem.css({left:event.pageX+7,top:((event.pageY+227 > wh) ? event.pageY - (event.pageY+227 - wh) : event.pageY+7)}).show();
    }
    this.hide_big = function(){
        $('#show_big_iframe').hide();
    }
    this.insert_user_code = function(name){
        
        go_insert = function(type){
            conect({
                url:'class/libraly.class.php?action=get_source_user',
                post:{user_source:name,type:type},
                action:function(data){
                    iframe.fun('code',{type:4,data:data});
                },
                nodisplay:true
            });
        }
        
        buld_modal({
            'title'  :'Стили',
            'message':'Загружать стили из исходного кода',
            'buttons':{
                'Нет':{
                    'action':function(){
                        go_insert(0);
                    }
                },
                'Да':{
                    'action':function(){
                        go_insert(1);
                    }
                }
            }
        });
        
    }
    this.fullScreen = function(elem){
        var ifr = $(elem);
        
        if(!iframe.fS){
            iframe.fS = true;
            
            iframe.fSL.w = ifr.find('.visualConteiner').width();
            iframe.fSL.h = ifr.find('.visualConteiner').height();
            
            ifr.addClass('fS').find('iframe,.visualConteiner').height($(window).height()).width($(window).width());
        }
        else{
            iframe.fS = false;
            ifr.removeClass('fS').find('iframe,.visualConteiner').height(iframe.fSL.h).width(iframe.fSL.w);
        }
    }
}