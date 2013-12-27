<?
header("Content-type: application/x-javascript");

define ( 'ROOT_DIR', dirname(dirname(__FILE__)) );
define ( 'THIS_DIR', dirname(__FILE__) );
define ( 'CLASS_DIR', dirname(__FILE__) );

include(CLASS_DIR.'/function.php');

$is_logged = login_alt($_GET['login']);

if(file_exists(ROOT_DIR.'/cache/'.$is_logged.'/godat.js.data')){
    print file_get_contents(ROOT_DIR.'/cache/'.$is_logged.'/godat.js.data');
}
else{

    $read = my_fileBuld(ROOT_DIR.'/data/'.$is_logged.'/');
    
    if(count($read['dir']) > 0){
        
        $bigArr = array();
        
        foreach($read['dir'] as $name){
            
            $readLib = my_fileBuld(ROOT_DIR.'/data/'.$is_logged.'/'.$name.'/');
            
            $json = array();
            
            foreach($readLib['file'] as $nameLib){
                $libold = array();
                
                $libold = @unserialize(file_get_contents(ROOT_DIR.'/data/'.$is_logged.'/'.$name.'/'.$nameLib));
                
                $nameLib = str_replace(".lib",'',$nameLib);
                
                $arrMenuCo = array();
                
                if(is_array($libold)){
                    
                    foreach($libold as $id=>$text){
                        
                        $libold[$id] = $text;
                        
                        $arrMenuCo[] = array("iframe.reobj(".$id.")"=>$libold[$id]);
                    }
                    
                }
                
                $json[$nameLib] = $arrMenuCo;
                
            }
            
            $allMenuInit[$name] = $json;
            
        }
        
        $cacheJs .= 'var arrMenuInit = '.json_encode($allMenuInit).";\n";
        
        print $cacheJs;
        
        file_put_contents(ROOT_DIR.'/cache/'.$is_logged.'/godat.js.data',$cacheJs);
    }
    else{
        print "var arrMenuInit = {};\n";
    }
}
?>