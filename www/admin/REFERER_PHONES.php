<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('REFERER_PHONES');
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












if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
$cmf->execute('delete from REFERER_PHONES where REFERER_PHONES_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{









$cmf->execute('insert into REFERER_PHONES (REFERER_PHONES_ID,NAME,PHONE,DOMENS,CRITERIA) values (null,?,?,?,?)',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['PHONE']),stripslashes($_REQUEST['DOMENS']),stripslashes($_REQUEST['CRITERIA']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{







$cmf->execute('update REFERER_PHONES set NAME=?,PHONE=?,DOMENS=?,CRITERIA=? where REFERER_PHONES_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['PHONE']),stripslashes($_REQUEST['DOMENS']),stripslashes($_REQUEST['CRITERIA']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_REFERER_PHONES_ID,$V_NAME,$V_PHONE,$V_DOMENS,$V_CRITERIA)=
$cmf->selectrow_arrayQ('select REFERER_PHONES_ID,NAME,PHONE,DOMENS,CRITERIA from REFERER_PHONES where REFERER_PHONES_ID=?',$_REQUEST['id']);



@print <<<EOF
<h2 class="h2">Редактирование - Телефон по рефереру</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="REFERER_PHONES.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PHONE) &amp;&amp; checkXML(DOMENS) &amp;&amp; checkXML(CRITERIA);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$REQUEST['s']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PHONE" value="$V_PHONE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Домены (через запятую):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="DOMENS" value="$V_DOMENS" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Критерии (через запятую):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CRITERIA" value="$V_CRITERIA" size="90" /><br />

</td></tr>


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
list($V_REFERER_PHONES_ID,$V_NAME,$V_PHONE,$V_DOMENS,$V_CRITERIA)=array('','','','','');

@print <<<EOF
<h2 class="h2">Добавление - Телефон по рефереру</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="REFERER_PHONES.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PHONE) &amp;&amp; checkXML(DOMENS) &amp;&amp; checkXML(CRITERIA);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PHONE" value="$V_PHONE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Домены (через запятую):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="DOMENS" value="$V_DOMENS" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Критерии (через запятую):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CRITERIA" value="$V_CRITERIA" size="90" /><br />

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


print '<h2 class="h2">Телефон по рефереру</h2><form action="REFERER_PHONES.php" method="POST">';



$_REQUEST['s']+=0;
$SORTNAMES=array('N','Название','Телефон');
$SORTQUERY=array('order by A.REFERER_PHONES_ID ','order by A.REFERER_PHONES_ID desc ','order by A.NAME ','order by A.NAME desc ','order by A.PHONE ','order by A.PHONE desc ');
list ($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="REFERER_PHONES.php?s=$tmps{$filtpath}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="REFERER_PHONES.php?s=$tmps{$filtpath}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="REFERER_PHONES.php?s=$tmps{$filtpath}">$tmp</a></th>
EOF;
        }
        $i++;
}


$pagesize=50;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from REFERER_PHONES A where 1');

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
- <a class="t" href="REFERER_PHONES.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.REFERER_PHONES_ID,A.NAME,A.PHONE from REFERER_PHONES A where 1'.' '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$startSelect,(int) $pagesize);





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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td>$HEADER<td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_REFERER_PHONES_ID,$V_NAME,$V_PHONE)=mysql_fetch_array($sth, MYSQL_NUM))
{


print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="id[]" value="$V_REFERER_PHONES_ID" /></td>
<td>$V_REFERER_PHONES_ID</td><td>$V_NAME</td><td>$V_PHONE</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="REFERER_PHONES.php?e=ED&amp;id=$V_REFERER_PHONES_ID&amp;p={$_REQUEST['p']}&amp;s={$_REQUEST['s']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
