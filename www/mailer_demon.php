<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once('admin/core.php');
require_once '../include/mail/Phpmailer.class.php';
require_once '../include/mail/Smtp.class.php';


$cmf = &new SCMF();

$mail = new PHPMailer();

$sql = "select VALUE
      from SETINGS
      where SYSTEM_NAME = 'email_from'";

$email_from = $cmf->selectrow_array($sql);

$patrern = '/(.*)<(.*)>/Uis';
preg_match_all($patrern, $email_from, $arr);

$mail->ContentType = 'plain/html';          // SMTP password
$mail->CharSet = 'utf-8';          // SMTP password
$mail->From = trim($arr[2][0]);
$mail->FromName = trim($arr[1][0]);
$mail->AddReplyTo('');
$mail->WordWrap = 50;
$mail->IsHTML(true);



$sql = "select MS.MESSAGES_ID, MS.NAME, CL.EMAIL, MS.TEXT
from CLIENT CL join MESSAGES_CLIENTS MC on (CL.CLIENT_ID = MC.USER_ID)
join MESSAGES MS using (MESSAGES_ID)
where CL.STATUS = 1
and MS.STATE_ =0
and MS.ACTION = 0
and CL.EMAIL !=''
;";

$sth = $cmf->execute($sql);

while ($row = mysql_fetch_assoc($sth)) {

    $mail->Subject = $row['NAME'];

    $zxc = strtolower($row['TEXT']);
    preg_match_all('/.*<img.*\s*src="(.*)"/Uis', $zxc, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $img) {

            $tmp = explode('/', $img);
            $row['TEXT'] = str_replace($img, $tmp[3], $row['TEXT']);
            $mail->AddAttachment(substr($img, 1));
            echo $img . '--' . $mail->ErrorInfo . "<br />";
        }
    }

    $mail->Body = $row['TEXT'];

    $V_FILE = array('', '');
    if ($row['ATTACHMENT'])
        $V_FILE = explode("#", $row['ATTACHMENT']);

    if ($V_FILE[0]) {
        $path = 'images/newsletters/' . $V_FILE[0];
        $mail->AddAttachment($path);
    }

//  $sql="select SH.EMAIL
//        from SUBSCRIBER_GROUPS SG
//        join SUBSCRIBERS S ON (S.SUBSCRIBER_GROUPS_ID = SG.SUBSCRIBER_GROUPS_ID)
//        join SHOPUSER SH ON (SH.USER_ID = S.SUBSCRIBER_ID)
//        where SG.SUBSCRIBER_GROUPS_ID = {$row['SUBSCRIBER_GROUPS_ID']}";
//
//  $sth2=$cmf->execute($sql);
//  while($usr=mysql_fetch_assoc($sth2)){
//    echo "Ушло на ".$usr['EMAIL']."<br />";
//    $mail->AddAddress($usr['EMAIL']);
//    $mail->Send();
//  }


    echo "Ушло на " . $usr['EMAIL'] . "<br />";
    $mail->AddAddress($usr['EMAIL']);
    $mail->Send();

    $sql = "update MESSAGES
        set STATE_= 1
        where MESSAGES_ID={$row['MESSAGES_ID']}";

    $cmf->execute($sql);
}
?>