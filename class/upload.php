<?
$user_name = preg_replace("'[^a-z0-9_]'si",'',$_POST['user_name']);
$uploadDir = isset($_POST['dir']) && $_POST['dir'] !== '' ? base64_decode($_POST['dir']) : dirname(dirname(__FILE__)).'/userData/userData/'.$user_name;

$uploadDir = str_replace("\\",'/',$uploadDir);

if(!preg_match('/userData\/userData/',$uploadDir)) exit;

$maxFileSize = 10 * 1024 * 1024; //10 MB

$allowed = array('jpg','gif','png','txt','css','js','html','tmp','php');

if(isset($_FILES)) {
    $name = $_FILES['Filedata']['name'];
    
    $ex = end(explode('.',$name));
    
    if(in_array($ex,$allowed)){
        
        if ($maxFileSize < $_FILES['Filedata']['size']) {  return;  }
                
        if (is_uploaded_file($_FILES['Filedata']['tmp_name'])){
        
            $fileName = $uploadDir.'/'.$name;
            
            move_uploaded_file($_FILES['Filedata']['tmp_name'], $fileName);
    
            echo $name;
        }
    }
}
	
?>