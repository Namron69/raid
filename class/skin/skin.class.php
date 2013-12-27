<?php

//================================//
// SKIN_CLASS                     //
//================================//

class skin{

    var $num       = 0;
    var $loading   = false;
    var $skin      = array();
    var $setings   = array();
    var $row       = 1;

function skin(){
    global $config,
           $skin_global,
           $skin_separator,
           $skin_echoauth,
           $skin_button,
           $skin_input,
           $skin_select,
           $skin_tabs,
           $skin_row_table,
           $skin_table,
           $skin_textarea,
           $skin_row_name,
           $skin_info,
           $skin_file;
    
    $this->setings           = $config;
    $this->skin['skin']      = $skin_global;
    $this->skin['auth']      = $skin_echoauth;
    $this->skin['separator'] = $skin_separator;
    $this->skin['button']    = $skin_button;
    $this->skin['input']     = $skin_input;
    $this->skin['textarea']  = $skin_textarea;
    $this->skin['select']    = $skin_select;
    $this->skin['tabs']      = $skin_tabs;
    $this->skin['table']     = $skin_table;
    $this->skin['row']       = $skin_row_table;
    $this->skin['row_name']  = $skin_row_name;
    $this->skin['info']      = $skin_info;
    $this->skin['file']      = $skin_file;
}

function replase($name,$out,$content){
    
    return $content = preg_replace( $name, $out, $content );
    
}

function theme($content){
    
    return $content = preg_replace( "/{THEME}/", 'skins/'.$this->setings['admin_skins'], $content );
    
}

function separator(){

	return $this->theme( $this->skin['separator'] );

}

function css($url,$ar,$callback = false){
    
    if(is_array($ar)){
        foreach($ar as $val){
            $css .= '<link rel="stylesheet" href="'.$url.'/'.$val.'.css" type="text/css" />'."\n";
        }
    }
    else{
        $css = '<link rel="stylesheet" href="'.$url.'/'.$ar.'.css" type="text/css" />'."\n";
    }

    if($callback)
       return $css;
    else 
       print $css;
}

function js($url,$ar,$callback = false){
    
    if(is_array($ar)){
        foreach($ar as $val){
            $js .= '<script src="'.$url.'/'.$val.'.js" type="text/javascript"></script>'."\n";
        }
    }
    else{
        $js = '<script src="'.$url.'/'.$ar.'.js" type="text/javascript"></script>'."\n";
    }
    
    if($callback)
       return $js;
    else 
       print $js;
}

function color($text,$color){
    return '<span style="color: #'.$color.'">'.$text.'</span>';
}

function ico($ico,$mass = ''){
    
    $array = $this->in_array($mass);
    
    $function    = (isset($array['function']))    ? ' '.$array['function']            : '';
    $position    = (isset($array['position']))    ? ' '.$array['position']            : '';
    $align       = (isset($array['align']))       ? ' align="'.$array['align'].'"'    : '';
    $style       = (isset($array['style']))       ? ' style="'.$array['style'].'"'    : '';

    return '<img src="system/images/ico/'.$ico.'" class="skin_ico'.$position.'"'.$align.$style.$function.' alt="" />';
}
function panel($content,$mass = false){
    
    $array = $this->in_array($mass);
    
    $function    = (isset($array['function']))    ? ' '.$array['function']            : '';
    $position    = (isset($array['position']))    ? ' '.$array['position']            : '';
    $align       = (isset($array['align']))       ? ' align="'.$array['align'].'"'    : '';
    $style       = (isset($array['style']))       ? ' style="'.$array['style'].'"'    : '';
      
    return '<div class="mini_panel'.$position.'"'.$style.$function.'><div class="mini_panel_2"><div class="mini_panel_3"><div class="mini_panel_4"'.$align.'>'.$content.'</div></div></div></div>';
}

function info($info){
    $result = $this->replase("/{name}/",$info,$this->skin['info']);
    return    $this->replase("/{THEME}/",'skins/'.$this->setings['admin_skins'],$result);
}

function in_array($ar){
    if($ar == '')
       return array();
    else{
        $ar = $ar.',';
        if(preg_match_all("'\[(.*?)\],'si",$ar,$mass)){
        foreach($mass[1] as $value){

            $replase = explode('::',$value);
            $array[$replase[0]] = $replase[1];
        }
        return $array;
        }
    }
}

function buld_array_table($ar){
    if($ar == '')
       return array();
    else{
        $ar = $ar.',';
        if(preg_match_all("'\[(.*?)\],'si",$ar,$mass)){
        
        foreach($mass[1] as $value){

            $replase = explode('::',$value);            
            $val = explode('=>',$replase[1]);
            $array[$replase[0]-1][$val[0]] = $val[1];
        }
        return $array;
        }
    }
}

function offset($mass,$content){
    $array = $this->in_array($mass);
    
    $float    = $array['position']     ? $array['position'] : 'right';
    $offset   = $array['offset']       ? $array['offset']   : false;
    $function = $array['function']     ? ' '.$array['function'] : '';
    
    switch ($float){ 
	case 'right': if($offset) $margin = 'margin-right: '.$offset.'px;';
	break;

	case 'left':  if($offset) $margin = 'margin-left: '.$offset.'px;';
	break;
    }
    
    $style = $margin ? ' '.$array['style'] : ($array['style'] ? $array['style'] : '');
    
    $content = $this->replase("/{style}/",$margin.$style,$content);
    $content = $this->replase("/{position}/",' '.$float,$content);
    $content = $this->replase("/{function}/",$function,$content);
    
    return $content;
}

function main($ar){
    global $config;
    
    $this->skin['skin'] = $this->theme( $this->skin['skin'] );
    
    if(is_array($ar)){
        foreach($ar as $key => $val){
            $this->skin['skin'] = $this->replase("/{".$key."}/",$val,$this->skin['skin']);
        }
    }
    
    $this->skin['skin'] = preg_replace("'{js=[\'\"](.+?)[\'\"]}'si","<script src=\"skins/".$this->setings['admin_skins']."/\\1\" type=\"text/javascript\"></script>",$this->skin['skin']);
    
    $this->skin['skin'] = preg_replace("'{css=[\'\"](.+?)[\'\"]}'si","<link rel=\"stylesheet\" href=\"skins/".$this->setings['admin_skins']."/\\1\" type=\"text/css\" />",$this->skin['skin']);
    
    print eval(content_eval($this->skin['skin']));
}


function auth($ar) {
    
    global $config;
    
	$this->skin['auth'] = $this->theme( $this->skin['auth'] );
    
    if(is_array($ar)){
        foreach($ar as $key => $val){
            $this->skin['auth'] = $this->replase("/{".$key."}/",$val,$this->skin['auth']);
        }
    }
    
    $this->skin['auth'] = preg_replace("'{js=[\'\"](.+?)[\'\"]}'si","<script src=\"skins/".$this->setings['admin_skins']."/\\1\" type=\"text/javascript\"></script>",$this->skin['auth']);
    
    $this->skin['auth'] = preg_replace("'{css=[\'\"](.+?)[\'\"]}'si","<link rel=\"stylesheet\" href=\"skins/".$this->setings['admin_skins']."/\\1\" type=\"text/css\" />",$this->skin['auth']);
    
    print $this->skin['auth'];
}


function button($mass){
    
    $array = $this->in_array($mass);
    
    $button = $this->offset($mass,$this->skin['button']);
    
    $button = $this->replase("/{name}/",$array['name'],$button);
    
    return $button;
}

function input($mass){
    
    $array = $this->in_array($mass);
    
    $input = $this->offset($mass,$this->skin['input']);
    
    $type = ($array['type'])   ? $array['type'] : 'text';
    $class = ($array['class']) ? 'class="'.$array['class'].'"' : '';
    
    if($type == 'file') return '';
    
    $input = $this->replase("/{name}/" ,$array['name'] ,$input);
    $input = $this->replase("/{value}/",$array['value'],$input);
    $input = $this->replase("/{type}/",$type,$input);
    $input = $this->replase("/{class}/",$class,$input);

    return $input;
}

function file($mass){
    
    $array = $this->in_array($mass);
    
    $file = $this->offset($mass,$this->skin['file']);

    $class = ($array['class']) ? 'class="'.$array['class'].'"' : '';
    
    $file = $this->replase("/{name}/" ,$array['name'] ,$file);
    $file = $this->replase("/{class}/",$class,$file);

    return $file;
}

function textarea($mass = ''){
    $array = $this->in_array($mass);
    
    $textarea = $this->offset($mass,$this->skin['textarea']);
    
    $rows = (isset($array['rows'])) ? ' rows="'.$array['rows'].'"' : '';
    $cols = (isset($array['cols'])) ? ' cols="'.$array['cols'].'"' : '';
    
    $textarea = $this->replase("/{name}/" ,$array['name'] ,$textarea);
    $textarea = $this->replase("/{value}/",$array['value'],$textarea);
    $textarea = $this->replase("/{cols}/",$cols,$textarea);
    $textarea = $this->replase("/{rows}/",$rows,$textarea);
    
    return $textarea;
}

function width($i,$w){
        return (($i*5) + $w).'px';
    }
    
function select($mass,$option = array(),$fun = ''){
    
    $array  = $this->in_array($mass);
    $farray = $this->in_array($fun);
    
    $select = $this->offset($mass,$this->skin['select']);
    
    $id = hashs();
    
    $select = $this->replase("/{id}/" ,$id ,$select);
    
    $select = $this->replase("/{name}/" ,$array['name'] ,$select);
    
    if(preg_match("'\[option\](.*?)\[\/option\]'si",$this->skin['select'],$shtml)){

        $set_option = true;
    }
    $width = 0;
    
    foreach($option as $key => $val){
        $output .= "<option value=\"$key\"";
			if( $key == $array['value'] ) {
				$output .= " selected ";
			}
	    $output .= ">$val</option>\n";
        
        if($set_option){
            
            $width = max($width,strlen($val));
            
            $res_option = $this->replase("/{name}/" ,$val,$shtml[1]);
            $res_option = $this->replase("/{value}/" ,$key,$res_option);
            $res_option = $this->replase("/{id}/" ,$id,$res_option);

            if( $key == $array['value'] )
                $res_option = $this->replase("/{selected}/" ,'selected',$res_option);
            else 
                $res_option = $this->replase("/{selected}/" ,'',$res_option);
            
            if($farray[$key]) $res_option = $this->replase("/{function}/" ,' '.$farray[$key],$res_option);
            else              $res_option = $this->replase("/{function}/" ,'',$res_option);
            
            $res_result .= $res_option;
            
        }
    }
    
    $select = preg_replace("#\{width=(.*?)\}#sie","\$this->width($width,'\\1')",$select);
    $select = $this->replase("/{option}/",$output,$select);
    $select = $this->replase("'\[option\](.*?)\[\/option\]'si" ,'',$select);
     
    if($set_option and (count($option) > 0)){
        $select = $this->replase("#\[list\](.*?)\[\/list\]#si" ,"\\1",$select);
        $select = $this->replase("/{list}/" ,$res_result,$select);
        if($option[$array['value']]){
            $select = $this->replase("/{value_name}/",$option[$array['value']],$select);
            $select = $this->replase("/{value}/",$array['value'],$select);
        }
        else{
            foreach($option as $keys => $vals){
                $r_name = $vals;
                $r_key = $keys;
                break;
            }
            $select = $this->replase("/{value_name}/",$r_name,$select);
            $select = $this->replase("/{value}/",$r_key,$select);
        }
        
    }
    else{
        $select = $this->replase("#\[list\](.*?)\[\/list\]#si" ,"",$select);
        $select = $this->replase("/{value_name}/",'',$select);
        $select = $this->replase("/{value}/",'',$select);
    }
    
    
    return $select;
}

function checkbox($mass) {
    
    $array = $this->in_array($mass);
    
    $float  = $array['position'] ? $array['position'] : 'right';
    $offset = $array['offset']   ? $array['offset']   : false;

    switch ($float){ 
	case 'right': if($offset) $margin = 'margin-right: '.$offset.'px';
	break;

	case 'left':  if($offset) $margin = 'margin-left: '.$offset.'px';
	break;
    }
    
    $function = (isset($array['function'])) ? ' '.str_replace('"','\'',$array['function']) : '';
    $class = (isset($array['class'])) ? ' '.str_replace('"','\'',$array['class']) : '';

	if($array['value'] == 'yes')
	      return '<div class="checkbox check '.$float.$class.'" onclick="checkbox(this);'.$function.'" style="'.$margin.'"><input type="hidden" name="'.$array['name'].'" value="yes" checked="checked" /></div>';
	
	else
	      return '<div class="checkbox '.$float.$class.'" onclick="checkbox(this);'.$function.'" style="'.$margin.'"><input type="hidden" name="'.$array['name'].'" value="no" checked="none" /></div>';

}

function tabs($num,$mass = false,$option = array(),$function = false){
    
    $num  = intval($num);
    
    $hash = hashs();
    
    $name   = $this->in_array($mass);
    $function = $this->in_array($function);
    
    for ($i = 1;$num >= $i;$i++) {
        if(preg_match("#\[li\](.*?)\[\/li\]#si",$this->skin['tabs'],$html)){
            $res = $this->replase("/{name}/",$name[$i],$html[1]);
            if($i == 1)
                 $res = $this->replase("/{class}/",'active',$res);
            else 
                 $res = $this->replase("/{class}/",'',$res);
            
            $res = $this->replase("/{id}/",$i,$res);
            $res = $this->replase("/{hash}/",$hash,$res);
            
            $i_fun = ($function[$i]) ? ';'.$function[$i] : '';
            
            $res = $this->replase("/{function}/",$i_fun,$res);
            $output .= $res;
        }
        if(preg_match("#\[content\](.*?)\[\/content\]#si",$this->skin['tabs'],$cbi)){
            $res_c = $this->replase("/{content}/",$option[$i],$cbi[1]);
            $res_c = $this->replase("/{id}/",$i,$res_c);
            $res_c = $this->replase("/{hash}/",$hash,$res_c);
            
            if($i == 1)
                 $res_c = $this->replase("/{class}/",'tabs_active',$res_c);
            else 
                 $res_c = $this->replase("/{class}/",'',$res_c);
                 
            $content .= $res_c;
        }
    }
    
    $tabs = $this->replase("#\[li\](.*?)\[\/li\]#si" ,$output,$this->skin['tabs']);
    $tabs = $this->replase("#\[content\](.*?)\[\/content\]#si" ,$content,$tabs);
    
    return $tabs;
}

function table($num,$content = false,$option = array(),$function = ''){
    
    $num  = intval($num);
    
    if($num > 0){

        for ($i = 0;$num > $i;$i++) {
            if(preg_match("#\[td\](.*?)\[\/td\]#si",$this->skin['table'],$html)){
                
                if($option[$i]['name']){
                    
                    $res     = $this->replase("/{name}/",$option[$i]['name'],$html[1]); $f ++;
                
                }
                else
                    $res     = $this->replase("/{name}/",'',$html[1]);
                
                
                if($option[$i]['width'] and $option[$i]['width'] !== '')
                
                    $res     = $this->replase("/{width}/",'width="'.$option[$i]['width'].'"',$res);
                    
                else
                
                    $res     = $this->replase("/{width}/",'',$res);
                    
                $output .= $this->replase("/{colspan}/",intval($option[$i]['colspan']),$res);
   
            }
        }
        
        $table = $this->replase("#\[td\](.*?)\[\/td\]#si" ,$output,$this->skin['table']);
        $table = $this->replase("/{content}/",$content,$table);
        
        if($f)
               $table = preg_replace("#\[name\](.*?)\[\/name\]#si","\\1",$table);
        else 
               $table = preg_replace("#\[name\](.*?)\[\/name\]#si","",$table);
    }
    else{
        
        $table = $this->replase("#\[name\](.*?)\[\/name\]#si" ,'',$this->skin['table']);
        $table = $this->replase("/{content}/",'',$table);
    }
    
    $function = ($function != '') ? ' '.$function : '';
    
    $table = $this->replase("/{function}/",$function,$table);
    
    return $table;
}

function replase_row($content,$option,$id,$html){
                
                $res         = $this->replase("/{content}/",$content,$html);
                
                if($option['width'] and $option['width'] !== '')
                    $res     = $this->replase("/{width}/",'width="'.$option['width'].'"',$res);
                else
                    $res     = $this->replase("/{width}/",'',$res);
                    
                    $res     = $this->replase("/{id}/",$id,$res);
                    
                if($option['valign']) $res     = $this->replase("/{valign}/",' valign="'.$option['valign'].'"',$res);
                else                  $res     = $this->replase("/{valign}/",'',$res);
                
                if(intval($option['colspan']) > 0){
                    $res = $this->replase("/{colspan}/",' colspan="'.intval($option['colspan']).'"',$res);
                }
                else{
                    $res = $this->replase("/{colspan}/",'',$res);
                }
                
                return $res;
}

function row($num,$content = array(),$option = array(),$function = '',$tr = true){
    
    $num  = intval($num);
    $row  = 1;
    if($num > 0){
        
        @preg_match("#\[td\](.*?)\[\/td\]#si",$this->skin['row'],      $start_html);

        for ($i = 0;$num > $i;$i++) {
                
                $output .= $this->replase_row($content[$i],$option[$i],$i,$start_html[1]);

        }
           
            if($this->row > 2){
                    $this->row = 1;
            }
            
            $function = ($function != '') ? ' '.$function : '';
                
            $res = $this->replase("/{row}/" ,$this->row,$this->skin['row']);
            $res = $this->replase("/{function}/" ,$function,$res);
            
            if(!$tr){
                $res = $this->replase("#<tr[^>]*>#si" ,'',$res);
                $res = $this->replase("#</tr>#si" ,'',$res);
            }
            
            
            $this->row ++;
        
            return $this->replase("#\[td\](.*?)\[\/td\]#si" ,$output,$res);
    }
}

function row_name($num,$name,$description = '',$mass = false){
    
    $num  = intval($num);
    
    $option = $this->in_array($mass);
    
    $function = ($option['function'] !== '') ? ' '.$option['function'] : '';
    
    if($this->row > 2){
       $this->row = 1;
    }
            
    $row_name = $this->replase("/{content}/",$name,$this->skin['row_name']);
    $row_name = $this->replase("/{function}/",$function,$row_name);
    $row_name = $this->replase("/{row}/" ,$this->row,$row_name);
    $row_name = $this->replase("/{colspan}/",intval($num),$row_name);
    $row_name = $this->replase("/{description}/",$description,$row_name);
    
    if($option['width'] and $option['width'] !== '')
        $row_name     = $this->replase("/{width}/",'width="'.$option['width'].'"',$row_name);
    else
        $row_name     = $this->replase("/{width}/",'',$row_name);
      
    $this->row ++;
      
    return $row_name;

}
}

$skin = new skin;
?>