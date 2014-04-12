<? require ('core.php');
if(file_exists('resize_img.php')){
include('resize_img.php');
$obj_img_resize = new Resize();
}
$cmf= new SCMF('GALLERY_GROUP');
session_set_cookie_params($cmf->sessionCookieLifeTime,'/admin/');
session_start();

if (!$cmf->GetRights()) {header('Location: login.php'); exit;}



$cmf->HeaderNoCache();
$cmf->makeCookieActions();



$cmf->MakeCommonHeader();
$visible=1;
$VIRTUAL_IMAGE_PATH="/gallery/";






if(!isset($_REQUEST['e']))$_REQUEST['e']='';
if(!isset($_REQUEST['s']))$_REQUEST['s']='';
if(!isset($_REQUEST['f']))$_REQUEST['f']='';

if($_REQUEST['e'] == 'RET')
{

$_REQUEST['pid']=$cmf->selectrow_array('select GALLERY_GROUP_ID from GALLERY where GALLERY_ID=? ',$_REQUEST['id']);
}





if(!isset($_REQUEST['e1']))$_REQUEST['e1']='';
if(!isset($_REQUEST['p']))$_REQUEST['p']='';

if(($cmf->Param('e1') == 'Удалить') and is_array($_REQUEST['iid']))
{
foreach ($_REQUEST['iid'] as $id)
 {

$cmf->execute('delete from GALLERY_LANGS where GALLERY_ID=? and GALLERY_LANGS_ID=?',$_REQUEST['id'],$id);

 }
$_REQUEST['e']='ED';
$visible=0;
}




if($cmf->Param('e1') == 'Изменить')
{






$cmf->execute('update GALLERY_LANGS set CMF_LANG_ID=?,NAME=?,DESCRIPTION=? where GALLERY_ID=? and GALLERY_LANGS_ID=?',stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),$_REQUEST['id'],$_REQUEST['iid']);

$_REQUEST['e']='ED';
};


if($cmf->Param('e1') == 'Добавить')
{


$_REQUEST['iid']=$cmf->GetSequence('GALLERY_LANGS');








$cmf->execute('insert into GALLERY_LANGS (GALLERY_ID,GALLERY_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION) values (?,?,?,?,?)',$_REQUEST['id'],$_REQUEST['iid'],stripslashes($_REQUEST['CMF_LANG_ID'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']));
$_REQUEST['e']='ED';

$visible=0;
}

if($cmf->Param('e1') == 'ED')
{
list ($V_GALLERY_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=$cmf->selectrow_arrayQ('select GALLERY_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION from GALLERY_LANGS where GALLERY_ID=? and GALLERY_LANGS_ID=?',$_REQUEST['id'],$_REQUEST['iid']);


        $V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');
        
        
@print <<<EOF
<h2 class="h2">Редактирование - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="GALLERY.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="e" value="ED" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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
list($V_GALLERY_LANGS_ID,$V_CMF_LANG_ID,$V_NAME,$V_DESCRIPTION)=array('','','','');


$V_STR_CMF_LANG_ID=$cmf->Spravotchnik($V_CMF_LANG_ID,'select CMF_LANG_ID,NAME from CMF_LANG  where STATUS=1 and SYSTEM_NAME!="ru"  order by NAME');     
@print <<<EOF
<h2 class="h2">Добавление - Тексты для других языков</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form name="frm" method="POST" action="GALLERY.php#f1" ENCTYPE="multipart/form-data" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
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
</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Название :<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

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








if(($_REQUEST['e'] == 'Удалить') and is_array($_REQUEST['id']) and ($cmf->D))
{

      foreach ($_REQUEST['id'] as $id){
         list($image1, $image2) = $cmf->selectrow_array("select IMAGE1, IMAGE2 from GALLERY where GALLERY_ID=?",$id);
         //Удаление картинок
        if(strchr($image1,"#")){
          $img1 = explode("#",$image1);
          $src1 = $img1[0];
          @unlink('../images/gallery/'.$src1);
        }
        if(strchr($image2,"#")){
          $img2 = explode("#",$image2);
          $src2 = $img2[0];
          @unlink('../images/gallery/'.$src2);
        }
      }
      
foreach ($_REQUEST['id'] as $id)
 {

$ORDERING=$cmf->selectrow_array('select ORDERING from GALLERY where GALLERY_ID=?',$id);
$cmf->execute('update GALLERY set ORDERING=ORDERING-1 where ORDERING>? and GALLERY_GROUP_ID=?',$ORDERING,$_REQUEST['pid']);
$cmf->execute('delete from GALLERY where GALLERY_ID=?',$id);

 }

}


if($_REQUEST['e'] == 'UP')
{
list($V_GALLERY_GROUP_ID,$V_ORDERING) =$cmf->selectrow_array('select GALLERY_GROUP_ID,ORDERING from GALLERY where GALLERY_ID=?',$_REQUEST['id']);
if($V_ORDERING > 1)
{

$sql="select GALLERY_ID
           , ORDERING
      from GALLERY
      where ORDERING < {$V_ORDERING}
            and GALLERY_GROUP_ID = {$V_GALLERY_GROUP_ID}
      order by ORDERING DESC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update GALLERY set ORDERING=? where GALLERY_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update GALLERY set ORDERING=? where GALLERY_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);

}
}

if($_REQUEST['e'] == 'DN')
{
list($V_GALLERY_GROUP_ID,$V_ORDERING) =$cmf->selectrow_array('select GALLERY_GROUP_ID,ORDERING from GALLERY where GALLERY_ID=?',$_REQUEST['id']);
$V_MAXORDERING=$cmf->selectrow_array('select max(ORDERING) from GALLERY where GALLERY_GROUP_ID=?',$V_GALLERY_GROUP_ID);
if($V_ORDERING < $V_MAXORDERING)
{

$sql="select GALLERY_ID
           , ORDERING
      from GALLERY
      where ORDERING > {$V_ORDERING}
            and GALLERY_GROUP_ID = {$V_GALLERY_GROUP_ID}
      order by ORDERING ASC
      limit 1";
      
list($V_OTHER_ID,$V_OTHER_ORDERING)=$cmf->selectrow_array($sql);


$cmf->execute('update GALLERY set ORDERING=? where GALLERY_ID=?',$V_ORDERING,$V_OTHER_ID);
$cmf->execute('update GALLERY set ORDERING=? where GALLERY_ID=?',$V_OTHER_ORDERING, $_REQUEST['id']);
}
}


if($_REQUEST['e'] == 'Добавить')
{

$_REQUEST['ORDERING']=$cmf->selectrow_array('select max(ORDERING) from GALLERY where GALLERY_GROUP_ID=?',$_REQUEST['pid']);
$_REQUEST['ORDERING']++;
$_REQUEST['id']=$cmf->GetSequence('GALLERY');





		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE1= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE1='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_y'");
   if(isset($_REQUEST['GEN_IMAGE1']) && $_REQUEST['GEN_IMAGE1'] && isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;
 
	  }
	  else{
			$_REQUEST['IMAGE1']=$cmf->PicturePostResize('NOT_',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE1'],$_REQUEST['HEIGHT_IMAGE1']);
	  }
	}
	elseif(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

	  }
	  else{
			$_REQUEST['IMAGE1']=$cmf->PicturePostResize('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE1'],$_REQUEST['HEIGHT_IMAGE1']);
	  }
	}

			
	
	if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_b'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE2= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE2='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_y'");
	
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_b',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


$cmf->execute('insert into GALLERY (GALLERY_ID,GALLERY_GROUP_ID,NAME,DESCRIPTION,IMAGE1,IMAGE2,STATUS,ORDERING) values (?,?,?,?,?,?,?,?)',$_REQUEST['id'],stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['STATUS']),stripslashes($_REQUEST['ORDERING']));

$_REQUEST['e'] ='ED';

      include('resize_img.php');
$pathToW = '';
        	
      if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
        $tmpPath = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");
        if($tmpPath){
            $tmpPath = preg_replace('/\#.*/','',$tmpPath);
            $pathToW = '../images/wm/'.$tmpPath;
        }
        $gallary_big_x = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_x'");
        $gallary_big_y = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_y'");        
        
        $image2 = $cmf->selectrow_array("select IMAGE2 from GALLERY where GALLERY_ID=?",$_REQUEST['id']);
        
        if(!empty($_REQUEST['IS_WATERMARK_b']) && !empty($pathToW)) $waterPath = $pathToW;
        else $waterPath = ''; 
        
        $object = new Resize();
        $object->addSettings($image2, $image2, '../images/gallery/', $waterPath, $gallary_big_x, $gallary_big_y);
        $object->addImage();

        $image2 = $object->new_image_name;

        if ($image2){
        $sql="update GALLERY
              set IMAGE2 = '{$image2}'
              where GALLERY_ID = {$_REQUEST['id']}";
              
        $cmf->execute($sql);
        }
      }
	  if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
        $tmpPath = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");
        if($tmpPath){
            $tmpPath = preg_replace('/\#.*/','',$tmpPath);
            $pathToW = '../images/wm/'.$tmpPath;
        }
        $gallary_small_x = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_x'");
        $gallary_small_y = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_y'");
        
        $image1 = $cmf->selectrow_array("select IMAGE1 from GALLERY where GALLERY_ID=?",$_REQUEST['id']);
        
        if(!empty($_REQUEST['IS_WATERMARK']) && !empty($pathToW)) $waterPath = $pathToW;
        else $waterPath = ''; 
        
        $object = new Resize();
        $object->addSettings($image1, $image1, '../images/gallery/', $waterPath, $gallary_small_x, $gallary_small_y);
        $object->addImage();
        
        $image1 = $object->new_image_name;
           
        if ($image1){
        $sql="update GALLERY
              set IMAGE1 = '{$image1}'
              where GALLERY_ID = {$_REQUEST['id']}";
              
        $cmf->execute($sql);
        }
      }
      
}

if($_REQUEST['e'] == 'Изменить')
{






		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE1= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE1='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_y'");
   if(isset($_REQUEST['GEN_IMAGE1']) && $_REQUEST['GEN_IMAGE1'] && isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;
 
	  }
	  else{
			$_REQUEST['IMAGE1']=$cmf->PicturePostResize('NOT_',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE1'],$_REQUEST['HEIGHT_IMAGE1']);
	  }
	}
	elseif(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
	  if(isset($obj_img_resize) && is_object($obj_img_resize)){
		  
			$obj_img_resize->addSettings('NOT_IMAGE1',''.$_REQUEST['id'].'', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE1, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE1'] = $obj_img_resize->new_image_name;

	  }
	  else{
			$_REQUEST['IMAGE1']=$cmf->PicturePostResize('NOT_IMAGE1',$_REQUEST['IMAGE1'],''.$_REQUEST['id'].'',$VIRTUAL_IMAGE_PATH,$_REQUEST['WIDTH_IMAGE1'],$_REQUEST['HEIGHT_IMAGE1']);
	  }
	}

			
	
	if(isset($_REQUEST['CLR_IMAGE1']) && $_REQUEST['CLR_IMAGE1']){$_REQUEST['IMAGE1']=$cmf->UnlinkFile($_REQUEST['IMAGE1'],$VIRTUAL_IMAGE_PATH);}
	

		
				

$path_to_watermark = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");

if(!empty($path_to_watermark) && !empty($_REQUEST['IS_WATERMARK_b'])){
	$path_to_watermark = preg_replace('/\#.*/','',$path_to_watermark);
	$path_to_watermark_IMAGE2= '../images/wm/'.$path_to_watermark;
}
else $path_to_watermark_IMAGE2='';


	$width = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_x'");
	$height = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_y'");
	
    if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
		if(!empty($_REQUEST['iid'])){
		   if(!empty($_REQUEST['bid'])){ 
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_'.$_REQUEST['iid'].'_'.$_REQUEST['bid'].'_b',$VIRTUAL_IMAGE_PATH);
			  }
		   }
		   else{
			  if(isset($obj_img_resize) && is_object($obj_img_resize)){
				
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			  }
			  else{
					 $_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			  }
		   }
		}
		else{ 
			if(isset($obj_img_resize) && is_object($obj_img_resize)){
			   
			$obj_img_resize->addSettings('NOT_IMAGE2',''.$_REQUEST['id'].'_b', '../images'.$VIRTUAL_IMAGE_PATH, $path_to_watermark_IMAGE2, $width, $height);
			$obj_img_resize->addImagePost();
			$_REQUEST['IMAGE2'] = $obj_img_resize->new_image_name;

			}
			else{
				$_REQUEST['IMAGE2']=$cmf->PicturePost('NOT_IMAGE2',$_REQUEST['IMAGE2'],''.$_REQUEST['id'].'_b',$VIRTUAL_IMAGE_PATH);		   
			}
		}
	}

			
		if(isset($_REQUEST['CLR_IMAGE2']) && $_REQUEST['CLR_IMAGE2']){$_REQUEST['IMAGE2']=$cmf->UnlinkFile($_REQUEST['IMAGE2'],$VIRTUAL_IMAGE_PATH);}
	
$_REQUEST['STATUS']=isset($_REQUEST['STATUS']) && $_REQUEST['STATUS']?1:0;


if(!empty($_REQUEST['pid'])) $cmf->execute('update GALLERY set GALLERY_GROUP_ID=?,NAME=?,DESCRIPTION=?,IMAGE1=?,IMAGE2=?,STATUS=? where GALLERY_ID=?',stripslashes($_REQUEST['pid'])+0,stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);
else $cmf->execute('update GALLERY set NAME=?,DESCRIPTION=?,IMAGE1=?,IMAGE2=?,STATUS=? where GALLERY_ID=?',stripslashes($_REQUEST['NAME']),stripslashes($_REQUEST['DESCRIPTION']),stripslashes($_REQUEST['IMAGE1']),stripslashes($_REQUEST['IMAGE2']),stripslashes($_REQUEST['STATUS']),$_REQUEST['id']);

$_REQUEST['e'] ='ED';

      include('resize_img.php');
$pathToW = '';

	

      if(isset($_FILES['NOT_IMAGE2']['tmp_name']) && $_FILES['NOT_IMAGE2']['tmp_name']){
        $tmpPath = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_big_watermark'");
        if($tmpPath){
            $tmpPath = preg_replace('/\#.*/','',$tmpPath);
            $pathToW = '../images/wm/'.$tmpPath;
        }
        $gallary_big_x = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_x'");
        $gallary_big_y = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_big_y'");        
        
        $image2 = $cmf->selectrow_array("select IMAGE2 from GALLERY where GALLERY_ID=?",$_REQUEST['id']);
        
        if(!empty($_REQUEST['IS_WATERMARK_b']) && !empty($pathToW)) $waterPath = $pathToW;
        else $waterPath = ''; 
        
        $object = new Resize();
        $object->addSettings($image2, $image2, '../images/gallery/', $waterPath, $gallary_big_x, $gallary_big_y);
        $object->addImage();
        
        $image2 = $object->new_image_name;
           
        if ($image2){
        $sql="update GALLERY
              set IMAGE2 = '{$image2}'
              where GALLERY_ID = {$_REQUEST['id']}";
              
        $cmf->execute($sql);
        }
      }
	  if(isset($_FILES['NOT_IMAGE1']['tmp_name']) && $_FILES['NOT_IMAGE1']['tmp_name']){
        $tmpPath = $cmf->selectrow_array("select IMAGE from SETINGS where SYSTEM_NAME='path_to_small_watermark'");
        if($tmpPath){
            $tmpPath = preg_replace('/\#.*/','',$tmpPath);
            $pathToW = '../images/wm/'.$tmpPath;
        }
        $gallary_small_x = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_x'");
        $gallary_small_y = $cmf->selectrow_array("select VALUE from SETINGS where SYSTEM_NAME='gallary_small_y'");
        
        $image1 = $cmf->selectrow_array("select IMAGE1 from GALLERY where GALLERY_ID=?",$_REQUEST['id']);
        
        if(!empty($_REQUEST['IS_WATERMARK']) && !empty($pathToW)) $waterPath = $pathToW;
        else $waterPath = ''; 
        
        $object = new Resize();
        $object->addSettings($image1, $image1, '../images/gallery/', $waterPath, $gallary_small_x, $gallary_small_y);
        $object->addImage();
        
        $image1 = $object->new_image_name;
           
        if ($image1){
        $sql="update GALLERY
              set IMAGE1 = '{$image1}'
              where GALLERY_ID = {$_REQUEST['id']}";
              
        $cmf->execute($sql);
        }
      }
      
};

if($_REQUEST['e'] == 'ED')
{
list($V_GALLERY_ID,$V_GALLERY_GROUP_ID,$V_NAME,$V_DESCRIPTION,$V_IMAGE1,$V_IMAGE2,$V_STATUS)=$cmf->selectrow_arrayQ('select GALLERY_ID,GALLERY_GROUP_ID,NAME,DESCRIPTION,IMAGE1,IMAGE2,STATUS from GALLERY where GALLERY_ID=?',$_REQUEST['id']);



if(isset($V_IMAGE1))
{
  $IM_IMAGE1=split('#',$V_IMAGE1);
  if(isset($IM_3[1]) && $IM_IMAGE1[1] > 150){$IM_IMAGE1[2]=$IM_IMAGE1[2]*150/$IM_IMAGE1[1]; $IM_IMAGE1[1]=150;}
}

if(isset($V_IMAGE2))
{
   $IM_IMAGE2=split('#',$V_IMAGE2);
   if(isset($IM_4[1]) && $IM_IMAGE2[1] > 150){$IM_IMAGE2[2]=$IM_IMAGE2[2]*150/$IM_IMAGE2[1]; $IM_IMAGE2[1]=150;}
}

$V_STATUS=$V_STATUS?'checked':'';
print @<<<EOF
<h2 class="h2">Редактирование - Галерея</h2>
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="GALLERY.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />
<input type="hidden" name="s" value="{$_REQUEST['s']}" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
EOF;
if(!empty($IM_IMAGE1[1])) $width = $IM_IMAGE1[1];
else $width = '';

if(!empty($IM_IMAGE1[2])) $height = $IM_IMAGE1[2];
else $height = '';

$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;

print <<<EOF
<table><tr><td>
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" /></td>
<td><input type="file" name="NOT_IMAGE1" size="1" disabled="1" /><br />
<input type="checkbox" name="GEN_IMAGE1" value="1" checked="1" onClick="if(document.frm.GEN_IMAGE1.checked == '1') document.frm.NOT_IMAGE1.disabled='1'; else document.frm.NOT_IMAGE1.disabled='';" />Сгенерить из большой<br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт. <br />
Ширина превью: <input type="text" name="WIDTH_IMAGE1" size="5" value="$width" /><br />
Высота превью: <input type="text" name="HEIGHT_IMAGE1" size="5" value="$height" /><br />
<br /><input type="checkbox" name="IS_WATERMARK" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка бол.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
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
<br /><input type="checkbox" name="IS_WATERMARK_b" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Изменить" class="gbt bsave" />&#160;&#160;
<input type="submit" name="e" value="Назад" class="gbt bcancel" />
</td></tr>
</form>
</table><br />
EOF;



print <<<EOF
<a name="f1"></a><h3 class="h3">Тексты для других языков</h3>
EOF;

@print <<<EOF
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">
<form action="GALLERY.php#f1" method="POST">
<tr bgcolor="#F0F0F0"><td colspan="5">
<input type="submit" name="e1" value="Новый" class="gbt badd" /><img src="img/hi.gif" width="4" height="1" />
<input type="submit" name="e1" onclick="return dl();" value="Удалить" class="gbt bdel" />
<input type="hidden" name="id" value="{$_REQUEST['id']}" />
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<input type="hidden" name="p" value="{$_REQUEST['p']}" />

</td></tr>
EOF;
$sth=$cmf->execute('select GALLERY_LANGS_ID,CMF_LANG_ID,NAME from GALLERY_LANGS where GALLERY_ID=? ',$_REQUEST['id']);
print <<<EOF
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'[iid]');" /></td><th>N</th><th>Язык</th><th>Название </th><td></td></tr>
EOF;
while(list($V_GALLERY_LANGS_ID,$V_CMF_LANG_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$V_CMF_LANG_ID=$cmf->selectrow_arrayQ('select NAME from CMF_LANG where CMF_LANG_ID=?',$V_CMF_LANG_ID);
                                        


@print <<<EOF
<tr bgcolor="#FFFFFF">
<td><input type="checkbox" name="iid[]" value="$V_GALLERY_LANGS_ID" /></td>
<td>$V_GALLERY_LANGS_ID</td><td>$V_CMF_LANG_ID</td><td>$V_NAME</td><td nowrap="">

<a href="GALLERY.php?e1=ED&amp;iid=$V_GALLERY_LANGS_ID&amp;id={$_REQUEST['id']}&amp;pid={$_REQUEST['pid']}"><img src="i/ed.gif" border="0" title="Изменить" /></a>
</td></tr>
EOF;
$visible=0;
}
print '</form></table>';


$visible=0;
}

if($_REQUEST['e'] =='Новый')
{
list($V_GALLERY_ID,$V_GALLERY_GROUP_ID,$V_NAME,$V_DESCRIPTION,$V_IMAGE1,$V_IMAGE2,$V_STATUS,$V_ORDERING)=array('','','','','','','','');


$IM_IMAGE1=array('','','');
$IM_IMAGE2=array('','','');
$V_STATUS='checked';
@print <<<EOF
<h2 class="h2">Добавление - Галерея</h2>
<a href="javascript:history.go(-1)">&#160;<b>вернуться</b></a><p />
<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" style="width: 500px" class="f">
<form method="POST" action="GALLERY.php" ENCTYPE="multipart/form-data" name="frm" onsubmit="return true  &amp;&amp; checkXML(NAME) &amp;&amp; checkXML(DESCRIPTION);">
<input type="hidden" name="pid" value="{$_REQUEST['pid']}" />
<tr bgcolor="#F0F0F0" class="ftr"><td colspan="2">
<input type="submit" name="e" value="Добавить" class="gbt badd" /> 
<input type="submit" name="e" value="Отменить" class="gbt bcancel" />
</td></tr>
EOF;



@print <<<EOF

<tr bgcolor="#FFFFFF"><th width="1%"><b>Название:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%">

<input type="text" name="NAME" value="$V_NAME" size="90" /><br />

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

</td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка мал.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE1" value="$V_IMAGE1" />
EOF;
if(!empty($IM_IMAGE1[1])) $width = $IM_IMAGE1[1];
else $width = '';

if(!empty($IM_IMAGE1[2])) $height = $IM_IMAGE1[2];
else $height = '';

$IM_IMAGE1[0] = !empty($IM_IMAGE1[0]) ? $IM_IMAGE1[0]:0;
$IM_IMAGE1[1] = !empty($IM_IMAGE1[1]) ? $IM_IMAGE1[1]:0;
$IM_IMAGE1[2] = !empty($IM_IMAGE1[2]) ? $IM_IMAGE1[2]:0;

print <<<EOF
<table><tr><td>
<img src="/images/$VIRTUAL_IMAGE_PATH$IM_IMAGE1[0]" width="$IM_IMAGE1[1]" height="$IM_IMAGE1[2]" /></td>
<td><input type="file" name="NOT_IMAGE1" size="1" disabled="1" /><br />
<input type="checkbox" name="GEN_IMAGE1" value="1" checked="1" onClick="if(document.frm.GEN_IMAGE1.checked == '1') document.frm.NOT_IMAGE1.disabled='1'; else document.frm.NOT_IMAGE1.disabled='';" />Сгенерить из большой<br />
<input type="checkbox" name="CLR_IMAGE1" value="1" />Сбросить карт. <br />
Ширина превью: <input type="text" name="WIDTH_IMAGE1" size="5" value="$width" /><br />
Высота превью: <input type="text" name="HEIGHT_IMAGE1" size="5" value="$height" /><br />
<br /><input type="checkbox" name="IS_WATERMARK" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Картинка бол.:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type="hidden" name="IMAGE2" value="$V_IMAGE2" />
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
<br /><input type="checkbox" name="IS_WATERMARK_b" /> Прикрепить watermark

</td></tr></table></td></tr><tr bgcolor="#FFFFFF"><th width="1%"><b>Вкл:<br /><img src="img/hi.gif" width="125" height="1" /></b></th><td width="100%"><input type='checkbox' name='STATUS' value='1' $V_STATUS/><br /></td></tr>

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

if(!empty($_REQUEST['pid']) and $_REQUEST['pid']!='all') $V_PARENTSCRIPTNAME=$cmf->selectrow_array('select NAME from GALLERY_GROUP where GALLERY_GROUP_ID=?',$_REQUEST['pid']);
else $V_PARENTSCRIPTNAME='';

print <<<EOF
<h2 class="h2">$V_PARENTSCRIPTNAME / Галерея</h2><form action="GALLERY.php" method="POST">
<a href="GALLERY_GROUP.php?e=RET&amp;id={$_REQUEST['pid']}">
<img src="i/back.gif" border="0" align="top" /> Назад</a><br />
EOF;




$pagesize=20;

if(!isset($_REQUEST['p']) || !($_REQUEST['p']) ){$_REQUEST['p']=1;}

if(!isset($_REQUEST['count']) || !$_REQUEST['count'])
{
if(!empty($_REQUEST['pid']) and $_REQUEST['pid']=='all')
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from GALLERY A where A.GALLERY_GROUP_ID > 0',$_REQUEST['pid']);
}
else
{
$_REQUEST['count']=$cmf->selectrow_array('select count(*) from GALLERY A where A.GALLERY_GROUP_ID=?',$_REQUEST['pid']);

}
$_REQUEST['pcount']=floor($_REQUEST['count']/$pagesize+0.9999);
if($_REQUEST['p'] > $_REQUEST['pcount']){$_REQUEST['p']=$_REQUEST['pcount'];}
}

if($_REQUEST['pcount'] > 1)
{
 $start=1;
if($_REQUEST['p']>15){$start=$_REQUEST['p']-15;}
 
 for($i=$start;$i<=$_REQUEST['pcount'] && ($i-$start)<31;$i++)
 {
  if($i==$_REQUEST['p']) { print <<<EOF
- <b class="red">$i</b>
EOF;
 } else { print <<<EOF
- <a class="t" href="GALLERY.php?pid={$_REQUEST['pid']}&amp;count={$_REQUEST['count']}&amp;p={$i}&amp;pcount={$_REQUEST['pcount']}{$filters}">$i</a>
EOF;
  }
 }
print <<<EOF
&#160;из <span class="red">({$_REQUEST['pcount']})</span><br />
EOF;
}

if(!empty($_REQUEST['pid']) and $_REQUEST['pid'] == 'all')
{
$sth=$cmf->execute('select A.GALLERY_ID,A.NAME,A.IMAGE1,A.STATUS from GALLERY A
where A.GALLERY_GROUP_ID > 0  order by A.ORDERING limit ?,?',$pagesize*($_REQUEST['p']-1),$pagesize);
}
else
{
$sth=$cmf->execute('select A.GALLERY_ID,A.NAME,A.IMAGE1,A.STATUS from GALLERY A
where A.GALLERY_GROUP_ID=?  order by A.ORDERING limit ?,?',$_REQUEST['pid'],$pagesize*($_REQUEST['p']-1),$pagesize);

}





@print <<<EOF
<img src="img/hi.gif" width="1" height="3" /><table bgcolor="#C0C0C0" border="0" cellpadding="4" cellspacing="1" class="l">
<tr bgcolor="#F0F0F0"><td colspan="5">
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
<tr bgcolor="#FFFFFF"><td><input type="checkbox" onclick="return SelectAll(this.form,checked,'id[]');" /></td><th>N</th><th>Название</th><th>Картинка мал.</th><td></td></tr>
EOF;


if($sth)
while(list($V_GALLERY_ID,$V_NAME,$V_IMAGE1,$V_STATUS)=mysql_fetch_array($sth, MYSQL_NUM))
{



if(isset($V_IMAGE1))
{
   $IM_3=split('#',$V_IMAGE1);
   if(isset($IM_3[1]) && $IM_3[1] > 150){$IM_3[2]=$IM_3[2]*150/$IM_3[1]; $IM_3[1]=150;
   $V_IMAGE1="<img src=\"/images$VIRTUAL_IMAGE_PATH$IM_3[0]\" width=\"$IM_3[1]\" height=\"$IM_3[2]\">";}
}


if($V_STATUS == 1){$V_COLOR='#FFFFFF';} else {$V_COLOR='#a0a0a0';}



@print <<<EOF
<tr bgcolor="$V_COLOR">
<td><input type="checkbox" name="id[]" value="$V_GALLERY_ID" /></td>
<td>$V_GALLERY_ID</td><td>$V_NAME</td><td>$V_IMAGE1</td><td nowrap="">
<a href="GALLERY.php?e=UP&amp;id=$V_GALLERY_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/up.gif" border="0" /></a>
<a href="GALLERY.php?e=DN&amp;id=$V_GALLERY_ID&amp;pid={$_REQUEST['pid']}{$filters}"><img src="i/dn.gif" border="0" /></a>
EOF;

if ($cmf->W)
@print <<<EOF
<a href="GALLERY.php?e=ED&amp;id=$V_GALLERY_ID&amp;pid={$_REQUEST['pid']}&amp;p={$_REQUEST['p']}{$filters}"><img src="i/ed.gif" border="0" title="Изменить" /></a>

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


function ___GetTree($cmf,$pid,$id)
{
$id+=0;
$ret='';
$sth=$cmf->execute('select GALLERY_GROUP_ID,NAME from GALLERY_GROUP where PARENT_ID=? order by ORDERING',$pid);
while(list($V_GALLERY_GROUP_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM))
{
$ret.='<li>'.($id==$V_GALLERY_GROUP_ID?'<input type="radio" name="cid" value="'.$V_GALLERY_GROUP_ID.'" disabled="yes" />':'<input type="radio" name="cid" value="'.$V_GALLERY_GROUP_ID.'" />')."&#160;$V_NAME</li>".___GetTree($cmf,$V_GALLERY_GROUP_ID,$id);
}
if ($ret) {$ret="<ul>${ret}</ul>";}
return $ret;
}

?>
