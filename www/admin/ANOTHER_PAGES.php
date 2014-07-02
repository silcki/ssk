<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('ANOTHER_PAGES');
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
$VIRTUAL_IMAGE_PATH="/pg/";









if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from ANOTHER_PAGES_LANGS where ANOTHER_PAGES_ID=? and ANOTHER_PAGES_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{








$cmf->execute('update ANOTHER_PAGES_LANGS set CMF_LANG_ID=?,NAME=?,TITLE=?,DESCRIPTION=?,KEYWORDS=? where ANOTHER_PAGES_ID=? and ANOTHER_PAGES_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['KEYWORDS']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('ANOTHER_PAGES_LANGS');










$cmf->execute('insert into ANOTHER_PAGES_LANGS (ANOTHER_PAGES_ID,ANOTHER_PAGES_LANGS_ID,CMF_LANG_ID,NAME,TITLE,DESCRIPTION,KEYWORDS) values (?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['KEYWORDS']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_ANOTHER_PAGES_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_TITLE,$V_DESCRIPTION,$V_KEYWORDS)=$cmf->selectrow_arrayQ('select ANOTHER_PAGES_LANGS_ID,CMF_LANG_ID,NAME,TITLE,DESCRIPTION,KEYWORDS from ANOTHER_PAGES_LANGS where ANOTHER_PAGES_ID=? and ANOTHER_PAGES_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ANOTHER_PAGES.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(KEYWORDS);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="iid" value="{$_REQUEST['iid']}" />
<input type="hidden" name="type" value="0" />

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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тайтл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="5" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ключевые слова:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="KEYWORDS" rows="5" cols="90">$V_KEYWORDS</textarea><br />


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
list($V_ANOTHER_PAGES_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_TITLE,$V_DESCRIPTION,$V_KEYWORDS)=array('','','','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="ANOTHER_PAGES.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(KEYWORDS);">
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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тайтл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="5" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ключевые слова:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="KEYWORDS" rows="5" cols="90">$V_KEYWORDS</textarea><br />


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


if($_REQUEST['e'] == 'DL')
{
DelTree($cmf,$_REQUEST['id']);
$cmf->execute('delete from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
}

if($_REQUEST['e'] == 'VS')
{
$STATUS=$cmf->selectrow_array('select STATUS from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
$STATUS=1-$STATUS;
$cmf->execute('update ANOTHER_PAGES set STATUS=? where ANOTHER_PAGES_ID=?',$STATUS,$_REQUEST['id']);
if($STATUS)
{
$cmf->execute('update ANOTHER_PAGES set REALSTATUS=1 where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],1);
}
else
{
$REALSTATUS=GetMyRealStatus($cmf,$_REQUEST['id']);
$cmf->execute('update ANOTHER_PAGES set REALSTATUS=? where ANOTHER_PAGES_ID=?',$REALSTATUS,$_REQUEST['id']);
SetTreeRealStatus($cmf,$_REQUEST['id'],$REALSTATUS);
}
}

if($_REQUEST['e'] == 'UP')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDER_ from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{
$cmf->execute('update ANOTHER_PAGES set ORDER_=ORDER_+1 where ORDER_=? and PARENT_ID=?',$V_ORDERING-1,$V_PARENT_ID);
$cmf->execute('update ANOTHER_PAGES set ORDER_=ORDER_-1 where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['e'] == 'DN')
{
list($V_PARENT_ID,$V_ORDERING) =$cmf->selectrow_array('select PARENT_ID,ORDER_ from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
list($V_MAXORDERING)=$cmf->selectrow_array('select max(ORDER_) from ANOTHER_PAGES where PARENT_ID=?',$V_PARENT_ID);
if($V_ORDERING < $V_MAXORDERING)
{
$cmf->execute('update ANOTHER_PAGES set ORDER_=ORDER_-1 where ORDER_=? and PARENT_ID=?',$V_ORDERING+1,$V_PARENT_ID);
$cmf->execute('update ANOTHER_PAGES set ORDER_=ORDER_+1 where ANOTHER_PAGES_ID=?',$_REQUEST['id']);
}
}

if($_REQUEST['event'] == 'Добавить')
{

if(!empty($_REQUEST['pid']))
{
  $_REQUEST['ORDER_']=$cmf->selectrow_array('select max(ORDER_) from ANOTHER_PAGES where PARENT_ID=?',$_REQUEST['pid']);
  $_REQUEST['ORDER_']++;
  $_REQUEST['id']=$cmf->GetSequence('ANOTHER_PAGES');
  



		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	








$_REQUEST['IS_NODE']=isset($_REQUEST['IS_NODE']) && $_REQUEST['IS_NODE']?1:0;
$_REQUEST['VIA_JS']=isset($_REQUEST['VIA_JS']) && $_REQUEST['VIA_JS']?1:0;
$_REQUEST['IS_NEW_WIN']=isset($_REQUEST['IS_NEW_WIN']) && $_REQUEST['IS_NEW_WIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $cmf->execute('insert into ANOTHER_PAGES (ANOTHER_PAGES_ID,PARENT_ID,NAME,IMAGE1,CATNAME,REALCATNAME,URL,SPECIAL_URL,MENU_WIDTH,TITLE,DESCRIPTION,KEYWORDS,IS_NODE,VIA_JS,IS_NEW_WIN,STATUS,REALSTATUS,ORDER_) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['CATNAME']),'',stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['MENU_WIDTH']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['KEYWORDS']),stripslashes($_REQUEST['IS_NODE']),stripslashes($_REQUEST['VIA_JS']),stripslashes($_REQUEST['IS_NEW_WIN']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDER_']));
  
  
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
        
        $cmf->execute('update ANOTHER_PAGES set CATNAME=? where ANOTHER_PAGES_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }      
      $cmf->execute('update ANOTHER_PAGES set REALSTATUS=?,REALCATNAME=? where ANOTHER_PAGES_ID=?',GetMyRealStatus($cmf,$_REQUEST['id']),GetPath($cmf,$_REQUEST['id']),$_REQUEST['id']);
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->_applySEFUDoc($_REQUEST['id']);
    
}
else
{
  $_REQUEST['ORDER_']=$cmf->selectrow_array('select max(ORDER_) from ANOTHER_PAGES where PARENT_ID=?',0);
  $_REQUEST['ORDER_']++;
  $_REQUEST['id']=$cmf->GetSequence('ANOTHER_PAGES');
  



		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	








$_REQUEST['IS_NODE']=isset($_REQUEST['IS_NODE']) && $_REQUEST['IS_NODE']?1:0;
$_REQUEST['VIA_JS']=isset($_REQUEST['VIA_JS']) && $_REQUEST['VIA_JS']?1:0;
$_REQUEST['IS_NEW_WIN']=isset($_REQUEST['IS_NEW_WIN']) && $_REQUEST['IS_NEW_WIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


  $_REQUEST['pid'] = (!empty($_REQUEST['PARENT_ID'])) ? $_REQUEST['PARENT_ID'] : 0;
  $cmf->execute('insert into ANOTHER_PAGES (ANOTHER_PAGES_ID,PARENT_ID,NAME,IMAGE1,CATNAME,REALCATNAME,URL,SPECIAL_URL,MENU_WIDTH,TITLE,DESCRIPTION,KEYWORDS,IS_NODE,VIA_JS,IS_NEW_WIN,STATUS,REALSTATUS,ORDER_) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['pid']+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['CATNAME']),'',stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['MENU_WIDTH']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['KEYWORDS']),stripslashes($_REQUEST['IS_NODE']),stripslashes($_REQUEST['VIA_JS']),stripslashes($_REQUEST['IS_NEW_WIN']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['REALSTATUS']),stripslashes($_REQUEST['ORDER_']));
  
  
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
        
        $cmf->execute('update ANOTHER_PAGES set CATNAME=? where ANOTHER_PAGES_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }      
      $cmf->execute('update ANOTHER_PAGES set REALSTATUS=?,REALCATNAME=? where ANOTHER_PAGES_ID=?',GetMyRealStatus($cmf,$_REQUEST['id']),GetPath($cmf,$_REQUEST['id']),$_REQUEST['id']);
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->_applySEFUDoc($_REQUEST['id']);
    

}
$_REQUEST['e']='ED';
}

if($_REQUEST['event'] == 'Изменить')
{




		
				
    if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
     		  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'',$VIRTUAL_IMAGE_PATH);
		   }
		   else{
			  $_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		   }
		}
		else{ 
			$_REQUEST['IMAGE1']=$cmf->PicturePost('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH);		   
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	








$_REQUEST['IS_NODE']=isset($_REQUEST['IS_NODE']) && $_REQUEST['IS_NODE']?1:0;
$_REQUEST['VIA_JS']=isset($_REQUEST['VIA_JS']) && $_REQUEST['VIA_JS']?1:0;
$_REQUEST['IS_NEW_WIN']=isset($_REQUEST['IS_NEW_WIN']) && $_REQUEST['IS_NEW_WIN']?1:0;
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;
$_REQUEST['REALSTATUS']=isset($_REQUEST['REALSTATUS']) && $_REQUEST['REALSTATUS']?1:0;


@$cmf->execute('update ANOTHER_PAGES set NAME=?,IMAGE1=?,CATNAME=?,URL=?,SPECIAL_URL=?,MENU_WIDTH=?,TITLE=?,DESCRIPTION=?,KEYWORDS=?,IS_NODE=?,VIA_JS=?,IS_NEW_WIN=? where ANOTHER_PAGES_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['CATNAME']),stripslashes($_REQUEST['URL']),stripslashes($_REQUEST['SPECIAL_URL']),stripslashes($_REQUEST['MENU_WIDTH']),stripslashes($_REQUEST['TITLE']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['KEYWORDS']),stripslashes($_REQUEST['IS_NODE']),stripslashes($_REQUEST['VIA_JS']),stripslashes($_REQUEST['IS_NEW_WIN']),$_REQUEST['id']);
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
        
        $cmf->execute('update ANOTHER_PAGES set CATNAME=? where ANOTHER_PAGES_ID=?',$_REQUEST['CATNAME'] ,$_REQUEST['id']);
        
      }
      UpdatePath($cmf,0,'');
      
      require $_SERVER['DOCUMENT_ROOT']."/lib/CreateSEFU.class.php";
      $sefu = new CreateSEFU();
      $sefu->_applySEFUDoc($_REQUEST['id']);
    
};

if($_REQUEST['e'] == 'ED')
{
list($V_ANOTHER_PAGES_ID,$V_PARENT_ID,$V_NAME,$V_IMAGE1,$V_CATNAME,$V_REALCATNAME,$V_URL,$V_SPECIAL_URL,$V_MENU_WIDTH,$V_TITLE,$V_DESCRIPTION,$V_KEYWORDS,$V_IS_NODE,$V_VIA_JS,$V_IS_NEW_WIN,$V_STATUS,$V_REALSTATUS)=$cmf->selectrow_arrayQ('select ANOTHER_PAGES_ID,PARENT_ID,NAME,IMAGE1,CATNAME,REALCATNAME,URL,SPECIAL_URL,MENU_WIDTH,TITLE,DESCRIPTION,KEYWORDS,IS_NODE,VIA_JS,IS_NEW_WIN,STATUS,REALSTATUS from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$_REQUEST['id']);




if(isset($V_IMAGE1))
{
   $IM_IMAGE1=split('#',$V_IMAGE1);
   if(isset($IM_3[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

$V_IS_NODE=$V_IS_NODE?'checked':'';
$V_VIA_JS=$V_VIA_JS?'checked':'';
$V_IS_NEW_WIN=$V_IS_NEW_WIN?'checked':'';
$V_STATUS=$V_STATUS?'checked':'';
$V_REALSTATUS=$V_REALSTATUS?'checked':'';

@print <<<EOF
<h2 class="h2">Редактирование - Страницы сайта</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ANOTHER_PAGES.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(CATNAME) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(SPECIAL_URL) &amp;&amp; checkXML(MENU_WIDTH) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(KEYWORDS);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="type" value="0" />

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="event" onclick="this.form.action='EDITER.php';" value="Xml" class="gbt bxml" />&#160;&#160;
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка раздела:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь до каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATNAME" value="$V_CATNAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Спец URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SPECIAL_URL" value="$V_SPECIAL_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ширина меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="MENU_WIDTH" value="$V_MENU_WIDTH" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тайтл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="5" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ключевые слова:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="KEYWORDS" rows="5" cols="90">$V_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Раздел явл. узлом:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_NODE' value='1' $V_IS_NODE/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Исп. JavaScript:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='VIA_JS' value='1' $V_VIA_JS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>В новом окне:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_NEW_WIN' value='1' $V_IS_NEW_WIN/><br /></td></tr>


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
<form action="ANOTHER_PAGES.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select ANOTHER_PAGES_LANGS_ID,CMF_LANG_ID,NAME from ANOTHER_PAGES_LANGS where ANOTHER_PAGES_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Название</th><td></td></tr>
EOF;
while(list($V_ANOTHER_PAGES_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_ANOTHER_PAGES_LANGS_ID" /></td>
<td>$V_ANOTHER_PAGES_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td style="padding-left:{$width}px">$V_NAME</td><td nowrap="">

<a href="ANOTHER_PAGES.php?e1=ED&amp;iid=$V_ANOTHER_PAGES_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] == 'AD' ||  $_REQUEST['e'] =='Новый')
{
list($V_ANOTHER_PAGES_ID,$V_PARENT_ID,$V_NAME,$V_IMAGE1,$V_CATNAME,$V_REALCATNAME,$V_URL,$V_SPECIAL_URL,$V_MENU_WIDTH,$V_TITLE,$V_DESCRIPTION,$V_KEYWORDS,$V_IS_NODE,$V_VIA_JS,$V_IS_NEW_WIN,$V_STATUS,$V_REALSTATUS,$V_ORDER_)=array('','','','','','','','','','','','','','','','','','');
if(!empty($_REQUEST['pid'])) $V_ = $_REQUEST['pid'];
else $V_ = 0;



$IM_IMAGE1=array('','','');
$V_IS_NODE='';
$V_VIA_JS='';
$V_IS_NEW_WIN='';
$V_STATUS='';
$V_REALSTATUS='';

@print <<<EOF
<h2 class="h2">Добавление - Страницы сайта</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="ANOTHER_PAGES.php" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(CATNAME) &amp;&amp; checkXML(URL) &amp;&amp; checkXML(SPECIAL_URL) &amp;&amp; checkXML(MENU_WIDTH) &amp;&amp; checkXML(TITLE) &amp;&amp; checkXML(DESCRIPTION) &amp;&amp; checkXML(KEYWORDS);">
EOF;
print '<input type="hidden" name="pid" value="'.$_REQUEST['pid'].'" />';
@print <<<EOF
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="event" value="Добавить" class="gbt badd" /> 
<input type="submit" name="event" value="Отменить" class="gbt bcancel" />
</td></tr>


<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка раздела:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
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

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Путь до каталога:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="CATNAME" value="$V_CATNAME" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="URL" value="$V_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Спец URL:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="SPECIAL_URL" value="$V_SPECIAL_URL" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ширина меню:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="MENU_WIDTH" value="$V_MENU_WIDTH" size="90" /><br />

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Тайтл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="TITLE" rows="2" cols="90">$V_TITLE</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Описание:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="DESCRIPTION" rows="5" cols="90">$V_DESCRIPTION</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Ключевые слова:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">


<textarea name="KEYWORDS" rows="5" cols="90">$V_KEYWORDS</textarea><br />


</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Раздел явл. узлом:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_NODE' value='1' $V_IS_NODE/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Исп. JavaScript:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='VIA_JS' value='1' $V_VIA_JS/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>В новом окне:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='IS_NEW_WIN' value='1' $V_IS_NEW_WIN/><br /></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

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
$ANOTHER_PAGES_ID=$_REQUEST['id'];
$O_ANOTHER_PAGES_ID=$ANOTHER_PAGES_ID;
do 
{
  $PARENTID=$cmf->selectrow_array('select PARENT_ID from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$ANOTHER_PAGES_ID);
  $parhash[$ANOTHER_PAGES_ID]=1;
  $ANOTHER_PAGES_ID=$PARENTID;
}while(isset($PARENTID));
print <<<EOF
<h2 class="h2">Страницы сайта</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="ANOTHER_PAGES.php" method="POST">
<input type="hidden" name="r" value="{$_REQUEST['r']}" />
<tr bgcolor="#F0F0F0"><td colspan="7">
EOF;

if ($cmf->W)
print <<<EOF
<input type="submit" name="e" value="Новый" class="gbt badd" />
EOF;

print <<<EOF
</td></tr>
EOF;
print <<<EOF
<tr bgcolor="#FFFFFF"><th>N</th><th>Название</th><th>Полный Путь до каталога</th><th>URL</th><th>Спец URL</th><form action="ANOTHER_PAGES.php" method="POST"><th>

</th></form></tr>
EOF;
print visibleTree($cmf,$_REQUEST['r'],0,$_REQUEST['r'],$parhash);
print '</form></table>';
}

function visibleTree($cmf,$parent,$level,$root,$parhash)
{
$width=$level*15+10;
$ret='';
$sth=$cmf->execute('select ANOTHER_PAGES_ID,NAME,REALCATNAME,URL,SPECIAL_URL,STATUS,REALSTATUS from ANOTHER_PAGES where PARENT_ID=? order by ORDER_',$parent);
while ( list($V_ANOTHER_PAGES_ID,$V_NAME,$V_REALCATNAME,$V_URL,$V_SPECIAL_URL,$V_STATUS,$V_REALSTATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{




  $ICONS=<<<EOF
  
EOF;
  $V_REALSTATUS=$V_REALSTATUS?'b':'d';
  $V_STATUS=$V_STATUS?0:1;
  $CO_=$cmf->selectrow_array('select count(*) from ANOTHER_PAGES where PARENT_ID=?',$V_ANOTHER_PAGES_ID);
if(!$CO_)
 {

$folder=<<<EOF
<img src="i/f1.gif" class="fld" /><a href="ANOTHER_PAGES.php?e=ED&amp;id=$V_ANOTHER_PAGES_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }
else
 {

$folder=isset($parhash[$V_ANOTHER_PAGES_ID])?$folder=<<<EOF
<a href="ANOTHER_PAGES.php?id=$V_ANOTHER_PAGES_ID&amp;r=$root" class="$V_REALSTATUS"><img src="i/f1.gif" class="fld" /></a><a href="ANOTHER_PAGES.php?id=$V_ANOTHER_PAGES_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF
:
$folder=<<<EOF
<a href="ANOTHER_PAGES.php?id=$V_ANOTHER_PAGES_ID&amp;r=$root" class="$V_REALSTATUS"><img src="i/f0.gif" class="fld" /></a><a href="ANOTHER_PAGES.php?id=$V_ANOTHER_PAGES_ID&amp;r=$root" class="$V_REALSTATUS">$V_NAME</a>
EOF;

 }

 $V_NAME=<<<EOF
$folder 
EOF;
 
  $ret.=<<<EOF
<tr bgcolor="#ffffff">
<td>$V_ANOTHER_PAGES_ID</td><td style="padding-left:{$width}px">$V_NAME</td><td>$V_REALCATNAME</td><td>$V_URL</td><td>$V_SPECIAL_URL</td><td nowrap="">
EOF;

if ($cmf->W)
$ret.=<<<EOF
<a href="ANOTHER_PAGES.php?e=AD&amp;pid=$V_ANOTHER_PAGES_ID&amp;r=$root"><img src="i/add.gif" border="0" title="Добавить" hspace="5" /></a>
<a href="ANOTHER_PAGES.php?e=UP&amp;id=$V_ANOTHER_PAGES_ID&amp;r=$root"><img src="i/up.gif" border="0" title="Вверх" hspace="5" /></a>
<a href="ANOTHER_PAGES.php?e=DN&amp;id=$V_ANOTHER_PAGES_ID&amp;r=$root"><img src="i/dn.gif" border="0" title="Вниз" hspace="5" /></a>
<a href="ANOTHER_PAGES.php?e=ED&amp;id=$V_ANOTHER_PAGES_ID&amp;r=$root"><img src="i/ed.gif" border="0" title="Изменить" hspace="5" /></a>
<a href="ANOTHER_PAGES.php?e=VS&amp;id=$V_ANOTHER_PAGES_ID&amp;o=$V_ANOTHER_PAGES_ID"><img src="i/v$V_STATUS.gif" border="0" /></a>&#160;
$ICONS
EOF;
if ($cmf->D)
{
$ret .=<<<EOF
<a href="ANOTHER_PAGES.php?e=DL&amp;id=$V_ANOTHER_PAGES_ID&amp;r=$root" onclick="return dl();"><img src="i/del.gif" border="0" title="Удалить" hspace="5" /></a>
EOF;
}

  $ret.= '</td></tr>';

  if(isset($parhash[$V_ANOTHER_PAGES_ID])){$ret.=visibleTree($cmf,$V_ANOTHER_PAGES_ID,$level+1,$root,$parhash);}
}
return $ret;
}

function DelTree($cmf,$id)
{
$sth=$cmf->execute('select ANOTHER_PAGES_ID from ANOTHER_PAGES where PARENT_ID=?',$id);
while(list($V_ANOTHER_PAGES_ID)=mysql_fetch_array($sth, MYSQL_NUM))
{
DelTree($cmf,$V_ANOTHER_PAGES_ID);
$cmf->execute('delete from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$V_ANOTHER_PAGES_ID);
#### del items
}
}

function SetTreeRealStatus($cmf,$id,$state)
{
$sth=$cmf->execute('select ANOTHER_PAGES_ID,STATUS from ANOTHER_PAGES where PARENT_ID=?',$id);
while(list($V_ANOTHER_PAGES_ID,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{
if($V_STATUS){SetTreeRealStatus($cmf,$V_ANOTHER_PAGES_ID,$state);}
if($state) {$cmf->execute('update ANOTHER_PAGES set REALSTATUS=STATUS where ANOTHER_PAGES_ID=?',$V_ANOTHER_PAGES_ID);}
else {$cmf->execute('update ANOTHER_PAGES set REALSTATUS=0 where ANOTHER_PAGES_ID=?',$V_ANOTHER_PAGES_ID);}
}
}

function GetMyRealStatus($cmf,$id)
{
$V_PARENT_ID=$id;
$V_FULLSTATUS=0;
while ($V_PARENT_ID>0)
{
list ($V_PARENT_ID,$V_STATUS)=$cmf->selectrow_array('select PARENT_ID,STATUS from ANOTHER_PAGES where ANOTHER_PAGES_ID=?',$V_PARENT_ID);
$V_FULLSTATUS+=1-$V_STATUS;
}
if($V_FULLSTATUS){$V_FULLSTATUS=0;} else {$V_FULLSTATUS=1;}
return $V_FULLSTATUS;
}

$cmf->MakeCommonFooter();
$cmf->Close();

function GetPath($cmf,$id)
{
list ($PATH,$PARENTID,$NAME)=array('','','');
while(list($PARENTID,$NAME)=$cmf->selectrow_array('select PARENT_ID,CATNAME from ANOTHER_PAGES where ANOTHER_PAGES_ID=? and IS_NODE=0',$id))
{
$id=$PARENTID;
if($NAME && $PARENTID > 0){ $PATH="/$NAME$PATH"; }
$PATH = preg_replace("/(\/){2,}/","/",$PATH);
};
return $PATH;
}

function UpdatePath($cmf,$id,$path)
{
        $sth=$cmf->execute('select ANOTHER_PAGES_ID,CATNAME,PARENT_ID from ANOTHER_PAGES where PARENT_ID=?',$id);
        while(list ($V_CATALOGUE_ID,$V_CATNAME, $V_PARENT_ID)=mysql_fetch_array($sth, MYSQL_NUM))
        {
                if($V_CATNAME && $V_PARENT_ID > 0){$V_CATNAME="$path/$V_CATNAME";} else {$V_CATNAME=$path;};
                $V_CATNAME = preg_replace("/(\/){2,}/","/",$V_CATNAME);
                if(!preg_match("/(\/)$/",$V_CATNAME)) $V_CATNAME .="/";
                $cmf->execute('update ANOTHER_PAGES set REALCATNAME=? where ANOTHER_PAGES_ID=?',$V_CATNAME,$V_CATALOGUE_ID);
                UpdatePath($cmf,$V_CATALOGUE_ID,$V_CATNAME);
        }
}

?>
