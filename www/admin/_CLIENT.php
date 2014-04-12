<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CLIENT');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
$VIRTUAL_IMAGE_PATH="/cl/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';



      if($cmf->Param('ell3') == 'Отменить')
      {
         $visible=0;
         $pos = 3;
         $pos = $pos -1;
         echo '<meta http-equiv="Refresh" content="1; url=CLIENT.php?e'.$pos.'=ED&amp;id='.$_REQUEST['id'].'&amp;iid='.$_REQUEST['iid'].'">';
      };
    









if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CLIENT where CLIENT_ID=?',$id);
$cmf->execute('update CLIENT set ORDERING=ORDERING-1 where ORDERING>?',$ORDERING);
$cmf->execute('delete from CLIENT where CLIENT_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CLIENT where CLIENT_ID=?',$_REQUEST['id']);
if($ORDERING>1)
{
$cmf->execute('update CLIENT set ORDERING=ORDERING+1 where ORDERING=?',$ORDERING-1);
$cmf->execute('update CLIENT set ORDERING=ORDERING-1 where CLIENT_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CLIENT where CLIENT_ID=?',$_REQUEST['id']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from CLIENT');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update CLIENT set ORDERING=ORDERING-1 where ORDERING=?',$ORDERING+1);
$cmf->execute('update CLIENT set ORDERING=ORDERING+1 where CLIENT_ID=?',$_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{


$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from CLIENT');
$_REQUEST['ORDERING']++;


$_REQUEST['id']=$cmf->GetSequence('CLIENT');





		
				


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='client_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='client_small_y'");
	
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
	

$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into CLIENT (CLIENT_ID,NAME,EMAIL,URL,IMAGE1,DESCRIPTION,STATUS_MAIN,STATUS,ORDERING) values (?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{






		
				


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='client_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='client_small_y'");
	
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
	

$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update CLIENT set NAME=?,EMAIL=?,URL=?,IMAGE1=?,DESCRIPTION=?,STATUS_MAIN=?,STATUS=? where CLIENT_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CLIENT_ID,$V_NAME,$V_EMAIL,$V_URL,$V_IMAGE1,$V_DESCRIPTION,$V_STATUS_MAIN,$V_STATUS)=
$cmf->selectrow_arrayQ('select CLIENT_ID,NAME,EMAIL,URL,IMAGE1,DESCRIPTION,STATUS_MAIN,STATUS from CLIENT where CLIENT_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_4[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

$V_STATUS_MAIN=$V_STATUS_MAIN?'checked':'';
$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Клиенты</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(EMAIL) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="8" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Логотип:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />

EOF;



$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_CLIENT_ID,$V_NAME,$V_EMAIL,$V_URL,$V_IMAGE1,$V_DESCRIPTION,$V_STATUS_MAIN,$V_STATUS,$V_ORDERING)=array('','','','','','','','','');

$IM_IMAGE1=array('','','');
$V_STATUS_MAIN='';
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Клиенты</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(EMAIL) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(DESCRIPTION);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Логотип:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

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


print '<h2 class="h2">Клиенты</h2><form action="CLIENT.php" method="POST">';




$sth=$cmf->execute('select A.CLIENT_ID,A.NAME,A.EMAIL,A.URL,A.STATUS_MAIN,A.STATUS from CLIENT A where 1'.' order by A.ORDERING ');





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="7">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Имя</th><th>E-mail</th><th>URL</th><th>Выводить на главной</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_CLIENT_ID,$V_NAME,$V_EMAIL,$V_URL,$V_STATUS_MAIN,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if(!$V_STATUS_MAIN) {$V_STATUS_MAIN='Нет';} else {$V_STATUS_MAIN='Да';}
                        
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_CLIENT_ID" /></td>
<td>$V_CLIENT_ID</td><td>$V_NAME</td><td>$V_EMAIL</td><td>$V_URL</td><td>$V_STATUS_MAIN</td><td nowrap="">
<a href="CLIENT.php?e=UP&amp;id=$V_CLIENT_ID"><img src="i/up.gif" border="0" /></a>
<a href="CLIENT.php?e=DN&amp;id=$V_CLIENT_ID"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="CLIENT.php?e=ED&amp;id=$V_CLIENT_ID"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
