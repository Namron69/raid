<?

function sourceView(){
    global $openPrj;
    
    $o .= '<div class="fullScreenIframe">';
    $o .= '<div class="position_r" style="background: url(lib/images/grid_21.png);">';
    $o .= '<div class="visualConteiner" type="sourceView">'."\n";
    $o .= '  <iframe src="class/iframeResult.php'.($openPrj ? '?prj='.$openPrj : '').'" id="result_sourceView" name="result_sourceView" width="200" height="200" style="border: 0;" onmouseover="$(\'.iframeTools\').fadeIn(200)"></iframe>';
    $o .= ' <div class="position top left width height selectReObj" onclick="$(this).fadeOut(200);">';
    $o .= '  <ul class="position_a top_50 left_50 ul">';
    $o .= '  <li onclick="iframe.selectReObj(1)"><img src="lib/images/obj/ico_03.png" />Снаружи</li>';
    $o .= '  <li onclick="iframe.selectReObj(2)"><img src="lib/images/obj/ico_05.png" />Внутри</li>';
    $o .= '  <li onclick="iframe.selectReObj(3)"><img src="lib/images/obj/ico_07.png" />Заменить</li>';
    $o .= '  <li onclick="iframe.selectReObj(4)"><img src="lib/images/obj/ico_09.png" />Внутри после</li>';
    $o .= '  <li onclick="iframe.selectReObj(5)"><img src="lib/images/obj/ico_11.png" />Внутри перед</li>';
    $o .= '  </ul>';
    $o .= ' </div>';
    $o .= ' <div class="position top right height hide libReObj">';
    $o .= ' <div class="position top height shad"></div>';
    $o .= ' <div class="position height close" onclick="$(this).parent().fadeOut(200)"></div>';
    $o .= scroll('<div id="resultReObj"></div>','reobj');
    $o .= ' </div>';
    $o .= ' </div>';
    $o .= '  <ul class="position_a ul iframeTools hide">';
    $o .= '  <li onclick="iframe.back_point()"><img src="lib/images/back_03.png" /></li>';
    $o .= '  <li onclick="iframe.fun(\'reload\',{prj:file.name});"><img src="lib/images/reload_06.png" /></li>';
    $o .= '  <li onclick="iframe.fullScreen(\'.fullScreenIframe\');"><img src="lib/images/full_scrin.png" /></li>';
    $o .= '  <li onclick="iframe.fun(\'showAllHidden\');"><img src="lib/images/show_all.png" /></li>';
    $o .= '  </ul>';
    
    
    $o .= ' <div class="compilerConteiner hide">';
    $o .= ' <div class="position top right height width c_bg">';
    $o .= ' </div>';
    
    $o .= ' <div class="position_f top right height width compilerStatus">';
    $o .= ' </div>';
    
    $o .= ' <div class="position top right height width c_cont">';
    $o .= scroll('<div id="resultCompiler"><img src="lib/images/compiler.gif" /></div>','comp');
    $o .= ' </div>';
    $o .= ' </div>';
    
    $o .= '</div>';
    $o .= '</div>'."\n";
    
    return $o;
}
function raidPrew(){
    global $openPrj;
    
    $o .= '<div class="fullScreenIframePrew">';
    $o .= '<div class="position_r" style="background: url(lib/images/grid_21.png);" id="PrewGF">';
    $o .= '<div class="visualConteiner addIframe">'."\n";
    
    $o .= ' </div>';
    $o .= '  <ul class="position_a ul iframeTools hide">';
    $o .= '  <li onclick="updatePrewIf(true)"><img src="lib/images/prev_download.png" /></li>';
    $o .= '  <li onclick="updatePrewIf()"><img src="lib/images/reload_06.png" /></li>';
    $o .= '  <li onclick="iframe.fullScreen(\'.fullScreenIframePrew\');"><img src="lib/images/full_scrin.png" /></li>';
    $o .= '  </ul>';
    
    $o .= '</div>'."\n";
    /*
    $o .= '<div class="selectEngine">'."\n";
    $o .= ' <ul class="imagesUi">'."\n";
    $o .= '  <li onclick="loadPrewEngine(\'sl\')"><div><table><tbody><tr><td valign="center"><img src="lib/images/engine/sl.png"></td></tr></tbody></table></div>SL SYSTEM</li>'."\n";
    $o .= '  <li onclick="loadPrewEngine(\'dle\')"><div><table><tbody><tr><td valign="center"><img src="lib/images/engine/dle.png"></td></tr></tbody></table></div>DLE</li>'."\n";
    $o .= ' </ul>'."\n";
    $o .= '</div>';
    */
    $o .= '</div>'."\n";
    
    return $o;
}
function cur($code,$more = ''){
    return preg_replace("'[^$more]'si",'',$code);
}
function scroll($data,$id = ''){
	
    $o .= '<div class="scrollbarContent" id="scroll_'.$id.'">'."\n";
    $o .= ' <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>';
    $o .= ' <div class="viewport">';
    $o .= '  <div class="overview">';
    $o .= '   '.$data;
    $o .= '  </div>'."\n";
    $o .= ' </div>'."\n";
    $o .= '</div>'."\n";
    
    return $o;
}

function buld_input($name,$value,$style = ''){
    return '<input type="text" class="buld_input" spellcheck="false" value="'.$value.'" name="'.$name.'" '.$style.' />'."\n";
}

function FSize($file, $setup = null){
    $FZ = ($file && @is_file($file)) ? filesize($file) : NULL;
    $FS = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
    if($FZ > 0){
    if(!$setup && $setup !== 0)
    {
        return number_format($FZ/pow(1024, $I=floor(log($FZ, 1024))), ($i >= 1) ? 2 : 0) . ' ' . $FS[$I];
    } elseif ($setup == 'INT') return number_format($FZ);
    else return number_format($FZ/pow(1024, $setup), ($setup >= 1) ? 2 : 0 ). ' ' . $FS[$setup];
    }
    else{
        return 0;
    }
}

function FSizeNumber($size){
    
    $format = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
    
    if($size > 0){
        return number_format($size/pow(1024, $I=floor(log($size, 1024))), ($i >= 1) ? 2 : 0) . ' ' . $format[$I];
    } 
    else{
        return 0;
    }
}

function showDate($url){
    $stat = @stat($url);
    return @date("F d y H:i:s", $stat[9]);
}

function my_fileBuld($is_dir){
    
    if(is_dir($is_dir)){
        $handle = @opendir( $is_dir );
    
    	while ( false !== ($file = @readdir( $handle )) ) {
    
    		if( @is_dir( $is_dir .DIRECTORY_SEPARATOR.$file ) and ($file != "." and $file != "..") ) {
    			  
                $c_files['dir'][$file] = $file;
    			
    		}elseif($file != "." and $file != ".."){
    		    $c_files['file'][$file] = $file;
    		}
    	}
       @closedir($handle);
   }
   
   if(count($c_files['dir']) == 0)  $c_files['dir']  = array();
   if(count($c_files['file']) == 0) $c_files['file'] = array();
   
   return $c_files;
}

function sizeFolder($dir){
    $file = my_fileBuld($dir);
    
    $size = 0;
    
    foreach($file['file'] as $name){
        $size += @filesize($dir.DIRECTORY_SEPARATOR.$name);
    }
    
    return $size;
}

function buldImages(){

    $img .= '<div class="buldImagesContent">'."\n";
    $img .= ' <ul class="imagesUi raidImages">'."\n";
    $img .= ' </ul>'."\n";
    $img .= '</div>'."\n";
    
    $img = '<div class="position_r buldImagesInfo">'.scroll($img,'images').'<div class="position bottom left width" style="background: #212121" id="infoImage"></div></div>';
    
    return $img;
}

function myProject(){
    
    global $is_logged;
    
    $file = my_fileBuld(THIS_DIR.'/userData/userProject/'.$is_logged);
    
    $allowed = array('prj');
    
    if(count($file['dir']) > 0){
        
        foreach($file['dir'] as $name){
            $prj .= '<li class="li_prj_'.$name.'"><span onclick="file.open(\''.$name.'\')">'.$name.'</span><b class="del" onclick="file.del(\''.$name.'\')"></b></li>';
        }

        return '<ul class="ul myProject">'.$prj.'</ul>';
    }
    else{
        return '<div id="myProject">Вы еще не создали ни один проект</div>';
    }
}

function lastPrj(){
    global $is_logged,$openPrj;
    
    @mkdir(ROOT_DIR.'/userData/userHash/'.$is_logged);
    
    $jsonFile = ROOT_DIR.'/userData/userHash/'.$is_logged.'/last.prj';
    
    $json = @file_get_contents($jsonFile);
    
    $jsonArr = json_decode($json);
    
    if($openPrj){
        $p = count($jsonArr);
        
        if(@end($jsonArr) !== $openPrj && $p >= 10){
            array_shift($jsonArr);
            $jsonArr[] = $openPrj;
        }
        elseif(@end($jsonArr) !== $openPrj){
            $jsonArr[] = $openPrj;
        }
        
        @file_put_contents($jsonFile,json_encode($jsonArr));
    }
    
    if(count($jsonArr) > 0){
        
        foreach($jsonArr as $name){
            $prj .= '<li><span onclick="file.open(\''.$name.'\')">'.$name.'</span><b></b></li>';
        }
        $result = '<ul class="ul rideOr">'.$prj.'</ul>';
    }
    else{
        $result = 'Проекты еще не открывались';
    }
    
    return $result;
}

function lastBuldLib(){
    global $db,$is_logged;
    
    $read = my_fileBuld(ROOT_DIR.'/data/'.$is_logged.'/');
    
    foreach($read['dir'] as $name){
        $cat = my_fileBuld(ROOT_DIR.'/data/'.$is_logged.'/'.$name.'/');
        $li .= '<li class="blib_'.$name.' blib_select"><span>'.substr($name,0,14).'</span><b uplName="'.$name.'"></b></li>';
    }
    if(count($read['dir']) == 0) $li .= '<li class="nolib" onclick="libraly.showLibraly()"><span>У вас нет не одной библиотеки</span></li>';
    return $li;
}
function html_css($path = '',$arr = array()){
    
    if(is_array($arr)){
        foreach($arr as $val){
            $output .= '<link rel="stylesheet" href="'.$path.'/'.$val.'.css" type="text/css" />'."\n";
        }
    }
    else    $output .= '<link rel="stylesheet" href="'.$path.'/'.$arr.'.css" type="text/css" />'."\n";
    
    return  $output;
    
}

function html_js($path = '',$arr = array()){
    
    if(is_array($arr)){
        foreach($arr as $val){
            $output .= '<script type="text/javascript" src="'.$path.'/'.$val.'.js"></script>'."\n";
        }
    }
    else    $output = '<script type="text/javascript" src="'.$path.'/'.$arr.'.js"></script>'."\n";
    
    return  $output;
}
function hashs($name = false){
    $res   = ($name) ? md5($name): md5(rand(0,99999));
    $start = rand(0,20);
    $end   = $start + 5;
    return   substr($res,$start,$end);
}
function replase($code,$more = ''){
    return preg_replace("'[^a-z0-9_$more]'si",'',$code);
}
function convert_ch($var,$type = ''){
    $type = ($type != '') ? $type : 'default';
    
    if($type == 'default'){
        return iconv('UTF-8', 'windows-1251', $var);
    }
    else{
        return iconv('windows-1251', 'UTF-8', $var);
    }
    
}
function show_info($type,$data = false){
    switch ($type){
        case 'info':   print "seting = {info:'".$data."'}";
	    break;

	    case 'sesion': print "seting = {sesion:'true'}";
	    break;
        
        case 'status': print "seting = {status:true}";
	    break;
        
        default : exit;
    }
    exit;
}
function RemoveDir($path){
	if(@file_exists($path) && is_dir($path))
	{
		$dirHandle = @opendir($path);
		while (false !== ($file = @readdir($dirHandle))) 
		{
			if ($file!='.' && $file!='..')
			{
				$tmpPath=$path.'/'.$file;
				@chmod($tmpPath, 0777);
				
				if (is_dir($tmpPath))
	  			{   
					RemoveDir($tmpPath);
			   	} 
	  			else 
	  			{ 
	  				if(file_exists($tmpPath))
					{
	  					@unlink($tmpPath);
					}
	  			}
			}
		}
		@closedir($dirHandle);
		
		if(file_exists($path))
		{
			if(@rmdir($path))
            return 'delete';
            else
            return 'no_delete';
		}
	}
	else
	{
		return 'no_folder';
	}
}
function buld_array_reverse($array){
    
    $reverse = array();
    $allKey = array();
    
    foreach($array as $key=>$arr){
        $allKey[] = $key;
    }
    
    $allKey = array_reverse($allKey);
    
    foreach($allKey as $key){
        $reverse[$key] = $array[$key];
    }
    
    return $reverse;
}
function buld_compress($html){
    $html = preg_replace('/\s+/', ' ', $html);
    $html = str_replace("\n", '', $html);
    return $html;
}
function buldFleWrite(){
    $folder = array(
        'cache',
        'data',
        'userData'
    );
    foreach($folder as $name){
        if(!is_writable(ROOT_DIR.'/'.$name)){
            $error .= './'.$name.' Запись запрещена!<br />';
        }
    }
    
    if($error) return "setTimeout(function(){warning('".$error."')},3000);";
}
function login_alt($login){
    return preg_replace("'[^a-z0-9]'si",'_',$login);
}
?>