<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CMF_ERRORS');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;







if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';




if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from CMF_ERRORS_LANGS where CMF_ERRORS_ID=? and CMF_ERRORS_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{





$cmf->execute('update CMF_ERRORS_LANGS set CMF_LANG_ID=?,NAME=? where CMF_ERRORS_ID=? and CMF_ERRORS_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('CMF_ERRORS_LANGS');







$cmf->execute('insert into CMF_ERRORS_LANGS (CMF_ERRORS_ID,CMF_ERRORS_LANGS_ID,CMF_LANG_ID,NAME) values (?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_CMF_ERRORS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=$cmf->selectrow_arrayQ('select CMF_ERRORS_LANGS_ID,CMF_LANG_ID,NAME from CMF_ERRORS_LANGS where CMF_ERRORS_ID=? and CMF_ERRORS_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CMF_ERRORS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
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
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ошибки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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
list($V_CMF_ERRORS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=array('','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CMF_ERRORS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ошибки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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









if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
$cmf->execute('delete from CMF_ERRORS where CMF_ERRORS_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{







$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('insert into CMF_ERRORS (CMF_ERRORS_ID,CMF_ERRORS_GROUP_ID,SYSTEM_NAME,NAME,STATUS) values (null,?,?,?,?)',stripslashes($_REQUEST['CMF_ERRORS_GROUP_ID'])+0,stripslashes($_REQUEST['SYSTEM_NAME']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{





$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('update CMF_ERRORS set CMF_ERRORS_GROUP_ID=?,SYSTEM_NAME=?,NAME=?,STATUS=? where CMF_ERRORS_ID=?',stripslashes($_REQUEST['CMF_ERRORS_GROUP_ID'])+0,stripslashes($_REQUEST['SYSTEM_NAME']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CMF_ERRORS_ID,$V_CMF_ERRORS_GROUP_ID,$V_SYSTEM_NAME,$V_NAME,$V_STATUS)=
$cmf->selectrow_arrayQ('select CMF_ERRORS_ID,CMF_ERRORS_GROUP_ID,SYSTEM_NAME,NAME,STATUS from CMF_ERRORS where CMF_ERRORS_ID=?',$_REQUEST['id']);



        $V_STR_CMF_ERRORS_GROUP_ID=$cmf->Spravotchnik($V_CMF_ERRORS_GROUP_ID,'select CMF_ERRORS_GROUP_ID,NAME from CMF_ERRORS_GROUP  where STATUS=1  order by NAME');
        
        
$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Системные ошибки</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_ERRORS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(SYSTEM_NAME) &amp;&amp; checkXML(NAME);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$REQUEST['s']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Группа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_ERRORS_GROUP_ID">$V_STR_CMF_ERRORS_GROUP_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Системное имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SYSTEM_NAME" value="$V_SYSTEM_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ошибки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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
<form action="CMF_ERRORS.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select CMF_ERRORS_LANGS_ID,CMF_LANG_ID,NAME from CMF_ERRORS_LANGS where CMF_ERRORS_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Текст ошибки</th><td></td></tr>
EOF;
while(list($V_CMF_ERRORS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_CMF_ERRORS_LANGS_ID" /></td>
<td>$V_CMF_ERRORS_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="CMF_ERRORS.php?e1=ED&amp;iid=$V_CMF_ERRORS_LANGS_ID&amp;id={$_REQUEST['id']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_CMF_ERRORS_ID,$V_CMF_ERRORS_GROUP_ID,$V_SYSTEM_NAME,$V_NAME,$V_STATUS)=array('','','','','');

$V_STR_CMF_ERRORS_GROUP_ID=$cmf->Spravotchnik($V_CMF_ERRORS_GROUP_ID,'select CMF_ERRORS_GROUP_ID,NAME from CMF_ERRORS_GROUP  where STATUS=1  order by NAME');     
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Системные ошибки</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_ERRORS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(SYSTEM_NAME) &amp;&amp; checkXML(NAME);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Группа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_ERRORS_GROUP_ID">$V_STR_CMF_ERRORS_GROUP_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Системное имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SYSTEM_NAME" value="$V_SYSTEM_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ошибки:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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


print '<h2 class="h2">Системные ошибки</h2><form action="CMF_ERRORS.php" method="POST">';



$_REQUEST['s']+=0;
$SORTNAMES=array('N','Группа','Системное имя','Текст ошибки');
$SORTQUERY=array('order by A.CMF_ERRORS_ID ','order by A.CMF_ERRORS_ID desc ','order by A.CMF_ERRORS_GROUP_ID ','order by A.CMF_ERRORS_GROUP_ID desc ','order by A.SYSTEM_NAME ','order by A.SYSTEM_NAME desc ','order by A.NAME ','order by A.NAME desc ');
list ($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="CMF_ERRORS.php?s=$tmps{$filtpath}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="CMF_ERRORS.php?s=$tmps{$filtpath}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="CMF_ERRORS.php?s=$tmps{$filtpath}">$tmp</a></th>
EOF;
        }
        $i++;
}



$sth=$cmf->execute('select A.CMF_ERRORS_ID,A.CMF_ERRORS_GROUP_ID,A.SYSTEM_NAME,A.NAME,A.STATUS from CMF_ERRORS A where 1'.' '.$SORTQUERY[$_REQUEST['s']]);





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="6">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td>$HEADER<td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_CMF_ERRORS_ID,$V_CMF_ERRORS_GROUP_ID,$V_SYSTEM_NAME,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_ERRORS_GROUP_ID=$cmf->selectrow_arrayQ('select NAME from CMF_ERRORS_GROUP where CMF_ERRORS_GROUP_ID=?',$V_CMF_ERRORS_GROUP_ID);
                                        
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_CMF_ERRORS_ID" /></td>
<td>$V_CMF_ERRORS_ID</td><td>$V_CMF_ERRORS_GROUP_ID</td><td>$V_SYSTEM_NAME</td><td>$V_NAME</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="CMF_ERRORS.php?e=ED&amp;id=$V_CMF_ERRORS_ID&amp;s={$_REQUEST['s']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
