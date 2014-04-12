<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('VOPROS');
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

$_REQUEST['pid']=$cmf->selectrow_array('select OTVETS_ID from OTVETS_COMMENT where OTVETS_COMMENT_ID=? ',$_REQUEST['id']);
}












if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

foreach ($_REQUEST['id'] as $id)
 {

$cmf->execute('delete from OTVETS_COMMENT where OTVETS_COMMENT_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{






$cmf->execute('insert into OTVETS_COMMENT (OTVETS_COMMENT_ID,OTVETS_ID,NAME,COUNT_) values (null,?,?,?)',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['COUNT_'])+0);
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e'] ='ED';

}

if($_REQUEST['e'] == 'Изменить')
{






if(!empty($_REQUEST['pid'])) $cmf->execute('update OTVETS_COMMENT set OTVETS_ID=?,NAME=?,COUNT_=? where OTVETS_COMMENT_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['COUNT_'])+0,$_REQUEST['id']);
else $cmf->execute('update OTVETS_COMMENT set NAME=?,COUNT_=? where OTVETS_COMMENT_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['COUNT_'])+0,$_REQUEST['id']);

$_REQUEST['e'] ='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_OTVETS_COMMENT_ID,$V_OTVETS_ID,$V_NAME,$V_COUNT_)=$cmf->selectrow_arrayQ('select OTVETS_COMMENT_ID,OTVETS_ID,NAME,COUNT_ from OTVETS_COMMENT where OTVETS_COMMENT_ID=?',$_REQUEST['id']);



print @<<<EOF
<h2 class="h2">Редактирование - Коментарии к ответам</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="OTVETS_COMMENT.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Текст комментария:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Количество:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COUNT_" value="$V_COUNT_" size="70" /><br />

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
list($V_OTVETS_COMMENT_ID,$V_OTVETS_ID,$V_NAME,$V_COUNT_)=array('','','','');


@print <<<EOF
<h2 class="h2">Добавление - Коментарии к ответам</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="OTVETS_COMMENT.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Текст комментария:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Количество:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COUNT_" value="$V_COUNT_" size="70" /><br />

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

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select  from  where =?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Коментарии к ответам</h2><form action="OTVETS_COMMENT.php" method="POST">
<a href="OTVETS.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all')
{
$sth=$cmf->execute('select A.OTVETS_COMMENT_ID,A.NAME,A.COUNT_ from OTVETS_COMMENT A where A.OTVETS_ID=?  order by A.COUNT_ desc ',$_REQUEST['pid']);
}
else
{
$sth=$cmf->execute('select A.OTVETS_COMMENT_ID,A.NAME,A.COUNT_ from OTVETS_COMMENT A
where A.OTVETS_ID > 0  order by A.COUNT_ desc limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);

}





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="5">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Текст комментария</th><th>Количество</th><td></td></tr>
EOF;


if($sth)
while(list($V_OTVETS_COMMENT_ID,$V_NAME,$V_COUNT_)=mysql_fetch_array($sth, MYSQL_NUM))
{






@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="id[]" value="$V_OTVETS_COMMENT_ID" /></td>
<td>$V_OTVETS_COMMENT_ID</td><td>$V_NAME</td><td>$V_COUNT_</td><td nowrap="">
EOF;

if ($cmf->W)
@print <<<EOF
<a href="OTVETS_COMMENT.php?e=ED&amp;id=$V_OTVETS_COMMENT_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

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
$sth=$cmf->execute('select OTVETS_ID, from OTVETS  order by ORDERING');
while(list($V_OTVETS_ID,$V_)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_OTVETS_ID?'<input type="radio" name="cid" value="'.$V_OTVETS_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_OTVETS_ID.'" />')."&#160;$V_</li>";
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>
