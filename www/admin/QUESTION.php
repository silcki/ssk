<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('QUESTION');
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




if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING from QUESTION_LANGS where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$id);
$cmf->execute('update QUESTION_LANGS set ORDERING=ORDERING-1 where QUESTION_ID=? and ORDERING>?',$_REQUEST['id'],$ORDERING);
$cmf->execute('delete from QUESTION_LANGS where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}


if($cmf->Param('e1') == 'UP')
{
$ORDERING=$cmf->selectrow_array('select ORDERING from QUESTION_LANGS where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
if($ORDERING>1)
{
$cmf->execute('update QUESTION_LANGS set ORDERING=ORDERING+1 where QUESTION_ID=? and ORDERING=?',$_REQUEST['id'],$ORDERING-1);
$cmf->execute('update QUESTION_LANGS set ORDERING=ORDERING-1 where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}

if($cmf->Param('e1') == 'DN')
{
$ORDERING=$cmf->selectrow_array('select ORDERING from QUESTION_LANGS where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from QUESTION_LANGS');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update QUESTION_LANGS set ORDERING=ORDERING-1 where QUESTION_ID=? and ORDERING=?',$_REQUEST['id'],$ORDERING+1);
$cmf->execute('update QUESTION_LANGS set ORDERING=ORDERING+1 where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);
}
$_REQUEST['e']='ED';
}



if($cmf->Param('e1') == 'Изменить')
{







$cmf->execute('update QUESTION_LANGS set CMF_LANG_ID=?,QUESTION=?,ANSWER=? where QUESTION_ID=? and QUESTION_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['QUESTION']),stripslashes($_REQUEST['ANSWER']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{

$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from QUESTION_LANGS where QUESTION_ID=?',$_REQUEST['id']);
$_REQUEST['ORDERING']++;


$_REQUEST['iid']=$cmf->GetSequence('QUESTION_LANGS');









$cmf->execute('insert into QUESTION_LANGS (QUESTION_ID,QUESTION_LANGS_ID,CMF_LANG_ID,QUESTION,ANSWER,ORDERING) values (?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['QUESTION']),stripslashes($_REQUEST['ANSWER']),stripslashes($_REQUEST['ORDERING']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_QUESTION_LANGS_ID,$V_CMF_LANG_ID,$V_QUESTION,$V_ANSWER)=$cmf->selectrow_arrayQ('select QUESTION_LANGS_ID,CMF_LANG_ID,QUESTION,ANSWER from QUESTION_LANGS where QUESTION_ID=? and QUESTION_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="QUESTION.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(QUESTION) &amp;&amp; checkXML(ANSWER);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="3" />

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="QUESTION" rows="7" cols="90">$V_QUESTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="ANSWER" name="ANSWER" rows="7" cols="90">
EOF;
$V_ANSWER = htmlspecialchars_decode($V_ANSWER);
echo $V_ANSWER;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'ANSWER', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

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
list($V_QUESTION_LANGS_ID,$V_CMF_LANG_ID,$V_QUESTION,$V_ANSWER,$V_ORDERING)=array('','','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="QUESTION.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(QUESTION) &amp;&amp; checkXML(ANSWER);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="QUESTION" rows="7" cols="90">$V_QUESTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="ANSWER" name="ANSWER" rows="7" cols="90">
EOF;
$V_ANSWER = htmlspecialchars_decode($V_ANSWER);
echo $V_ANSWER;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'ANSWER', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

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
list($ORDERING)=$cmf->selectrow_array('select ORDERING from QUESTION where QUESTION_ID=?',$id);
$cmf->execute('update QUESTION set ORDERING=ORDERING-1 where ORDERING>?',$ORDERING);
$cmf->execute('delete from QUESTION where QUESTION_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from QUESTION where QUESTION_ID=?',$_REQUEST['id']);
if($ORDERING>1)
{
$cmf->execute('update QUESTION set ORDERING=ORDERING+1 where ORDERING=?',$ORDERING-1);
$cmf->execute('update QUESTION set ORDERING=ORDERING-1 where QUESTION_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($ORDERING)=$cmf->selectrow_array('select ORDERING from QUESTION where QUESTION_ID=?',$_REQUEST['id']);
$MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from QUESTION');
if($ORDERING<$MAXORDERING)
{
$cmf->execute('update QUESTION set ORDERING=ORDERING-1 where ORDERING=?',$ORDERING+1);
$cmf->execute('update QUESTION set ORDERING=ORDERING+1 where QUESTION_ID=?',$_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{


$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from QUESTION');
$_REQUEST['ORDERING']++;






$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into QUESTION (QUESTION_ID,QUESTION_GROUP_ID,QUESTION,ANSWER,STATUS,ORDERING) values (null,?,?,?,?,?)',stripslashes($_REQUEST['QUESTION_GROUP_ID'])+0,stripslashes($_REQUEST['QUESTION']),stripslashes($_REQUEST['ANSWER']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));
$_REQUEST['id']=mysql_insert_id($cmf->dbh);

$_REQUEST['e']='ED';

}

if($_REQUEST['e'] == 'Изменить')
{





$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('update QUESTION set QUESTION_GROUP_ID=?,QUESTION=?,ANSWER=?,STATUS=? where QUESTION_ID=?',stripslashes($_REQUEST['QUESTION_GROUP_ID'])+0,stripslashes($_REQUEST['QUESTION']),stripslashes($_REQUEST['ANSWER']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
$_REQUEST['e']='ED';

};

if($_REQUEST['e'] == 'ED')
{
list($V_QUESTION_ID,$V_QUESTION_GROUP_ID,$V_QUESTION,$V_ANSWER,$V_STATUS)=
$cmf->selectrow_arrayQ('select QUESTION_ID,QUESTION_GROUP_ID,QUESTION,ANSWER,STATUS from QUESTION where QUESTION_ID=?',$_REQUEST['id']);



        $V_STR_QUESTION_GROUP_ID=$cmf->Spravotchnik($V_QUESTION_GROUP_ID,'select QUESTION_GROUP_ID,NAME from QUESTION_GROUP  order by NAME');
        
        
$V_STATUS=$V_STATUS?'checked':'';
@print <<<EOF
<h2 class="h2">Редактирование - Вопрос-ответ</h2>


<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="QUESTION.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(QUESTION) &amp;&amp; checkXML(ANSWER);">
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Группы Вопрос-ответ:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="QUESTION_GROUP_ID">$V_STR_QUESTION_GROUP_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="QUESTION" rows="7" cols="90">$V_QUESTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="ANSWER" name="ANSWER" rows="7" cols="90">
EOF;
$V_ANSWER = htmlspecialchars_decode($V_ANSWER);
echo $V_ANSWER;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'ANSWER', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
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




print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="QUESTION.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />

<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select QUESTION_LANGS_ID,CMF_LANG_ID,QUESTION from QUESTION_LANGS where QUESTION_ID=?  order by ORDERING',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Текст вопроса</th><td></td></tr>
EOF;
while(list($V_QUESTION_LANGS_ID,$V_CMF_LANG_ID,$V_QUESTION)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_QUESTION_LANGS_ID" /></td>
<td>$V_QUESTION_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_QUESTION</td><td nowrap="">
<a href="QUESTION.php?e1=UP&amp;iid=$V_QUESTION_LANGS_ID&amp;id={$_REQUEST['id']}#f1"><img src="i/up.gif" border="0" /></a>
<a href="QUESTION.php?e1=DN&amp;iid=$V_QUESTION_LANGS_ID&amp;id={$_REQUEST['id']}#f1"><img src="i/dn.gif" border="0" /></a>
<a href="QUESTION.php?e1=ED&amp;iid=$V_QUESTION_LANGS_ID&amp;id={$_REQUEST['id']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}


if($_REQUEST['e'] == 'Новый')
{
list($V_QUESTION_ID,$V_QUESTION_GROUP_ID,$V_QUESTION,$V_ANSWER,$V_STATUS,$V_ORDERING)=array('','','','','','');

$V_STR_QUESTION_GROUP_ID=$cmf->Spravotchnik($V_QUESTION_GROUP_ID,'select QUESTION_GROUP_ID,NAME from QUESTION_GROUP  order by NAME');     
$V_STATUS='';
@print <<<EOF
<h2 class="h2">Добавление - Вопрос-ответ</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="QUESTION.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(QUESTION) &amp;&amp; checkXML(ANSWER);">
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>

<tr bgcolor="#FFFFFF"><th width="1%"><b>Группы Вопрос-ответ:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="QUESTION_GROUP_ID">$V_STR_QUESTION_GROUP_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст вопроса:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="QUESTION" rows="7" cols="90">$V_QUESTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Текст ответа:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="ANSWER" name="ANSWER" rows="7" cols="90">
EOF;
$V_ANSWER = htmlspecialchars_decode($V_ANSWER);
echo $V_ANSWER;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'ANSWER', {
      customConfig : 'ckeditor/config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
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


print '<h2 class="h2">Вопрос-ответ</h2><form action="QUESTION.php" method="POST">';



$pagesize=20;
if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}
if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{

$_REQUEST['count']=$cmf->selectrow_array('select count(*) from QUESTION A where 1');

$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 for($i=1;$i<=$_REQUEST['pcount'];$i++)
 {
  if($i==$_REQUEST['p']) { print '- <b class="red">'.$i.'</b>'; } else { print <<<EOF
- <a class="t" href="QUESTION.php?count={$_REQUEST['count']}&amp;p=$i&amp;pcount={$_REQUEST['pcount']}&amp;s={$_REQUEST['s']}{$filtpath}">$i</a>
EOF;
}
 }
 print'<br />';
}


$sth=$cmf->execute('select A.QUESTION_ID,A.QUESTION_GROUP_ID,A.QUESTION,A.STATUS from QUESTION A where 1'.' order by A.ORDERING limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);





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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Группы Вопрос-ответ</th><th>Текст вопроса</th><td></td></tr>
 
EOF;

if(is_resource($sth))
while(list($V_QUESTION_ID,$V_QUESTION_GROUP_ID,$V_QUESTION,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_QUESTION_GROUP_ID=$cmf->selectrow_arrayQ('select NAME from QUESTION_GROUP where QUESTION_GROUP_ID=?',$V_QUESTION_GROUP_ID);
                                        
if($V_STATUS){$V_STATUS='#FFFFFF';} else {$V_STATUS='#a0a0a0';}

print <<<EOF
<tr bgcolor="$V_STATUS">
<td><input type="checkbox" name="id[]" value="$V_QUESTION_ID" /></td>
<td>$V_QUESTION_ID</td><td>$V_QUESTION_GROUP_ID</td><td>$V_QUESTION</td><td nowrap="">
<a href="QUESTION.php?e=UP&amp;id=$V_QUESTION_ID"><img src="i/up.gif" border="0" /></a>
<a href="QUESTION.php?e=DN&amp;id=$V_QUESTION_ID"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
{

@print <<<EOF
<a href="QUESTION.php?e=ED&amp;id=$V_QUESTION_ID&amp;p={$_REQUEST['p']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>


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
