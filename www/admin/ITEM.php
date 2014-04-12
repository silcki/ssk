<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CATALOGUE');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();
$visible=1;
$VIRTUAL_IMAGE_PATH="/it/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';

if($_REQUEST['e'] == 'RET')
{

$_REQUEST['pid']=$cmf->selectrow_array('select CATALOGUE_ID from ITEM where ITEM_ID=? ',$_REQUEST['id']);
}




if($_REQUEST['e']=='Переместить')
{
?><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="f">
<form action="ITEM.php" method="POST">
<input type="hidden" name="pid" value="<?=$_REQUEST['pid']?>">
<input type="hidden" name="p" value="<?=$_REQUEST['p']?>">
<?
if(isset($_REQUEST['id']))
foreach ($_REQUEST['id'] as $vid){?><input type="hidden" name="id[]" value="<?=$vid?>"/><? } 
?>
<tr bgcolor="#F0F0F0" class="ftr"><td><input type="submit" name="e" value="Перенести" class="gbt bmv"/><input type="submit" name="event" value="Отменить" class="gbt bcancel"/></td></tr>
<tr bgcolor="#FFFFFF"><td><?
print GetTree($cmf,0);
?></td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td><input type="submit" name="e" value="Перенести" class="gbt bmv"/><input type="submit" name="event" value="Отменить" class="gbt bcancel"/></td></tr>
</form></table><?
$visible=0;
}
if($_REQUEST['e']=='Переместить все')
{
?><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="f">
<form action="ITEM.php" method="POST">
<input type="hidden" name="pid" value="<?=$_REQUEST['pid']?>">
<input type="hidden" name="p" value="<?=$_REQUEST['p']?>">
<?
$children = getChildren($cmf,$_REQUEST['pid']);
if($children) $where = " and CATALOGUE_ID IN (".implode(',',$children).")";
else $where = " and CATALOGUE_ID = '".$_REQUEST['pid']."'";

$items = getItems($cmf,$where);
if($items)
{
   foreach ($items as $vid){?><input type="hidden" name="id[]" value="<?=$vid?>"/><? }
}
?>
<tr bgcolor="#F0F0F0" class="ftr"><td><input type="submit" name="e" value="Перенести" class="gbt bmv"/><input type="submit" name="event" value="Отменить" class="gbt bcancel"/></td></tr>
<tr bgcolor="#FFFFFF"><td><?
print GetTree($cmf,0);
?></td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td><input type="submit" name="e" value="Перенести" class="gbt bmv"/><input type="submit" name="event" value="Отменить" class="gbt bcancel"/></td></tr>
</form></table>
<?php
$visible=0;
}
if($_REQUEST['e'] =='Перенести'){
    require_once $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
    $sefu = new CreateSEFU();
    if(!empty($_REQUEST['id']) && !empty($_REQUEST['cid'])) {
        foreach ($_REQUEST['id'] as $vid)
        {
            $OLD_CATALOGUE_ID = $cmf->selectrow_array('select CATALOGUE_ID from ITEM where ITEM_ID=?', $vid);
            $old_cat_site_url = '/cat/view/n/' . $OLD_CATALOGUE_ID . '/';
            $old_site_url = '/cat/item/n/' . $OLD_CATALOGUE_ID . '/it/' . $vid . '/';
            $old_sufe_url = $cmf->selectrow_array('select SEF_URL from SEF_SITE_URL where SITE_URL=?', $old_site_url);

            $new_site_url = '/cat/item/n/' . $_REQUEST['cid'] . '/it/' . $vid . '/';

            $order_ = $cmf->selectrow_array('select max(ORDERING) from ITEM where CATALOGUE_ID=?', $_REQUEST['cid']);
            $order_++;
            $cmf->execute('update ITEM set CATALOGUE_ID=?, ORDERING=? where ITEM_ID=?',$_REQUEST['cid'],$order_, $vid);
            $sefu->applySEFUItem($vid);
            
            $new_sefu_id = $cmf->selectrow_array('select SEF_SITE_URL_ID   from SEF_SITE_URL where SITE_URL=?', $new_site_url);
            $old_cat_sefu_id = $cmf->selectrow_array('select SEF_SITE_URL_ID   from SEF_SITE_URL where SITE_URL=?', $old_cat_site_url);
            
            if (!empty($new_sefu_id)) {
                $cmf->execute('delete from OLD_SEF_URL where SEF_SITE_URL_ID = ?',$old_cat_sefu_id);
                $cmf->execute('delete from SEF_SITE_URL where SEF_SITE_URL_ID = ?',$old_cat_sefu_id);

                $cmf->execute('insert into OLD_SEF_URL set NAME=?, SEF_SITE_URL_ID=?, DATE =  now()',$old_site_url,$new_sefu_id);
                $cmf->execute('insert into OLD_SEF_URL set NAME=?, SEF_SITE_URL_ID=?, DATE =  now()',$old_sufe_url,$new_sefu_id);
            
            }
        }
    }
    $cmf->execute('delete from CAT_ITEM');
    $cmf->Rebuild(array(0));
    $cmf->CheckCount(0);

}



if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from ITEM_LANGS where ITEM_ID=? and ITEM_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{











$cmf->execute('update ITEM_LANGS set CMF_LANG_ID=?,NAME=?,POP_IMAGE_TEXT=?,UNDER_IMAGE_TEXT=?,DESCRIPTION=?,HTML_TITLE=?,HTML_KEYWORDS=?,HTML_DESCRIPTION=? where ITEM_ID=? and ITEM_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['POP_IMAGE_TEXT']),stripslashes($_REQUEST['UNDER_IMAGE_TEXT']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['HTML_TITLE']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('ITEM_LANGS');













$cmf->execute('insert into ITEM_LANGS (ITEM_ID,ITEM_LANGS_ID,CMF_LANG_ID,NAME,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION) values (?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['POP_IMAGE_TEXT']),stripslashes($_REQUEST['UNDER_IMAGE_TEXT']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['HTML_TITLE']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_ITEM_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_POP_IMAGE_TEXT,$V_UNDER_IMAGE_TEXT,$V_DESCRIPTION,$V_HTML_TITLE,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION)=$cmf->selectrow_arrayQ('select ITEM_LANGS_ID,CMF_LANG_ID,NAME,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION from ITEM_LANGS where ITEM_ID=? and ITEM_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(POP_IMAGE_TEXT) &amp;&amp; checkXML(UNDER_IMAGE_TEXT) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_TITLE) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="3" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

EOF;
if(!empty($V_CMF_LANG_ID)) print '<input type="hidden" name="CMF_LANG_ID" value="'.$V_CMF_LANG_ID.'" />';

@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e1" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст над картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="POP_IMAGE_TEXT" rows="7" cols="90">$V_POP_IMAGE_TEXT</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст под картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="UNDER_IMAGE_TEXT" name="UNDER_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_UNDER_IMAGE_TEXT = htmlspecialchars_decode($V_UNDER_IMAGE_TEXT);
echo $V_UNDER_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'UNDER_IMAGE_TEXT', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Title:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="HTML_TITLE" value="$V_HTML_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e1" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;





$visible=0;
}

if($cmf->Param('e1') == 'Новый')
{
list($V_ITEM_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_POP_IMAGE_TEXT,$V_UNDER_IMAGE_TEXT,$V_DESCRIPTION,$V_HTML_TITLE,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION)=array('','','','','','','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(POP_IMAGE_TEXT) &amp;&amp; checkXML(UNDER_IMAGE_TEXT) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_TITLE) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст над картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="POP_IMAGE_TEXT" rows="7" cols="90">$V_POP_IMAGE_TEXT</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст под картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="UNDER_IMAGE_TEXT" name="UNDER_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_UNDER_IMAGE_TEXT = htmlspecialchars_decode($V_UNDER_IMAGE_TEXT);
echo $V_UNDER_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'UNDER_IMAGE_TEXT', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Title:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="HTML_TITLE" value="$V_HTML_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}

if(!isset($_REQUEST['e2']))$_REQUEST['e2']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e2') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING_ from ITEM_PHOTO where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$id);
$cmf->execute('update ITEM_PHOTO set ORDERING_=ORDERING_-1 where ITEM_ID=? and ORDERING_>?',$_REQUEST['id'],$ORDERING);
$cmf->execute('delete from ITEM_PHOTO where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}


if($cmf->Param('e2') == 'UP')
{
$ORDERING=$cmf->selectrow_array('select ORDERING_ from ITEM_PHOTO where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
if($ORDERING>1)
{
$cmf->execute('update ITEM_PHOTO set ORDERING_=ORDERING_+1 where ITEM_ID=? and ORDERING_=?',$_REQUEST['id'],$ORDERING-1);
$cmf->execute('update ITEM_PHOTO set ORDERING_=ORDERING_-1 where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}

if($cmf->Param('e2') == 'DN')
{
$ORDERING=$cmf->selectrow_array('select ORDERING_ from ITEM_PHOTO where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING_) from ITEM_PHOTO');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update ITEM_PHOTO set ORDERING_=ORDERING_-1 where ITEM_ID=? and ORDERING_=?',$_REQUEST['id'],$ORDERING+1);
$cmf->execute('update ITEM_PHOTO set ORDERING_=ORDERING_+1 where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}



if($cmf->Param('e2') == 'Изменить')
{





$cmf->execute('update ITEM_PHOTO set GALLERY_ID=? where ITEM_ID=? and ITEM_PHOTO_ID=?',stripslashes($_REQUEST['GALLERY_ID'])+0,$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e2') == 'Добавить')
{

$_REQUEST['ORDERING_']=$cmf->selectrow_array('select max(ORDERING_) from ITEM_PHOTO where ITEM_ID=?',$_REQUEST['id']);
$_REQUEST['ORDERING_']++;


$_REQUEST['iid']=$cmf->GetSequence('ITEM_PHOTO');







$cmf->execute('insert into ITEM_PHOTO (ITEM_ID,ITEM_PHOTO_ID,GALLERY_ID,ORDERING_) values (?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['GALLERY_ID'])+0,stripslashes($_REQUEST['ORDERING_']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e2') == 'ED')
{
list ($V_ITEM_PHOTO_ID,$V_GALLERY_ID)=$cmf->selectrow_arrayQ('select ITEM_PHOTO_ID,GALLERY_ID from ITEM_PHOTO where ITEM_ID=? and ITEM_PHOTO_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_GALLERY_ID=$cmf->Spravotchnik($V_GALLERY_ID,'select GALLERY_ID,concat(GALLERY_ID,\' - \',NAME) from GALLERY  where STATUS=1 order by concat(GALLERY_ID,\' - \',NAME)');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Связь "Товар - Фото"</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM.php#f2" ENCTYPE="multipart/form-data" onsubmit="return true ;">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="3" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

EOF;
if(!empty($V_CMF_LANG_ID)) print '<input type="hidden" name="CMF_LANG_ID" value="'.$V_CMF_LANG_ID.'" />';

@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e2" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Фото:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="GALLERY_ID">$V_STR_GALLERY_ID</select><br />
</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e2" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;





$visible=0;
}

if($cmf->Param('e2') == 'Новый')
{
list($V_ITEM_PHOTO_ID,$V_GALLERY_ID,$V_ORDERING_)=array('','','');


$V_STR_GALLERY_ID=$cmf->Spravotchnik($V_GALLERY_ID,'select GALLERY_ID,concat(GALLERY_ID,\' - \',NAME) from GALLERY  where STATUS=1 order by concat(GALLERY_ID,\' - \',NAME)');     
@print <<<EOF
<h2 class="h2">Добавление - Связь "Товар - Фото"</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM.php#f2" ENCTYPE="multipart/form-data" onsubmit="return true ;">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Фото:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="GALLERY_ID">$V_STR_GALLERY_ID</select><br />
</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}








if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

      foreach ($_REQUEST['id'] as $id){
         list($image1, $image2) = $cmf->selectrow_array("select IMAGE1, IMAGE2 from ITEM where ITEM_ID=?",$id);
         //Удаление картинок
        if(strchr($image1,"#")){
          $img1 = explode("#",$image1);
          $src1 = $img1[0];
          @unlink('../images/it/'.$src1);
        }
        if(strchr($image2,"#")){
          $img2 = explode("#",$image2);
          $src2 = $img2[0];
          @unlink('../images/it/'.$src2);
        }
      }
      
foreach ($_REQUEST['id'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING from ITEM where ITEM_ID=?',$id);
$cmf->execute('update ITEM set ORDERING=ORDERING-1 where ORDERING>? and CATALOGUE_ID=?',$ORDERING,$_REQUEST['pid']);
$cmf->execute('delete from ITEM where ITEM_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($V_CATALOGUE_ID,$V_ORDERING) =$cmf->selectrow_array('select CATALOGUE_ID,ORDERING from ITEM where ITEM_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{

$sql="select ITEM_ID
           , ORDERING
      from ITEM
      where ORDERING < {$V_ORDERING}
            and CATALOGUE_ID = {$V_CATALOGUE_ID}
      order by ORDERING DESC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update ITEM set ORDERING=? where ITEM_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update ITEM set ORDERING=? where ITEM_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);

}
}

if($_REQUEST['e'] == 'DN')
{
list($V_CATALOGUE_ID,$V_ORDERING) =$cmf->selectrow_array('select CATALOGUE_ID,ORDERING from ITEM where ITEM_ID=?',$_REQUEST['id']);
$V_MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from ITEM where CATALOGUE_ID=?',$V_CATALOGUE_ID);
if($V_ORDERING < $V_MAXORDERING)
{

$sql="select ITEM_ID
           , ORDERING
      from ITEM
      where ORDERING > {$V_ORDERING}
            and CATALOGUE_ID = {$V_CATALOGUE_ID}
      order by ORDERING ASC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update ITEM set ORDERING=? where ITEM_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update ITEM set ORDERING=? where ITEM_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{

		if(empty($_REQUEST['SPECIAL_URL'])){
        require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Translit.class.php');
      $translit = new Translit();
          $_REQUEST['SPECIAL_URL'] = $translit->getLatin($_REQUEST['NAME']);
      }
    
$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from ITEM where CATALOGUE_ID=?',$_REQUEST['pid']);
$_REQUEST['ORDERING']++;
$_REQUEST['id']=$cmf->GetSequence('ITEM');





		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_icon'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_icon_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_icon_y'");
   if(isset($_REQUEST['GEN_IMAGE']) && $_REQUEST['GEN_IMAGE'] && isset($_FILES['NOT_IMAGE1IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE1IMAGE2']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE1IMAGE2',''.$_REQUEST['id'].'_icon', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE'] = $obj_img_resize->new_image_name;
 
	  }
	  else{
			$_REQUEST['IMAGE']=$cmf->PicturePostResize('NOT_',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_icon',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE'],$_REQUEST['HEIGHT_IMAGE']);
	  }
	}
	elseif(isset($_FILES['NOT_IMAGE']['tmp_name']) && $_FILES['NOT_IMAGE']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE',''.$_REQUEST['id'].'_icon', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE'] = $obj_img_resize->new_image_name;

	  }
	  else{
			$_REQUEST['IMAGE']=$cmf->PicturePostResize('NOT_IMAGE',$_REQUEST['IMAGE'],''.$_REQUEST['id'].'_icon',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE'],$_REQUEST['HEIGHT_IMAGE']);
	  }
	}

			
	
	if(isset($_REQUEST['CLR_IMAGE']) && $_REQUEST['CLR_IMAGE']){$_REQUEST['IMAGE']=$cmf->UnlinkFile($_REQUEST['IMAGE'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_m'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE1= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE1='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_small_y'");
	
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_m',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_m',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_m',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_b'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE2= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE2='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_big_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_big_y'");
	
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_b',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	







$_REQUEST['IS_FORM']=isset($_REQUEST['IS_FORM']) && $_REQUEST['IS_FORM']?1:0;
$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into ITEM (ITEM_ID,CATALOGUE_ID,NAME,SPECIAL_URL,IMAGE,IMAGE1,IMAGE2,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,CODE_MAP_AREA,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION,IS_FORM,STATUS_MAIN,STATUS,ORDERING) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['IMAGE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['POP_IMAGE_TEXT']),stripslashes($_REQUEST['UNDER_IMAGE_TEXT']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['CODE_MAP_AREA']),stripslashes($_REQUEST['HTML_TITLE']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),stripslashes($_REQUEST['IS_FORM']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));

$_REQUEST['e'] ='ED';

      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUItem($_REQUEST['id']);
    
}

if($_REQUEST['e'] == 'Изменить')
{

		if(empty($_REQUEST['SPECIAL_URL'])){
        require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Translit.class.php');
      $translit = new Translit();
          $_REQUEST['SPECIAL_URL'] = $translit->getLatin($_REQUEST['NAME']);
      }
 
    





		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_icon'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_icon_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_icon_y'");
   if(isset($_REQUEST['GEN_IMAGE']) && $_REQUEST['GEN_IMAGE'] && isset($_FILES['NOT_IMAGE1IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE1IMAGE2']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE1IMAGE2',''.$_REQUEST['id'].'_icon', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE'] = $obj_img_resize->new_image_name;
 
	  }
	  else{
			$_REQUEST['IMAGE']=$cmf->PicturePostResize('NOT_',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_icon',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE'],$_REQUEST['HEIGHT_IMAGE']);
	  }
	}
	elseif(isset($_FILES['NOT_IMAGE']['tmp_name']) && $_FILES['NOT_IMAGE']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE',''.$_REQUEST['id'].'_icon', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE'] = $obj_img_resize->new_image_name;

	  }
	  else{
			$_REQUEST['IMAGE']=$cmf->PicturePostResize('NOT_IMAGE',$_REQUEST['IMAGE'],''.$_REQUEST['id'].'_icon',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE'],$_REQUEST['HEIGHT_IMAGE']);
	  }
	}

			
	
	if(isset($_REQUEST['CLR_IMAGE']) && $_REQUEST['CLR_IMAGE']){$_REQUEST['IMAGE']=$cmf->UnlinkFile($_REQUEST['IMAGE'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_m'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE1= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE1='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_small_y'");
	
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_m',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_m',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'_m', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_m',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_b'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE2= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE2='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_big_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='item_big_y'");
	
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_b',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	







$_REQUEST['IS_FORM']=isset($_REQUEST['IS_FORM']) && $_REQUEST['IS_FORM']?1:0;
$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


if(!empty($_REQUEST['pid'])) $cmf->execute('update ITEM set CATALOGUE_ID=?,NAME=?,SPECIAL_URL=?,IMAGE=?,IMAGE1=?,IMAGE2=?,POP_IMAGE_TEXT=?,UNDER_IMAGE_TEXT=?,DESCRIPTION=?,CODE_MAP_AREA=?,HTML_TITLE=?,HTML_KEYWORDS=?,HTML_DESCRIPTION=?,IS_FORM=?,STATUS_MAIN=?,STATUS=? where ITEM_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['IMAGE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['POP_IMAGE_TEXT']),stripslashes($_REQUEST['UNDER_IMAGE_TEXT']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['CODE_MAP_AREA']),stripslashes($_REQUEST['HTML_TITLE']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),stripslashes($_REQUEST['IS_FORM']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
else $cmf->execute('update ITEM set NAME=?,SPECIAL_URL=?,IMAGE=?,IMAGE1=?,IMAGE2=?,POP_IMAGE_TEXT=?,UNDER_IMAGE_TEXT=?,DESCRIPTION=?,CODE_MAP_AREA=?,HTML_TITLE=?,HTML_KEYWORDS=?,HTML_DESCRIPTION=?,IS_FORM=?,STATUS_MAIN=?,STATUS=? where ITEM_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['IMAGE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['POP_IMAGE_TEXT']),stripslashes($_REQUEST['UNDER_IMAGE_TEXT']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['CODE_MAP_AREA']),stripslashes($_REQUEST['HTML_TITLE']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),stripslashes($_REQUEST['IS_FORM']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);

$_REQUEST['e'] ='ED';

      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUItem($_REQUEST['id']);
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_ITEM_ID,$V_CATALOGUE_ID,$V_NAME,$V_SPECIAL_URL,$V_IMAGE,$V_IMAGE1,$V_IMAGE2,$V_POP_IMAGE_TEXT,$V_UNDER_IMAGE_TEXT,$V_DESCRIPTION,$V_CODE_MAP_AREA,$V_HTML_TITLE,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION,$V_IS_FORM,$V_STATUS_MAIN,$V_STATUS)=$cmf->selectrow_arrayQ('select ITEM_ID,CATALOGUE_ID,NAME,SPECIAL_URL,IMAGE,IMAGE1,IMAGE2,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,CODE_MAP_AREA,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION,IS_FORM,STATUS_MAIN,STATUS from ITEM where ITEM_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE))
{
  $IM_IMAGE=split('#',$V_IMAGE);
  if(isset($IM_3[1]) && $IM_IMAGE[1] > 150){$IM_IMAGE[2]=$IM_IMAGE[2]*150/$IM_IMAGE[1]; $IM_IMAGE[1]=150;}
}

if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_4[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

if(isset($V_IMAGE2))
{
   $IM_IMAGE2=split('#',$V_IMAGE2);
   if(isset($IM_5[1]) && $IM_IMAGE2[1] > 150){$IM_IMAGE2[2]=$IM_IMAGE2[2]*150/$IM_IMAGE2[1]; $IM_IMAGE2[1]=150;}
}

$V_IS_FORM=$V_IS_FORM?'checked':'';
$V_STATUS_MAIN=$V_STATUS_MAIN?'checked':'';
$V_STATUS=$V_STATUS?'checked':'';
print @<<<EOF
<h2 class="h2">Редактирование - Продукция</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ITEM.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(SPECIAL_URL) &amp;&amp; checkXML(POP_IMAGE_TEXT) &amp;&amp; checkXML(UNDER_IMAGE_TEXT) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(CODE_MAP_AREA) &amp;&amp; checkXML(HTML_TITLE) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="3" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Назад" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование продукта:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Спец URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SPECIAL_URL" value="$V_SPECIAL_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Иконка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE" value="$V_IMAGE" />
<table><tr><td>
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE[0]" width="$IM_IMAGE[1]" height="$IM_IMAGE[2]" /></td>
<td><input type="file" name="NOT_IMAGE" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_icon" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE1[0]))
{
if(strchr($IM_IMAGE1[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE1" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_m" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка бол.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE2[0]))
{
if(strchr($IM_IMAGE2[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE2[0] = !empty($IM_IMAGE2[0]) ? $IM_IMAGE2[0]:0;
$IM_IMAGE2[1] = !empty($IM_IMAGE2[1]) ? $IM_IMAGE2[1]:0;
$IM_IMAGE2[2] = !empty($IM_IMAGE2[2]) ? $IM_IMAGE2[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]" width="$IM_IMAGE2[1]" height="$IM_IMAGE2[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE2" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE2" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_b" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст над картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="POP_IMAGE_TEXT" name="POP_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_POP_IMAGE_TEXT = htmlspecialchars_decode($V_POP_IMAGE_TEXT);
echo $V_POP_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'POP_IMAGE_TEXT', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст под картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="UNDER_IMAGE_TEXT" name="UNDER_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_UNDER_IMAGE_TEXT = htmlspecialchars_decode($V_UNDER_IMAGE_TEXT);
echo $V_UNDER_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'UNDER_IMAGE_TEXT', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Код карты картинки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="CODE_MAP_AREA" rows="7" cols="90">$V_CODE_MAP_AREA</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Title:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="HTML_TITLE" value="$V_HTML_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Форма на странице:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_FORM' value='1' $V_IS_FORM/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной?:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Назад" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;



print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="ITEM.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select ITEM_LANGS_ID,CMF_LANG_ID,NAME from ITEM_LANGS where ITEM_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Наименование</th><td></td></tr>
EOF;
while(list($V_ITEM_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_ITEM_LANGS_ID" /></td>
<td>$V_ITEM_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="ITEM.php?e1=ED&amp;iid=$V_ITEM_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';



print <<<EOF
<a name="f2"></a><h3 class="h3">Связь "Товар - Фото"</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="ITEM.php#f2" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="4">
<input type="submit" name="e2" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e2" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select ITEM_PHOTO_ID,GALLERY_ID from ITEM_PHOTO where ITEM_ID=?  order by ORDERING_',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Фото</th><td></td></tr>
EOF;
while(list($V_ITEM_PHOTO_ID,$V_GALLERY_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_GALLERY_ID=$cmf->selectrow_arrayQ('select concat(GALLERY_ID,\' - \',NAME) from GALLERY where GALLERY_ID=?',$V_GALLERY_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_ITEM_PHOTO_ID" /></td>
<td>$V_ITEM_PHOTO_ID</td><td>$V_GALLERY_ID</td><td nowrap="">
<a href="ITEM.php?e2=UP&amp;iid=$V_ITEM_PHOTO_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}#f2"><img src="i/up.gif" border="0" /></a>
<a href="ITEM.php?e2=DN&amp;iid=$V_ITEM_PHOTO_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}#f2"><img src="i/dn.gif" border="0" /></a>
<a href="ITEM.php?e2=ED&amp;iid=$V_ITEM_PHOTO_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] =='Новый')
{
list($V_ITEM_ID,$V_CATALOGUE_ID,$V_NAME,$V_SPECIAL_URL,$V_IMAGE,$V_IMAGE1,$V_IMAGE2,$V_POP_IMAGE_TEXT,$V_UNDER_IMAGE_TEXT,$V_DESCRIPTION,$V_CODE_MAP_AREA,$V_HTML_TITLE,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION,$V_IS_FORM,$V_STATUS_MAIN,$V_STATUS,$V_ORDERING)=array('','','','','','','','','','','','','','','','','','');


$IM_IMAGE=array('','','');
$IM_IMAGE1=array('','','');
$IM_IMAGE2=array('','','');
$V_IS_FORM='checked';
$V_STATUS_MAIN='';
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Продукция</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ITEM.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(SPECIAL_URL) &amp;&amp; checkXML(POP_IMAGE_TEXT) &amp;&amp; checkXML(UNDER_IMAGE_TEXT) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(CODE_MAP_AREA) &amp;&amp; checkXML(HTML_TITLE) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование продукта:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Спец URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SPECIAL_URL" value="$V_SPECIAL_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Иконка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE" value="$V_IMAGE" />
<table><tr><td>
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE[0]" width="$IM_IMAGE[1]" height="$IM_IMAGE[2]" /></td>
<td><input type="file" name="NOT_IMAGE" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_icon" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE1[0]))
{
if(strchr($IM_IMAGE1[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE1" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_m" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка бол.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE2[0]))
{
if(strchr($IM_IMAGE2[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE2[0] = !empty($IM_IMAGE2[0]) ? $IM_IMAGE2[0]:0;
$IM_IMAGE2[1] = !empty($IM_IMAGE2[1]) ? $IM_IMAGE2[1]:0;
$IM_IMAGE2[2] = !empty($IM_IMAGE2[2]) ? $IM_IMAGE2[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]" width="$IM_IMAGE2[1]" height="$IM_IMAGE2[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE2" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE2" value="1" />Сбросить карт.
<br /><input type="checkbox" name="IS_WATERMARK_b" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст над картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="POP_IMAGE_TEXT" name="POP_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_POP_IMAGE_TEXT = htmlspecialchars_decode($V_POP_IMAGE_TEXT);
echo $V_POP_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'POP_IMAGE_TEXT', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст под картинкой:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="UNDER_IMAGE_TEXT" name="UNDER_IMAGE_TEXT" rows="7" cols="90">
EOF;
$V_UNDER_IMAGE_TEXT = htmlspecialchars_decode($V_UNDER_IMAGE_TEXT);
echo $V_UNDER_IMAGE_TEXT;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'UNDER_IMAGE_TEXT', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Код карты картинки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="CODE_MAP_AREA" rows="7" cols="90">$V_CODE_MAP_AREA</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Title:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="HTML_TITLE" value="$V_HTML_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Форма на странице:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_FORM' value='1' $V_IS_FORM/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной?:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;
$visible=0;
}

if($visible)
{
if(empty($_REQUEST['pid'])) $_REQUEST['pid'] = 0;

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select NAME from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Продукция</h2><form action="ITEM.php" method="POST">
<a href="CATALOGUE.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




$pagesize=35;

if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}

if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{
if(!empty($_REQUEST['pid']) and $_REQUEST['pid']=='all')
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ITEM A where A.CATALOGUE_ID > 0',$_REQUEST['pid']);
}
else
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ITEM A where A.CATALOGUE_ID=?',$_REQUEST['pid']);

}
$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 $start=1;
if($_REQUEST['p']>15){$start=$_REQUEST['p']-15;}
 
 for($i=$start;$i<=$_REQUEST['pcount'] && ($i-$start)<31;$i++)
 {
  if($i==$_REQUEST['p']) { print <<<EOF
- <b class="red">$i</b>
EOF;
 } else { print <<<EOF
- <a class="t" href="ITEM.php?pid={$_REQUEST['pid']}&amp;count={$_REQUEST['count']}&amp;p={$i}&amp;pcount={$_REQUEST['pcount']}{$filters}">$i</a>
EOF;
  }
 }
print <<<EOF
&#160;из <span class="red">({$_REQUEST['pcount']})</span><br />
EOF;
}

if(!empty($_REQUEST['pid']) and $_REQUEST['pid'] == 'all')
{
$sth=$cmf->execute('select A.ITEM_ID,A.NAME,A.SPECIAL_URL,A.IMAGE,A.IS_FORM,A.STATUS_MAIN,A.STATUS from ITEM A
where A.CATALOGUE_ID > 0  order by A.ORDERING limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);
}
else
{
$sth=$cmf->execute('select A.ITEM_ID,A.NAME,A.SPECIAL_URL,A.IMAGE,A.IS_FORM,A.STATUS_MAIN,A.STATUS from ITEM A
where A.CATALOGUE_ID=?  order by A.ORDERING limit ?,?',$_REQUEST['pid'],$pagesize*($_REQUEST['p']-1),$pagesize);

}





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="7">
EOF;

if ($cmf->W)
@print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" /><input type="submit" name="e" value="Переместить" class="gbt bmv" />
EOF;

if ($cmf->D)
  print '<input type="submit" name="e" onclick="return dl();" value="Удалить" class="gbt bdel" />';
  
@print <<<EOF
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
</td></tr>
EOF;

print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Наименование продукта</th><th>Спец URL</th><th>Иконка</th><th>Выводить на главной?</th><td></td></tr>
EOF;


if($sth)
while(list($V_ITEM_ID,$V_NAME,$V_SPECIAL_URL,$V_IMAGE,$V_IS_FORM,$V_STATUS_MAIN,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{



if(isset($V_IMAGE))
{
   $IM_4=split('#',$V_IMAGE);
   if(isset($IM_4[1]) && $IM_4[1] > 150){$IM_4[2]=$IM_4[2]*150/$IM_4[1]; $IM_4[1]=150;
   $V_IMAGE="<img src=\"/images$VIRTUAL_IMAGE_PATH$IM_4[0]\" width=\"$IM_4[1]\" height=\"$IM_4[2]\">";}
}

if(!$V_STATUS_MAIN) {$V_STATUS_MAIN='Нет';} else {$V_STATUS_MAIN='Да';}
                        

if($V_STATUS == 1){$V_COLOR='#FFFFFF';} else {$V_COLOR='#a0a0a0';}



@print <<<EOF
<tr bgcolor="$V_COLOR">
<td><input type="checkbox" name="id[]" value="$V_ITEM_ID" /></td>
<td>$V_ITEM_ID</td><td>$V_NAME</td><td>$V_SPECIAL_URL</td><td>$V_IMAGE</td><td>$V_STATUS_MAIN</td><td nowrap="">
<a href="ITEM.php?e=UP&amp;id=$V_ITEM_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/up.gif" border="0" /></a>
<a href="ITEM.php?e=DN&amp;id=$V_ITEM_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
@print <<<EOF
<a href="ITEM.php?e=ED&amp;id=$V_ITEM_ID&amp;pid={$_REQUEST['pid']}&amp;p={$_REQUEST['p']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

<a href="ITEM_ELEMENTS.php?pid=$V_ITEM_ID"><img src="img/main_i_plus.gif" border="0" title="Дополнительные элементы конструкции" /></a></td></tr>
EOF;
}
@print <<<EOF
        </table>
EOF;
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();

function GetTree($cmf,$id)
{
$sth=$cmf->execute('select CATALOGUE_ID,NAME from CATALOGUE where PARENT_ID=? order by ORDERING',$id);
if($sth)
{
$ret='<ul>';
while(list($V_CATALOGUE_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.=<<<EOF
<dl><input type="radio" name="cid" value="$V_CATALOGUE_ID">&#160;$V_NAME</dl>
EOF
.GetTree($cmf,$V_CATALOGUE_ID);
}
$ret.='</ul>';
}
return $ret;
}

function getChildren($cmf,$id)
{
  $path = array();
  $sth=$cmf->execute('select CATALOGUE_ID from CATALOGUE where PARENT_ID=? and STATUS=1 order by CATALOGUE_ID',$id);
  if(mysql_num_rows($sth))
  {
     while($row=mysql_fetch_array($sth))
     {
       if($row['CATALOGUE_ID']>0)
       {
          $path[] = $row['CATALOGUE_ID'];
          $path = array_merge($path,getChildren($row['CATALOGUE_ID']));
       }
     }
  }
  return $path;
}

function getItems($cmf,$where)
{
   $items = array();
   $sth = $cmf->execute("select ITEM_ID from ITEM where 1 ".$where);
   if(mysql_num_rows($sth))
   {
      while($row=mysql_fetch_array($sth))
      {
         $items[] = $row['ITEM_ID'];
      }
   }
   return $items;
}

function getParents($id)
         {
          global $cmf;
          $path = array();
          $sth=$cmf->execute('select PARENT_ID,NAME,CATALOGUE_ID from CATALOGUE where CATALOGUE_ID='.$id.' and STATUS=1 order by NAME');
          if(mysql_num_rows($sth))
          {
             while(list($V_ID,$V_VAL,$V_CID)=mysql_fetch_array($sth))
             {
                if($V_ID>0)
                {
                   $path[] = $V_ID;
                   $path = array_merge($path,getParents($V_ID));
                }
             }
          }
          return $path;
         }



function ___GetTree($cmf,$pid,$id)
{
$id+=0;
$ret='';
$sth=$cmf->execute('select CATALOGUE_ID,NAME from CATALOGUE where =? order by ORDERING',$pid);
while(list($V_CATALOGUE_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_CATALOGUE_ID?'<input type="radio" name="cid" value="'.$V_CATALOGUE_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_CATALOGUE_ID.'" />')."&#160;$V_NAME</li>".___GetTree($cmf,$V_CATALOGUE_ID,$id);
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>

?>
