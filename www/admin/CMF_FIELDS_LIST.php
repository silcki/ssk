<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CMF_FIELDS_LIST');
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

$_REQUEST['pid']=$cmf->selectrow_array('select CMF_FIELDS_ID from CMF_FIELDS_LIST where CMF_FIELDS_LIST_ID=? ',$_REQUEST['id']);
}












if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

foreach ($_REQUEST['id'] as $id)
 {

$cmf->execute('delete from CMF_FIELDS_LIST where CMF_FIELDS_LIST_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{




$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('insert into CMF_FIELDS_LIST (CMF_FIELDS_LIST_ID,CMF_FIELDS_ID,NAME,STATUS) values (null,?,?,?)',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e'] ='ED';

}

if($_REQUEST['e'] == 'Изменить')
{




$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

if(!empty($_REQUEST['pid'])) $cmf->execute('update CMF_FIELDS_LIST set CMF_FIELDS_ID=?,NAME=?,STATUS=? where CMF_FIELDS_LIST_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
else $cmf->execute('update CMF_FIELDS_LIST set NAME=?,STATUS=? where CMF_FIELDS_LIST_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);

$_REQUEST['e'] ='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_CMF_FIELDS_LIST_ID,$V_CMF_FIELDS_ID,$V_NAME,$V_STATUS)=$cmf->selectrow_arrayQ('select CMF_FIELDS_LIST_ID,CMF_FIELDS_ID,NAME,STATUS from CMF_FIELDS_LIST where CMF_FIELDS_LIST_ID=?',$_REQUEST['id']);



$V_STATUS=$V_STATUS?'checked':'';
print @<<<EOF
<h2 class="h2">Редактирование - Список возможных значений</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_FIELDS_LIST.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Значение:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

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
list($V_CMF_FIELDS_LIST_ID,$V_CMF_FIELDS_ID,$V_NAME,$V_STATUS)=array('','','','');


$V_STATUS='';
@print <<<EOF
<h2 class="h2">Добавление - Список возможных значений</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CMF_FIELDS_LIST.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Значение:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
if(empty($_REQUEST['pid'])) $_REQUEST['pid'] = 0;

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select NAME from CMF_FIELDS where CMF_FIELDS_ID=?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Список возможных значений</h2><form action="CMF_FIELDS_LIST.php" method="POST">
<a href="CMF_FIELDS.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




$_REQUEST['s']+=0;
$SORTNAMES=array('N','Значение');
$SORTQUERY=array('order by A.CMF_FIELDS_LIST_ID ','order by A.CMF_FIELDS_LIST_ID desc ','order by A.NAME ','order by A.NAME desc ');

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
<th nowrap=""><a class="b" href="CMF_FIELDS_LIST.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="CMF_FIELDS_LIST.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="CMF_FIELDS_LIST.php?pid={$_REQUEST['pid']}&amp;s=$tmps{$filters}">$tmp</a></th>
EOF;
        }
        $i++;
}


if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all')
{
$sth=$cmf->execute('select A.CMF_FIELDS_LIST_ID,A.NAME,A.STATUS from CMF_FIELDS_LIST A where A.CMF_FIELDS_ID=?  '.$SORTQUERY[$_REQUEST['s']],$_REQUEST['pid']);
}
else
{
$sth=$cmf->execute('select A.CMF_FIELDS_LIST_ID,A.NAME,A.STATUS from CMF_FIELDS_LIST A
where A.CMF_FIELDS_ID > 0  '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);

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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td>$HEADER<td></td></tr>
EOF;


if($sth)
while(list($V_CMF_FIELDS_LIST_ID,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{




if($V_STATUS == 1){$V_COLOR='#FFFFFF';} else {$V_COLOR='#a0a0a0';}



@print <<<EOF
<tr bgcolor="$V_COLOR">
<td><input type="checkbox" name="id[]" value="$V_CMF_FIELDS_LIST_ID" /></td>
<td>$V_CMF_FIELDS_LIST_ID</td><td>$V_NAME</td><td nowrap="">
EOF;

if ($cmf->W)
@print <<<EOF
<a href="CMF_FIELDS_LIST.php?e=ED&amp;id=$V_CMF_FIELDS_LIST_ID&amp;pid={$_REQUEST['pid']}&amp;s={$_REQUEST['s']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

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
$sth=$cmf->execute('select CMF_FIELDS_ID, from CMF_FIELDS  order by ORDERING');
while(list($V_CMF_FIELDS_ID,$V_)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_CMF_FIELDS_ID?'<input type="radio" name="cid" value="'.$V_CMF_FIELDS_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_CMF_FIELDS_ID.'" />')."&#160;$V_</li>";
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>
