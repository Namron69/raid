<?
class curl{
    var $seting = array(
        'TIMEOUT'=>8,
        'RETURNTRANSFER'=>1,
        'FOLLOWLOCATION'=>1,
        'HEADER'=>0,
        'REFERER'=>false,
        'ENCODING'=>'gzip',
        'COOKIE_URL'=>'',
        'COOKIE'=>false,
        'USERAGENT'=>"Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8)",
        'PROXY'=>false,
        'CONVERT'=>false,
        'CONVERT_TO'=>false,
        'REMOVE_SCRIPT'=>true
    );
    
    function getpage($url,$mass = array()){
        global $cron;
        
        $post = (isset($mass['post'])) ? $mass['post'] : false;
        $cook = (isset($mass['cook'])) ? $mass['cook'] : false;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $this->urlencode($url));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->seting['RETURNTRANSFER']);
        curl_setopt($ch, CURLOPT_HEADER, $this->seting['HEADER']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->seting['FOLLOWLOCATION']);
        
        if($this->seting['REFERER']) curl_setopt($ch, CURLOPT_REFERER, $this->seting['REFERER']);
        else                         curl_setopt($ch, CURLOPT_REFERER, $url);
        
        curl_setopt($ch, CURLOPT_ENCODING, $this->seting['ENCODING']);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->seting['USERAGENT']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->seting['TIMEOUT']);
        
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        if($this->seting['COOKIE']){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->seting['COOKIE_URL']);
            curl_setopt($ch, CURLOPT_COOKIEFILE,$this->seting['COOKIE_URL']);
        }
        
        if($this->seting['PROXY']){
            @curl_setopt($ch, CURLOPT_PROXY, $this->seting['PROXY']['ip']);
            @curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->seting['PROXY']['login'].':'.$this->seting['PROXY']['password']);
        }
        
        $TMP = curl_exec($ch);
        
        if (curl_errno($ch)){
            print "CURL returned error: ".curl_error($ch);
      	     
            return false;
        }
        
        curl_close($ch);
        
        if($this->seting['CONVERT']){
            $in_charset = 'windows-1251';
            
            if (preg_match('/charset=([^ ]*)[\"\']/i', $TMP, $response)){
    		    $in_charset = $response[1];
    	    }
    	    if($in_charset == 'UTF-8' or $in_charset == 'utf-8'){
    	        $TMP = mb_convert_encoding($TMP, 'windows-1251', $in_charset);
            }
        }
        
        if($this->seting['CONVERT_TO']){
            if($this->seting['CONVERT_TO'] == 'cp')
                $TMP = mb_convert_encoding($TMP, 'windows-1251', 'UTF-8');
            else
                $TMP = mb_convert_encoding($TMP, 'UTF-8', 'windows-1251');
        }
        if($this->seting['REMOVE_SCRIPT']) $TMP = preg_replace("'<script[^>]*>.*?<\/script>'si",'',$TMP);
        
        return $TMP;
    }
    
    function urlencode($url){
        $purl = parse_url($url);
        
        if(!$purl['host']) return 'http://'.$url;
        else return $url;
    }
}
?>