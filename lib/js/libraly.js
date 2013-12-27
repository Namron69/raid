var libraly = new libraly_manager();

function libraly_manager(){
    
    this.showLibraly = function(p){
        
        in_ce.open('class/libraly.class.php?action=showlib',{name:'Библиотеки',btn:{'Добавить':"libraly.saveNew()",'Импорт':"libraly.import_lib()"},scroll:true});

    }
    
    this.sLcat = function(name){
        
        in_ce.open('class/libraly.class.php?action=sLcat',{name:'Категории',post:{name:name},btn:{'Добавить':"libraly.addCat('"+name+"')"},scroll:true});
        
    }
    
    this.del = function(name){
        is_confirm('Удаление','Вы действительно хотите удалить библиотеку',function(){
            conect({
                url:'class/libraly.class.php?action=del',
                post:{name:name},
                action:function(){
                    $('.blib_'+name).hide();
                    $('.li_prj_'+name).slideUp();
                    libraly.update_libraly();
                    layer.init('doc',true);
                    layer.init('com',true);
                }
            });
        });
    }
    
    this.saveNew = function(){
        is_promt('Создание','Введите название новой библиотеки','',function(name){
            conect({
                url:'class/libraly.class.php?action=new',
                post:{name:name},
                action: function(data){
                    libraly.lib_add_int(name);
                    libraly.showLibraly();
                    libraly.update_libraly();
                    layer.init('doc');
                }
            })
        });
    }
    
    this.addCat = function(idname){
        is_promt('Создание','Введите название новой категории','',function(name){
            conect({
                url:'class/libraly.class.php?action=addCat',
                post:{name:name,lib:idname},
                action: function(data){
                    libraly.sLcat(idname);
                    libraly.update_libraly();
                    layer.init('doc');
                }
            })
        });
    }
    this.delCat = function(name,lib){
        is_confirm('Удаление','Вы действительно хотите удалить категорию',function(){
            conect({
                url:'class/libraly.class.php?action=delCat',
                post:{name:name,lib:lib},
                action:function(){
                    $('.li_prj_'+name).slideUp(300,function(){
                        libraly.update_libraly();
                        layer.init('doc');
                    });
                }
            });
        });
    }
    this.editCat = function(name,lib){
        
        in_ce.open('class/libraly.class.php?action=editCat',{post:{name:name,lib:lib},btn:{'Назад':"libraly.sLcat('"+lib+"')",'Добавить':"libraly.preaddObj('"+name+"','"+lib+"')"},scroll:true});
        
    }
    this.preaddObj = function(name,lib){
        is_promt('Название','Введите название','',function(val_name){
            conect({
                url:'class/libraly.class.php?action=obj_save',
                post:{name:name,lib:lib,obj_option:{name:val_name}},
                action:function(){
                    libraly.editCat(name,lib);
                    libraly.update_libraly();
                }
            });
        })
        
    }
    this.obj_edit = function(name,lib,id){
        in_ce.open('class/libraly.class.php?action=editObj',{post:{name:name,lib:lib,id:id},btn:{'Назад':"libraly.editCat('"+name+"','"+lib+"')",'Сохранить':"libraly.obj_save('"+name+"','"+lib+"',"+id+")"}});
    }
    this.obj_save = function(name,lib){
        conect({
            url:'class/libraly.class.php?action=obj_save',
            post:$('#obj_formX5674').serializeArray(),
            action:function(){
                libraly.editCat(name,lib);
                libraly.update_libraly();
            }
        });
    }
    this.prew_edit = function(name,lib,id){
        in_ce.open('class/libraly.class.php?action=editPrew',{post:{name:name,lib:lib,id:id},btn:{'Назад':"libraly.editCat('"+name+"','"+lib+"')",'Сохранить':"libraly.prew_save('"+name+"','"+lib+"',"+id+")"}});
    }
    this.prew_save = function(name,lib,id){
        conect({
            url:'class/libraly.class.php?action=prew_save&id='+id,
            post:$('#obj_formX5674').serializeArray(),
            action:function(){
                libraly.editCat(name,lib);
                libraly.update_libraly();
            }
        });
    }
    this.obj_delete_arr = function(name,lib,id,elem){
        is_confirm('Подтвердить','Вы действительно хотите удалить объект',function(){
            conect({
                url:'class/libraly.class.php?action=obj_delete',
                post:{name:name,lib:lib,id:id},
                action:function(){
                    libraly.editCat(name,lib);
                }
            });
        })
    }
    this.obj_add = function(){
        
        $('.ObjListX08932').append('<li class="p_1"><div class="lineB"><img src="lib/images/option_ico.png" class="left" /><div class="left textPad" style="min-width: 100px; font-weight: bold"><input type="text" onkeyup="replase(this); this.value = this.value.substr(0,15)" name="obj_option[option][]" value="" spellcheck="false" class="buld_input"></div><div class="right"><div class="grayBtn" onclick="libraly.obj_del_op(this); initScroll(\'objListX689334\',{height: in_ce.i_height,update:\'relative\'});">Удалить</div></div><div class="clear"></div></div></li>');
    }
    this.lib_add_int = function(name){
        if(!$('#modulsUp li.blib_'+name).length){
            $('#modulsUp li').removeClass('active').parents('ul').append('<li class="blib_'+name+' blib_select active"><span>'+name.substr(0,14)+'<b uplname="'+name+'"></b></span></li>').find('li.nolib').remove();
        }
    }
    this.obj_del_op = function(elem){
        $(elem).parents('li').remove();
    }
    this.obj_area_key = function(event){
        if(event.keyCode == 13) libraly.obj_area_init();
    }
    this.obj_area_init = function(){
        var elem = $('#obj_area_id');
        var value = elem.val();
        var w = elem.width();
        var h = value.split("\n").length * 17;
        
        var sc_line = function(v){
            bn = Math.round((w - 10) / 8);
            
            if(v.length > bn){
                h = h + 17;
                sc_line(v.substr(bn,v.length));
            }
        }
        
        $.each(value.split("\n"),function(line,i){ 
            sc_line(i); 
        });
                      
                 
        elem.height(h > in_ce.i_height ? h : in_ce.i_height - 10);
        
        initScroll('objListX372663',{height: in_ce.i_height,update:'relative'});
    }
    this.renameLib = function(name){
        is_promt('Название','Укажите на новое название библиотеки','',function(newname){
            conect({
                url:'class/libraly.class.php?action=renameLib',
                post:{name:name,newname:newname},
                action:function(){
                    libraly.showLibraly();
                    libraly.update_libraly();
                }
            });
        })
        
    }
    this.update_libraly = function(){
        $('#libraly_script').remove();
        $('body').append('<script src="class/godata.js.php?login='+user_name_var+'" id="libraly_script" type="text/javascript"></script>');
    }
    this.export_lib = function(name){
        conect({
            url:'class/libraly.class.php?action=export',
            post:{name:name}
        });
    }
    this.import_lib = function(){
        in_ce.open('class/libraly.class.php?action=show_export',{name:'Импорт',btn:{'Назад':'libraly.showLibraly()'},scroll:true});
    }
    this.get_import_lib = function(name,username){
        is_confirm('Предупреждение','Ваша библиотека может быть заменена на новою, вы действительно хотите сделать импорт',function(){
            conect({
                url:'class/libraly.class.php?action=get_import',
                post:{name:name,username:username},
                action: function(){
                    libraly.lib_add_int(name);
                    show_error('Библиотека была успешна импортирована');
                    layer.init('doc');
                    libraly.update_libraly();
                }
            });
        });
    }
}