<?php

$skin_separator = <<<HTML
<div class="clear"></div>
<div class="separator"></div>
HTML;

$skin_info = <<<HTML
<div class="skin_info">
 <table>
  <td valign="top" width="65" align="center"><img src="{THEME}/images/alert.png" /></td>
  <td valign="top"><h3>Информация</h3><span>{name}</span><div class="clear"></div></td>
 </table>
</div>
HTML;

$skin_button = <<<HTML
<div class="button{position}" style="{style}"{function}>
 <div>
  <div>
   <div>{name}</div>
  </div>
 </div>
</div>
HTML;

$skin_input = <<<HTML
<div class="input{position}" style="{style}">
 <div>
  <input type="{type}" name="{name}" value="{value}"{class}{function} />
 </div>
</div>
HTML;

$skin_textarea = <<<HTML
<div class="textarea{position}" style="{style}">
 <textarea type="text" style="resize: none;width:100%" name="{name}"{cols}{rows}{function}>{value}</textarea>
</div>
HTML;

$skin_select = <<<HTML
<div class="select{position}" style="width:{width=50};{style}" onclick="select_show_list(this)">
 <div>
  <div class="select_name_{id}">
   <span>{value_name}</span>
   <input type="hidden" name="{name}" value="{value}" />
  </div>
 </div>
 [list]<div class="list_select"><ul style="width:{width=43};">{list}</ul></div>[/list]
</div>

[option]<li class="list_select_{id} {selected}" onclick="select_list('{id}','{value}','{name}',this);{function}">{name}</li>[/option]
HTML;

$skin_tabs = <<<HTML
<ul class="tabs">
 [li]<li class="{class} tabs_li_hash_{hash}" onclick="tabs('{hash}','{hash}_{id}',this){function}"><div><div>{name}</div></div></li>[/li]
</ul>
<div class="tabs_line"></div>
[content]<div class="tabs_content tabs_hash_{hash} {class}" id="tabs_{hash}_{id}">{content}</div>[/content]
HTML;

$skin_table = <<<HTML
<table class="table" cellpading="0" cellspacing="0"{function}>
 [name]<tr class="table_name">[td]<td colspan="{colspan}" {width}>{name}</td>[/td]</tr>[/name]
 {content}
</table>
HTML;

$skin_row_table = <<<HTML
<tr class="row_{row}"{function}>
 [td]<td{colspan} class="row_td" {width}{valign}>{content}</td>[/td]
</tr> 
HTML;

$skin_row_name = <<<HTML
<tr class="row_{row} tr_row_name"{function}>
 <td colspan="{colspan}" class="row_td" {width}><h3 class="row_name">{content}<br><span>{description}</span></h3></td>
</tr> 
HTML;

$skin_file = <<<HTML
<div class="input_file{position}" style="{style}">
<input type="file" name="{name}"{class}{function} />
</div>
HTML;

//===========================================================================//
require_once (CLASS_DIR . '/skin/skin.class.php');
//===========================================================================//
?>