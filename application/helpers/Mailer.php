<?php
Zend_Loader::loadClass('Zend_Mail');
Zend_Loader::loadClass('Zend_Mail_Transport_Smtp');
/**
 * Отправка сообщений
 *
 * @author Dima <john.doe@example.com>
 */
class Mailer
{

    /**
     * Отправить сообщение
     *
     * @param array $params description
     *                      to - send email
     *         message - mail message
     *         subject - mail subject
     *         mailerFrom - email sender
     *         mailerFromName - sender name
     *         attach - link file source
     *         name - file name
     *         attach_type - file type
     *
     * @return null | string
     */
    static function send($params)
    {
        $transport = self::getTransport($params);

        $mailer = new Zend_Mail('utf-8');

        $mailer->setFrom($params['mailerFrom'], "noreply@ssk.ua");
        $mailer->setSubject($params['subject']);
        $mailer->addTo($params['to']);
        $mailer->setBodyHtml(
            $params['message'], 'UTF-8', Zend_Mime::ENCODING_BASE64
        );

        if (!empty($params['attach'])) {
            $logo = new Zend_Mime_part(file_get_contents($params['attach']));
            $logo->type = $params['attach_type'];
            $logo->disposition = Zend_Mime::DISPOSITION_INLINE;
            $logo->encoding = Zend_Mime::ENCODING_BASE64;
            $logo->filename = $params['name'];

            $mailer->addAttachment($logo);
        }

        try {
            if (!is_null($transport)) {
                $mailer->send($transport);
            } else {
                $mailer->send();
            }
        } catch (Exception $ex) {
            echo "Ошибка отправки электронного письма на ящик " . $ex->getMessage();
            exit;
        }
    }

    /**
     * @param $params
     *
     * @return null|Zend_Mail_Transport_Smtp
     */
    static public function getTransport($params)
    {
        if (!empty($params['host'])) {
            $config = array('auth' => $params['auth'],
                            'username' => $params['username'],
                            'password' => $params['password'],
                            'port' => $params['port'],
                        );

            return new Zend_Mail_Transport_Smtp($params['host'], $config);
        }

        return null;
    }

}