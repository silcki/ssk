<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('ANNOUNCEMENT');
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
$cmf->execute('delete from ANNOUNCEMENT where ANNOUNCEMENT_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{

      require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Translit.class.php');      
      $translit = new Translit();
      $_REQUEST['SPECIAL_URL'] = $translit->getLatin($_REQUEST['NAME']);      
    















$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('insert into ANNOUNCEMENT (ANNOUNCEMENT_ID,ANNOUNCEMENT_RUBRICS_ID,ANNOUNCEMENT_TYPES_ID,TITLE,TEXT,ORGANIZATION,COUNTRY,CITY,NAME,PHONE,FAX,EMAIL,DATE,STATUS) values (null,?,?,?,?,?,?,?,?,?,?,?,?,?)',stripslashes($_REQUEST['ANNOUNCEMENT_RUBRICS_ID'])+0,stripslashes($_REQUEST['ANNOUNCEMENT_TYPES_ID'])+0,stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['TEXT']),stripslashes($_REQUEST['ORGANIZATION']),stripslashes($_REQUEST['COUNTRY']),stripslashes($_REQUEST['CITY']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['PHONE']),stripslashes($_REQUEST['FAX']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['DATE']),stripslashes($_REQUEST['STATUS']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{

      require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Translit.class.php');      
      $translit = new Translit();
      $_REQUEST['SPECIAL_URL'] = $translit->getLatin($_REQUEST['NAME']);      
    













$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('update ANNOUNCEMENT set ANNOUNCEMENT_RUBRICS_ID=?,ANNOUNCEMENT_TYPES_ID=?,TITLE=?,TEXT=?,ORGANIZATION=?,COUNTRY=?,CITY=?,NAME=?,PHONE=?,FAX=?,EMAIL=?,DATE=?,STATUS=? where ANNOUNCEMENT_ID=?',stripslashes($_REQUEST['ANNOUNCEMENT_RUBRICS_ID'])+0,stripslashes($_REQUEST['ANNOUNCEMENT_TYPES_ID'])+0,stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['TEXT']),stripslashes($_REQUEST['ORGANIZATION']),stripslashes($_REQUEST['COUNTRY']),stripslashes($_REQUEST['CITY']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['PHONE']),stripslashes($_REQUEST['FAX']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['DATE']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_ANNOUNCEMENT_ID,$V_ANNOUNCEMENT_RUBRICS_ID,$V_ANNOUNCEMENT_TYPES_ID,$V_TITLE,$V_TEXT,$V_ORGANIZATION,$V_COUNTRY,$V_CITY,$V_NAME,$V_PHONE,$V_FAX,$V_EMAIL,$V_DATE,$V_STATUS)=
$cmf->selectrow_arrayQ('select ANNOUNCEMENT_ID,ANNOUNCEMENT_RUBRICS_ID,ANNOUNCEMENT_TYPES_ID,TITLE,TEXT,ORGANIZATION,COUNTRY,CITY,NAME,PHONE,FAX,EMAIL,DATE_FORMAT(DATE,"%Y-%m-%d %H:%i"),STATUS from ANNOUNCEMENT where ANNOUNCEMENT_ID=?',$_REQUEST['id']);



        $V_STR_ANNOUNCEMENT_RUBRICS_ID=$cmf->Spravotchnik($V_ANNOUNCEMENT_RUBRICS_ID,'select A.ANNOUNCEMENT_RUBRICS_ID,NAME from ANNOUNCEMENT_RUBRICS A    where 1     order by NAME');
        
        $V_STR_ANNOUNCEMENT_TYPES_ID=$cmf->Spravotchnik($V_ANNOUNCEMENT_TYPES_ID,'select A.ANNOUNCEMENT_TYPES_ID,NAME from ANNOUNCEMENT_TYPES A    where 1     order by NAME');
        
$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Объявления</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ANNOUNCEMENT.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(TEXT) &amp;&amp; checkXML(ORGANIZATION) &amp;&amp; checkXML(COUNTRY) &amp;&amp; checkXML(CITY) &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PHONE) &amp;&amp; checkXML(FAX) &amp;&amp; checkXML(EMAIL);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$REQUEST['s']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Рубрика:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ANNOUNCEMENT_RUBRICS_ID">$V_STR_ANNOUNCEMENT_RUBRICS_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тип:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ANNOUNCEMENT_TYPES_ID">$V_STR_ANNOUNCEMENT_TYPES_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст объявления:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TEXT" rows="7" cols="90">$V_TEXT</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Организация:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="ORGANIZATION" value="$V_ORGANIZATION" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Страна:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COUNTRY" value="$V_COUNTRY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Город:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CITY" value="$V_CITY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PHONE" value="$V_PHONE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Факс:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="FAX" value="$V_FAX" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Дата добавления:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATE" name="DATE" value="$V_DATE" />
EOF;

if($V_DATE) $V_DAT_ = substr($V_DATE,8,2).".".substr($V_DATE,5,2).".".substr($V_DATE,0,4)." ".substr($V_DATE,11,2).":".substr($V_DATE,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATE">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATE" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATE",
                       displayArea    :    "DATE_DATE",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATE",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>


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
list($V_ANNOUNCEMENT_ID,$V_ANNOUNCEMENT_RUBRICS_ID,$V_ANNOUNCEMENT_TYPES_ID,$V_TITLE,$V_TEXT,$V_ORGANIZATION,$V_COUNTRY,$V_CITY,$V_NAME,$V_PHONE,$V_FAX,$V_EMAIL,$V_DATE,$V_STATUS)=array('','','','','','','','','','','','','','');

$V_STR_ANNOUNCEMENT_RUBRICS_ID=$cmf->Spravotchnik($V_ANNOUNCEMENT_RUBRICS_ID,'select A.ANNOUNCEMENT_RUBRICS_ID,NAME from ANNOUNCEMENT_RUBRICS A    order by NAME');
        
$V_STR_ANNOUNCEMENT_TYPES_ID=$cmf->Spravotchnik($V_ANNOUNCEMENT_TYPES_ID,'select A.ANNOUNCEMENT_TYPES_ID,NAME from ANNOUNCEMENT_TYPES A    order by NAME');
        
$V_DATE=$cmf->selectrow_array('select now()');
$V_STATUS='';
@print <<<EOF
<h2 class="h2">Добавление - Объявления</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ANNOUNCEMENT.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(TEXT) &amp;&amp; checkXML(ORGANIZATION) &amp;&amp; checkXML(COUNTRY) &amp;&amp; checkXML(CITY) &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(PHONE) &amp;&amp; checkXML(FAX) &amp;&amp; checkXML(EMAIL);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Рубрика:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ANNOUNCEMENT_RUBRICS_ID">$V_STR_ANNOUNCEMENT_RUBRICS_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тип:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ANNOUNCEMENT_TYPES_ID">$V_STR_ANNOUNCEMENT_TYPES_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Заголовок:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст объявления:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TEXT" rows="7" cols="90">$V_TEXT</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Организация:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="ORGANIZATION" value="$V_ORGANIZATION" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Страна:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COUNTRY" value="$V_COUNTRY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Город:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CITY" value="$V_CITY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="PHONE" value="$V_PHONE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Факс:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="FAX" value="$V_FAX" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Дата добавления:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATE" name="DATE" value="$V_DATE" />
EOF;

if($V_DATE) $V_DAT_ = substr($V_DATE,8,2).".".substr($V_DATE,5,2).".".substr($V_DATE,0,4)." ".substr($V_DATE,11,2).":".substr($V_DATE,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATE">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATE" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATE",
                       displayArea    :    "DATE_DATE",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATE",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
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
list($filtpath,$filtwhere)=array('','');
foreach($_REQUEST as $key=>$val)
{
  if(preg_match('/^FLT_(.+)$/',$key,$p))
  {
    if($val!='')
     {
        $filtpath.='&amp;'.$key.'='.$val;
     }
  }
}



print '<h2 class="h2">Объявления</h2><form action="ANNOUNCEMENT.php" method="POST">';


if($_REQUEST['s'] == ''){$_REQUEST['s']=11;}
$_REQUEST['s']+=0;
$SORTNAMES=array('N','Рубрика','Тип','Заголовок','Текст объявления','Дата добавления');
$SORTQUERY=array('order by A.ANNOUNCEMENT_ID ','order by A.ANNOUNCEMENT_ID desc ','order by A.ANNOUNCEMENT_RUBRICS_ID ','order by A.ANNOUNCEMENT_RUBRICS_ID desc ','order by A.ANNOUNCEMENT_TYPES_ID ','order by A.ANNOUNCEMENT_TYPES_ID desc ','order by A.TITLE ','order by A.TITLE desc ','order by A.TEXT ','order by A.TEXT desc ','order by A.DATE ','order by A.DATE desc ');
list ($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ANNOUNCEMENT.php?s=$tmps{$filtpath}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ANNOUNCEMENT.php?s=$tmps{$filtpath}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="ANNOUNCEMENT.php?s=$tmps{$filtpath}">$tmp</a></th>
EOF;
        }
        $i++;
}


$pagesize=35;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ANNOUNCEMENT A where 1'.' and'.(($_REQUEST['f'] != '') ? ' ANNOUNCEMENT_TYPES_ID='.$_REQUEST['f'] : ' 1=1 ')
.($cmf->Param('FLT_ANNOUNCEMENT_RUBRICS_ID')?' and A.ANNOUNCEMENT_RUBRICS_ID='.mysql_escape_string($cmf->Param('FLT_ANNOUNCEMENT_RUBRICS_ID')):'')
.($cmf->Param('FLT_ANNOUNCEMENT_TYPES_ID')?' and A.ANNOUNCEMENT_TYPES_ID='.mysql_escape_string($cmf->Param('FLT_ANNOUNCEMENT_TYPES_ID')):''));

$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 for($i=1;$i<=$_REQUEST['pcount'];$i++)
 {
  if($i==$_REQUEST['p']) { print '- <b class="red">'.$i.'</b>'; } else { print <<<EOF
- <a class="t" href="ANNOUNCEMENT.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.ANNOUNCEMENT_ID,A.ANNOUNCEMENT_RUBRICS_ID,A.ANNOUNCEMENT_TYPES_ID,A.TITLE,A.TEXT,DATE_FORMAT(A.DATE,"%Y-%m-%d %H:%i"),A.STATUS from ANNOUNCEMENT A where 1 and '.(($_REQUEST['f'] != '') ? ' ANNOUNCEMENT_TYPES_ID='.$_REQUEST['f'] : ' 1=1 ').''
.($cmf->Param('FLT_ANNOUNCEMENT_RUBRICS_ID')?' and A.ANNOUNCEMENT_RUBRICS_ID='.mysql_escape_string($cmf->Param('FLT_ANNOUNCEMENT_RUBRICS_ID')):'')
.($cmf->Param('FLT_ANNOUNCEMENT_TYPES_ID')?' and A.ANNOUNCEMENT_TYPES_ID='.mysql_escape_string($cmf->Param('FLT_ANNOUNCEMENT_TYPES_ID')):'').' '.$SORTQUERY[$_REQUEST['s']].'limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);





$V_STR_ANNOUNCEMENT_RUBRICS_ID=$cmf->Spravotchnik($cmf->Param('FLT_ANNOUNCEMENT_RUBRICS_ID'),'select ANNOUNCEMENT_RUBRICS_ID,NAME from ANNOUNCEMENT_RUBRICS   order by NAME');
$V_STR_ANNOUNCEMENT_TYPES_ID=$cmf->Spravotchnik($cmf->Param('FLT_ANNOUNCEMENT_TYPES_ID'),'select ANNOUNCEMENT_TYPES_ID,NAME from ANNOUNCEMENT_TYPES   order by NAME');
@print <<<EOF
<table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />

<tr bgcolor="#F0F0F0"><td colspan="2"><input type="submit" name="e" value="Фильтр" class="gbt bflt" /></td></tr>
<tr bgcolor="#FFFFFF"><th>Рубрика<br /><img src="i/0.gif" width="125" height="1" /></th><td><select name="FLT_ANNOUNCEMENT_RUBRICS_ID"><option value="0">--------</option>{$V_STR_ANNOUNCEMENT_RUBRICS_ID}</select><br /></td></tr><tr bgcolor="#FFFFFF"><th>Тип<br /><img src="i/0.gif" width="125" height="1" /></th><td><select name="FLT_ANNOUNCEMENT_TYPES_ID"><option value="0">--------</option>{$V_STR_ANNOUNCEMENT_TYPES_ID}</select><br /></td></tr>
</table>
EOF;


@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="1">
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

</td>
<td colspan="7">
EOF;

$COUNT = $cmf->selectrow_array('select count(*) from ANNOUNCEMENT ');
if ( $_REQUEST['f'] > 0 )
{
print '<a href="ANNOUNCEMENT.php?s='.$_REQUEST['s'].'">Все ('.$COUNT.')</a>&#160;&#160;&#160;';
}
else
{
print '<a href="ANNOUNCEMENT.php?s='.$_REQUEST['s'].'" class="red">Все ('.$COUNT.')</a>&#160;&#160;&#160;';
}

$vopr = $cmf->select('select ANNOUNCEMENT_TYPES_ID,NAME from ANNOUNCEMENT_TYPES order by ANNOUNCEMENT_TYPES_ID');
$index = sizeof($vopr);
for($i=0; $i<$index; $i++)
{
$V_ANNOUNCEMENT_TYPES_ID       = $vopr[$i]['ANNOUNCEMENT_TYPES_ID'];
$V_NAME                        = $vopr[$i]['NAME'];

$COUNT = $cmf->selectrow_array('select count(*) from ANNOUNCEMENT where ANNOUNCEMENT_TYPES_ID=?',$V_ANNOUNCEMENT_TYPES_ID);

if($_REQUEST['f'] == $V_ANNOUNCEMENT_TYPES_ID)
{
   print '<a href="ANNOUNCEMENT.php?f='.$V_ANNOUNCEMENT_TYPES_ID.'&amp;s='.$_REQUEST['s'].'" class="red">'.$V_NAME.' ('.$COUNT.')</a>&#160;&#160;&#160;';
}
else
{
print '<a href="ANNOUNCEMENT.php?f='.$V_ANNOUNCEMENT_TYPES_ID.'&amp;s='.$_REQUEST['s'].'">'.$V_NAME.' ('.$COUNT.')</a>&#160;&#160;&#160;';                                }
}
print <<<EOF
</td>
</tr>
EOF;

print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td>$HEADER<td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_ANNOUNCEMENT_ID,$V_ANNOUNCEMENT_RUBRICS_ID,$V_ANNOUNCEMENT_TYPES_ID,$V_TITLE,$V_TEXT,$V_DATE,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
                        $V_ANNOUNCEMENT_RUBRICS_ID=$cmf->selectrow_arrayQ('select NAME from ANNOUNCEMENT_RUBRICS A   where A.ANNOUNCEMENT_RUBRICS_ID=?',$V_ANNOUNCEMENT_RUBRICS_ID);

                        
                        $V_ANNOUNCEMENT_TYPES_ID=$cmf->selectrow_arrayQ('select NAME from ANNOUNCEMENT_TYPES A   where A.ANNOUNCEMENT_TYPES_ID=?',$V_ANNOUNCEMENT_TYPES_ID);

                        
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_ANNOUNCEMENT_ID" /></td>
<td>$V_ANNOUNCEMENT_ID</td><td>$V_ANNOUNCEMENT_RUBRICS_ID</td><td>$V_ANNOUNCEMENT_TYPES_ID</td><td>$V_TITLE</td><td>$V_TEXT</td><td>$V_DATE</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="ANNOUNCEMENT.php?e=ED&amp;id=$V_ANNOUNCEMENT_ID&amp;p={$_REQUEST['p']}&amp;s={$_REQUEST['s']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
