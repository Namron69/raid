<?
@session_start ();
@set_time_limit(0);

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );


define ( 'ROOT_DIR', '../' );
define ( 'THIS_DIR', dirname(__FILE__) );

require_once THIS_DIR . '/function.php';
require_once THIS_DIR . '/login.php';

if(!$is_logged) show_info('sesion');

$is_logged = login_alt($is_logged);

$action = replase($_GET['action']);

$idName = isset($_POST['name']) ? preg_replace("'[^a-z0-9_]'si",'',$_POST['name']) : (isset($_SESSION['open_prj']) ? $_SESSION['open_prj'] : false);
$dirName = ROOT_DIR.'/userData/userProject/'.$is_logged;

@mkdir($dirName);
@mkdir(ROOT_DIR.'/cache/'.$is_logged);

if($action == 'new'){
    
    if($idName and $idName !== ''){
        
        if(@file_exists($dirName.'/'.$idName)){
            print 'false';
        }
        else{
            @mkdir($dirName);
            @mkdir($dirName.'/'.$idName);
            
        		
            $_SESSION['open_prj'] = $idName;
    
            print 'true';
        }
    }
    else{
        show_info('info','Возникла ошибка при создание проекта.');
    }
    
}
elseif($action == 'charset'){
    $ch = ($_POST['ch'] == 1) ? 'Windows-1251' : 'UTF-8';
    
    if($_POST['ch'] == 0) unset($_SESSION['charset']);
    else $_SESSION['charset'] = $ch;
}
elseif($action == 'getstyle' || $action == 'css_copy'){
    $get = $action == 'css_copy' ? false : true;
    
    $styles = array();
    
    $styles = $_POST['styles'];
    $tag = replase(stripcslashes(trim($_POST['tag'])));
    
    include_once THIS_DIR.'/parser.css.php';
    
    $css = new parserCSS;
    
    $css->allCss = unserialize(@file_get_contents(ROOT_DIR.'/cache/'.$is_logged.'/style.array.data'));
    
    $finded = array();
    
    foreach($styles as $type=>$value){
        $exp = explode(" ",$value);
        
        foreach($exp as $name){
            if(trim($name) !== ''){
                if($type == 'class') $finded[] = $css->find('.'.trim($name));
                if($type == 'id') $finded[] = $css->find('#'.trim($name));
            }
        }
        $finded[] = $css->find('',$tag);
    }
    
    if($get) print '<div style="display:block;text-align:center" onclick=\'iframe.copy_css('.@json_encode($styles).',"'.$tag.'")\' class="grayBtn">Копировать стили</div>';
    if($get) print '<ul class="ul tableList">';
    
    foreach($finded as $array){
        if(count($array) > 0){
            foreach($array as $filename=>$classid){
                $exp = @end(explode("/",$filename));
                
                if($get) print '<li class="p_1"> <div class="lineB" style="padding: 4px 0px"> <img class="left" src="lib/images/style_ico.png"> <div style="min-width: 100px; font-weight: bold" class="left textPad"><a href="'.$filename.'" target="_blank">'.$exp.'</a></div> <div class="clear"></div> ';
                
                foreach($classid as $name=>$values){
                    if($get) print '<span style="color: #8363b1">'.$name.'</span>{<div class="coe" style="display: block">';
                    else print $name."{\n";
                    
                    foreach($values as $style=>$value){
                        if($get) print '<div>'.$style.': <span style="color: #e02664">'.$value.'</span>;</div>';
                        else print '    '.$style.': '.$value."\n";
                    }
                        
                    if($get) print '</div>}<br />';
                    else print "}\n";
                }
                
                if($get) print '</div> </li>';
            }
        } 
    }
    
    if($get) print '</ul>';
}
elseif($action == 'back_point'){
    $data = unserialize(file_get_contents($dirName.'/'.$idName.'/data'));
    
    if(intval($data['count']) > 1) $data['count'] = intval($data['count']) - 1;
    
    @file_put_contents($dirName.'/'.$idName.'/data',serialize($data));
}
elseif($action == 'save'){
    
    $url = stripcslashes($_POST['url']);
    $dataPost = stripcslashes($_POST['data']);

    if($idName && $idName !== '' && file_exists($dirName.'/'.$idName)){
        
        @mkdir($dirName.'/'.$idName.'/saved');
        
        $data = array();
        
        $data = unserialize(file_get_contents($dirName.'/'.$idName.'/data'));
        
        $data['count'] = intval($data['count']) + 1;
        $data['url'] = $url;
        $data['prev_url'] = stripcslashes($_POST['prev']);
        $data['prev_key'] = stripcslashes($_POST['prev_key']);
        
        @file_put_contents($dirName.'/'.$idName.'/data',serialize($data));
        @file_put_contents($dirName.'/'.$idName.'/saved/page.'.intval($data['count']).'.html',$dataPost);
    }
    else{
        show_info('info','Возникла ошибка при сохранение проекта.');
    }
}
elseif($action == 'del'){
    if($idName){
        RemoveDir($dirName.'/'.$idName);
    }
    else{
        show_info('info','Возникла ошибка получения данных.');
    }
}
elseif($action == 'compiler'){
    
    $data = unserialize(file_get_contents($dirName.'/'.$idName.'/data'));
    
    @file_put_contents(ROOT_DIR.'/cache/'.$is_logged.'/compSt.data','');
    
    if($data['url'] !== ''){
        include_once THIS_DIR.'/phpQuery/phpQuery.php';
        include_once THIS_DIR.'/url.parser.php';
        
        $url = new url($data['url']);
        
        @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged);
        @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler');
        @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler/'.$idName);
        
        $url->compiler(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler/'.$idName.'');
    }
    else{
        show_info('info','Возникла ошибка при загрузки сайта, проект не сохранен.');
    }
        
}
elseif($action == 'copy_html'){
    $ac = isset($_POST['ac']) ? intval($_POST['ac']) : show_info('info','Возникла ошибка получения данных.');
    
    $dataPost = stripcslashes($_POST['data']);
    
    
    //$dataPost = mb_convert_encoding($dataPost,"Windows-1251" , "UTF-8" );
    
    $dataPost = preg_replace("/activeHover\s*/",'',$dataPost);
    $dataPost = preg_replace("/activeSelect\s*/",'',$dataPost);
    $dataPost = preg_replace("/[\s]{0,1}class=[\'\"]{2}/",'',$dataPost);
    
    $dataPost = preg_replace("'<div style=\"display:[\s]{0,1}none[;]{0,1}\"><!--code_start-->'si",'',$dataPost);
    $dataPost = preg_replace("'<!--code_end--></div>'si",'',$dataPost);
    
    $dataPost = preg_replace("'<b><!--code_prew--></b>(.*?)<b><!--code_prew_end--></b>'si",'',$dataPost);
    
    $dataPost = preg_replace("'<raid'is","<",$dataPost);
    $dataPost = preg_replace("'<\/raid'is","</",$dataPost);
    
    $dataPost = preg_replace("'<base[^>]*>'is","",$dataPost);
    
    $dataPost = str_replace("&lt;?",'<?',$dataPost);
    $dataPost = str_replace("?&gt;",'?>',$dataPost);
    
    $dataPost = str_replace("&amp;lt;?",'<?',$dataPost);
    $dataPost = str_replace("?&amp;gt;",'?>',$dataPost);
    
    $dataPost = str_replace("-&gt;",'->',$dataPost);
    $dataPost = str_replace("=&gt;",'=>',$dataPost);
    
    $dataPost = str_replace('<jsscript',"<script",$dataPost);
    $dataPost = str_replace('jsscript>',"script>",$dataPost);
    
    //$dataPost = urldecode($dataPost); //хз
    
    $filenames = isset($_POST['filename']) ? preg_replace("'[^a-z0-9\_\.]'si",'',$_POST['filename']) : show_info('info','Возникла ошибка получения данных.');
    
    if($filenames !== ''){
        if($ac == 1){
            
            /**
             * ==================
             * Easy to save
             * ==================
             */
            
            @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged);
            @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler');
            @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler/'.$idName);
            
            if($idName){
                $data = unserialize(file_get_contents($dirName.'/'.$idName.'/data'));
            
                $dataUrl = explode("/",str_replace("http://",'',$data['url']));
            
                $dataPost = str_replace("http://".$dataUrl[0]."/",'<?=$THEME?>/',$dataPost);
            
                @file_put_contents(ROOT_DIR.'/userData/userData/'.$is_logged.'/compiler/'.$idName.'/'.$filenames,$dataPost);
            }
            else @file_put_contents(ROOT_DIR.'/userData/userData/'.$is_logged.'/'.$filenames,$dataPost);
        }
        elseif($ac == 2){
            
            /**
             * ==================
             * Saving Styles
             * ==================
             */
            
            preg_match_all("'class=[\'\"]([a-z0-9_\s]*)[\'\"]'si",$dataPost,$class);
            
            $resClass = array_unique($class[1]);
            
            preg_match_all("'id=[\'\"]([a-z0-9_\s]*)[\'\"]'si",$dataPost,$ids);
            
            $resID = array_unique($ids[1]);
            
            include_once THIS_DIR.'/parser.css.php';
            
            $css = new parserCSS;
            
            $css->allCss = unserialize(@file_get_contents(ROOT_DIR.'/cache/'.$is_logged.'/style.array.data'));
            
            $finded = array();
            
            /**
             * Find Styles
             */
            
            foreach($resClass as $value){
                $exp = explode(" ",$value);
                
                foreach($exp as $name){
                    if(trim($name) !== ''){
                        $finded[] = $css->find('.'.trim($name));
                    }
                }
            }
            
            foreach($resID as $value){
                $exp = explode(" ",$value);
                
                foreach($exp as $name){
                    if(trim($name) !== ''){
                        $finded[] = $css->find('#'.trim($name));
                    }
                }
            }
            
            if(count($finded) > 0){
                $compliteStyle = "<style>\n";
                
                
                foreach($finded as $array){
                    if(count($array) > 0){
                        
                        foreach($array as $filename=>$classid){
                            $exp = end(explode("/",$filename));
                            
                            foreach($classid as $name=>$values){
                                $compliteStyle .= $name."{\n";
                                
                                foreach($values as $style=>$value){
                                    $compliteStyle .= '    '.$style.': '.$value.";\n";
                                }
                                    
                                $compliteStyle .= "}\n";
                            }
                            
                        }
                        
                    } 
                }
                
                $compliteStyle .= "</style>\n";
            }
            
            $compliteStyle = preg_replace("'<style[^>]*>\s*<\/style>'si",'',$compliteStyle);
            
            @mkdir(ROOT_DIR.'/userData/userData/'.$is_logged);
            
            @file_put_contents(ROOT_DIR.'/userData/userData/'.$is_logged.'/'.$filenames.'.html',$compliteStyle.$dataPost);
            
        }
    }
    else{
        show_info('info','Возникла ошибка получения данных.');
    }
}
elseif($action == 'allPrj'){
    
    $file = my_fileBuld($dirName);
    
    $allowed = array('prj');
    
    if(count($file['dir']) > 0){
        
        foreach($file['dir'] as $name){
            $p++;
            
            $p = ($p > 2) ? 1 : $p;
            
            $date = @date("F d Y", @filemtime($dirName.'/'.$name));
            
            $size = FSizeNumber(sizeFolder($dirName.'/'.$name));
            
            $prj .= '<li class="li_prj_'.$name.' p_'.$p.'"><div class="lineB"><img src="lib/images/future_projects.png" class="left" /><div class="left textPad" style="min-width: 100px; font-weight: bold">'.$name.'</div><div class="left grayColor textPad" style="min-width: 110px">'.$date.'</div><div class="left grayColor textPad">'.$size.'</div><div class="right"><div class="grayBtn" style="margin-right: 10px" onclick="file.open(\''.$name.'\')">Открыть</div><div class="grayBtn" onclick="file.del(\''.$name.'\')">Удалить</div></div><div class="clear"></div></div></li>';
        }

        print '<ul class="ul tableList projectList">'.$prj.'</ul>';
    }
    else{
        print 'Вы еще не создали ни один проект.';
    }

}
?>