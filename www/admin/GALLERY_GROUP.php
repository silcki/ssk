<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('GALLERY_GROUP');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
if(!isset($_REQUEST['r']))$_REQUEST['r']=0;
if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['event']))$_REQUEST['event']='';
if(!isset($_REQUEST['id']))$_REQUEST['id']='';
if(!isset($_REQUEST['pid']))$_REQUEST['pid']=0;
if(!isset($_REQUEST['f']))$_REQUEST['f']='';
$VIRTUAL_IMAGE_PATH="/gallery_group/";


$cmf->ENUM_STYLE=array('Красный','Синий','Зеленый','Серый','Желтый');








if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from GALLERY_GROUP_LANGS where GALLERY_GROUP_ID=? and GALLERY_GROUP_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{






$cmf->execute('update GALLERY_GROUP_LANGS set CMF_LANG_ID=?,NAME=?,DESCRIPTION=? where GALLERY_GROUP_ID=? and GALLERY_GROUP_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('GALLERY_GROUP_LANGS');








$cmf->execute('insert into GALLERY_GROUP_LANGS (GALLERY_GROUP_ID,GALLERY_GROUP_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION) values (?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_GALLERY_GROUP_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=$cmf->selectrow_arrayQ('select GALLERY_GROUP_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION from GALLERY_GROUP_LANGS where GALLERY_GROUP_ID=? and GALLERY_GROUP_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="GALLERY_GROUP.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="6" />

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


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
list($V_GALLERY_GROUP_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=array('','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="GALLERY_GROUP.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


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


if($_REQUEST['e'] == 'DL')
{
DelTree($cmf,$_REQUEST['id']);
$cmf->execute('delete from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['id']);
}

if($_REQUEST['e'] == 'VS')
{
$STATUS=$cmf->selectrow_array('select STATUS from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['id']);
$STATUS=1-$STATUS;
$cmf->execute('update GALLERY_GROUP set STATUS=? where GALLERY_GROUP_ID=?',$STATUS,$_REQUEST['id']);
if($STATUS)
{
$cmf->execute('update GALLERY_GROUP set REALSTATUS=1 where GALLERY_GROUP_ID=?',$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],1);
}
else
{
$REALSTATUS=GetMyRealStatus($cmf,$_REQUEST['id']);
$cmf->execute('update GALLERY_GROUP set REALSTATUS=? where GALLERY_GROUP_ID=?',$REALSTATUS,$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],$REALSTATUS);
}
}

if($_REQUEST['e'] == 'UP')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDERING from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{
$cmf->execute('update GALLERY_GROUP set ORDERING=ORDERING+1 where ORDERING=? and PARENT_ID=?',$V_ORDERING-1,$V_PARENT_ID);
$cmf->execute('update GALLERY_GROUP set ORDERING=ORDERING-1 where GALLERY_GROUP_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDERING from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['id']);
list($V_MAXORDERING)=$cmf->selectrow_array('select max(ORDERING) from GALLERY_GROUP where PARENT_ID=?',$V_PARENT_ID);
if($V_ORDERING < $V_MAXORDERING)
{
$cmf->execute('update GALLERY_GROUP set ORDERING=ORDERING-1 where ORDERING=? and PARENT_ID=?',$V_ORDERING+1,$V_PARENT_ID);
$cmf->execute('update GALLERY_GROUP set ORDERING=ORDERING+1 where GALLERY_GROUP_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['event'] == 'Добавить')
{

if(!empty($_REQUEST['pid']))
{
  $_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from GALLERY_GROUP where PARENT_ID=?',$_REQUEST['pid']);
  $_REQUEST['ORDERING']++;
  $_REQUEST['id']=$cmf->GetSequence('GALLERY_GROUP');
  



		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_galary',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	


$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $cmf->execute('insert into GALLERY_GROUP (GALLERY_GROUP_ID,PARENT_ID,NAME,IMAGE1,STYLE,DESCRIPTION,STATUS,REALSTATUS,ORDERING) values (?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['STYLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDERING']));
  
  
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUGallery();
    
}
else
{
  $_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from GALLERY_GROUP where PARENT_ID=?',0);
  $_REQUEST['ORDERING']++;
  $_REQUEST['id']=$cmf->GetSequence('GALLERY_GROUP');
  



		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_galary',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	


$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $_REQUEST['pid'] = (!empty($_REQUEST['PARENT_ID'])) ? $_REQUEST['PARENT_ID'] : 0;
  $cmf->execute('insert into GALLERY_GROUP (GALLERY_GROUP_ID,PARENT_ID,NAME,IMAGE1,STYLE,DESCRIPTION,STATUS,REALSTATUS,ORDERING) values (?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['STYLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDERING']));
  
  
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUGallery();
    

}
$_REQUEST['e']='ED';
}

if($_REQUEST['event'] == 'Изменить')
{




		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_galary',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_galary',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	


$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


@$cmf->execute('update GALLERY_GROUP set NAME=?,IMAGE1=?,STYLE=?,DESCRIPTION=? where GALLERY_GROUP_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['STYLE']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id']);
$_REQUEST['e']='ED';

      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUGallery();
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_GALLERY_GROUP_ID,$V_PARENT_ID,$V_NAME,$V_IMAGE1,$V_STYLE,$V_DESCRIPTION,$V_STATUS,$V_REALSTATUS)=$cmf->selectrow_arrayQ('select GALLERY_GROUP_ID,PARENT_ID,NAME,IMAGE1,STYLE,DESCRIPTION,STATUS,REALSTATUS from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['id']);




if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_3[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

$V_STR_STYLE=$cmf->Enumerator($cmf->ENUM_STYLE,$V_STYLE);
$V_STATUS=$V_STATUS?'checked':'';
$V_REALSTATUS=$V_REALSTATUS?'checked':'';

@print <<<EOF
<h2 class="h2">Редактирование - Раздел галереи</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="GALLERY_GROUP.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="10" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="event" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название группы:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Стиль:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="STYLE">$V_STR_STYLE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="event" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;



print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="GALLERY_GROUP.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select GALLERY_GROUP_LANGS_ID,CMF_LANG_ID,NAME from GALLERY_GROUP_LANGS where GALLERY_GROUP_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Название </th><td></td></tr>
EOF;
while(list($V_GALLERY_GROUP_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_GALLERY_GROUP_LANGS_ID" /></td>
<td>$V_GALLERY_GROUP_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="GALLERY_GROUP.php?e1=ED&amp;iid=$V_GALLERY_GROUP_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] == 'AD' ||  $_REQUEST['e'] =='Новый')
{
list($V_GALLERY_GROUP_ID,$V_PARENT_ID,$V_NAME,$V_IMAGE1,$V_STYLE,$V_DESCRIPTION,$V_STATUS,$V_REALSTATUS,$V_ORDERING)=array('','','','','','','','','');
if(!empty($_REQUEST['pid'])) $V_ = $_REQUEST['pid'];
else $V_ = 0;



$IM_IMAGE1=array('','','');
$V_STR_STYLE=$cmf->Enumerator($cmf->ENUM_STYLE,-1);
$V_STATUS='checked';
$V_REALSTATUS='';

@print <<<EOF
<h2 class="h2">Добавление - Раздел галереи</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="GALLERY_GROUP.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
EOF;
print '<input type="hidden" name="pid" value="'.$_REQUEST['pid'].'" />';
@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Добавить" class="gbt badd" /> 
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название группы:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Стиль:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="STYLE">$V_STR_STYLE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Добавить" class="gbt badd" /> 
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}

if($visible)
{
$parhash=array('0'=>'1');
$GALLERY_GROUP_ID=$_REQUEST['id'];
$O_GALLERY_GROUP_ID=$GALLERY_GROUP_ID;
do 
{
  $PARENTID=$cmf->selectrow_array('select PARENT_ID from GALLERY_GROUP where GALLERY_GROUP_ID=?',$GALLERY_GROUP_ID);
  $parhash[$GALLERY_GROUP_ID]=1;
  $GALLERY_GROUP_ID=$PARENTID;
}while(isset($PARENTID));
print <<<EOF
<h2 class="h2">Раздел галереи</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="GALLERY_GROUP.php" method="POST">
<input type="hidden" name="r" value="{$_REQUEST['r']}" />
<tr bgcolor="#F0F0F0"><td colspan="5">
EOF;

if ($cmf->W)
print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" />
EOF;

print <<<EOF
</td></tr>
EOF;
print <<<EOF
<tr bgcolor="#FFFFFF"><th>N</th><th>Название группы</th><th>Стиль</th><form action="GALLERY.php" method="POST"><th>

</th></form></tr>
EOF;
print visibleTree($cmf,$_REQUEST['r'],0,$_REQUEST['r'],$parhash);
print '</form></table>';
}

function visibleTree($cmf,$parent,$level,$root,$parhash)
{
$width=$level*15+10;
$ret='';
$sth=$cmf->execute('select GALLERY_GROUP_ID,NAME,STYLE,STATUS,REALSTATUS from GALLERY_GROUP where PARENT_ID=? order by ORDERING',$parent);
while ( list($V_GALLERY_GROUP_ID,$V_NAME,$V_STYLE,$V_STATUS,$V_REALSTATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{

$V_STYLE=$cmf->ENUM_STYLE[$V_STYLE];
                        



  $ICONS=<<<EOF
  
EOF;
  $V_REALSTATUS=$V_REALSTATUS?'b':'d';
  $V_STATUS=$V_STATUS?0:1;
  $CO_=$cmf->selectrow_array('select count(*) from GALLERY_GROUP where PARENT_ID=?',$V_GALLERY_GROUP_ID);
if(!$CO_)
 {

$folder=<<<EOF
<img src="i/f1.gif" class="fld" /><a href="GALLERY.php?pid=$V_GALLERY_GROUP_ID" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }
else
 {

$folder=isset($parhash[$V_GALLERY_GROUP_ID])?$folder=<<<EOF
<a href="GALLERY.php?pid=$V_GALLERY_GROUP_ID" class="$V_REALSTATUS"><img src="i/f1.gif" class="fld" /></a><a href="GALLERY_GROUP.php?id=$V_GALLERY_GROUP_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF
:
$folder=<<<EOF
<a href="GALLERY.php?pid=$V_GALLERY_GROUP_ID" class="$V_REALSTATUS"><img src="i/f0.gif" class="fld" /></a><a href="GALLERY_GROUP.php?id=$V_GALLERY_GROUP_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }

 $V_NAME=<<<EOF
$folder 
EOF;
 
  $ret.=<<<EOF
<tr bgcolor="#ffffff">
<td>$V_GALLERY_GROUP_ID</td><td style="padding-left:{$width}px">$V_NAME</td><td>$V_STYLE</td><td nowrap="">
EOF;

if ($cmf->W)
$ret.=<<<EOF
<a href="GALLERY_GROUP.php?e=AD&amp;pid=$V_GALLERY_GROUP_ID&amp;r=$root"><img src="i/add.gif" border="0" title="Добавить" hspace="5" /></a>
<a href="GALLERY_GROUP.php?e=UP&amp;id=$V_GALLERY_GROUP_ID&amp;r=$root"><img src="i/up.gif" border="0" title="Вверх" hspace="5" /></a>
<a href="GALLERY_GROUP.php?e=DN&amp;id=$V_GALLERY_GROUP_ID&amp;r=$root"><img src="i/dn.gif" border="0" title="Вниз" hspace="5" /></a>
<a href="GALLERY_GROUP.php?e=ED&amp;id=$V_GALLERY_GROUP_ID&amp;r=$root"><img src="i/ed.gif" border="0" title="Изменить" hspace="5" /></a>
<a href="GALLERY_GROUP.php?e=VS&amp;id=$V_GALLERY_GROUP_ID&amp;o=$V_GALLERY_GROUP_ID"><img src="i/v$V_STATUS.gif" border="0" /></a>&#160;
$ICONS
EOF;
if ($cmf->D)
{
$ret .=<<<EOF
<a href="GALLERY_GROUP.php?e=DL&amp;id=$V_GALLERY_GROUP_ID&amp;r=$root" onclick="return dl();"><img src="i/del.gif" border="0" title="Удалить" hspace="5" /></a>
EOF;
}

  $ret.= '</td></tr>';

  if(isset($parhash[$V_GALLERY_GROUP_ID])){$ret.=visibleTree($cmf,$V_GALLERY_GROUP_ID,$level+1,$root,$parhash);}
}
return $ret;
}

function DelTree($cmf,$id)
{
$sth=$cmf->execute('select GALLERY_GROUP_ID from GALLERY_GROUP where PARENT_ID=?',$id);
while(list($V_GALLERY_GROUP_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
DelTree($cmf,$V_GALLERY_GROUP_ID);
$cmf->execute('delete from GALLERY_GROUP where GALLERY_GROUP_ID=?',$V_GALLERY_GROUP_ID);
#### del items
}
}

function SetTreeRealStatus($cmf,$id,$state)
{
$sth=$cmf->execute('select GALLERY_GROUP_ID,STATUS from GALLERY_GROUP where PARENT_ID=?',$id);
while(list($V_GALLERY_GROUP_ID,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){SetTreeRealStatus($cmf,$V_GALLERY_GROUP_ID,$state);}
if($state) {$cmf->execute('update GALLERY_GROUP set REALSTATUS=STATUS where GALLERY_GROUP_ID=?',$V_GALLERY_GROUP_ID);}
else {$cmf->execute('update GALLERY_GROUP set REALSTATUS=0 where GALLERY_GROUP_ID=?',$V_GALLERY_GROUP_ID);}
}
}

function GetMyRealStatus($cmf,$id)
{
$V_PARENT_ID=$id;
$V_FULLSTATUS=0;
while ($V_PARENT_ID>0)
{
list ($V_PARENT_ID,$V_STATUS)=$cmf->selectrow_array('select PARENT_ID,STATUS from GALLERY_GROUP where GALLERY_GROUP_ID=?',$V_PARENT_ID);
$V_FULLSTATUS+=1-$V_STATUS;
}
if($V_FULLSTATUS){$V_FULLSTATUS=0;} else {$V_FULLSTATUS=1;}
return $V_FULLSTATUS;
}

$cmf->MakeCommonFooter();
$cmf->Close();

?>
