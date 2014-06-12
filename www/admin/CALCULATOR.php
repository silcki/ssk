<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CALCULATOR');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
$VIRTUAL_IMAGE_PATH="/calc/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';












if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
list($ORDERING)=$cmf->selectrow_array('select ORDER_ from CALCULATOR where CALCULATOR_ID=?',$id);
$cmf->execute('update CALCULATOR set ORDER_=ORDER_-1 where ORDER_>?',$ORDERING);
$cmf->execute('delete from CALCULATOR where CALCULATOR_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($ORDERING)=$cmf->selectrow_array('select ORDER_ from CALCULATOR where CALCULATOR_ID=?',$_REQUEST['id']);
if($ORDERING>1)
{
$cmf->execute('update CALCULATOR set ORDER_=ORDER_+1 where ORDER_=?',$ORDERING-1);
$cmf->execute('update CALCULATOR set ORDER_=ORDER_-1 where CALCULATOR_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($ORDERING)=$cmf->selectrow_array('select ORDER_ from CALCULATOR where CALCULATOR_ID=?',$_REQUEST['id']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDER_) from CALCULATOR');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update CALCULATOR set ORDER_=ORDER_-1 where ORDER_=?',$ORDERING+1);
$cmf->execute('update CALCULATOR set ORDER_=ORDER_+1 where CALCULATOR_ID=?',$_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{


$_REQUEST['ORDER_']=$cmf->selectrow_array('select max(ORDER_) from CALCULATOR');
$_REQUEST['ORDER_']++;


$_REQUEST['id']=$cmf->GetSequence('CALCULATOR');



		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into CALCULATOR (CALCULATOR_ID,NAME,IMAGE1,INDENT,STATUS,ORDER_) values (?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['INDENT']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDER_']));

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{




		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update CALCULATOR set NAME=?,IMAGE1=?,INDENT=?,STATUS=? where CALCULATOR_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['INDENT']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CALCULATOR_ID,$V_NAME,$V_IMAGE1,$V_INDENT,$V_STATUS)=
$cmf->selectrow_arrayQ('select CALCULATOR_ID,NAME,IMAGE1,INDENT,STATUS from CALCULATOR where CALCULATOR_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_2[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Калькулятор</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CALCULATOR.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(INDENT);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="12" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка раздела:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Идентификатор:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="INDENT" value="$V_INDENT" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>


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
list($V_CALCULATOR_ID,$V_NAME,$V_IMAGE1,$V_INDENT,$V_STATUS,$V_ORDER_)=array('','','','','','');

$IM_IMAGE1=array('','','');
$V_STATUS='';
@print <<<EOF
<h2 class="h2">Добавление - Калькулятор</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CALCULATOR.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(INDENT);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка раздела:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Идентификатор:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="INDENT" value="$V_INDENT" size="90" /><br />

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


print '<h2 class="h2">Калькулятор</h2><form action="CALCULATOR.php" method="POST">';




$sth=$cmf->execute('select A.CALCULATOR_ID,A.NAME,A.STATUS from CALCULATOR A where 1'.' order by A.ORDER_ ');





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="4">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Название</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_CALCULATOR_ID,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_CALCULATOR_ID" /></td>
<td>$V_CALCULATOR_ID</td><td>$V_NAME</td><td nowrap="">
<a href="CALCULATOR.php?e=UP&amp;id=$V_CALCULATOR_ID"><img src="i/up.gif" border="0" /></a>
<a href="CALCULATOR.php?e=DN&amp;id=$V_CALCULATOR_ID"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="CALCULATOR.php?e=ED&amp;id=$V_CALCULATOR_ID"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
