<?
@session_start ();
@set_time_limit(0);

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

@mkdir(ROOT_DIR.'/cache/'.$is_logged);

$loadSt = isset($_GET['prew']) ? false : true;

?>
<!DOCTYPE HTML>
<head>
<title>RAID - Iframe Result</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="styleIframe.css" type="text/css" />
<?
if($loadSt){
    print html_js('../lib/js',array(
        'jquery',
    ));
    
    print html_js('../lib/js',array(
        'iframe_result'
    ));
    ?>
    <script>
    $(document).ready(function(){
        rad.bindRad();
    });
    </script>
<?
}
?>
</head>

<div id="loadCssFiles" style="display: <?=($loadSt ? 'block' : 'none')?>;"><div>Loading CSS styles</div></div>
<div id="globalConteinerCode">
    
    <?
    
    $href = (isset($_GET['open'])) ? urldecode($_GET['open']) : false;
    $openPrj = (isset($_GET['prj']) && $_GET['prj'] !== '') ? preg_replace("'[^a-z0-9_]'si",'',$_GET['prj']) : false;        

    $dataPrj = unserialize(@file_get_contents(ROOT_DIR.'/userData/userProject/'.$is_logged.'/'.$openPrj.'/data'));
    
    include_once THIS_DIR.'/phpQuery/phpQuery.php';
    include_once THIS_DIR.'/url.parser.php';
    include_once THIS_DIR.'/parser.css.php';
    
    $css = new parserCSS;
        
    if($href){
        $url = new url($href);
        $url->parseTags();
        $url->dublicate();
        
        if(isset($_SESSION['charset'])){
            if($_SESSION['charset'] == 'Windows-1251') $url->result =  mb_convert_encoding($url->result,"Windows-1251" , "UTF-8" );
            else $url->result =  mb_convert_encoding($url->result,"UTF-8" , "Windows-1251" );
        }
        
        if(isset($_GET['js'])){
            $url->js($_GET['js']);
        }
        
        @file_put_contents(ROOT_DIR.'/cache/loadpage.data',$url->result);
        echo $url->result;
    }
    else{
        if($loadSt){
            $url = new url($dataPrj['url']);
            $url->result =  @file_get_contents(ROOT_DIR.'/userData/userProject/'.$is_logged.'/'.$openPrj.'/saved/page.'.$dataPrj['count'].'.html');
            $url->dublicate();
        } 
        else{
            if(isset($_GET['load'])){
                
                $dir = ROOT_DIR.'/userData/userDta/'.$is_logged.'/compiler/'.$openPrj;
                
                require_once THIS_DIR . '/pclzip.lib.php';
        
                unlink($dir.'/folder.zip');
                
                $archive = new PclZip( $dir.'/folder.zip' );
            
                $archive->add($dir,
                    PCLZIP_OPT_REMOVE_PATH, 
                $dir);
            }
            
            $url = new url($dataPrj['prev_url'],array('loadPrj'=>(isset($_GET['load']) ? true : false),'namePrj'=>$openPrj,'login'=>$is_logged,'ip'=>$_SERVER['SERVER_ADDR'],'key'=>$dataPrj['prev_key']));
        } 
        
        if(isset($_SESSION['charset'])){
            if($_SESSION['charset'] == 'Windows-1251') $url->result =  mb_convert_encoding($url->result,"Windows-1251" , "UTF-8" );
            else $url->result =  mb_convert_encoding($url->result,"UTF-8" , "Windows-1251" );
        }
        
        if(isset($_GET['js'])){
            $url->js($_GET['js']);
        }
        
        echo $url->result;
    }
    
    ?>
    
</div>
<?
if($loadSt){
    foreach($url->load_all_css() as $filename=>$style){
        $css->parseString($style,$filename);
    }
        
    @file_put_contents(ROOT_DIR.'/cache/'.$is_logged.'/style.array.data',serialize($css->allCss));
    
    if(!strpos($url->result,'jquery')){
    
        print html_js('../lib/js',array(
            'jquery'
        ));
    }
    ?>
    <script>
    $('#loadCssFiles').fadeOut(150);
    $('#bgload').css({background: 'none'});
    </script>
<?
}
?>
</html>