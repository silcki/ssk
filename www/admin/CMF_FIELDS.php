<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CMF_FIELDS');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;



$cmf->ENUM_TYPE=array('text','radio','checkbox','select','select multiple','textarea','file');





if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';












if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CMF_FIELDS where CMF_FIELDS_ID=?',$id);
$cmf->execute('update CMF_FIELDS set ORDERING=ORDERING-1 where ORDERING>?',$ORDERING);
$cmf->execute('delete from CMF_FIELDS where CMF_FIELDS_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CMF_FIELDS where CMF_FIELDS_ID=?',$_REQUEST['id']);
if($ORDERING>1)
{
$cmf->execute('update CMF_FIELDS set ORDERING=ORDERING+1 where ORDERING=?',$ORDERING-1);
$cmf->execute('update CMF_FIELDS set ORDERING=ORDERING-1 where CMF_FIELDS_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from CMF_FIELDS where CMF_FIELDS_ID=?',$_REQUEST['id']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from CMF_FIELDS');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update CMF_FIELDS set ORDERING=ORDERING-1 where ORDERING=?',$ORDERING+1);
$cmf->execute('update CMF_FIELDS set ORDERING=ORDERING+1 where CMF_FIELDS_ID=?',$_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{


$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from CMF_FIELDS');
$_REQUEST['ORDERING']++;







$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['IS_MANDATORY']=isset($_REQUEST['IS_MANDATORY']) && $_REQUEST['IS_MANDATORY']?1:0;


$cmf->execute('insert into CMF_FIELDS (CMF_FIELDS_ID,NAME,TITLE,TYPE,VALUE_,STATUS,IS_MANDATORY,ORDERING) values (null,?,?,?,?,?,?,?)',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['TYPE']),stripslashes($_REQUEST['VALUE_']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['IS_MANDATORY']),stripslashes($_REQUEST['ORDERING']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{






$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['IS_MANDATORY']=isset($_REQUEST['IS_MANDATORY']) && $_REQUEST['IS_MANDATORY']?1:0;


$cmf->execute('update CMF_FIELDS set NAME=?,TITLE=?,TYPE=?,VALUE_=?,STATUS=?,IS_MANDATORY=? where CMF_FIELDS_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['TYPE']),stripslashes($_REQUEST['VALUE_']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['IS_MANDATORY']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CMF_FIELDS_ID,$V_NAME,$V_TITLE,$V_TYPE,$V_VALUE_,$V_STATUS,$V_IS_MANDATORY)=
$cmf->selectrow_arrayQ('select CMF_FIELDS_ID,NAME,TITLE,TYPE,VALUE_,STATUS,IS_MANDATORY from CMF_FIELDS where CMF_FIELDS_ID=?',$_REQUEST['id']);



$V_STR_TYPE=$cmf->Enumerator($cmf->ENUM_TYPE,$V_TYPE);
$V_STATUS=$V_STATUS?'checked':'';
$V_IS_MANDATORY=$V_IS_MANDATORY?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Идентификатор поля</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_FIELDS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(VALUE_);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тип поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="TYPE">$V_STR_TYPE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Значение по умолчанию(для типов отличных от select):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="VALUE_" value="$V_VALUE_" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Обязательное:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_MANDATORY' value='1' $V_IS_MANDATORY/><br /></td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />

EOF;



$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_CMF_FIELDS_ID,$V_NAME,$V_TITLE,$V_TYPE,$V_VALUE_,$V_STATUS,$V_IS_MANDATORY,$V_ORDERING)=array('','','','','','','','');

$V_STR_TYPE=$cmf->Enumerator($cmf->ENUM_TYPE,-1);
$V_STATUS='';
$V_IS_MANDATORY='';
@print <<<EOF
<h2 class="h2">Добавление - Идентификатор поля</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_FIELDS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(VALUE_);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тип поля:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="TYPE">$V_STR_TYPE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Значение по умолчанию(для типов отличных от select):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="VALUE_" value="$V_VALUE_" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Обязательное:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_MANDATORY' value='1' $V_IS_MANDATORY/><br /></td></tr>

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


print '<h2 class="h2">Идентификатор поля</h2><form action="CMF_FIELDS.php" method="POST">';




$sth=$cmf->execute('select A.CMF_FIELDS_ID,A.NAME,A.TITLE,A.TYPE,A.STATUS,A.IS_MANDATORY from CMF_FIELDS A where 1'.' order by A.ORDERING ');





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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Название поля</th><th>Имя поля</th><th>Тип поля</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_CMF_FIELDS_ID,$V_NAME,$V_TITLE,$V_TYPE,$V_STATUS,$V_IS_MANDATORY)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_TYPE=$cmf->ENUM_TYPE[$V_TYPE];
                        
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_CMF_FIELDS_ID" /></td>
<td>$V_CMF_FIELDS_ID</td><td>$V_NAME</td><td>$V_TITLE</td><td>$V_TYPE</td><td nowrap="">
<a href="CMF_FIELDS.php?e=UP&amp;id=$V_CMF_FIELDS_ID"><img src="i/up.gif" border="0" /></a>
<a href="CMF_FIELDS.php?e=DN&amp;id=$V_CMF_FIELDS_ID"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="CMF_FIELDS.php?e=ED&amp;id=$V_CMF_FIELDS_ID"><img src="i/ed.gif" border="0" title="Изменить" /></a>


<a href="CMF_FIELDS_LIST.php?pid=$V_CMF_FIELDS_ID"><img src="i/flt.gif" border="0" title="Список значений" hspace="5"/></a></td></tr>
EOF;
}
}
 
print '</table>';
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();

?>
