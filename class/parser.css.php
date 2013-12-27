<?
class parserCSS {
    
    var $allCss = array();
    
    function remove($string){
        $string = preg_replace("'\/\*(.+?)\*\/'si",'',$string);
        $string = preg_replace("'\/\*'si",'',$string);
        $string = preg_replace("'\*\/'si",'',$string);
        return str_replace("\n",'',trim($string));
    }
    
    function parseString($string,$filename = false){
        $results = array();
        
        $filename = $filename ? $filename : 'style';
        
        preg_match_all("'(.+?)\s*\{\s*(.+?)\s*\}'si", $string, $matches);
        
        foreach($matches[0] as $i=>$original)
            foreach(explode(';', $matches[2][$i]) as $attr)
                if (strlen($attr) > 0){
                    $exp = explode(":",$attr);
                    
                    $name = $exp[0];
                    
                    unset($exp[0]);
                    
                    $value = implode(":",$exp);
                    
                    $results[$this->remove(trim($matches[1][$i]))][$this->remove(trim($name))] = trim($value);
                }
        
        if(!isset($this->allCss[$filename])) $this->allCss[$filename] = array();
        
        $this->allCss[$filename] = $this->allCss[$filename] + $results;
        
        return $results;
    }
    
    function find($selector,$tag = false){
        $result = array();
        $selector = trim($selector);
        
        foreach($this->allCss as $filename=>$style){
            foreach($style as $key=>$arr){
                $key = trim($key);
                
                if($tag) $exp_tag = explode(",",$key);
                
                $finded = @strstr($key,$selector);
                
                $exp = explode(" ",$finded);
                
                if(($finded && $exp[0] == $selector) || ($tag && in_array(strtolower($tag),$exp_tag))){
                    $result[$filename][$key] = $arr;
                }
            }
        }
        return $result;
    }
}
?>