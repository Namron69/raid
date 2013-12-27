var image = new image_manager();

function image_manager(){
    var LastDir = '';
    
    this.info = function(elem,arr){
        var arr = $.extend({}, arr);
    
        $('.raidImages li').removeClass('active');
        $(elem).addClass('active');
        
        arrayImages = ['jpg','gif','png'];
        
        goinfo = function(x,y){
            var result = [
                '<div style="padding: 5px">',
                 '<table>',
                  '<tr>',
                   '<td>',arr.name,'</td>',
                   '<td>Дата создания: ',arr.date,'</td>',
                   '<td>Размер: ',arr.size,'</td>',
                  '</tr>',
                  '<tr>',
                   '<td>Файл: "<font style="text-transform: uppercase;">',arr.type,'"</font></td>',
                   (x ? '<td>Размеры: '+x+' X '+y+'</td>' : '<td></td>'),
                   '<td>Директория: ',arr.url,'</td>',
                  '</tr>',
                 '</table>',
                '</div>'
            ].join('');
    
            $('#infoImage').html(result);
        }
        
        if($.inArray(arr.type,arrayImages) > 0){
            pic     = new Image();
            pic.src = arr.url;
                            
            pic.onload = function(){
                var x = pic.width;
                var y = pic.height;
                
                goinfo(x,y);
            }
        }
        else{
            goinfo();
        }
    }
    
    this.del = function(hash,name){
        is_confirm('Удаление','Вы действительно хотите удалить директорию/файл?',function(){
            $('#image_'+hash).remove();
            conect({
                url:'class/image.class.php?action=del&name='+name,
                post:{dir:image.LastDir},
                nodisplay:true
            })
        });
    }
    this.ziped = function(){
        conect({
            url:'class/image.class.php?action=ziped',
            post:{dir:image.LastDir},
            action: function(){
                image.update();
            }
        })
    }
    this.edit = function(name){
        conect({
            url:'class/image.class.php?action=edit&name='+name,
            post:{dir:image.LastDir,name:name},
            action: function(data){
                var data = scroll('<div class="textarea_c"><textarea onkeyup="libraly.obj_area_key(event);" style="overflow: hidden;" id="obj_area_id" spellcheck="false" class="textarea_scrin">'+data+'</textarea></div>','objListX372663')
    
                in_ce.open('',{name:'Значение',btn:{'Сохранить':"image.save_edit('"+name+"')",'В Windows-1251':"image.convert('"+name+"',true)",'В UTF-8':"image.convert('"+name+"')"},data:data});
                initScroll('objListX372663',{height: in_ce.i_height});
                libraly.obj_area_init();
            },
            nodisplay:true
        })
    }
    this.save_edit = function(name){
        var data = $('#obj_area_id').val();
        conect({
            url:'class/image.class.php?action=save_edit&name='+name,
            post:{dir:image.LastDir,name:name,data:data},
            nodisplay:true
        })
    }
    this.convert = function(name,convert){
        conect({
            url:'class/image.class.php?action=convert&name='+name,
            post:{dir:image.LastDir,name:name,p:(convert ? 1 : 0)},
            action: function(){
                image.edit(name)
            },
            nodisplay:true
        })
    }
    this.createDir = function(){
        is_promt('Название','Введите название директории','',function(dirname){
            conect({
                url:'class/image.class.php?action=createDir',
                post:{dir:image.LastDir,dirname:dirname},
                action:function(){
                    image.update();
                },
                nodisplay:true
            })
        })
    }
    
    this.dialog = function(){
        var result = [
                
                '<div id="Buttons">',
                 '<span id="UploadPhotos">',
                  '<input type="button" id="Progress" /><i id="fAddPhotos"></i><input type="button" id="AddPhotos" value="Загрузить" />',
                 '</span>',
                '</div>'
                
        ].join('');
        
        w_api.modal('Загрузка',result,{height: 70,width: 200});
        
        BindSWFUpload();
    }
    
    this.update = function(dir){
        
        image.LastDir = (dir) ? dir : image.LastDir;
        
        conect({
            url:'class/image.class.php?action=update',
            post:{dir:image.LastDir},
            action:function(data){
                $('.raidImages').html(data);
                initScroll('images',{height:layer.conteiner - 44,update:'relative'});
            },
            nodisplay:true
        })
    }
}