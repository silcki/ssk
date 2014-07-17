<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('CATALOGUE');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();

$visible=1;
if(!isset($_REQUEST['r']))$_REQUEST['r']=0;
if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['event']))$_REQUEST['event']='';
if(!isset($_REQUEST['id']))$_REQUEST['id']='';
if(!isset($_REQUEST['pid']))$_REQUEST['pid']=0;
if(!isset($_REQUEST['f']))$_REQUEST['f']='';
$VIRTUAL_IMAGE_PATH="/cat/";


$cmf->ENUM_COLOR_STYLE=array('Красный','Синий','Зеленый','Серый','Желтый','Фиолетовый','Желтый 2');





 
if(!isset($V_PARENT_ID)) $V_PARENT_ID=0;

if($_REQUEST['e'] == 'Перестроить'){
$cmf->CheckCount(0);
}
    


if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from CATALOGUE_LANGS where CATALOGUE_ID=? and CATALOGUE_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{










$cmf->execute('update CATALOGUE_LANGS set CMF_LANG_ID=?,TYPENAME=?,NAME=?,TITLE=?,DESCRIPTION=?,HTML_KEYWORDS=?,HTML_DESCRIPTION=? where CATALOGUE_ID=? and CATALOGUE_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['TYPENAME']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('CATALOGUE_LANGS');












$cmf->execute('insert into CATALOGUE_LANGS (CATALOGUE_ID,CATALOGUE_LANGS_ID,CMF_LANG_ID,TYPENAME,NAME,TITLE,DESCRIPTION,HTML_KEYWORDS,HTML_DESCRIPTION) values (?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['TYPENAME']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_CATALOGUE_LANGS_ID,$V_CMF_LANG_ID,$V_TYPENAME,$V_NAME,$V_TITLE,$V_DESCRIPTION,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION)=$cmf->selectrow_arrayQ('select CATALOGUE_LANGS_ID,CMF_LANG_ID,TYPENAME,NAME,TITLE,DESCRIPTION,HTML_KEYWORDS,HTML_DESCRIPTION from CATALOGUE_LANGS where CATALOGUE_ID=? and CATALOGUE_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CATALOGUE.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(TYPENAME) &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="2" />

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Префикс для типа товара:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TYPENAME" value="$V_TYPENAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название пункта меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>TITLE:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


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
list($V_CATALOGUE_LANGS_ID,$V_CMF_LANG_ID,$V_TYPENAME,$V_NAME,$V_TITLE,$V_DESCRIPTION,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION)=array('','','','','','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CATALOGUE.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(TYPENAME) &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e1" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e1" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Язык:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="CMF_LANG_ID">$V_STR_CMF_LANG_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Префикс для типа товара:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TYPENAME" value="$V_TYPENAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название пункта меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>TITLE:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="7" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


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

if(!isset($_REQUEST['e2']))$_REQUEST['e2']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e2') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from CATALOGUE_ARTICLE_GROUP where CATALOGUE_ID=? and CATALOGUE_ARTICLE_GROUP_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e2') == 'Изменить')
{




$cmf->execute('update CATALOGUE_ARTICLE_GROUP set ARTICLE_GROUP_ID=? where CATALOGUE_ID=? and CATALOGUE_ARTICLE_GROUP_ID=?',stripslashes($_REQUEST['ARTICLE_GROUP_ID'])+0,$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e2') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('CATALOGUE_ARTICLE_GROUP');






$cmf->execute('insert into CATALOGUE_ARTICLE_GROUP (CATALOGUE_ID,CATALOGUE_ARTICLE_GROUP_ID,ARTICLE_GROUP_ID) values (?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['ARTICLE_GROUP_ID'])+0);
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e2') == 'ED')
{
list ($V_CATALOGUE_ARTICLE_GROUP_ID,$V_ARTICLE_GROUP_ID)=$cmf->selectrow_arrayQ('select CATALOGUE_ARTICLE_GROUP_ID,ARTICLE_GROUP_ID from CATALOGUE_ARTICLE_GROUP where CATALOGUE_ID=? and CATALOGUE_ARTICLE_GROUP_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_ARTICLE_GROUP_ID=$cmf->Spravotchnik($V_ARTICLE_GROUP_ID,'select ARTICLE_GROUP_ID,NAME from ARTICLE_GROUP  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Связь "Каталог - Группа статей"</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CATALOGUE.php#f2" ENCTYPE="multipart/form-data" onsubmit="return true ;">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />


<input type="hidden" name="p" value="{$_REQUEST['p']}" />

EOF;
if(!empty($V_CMF_LANG_ID)) print '<input type="hidden" name="CMF_LANG_ID" value="'.$V_CMF_LANG_ID.'" />';

@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Группа статей:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ARTICLE_GROUP_ID">$V_STR_ARTICLE_GROUP_ID</select><br />
</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;





$visible=0;
}

if($cmf->Param('e2') == 'Новый')
{
list($V_CATALOGUE_ARTICLE_GROUP_ID,$V_ARTICLE_GROUP_ID)=array('','');


$V_STR_ARTICLE_GROUP_ID=$cmf->Spravotchnik($V_ARTICLE_GROUP_ID,'select ARTICLE_GROUP_ID,NAME from ARTICLE_GROUP  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Связь "Каталог - Группа статей"</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="CATALOGUE.php#f2" ENCTYPE="multipart/form-data" onsubmit="return true ;">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
<tr bgcolor="#FFFFFF"><th width="1%"><b>Группа статей:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">
<select name="ARTICLE_GROUP_ID">$V_STR_ARTICLE_GROUP_ID</select><br />
</td></tr>
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e2" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e2" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}


if($_REQUEST['e'] == 'DL')
{
DelTree($cmf,$_REQUEST['id']);
$cmf->execute('delete from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['id']);
}

if($_REQUEST['e'] == 'VS')
{
$STATUS=$cmf->selectrow_array('select STATUS from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['id']);
$STATUS=1-$STATUS;
$cmf->execute('update CATALOGUE set STATUS=? where CATALOGUE_ID=?',$STATUS,$_REQUEST['id']);
if($STATUS)
{
$cmf->execute('update CATALOGUE set REALSTATUS=1 where CATALOGUE_ID=?',$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],1);
}
else
{
$REALSTATUS=GetMyRealStatus($cmf,$_REQUEST['id']);
$cmf->execute('update CATALOGUE set REALSTATUS=? where CATALOGUE_ID=?',$REALSTATUS,$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],$REALSTATUS);
}
}

if($_REQUEST['e'] == 'UP')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDERING from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{
$cmf->execute('update CATALOGUE set ORDERING=ORDERING+1 where ORDERING=? and PARENT_ID=?',$V_ORDERING-1,$V_PARENT_ID);
$cmf->execute('update CATALOGUE set ORDERING=ORDERING-1 where CATALOGUE_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDERING from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['id']);
list($V_MAXORDERING)=$cmf->selectrow_array('select max(ORDERING) from CATALOGUE where PARENT_ID=?',$V_PARENT_ID);
if($V_ORDERING < $V_MAXORDERING)
{
$cmf->execute('update CATALOGUE set ORDERING=ORDERING-1 where ORDERING=? and PARENT_ID=?',$V_ORDERING+1,$V_PARENT_ID);
$cmf->execute('update CATALOGUE set ORDERING=ORDERING+1 where CATALOGUE_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['event'] == 'Добавить')
{

if(!empty($_REQUEST['pid']))
{
  $_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from CATALOGUE where PARENT_ID=?',$_REQUEST['pid']);
  $_REQUEST['ORDERING']++;
  $_REQUEST['id']=$cmf->GetSequence('CATALOGUE');
  










		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img1',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img2',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['TO_PARENT']=isset($_REQUEST['TO_PARENT']) && $_REQUEST['TO_PARENT']?1:0;
$_REQUEST['ITEM_IS_DESCR']=isset($_REQUEST['ITEM_IS_DESCR']) && $_REQUEST['ITEM_IS_DESCR']?1:0;
$_REQUEST['IN_MENU']=isset($_REQUEST['IN_MENU']) && $_REQUEST['IN_MENU']?1:0;


$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $cmf->execute('insert into CATALOGUE (CATALOGUE_ID,PARENT_ID,NAME,CATNAME,REALCATNAME,URL,SPECIAL_URL,TITLE,DESCRIPTION,COLOR_STYLE,IMAGE1,IMAGE2,COUNT_,STATUS,STATUS_MAIN,TO_PARENT,ITEM_IS_DESCR,IN_MENU,HTML_KEYWORDS,HTML_DESCRIPTION,REALSTATUS,ORDERING) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['CATNAME']),'',stripslashes($_REQUEST['URL']),'',stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['COLOR_STYLE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),0,stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['TO_PARENT']),stripslashes($_REQUEST['ITEM_IS_DESCR']),stripslashes($_REQUEST['IN_MENU']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDERING']));
  
  
      if(empty($_REQUEST['CATNAME'])){
        $dbRules = $cmf->select("select * from TRANSLIT_RULE");
        $rules = array();
        if(!empty($dbRules)){
          foreach ($dbRules as $rule){
            $rules[$rule['SRC']] = $rule['TRANSLIT'];
          }
        }
        
        $_REQUEST['NAME'] = trim(mb_strtolower($_REQUEST['NAME'],'utf-8'));
        $_REQUEST['NAME'] = preg_replace("/\s+/s", "-", $_REQUEST['NAME']);
        
        $_REQUEST['CATNAME'] = strtr($_REQUEST['NAME'], $rules);
        
        $cmf->execute('update CATALOGUE set CATNAME=? where CATALOGUE_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }
  
      $cmf->execute('update CATALOGUE set REALSTATUS=?,REALCATNAME=? where CATALOGUE_ID=?',GetMyRealStatus($cmf,$_REQUEST['id']),GetPath($cmf,$_REQUEST['id']),$_REQUEST['id']);
      $cmf->CheckCount(0);
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUCatalogue($_REQUEST['id']);

    
}
else
{
  $_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from CATALOGUE where PARENT_ID=?',0);
  $_REQUEST['ORDERING']++;
  $_REQUEST['id']=$cmf->GetSequence('CATALOGUE');
  










		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img1',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img2',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['TO_PARENT']=isset($_REQUEST['TO_PARENT']) && $_REQUEST['TO_PARENT']?1:0;
$_REQUEST['ITEM_IS_DESCR']=isset($_REQUEST['ITEM_IS_DESCR']) && $_REQUEST['ITEM_IS_DESCR']?1:0;
$_REQUEST['IN_MENU']=isset($_REQUEST['IN_MENU']) && $_REQUEST['IN_MENU']?1:0;


$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $_REQUEST['pid'] = (!empty($_REQUEST['PARENT_ID'])) ? $_REQUEST['PARENT_ID'] : 0;
  $cmf->execute('insert into CATALOGUE (CATALOGUE_ID,PARENT_ID,NAME,CATNAME,REALCATNAME,URL,SPECIAL_URL,TITLE,DESCRIPTION,COLOR_STYLE,IMAGE1,IMAGE2,COUNT_,STATUS,STATUS_MAIN,TO_PARENT,ITEM_IS_DESCR,IN_MENU,HTML_KEYWORDS,HTML_DESCRIPTION,REALSTATUS,ORDERING) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['CATNAME']),'',stripslashes($_REQUEST['URL']),'',stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['COLOR_STYLE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),0,stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['TO_PARENT']),stripslashes($_REQUEST['ITEM_IS_DESCR']),stripslashes($_REQUEST['IN_MENU']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDERING']));
  
  
      if(empty($_REQUEST['CATNAME'])){
        $dbRules = $cmf->select("select * from TRANSLIT_RULE");
        $rules = array();
        if(!empty($dbRules)){
          foreach ($dbRules as $rule){
            $rules[$rule['SRC']] = $rule['TRANSLIT'];
          }
        }
        
        $_REQUEST['NAME'] = trim(mb_strtolower($_REQUEST['NAME'],'utf-8'));
        $_REQUEST['NAME'] = preg_replace("/\s+/s", "-", $_REQUEST['NAME']);
        
        $_REQUEST['CATNAME'] = strtr($_REQUEST['NAME'], $rules);
        
        $cmf->execute('update CATALOGUE set CATNAME=? where CATALOGUE_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }
  
      $cmf->execute('update CATALOGUE set REALSTATUS=?,REALCATNAME=? where CATALOGUE_ID=?',GetMyRealStatus($cmf,$_REQUEST['id']),GetPath($cmf,$_REQUEST['id']),$_REQUEST['id']);
      $cmf->CheckCount(0);
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUCatalogue($_REQUEST['id']);

    

}
$_REQUEST['e']='ED';
}

if($_REQUEST['event'] == 'Изменить')
{











		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img1',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_img1',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_img2',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_img2',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	

$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['STATUS_MAIN']=isset($_REQUEST['STATUS_MAIN']) && $_REQUEST['STATUS_MAIN']?1:0;
$_REQUEST['TO_PARENT']=isset($_REQUEST['TO_PARENT']) && $_REQUEST['TO_PARENT']?1:0;
$_REQUEST['ITEM_IS_DESCR']=isset($_REQUEST['ITEM_IS_DESCR']) && $_REQUEST['ITEM_IS_DESCR']?1:0;
$_REQUEST['IN_MENU']=isset($_REQUEST['IN_MENU']) && $_REQUEST['IN_MENU']?1:0;


$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


@$cmf->execute('update CATALOGUE set PARENT_ID=?,NAME=?,CATNAME=?,URL=?,TITLE=?,DESCRIPTION=?,COLOR_STYLE=?,IMAGE1=?,IMAGE2=?,STATUS_MAIN=?,TO_PARENT=?,ITEM_IS_DESCR=?,IN_MENU=?,HTML_KEYWORDS=?,HTML_DESCRIPTION=? where CATALOGUE_ID=?',stripslashes($_REQUEST['PARENT_ID']),stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['CATNAME']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['COLOR_STYLE']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['STATUS_MAIN']),stripslashes($_REQUEST['TO_PARENT']),stripslashes($_REQUEST['ITEM_IS_DESCR']),stripslashes($_REQUEST['IN_MENU']),stripslashes($_REQUEST['HTML_KEYWORDS']),stripslashes($_REQUEST['HTML_DESCRIPTION']),$_REQUEST['id']);
$_REQUEST['e']='ED';

      if(empty($_REQUEST['CATNAME'])){
        $dbRules = $cmf->select("select * from TRANSLIT_RULE");
        $rules = array();
        if(!empty($dbRules)){
          foreach ($dbRules as $rule){
            $rules[$rule['SRC']] = $rule['TRANSLIT'];
          }
        }
        
        $_REQUEST['NAME'] = trim(mb_strtolower($_REQUEST['NAME'],'utf-8'));
        $_REQUEST['NAME'] = preg_replace("/\s+/s", "-", $_REQUEST['NAME']);
        
        $_REQUEST['CATNAME'] = strtr($_REQUEST['NAME'], $rules);
        
        $cmf->execute('update CATALOGUE set CATNAME=? where CATALOGUE_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }
      UpdatePath($cmf,0,'');
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->applySEFUCatalogue($_REQUEST['id']);
      
      $itemsId = $cmf->select("select ITEM_ID from ITEM where CATALOGUE_ID = ?", $_REQUEST['id']);
      if(!empty($itemsId)){
        foreach ($itemsId as $rule){
            $sefu->applySEFUItem($rule['ITEM_ID']);
        }
      }
      
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_CATALOGUE_ID,$V_PARENT_ID,$V_NAME,$V_CATNAME,$V_REALCATNAME,$V_URL,$V_SPECIAL_URL,$V_TITLE,$V_DESCRIPTION,$V_COLOR_STYLE,$V_IMAGE1,$V_IMAGE2,$V_COUNT_,$V_STATUS,$V_STATUS_MAIN,$V_TO_PARENT,$V_ITEM_IS_DESCR,$V_IN_MENU,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION,$V_REALSTATUS)=$cmf->selectrow_arrayQ('select CATALOGUE_ID,PARENT_ID,NAME,CATNAME,REALCATNAME,URL,SPECIAL_URL,TITLE,DESCRIPTION,COLOR_STYLE,IMAGE1,IMAGE2,COUNT_,STATUS,STATUS_MAIN,TO_PARENT,ITEM_IS_DESCR,IN_MENU,HTML_KEYWORDS,HTML_DESCRIPTION,REALSTATUS from CATALOGUE where CATALOGUE_ID=?',$_REQUEST['id']);




$V_STR_PARENT_ID=$cmf->TreeSpravotchnik($V_PARENT_ID,'select A.CATALOGUE_ID,A.NAME from CATALOGUE A   where A.PARENT_ID=?  order by A.NAME',0);
$V_STR_COLOR_STYLE=$cmf->Enumerator($cmf->ENUM_COLOR_STYLE,$V_COLOR_STYLE);
if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_10[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

if(isset($V_IMAGE2))
{
   $IM_IMAGE2=split('#',$V_IMAGE2);
   if(isset($IM_11[1]) && $IM_IMAGE2[1] > 150){$IM_IMAGE2[2]=$IM_IMAGE2[2]*150/$IM_IMAGE2[1]; $IM_IMAGE2[1]=150;}
}

$V_STATUS=$V_STATUS?'checked':'';
$V_STATUS_MAIN=$V_STATUS_MAIN?'checked':'';
$V_TO_PARENT=$V_TO_PARENT?'checked':'';
$V_ITEM_IS_DESCR=$V_ITEM_IS_DESCR?'checked':'';
$V_IN_MENU=$V_IN_MENU?'checked':'';
$V_REALSTATUS=$V_REALSTATUS?'checked':'';

@print <<<EOF
<h2 class="h2">Редактирование - Каталог</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CATALOGUE.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(CATNAME) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="2" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="event" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Родительский каталог:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<select name="PARENT_ID"><option value="0">Корневой</option>$V_STR_PARENT_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название пункта меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь до каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATNAME" value="$V_CATNAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Добавка к тайтлу:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="DESCRIPTION" name="DESCRIPTION" rows="7" cols="90">
EOF;
$V_DESCRIPTION = htmlspecialchars_decode($V_DESCRIPTION);
echo $V_DESCRIPTION;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'DESCRIPTION', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Стиль:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="COLOR_STYLE">$V_STR_COLOR_STYLE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка рубрики:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE1[0]))
{
if(strchr($IM_IMAGE1[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE1" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка рубрики (вверху):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE2[0]))
{
if(strchr($IM_IMAGE2[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE2[0] = !empty($IM_IMAGE2[0]) ? $IM_IMAGE2[0]:0;
$IM_IMAGE2[1] = !empty($IM_IMAGE2[1]) ? $IM_IMAGE2[1]:0;
$IM_IMAGE2[2] = !empty($IM_IMAGE2[2]) ? $IM_IMAGE2[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]" width="$IM_IMAGE2[1]" height="$IM_IMAGE2[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE2" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE2" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной?:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка родителя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='TO_PARENT' value='1' $V_TO_PARENT/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Товар явл. описанием каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='ITEM_IS_DESCR' value='1' $V_ITEM_IS_DESCR/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>В меню каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IN_MENU' value='1' $V_IN_MENU/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr>


<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="event" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;



print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="CATALOGUE.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select CATALOGUE_LANGS_ID,CMF_LANG_ID,NAME from CATALOGUE_LANGS where CATALOGUE_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Название пункта меню</th><td></td></tr>
EOF;
while(list($V_CATALOGUE_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_CATALOGUE_LANGS_ID" /></td>
<td>$V_CATALOGUE_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="CATALOGUE.php?e1=ED&amp;iid=$V_CATALOGUE_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';



print <<<EOF
<a name="f2"></a><h3 class="h3">Связь "Каталог - Группа статей"</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="CATALOGUE.php#f2" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="4">
<input type="submit" name="e2" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e2" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select CATALOGUE_ARTICLE_GROUP_ID,ARTICLE_GROUP_ID from CATALOGUE_ARTICLE_GROUP where CATALOGUE_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Группа статей</th><td></td></tr>
EOF;
while(list($V_CATALOGUE_ARTICLE_GROUP_ID,$V_ARTICLE_GROUP_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_ARTICLE_GROUP_ID=$cmf->selectrow_arrayQ('select NAME from ARTICLE_GROUP where ARTICLE_GROUP_ID=?',$V_ARTICLE_GROUP_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_CATALOGUE_ARTICLE_GROUP_ID" /></td>
<td>$V_CATALOGUE_ARTICLE_GROUP_ID</td><td>$V_ARTICLE_GROUP_ID</td><td nowrap="">

<a href="CATALOGUE.php?e2=ED&amp;iid=$V_CATALOGUE_ARTICLE_GROUP_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] == 'AD' ||  $_REQUEST['e'] =='Новый')
{
list($V_CATALOGUE_ID,$V_PARENT_ID,$V_NAME,$V_CATNAME,$V_REALCATNAME,$V_URL,$V_SPECIAL_URL,$V_TITLE,$V_DESCRIPTION,$V_COLOR_STYLE,$V_IMAGE1,$V_IMAGE2,$V_COUNT_,$V_STATUS,$V_STATUS_MAIN,$V_TO_PARENT,$V_ITEM_IS_DESCR,$V_IN_MENU,$V_HTML_KEYWORDS,$V_HTML_DESCRIPTION,$V_REALSTATUS,$V_ORDERING)=array('','','','','','','','','','','','','','','','','','','','','','');
if(!empty($_REQUEST['pid'])) $V_PARENT_ID = $_REQUEST['pid'];
else $V_PARENT_ID = 0;



$V_STR_PARENT_ID=$cmf->TreeSpravotchnik($V_PARENT_ID,'select A.CATALOGUE_ID,A.NAME from CATALOGUE A   where A.PARENT_ID=?  order by A.NAME',0);
$V_STR_COLOR_STYLE=$cmf->Enumerator($cmf->ENUM_COLOR_STYLE,-1);
$IM_IMAGE1=array('','','');
$IM_IMAGE2=array('','','');
$V_STATUS='checked';
$V_STATUS_MAIN='';
$V_TO_PARENT='';
$V_ITEM_IS_DESCR='';
$V_IN_MENU='';
$V_REALSTATUS='';

@print <<<EOF
<h2 class="h2">Добавление - Каталог</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="CATALOGUE.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(CATNAME) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(HTML_KEYWORDS) &amp;&amp; checkXML(HTML_DESCRIPTION);">
EOF;
print '<input type="hidden" name="pid" value="'.$_REQUEST['pid'].'" />';
@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Добавить" class="gbt badd" /> 
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Родительский каталог:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<select name="PARENT_ID"><option value="0">Корневой</option>$V_STR_PARENT_ID</select><br />
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название пункта меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь до каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATNAME" value="$V_CATNAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Добавка к тайтлу:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="TITLE" value="$V_TITLE" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Краткий текст:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<textarea id="DESCRIPTION" name="DESCRIPTION" rows="7" cols="90">
EOF;
$V_DESCRIPTION = htmlspecialchars_decode($V_DESCRIPTION);
echo $V_DESCRIPTION;
@print <<<EOF
</textarea>

<script type="text/javascript">
  CKEDITOR.replace( 'DESCRIPTION', {
      customConfig : 'ckeditor/light_config.js',
      filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
      filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
      filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
      filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
      filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
      });
</script>

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Стиль:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><select name="COLOR_STYLE">$V_STR_COLOR_STYLE</select><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка рубрики:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE1[0]))
{
if(strchr($IM_IMAGE1[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE1" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка рубрики (вверху):<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
<table><tr><td>
EOF;
if(!empty($IM_IMAGE2[0]))
{
if(strchr($IM_IMAGE2[0],".swf"))
{
   print "<div style=\"width:600px\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"100%\" align=\"middle\">
                                                 <param name=\"allowScriptAccess\" value=\"sameDomain\" />
                                                 <param name=\"movie\" value=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" />
                                                 <param name=\"quality\" value=\"high\" />
                                                 <embed src=\"/images$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]\" quality=\"high\" width=\"100%\"  align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
                                                 </object></div>";
}
else
{
$IM_IMAGE2[0] = !empty($IM_IMAGE2[0]) ? $IM_IMAGE2[0]:0;
$IM_IMAGE2[1] = !empty($IM_IMAGE2[1]) ? $IM_IMAGE2[1]:0;
$IM_IMAGE2[2] = !empty($IM_IMAGE2[2]) ? $IM_IMAGE2[2]:0;
print <<<EOF
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE2[0]" width="$IM_IMAGE2[1]" height="$IM_IMAGE2[2]" />
EOF;
}
}
print <<<EOF
</td>
<td><input type="file" name="NOT_IMAGE2" size="1" /><br />
<input type="checkbox" name="CLR_IMAGE2" value="1" />Сбросить карт.

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Выводить на главной?:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS_MAIN' value='1' $V_STATUS_MAIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка родителя:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='TO_PARENT' value='1' $V_TO_PARENT/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Товар явл. описанием каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='ITEM_IS_DESCR' value='1' $V_ITEM_IS_DESCR/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>В меню каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IN_MENU' value='1' $V_IN_MENU/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Keywords:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_KEYWORDS" rows="7" cols="90">$V_HTML_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Html Description:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="HTML_DESCRIPTION" rows="7" cols="90">$V_HTML_DESCRIPTION</textarea><br />


</td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Добавить" class="gbt badd" /> 
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>
</form>
</table>
EOF;
$visible=0;
}

if($visible)
{
$parhash=array('0'=>'1');
$CATALOGUE_ID=$_REQUEST['id'];
$O_CATALOGUE_ID=$CATALOGUE_ID;
do 
{
  $PARENTID=$cmf->selectrow_array('select PARENT_ID from CATALOGUE where CATALOGUE_ID=?',$CATALOGUE_ID);
  $parhash[$CATALOGUE_ID]=1;
  $CATALOGUE_ID=$PARENTID;
}while(isset($PARENTID));
print <<<EOF
<h2 class="h2">Каталог</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="CATALOGUE.php" method="POST">
<input type="hidden" name="r" value="{$_REQUEST['r']}" />
<tr bgcolor="#F0F0F0"><td colspan="11">
EOF;

if ($cmf->W)
print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" />
EOF;

print <<<EOF
</td></tr>
EOF;
print <<<EOF
<tr bgcolor="#FFFFFF"><th>N</th><th>Название пункта меню</th><th>Путь</th><th>Спец URL</th><th>Стиль</th><th>Выводить на главной?</th><th>Картинка родителя</th><th>Товар явл. описанием каталога</th><th>В меню каталога</th><form action="ITEM.php" method="POST"><th>

</th></form></tr><tr bgcolor="#F0F0F0"><td>-</td><td colspan="7"><a href="?e=Перестроить" class="red">Перестроить</a></td></tr>        
EOF;
print visibleTree($cmf,$_REQUEST['r'],0,$_REQUEST['r'],$parhash);
print '</form></table>';
}

function visibleTree($cmf,$parent,$level,$root,$parhash)
{
$width=$level*15+10;
$ret='';
$sth=$cmf->execute('select CATALOGUE_ID,NAME,REALCATNAME,SPECIAL_URL,COLOR_STYLE,COUNT_,STATUS,STATUS_MAIN,TO_PARENT,ITEM_IS_DESCR,IN_MENU,REALSTATUS from CATALOGUE where PARENT_ID=? order by ORDERING',$parent);
while ( list($V_CATALOGUE_ID,$V_NAME,$V_REALCATNAME,$V_SPECIAL_URL,$V_COLOR_STYLE,$V_COUNT_,$V_STATUS,$V_STATUS_MAIN,$V_TO_PARENT,$V_ITEM_IS_DESCR,$V_IN_MENU,$V_REALSTATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{

$V_COLOR_STYLE=$cmf->ENUM_COLOR_STYLE[$V_COLOR_STYLE];
                        
if(!$V_STATUS_MAIN) {$V_STATUS_MAIN='Нет';} else {$V_STATUS_MAIN='Да';}
                        
if(!$V_TO_PARENT) {$V_TO_PARENT='Нет';} else {$V_TO_PARENT='Да';}
                        
if(!$V_ITEM_IS_DESCR) {$V_ITEM_IS_DESCR='Нет';} else {$V_ITEM_IS_DESCR='Да';}
                        
if(!$V_IN_MENU) {$V_IN_MENU='Нет';} else {$V_IN_MENU='Да';}
                        



  $ICONS=<<<EOF
  
EOF;
  $V_REALSTATUS=$V_REALSTATUS?'b':'d';
  $V_STATUS=$V_STATUS?0:1;
  $CO_=$cmf->selectrow_array('select count(*) from CATALOGUE where PARENT_ID=?',$V_CATALOGUE_ID);
if(!$CO_)
 {

$folder=<<<EOF
<img src="i/f1.gif" class="fld" /><a href="ITEM.php?pid=$V_CATALOGUE_ID" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }
else
 {

$folder=isset($parhash[$V_CATALOGUE_ID])?$folder=<<<EOF
<a href="ITEM.php?pid=$V_CATALOGUE_ID" class="$V_REALSTATUS"><img src="i/f1.gif" class="fld" /></a><a href="CATALOGUE.php?id=$V_CATALOGUE_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF
:
$folder=<<<EOF
<a href="ITEM.php?pid=$V_CATALOGUE_ID" class="$V_REALSTATUS"><img src="i/f0.gif" class="fld" /></a><a href="CATALOGUE.php?id=$V_CATALOGUE_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }

 $V_NAME=<<<EOF
$folder ($V_COUNT_)
EOF;
 
  $ret.=<<<EOF
<tr bgcolor="#ffffff">
<td>$V_CATALOGUE_ID</td><td style="padding-left:{$width}px">$V_NAME</td><td>$V_REALCATNAME</td><td>$V_SPECIAL_URL</td><td>$V_COLOR_STYLE</td><td>$V_STATUS_MAIN</td><td>$V_TO_PARENT</td><td>$V_ITEM_IS_DESCR</td><td>$V_IN_MENU</td><td nowrap="">
EOF;

if ($cmf->W)
$ret.=<<<EOF
<a href="CATALOGUE.php?e=AD&amp;pid=$V_CATALOGUE_ID&amp;r=$root"><img src="i/add.gif" border="0" title="Добавить" hspace="5" /></a>
<a href="CATALOGUE.php?e=UP&amp;id=$V_CATALOGUE_ID&amp;r=$root"><img src="i/up.gif" border="0" title="Вверх" hspace="5" /></a>
<a href="CATALOGUE.php?e=DN&amp;id=$V_CATALOGUE_ID&amp;r=$root"><img src="i/dn.gif" border="0" title="Вниз" hspace="5" /></a>
<a href="CATALOGUE.php?e=ED&amp;id=$V_CATALOGUE_ID&amp;r=$root"><img src="i/ed.gif" border="0" title="Изменить" hspace="5" /></a>
<a href="CATALOGUE.php?e=VS&amp;id=$V_CATALOGUE_ID&amp;o=$V_CATALOGUE_ID"><img src="i/v$V_STATUS.gif" border="0" /></a>&#160;
$ICONS
EOF;
if ($cmf->D)
{
$ret .=<<<EOF
<a href="CATALOGUE.php?e=DL&amp;id=$V_CATALOGUE_ID&amp;r=$root" onclick="return dl();"><img src="i/del.gif" border="0" title="Удалить" hspace="5" /></a>
EOF;
}

  $ret.= '</td></tr>';

  if(isset($parhash[$V_CATALOGUE_ID])){$ret.=visibleTree($cmf,$V_CATALOGUE_ID,$level+1,$root,$parhash);}
}
return $ret;
}

function DelTree($cmf,$id)
{
$sth=$cmf->execute('select CATALOGUE_ID from CATALOGUE where PARENT_ID=?',$id);
while(list($V_CATALOGUE_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
DelTree($cmf,$V_CATALOGUE_ID);
$cmf->execute('delete from CATALOGUE where CATALOGUE_ID=?',$V_CATALOGUE_ID);
#### del items
}
}

function SetTreeRealStatus($cmf,$id,$state)
{
$sth=$cmf->execute('select CATALOGUE_ID,STATUS from CATALOGUE where PARENT_ID=?',$id);
while(list($V_CATALOGUE_ID,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){SetTreeRealStatus($cmf,$V_CATALOGUE_ID,$state);}
if($state) {$cmf->execute('update CATALOGUE set REALSTATUS=STATUS where CATALOGUE_ID=?',$V_CATALOGUE_ID);}
else {$cmf->execute('update CATALOGUE set REALSTATUS=0 where CATALOGUE_ID=?',$V_CATALOGUE_ID);}
}
}

function GetMyRealStatus($cmf,$id)
{
$V_PARENT_ID=$id;
$V_FULLSTATUS=0;
while ($V_PARENT_ID>0)
{
list ($V_PARENT_ID,$V_STATUS)=$cmf->selectrow_array('select PARENT_ID,STATUS from CATALOGUE where CATALOGUE_ID=?',$V_PARENT_ID);
$V_FULLSTATUS+=1-$V_STATUS;
}
if($V_FULLSTATUS){$V_FULLSTATUS=0;} else {$V_FULLSTATUS=1;}
return $V_FULLSTATUS;
}

$cmf->MakeCommonFooter();
$cmf->Close();


function __DelTree($cmf,$id,$parent_id=0)
{
$sth=$cmf->execute('select CATALOGUE_ID from CATALOGUE where PARENT_ID=?',$id);
while(list($V_CATALOGUE_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
__DelTree($cmf,$V_CATALOGUE_ID,$parent_id);
$cmf->execute('delete from CATALOGUE where CATALOGUE_ID=?',$V_CATALOGUE_ID);
$cmf->execute('delete from CATALOGUE_LANGS where CATALOGUE_ID=?',$V_CATALOGUE_ID);
#### del items
$cmf->execute("update ITEM set CATALOGUE_ID=? where CATALOGUE_ID=?",$parent_id,$V_CATALOGUE_ID);
}
}

function GetPath($cmf,$id)
{
list ($PATH,$PARENTID,$NAME)=array('','','');
$i=0;
while(list($PARENTID,$NAME)=$cmf->selectrow_array('select PARENT_ID,CATNAME from CATALOGUE where CATALOGUE_ID=?',$id))
{
$i++;
if($i==1 && $NAME=='') break;
$id=$PARENTID;
if($NAME){ $PATH="/$NAME$PATH"; }
};

if('/' != substr($PATH,-1)){$PATH=$PATH."/";}
$PATH = preg_replace("/(\/){2,}/","/",$PATH);
return $PATH;
}

function UpdatePath($cmf,$id,$path)
{
        $sth=$cmf->execute('select CATALOGUE_ID,CATNAME from CATALOGUE where PARENT_ID=?',$id);
        while(list ($V_CATALOGUE_ID,$V_CATNAME)=mysql_fetch_array($sth, MYSQL_NUM))
        {
                if($V_CATNAME){$V_CATNAME="$path/$V_CATNAME";} else {$V_CATNAME='';};
                $V_CATNAME = preg_replace("/(\/){2,}/","/",$V_CATNAME);
                if(!preg_match("/(\/)$/",$V_CATNAME)) $V_CATNAME .="/";
                $cmf->execute('update CATALOGUE set REALCATNAME=? where CATALOGUE_ID=?',$V_CATNAME,$V_CATALOGUE_ID);
                UpdatePath($cmf,$V_CATALOGUE_ID,$V_CATNAME);
        }
}

function updateOrdering($cmf,$id)
{
   $sth = $cmf->execute("select CATALOGUE_ID from CATALOGUE where PARENT_ID=? order by ORDERING",$id);
   $order=1;
   while($row = mysql_fetch_array($sth))
   {
       $sql = "update CATALOGUE set ORDERING='".$order."' where CATALOGUE_ID = '".$row['CATALOGUE_ID']."'";
       $cmf->execute($sql);
       $order++;
       updateOrdering($cmf,$row['CATALOGUE_ID']);
   }
}

?>
