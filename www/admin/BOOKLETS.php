<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('BOOKLETS');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
$VIRTUAL_IMAGE_PATH="/booklets/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';




if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING from BOOKLETS_PAGES where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$id);
$cmf->execute('update BOOKLETS_PAGES set ORDERING=ORDERING-1 where BOOKLETS_ID=? and ORDERING>?',$_REQUEST['id'],$ORDERING);
$cmf->execute('delete from BOOKLETS_PAGES where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}


if($cmf->Param('e1') == 'UP')
{
$ORDERING=$cmf->selectrow_array('select ORDERING from BOOKLETS_PAGES where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
if($ORDERING>1)
{
$cmf->execute('update BOOKLETS_PAGES set ORDERING=ORDERING+1 where BOOKLETS_ID=? and ORDERING=?',$_REQUEST['id'],$ORDERING-1);
$cmf->execute('update BOOKLETS_PAGES set ORDERING=ORDERING-1 where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}

if($cmf->Param('e1') == 'DN')
{
$ORDERING=$cmf->selectrow_array('select ORDERING from BOOKLETS_PAGES where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from BOOKLETS_PAGES');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update BOOKLETS_PAGES set ORDERING=ORDERING-1 where BOOKLETS_ID=? and ORDERING=?',$_REQUEST['id'],$ORDERING+1);
$cmf->execute('update BOOKLETS_PAGES set ORDERING=ORDERING+1 where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}



if($cmf->Param('e1') == 'Изменить')
{




$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update BOOKLETS_PAGES set NAME=?,DESCRIPTION=?,STATUS=? where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{

$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from BOOKLETS_PAGES where BOOKLETS_ID=?',$_REQUEST['id']);
$_REQUEST['ORDERING']++;


$_REQUEST['iid']=$cmf->GetSequence('BOOKLETS_PAGES');





$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;



$cmf->execute('insert into BOOKLETS_PAGES (BOOKLETS_ID,BOOKLETS_PAGES_ID,NAME,DESCRIPTION,STATUS,ORDERING) values (?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_BOOKLETS_PAGES_ID,$V_NAME,$V_DESCRIPTION,$V_STATUS)=$cmf->selectrow_arrayQ('select BOOKLETS_PAGES_ID,NAME,DESCRIPTION,STATUS from BOOKLETS_PAGES where BOOKLETS_ID=? and BOOKLETS_PAGES_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="BOOKLETS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />


<input type="hidden" name="p" value="{$_REQUEST['p']}" />

EOF;
if(!empty($V_CMF_LANG_ID)) print '<input type="hidden" name="CMF_LANG_ID" value="'.$V_CMF_LANG_ID.'" />';

@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="DESCRIPTION" name="DESCRIPTION" rows="7" cols="90">
EOF;
$V_DESCRIPTION = htmlspecialchars_decode($V_DESCRIPTION);
echo $V_DESCRIPTION;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'DESCRIPTION', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;





$visible=0;
}

if($cmf->Param('e1') == 'Новый')
{
list($V_BOOKLETS_PAGES_ID,$V_NAME,$V_DESCRIPTION,$V_STATUS,$V_ORDERING)=array('','','','','');


$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="BOOKLETS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="DESCRIPTION" name="DESCRIPTION" rows="7" cols="90">
EOF;
$V_DESCRIPTION = htmlspecialchars_decode($V_DESCRIPTION);
echo $V_DESCRIPTION;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'DESCRIPTION', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}









if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
list($ORDERING)=$cmf->selectrow_array('select ORDERING from BOOKLETS where BOOKLETS_ID=?',$id);
$cmf->execute('update BOOKLETS set ORDERING=ORDERING-1 where ORDERING>?',$ORDERING);
$cmf->execute('delete from BOOKLETS where BOOKLETS_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from BOOKLETS where BOOKLETS_ID=?',$_REQUEST['id']);
if($ORDERING>1)
{
$cmf->execute('update BOOKLETS set ORDERING=ORDERING+1 where ORDERING=?',$ORDERING-1);
$cmf->execute('update BOOKLETS set ORDERING=ORDERING-1 where BOOKLETS_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from BOOKLETS where BOOKLETS_ID=?',$_REQUEST['id']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from BOOKLETS');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update BOOKLETS set ORDERING=ORDERING-1 where ORDERING=?',$ORDERING+1);
$cmf->execute('update BOOKLETS set ORDERING=ORDERING+1 where BOOKLETS_ID=?',$_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{


$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from BOOKLETS');
$_REQUEST['ORDERING']++;


$_REQUEST['id']=$cmf->GetSequence('BOOKLETS');



		
				
    if(isset($_FILES['NOT_IMAGE_NAME']['tmp_name']) && $_FILES['NOT_IMAGE_NAME']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_img',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_img',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE_NAME']) && $_REQUEST['CLR_IMAGE_NAME']){$_REQUEST['IMAGE_NAME']=$cmf->UnlinkFile($_REQUEST['IMAGE_NAME'],$VIRTUAL_IMAGE_PATH);}
	
$_REQUEST['FILE_NAME']=$cmf->FilePost('NOT_FILE_NAME',$_REQUEST['FILE_NAME'],''.$_REQUEST['id'].'_doc',$VIRTUAL_IMAGE_PATH);
	if(isset($_REQUEST['CLR_FILE_NAME']) && $_REQUEST['CLR_FILE_NAME']){$_REQUEST['FILE_NAME']=$cmf->UnlinkFile($_REQUEST['FILE_NAME'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into BOOKLETS (BOOKLETS_ID,NAME,IMAGE_NAME,FILE_NAME,PATH_FILE,STATUS,ORDERING) values (?,?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE_NAME']),stripslashes($_REQUEST['FILE_NAME']),stripslashes($_REQUEST['PATH_FILE']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{




		
				
    if(isset($_FILES['NOT_IMAGE_NAME']['tmp_name']) && $_FILES['NOT_IMAGE_NAME']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_img',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE_NAME']=$cmf->PicturePost('NOT_IMAGE_NAME',$_REQUEST['IMAGE_NAME'],''.$_REQUEST['id'].'_img',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE_NAME']) && $_REQUEST['CLR_IMAGE_NAME']){$_REQUEST['IMAGE_NAME']=$cmf->UnlinkFile($_REQUEST['IMAGE_NAME'],$VIRTUAL_IMAGE_PATH);}
	
$_REQUEST['FILE_NAME']=$cmf->FilePost('NOT_FILE_NAME',$_REQUEST['FILE_NAME'],''.$_REQUEST['id'].'_doc',$VIRTUAL_IMAGE_PATH);
	if(isset($_REQUEST['CLR_FILE_NAME']) && $_REQUEST['CLR_FILE_NAME']){$_REQUEST['FILE_NAME']=$cmf->UnlinkFile($_REQUEST['FILE_NAME'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update BOOKLETS set NAME=?,IMAGE_NAME=?,FILE_NAME=?,PATH_FILE=?,STATUS=? where BOOKLETS_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE_NAME']),stripslashes($_REQUEST['FILE_NAME']),stripslashes($_REQUEST['PATH_FILE']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_BOOKLETS_ID,$V_NAME,$V_IMAGE_NAME,$V_FILE_NAME,$V_PATH_FILE,$V_STATUS)=
$cmf->selectrow_arrayQ('select BOOKLETS_ID,NAME,IMAGE_NAME,FILE_NAME,PATH_FILE,STATUS from BOOKLETS where BOOKLETS_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE_NAME))
{
   $IM_IMAGE_NAME=split('#',$V_IMAGE_NAME);
   if(isset($IM_2[1]) && $IM_IMAGE_NAME[1] > 150){$IM_IMAGE_NAME[2]=$IM_IMAGE_NAME[2]*150/$IM_IMAGE_NAME[1]; $IM_IMAGE_NAME[1]=150;}
}

$IM_FILE_NAME=split('#',$V_FILE_NAME);
$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Буклеты</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="BOOKLETS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PATH_FILE);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE_NAME" value="$V_IMAGE_NAME" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE_NAME[0]))
{
if(strchr($IM_IMAGE_NAME[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE_NAME[0] = !empty($IM_IMAGE_NAME[0]) ? $IM_IMAGE_NAME[0]:0;
$IM_IMAGE_NAME[1] = !empty($IM_IMAGE_NAME[1]) ? $IM_IMAGE_NAME[1]:0;
$IM_IMAGE_NAME[2] = !empty($IM_IMAGE_NAME[2]) ? $IM_IMAGE_NAME[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]" width="$IM_IMAGE_NAME[1]" height="$IM_IMAGE_NAME[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE_NAME" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE_NAME" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Файл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="FILE_NAME" value="$V_FILE_NAME" />
<table><tr><td><input type="file" name="NOT_FILE_NAME" size="1" /><br /><input type="checkbox" name="CLR_FILE_NAME" value="1" />Сбросить файл.</td>
<td>&#160;</td><td>size=$IM_FILE_NAME[1]<br />/images/$VIRTUAL_IMAGE_PATH$IM_FILE_NAME[0]
</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь к файлу:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PATH_FILE" value="$V_PATH_FILE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />

EOF;




print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="BOOKLETS.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="4">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select BOOKLETS_PAGES_ID,NAME,STATUS from BOOKLETS_PAGES where BOOKLETS_ID=?  order by ORDERING',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Название</th><td></td></tr>
EOF;
while(list($V_BOOKLETS_PAGES_ID,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

@print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="iid[]" value="$V_BOOKLETS_PAGES_ID" /></td>
<td>$V_BOOKLETS_PAGES_ID</td><td>$V_NAME</td><td nowrap="">
<a href="BOOKLETS.php?e1=UP&amp;iid=$V_BOOKLETS_PAGES_ID&amp;id={$_REQUEST['id']}#f1"><img src="i/up.gif" border="0" /></a>
<a href="BOOKLETS.php?e1=DN&amp;iid=$V_BOOKLETS_PAGES_ID&amp;id={$_REQUEST['id']}#f1"><img src="i/dn.gif" border="0" /></a>
<a href="BOOKLETS.php?e1=ED&amp;iid=$V_BOOKLETS_PAGES_ID&amp;id={$_REQUEST['id']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_BOOKLETS_ID,$V_NAME,$V_IMAGE_NAME,$V_FILE_NAME,$V_PATH_FILE,$V_STATUS,$V_ORDERING)=array('','','','','','','');

$IM_IMAGE_NAME=array('','','');
$IM_FILE_NAME=split('#',$V_FILE_NAME);
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Буклеты</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="BOOKLETS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PATH_FILE);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE_NAME" value="$V_IMAGE_NAME" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE_NAME[0]))
{
if(strchr($IM_IMAGE_NAME[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE_NAME[0] = !empty($IM_IMAGE_NAME[0]) ? $IM_IMAGE_NAME[0]:0;
$IM_IMAGE_NAME[1] = !empty($IM_IMAGE_NAME[1]) ? $IM_IMAGE_NAME[1]:0;
$IM_IMAGE_NAME[2] = !empty($IM_IMAGE_NAME[2]) ? $IM_IMAGE_NAME[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE_NAME[0]" width="$IM_IMAGE_NAME[1]" height="$IM_IMAGE_NAME[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE_NAME" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE_NAME" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Файл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="FILE_NAME" value="$V_FILE_NAME" />
<table><tr><td><input type="file" name="NOT_FILE_NAME" size="1" /><br /><input type="checkbox" name="CLR_FILE_NAME" value="1" />Сбросить файл.</td>
<td>&#160;</td><td>size=$IM_FILE_NAME[1]<br />/images/$VIRTUAL_IMAGE_PATH$IM_FILE_NAME[0]
</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь к файлу:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PATH_FILE" value="$V_PATH_FILE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

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


print '<h2 class="h2">Буклеты</h2><form action="BOOKLETS.php" method="POST">';



$pagesize=50;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from BOOKLETS A where 1');

$_REQUEST['pcount']=ceil($_REQUEST['count']/$pagesize);

$_REQUEST['p'] = $_REQUEST['p'] > $_REQUEST['pcount'] ? $_REQUEST['pcount'] : $_REQUEST['p'];
$startSelect = ($_REQUEST['p']-1)*$pagesize;
$startSelect = $startSelect > $_REQUEST['count'] ? 0 : $startSelect;
$startSelect = $startSelect < 0 ? 0 : $startSelect;
}

if($_REQUEST['pcount'] > 1)
{
 for($i=1;$i<=$_REQUEST['pcount'];$i++)
 {
  if($i==$_REQUEST['p']) { print '- <b class="red">'.$i.'</b>'; } else { print <<<EOF
- <a class="t" href="BOOKLETS.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.BOOKLETS_ID,A.NAME,A.PATH_FILE,A.STATUS from BOOKLETS A where 1'.' order by A.ORDERING limit ?,?',$startSelect,(int) $pagesize);





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="5">
EOF;

if ($cmf->W)
@print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" />
EOF;

@print <<<EOF
<img src="img/hi.gif" width="4" height="1" />
EOF;
if ($cmf->D)
  print '<input type="submit" name="e" onclick="return dl();" value="Удалить" class="gbt bdel" />';

@print <<<EOF
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;

print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Название</th><th>Путь к файлу</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_BOOKLETS_ID,$V_NAME,$V_PATH_FILE,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_BOOKLETS_ID" /></td>
<td>$V_BOOKLETS_ID</td><td>$V_NAME</td><td>$V_PATH_FILE</td><td nowrap="">
<a href="BOOKLETS.php?e=UP&amp;id=$V_BOOKLETS_ID"><img src="i/up.gif" border="0" /></a>
<a href="BOOKLETS.php?e=DN&amp;id=$V_BOOKLETS_ID"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="BOOKLETS.php?e=ED&amp;id=$V_BOOKLETS_ID&amp;p={$_REQUEST['p']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


</td></tr>
EOF;
}
}
 
print '</table>';
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();

?>
