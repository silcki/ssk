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
$VIRTUAL_IMAGE_PATH="/item_elem/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';

if($_REQUEST['e'] == 'RET')
{

$_REQUEST['pid']=$cmf->selectrow_array('select ITEM_ID from ITEM_ELEMENTS where ITEM_ELEMENTS_ID=? ',$_REQUEST['id']);
}





if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from ITEM_ELEMENTS_LANGS where ITEM_ELEMENTS_ID=? and ITEM_ELEMENTS_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{






$cmf->execute('update ITEM_ELEMENTS_LANGS set CMF_LANG_ID=?,NAME=?,DESCRIPTION=? where ITEM_ELEMENTS_ID=? and ITEM_ELEMENTS_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('ITEM_ELEMENTS_LANGS');








$cmf->execute('insert into ITEM_ELEMENTS_LANGS (ITEM_ELEMENTS_ID,ITEM_ELEMENTS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION) values (?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_ITEM_ELEMENTS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=$cmf->selectrow_arrayQ('select ITEM_ELEMENTS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION from ITEM_ELEMENTS_LANGS where ITEM_ELEMENTS_ID=? and ITEM_ELEMENTS_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM_ELEMENTS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />


<input type="hidden" name="p" value="{$_REQUEST['p']}" />

EOF;
if(!empty($V_CMF_LANG_ID)) print '<input type="hidden" name="CMF_LANG_ID" value="'.$V_CMF_LANG_ID.'" />';

@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr>
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
list($V_ITEM_ELEMENTS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=array('','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ITEM_ELEMENTS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
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

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткое описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


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








if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

foreach ($_REQUEST['id'] as $id)
 {

$cmf->execute('delete from ITEM_ELEMENTS where ITEM_ELEMENTS_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{

$_REQUEST['id']=$cmf->GetSequence('ITEM_ELEMENTS');





		
				
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
	


$cmf->execute('insert into ITEM_ELEMENTS (ITEM_ELEMENTS_ID,ITEM_ID,NAME,NAME_NUM,IMAGE1,DESCRIPTION) values (?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['NAME_NUM']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['DESCRIPTION']));

$_REQUEST['e'] ='ED';

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
	


if(!empty($_REQUEST['pid'])) $cmf->execute('update ITEM_ELEMENTS set ITEM_ID=?,NAME=?,NAME_NUM=?,IMAGE1=?,DESCRIPTION=? where ITEM_ELEMENTS_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['NAME_NUM']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id']);
else $cmf->execute('update ITEM_ELEMENTS set NAME=?,NAME_NUM=?,IMAGE1=?,DESCRIPTION=? where ITEM_ELEMENTS_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['NAME_NUM']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id']);

$_REQUEST['e'] ='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_ITEM_ELEMENTS_ID,$V_ITEM_ID,$V_NAME,$V_NAME_NUM,$V_IMAGE1,$V_DESCRIPTION)=$cmf->selectrow_arrayQ('select ITEM_ELEMENTS_ID,ITEM_ID,NAME,NAME_NUM,IMAGE1,DESCRIPTION from ITEM_ELEMENTS where ITEM_ELEMENTS_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_3[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

print @<<<EOF
<h2 class="h2">Редактирование - Описание элементов стеллажа</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ITEM_ELEMENTS.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(NAME_NUM) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Число:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME_NUM" value="$V_NAME_NUM" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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


</td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
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
<form action="ITEM_ELEMENTS.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select ITEM_ELEMENTS_LANGS_ID,CMF_LANG_ID,NAME from ITEM_ELEMENTS_LANGS where ITEM_ELEMENTS_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Наименование</th><td></td></tr>
EOF;
while(list($V_ITEM_ELEMENTS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_ITEM_ELEMENTS_LANGS_ID" /></td>
<td>$V_ITEM_ELEMENTS_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="ITEM_ELEMENTS.php?e1=ED&amp;iid=$V_ITEM_ELEMENTS_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] =='Новый')
{
list($V_ITEM_ELEMENTS_ID,$V_ITEM_ID,$V_NAME,$V_NAME_NUM,$V_IMAGE1,$V_DESCRIPTION)=array('','','','','','');


$IM_IMAGE1=array('','','');
@print <<<EOF
<h2 class="h2">Добавление - Описание элементов стеллажа</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ITEM_ELEMENTS.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(NAME_NUM) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Число:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME_NUM" value="$V_NAME_NUM" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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


</td></tr>

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

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select NAME from ITEM where ITEM_ID=?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Описание элементов стеллажа</h2><form action="ITEM_ELEMENTS.php" method="POST">
<a href="ITEM.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




$_REQUEST['s']+=0;
$SORTNAMES=array('N','Наименование','Число','Картинка');
$SORTQUERY=array('order by A.ITEM_ELEMENTS_ID ','order by A.ITEM_ELEMENTS_ID desc ','order by A.NAME ','order by A.NAME desc ','order by A.NAME_NUM ','order by A.NAME_NUM desc ','order by A.IMAGE1 ','order by A.IMAGE1 desc ');

//Ручные фильтры
$filters = '';
$filt_request = '';
foreach($_REQUEST as $key=>$val)
{
  if(preg_match('/^FILTER_(.+)$/',$key,$p))
  {
    if($val!='')
     {
        $filters.='&amp;'.$key.'='.$val;
     }
  }
}

list($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ITEM_ELEMENTS.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ITEM_ELEMENTS.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ITEM_ELEMENTS.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp</a></th>
EOF;
        }
        $i++;
}


$pagesize=20;

if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}

if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{
if(!empty($_REQUEST['pid']) and $_REQUEST['pid']=='all')
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ITEM_ELEMENTS A where A.ITEM_ID > 0',$_REQUEST['pid']);
}
else
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ITEM_ELEMENTS A where A.ITEM_ID=?',$_REQUEST['pid']);

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
- <a class="t" href="ITEM_ELEMENTS.php?pid={$_REQUEST['pid']}&amp;count={$_REQUEST['count']}&amp;p={$i}&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}{$filters}">$i</a>
EOF;
  }
 }
print <<<EOF
&#160;из <span class="red">({$_REQUEST['pcount']})</span><br />
EOF;
}

if(!empty($_REQUEST['pid']) and $_REQUEST['pid'] == 'all')
{
$sth=$cmf->execute('select A.ITEM_ELEMENTS_ID,A.NAME,A.NAME_NUM,A.IMAGE1 from ITEM_ELEMENTS A
where A.ITEM_ID > 0  '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);
}
else
{
$sth=$cmf->execute('select A.ITEM_ELEMENTS_ID,A.NAME,A.NAME_NUM,A.IMAGE1 from ITEM_ELEMENTS A
where A.ITEM_ID=?  '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$_REQUEST['pid'],$pagesize*($_REQUEST['p']-1),$pagesize);

}





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="6">
EOF;

if ($cmf->W)
@print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td>$HEADER<td></td></tr>
EOF;


if($sth)
while(list($V_ITEM_ELEMENTS_ID,$V_NAME,$V_NAME_NUM,$V_IMAGE1)=mysql_fetch_array($sth, MYSQL_NUM))
{



if(isset($V_IMAGE1))
{
   $IM_4=split('#',$V_IMAGE1);
   if(strchr($IM_4[0],".swf"))
   {
       $V_IMAGE1="<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"150\" height=\"100\"align=\"middle\"><param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_4[0]\" /><param name=\"quality\" value=\"high\" /><embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_4[0]\" quality=\"high\" width=\"150\" height=\"100\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" /></object>";
   }
   else
   {
      if(isset($IM_4[1]) && $IM_4[1] > 150){$IM_4[2]=$IM_4[2]*150/$IM_4[1]; $IM_4[1]=150;
      $V_IMAGE1="<img src=\"/images$VIRTUAL_IMAGE_PATH$IM_4[0]\" width=\"$IM_4[1]\" height=\"$IM_4[2]\">";}
   }
}




@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="id[]" value="$V_ITEM_ELEMENTS_ID" /></td>
<td>$V_ITEM_ELEMENTS_ID</td><td>$V_NAME</td><td>$V_NAME_NUM</td><td>$V_IMAGE1</td><td nowrap="">
EOF;

if ($cmf->W)
@print <<<EOF
<a href="ITEM_ELEMENTS.php?e=ED&amp;id=$V_ITEM_ELEMENTS_ID&amp;pid={$_REQUEST['pid']}&amp;p={$_REQUEST['p']}&amp;s={$_REQUEST['s']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

</td></tr>
EOF;
}
@print <<<EOF
        </table>
EOF;
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();


function ___GetList($cmf,$id)
{
$ret='';
$sth=$cmf->execute('select ITEM_ID, from ITEM  order by ORDERING');
while(list($V_ITEM_ID,$V_)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_ITEM_ID?'<input type="radio" name="cid" value="'.$V_ITEM_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_ITEM_ID.'" />')."&#160;$V_</li>";
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>
