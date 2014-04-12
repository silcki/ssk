<? require ('core.php');
$cmf= new SCMF('CLIENT_VOPROS');
if (!$cmf->GetRights()) {header('Location: login.php'); exit;}

$cmf->HeaderNoCache();
$cmf->MakeCommonHeader();

$pid = !empty($_REQUEST['pid']) ? $_REQUEST['pid']:'';

print <<<EOF
<h2 class="h2">Результат опроса</h2>
<a href="CLIENT_VOPROS.php"><img src="i/back.gif" border="0" align="top" /> Назад</a><br /><br />
<table width="50%" border="0"><tr><td>
EOF;

if(!empty($pid)){
  $sql="select * 
        from CLIENT_VOPROS 
        where CLIENT_VOPROS_ID = {$pid}";
          
  $client_vopros = $cmf->selectrow_array($sql);

  print "<h3>{$client_vopros[3]}</h2>";
  
  $sql="select count(CVC.CLIENT_ID) as total 
        from CLIENT_VOPROS CV
            ,CLIENT_OTVETS CO
            ,CLIENT_VOPROS_CLIENTS CVC
        where CV.CLIENT_VOPROS_ID = {$pid}
          and CV.CLIENT_VOPROS_ID = CO.CLIENT_VOPROS_ID
          and CO.CLIENT_OTVETS_ID = CVC.CLIENT_VOPROS_ID";
          
  $total = $cmf->selectrow_array($sql);
        
  $sql="select count(CVC.CLIENT_ID) as amnt
              ,CVC.CLIENT_VOPROS_ID 
              ,CO.NAME 
        from CLIENT_VOPROS CV
            ,CLIENT_OTVETS CO
            ,CLIENT_VOPROS_CLIENTS CVC
        where CV.CLIENT_VOPROS_ID = {$pid}
          and CV.CLIENT_VOPROS_ID = CO.CLIENT_VOPROS_ID
          and CO.CLIENT_OTVETS_ID = CVC.CLIENT_VOPROS_ID
        group by CVC.CLIENT_VOPROS_ID";
        
  $sth=$cmf->execute($sql);
        
  while(list($amnt,$V_CLIENT_VOPROS_ID,$V_NAME)=mysql_fetch_array($sth, MYSQL_NUM)){
    if($amnt>0)       
      $percent = round(($amnt * 100)/$total,2);
    else
      $percent = 0;
      
@print <<<EOF
<div><label>{$V_NAME}&nbsp;<strong>({$percent}%)</strong></label></div>
<div style="width:{$percent}%; height:3px; background-color:#000;">&nbsp;</div><br />
EOF;


  }
  
  $sql="select CO.NAME as CO_NAME 
              ,C.NAME  as C_NAME
        from CLIENT_VOPROS CV
            ,CLIENT_OTVETS CO
            ,CLIENT_VOPROS_CLIENTS CVC
            ,CLIENT C
        where CV.CLIENT_VOPROS_ID = {$pid}
          and CV.CLIENT_VOPROS_ID = CO.CLIENT_VOPROS_ID
          and CO.CLIENT_OTVETS_ID = CVC.CLIENT_VOPROS_ID
          and CVC.CLIENT_ID = C.CLIENT_ID";
        
  $sth=$cmf->execute($sql);

  print '<table bgcolor="#CCCCCC" border="0" cellpadding="5" cellspacing="1" class="l">';  
  while(list($CO_NAME,$C_NAME)=mysql_fetch_array($sth, MYSQL_NUM)){
    print "<tr bgcolor='#FFFFFF'><td>{$C_NAME}</td><td>{$CO_NAME}</td></tr>";
  }
  print '</form></table>';
}

print <<<EOF
</td></tr></table>
EOF;

$cmf->MakeCommonFooter();
$cmf->Close();
unset($cmf);

?>
