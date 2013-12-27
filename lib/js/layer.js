var layer = new layer_manager();

function layer_manager(){
    this.all_block = 0;
    this.conteiner = 0;
    this.arr_block = ['.TopMenu','.PrewDonwload','.myFilesTab','.footer'];
    this.w = 0;
    
    this.init = function(type,resize){
        
        layer.all_block = 0;
        
        $.each(layer.arr_block,function(k,v){
            layer.all_block += $(v).height();
        })
        
        layer.all_block += 12;
        
        win = $(window).height();
        
        layer.conteiner = win - layer.all_block;
        
        $('.globalContent').height(layer.conteiner);
        
        wGc = $('.visualConteiner').width();
        
        if(wGc == 0) wGc = layer.w;
        else layer.w = wGc;
        
        $('.buldImagesContent').width(wGc);
        $('.buldImagesInfo').height(layer.conteiner - 44);
        
        if(!iframe.fS){
            $('#result_sourceView,#result_sourcePrew').width(wGc).height(layer.conteiner - 44).css({overflow:'auto'});
            $('.visualConteiner').height(layer.conteiner - 44).find('.visualGrid').css({minHeight:layer.conteiner - 42,minWidth:wGc});
        }
        else{
            $('#result_sourceView,#result_sourcePrew').height(win);
            $('.visualConteiner').height(win);
        }
    }
}