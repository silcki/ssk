<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('SOKOBAN');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
$VIRTUAL_IMAGE_PATH="/wm/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';












if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
$cmf->execute('delete from SOKOBAN where SOKOBAN_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{







$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('insert into SOKOBAN (SOKOBAN_ID,NAME,LEVEL,LEVEL_CODE,STATUS) values (null,?,?,?,?)',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['LEVEL'])+0,stripslashes($_REQUEST['LEVEL_CODE']),stripslashes($_REQUEST['STATUS']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

            if (!empty($_POST['LEVEL_CODE'])) {
                $string = $_POST['LEVEL_CODE'];
                $rows_array = explode("\n", $_POST['LEVEL_CODE']);
                $rows_array = array_map('trim', $rows_array);

                $cmf->execute('update SOKOBAN set LEVEL_CODE_JSON=? where SOKOBAN_ID=?',sokoban($rows_array), $_REQUEST['id']);
            }
    
}

if($_REQUEST['e'] == 'Изменить')
{





$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;

$cmf->execute('update SOKOBAN set NAME=?,LEVEL=?,LEVEL_CODE=?,STATUS=? where SOKOBAN_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['LEVEL'])+0,stripslashes($_REQUEST['LEVEL_CODE']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

            if (!empty($_POST['LEVEL_CODE'])) {
                $string = $_POST['LEVEL_CODE'];
                $rows_array = explode("\n", $_POST['LEVEL_CODE']);
                $rows_array = array_map('trim', $rows_array);

                $cmf->execute('update SOKOBAN set LEVEL_CODE_JSON=? where SOKOBAN_ID=?',sokoban($rows_array), $_REQUEST['id']);
            }
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_SOKOBAN_ID,$V_NAME,$V_LEVEL,$V_LEVEL_CODE,$V_STATUS)=
$cmf->selectrow_arrayQ('select SOKOBAN_ID,NAME,LEVEL,LEVEL_CODE,STATUS from SOKOBAN where SOKOBAN_ID=?',$_REQUEST['id']);



$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Карты игры</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="SOKOBAN.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(LEVEL_CODE);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$REQUEST['s']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Уровень:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="LEVEL" value="$V_LEVEL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>
                Код уровня
                0 - пусто,
                1 - стена,
                x - цель,
                s - ящик,
                o - ящик на целе,
                k - человечек,
                b - человечек на целе
            :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="LEVEL_CODE" rows="7" cols="90">$V_LEVEL_CODE</textarea><br />


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
list($V_SOKOBAN_ID,$V_NAME,$V_LEVEL,$V_LEVEL_CODE,$V_STATUS)=array('','','','','');

$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Карты игры</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="SOKOBAN.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(LEVEL_CODE);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Уровень:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="LEVEL" value="$V_LEVEL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>
                Код уровня
                0 - пусто,
                1 - стена,
                x - цель,
                s - ящик,
                o - ящик на целе,
                k - человечек,
                b - человечек на целе
            :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="LEVEL_CODE" rows="7" cols="90">$V_LEVEL_CODE</textarea><br />


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


print '<h2 class="h2">Карты игры</h2><form action="SOKOBAN.php" method="POST">';



$_REQUEST['s']+=0;
$SORTNAMES=array('N','Название','Уровень');
$SORTQUERY=array('order by A.SOKOBAN_ID ','order by A.SOKOBAN_ID desc ','order by A.NAME ','order by A.NAME desc ','order by A.LEVEL ','order by A.LEVEL desc ');
list ($HEADER,$i)=array('',0);

foreach ($SORTNAMES as $tmp)
{
        $tmps=$i*2;
        if(($_REQUEST['s']-$tmps)==0) 
        {
                $tmps+=1;
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SOKOBAN.php?s=$tmps{$filtpath}">$tmp <img src="i/sdn.gif" border="0" /></a></th>
EOF;
        }
        elseif(($_REQUEST['s']-$tmps)==1)
        {
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SOKOBAN.php?s=$tmps{$filtpath}">$tmp <img src="i/sup.gif" border="0" /></a></th>
EOF;
        } 
        else { 
$HEADER.=<<<EOF
<th nowrap=""><a class="b" href="SOKOBAN.php?s=$tmps{$filtpath}">$tmp</a></th>
EOF;
        }
        $i++;
}



$sth=$cmf->execute('select A.SOKOBAN_ID,A.NAME,A.LEVEL,A.STATUS from SOKOBAN A where 1'.' '.$SORTQUERY[$_REQUEST['s']]);





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
while(list($V_SOKOBAN_ID,$V_NAME,$V_LEVEL,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_SOKOBAN_ID" /></td>
<td>$V_SOKOBAN_ID</td><td>$V_NAME</td><td>$V_LEVEL</td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="SOKOBAN.php?e=ED&amp;id=$V_SOKOBAN_ID&amp;s={$_REQUEST['s']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


</td></tr>
EOF;
}
}
 
print '</table>';
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();

                function sokoban($rows_array)
                {
                    $result = array();
                    if (!empty($rows_array) && is_array($rows_array)) {
                        $num_rows = 0;
                        $num_columns = 0;
                        foreach ($rows_array as $row => $row_data) {
                            $row_length = strlen($row_data);
                            $num_columns = $num_columns < $row_length ? $row_length:$num_columns;
                            for ($col = 0; $col < $row_length; $col++) {
                                switch ($row_data[$col]) {
                                    case '0'://пусто
                                        $result['map'][$row][$col] = 0;
                                        break;
                                    case '1'://стена
                                        $result['map'][$row][$col] = 1;
                                        break;
                                    case 'x'://цель
                                        $result['map'][$row][$col] = 10;
                                        break;
                                    case 's'://ящик
                                        $result['map'][$row][$col] = 20;
                                        break;
                                    case 'o'://ящик
                                        $result['map'][$row][$col] = 30;
                                        break;
                                    case 'k'://Кира
                                        $result['map'][$row][$col] = 100;
                                        $result['kira_x'] = $col;
                                        $result['kira_y'] = $row;
                                        break;
                                    case 'b'://Кира и цель
                                        $result['map'][$row][$col] = 110;
                                        $result['kira_x'] = $col;
                                        $result['kira_y'] = $row;
                                        break;
                                }
                            }
                        }

                        $result['num_rows'] = count($rows_array);
                        $result['num_columns'] = $num_columns;
                    }

                    return json_encode($result);
                }
        
?>
