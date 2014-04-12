<?php

header('Content-type: text/html; charset=UTF-8');
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

$sql = "select MESSAGES_ID
            ,TEXT
      from MESSAGES
      where ACTION = 0
        and STATE_ = 0";

$sth1 = $cmf->execute($sql);

while (list($messages_id, $messages_text) = mysql_fetch_array($sth1, MYSQL_NUM)) {
    if (!empty($messages_id)) {
        $now = date("Y-m-d");

        $sql = "select CVC.CLIENT_ID
               , CVC.CLIENT_HASH
               , C.EMAIL
          from MESSAGES_CLIENTS MC
             , CLIENT_VOPROS_CLIENTS CVC
             , CLIENT_VOPROS CV
             , CLIENT C
          where MC.MESSAGES_ID = {$messages_id}
            and MC.USER_ID = CVC.CLIENT_ID
            and CVC.CLIENT_VOPROS_ID = CV.CLIENT_VOPROS_ID
            and CVC.CLIENT_ID = C.CLIENT_ID
            and DATE_FORMAT(CV.DATA_START,'%d.%m.%Y') <= '{$now}'
            and DATE_FORMAT(CV.DATA_STOP,'%d.%m.%Y') >= '{$now}'
            and CV.STATUS = 1";

        $sth = $cmf->execute($sql);

        while (list($V_CLIENT_ID, $V_CLIENT_HASH, $V_EMAIL) = mysql_fetch_array($sth, MYSQL_NUM)) {
            $messages_text_send = $messages_text;

            $last_conf = "http://ssk.ua/index/clientvote/client/" . $V_CLIENT_HASH . "/";
            $messages_text_send.= "<br /><a href='{$last_conf}'>" . $last_conf . "</a>";

            $mail->Subject = 'Опрос';
            $mail->Body = $messages_text_send;

            echo "Ушло на {$V_EMAIL} <br />";

            $mail->AddAddress($V_EMAIL);
            $mail->Send();
        }
    }

    $sql = "update MESSAGES
        set ACTION = 1
          , STATE_ = 1
        where MESSAGES_ID = {$messages_id}";

    $sth1 = $cmf->execute($sql);
}
?>