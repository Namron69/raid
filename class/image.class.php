<?
@session_start ();

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );


define ( 'ROOT_DIR', dirname(dirname(__FILE__)) );
define ( 'THIS_DIR', dirname(__FILE__) );

require_once THIS_DIR . '/function.php';
require_once THIS_DIR . '/login.php';

if(!$is_logged) show_info('sesion');

$is_logged = login_alt($is_logged);

$action = replase($_GET['action']);
$dir = isset($_POST['dir']) && $_POST['dir'] !== '' ? base64_decode($_POST['dir']) : dirname(THIS_DIR).'\userData\userData\\'.$is_logged;
$idName = isset($_GET['name']) ? replase($_GET['name'],'_\.\-') : false;

$dir = str_replace('/','\\',$dir);
$dir = str_replace('\\','/',$dir);

if(!preg_match('/userData/',$dir)) show_info('info','Ошибка: Не верный путь к директории');

@mkdir(ROOT_DIR.'/userData/userProject/'.$is_logged);
@mkdir(ROOT_DIR.'/userData/userData/'.$is_logged);

function bunlerFiles($name,$optionFile = array()){
    global $is_logged,$dir;
    
    
    $allowed = array('jpg','gif','png');
    $ext = @end(explode('.',$name));
    
    $baseDir = basePathLib($dir);
    $baseDir = (substr($baseDir,0,1) == '/') ? substr($baseDir,1,strlen($baseDir)) : $baseDir;
        
    
    $option = array(
        'url'=>$baseDir.'/'.$name,
        'type'=>$ext,
        'size'=>FSize($dir.'/'.$name),
        'name'=>$name,
        'date'=>@date("F d Y H:i:s", @filemtime($dir.'/'.$name))
    );
    
    $hash = rand(111111,9999999);
    
    $option = str_replace('"',"'",json_encode($option));
    
    $image .= '<li onclick="image.info(this,'.$option.')" id="image_'.$hash.'"><div><table><tr><td valign="center">';
    
    
    if(in_array($ext,$allowed)){
        $image .= '<img src="'.$baseDir.'/'.$name.'?i='.rand(0,999999).'" ondblclick="window.open(\''.$baseDir.'/'.$name.'\',\'_blank\')" />';
    }
    elseif($optionFile['folder']){
        $image .= '<img src="lib/images/open_folder.png" ondblclick="image.update(\''.base64_encode($dir.'/'.$name).'\')" />';
    }
    elseif($ext == 'zip'){
        $image .= '<img src="lib/images/zip_ico.png" ondblclick="window.open(\''.$baseDir.'/'.$name.'\',\'_blank\')" />';
    }
    /*
    elseif($ext == 'html'){
        $image .= '<iframe src="'.$baseDir.'/'.$name.'?i='.rand(0,999999).'" width="66" height="66" frameborder="0" scrolling="no"></iframe><div class="position top left width height" onmousemove="iframe.show_big(event,\''.$baseDir.'/'.$name.'?i='.rand(0,999999).'\')" onmouseout="iframe.hide_big()" onclick="iframe.insert_user_code(\''.$baseDir.'/'.$name.'\')" style="background: transparent"></div>';
    }
    */
    else{
        $image .= '<img src="lib/images/text_plain.png" ondblclick="image.edit(\''.$name.'\')" />';
    }
    
    $image .= '</td></tr></table></div>'.substr(str_replace('.'.$ext,'',$name),0,16).'<b class="del" onclick="image.del(\''.$hash.'\',\''.$name.'\')"></b></li>';
    
    return $image;
}

function backFolder($dir = false){
    global $is_logged;
    
    $back = explode("/",$dir);
    
    $lastName = $back[count($back)-1];
    
    unset($back[count($back)-1]);
    
    $back = implode('/',$back);
    
    if($lastName !== $is_logged) return $back;
    else return false;
}

function basePathLib($relative_path) {
    $scriptName =str_replace('/class/image.class.php','',$_SERVER['SCRIPT_NAME']);
    
    $realpath=str_replace('\\','/',realpath($relative_path));
    
    $htmlpath = str_ireplace(str_replace('\\','/',ROOT_DIR),'',$realpath);
    
    return $htmlpath;
}

if($action == 'del'){
    
    
    if(!preg_match('/userData/',$dir)) show_info('info','Ошибка: Не верный путь к директории');
    
    if(is_dir($dir.'/'.$idName)) RemoveDir($dir.'/'.$idName);
    else unlink($dir.'/'.$idName);
    
}
elseif($action == 'ziped'){
    
    if($dir){
        require_once THIS_DIR . '/pclzip.lib.php';
        
        unlink($dir.'/folder.zip');
        
        $archive = new PclZip( $dir.'/folder.zip' );
    
        $archive->add($dir,
            PCLZIP_OPT_REMOVE_PATH, 
        $dir);
    }
    else{
        show_info('info','Ошибка: директория не найдена');
    }
}
elseif($action == 'createDir'){
    $dirname = isset($_POST['dirname']) ? replase($_POST['dirname']) : false;
    
    if($dirname && $dirname !== ''){
        @mkdir($dir.'/'.$dirname);
    }
    else{
        show_info('info','Ошибка при создании директории');
    }
}
elseif($action == 'edit'){
    print @file_get_contents($dir.'/'.$idName);
}
elseif($action == 'save_edit'){
    $dataf = convert_ch(stripcslashes(trim($_POST['data'])));
    print @file_put_contents($dir.'/'.$idName,$dataf);
}
elseif($action == 'convert'){
    $p = isset($_POST['p']) ? intval($_POST['p']) : false;
    $fileload = @file_get_contents($dir.'/'.$idName);
    
    if($p){
        
        $fileload =  mb_convert_encoding($fileload,"Windows-1251" , "UTF-8" );
        
        @file_put_contents($dir.'/'.$idName,$fileload);
    }
    else{
        $fileload =  mb_convert_encoding($fileload,"UTF-8" , "Windows-1251" );
        
        @file_put_contents($dir.'/'.$idName,$fileload);
    }
}
elseif($action == 'update'){
    
    
    $file = my_fileBuld($dir);
    
    $image = '';
    
    $back = backFolder($dir);
    
    if($back){
        $image .= '<li onclick="image.update(\''.base64_encode($back).'\')"><div><table><tr><td valign="center" class="position_r">';
        
        $image .= '<img src="lib/images/back.png" />';
        
        $image .= '</td></tr></table></div>../назад</li>';
    }
    
    foreach($file['dir'] as $name){
        $image .= bunlerFiles($name,array('folder'=>true));
    }
    
    foreach($file['file'] as $name){
        $image .= bunlerFiles($name);
    }
    
    print $image;
}
?>