<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('SPECIAL_OFFERS');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
$VIRTUAL_IMAGE_PATH="/offers/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';




if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from SPECIAL_OFFERS_LANGS where SPECIAL_OFFERS_ID=? and SPECIAL_OFFERS_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{






$cmf->execute('update SPECIAL_OFFERS_LANGS set CMF_LANG_ID=?,NAME=?,DESCRIPTION=? where SPECIAL_OFFERS_ID=? and SPECIAL_OFFERS_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('SPECIAL_OFFERS_LANGS');








$cmf->execute('insert into SPECIAL_OFFERS_LANGS (SPECIAL_OFFERS_ID,SPECIAL_OFFERS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION) values (?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_SPECIAL_OFFERS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=$cmf->selectrow_arrayQ('select SPECIAL_OFFERS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION from SPECIAL_OFFERS_LANGS where SPECIAL_OFFERS_ID=? and SPECIAL_OFFERS_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="SPECIAL_OFFERS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
list($V_SPECIAL_OFFERS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=array('','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="SPECIAL_OFFERS.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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









if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
$cmf->execute('delete from SPECIAL_OFFERS where SPECIAL_OFFERS_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{








$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('insert into SPECIAL_OFFERS (SPECIAL_OFFERS_ID,DATA,NAME,DESCRIPTION,URL,STATUS) values (null,?,?,?,?,?)',stripslashes($_REQUEST['DATA']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['STATUS']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{






$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('update SPECIAL_OFFERS set DATA=?,NAME=?,DESCRIPTION=?,URL=?,STATUS=? where SPECIAL_OFFERS_ID=?',stripslashes($_REQUEST['DATA']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_SPECIAL_OFFERS_ID,$V_DATA,$V_NAME,$V_DESCRIPTION,$V_URL,$V_STATUS)=
$cmf->selectrow_arrayQ('select SPECIAL_OFFERS_ID,DATE_FORMAT(DATA,"%Y-%m-%d %H:%i"),NAME,DESCRIPTION,URL,STATUS from SPECIAL_OFFERS where SPECIAL_OFFERS_ID=?',$_REQUEST['id']);



$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Спецпредложения</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="SPECIAL_OFFERS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(URL);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="6" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$REQUEST['s']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA" name="DATA" value="$V_DATA" />
EOF;

if($V_DATA) $V_DAT_ = substr($V_DATA,8,2).".".substr($V_DATA,5,2).".".substr($V_DATA,0,4)." ".substr($V_DATA,11,2).":".substr($V_DATA,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA",
                       displayArea    :    "DATE_DATA",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
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
<form action="SPECIAL_OFFERS.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select SPECIAL_OFFERS_LANGS_ID,CMF_LANG_ID,NAME from SPECIAL_OFFERS_LANGS where SPECIAL_OFFERS_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Заголовок </th><td></td></tr>
EOF;
while(list($V_SPECIAL_OFFERS_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_SPECIAL_OFFERS_LANGS_ID" /></td>
<td>$V_SPECIAL_OFFERS_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="SPECIAL_OFFERS.php?e1=ED&amp;iid=$V_SPECIAL_OFFERS_LANGS_ID&amp;id={$_REQUEST['id']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_SPECIAL_OFFERS_ID,$V_DATA,$V_NAME,$V_DESCRIPTION,$V_URL,$V_STATUS)=array('','','','','','');

$V_DATA=$cmf->selectrow_array('select now()');
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Спецпредложения</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="SPECIAL_OFFERS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(URL);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA" name="DATA" value="$V_DATA" />
EOF;

if($V_DATA) $V_DAT_ = substr($V_DATA,8,2).".".substr($V_DATA,5,2).".".substr($V_DATA,0,4)." ".substr($V_DATA,11,2).":".substr($V_DATA,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA",
                       displayArea    :    "DATE_DATA",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

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


print '<h2 class="h2">Спецпредложения</h2><form action="SPECIAL_OFFERS.php" method="POST">';



$_REQUEST['s']+=0;
$SORTNAMES=array('N','Дата','Заголовок');
$SORTQUERY=array('order by A.SPECIAL_OFFERS_ID ','order by A.SPECIAL_OFFERS_ID desc ','order by A.DATA ','order by A.DATA desc ','order by A.NAME ','order by A.NAME desc ');
list ($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SPECIAL_OFFERS.php?s=$tmps{$filtpath}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SPECIAL_OFFERS.php?s=$tmps{$filtpath}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SPECIAL_OFFERS.php?s=$tmps{$filtpath}">$tmp</a></th>
EOF;
        }
        $i++;
}


$pagesize=20;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from SPECIAL_OFFERS A where 1');

$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 for($i=1;$i<=$_REQUEST['pcount'];$i++)
 {
  if($i==$_REQUEST['p']) { print '- <b class="red">'.$i.'</b>'; } else { print <<<EOF
- <a class="t" href="SPECIAL_OFFERS.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.SPECIAL_OFFERS_ID,DATE_FORMAT(A.DATA,"%Y-%m-%d %H:%i"),A.NAME,A.STATUS from SPECIAL_OFFERS A where 1'.' '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);





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
while(list($V_SPECIAL_OFFERS_ID,$V_DATA,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_SPECIAL_OFFERS_ID" /></td>
<td>$V_SPECIAL_OFFERS_ID</td><td>$V_DATA</td><td>$V_NAME</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="SPECIAL_OFFERS.php?e=ED&amp;id=$V_SPECIAL_OFFERS_ID&amp;p={$_REQUEST['p']}&amp;s={$_REQUEST['s']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
