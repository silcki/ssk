<?
require ('core.php');
$cmf= new SCMF();
$cmf->Header();

$par=split('\|',$_REQUEST['q']);

array_unshift($par,$_REQUEST['sel']);
//print_r($par);
$sth=call_user_func_array(array(&$cmf, 'execute'),$par);
while(list($id,$val)=mysql_fetch_array($sth, MYSQL_NUM))
{
        //print "add(name,'$id','".addslashes($val)."');";
    print "<option value='$id'>".addslashes($val)."</option>";
}

$cmf->Close();
unset ($cmf);
?>
