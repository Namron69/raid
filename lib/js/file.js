var file = new file_manager();

function file_manager(){
    
    this.name = '';
    this.chengeStatus = false;
    this.stopTime = false;
    this.prev_url = false;
    this.prev_key = false;
    
    this.save = function(callback){
        
        if(file.name == ''){
            is_promt('Сохранение','Введите название нового проекта','',function(name){
                file.saveNew(name);
            });
            return;
        }
        //iframe.fun('save',function(data){
        if(file.chengeStatus){
            conect({
                url:'class/file.class.php?action=save',
                post:{data:$('#'+iframe.iname).contents().find('#globalConteinerCode').html(),name:file.name,url:iframe.last_url,prev:file.prev_url,prev_key:file.prev_key},
                action:function(){
                    if(callback) callback();    
                },
                nodisplay:true
            });
        }
        //})
        
        
        file.chenge(true);
    }
    
    this.saveSource = function(){
        conect({
            url:'class/buld.php?action=sourcePrj',
            post:$('#form_doc').serializeArray(),
            action:function(data){
                conect({
                    url:'class/file.class.php?id=com&action=saveSource',
                    post:{data:data,name:file.name},
                    nodisplay:true
                });
            },
            nodisplay:true
        });
    }
    
    this.saveNew = function(name,r){
        conect({
            url:'class/file.class.php?action=new',
            post:{name:name},
            action: function(data){
                
                if(data == 'true' || r){
                    file.name = name;
                    file.save();
                    openPrj = true;
                }
                else{
                    is_confirm('Изменить','Вы хотите изменить проект на новый',function(){
                        file.saveNew(name,true);
                    })
                }
            },
            nodisplay:true
        })
    }
    
    this.saveAs = function(){
        is_promt('Сохранение','Введите название нового проекта','',function(name){
            file.saveNew(name);
        });
    }
    
    this.open = function(name){
        if(file.chengeStatus){
            is_confirm('Сохранение','Файл был изменен и не сохранен, вы уверены что хотите открыть другой проект.',function(){
               file.chenge(true);
               file.open(name);
            });
        }
        else{
            $('.loadingPrj').fadeIn(300,function(){
                window.location = 'index.php?openPrj='+name;
            });
        }
        
    }
    this.chPrev = function(){
        is_promt('Превью','Укажите адрес для превью результата',file.prev_url,function(us){
            file.prev_url = us;
            file.chenge(false);
        },true)
    }
    this.chPrevKey = function(){
        is_promt('Ключ','Укажите ключ для доступа скрипта к вашему сайту',file.prev_key,function(us){
            file.prev_key = us;
            file.chenge(false);
        })
    }
    this.setChar = function(ch){
        conect({
            url:'class/file.class.php?action=charset',
            post:{ch:ch},
            action:function(){
                $('#charSet').text(ch==1 || ch==0 ? 'Windows-1251' : 'UTF-8');
            },
            nodisplay:true
        });
    }
    this.chenge = function(st){
        if(st){
            $('.visualConteiner').css({borderColor:'#454545'});
            file.chengeStatus = false;
        }
        else{
            $('.visualConteiner').css({borderColor:'#f37e00'});
            file.chengeStatus = true;
        }
        
    }
    
    this.del = function(name){
        is_confirm('Удаление','Вы действительно хотите удалить проект?',function(){
            conect({
                url:'class/file.class.php?action=del',
                post:{name:name},
                action:function(){
                    $('.li_prj_'+name).slideUp();
                    
                    if(file.name == name) file.name = '';
                },
                nodisplay:true
            });
        });
    }
    
    this.allPrj = function(){
        in_ce.open('class/file.class.php?action=allPrj',{post:{name:name},name:'Все проекты',scroll:true});
    }
    this.compilerTime = function(){
        
        newhashedit = Math.floor(Math.random() * (99999999 - 1111111 + 1)) + 1111111;
        
        conect({
            url:'cache/'+user_name_var+'/compSt.data?i='+newhashedit,
            action:function(data){
                setTimeout(function(){
                    if(!file.stopTime){
                        $('#resultCompiler').html(data);
                        initScroll('comp',{height:layer.conteiner - 44,update:'bottom'});
                        file.compilerTime();
                    }
                },1000);
            },
            nodisplay:true
        });
    }
    this.compiler = function(){
        
        if(file.name == ''){
            is_promt('Сохранение','Введите название нового проекта','',function(name){
                file.saveNew(name);
            });
            return;
        }
        file.stopTime = false;
        
        $('#resultCompiler').html('<img src="lib/images/compiler.gif" />');
        $('.compilerConteiner').fadeIn(200).unbind('click');
        
        initScroll('comp',{height:layer.conteiner - 44});
        
        conect({
            url:'class/file.class.php?action=compiler',
            post:{name:file.name},
            action:function(data){
                
                setTimeout(function(){
                    file.stopTime = true;
                
                    show_error('Компиляция завершина');
                },2000);
                
                $('.compilerConteiner').bind('click',function(){
                    $(this).fadeOut(200);
                });
            },
            nodisplay:true
        });
        
        file.compilerTime();
    }
    this.web_lide_add = function(){
        in_ce.open('class/file.class.php?action=web_lide_add_new',{name:'Добовление шаблона в WEB.LIDE',btn:{'Добавить':"libraly.addCat('"+name+"')"},scroll:true});
    }
}