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












if(($_REQUEST['e']=='Удалить') and isset($_REQUEST['id']) and $cmf->D)
{

foreach ($_REQUEST['id'] as $id)
 {
$cmf->execute('delete from CLIENT_VOPROS where CLIENT_VOPROS_ID=?',$id);

 }

}



if($_REQUEST['e'] == 'Добавить')
{

      $_REQUEST['id']=$cmf->GetSequence('CLIENT_VOPROS');
      $cmf->execute("delete from CLIENT_VOPROS_CLIENTS where CLIENT_VOPROS_ID=?",$_REQUEST['id']);

      $users = array();
      if(!empty($_REQUEST['USERS']) && is_array($_REQUEST['USERS']))
      {
         $users = $_REQUEST['USERS'];
      }
      else{
         $sth = $cmf->execute("select CLIENT_ID from CLIENT where STATUS=1");
         while($row = mysql_fetch_array($sth)){
            $users[] = $row['CLIENT_ID'];
         }
      }
      $_REQUEST['USERS'] = '';
      






$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into CLIENT_VOPROS (CLIENT_VOPROS_ID,DATA_START,DATA_STOP,NAME,STATUS,USERS) values (null,?,?,?,?,?)',stripslashes($_REQUEST['DATA_START']),stripslashes($_REQUEST['DATA_STOP']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['USERS'])+0);
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

      foreach($users as $value){
        $sql="select EMAIL
              from CLIENT
              where CLIENT_ID = {$value}";
      
        $V_EMAIL = $cmf->selectrow_array($sql);
        
        $V_CLIENT_HASH = $last_conf = substr(md5($value.$V_EMAIL.$_REQUEST['id']),0,24);
        
        $sql="insert into CLIENT_VOPROS_CLIENTS 
              set CLIENT_ID={$value}
                , CLIENT_VOPROS_ID={$_REQUEST['id']}
                , CLIENT_HASH='{$V_CLIENT_HASH}'
                , STATUS = 1";
                
        $cmf->execute($sql);
      }

    
}

if($_REQUEST['e'] == 'Изменить')
{

      $cmf->execute("delete from CLIENT_VOPROS_CLIENTS where CLIENT_VOPROS_ID=?",$_REQUEST['id']);

      $users = array();
      if(!empty($_REQUEST['USERS']) && is_array($_REQUEST['USERS']))
      {
         $users = $_REQUEST['USERS'];
      }
      else
      {
         $sth = $cmf->execute("select CLIENT_ID from CLIENT where STATUS=1");
         while($row = mysql_fetch_array($sth))
         {
            $users[] = $row['CLIENT_ID'];
         }
      }
      $_REQUEST['USERS'] = '';
      




$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update CLIENT_VOPROS set DATA_START=?,DATA_STOP=?,NAME=?,STATUS=?,USERS=? where CLIENT_VOPROS_ID=?',stripslashes($_REQUEST['DATA_START']),stripslashes($_REQUEST['DATA_STOP']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['USERS'])+0,$_REQUEST['id']);
$_REQUEST['e']='ED';

      foreach($users as $value){
        $sql="select EMAIL
              from CLIENT
              where CLIENT_ID = {$value}";
      
        $V_EMAIL = $cmf->selectrow_array($sql);
        
        $V_CLIENT_HASH = $last_conf = substr(md5($value.$V_EMAIL.$_REQUEST['id']),0,24);
        
        $sql="insert into CLIENT_VOPROS_CLIENTS 
              set CLIENT_ID={$value}
                , CLIENT_VOPROS_ID={$_REQUEST['id']}
                , CLIENT_HASH='{$V_CLIENT_HASH}'
                , STATUS = 1";
                
        $cmf->execute($sql);
      }
  
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_CLIENT_VOPROS_ID,$V_DATA_START,$V_DATA_STOP,$V_NAME,$V_STATUS,$V_USERS)=
$cmf->selectrow_arrayQ('select CLIENT_VOPROS_ID,DATE_FORMAT(DATA_START,"%Y-%m-%d %H:%i"),DATE_FORMAT(DATA_STOP,"%Y-%m-%d %H:%i"),NAME,STATUS,USERS from CLIENT_VOPROS where CLIENT_VOPROS_ID=?',$_REQUEST['id']);

      $users = array();
      $sth = $cmf->execute("select CLIENT_ID from CLIENT_VOPROS_CLIENTS where CLIENT_VOPROS_ID=?",$_REQUEST['id']);
      while($row = mysql_fetch_array($sth))
      {
         $users[] = $row['CLIENT_ID'];
      }
      $V_USERS = $users;
    


$V_STATUS=$V_STATUS?'checked':'';
        $V_STR_USERS=$cmf->Spravotchnik($V_USERS,'select CLIENT_ID,NAME from CLIENT    order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Опрос</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT_VOPROS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата начала:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA_START" name="DATA_START" value="$V_DATA_START" />
EOF;

if($V_DATA_START) $V_DAT_ = substr($V_DATA_START,8,2).".".substr($V_DATA_START,5,2).".".substr($V_DATA_START,0,4)." ".substr($V_DATA_START,11,2).":".substr($V_DATA_START,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA_START">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA_START" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA_START",
                       displayArea    :    "DATE_DATA_START",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA_START",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Дата Окончания:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA_STOP" name="DATA_STOP" value="$V_DATA_STOP" />
EOF;

if($V_DATA_STOP) $V_DAT_ = substr($V_DATA_STOP,8,2).".".substr($V_DATA_STOP,5,2).".".substr($V_DATA_STOP,0,4)." ".substr($V_DATA_STOP,11,2).":".substr($V_DATA_STOP,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA_STOP">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA_STOP" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA_STOP",
                       displayArea    :    "DATE_DATA_STOP",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA_STOP",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="NAME" rows="7" cols="90">$V_NAME</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Пользователи:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="USERS[]" multiple="1" size="10"><option value="0"></option>$V_STR_USERS</select><br />
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
list($V_CLIENT_VOPROS_ID,$V_DATA_START,$V_DATA_STOP,$V_NAME,$V_STATUS,$V_USERS)=array('','','','','','');

$V_DATA_START=$cmf->selectrow_array('select now()');
$V_DATA_STOP=$cmf->selectrow_array('select now()');
$V_STATUS='';
$V_STR_USERS=$cmf->Spravotchnik($V_USERS,'select CLIENT_ID,NAME from CLIENT    order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Опрос</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CLIENT_VOPROS.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Дата начала:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA_START" name="DATA_START" value="$V_DATA_START" />
EOF;

if($V_DATA_START) $V_DAT_ = substr($V_DATA_START,8,2).".".substr($V_DATA_START,5,2).".".substr($V_DATA_START,0,4)." ".substr($V_DATA_START,11,2).":".substr($V_DATA_START,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA_START">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA_START" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA_START",
                       displayArea    :    "DATE_DATA_START",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA_START",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Дата Окончания:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="hidden" id="DATA_STOP" name="DATA_STOP" value="$V_DATA_STOP" />
EOF;

if($V_DATA_STOP) $V_DAT_ = substr($V_DATA_STOP,8,2).".".substr($V_DATA_STOP,5,2).".".substr($V_DATA_STOP,0,4)." ".substr($V_DATA_STOP,11,2).":".substr($V_DATA_STOP,14,2);
else $V_DAT_ = '';


        
        @print <<<EOF
        <table>
        <tr><td><div id="DATE_DATA_STOP">$V_DAT_</div></td>
        <td><img src="img/img.gif" id="f_trigger_DATA_STOP" style="cursor: pointer; border: 1px solid red;" title="Show calendar" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        </td>
        </tr>
        </table>

        
        
        <script type="text/javascript">
        Calendar.setup({
                       inputField     :    "DATA_STOP",
                       displayArea    :    "DATE_DATA_STOP",
                       ifFormat       :    "%Y-%m-%d %H:%M",
                       daFormat       :    "%d.%m.%Y %H:%M",
                       showsTime      :    "true",
                       timeFormat     :    "24",
                       button         :    "f_trigger_DATA_STOP",
                       align          :    "Tl",
                       singleClick    :    false
                       });
        </script>
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="NAME" rows="7" cols="90">$V_NAME</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Пользователи:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="USERS[]" multiple="1" size="10"><option value="0"></option>$V_STR_USERS</select><br />
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


print '<h2 class="h2">Опрос</h2><form action="CLIENT_VOPROS.php" method="POST">';




$sth=$cmf->execute('select A.CLIENT_VOPROS_ID,DATE_FORMAT(A.DATA_START,"%Y-%m-%d %H:%i"),DATE_FORMAT(A.DATA_STOP,"%Y-%m-%d %H:%i"),A.NAME,A.STATUS from CLIENT_VOPROS A where 1'.' order by A.DATA_START desc ');





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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Дата начала</th><th>Дата Окончания</th><th>Текст вопроса</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_CLIENT_VOPROS_ID,$V_DATA_START,$V_DATA_STOP,$V_NAME,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_CLIENT_VOPROS_ID" /></td>
<td>$V_CLIENT_VOPROS_ID</td><td>$V_DATA_START</td><td>$V_DATA_STOP</td><td><a href="CLIENT_VOPROS_CLIENTS.php?pid=$V_CLIENT_VOPROS_ID" class="b">$V_NAME</a></td><td nowrap="">

EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="CLIENT_VOPROS.php?e=ED&amp;id=$V_CLIENT_VOPROS_ID"><img src="i/ed.gif" border="0" title="Изменить" /></a>


<a href="CLIENT_OTVETS.php?pid=$V_CLIENT_VOPROS_ID"><img src="img/fold0.gif" border="0" title="Ответы" /></a></td></tr>
EOF;
}
}
 
print '</table>';
}
print '</form>';
$cmf->MakeCommonFooter();
$cmf->Close();

?>
