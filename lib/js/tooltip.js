function tooltip(id,title){
    
        var in_i = $('#tip');

        if(!in_i.length){
        
        var in_i = [
            '<div id="tip" style="opacity:0;z-index:998"><div><div><div id="tip_inner">'+title+'</div></div></div></div>'
	        ].join('');
        $(in_i).appendTo('body');

        }

        var offset = $(id).offset();
		var tLeft  = offset.left;
		var tTop   = offset.top;
        
        var wOffset  = $(id).width();

        
        var $this    = $('#tip');
        var tip      = $($this);
        var tipInner = $('#tip_inner');
        
		var tWidth   = $this.width();
		var tHeight  = $this.height();
        
        showTip = function(){
			tip.stop().show().animate({"top": "+=20px", "opacity": "1"}, 200);
		}
                
        setTip = function(top, left){
			var topOffset = tip.height();
            var witOffset = tip.width();
          
            var main = $(document).width();

			var xTip = ((left + (wOffset/2)) - (witOffset/2)-4);
			var yTip = (top-topOffset-20);

            xleft = (xTip < 0) ? xTip+(witOffset/2) : (((main - witOffset) < xTip) ? xTip - (witOffset/2) : xTip);
            
            xb    = (xTip < 0) ? "0 100%" : (((main - witOffset) < xTip) ? '100% 100%' : '50% 100%');
            
            if(yTip < 0){
                yTip = 30;
                tipInner.css({background:'none'});
            }
            else{
                tipInner.css({backgroundPosition:xb});
            } 
            
			tip.css({'top' : yTip, 'left' : xleft});
            

		}
		
		tipInner.html(title);
        setTip(tTop, tLeft);
        showTip();
        
        
}
function tooltip_hide(){
    $('#tip').stop().animate({"top": "-=20px", "opacity": "0"}, 200,function(){
        $(this).hide();
    });
}

$('[tooltip]').live('mouseover mouseout',function(event){
    if (event.type == 'mouseover'){
        tooltip(this,$(this).attr('tooltip'));
    }
    else{
        tooltip_hide();
    }
});
$('#tip').live('mouseover',function(){
    tooltip_hide();
})