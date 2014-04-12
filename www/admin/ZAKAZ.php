<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('ZAKAZ');
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



    if(empty($_REQUEST['DATE_FROM'])) $_REQUEST['DATE_FROM']='';
    if(empty($_REQUEST['DATE_TO'])) $_REQUEST['DATE_TO']='';
    $filtpath = "&amp;f={$_REQUEST['f']}&amp;DATE_FROM={$_REQUEST['DATE_FROM']}&amp;DATE_TO={$_REQUEST['DATE_TO']}";
    $sumPrice = 0;  
     $cur = $cmf->selectrow_array("select SNAME
                          from CURRENCY
                          where PRICE = 1
                          and STATUS = 1");  
    

if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from ZAKAZ_ITEM where ZAKAZ_ID=? and ZAKAZ_ITEM_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{






$cmf->execute('update ZAKAZ_ITEM set ITEM_ID=?,CATALOGUE_ID=?,NAME=? where ZAKAZ_ID=? and ZAKAZ_ITEM_ID=?',stripslashes($_REQUEST['ITEM_ID'])+0,stripslashes($_REQUEST['CATALOGUE_ID'])+0,stripslashes($_REQUEST['NAME']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('ZAKAZ_ITEM');








$cmf->execute('insert into ZAKAZ_ITEM (ZAKAZ_ID,ZAKAZ_ITEM_ID,ITEM_ID,CATALOGUE_ID,NAME) values (?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['ITEM_ID'])+0,stripslashes($_REQUEST['CATALOGUE_ID'])+0,stripslashes($_REQUEST['NAME']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_ZAKAZ_ITEM_ID,$V_ITEM_ID,$V_CATALOGUE_ID,$V_NAME)=$cmf->selectrow_arrayQ('select ZAKAZ_ITEM_ID,ITEM_ID,CATALOGUE_ID,NAME from ZAKAZ_ITEM where ZAKAZ_ID=? and ZAKAZ_ITEM_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


@print <<<EOF
<h2 class="h2">Редактирование - Товары</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ZAKAZ.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
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
<tr bgcolor="#FFFFFF"><th width="1%"><b>ИД товара:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="ITEM_ID" value="$V_ITEM_ID" size="" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Рубрика:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATALOGUE_ID" value="$V_CATALOGUE_ID" size="" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
list($V_ZAKAZ_ITEM_ID,$V_ITEM_ID,$V_CATALOGUE_ID,$V_NAME)=array('','','','');


@print <<<EOF
<h2 class="h2">Добавление - Товары</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ZAKAZ.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>ИД товара:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="ITEM_ID" value="$V_ITEM_ID" size="" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Рубрика:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATALOGUE_ID" value="$V_CATALOGUE_ID" size="" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Наименование:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
$cmf->execute('delete from ZAKAZ where ZAKAZ_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{













$cmf->execute('insert into ZAKAZ (ZAKAZ_ID,DATA,NAME,EMAIL,TELMOB,CITY,COMPANY,DESCRIPTION,STATUS) values (null,?,?,?,?,?,?,?,?)',stripslashes($_REQUEST['DATA']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['TELMOB']),stripslashes($_REQUEST['CITY']),stripslashes($_REQUEST['COMPANY']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS'])+0);
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{











$cmf->execute('update ZAKAZ set DATA=?,NAME=?,EMAIL=?,TELMOB=?,CITY=?,COMPANY=?,DESCRIPTION=?,STATUS=? where ZAKAZ_ID=?',stripslashes($_REQUEST['DATA']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['EMAIL']),stripslashes($_REQUEST['TELMOB']),stripslashes($_REQUEST['CITY']),stripslashes($_REQUEST['COMPANY']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['STATUS'])+0,$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_ZAKAZ_ID,$V_DATA,$V_NAME,$V_EMAIL,$V_TELMOB,$V_CITY,$V_COMPANY,$V_DESCRIPTION,$V_STATUS)=
$cmf->selectrow_arrayQ('select ZAKAZ_ID,DATE_FORMAT(DATA,"%Y-%m-%d %H:%i"),NAME,EMAIL,TELMOB,CITY,COMPANY,DESCRIPTION,STATUS from ZAKAZ where ZAKAZ_ID=?',$_REQUEST['id']);



        $V_STR_STATUS=$cmf->Spravotchnik($V_STATUS,'select ZAKAZSTATUS_ID,NAME from ZAKAZSTATUS  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Заказы</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ZAKAZ.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(EMAIL) &amp;&amp; checkXML(TELMOB) &amp;&amp; checkXML(CITY) &amp;&amp; checkXML(COMPANY) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="7" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата заказа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TELMOB" value="$V_TELMOB" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Город:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CITY" value="$V_CITY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Компания:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COMPANY" value="$V_COMPANY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст сообщения:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Статус:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="STATUS"><option value="0">- не задан -</option>$V_STR_STATUS</select><br />
</td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />

EOF;




print <<<EOF
<a name="f1"></a><h3 class="h3">Товары</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="ZAKAZ.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select ZAKAZ_ITEM_ID,ITEM_ID,NAME from ZAKAZ_ITEM where ZAKAZ_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>ИД товара</th><th>Наименование</th><td></td></tr>
EOF;
while(list($V_ZAKAZ_ITEM_ID,$V_ITEM_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{


                $CATALOGUE_ID = $cmf->selectrow_array("select CATALOGUE_ID FROM ITEM WHERE ITEM_ID=?",$V_ITEM_ID);
                $CATALOGUE_NAME = $cmf->selectrow_array("select NAME FROM CATALOGUE WHERE CATALOGUE_ID=?",$CATALOGUE_ID);
                $V_NAME = '<a href="ITEM.php?e=ED&id='.$V_ITEM_ID.'&pid='.$CATALOGUE_ID.'" target="_blank">'.$V_NAME.'</a>';
                $V_NAME .= '<br/>Рубрика: <a href="ITEM.php?pid='.$CATALOGUE_ID.'" target="_blank">'.$CATALOGUE_NAME.'</a>';

                
@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_ZAKAZ_ITEM_ID" /></td>
<td>$V_ZAKAZ_ITEM_ID</td><td>$V_ITEM_ID</td><td>$V_NAME</td><td nowrap="">

<a href="ZAKAZ.php?e1=ED&amp;iid=$V_ZAKAZ_ITEM_ID&amp;id={$_REQUEST['id']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_ZAKAZ_ID,$V_DATA,$V_NAME,$V_EMAIL,$V_TELMOB,$V_CITY,$V_COMPANY,$V_DESCRIPTION,$V_STATUS)=array('','','','','','','','','');

$V_DATA=$cmf->selectrow_array('select now()');
$V_STR_STATUS=$cmf->Spravotchnik($V_STATUS,'select ZAKAZSTATUS_ID,NAME from ZAKAZSTATUS  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Заказы</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ZAKAZ.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(EMAIL) &amp;&amp; checkXML(TELMOB) &amp;&amp; checkXML(CITY) &amp;&amp; checkXML(COMPANY) &amp;&amp; checkXML(DESCRIPTION);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата заказа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Имя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>E-mail:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="EMAIL" value="$V_EMAIL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Телефон:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TELMOB" value="$V_TELMOB" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Город:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CITY" value="$V_CITY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Компания:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="COMPANY" value="$V_COMPANY" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст сообщения:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Статус:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="STATUS"><option value="0">- не задан -</option>$V_STR_STATUS</select><br />
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


print '<h2 class="h2">Заказы</h2><form action="ZAKAZ.php" method="POST">';



$pagesize=20;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from ZAKAZ A where 1'.' and'.
(($_REQUEST['f'] != '') ? ' STATUS='.$_REQUEST['f'] : ' 1=1 ').
(($_REQUEST['DATE_FROM'] != '')? " and DATE(A.DATA) >= DATE(STR_TO_DATE('{$_REQUEST['DATE_FROM']}', '%Y-%m-%d %H:%i'))":'').
(($_REQUEST['DATE_TO'] != '')? " and DATE(A.DATA) <= DATE(STR_TO_DATE('{$_REQUEST['DATE_TO']}', '%Y-%m-%d %H:%i'))":''));

$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 for($i=1;$i<=$_REQUEST['pcount'];$i++)
 {
  if($i==$_REQUEST['p']) { print '- <b class="red">'.$i.'</b>'; } else { print <<<EOF
- <a class="t" href="ZAKAZ.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.ZAKAZ_ID,DATE_FORMAT(A.DATA,"%Y-%m-%d %H:%i"),A.NAME,A.EMAIL,A.TELMOB,A.CITY,A.COMPANY from ZAKAZ A where 1 and '.
(($_REQUEST['f'] != '') ? ' STATUS='.$_REQUEST['f'] : ' 1=1 ').
(($_REQUEST['DATE_FROM'] != '')? " and DATE(A.DATA) >= DATE(STR_TO_DATE('{$_REQUEST['DATE_FROM']}', '%Y-%m-%d %H:%i'))":'').
(($_REQUEST['DATE_TO'] != '')? " and DATE(A.DATA) <= DATE(STR_TO_DATE('{$_REQUEST['DATE_TO']}', '%Y-%m-%d %H:%i'))":'').''.' order by A.DATA desc limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="2">
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
                        <td colspan="7" class="main_tbl_title">
EOF;
                        
                        $COUNT = $cmf->selectrow_array('select count(*) from ZAKAZ ');
                        $countNotViewed = $cmf->selectrow_array("select count(*) from ZAKAZ where STATUS = 0 or STATUS is NULL");
                        
                        if ( $_REQUEST['f'] > 0 || $_REQUEST['f'] == '0') 
                        {
                                print '<a href="ZAKAZ.php?s='.$_REQUEST['s'].'">Все ('.$COUNT.')</a>&#160;&#160;&#160;';
                        }
                        else 
                        {
                                print '<a href="ZAKAZ.php?s='.$_REQUEST['s'].'" class="red">Все ('.$COUNT.')</a>&#160;&#160;&#160;';
                        }
                        
                        if ( $_REQUEST['f'] == '0' ) 
                        {
                                print '<a href="ZAKAZ.php?f=0&amp;s='.$_REQUEST['s'].'" class="red">Необработанные ('.$countNotViewed.')</a>&#160;&#160;&#160;';
                        }
                        else 
                        {
                                print '<a href="ZAKAZ.php?f=0&amp;s='.$_REQUEST['s'].'">Необработанные ('.$countNotViewed.')</a>&#160;&#160;&#160;';
                        }
                        
                        $vopr = $cmf->select('select ZAKAZSTATUS_ID,NAME from ZAKAZSTATUS order by ORDERING');
                        $index = sizeof($vopr);
                        for($i=0; $i<$index; $i++)
                        {
                                $V_ZAKAZSTATUS_ID       = $vopr[$i]['ZAKAZSTATUS_ID'];
                                $V_NAME                         = $vopr[$i]['NAME'];
                                
                                $COUNT = $cmf->selectrow_array('select count(*) from ZAKAZ where STATUS=?',$V_ZAKAZSTATUS_ID);
                                
                                if($_REQUEST['f'] == $V_ZAKAZSTATUS_ID)
                                {
                                        print '<a href="ZAKAZ.php?f='.$V_ZAKAZSTATUS_ID.'&amp;s='.$_REQUEST['s'].'" class="red">'.$V_NAME.' ('.$COUNT.')</a>&#160;&#160;&#160;';
                                }
                                else 
                                {
                                        print '<a href="ZAKAZ.php?f='.$V_ZAKAZSTATUS_ID.'&amp;s='.$_REQUEST['s'].'">'.$V_NAME.' ('.$COUNT.')</a>&#160;&#160;&#160;'; 
                                }
                        }
print <<<EOF
<br/><b>C даты:</b><input type="hidden" id="DATE_FROM" name="DATE_FROM" value="{$_REQUEST['DATE_FROM']}" />
EOF;

if($_REQUEST['DATE_FROM']) $V_DAT_FROM = substr($_REQUEST['DATE_FROM'],8,2).".".substr($_REQUEST['DATE_FROM'],5,2).".".substr($_REQUEST['DATE_FROM'],0,4);//." ".substr($_REQUEST['DATE_FROM'],11,2).":".substr($_REQUEST['DATE_FROM'],14,2);
else $V_DAT_FROM = '';


        
        @print <<<EOF
        <div id="DATE_DATE_FROM">$V_DAT_FROM</div>
        <img src="img/img.gif" id="f_trigger_DATE_FROM" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATE_FROM",
                       displayArea    :    "DATE_DATE_FROM",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATE_FROM",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
<b>По дату:</b><input type="hidden" id="DATE_TO" name="DATE_TO" value="{$_REQUEST['DATE_TO']}" />
EOF;

if($_REQUEST['DATE_TO']) $V_DAT_TO = substr($_REQUEST['DATE_TO'],8,2).".".substr($_REQUEST['DATE_TO'],5,2).".".substr($_REQUEST['DATE_TO'],0,4);//." ".substr($V_DELIVERYDATA,11,2).":".substr($V_DELIVERYDATA,14,2);
else $V_DAT_TO = '';


        
        @print <<<EOF
        <div id="DATE_DATE_TO">$V_DAT_TO</div>
        <img src="img/img.gif" id="f_trigger_DATE_TO" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        
            
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATE_TO",
                       displayArea    :    "DATE_DATE_TO",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATE_TO",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>

<button type="submit" name="show" title="Показать">Показать</button>                        
                        </td>
                </tr>
EOF;

print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Дата заказа</th><th>Имя</th><th>E-mail</th><th>Телефон</th><th>Город</th><th>Компания</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_ZAKAZ_ID,$V_DATA,$V_NAME,$V_EMAIL,$V_TELMOB,$V_CITY,$V_COMPANY)=mysql_fetch_array($sth, MYSQL_NUM))
{
print <<<EOF
<input type="hidden" name="f" value="{$_REQUEST['f']}"/>
EOF;
    


print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="id[]" value="$V_ZAKAZ_ID" /></td>
<td>$V_ZAKAZ_ID</td><td>$V_DATA</td><td>$V_NAME</td><td>$V_EMAIL</td><td>$V_TELMOB</td><td>$V_CITY</td><td>$V_COMPANY</td><td>
EOF;
 $sth5=$cmf->execute(' select I.NAME, I.ITEM_ID, C.CATALOGUE_ID
                     from ZAKAZ_ITEM  Z
                       join  ITEM I using (ITEM_ID)
                       join CATALOGUE C on  (C.CATALOGUE_ID = I.CATALOGUE_ID)
                       where   Z.ZAKAZ_ID = ?',$V_ZAKAZ_ID);
 while(list($VV_NAME, $VV_ITEM_ID, $VV_CATALOGUE_ID)=mysql_fetch_array($sth5, MYSQL_NUM))
 {
   echo "<a href = '/admin/ITEM.php?e=ED&id=$VV_ITEM_ID&pid=$VV_CATALOGUE_ID' target='_brank'>$VV_NAME</a>";
 }
print <<<EOF
</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="ZAKAZ.php?e=ED&amp;id=$V_ZAKAZ_ID&amp;p={$_REQUEST['p']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
