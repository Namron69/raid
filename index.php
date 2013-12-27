<?
@ob_start();
@session_start ();

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define ( 'ROOT_DIR', dirname(__FILE__) );
define ( 'THIS_DIR', dirname(__FILE__) );
define ( 'CLASS_DIR', ROOT_DIR.'/class' );

header('Content-type: text/html; charset=UTF-8');

$showIndex = true;

require_once (CLASS_DIR . '/skin/skin.php');
require_once (CLASS_DIR . '/function.php');
include_once(CLASS_DIR.'/login.php');

$is_logged_info = $is_logged;
$is_logged = login_alt($is_logged);

$openPrj = (isset($_GET['openPrj']) && $_GET['openPrj'] !== '') ? preg_replace("'[^a-z0-9_]'si",'',$_GET['openPrj']) : false;

if($openPrj) $_SESSION['open_prj'] = $openPrj;
if(isset($_GET['new'])) unset($_SESSION['open_prj']);

$dataPrj = unserialize(@file_get_contents(ROOT_DIR.'/userData/userProject/'.$is_logged.'/'.$openPrj.'/data'));

?>
<!DOCTYPE HTML>
<head>
<title>RAID - Copyright © 2012 SL-CMS.COM Designed by QWARP</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="RAID - Визуальный редактор шаблонов , A visual template editor" />
<meta name="keywords" content="визуальный,редактор" />
<link rel="shortcut icon" href="lib/images/favicon.ico" />
<?
print html_css('lib/css',array(
    'style',
    'contextmenu',
    'widget',
    'loading',
    'tools',
    'interface',
    'tooltip'
));
print html_css('system',array(
    'form',
    'system'
));

print html_js('lib/js',array(
    'jquery',
    'jquery-ui-1.8.16.custom.min',
    'layer',
    'ready',
    'function',
    'contextmenu',
    'scroll',
    'image',
    'widget',
    'swfupload',
    'upload',
    'iframe',
    'file',
    'libraly',
    'interface',
    'api',
    'tooltip'
));

if(!$is_logged) header( "Location: ../index.php" );

?>
<script src="class/godata.js.php?login=<?=$is_logged;?>" id="libraly_script" type="text/javascript"></script>
<script>
var user_name_var = '<?=$is_logged;?>';
image.LastDir = '';
iframe.last_url = '<?=$dataPrj['url']?>';
file.prev_url = '<?=$dataPrj['prev_url']?>';
file.prev_key = '<?=$dataPrj['prev_key']?>';
<?=($openPrj ? "file.name = '".$openPrj."';"."\n".'var openPrj = true;'."\n" : 'var openPrj = false;'."\n")?>
<?=buldFleWrite()?>
</script>

</head>
<body>

<? if(!$openPrj){ ?>
 <script type="text/javascript" src="lib/js/loading.js"></script>
<?}?>

<div class="layerColor padding_5 TopMenu">
 <img src="lib/images/logoSmall_03.png" class="left" />
 <ul class="ul globalManu left">
  <li>Файл
   <div>
    <span onclick="iframe.open_url()">Открыть&nbsp;-&nbsp;URL</span>
    <span onclick="file.save()">Сохранить&nbsp;</span>
    <span onclick="file.saveAs()">Сохранить&nbsp;&nbsp;Как</span>
    <span onclick="window.location = 'index.php?new'">Новый&nbsp;</span>
    <span class="line"></span>
    <span onclick="file.compiler()">Компилировать</span>
    <span onclick="iframe.save_html_main()">Сохранить&nbsp;результат</span>
   </div>
  </li>
  <li>Кодировка
   <div>
    <span onclick="file.setChar(0)">Без&nbsp;кодировки</span>
    <span onclick="file.setChar(1)">Windows&nbsp;1251</span>
    <span onclick="file.setChar(2)">UTF-8</span>
   </div>
  </li>
  <li>Javascript
   <div>
    <span onclick="iframe.fun('reload',{prj:file.name,js:1});">Отключить</span>
    <span onclick="iframe.fun('reload',{prj:file.name,js:2});">Включить</span>
   </div>
  </li>
  <li>Превью
   <div>
    <span onclick="file.chPrev()">Указать&nbsp;адрес</span>
    <span onclick="file.chPrevKey()">Указать&nbsp;ключ</span>
   </div>
  </li>
  <li>Файлы
   <div>
    <span onclick="image.dialog()">Загрузить</span>
    <span onclick="image.createDir()">Создать&nbsp;папку</span>
    <span onclick="image.ziped()">Запаковать&nbsp;в&nbsp;zip</span>
   </div>
  </li>
  <li>Окна
   <div>
    <span onclick="image.dialog()">Загрузить&nbsp;изображения</span>
    <span onclick="file.allPrj()">Мои&nbsp;проекты</span>
   </div>
  </li>
  <li>Помощь
   <div>
    <span onclick="abount()">О&nbsp;скрипте</span>
   </div>
  </li>
 </ul>
 
 <ul class="ul globalManu right">
  <?=isset($_SESSION['charset']) ? '<li>Кодировка: <b id="charSet">'.$_SESSION['charset'].'</b></li>' : '<li>Кодировка: <b id="charSet">Windows 1251</b></li>'?>
  <?=($openPrj) ? '<li>Название проекта: <b>'.$openPrj.'</b></li>' : '';?>
  <li>Вошли как: <b><?=$is_logged_info?></b></li>
  <li><a href="index.php?logout">Выход</a></li>
 </ul>
 <div class="clear"></div>
</div>


<div class="layerColor bgMaX PrewDonwload">
 <div class="tabBg position_r"><ul class="ul DisplayX menuLetters"><li class="active">Библиотеки</li></ul><div class="tab_tools position libralyOp"></div></div>
 <div class="padding_10">
 <ul class="ul rideOr" id="modulsUp">
 <?=lastBuldLib();?>
</ul>
 </div>
</div>

<table cellpadding="0" cellspacing="0" class="ul globalContent">
 <tr>
  <td width="30">
   <ul class="ul toolsUl">
    <li class="active" onclick="toolsAction(this,'select')"><img src="lib/images/toolsico/select_19.png" tooltip="Искать" /></li>
    <li onclick="toolsAction(this,'click')"><img src="lib/images/toolsico/click.png" tooltip="Принудительно" /></li>
    <li onclick="toolsAction(this,'del')"><img src="lib/images/toolsico/delete_19.png" tooltip="Удалить" /></li>
    <li onclick="toolsAction(this,'del_is')"><img src="lib/images/toolsico/delete_is.png" tooltip="Удалить тег" /></li>
    <li onclick="toolsAction(this,'clone')"><img src="lib/images/toolsico/clone.png" tooltip="Клонировать" /></li>
    <li onclick="toolsAction(this,'copy')"><img src="lib/images/toolsico/copy.png" tooltip="Копировать" /></li>
    <li onclick="toolsAction(this,'paste')"><img src="lib/images/toolsico/paste.png" tooltip="Вставить" /></li>
   </ul>
  </td>
  <td class="bgMaY tabsPrjMenu">  
  <?=$skin->tabs(3,"[1::Результат],[2::Файлы],[3::Превью]",array(
          1=>sourceView(),
          2=>buldImages(),
          3=>raidPrew()
  ),'[2::image.update();],[3::loadPrewEngine()]');
  ?>
  </td>
  <td width="240" class="bgMaY">
  <?=$skin->tabs(3,"[1::Свойства],[2::CSS],[3::Мои проекты <img src=\"lib/images/tab_tools_41.png\" onmousedown=\"file.allPrj()\" />]",array(
          1=>scroll('<div class="visualSeting">Выберите объект для получения свойств этого объекта</div>','option'),
          2=>scroll('<div class="visualSetingCss">Выберите объект для получения свойств этого объекта</div>','option_css'),
          3=>scroll(myProject(),'project'),
  ),'[1::initScroll(\'option\',{height:layer.conteiner - 44})],[2::initScroll(\'option_css\',{height:layer.conteiner - 44})],[3::initScroll(\'project\',{height:layer.conteiner - 44})]');
  ?>
  </td>
 </tr>
</table>

<div class="layerColor bgMaX mTop myFilesTab">
 <div class="padding_10">
 <?=lastPrj();?>
 </div>
</div>

<div class="layerColor footer">
 <div class="tabBg"><div class="statusBar left"></div><div class="right Copyright">Copyright © 2011 SL-CMS.COM. All Rights Reserved. Designed by QWARP</div></div>
</div>

<div class="loadingPrj">
  <div class="bgLayer">
  </div>
</div>

<div class="position_f bottom left" id="is_loading_hide"></div>

<script>
$('.loadingPrj').fadeOut(300);
</script>

<div class="interface position_f top left width height hide">
    <div class="padding_15">
        <div class="position_r conteiner">
            <div class="bar position_a top left width">
                <ul class="ul i_name">
            
                </ul>
            </div>
            <div class="close position_a top right" onclick="in_ce.close()"></div>
            <div class="i_wrap">
                <div class="i_content">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        
        $('body').on('touchmove', function(event){
            event.preventDefault();
            event.stopPropagation();
        });
        
    }(jQuery));
    
</script>

</body>
</html>