<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CLIENT_VOPROS');
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

if($_REQUEST['e'] == 'RET')
{

$_REQUEST['pid']=$cmf->selectrow_array('select CLIENT_VOPROS_ID from CLIENT_OTVETS where CLIENT_OTVETS_ID=? ',$_REQUEST['id']);
}












if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

foreach ($_REQUEST['id'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING from CLIENT_OTVETS where CLIENT_OTVETS_ID=?',$id);
$cmf->execute('update CLIENT_OTVETS set ORDERING=ORDERING-1 where ORDERING>? and CLIENT_VOPROS_ID=?',$ORDERING,$_REQUEST['pid']);
$cmf->execute('delete from CLIENT_OTVETS where CLIENT_OTVETS_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($V_CLIENT_VOPROS_ID,$V_ORDERING) =$cmf->selectrow_array('select CLIENT_VOPROS_ID,ORDERING from CLIENT_OTVETS where CLIENT_OTVETS_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{

$sql="select CLIENT_OTVETS_ID
           , ORDERING
      from CLIENT_OTVETS
      where ORDERING < {$V_ORDERING}
            and CLIENT_VOPROS_ID = {$V_CLIENT_VOPROS_ID}
      order by ORDERING DESC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update CLIENT_OTVETS set ORDERING=? where CLIENT_OTVETS_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update CLIENT_OTVETS set ORDERING=? where CLIENT_OTVETS_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);

}
}

if($_REQUEST['e'] == 'DN')
{
list($V_CLIENT_VOPROS_ID,$V_ORDERING) =$cmf->selectrow_array('select CLIENT_VOPROS_ID,ORDERING from CLIENT_OTVETS where CLIENT_OTVETS_ID=?',$_REQUEST['id']);
$V_MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from CLIENT_OTVETS where CLIENT_VOPROS_ID=?',$V_CLIENT_VOPROS_ID);
if($V_ORDERING < $V_MAXORDERING)
{

$sql="select CLIENT_OTVETS_ID
           , ORDERING
      from CLIENT_OTVETS
      where ORDERING > {$V_ORDERING}
            and CLIENT_VOPROS_ID = {$V_CLIENT_VOPROS_ID}
      order by ORDERING ASC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update CLIENT_OTVETS set ORDERING=? where CLIENT_OTVETS_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update CLIENT_OTVETS set ORDERING=? where CLIENT_OTVETS_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{

$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from CLIENT_OTVETS where CLIENT_VOPROS_ID=?',$_REQUEST['pid']);
$_REQUEST['ORDERING']++;





$cmf->execute('insert into CLIENT_OTVETS (CLIENT_OTVETS_ID,CLIENT_VOPROS_ID,NAME,ORDERING) values (null,?,?,?)',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['ORDERING']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e'] ='ED';

}

if($_REQUEST['e'] == 'Изменить')
{






if(!empty($_REQUEST['pid'])) $cmf->execute('update CLIENT_OTVETS set CLIENT_VOPROS_ID=?,NAME=? where CLIENT_OTVETS_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),$_REQUEST['id']);
else $cmf->execute('update CLIENT_OTVETS set NAME=? where CLIENT_OTVETS_ID=?',stripslashes($_REQUEST['NAME']),$_REQUEST['id']);

$_REQUEST['e'] ='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CLIENT_OTVETS_ID,$V_CLIENT_VOPROS_ID,$V_NAME)=$cmf->selectrow_arrayQ('select CLIENT_OTVETS_ID,CLIENT_VOPROS_ID,NAME from CLIENT_OTVETS where CLIENT_OTVETS_ID=?',$_REQUEST['id']);



print @<<<EOF
<h2 class="h2">Редактирование - Ответы</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT_OTVETS.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="NAME" rows="7" cols="90">$V_NAME</textarea><br />


</td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Назад" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;


$visible=0;
}

if($_REQUEST['e'] =='Новый')
{
list($V_CLIENT_OTVETS_ID,$V_CLIENT_VOPROS_ID,$V_NAME,$V_ORDERING)=array('','','','');


@print <<<EOF
<h2 class="h2">Добавление - Ответы</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT_OTVETS.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="NAME" rows="7" cols="90">$V_NAME</textarea><br />


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

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select NAME from CLIENT_VOPROS where CLIENT_VOPROS_ID=?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Ответы</h2><form action="CLIENT_OTVETS.php" method="POST">
<a href="CLIENT_VOPROS.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all')
{
$sth=$cmf->execute('select A.CLIENT_OTVETS_ID,A.NAME from CLIENT_OTVETS A where A.CLIENT_VOPROS_ID=?  order by A.ORDERING ',$_REQUEST['pid']);
}
else
{
$sth=$cmf->execute('select A.CLIENT_OTVETS_ID,A.NAME from CLIENT_OTVETS A
where A.CLIENT_VOPROS_ID > 0  order by A.ORDERING limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);

}





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="4">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Текст ответа</th><td></td></tr>
EOF;


if($sth)
while(list($V_CLIENT_OTVETS_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{






@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="id[]" value="$V_CLIENT_OTVETS_ID" /></td>
<td>$V_CLIENT_OTVETS_ID</td><td>$V_NAME</td><td nowrap="">
<a href="CLIENT_OTVETS.php?e=UP&amp;id=$V_CLIENT_OTVETS_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/up.gif" border="0" /></a>
<a href="CLIENT_OTVETS.php?e=DN&amp;id=$V_CLIENT_OTVETS_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
@print <<<EOF
<a href="CLIENT_OTVETS.php?e=ED&amp;id=$V_CLIENT_OTVETS_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

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
$sth=$cmf->execute('select CLIENT_VOPROS_ID,NAME from CLIENT_VOPROS  order by NAME');
while(list($V_CLIENT_VOPROS_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_CLIENT_VOPROS_ID?'<input type="radio" name="cid" value="'.$V_CLIENT_VOPROS_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_CLIENT_VOPROS_ID.'" />')."&#160;$V_NAME</li>";
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>
