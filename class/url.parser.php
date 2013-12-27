<?
class url{
    
    var $host = '';
    var $uri = '';
    var $url_path = '';
    var $result = '';
    var $tab_re = array('html','meta','head','base');
    var $allCss = array();
    var $allImgJs = array();
    var $striJsArr = array();
    var $allCssImport = array();
    var $post = false;
    
    function url($url,$post = false){
        $url = str_replace('http://','',$url);
        $host = parse_url('http://'.$url);
        
        if($host['host']){
            $this->host = str_replace("www.",'',$host['host']);
            $this->uri = $host['path'].'?'.$host['query'].$host['fragment'];
            $this->url_path = $host['path'];
            if($post) $this->post = $post;
            $this->open();
        } 
    }
    
    function conect($frt){

        $ret = false;
        
        if( @function_exists('curl_init') ){
            if( $curl = @curl_init() ){
                    
                if( !@curl_setopt($curl,CURLOPT_URL,$frt) ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_RETURNTRANSFER,true) ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,15) ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_HEADER,false) ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true) ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8)') ) return $ret;
                if( !@curl_setopt($curl,CURLOPT_ENCODING,"gzip,deflate") ) return $ret;
                
                if($this->post){
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->post));
                }
                    
                $ret = @curl_exec($curl);
                @curl_close($curl);
            }
        }
        else{
            $u = parse_url($frt);
               
            if( $fp = @fsockopen($u['host'],!empty($u['port']) ? $u['port'] : 80 ) ){
                    
                $headers = 'GET '.  $u['path'] . '?' . $u['query'] .' HTTP/1.0'. "\r\n";
                $headers .= 'Host: '. $u['host'] ."\r\n";
                $headers .= 'Connection: Close' . "\r\n\r\n";
                    
                @fwrite($fp, $headers);
                $ret = '';
                         
                while( !feof($fp) ){
                    $ret .= @fgets($fp,1024);
                }
                    
                $ret = substr($ret,strpos($ret,"\r\n\r\n") + 4);
                    
                @fclose($fp);
            }
        }
        
        $ret = trim($ret);
        
        
        $in_charset = 'UTF-8';
            
        if (preg_match('/charset=([^ ]*)[\"\']/i', $ret, $response)){
		    $in_charset = $response[1];
	    }
	    if(strstr($in_charset,'windows-1251')){
	        $ret = mb_convert_encoding($ret, 'UTF-8', $in_charset);
        }
        
        if($ret !== '') return $ret;
        else return false;
    }
    
    function open(){
        $data = $this->conect('http://'.$this->host.$this->uri);
        
        $data = preg_replace("'<\?xml[^>]*>'si",'',$data);
        
        if(preg_match("'<meta\shttp-equiv=[\'\"]refresh[\'\"].+?url=(.+?)[\'\"]>'si",$data,$m)){
             if(preg_match("'http://'si",$m[1])) $this->url($m[1]);
             else $this->url('http://'.$this->host.'/'.$m[1]);
        }
        else{
            if($data){
                $this->result = $this->src($data);
            }
        }
    }
    function js($js){
        if($js == 1){
            $this->result = str_replace("<script",'<jsscript',$this->result);
            $this->result = str_replace("</script>",'</jsscript>',$this->result);
        } 
        elseif($js == 2){
            $this->result = str_replace('<jsscript',"<script",$this->result);
            $this->result = str_replace('jsscript>',"script>",$this->result);
        } 
    }
    function clickR($c,$n,$t){
        $c = stripslashes($c);
        $t = stripslashes($t);
        
        return $n.'='.$t.'return false; '.$c.$t;
    }
    
    function src($string){
        
        $string = preg_replace("'onclick=\"(.+?)\"'sie","\$this->clickR('\\1','onclick','\"')",$string);
        $string = preg_replace("'onmousedown=\"(.+?)\"'sie","\$this->clickR('\\1','onmousedown','\"')",$string);
        $string = preg_replace("'onmouseup=\"(.+?)\"'sie","\$this->clickR('\\1','onmouseup','\"')",$string);
        
        $string = preg_replace("'onclick=\'(.+?)\''sie","\$this->clickR('\\1','onclick','\'')",$string);
        $string = preg_replace("'onmousedown=\'(.+?)\''sie","\$this->clickR('\\1','onmousedown','\'')",$string);
        $string = preg_replace("'onmouseup=\'(.+?)\''sie","\$this->clickR('\\1','onmouseup','\'')",$string);
        
        
        $string = preg_replace("'src=\'\/\/'si","src='http://",$string);
        $string = preg_replace("'src=\"\/\/'si","src=\"http://",$string);
        
        $string = preg_replace("'href=\'\/\/'si","href='http://",$string);
        $string = preg_replace("'href=\"\/\/'si","href=\"http://",$string);
        
        $string = preg_replace("'url\(\'\/\/'si","url('http://",$string);
        $string = preg_replace("'url\(\"\/\/'si","url(\"http://",$string);
        $string = preg_replace("'url\(\/\/'si","url(http://",$string);
        
        $string = preg_replace("'background=\'\/\/'si","background='http://",$string);
        $string = preg_replace("'background=\"\/\/'si","background=\"http://",$string);
        
        $string = preg_replace("'src=\"(.+?)\"'sie","\$this->curentUrl('\\1','src','\"')",$string);
        $string = preg_replace("'href=\"(.+?)\"'sie","\$this->curentUrl('\\1','href','\"')",$string);
        $string = preg_replace("'background=\"(.+?)\"'sie","\$this->curentUrl('\\1','background','\"')",$string);
        
        $string = preg_replace("'src=\'(.+?)\''sie","\$this->curentUrl('\\1','src','\'')",$string);
        $string = preg_replace("'href=\'(.+?)\''sie","\$this->curentUrl('\\1','href','\'')",$string);
        $string = preg_replace("'background=\'(.+?)\''sie","\$this->curentUrl('\\1','background','\'')",$string);
        
        $document = phpQuery::newDocument($string);
  
        $hentry = $document->find('script[src],img');
        
        if($hentry->length()){
            
            foreach ($hentry as $ur) {
                $pq   = pq($ur);
                
                $src = $pq->attr('src');
                
                //$reurl = $this->curentUrl($src);
                
                //$pq->attr('src',$reurl);
                
                $this->allImgJs[] = $this->curentUrl($src);
                
            }
        }
        
        $hentry = $document->find('link[href]');
        
        if($hentry->length()){
            
            foreach ($hentry as $ur) {
                $pq   = pq($ur);
                
                $href = $pq->attr('href');
                
                //$reurl = $this->curentUrl($href);
                
                //$pq->attr('href',$reurl);
                
                $this->allCss[] = $this->curentUrl($href);
                
            }
        }
        
        
        //$string = phpQuery::getDocument();


        
        $string = preg_replace("'url\([\"\'](.*?)[\"\']\)'sie","\$this->style_bg_url('\\1')",$string);
        $string = preg_replace("'url\((.*?)\)'sie","\$this->style_bg_url('\\1')",$string);
        $string = preg_replace("'\@import\s[\"\'](.*?)[\"\']'sie","\$this->import_url('\\1')",$string);
        
        return $string;
    }
    
    function load_all_css(){
        $data = array();
        
        foreach($this->allCss as $url){
            if(strpos($url,'.css')){
                $string = $this->conect($url);
                $string = preg_replace("'url\([\"\'](.*?)[\"\']\)'sie","\$this->parseCssUrl('\\1','$url',1)",$string);
                $string = preg_replace("'url\((.*?)\)'sie","\$this->parseCssUrl('\\1','$url',1)",$string);
                $string = preg_replace("'\@import\s[\"\'](.*?)[\"\']'sie","\$this->loadImportCss('\\1','$url',2)",$string);
                $data[$url] = $string;
            }
        }
        
        foreach($this->allCssImport as $url){
            if(strpos($url,'.css')){
                $string = $this->conect($url);
                $string = preg_replace("'url\([\"\'](.*?)[\"\']\)'sie","\$this->parseCssUrl('\\1','$url',1)",$string);
                $string = preg_replace("'url\((.*?)\)'sie","\$this->parseCssUrl('\\1','$url',1)",$string);
                $data[$url] = $string;
            }
        }
        
        $stringBody = preg_replace("'<script[^>]*>(.*?)<\/script>si'",'',$this->result);
        
        $searchStyles = preg_match_all("'<style[^>]*>(.*?)<\/style>'si",$stringBody,$styles);
        
        if($searchStyles){
            foreach($styles[1] as $datast){
                $dataStyle .= $datast."\n";
            }
            
            $data['body_style'] = $dataStyle;
        }
        
        return $data;
    }
    
    function loadImportCss($cssurl,$url,$type){
        $url = $this->parseCssUrl($cssurl,$url,$type);
        
        $this->allCssImport[] = $url;
        
        $string = $this->conect($url);
        $string = preg_replace("'\@import\s[\"\'](.*?)[\"\']'sie","\$this->loadImportCss('\\1','$url',2)",$string);
        
        return '@import"'.$url.'";';
    }
    
    function parseCssUrl($cssurl,$url,$type = false){
        
        if(substr($cssurl,0,3) == '../'){
            $exp = explode("/",$url);
            
            unset($exp[count($exp)-2]);
            
            $url = implode('/',$exp);
            
            return $this->parseCssUrl(substr($cssurl,3,strlen($cssurl)),$url);
        }
        elseif(strstr($cssurl,'http://')){
            if($type == 2) return '@import"'.$this->checkImages($cssurl).'";';
            else return 'url('.$this->checkImages($cssurl).')';
        }
        else{
            if(substr($cssurl,0,2) == './') $cssurl = substr($cssurl,2,strlen($cssurl));
            
            if($cssurl[0] == '/') $cssurl = substr($cssurl,1,strlen($cssurl));
            
            $exp = explode("/",$url);
            
            unset($exp[count($exp)-1]);
            
            $full = implode('/',$exp);
            
            if($type == 2){
                return $this->checkImages($full.'/'.$cssurl);
            } 
            else return 'url('.$this->checkImages($full.'/'.$cssurl).')';
        }
    }
    
    function checkImages($url){
        $ext = explode("/",$url);
        if(strpos(end($ext),'.css') || strpos(end($ext),'.js') || strpos(end($ext),'.jpg') || strpos(end($ext),'.png') || strpos(end($ext),'.gif')){
            $this->allImgJs[] = $url;
        }
        return $url;
    }
    
    function style_bg_url($url){
        return 'url('.$this->checkImages($this->curentUrl($url)).')';
    }
    function import_url($url){
        $reurl = $this->checkImages($this->curentUrl($url));
        $this->allCss[] = $reurl;
        return '@import "'.$reurl.'"';
    }
    
    function curentUrl($url,$types = false,$qo = false){
        $qo = stripslashes($qo);
        $types = stripslashes($types);
        $types = $types ? $types.'='.$qo : '';
        
        $url = str_replace('&amp;','&',$url);
        
        if(strstr($url,'http://')){
            return $types.$url.$qo;
        }
        else{
            /*
            if(substr($url,0,1) == '/'){
                $url = substr($url,1,strlen($url));
                return $this->curentUrl($url);
            }
            */
            if(strlen($this->url_path) == 1) $this->url_path = '';
            
            $exppath = explode("/",$this->url_path);
            
            
            if(strpos(end($exppath),'.') !== false){
                unset($exppath[count($exppath)-1]);
                $this->url_path = implode('/',$exppath);
            }
            
            
            if($this->url_path[strlen($this->url_path)-1] == '/') $this->url_path = substr($this->url_path,0,strlen($this->url_path)-1);
            
            $repath = substr($this->url_path,1,strlen($this->url_path));
            
            $strlen = strlen($repath);
            
            if(substr($url,0,$strlen) == $repath) $url = substr($url,$strlen,strlen($url));
            
            //print $repath.'<br>';
            //print $url.'<br>----------<br>';
            if($url[0] == '/') return $types.'http://'.$this->host.$url.$qo;
            else return $types.'http://'.$this->host.$this->url_path.'/'.$url.$qo;
        }
    }
    
    function replase(){
        $string = $this->result;
        
        foreach($this->tab_re as $name){
            $string = preg_replace("'<".$name."[^>]*>'is",'',$string);
            $string = preg_replace("'<\/".$name."[^>]*>'is",'',$string);
        }
        
        $this->result = $string;
    }
    
    function compiler($dir){
        global $is_logged;
        
        $this->load_all_css();
        
        @file_put_contents(ROOT_DIR.'/cache/'.$is_logged.'/compSt.data','');
        
        $this->allCss = array_unique($this->allCss);
        $this->allImgJs = array_unique($this->allImgJs);
        
        foreach($this->allCss as $url){
            $this->createDir($url,$dir);
        }
        foreach($this->allImgJs as $url){
            $this->createDir($url,$dir);
        }
    }
    
    function createDir($urlfile,$imdir){
        global $is_logged;
        
        $url = $urlfile;
        $urlfile = str_replace('http://','',$urlfile);

        $exp = explode("/",$urlfile);
        
        unset($exp[0]);
        
        $urlfile = implode("/",$exp);
        
        if($urlfile[0] == '/') $urlfile = substr($urlfile,1,strlen($urlfile));
        
        $xdir = explode('/',$urlfile);
        
        $rder = '';
        
        @mkdir($imdir);
        
        foreach($xdir as $vdir){
            if(strpos($vdir,'.') === false){
                
                $rder .= '/'.$vdir;
                @mkdir($imdir.$rder);
                
            }
            else{
                $coplitePath =  '.'.$rder.'/'.$vdir."<br />";
                echo '.'.$rder.'/'.$vdir."\n";
                
                
                if(!file_exists($imdir.$rder.'/'.$vdir)){
                    if($this->confirm($url)){
                        
                        $string = $this->conect($url);
                         
                        $vdir = preg_replace("'\?(.*?)$'is",'',$vdir); 
                           
                        @file_put_contents($imdir.$rder.'/'.$vdir,$string);
                    }
                }
                
                @file_put_contents(ROOT_DIR.'/cache/'.$is_logged.'/compSt.data',$coplitePath,FILE_APPEND);
                @ob_flush();
            }
        }
    }
    function confirm($url){
        $Headers = @get_headers($url);
        
        if(preg_match("|200|", $Headers[0])){
            return true;
        } 
        else{
            return false;
        }
    }
    
    function parseTags(){
        $string = $this->result;
        
        $arrTab = array('body','html','head','!DOCTYPE');
        
        foreach($arrTab as $name){
            $string = preg_replace("'<".$name."'is","<raid".$name,$string);
            $string = preg_replace("'<\/".$name."'is","</raid".$name,$string);
        }
        
        $this->result = $string;
    }
    
    function dublicate(){
        $string = $this->result;
        
        $string = preg_replace("'<raid([^>]*)>'ise","\$this->d_fun('\\1',1)",$string);
        $string = preg_replace("'<\/raid([^>]*)>'ise","\$this->d_fun('\\1',2)",$string);
        
        $this->result = $string;
    }
    
    function d_fun($string,$type){
        $string = stripcslashes($string);
        
        if($type == 1) return '<'.$string.'><raid'.$string.'>';
        else return '</raid'.$string.'></'.$string.'>';
    }
}

?>