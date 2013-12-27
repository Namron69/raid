<?
@session_start ();
@set_time_limit(0);

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define ( 'ROOT_DIR', dirname(dirname(__FILE__)) );
define ( 'THIS_DIR', dirname(__FILE__) );
define ( 'CLASS_DIR', dirname(__FILE__) );

require_once THIS_DIR . '/function.php';
require_once THIS_DIR . '/skin/skin.php';
require_once THIS_DIR . '/login.php';
include_once THIS_DIR.'/curl.php';
$curl = new curl;

if(!$is_logged) show_info('sesion');

$is_logged = login_alt($is_logged);

$action = replase($_GET['action']);

$idName = isset($_POST['name']) ? replase($_POST['name']) : false;
$lib = isset($_POST['lib']) ? replase($_POST['lib']) : false;
$id = isset($_POST['id']) ? intval($_POST['id']) : false;
$charset = $_SESSION['charset'] !== '' ? $_SESSION['charset'] : false;

$charsetSet = ($charset && $charset == 'Windows-1251') ? true : false;

@mkdir(ROOT_DIR.'/cache/'.$is_logged);
@mkdir(ROOT_DIR.'/data/'.$is_logged);

function clearCache(){
    global $is_logged;
    unlink(ROOT_DIR.'/cache/'.$is_logged.'/godat.js.data');
    unlink(ROOT_DIR.'/cache/'.$is_logged.'/libload.data');
}

if($action == 'new'){
    if($idName and $idName !== ''){
        $dirName = ROOT_DIR.'/data/'.$is_logged.'/'.$idName;
        
        if(@file_exists($dirName)){
            show_info('info','Библиотека с таким названием уже существует.');
        }
        else{
            @mkdir($dirName);
            @mkdir($dirName.'/code');
            clearCache();
        }
    }
    else{
        show_info('info','Возникла ошибка при создание библиотеки.');
    }
}
elseif($action == 'del'){
    
    if($idName == '') show_info('info','Библиотека не указана!');
    
    $status = RemoveDir(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/');
    
    clearCache();
    
    if($status !== 'delete') show_info('info','Возникла ошибка при удалении');
}
elseif($action == 'renameLib'){
    
    if($idName == '') show_info('info','Библиотека не указана!');
    $newname = $_POST['newname'] !== '' ? replase($_POST['newname']) : show_info('info','Библиотека не указана!');
    
    rename(ROOT_DIR.'/data/'.$is_logged.'/'.$idName,ROOT_DIR.'/data/'.$is_logged.'/'.$newname);
    clearCache();
}
elseif($action == 'showlib'){
    
    $read = my_fileBuld(ROOT_DIR.'/data/'.$is_logged);
    
    if(count($read['dir']) > 0){
        
        foreach($read['dir'] as $name){
            $p++;
            
            $p = ($p > 2) ? 1 : $p;
            
            $size = sizeFolder(ROOT_DIR.'/data/'.$is_logged.'/'.$name);
            
            $date = showDate(ROOT_DIR.'/data/'.$is_logged.'/'.$name);
            
            $prj .= '<li class="li_prj_'.$name.' p_'.$p.'">
                <div class="lineB">
                    <img src="lib/images/future_projects.png" class="left" />
                    <div class="left textPad" style="min-width: 100px; font-weight: bold">'.$name.'</div>
                    <div class="left grayColor textPad" style="min-width: 200px">'.$date.'</div>
                    <div class="left grayColor textPad">'.FSizeNumber($size).'</div>
                    <div class="right">
                        <div class="grayBtn" style="margin-right: 10px" onclick="libraly.export_lib(\''.$name.'\')">Экспорт</div>
                        <div class="grayBtn" style="margin-right: 10px" onclick="libraly.renameLib(\''.$name.'\')">Изменить</div>
                        <div class="grayBtn" onclick="libraly.del(\''.$name.'\')">Удалить</div>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>';
        }

        print '<ul class="ul tableList projectList">'.$prj.'</ul>';
    }

}
elseif($action == 'sLcat'){
    $read = my_fileBuld(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/');
    
    if(count($read['file']) > 0){
        
        foreach($read['file'] as $name){
            $p++;
            
            $p = ($p > 2) ? 1 : $p;
            
            $size = FSize(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/'.$name);
            
            $date = showDate(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/'.$name);
            
            $name = str_replace(".data",'',$name);
            
            $prj .= '<li class="li_prj_'.$name.' p_'.$p.'">
                <div class="lineB">
                    <img src="lib/images/lib_ico.png" class="left" />
                    <div class="left textPad" style="min-width: 100px; font-weight: bold">'.$name.'</div>
                    <div class="left grayColor textPad" style="min-width: 200px">'.$date.'</div>
                    <div class="left grayColor textPad">'.$size.'</div>
                    <div class="right">
                        <div class="grayBtn" style="margin-right: 10px" onclick="libraly.editCat(\''.$name.'\',\''.$idName.'\')">Редактировать</div>
                        <div class="grayBtn" onclick="libraly.delCat(\''.$name.'\',\''.$idName.'\')">Удалить</div>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>';
        }

    }
    
    print '<ul class="ul tableList projectList">'.$prj.'</ul>';
}
elseif($action == 'addCat'){
    
    if($lib == '') show_info('info','Ошибка: не указан тип библиотеки');
    
    $libpath = ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data';
    
    if(@file_exists($libpath)) show_info('info','Такая категория не существует');
    
    @file_put_contents($libpath,'');
    
    clearCache();
        
}
elseif($action == 'delCat'){
    
    if($lib == '') show_info('info','Ошибка: не указан тип библиотеки');
    
    @unlink(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data');
    
    clearCache();
        
}
elseif($action == 'get_source'){
    if(!$id) show_info('info','Ошибка получения данных');
    
    $code = @file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/code/'.$id.'.code');
    
    $code = str_replace("<?",'&lt;?',$code);
    $code = str_replace("?>",'?&gt;',$code);
    
    if(!$charsetSet) $code = mb_convert_encoding($code,"UTF-8" , "Windows-1251" );
    
    $prew = @file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/code/'.$id.'.code.prew');
    
    if(trim($prew) !== '') print '<div style="display:none"><!--code_start-->'.$code.'<!--code_end--></div>';
    else print $code;
    
    $prew = str_replace('{%theme%}','../userData/userProject/'.$is_logged.'/'.$_SESSION['open_prj'].'/',$prew);
    $prew = str_replace('{%theme_image%}','../userData/userImages/',$prew);
    
    if($prew !== '') print '<b><!--code_prew--></b><b class="show_name_lib_prew"></b>'.$prew.'<b><!--code_prew_end--></b>';
}
elseif($action == 'get_source_user'){
    $source = isset($_POST['user_source']) ? $_POST['user_source'] : show_info('info','Ошибка получения данных');
    if(!$charsetSet) $source = mb_convert_encoding($source,"UTF-8" , "Windows-1251" );
    if(!$_POST['type']) $source = preg_replace("'<style[^>]*>(.*?)<\/style>'si",'',$source);
    print @file_get_contents(ROOT_DIR.'/'.$source);
}
elseif($action == 'editCat'){
    
    if(!file_exists(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data')) show_info('info','Ошибка: не указан тип библиотеки');
    
    $libold = unserialize(file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data'));
    
    $libold = buld_array_reverse($libold);
   
    foreach($libold as $id=>$value){
        $p++;
        
        $p = ($p > 2) ? 1 : $p;
        
        $prj .= '<li class="p_'.$p.'">
            <div class="lineB">
                <img src="lib/images/objects_ico.png" class="left" />
                <div class="left textPad" style="min-width: 100px; font-weight: bold">'.$value.'</div>
                <div class="left textPad" style="min-width: 100px">'.substr(htmlspecialchars(@file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/code/'.$id.'.code')),0,100).'</div>
                <div class="right">
                    <div class="grayBtn" style="margin-right: 10px" onclick="libraly.obj_edit(\''.$idName.'\',\''.$lib.'\','.$id.')">Pедактировать</div>
                    <div class="grayBtn" style="margin-right: 10px" onclick="libraly.prew_edit(\''.$idName.'\',\''.$lib.'\','.$id.')">Превью</div>
                    <div class="grayBtn" onclick="libraly.obj_delete_arr(\''.$idName.'\',\''.$lib.'\','.$id.',this)">Удалить</div>
                </div>
                <div class="clear"></div>
            </div>
        </li>';
    }
    
    print buld_compress('<ul class="ul tableList projectListObj">'.$prj.'</ul>');
    
}
elseif($action == 'editPrew'){
    ?>
    <form id="obj_formX5674">      
   
    <?=scroll('<div class="textarea_c"><textarea class="textarea_scrin" spellcheck="false" name="code_prew" id="obj_area_id" style="overflow:hidden" onkeyup="libraly.obj_area_key(event)">'.@file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/code/'.$id.'.code.prew').'</textarea></div>','objListX372663')?>
            
    <input type="hidden" name="lib" value="<?=$lib?>" />
    <input type="hidden" name="id" value="<?=$id?>" />
</form>
    <script>
        initScroll('objListX372663',{height: in_ce.i_height});
        libraly.obj_area_init();
    </script>
    <?
}
elseif($action == 'prew_save'){
    $code_prew = isset($_POST['code_prew']) ? convert_ch(stripcslashes(trim($_POST['code_prew']))) : show_info('info','Ошибка получения данных');
    @file_put_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/code/'.$id.'.code.prew',$code_prew);
}
elseif($action == 'preaddObj' || $action == 'editObj'){
    
    if($action == 'editObj'){
        $id = isset($_POST['id']) ? intval($_POST['id']) : show_info('info','Ошибка получения данных');
        
        $libold = unserialize(file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data'));
    }
    ?>
<form id="obj_formX5674">      
   
    <?=scroll('<div class="textarea_c"><textarea class="textarea_scrin" spellcheck="false" name="obj_option[code]" id="obj_area_id" style="overflow:hidden" onkeyup="libraly.obj_area_key(event)">'.@file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/code/'.$id.'.code').'</textarea></div>','objListX372663')?>
            

    <input type="hidden" name="name" value="<?=$idName?>" />
    <input type="hidden" name="lib" value="<?=$lib?>" />
    <? if($action == 'editObj'){ ?>
    <input type="hidden" name="id" value="<?=$id?>" />
    <input type="hidden" name="obj_option[name]" value="<?=$libold[$id]?>" />
    <?}?>
</form>
    <script>
        initScroll('objListX372663',{height: in_ce.i_height});
        libraly.obj_area_init();
    </script>
    <?
}
elseif($action == 'obj_save'){
    
    $obj_option = isset($_POST['obj_option']) ? $_POST['obj_option'] : show_info('info','Ошибка получения данных');
    
    $libold = unserialize(file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data'));
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : rand(0,99999999999999);
    
    $libold[$id] = htmlspecialchars(stripcslashes(convert_ch($obj_option['name'])));
    
    @file_put_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data',serialize($libold));
    @file_put_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/code/'.$id.'.code',convert_ch(stripcslashes($obj_option['code'])));
    
    clearCache();
}
elseif($action == 'obj_delete'){
    $id = isset($_POST['id']) ? intval($_POST['id']) : show_info('info','Ошибка получения данных');
    
    $libold = unserialize(file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data'));
    
    unset($libold[$id]);
    
    @file_put_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$lib.'/'.$idName.'.data',serialize($libold));
    
    clearCache();
}
elseif($action == 'ststatus'){
    print @file_get_contents(ROOT_DIR.'/cache/'.$is_logged.'/compSt.data');
}
elseif($action == 'export'){
    
    include_once THIS_DIR.'/pclzip.data.php';
    
    unlink(ROOT_DIR.'/cache/'.$is_logged.'/pac.zip');
    
    $archive = new PclZip( ROOT_DIR.'/cache/'.$is_logged.'/pac.zip' );
    
    $archive->add(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/',
                            PCLZIP_OPT_REMOVE_PATH, 
                            ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/');
                            
    $curl->seting['TIMEOUT'] = 30;
    $curl->seting['ENCODING'] = false;
    
    $postE = array(
        'script'=>'raid',
        'name'=>$idName,
        'login'=>$is_logged,
        'Filedata'=>'@'.ROOT_DIR.'/cache/'.$is_logged.'/pac.zip'
    );
    
    $status = unserialize($curl->getpage('http://conect.sl-cms.com/conect.php?action=export',array('post'=>$postE)));
    
    if($status['error']) show_info('info',$status['error']);
    
    show_info('info','Библиотека была экспортирована');
}
elseif($action == 'show_export'){
    print $curl->getpage('http://conect.sl-cms.com/conect.php?action=show_export',array('post'=>array('script'=>'raid','login'=>$is_logged)));
}
elseif($action == 'get_import'){
    include_once THIS_DIR.'/pclzip.data.php';
    
    $username = replase($_POST['username']);
    
    $zip = ROOT_DIR.'/cache/'.$is_logged.'/im.zip';
    
    @unlink($zip);
    
    if($username !== '') copy('http://conect.sl-cms.com/libraly/raid/'.$username.'/'.$idName.'.zip',$zip);
    else copy('http://conect.sl-cms.com/libraly/raid/'.$is_logged.'/'.$idName.'.zip',$zip);
    
    $archive = new PclZip( $zip );
    
    @mkdir(ROOT_DIR.'/data/'.$is_logged.'/'.$idName.'/');
    
    if(file_exists($zip)){
    
        if ($archive->extract('../data/'.$is_logged.'/'.$idName ) == 0) {
            show_info('info','Возникла ошибка при импорте! '.$archive->errorInfo(true));
        }
    }
    else{
        show_info('info','Возникла ошибка при импорте! ');
    }
}
?>