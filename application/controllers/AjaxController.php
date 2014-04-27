<?php
class AjaxController extends Core_Controller_Action_Abstract
{

    const TEXT_FEEDBACK_SUBJECT = 'feedback_mail_subject';
    const TEXT_ORDER_ADMIN_SUBJECT = 'order_mail_subject';

    /**
     * Переменная по которой ищем в БД имейлы манагеров
     */
    const EMAIL_FEEDBACK_VAR = 'email_feedback';
    const EMAIL_FAQ_VAR = 'email_faq';
    const EMAIL_MANAGERS = 'manager_emails';
    const ANOTHER_PAGES_PATH_FEEDBACK = '/feedback/';

    /**
     * Путь по которму ищем XML для письма с заказом
     */
    const ANOTHER_PAGES_PATH_ORDER = '/cat/order/';
    const USER_TEXT_SUBJECT = 'Оформление заказа';
    const FAQ_TEXT_SUBJECT = 'Оформление заказа';
    const ANOTHER_PAGES_PATH_ADMIN_ORDER = '/cat/orderadmin/';

    public function mapsAction()
    {
        $this->_disableRender();

        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        $doc = $anotherPagesModel->getDocXml($this->AnotherPages->getPageId('/index/map/'), 0, $this->lang_id);
        echo stripslashes($doc);
    }

    public function callbackAction()
    {
        $textesModel = $this->getServiceManager()->getModel()->getTextes();
        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        if ($this->requestHttp->isPost()) {
            $data['NAME'] = $this->requestHttp->getPost('name');
            $data['PHONE'] = $this->requestHttp->getPost('phone');
            $data['CALLBACK_TIME_ID'] = $this->requestHttp->getPost('callback_time_id');
            $data['DESCRIPTION'] = $this->requestHttp->getPost('description');

            $result = $errors = array();
            if (empty($data['NAME'])) {
                $err = $textesModel->getSysText('text_callback_errore_name');
                $errors[] = $err['DESCRIPTION'];
            }

            if (empty($data['PHONE'])) {
                $err = $textesModel->getSysText('text_callback_errore_phone');
                $errors[] = $err['DESCRIPTION'];
            }

            if (empty($data['CALLBACK_TIME_ID'])) {
                $err = $textesModel->getSysText('text_callback_errore_callback_time');
                $errors[] = $err['DESCRIPTION'];
            }

            if (empty($errors)) {
                $anotherPagesModel->insertData('CALLBACK', $data);

                $time = $anotherPagesModel->getCallbackTimeName($data['CALLBACK_TIME_ID']);

                $message_admin = $anotherPagesModel->getDocXml($anotherPagesModel->getPageId('/callback/'), 0, $this->lang_id);
                $message_admin = str_replace("##name##", $data['NAME'], $message_admin);
                $message_admin = str_replace("##phone##", $data['PHONE'], $message_admin);
                $message_admin = str_replace("##time##", $time, $message_admin);
                $message_admin = str_replace("##description##", $data['DESCRIPTION'], $message_admin);

                $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                    . $message_admin . '</body></html>';

                $subject = $textesModel->getSysText('callback_subject', $this->lang_id);

                $to = $this->getSettingValue('callback_email');
                if ($to) {
                    $email_from = $this->getSettingValue('email_from');
                    $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
                    preg_match($patrern, $email_from, $arr);

                    $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
                    $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

                    $params = array_merge($params, $this->getMailTrasportData());

                    $manager_emails_arr = explode(";", $to);
                    if (!empty($manager_emails_arr)) {
                        $params['message'] = $message_admin;
                        $params['subject'] = $subject['DESCRIPTION'];

                        foreach ($manager_emails_arr as $mm) {
                            $mm = trim($mm);
                            if (!empty($mm)) {
                                $params['to'] = $mm;
                                Core_Controller_Action_Helper_Mailer::send($params);
                            }
                        }
                    }
                }

                $result['status'] = 'ok';
            } else {
                $result['status'] = 'fail';
                $result['errors'] = $errors;
            }

            $this->_disableRender();

            $this->_helper->json($result);
        }

        $callback_time = $anotherPagesModel->getCallbackTime($this->lang_id);
        if (!empty($callback_time)) {
            foreach ($callback_time as $view) {
                $this->domXml->create_element('callback_time', '', 2);
                $this->domXml->set_attribute(array('id' => $view['CALLBACK_TIME_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function complainAction()
    {
        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();
        $textesModel = $this->getServiceManager()->getModel()->getTextes();

        if ($this->requestHttp->isPost()) {
            $data['NAME'] = $this->requestHttp->getPost('name');
            $data['PHONE'] = $this->requestHttp->getPost('phone');
            $data['EMAIL'] = $this->requestHttp->getPost('email');
            $data['DESCRIPTION'] = $this->requestHttp->getPost('description');

            $result = $errors = array();
            if (empty($data['NAME'])) {
                $err = $textesModel->getSysText('text_complain_errore_name');
                $errors[] = $err['DESCRIPTION'];
            }

            if (empty($data['DESCRIPTION'])) {
                $err = $textesModel->getSysText('text_complain_errore_description');
                $errors[] = $err['DESCRIPTION'];
            }

            if (empty($errors)) {
                $anotherPagesModel->insertData('COMPLAIN', $data);

                $doc_id = $anotherPagesModel->getPageId('/complain/');
                $message_admin = $anotherPagesModel->getDocXml($doc_id, 0, $this->lang_id);

                $message_admin = str_replace("##name##", $data['NAME'], $message_admin);
                $message_admin = str_replace("##phone##", $data['PHONE'], $message_admin);
                $message_admin = str_replace("##email##", $data['EMAIL'], $message_admin);
                $message_admin = str_replace("##description##", $data['DESCRIPTION'], $message_admin);

                $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                    . $message_admin . '</body></html>';

                $subject = $textesModel->getSysText('complain_subject', $this->lang_id);

                $to = $this->getSettingValue('complain_email');
                if ($to) {
                    $email_from = $this->getSettingValue('email_from');
                    $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
                    preg_match($patrern, $email_from, $arr);

                    $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
                    $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

                    $params = array_merge($params, $this->getMailTrasportData());

                    $manager_emails_arr = explode(";", $to);
                    if (!empty($manager_emails_arr)) {
                        $params['message'] = $message_admin;
                        $params['subject'] = $subject['DESCRIPTION'];

                        foreach ($manager_emails_arr as $mm) {
                            $mm = trim($mm);
                            if (!empty($mm)) {
                                $params['to'] = $mm;
                                Core_Controller_Action_Helper_Mailer::send($params);
                            }
                        }
                    }
                }

                $result['status'] = 'ok';
            } else {
                $result['status'] = 'fail';
                $result['errors'] = $errors;
            }

            $this->_disableRender();

            $this->_helper->json($result);
        }
    }

    /**
     * Action для проверки каптчи из форм
     */
    public function validatecaptchaAction()
    {
        if ($this->validateCaptcha()) {
            echo "true";
        } else {
            echo "false";
        }

        $this->_disableRender();
    }

    /**
     * Проверка валидности каптчи для внутренних нужд
     * То есть сразу в поток отдаем JSON с резултатом или true
     *
     * @return boolean
     */
    private function validateCaptcha()
    {
        return Core_Controller_Action_Helper_Captcha::validateCaptcha(new Zend_Controller_Request_Http());
    }

    /**
     * Запрос bp формы FAQ
     *
     * @return boolean
     */
    public function sendquestionAction()
    {
        $result = array();

        $result['status'] = 'fail';

        if ($this->validateCaptcha()) {
            $orderField = $this->getOrderFields();

            $sendResult = $this->sendEmail(
                $this->getBaseEmailBody(
                    $orderField,
                    $this->AnotherPages->getDocXml(
                        $this->AnotherPages->getPageId('/faq/send/'), 0,
                        $this->lang_id
                    )
                ),
                explode(";", $this->getSettingValue(self::EMAIL_FAQ_VAR)),
                self::FAQ_TEXT_SUBJECT
            );

            $result['status'] = 'ok';
        }

        $this->_helper->json($result);
    }

    /**
     * Отправить запрос и сохранить данные запроса в БД
     *
     * @return void
     */
    public function sendrequestAction()
    {
        // TODO: каммент нужен только для отладки - в рабочей версии убрать!
        if (!$this->validateCaptcha()) {
            return false;
        }

        $orderField = $this->getOrderFields();

//        $orderField = $this->getFixtureOrderFiels();
        // Сохраянем в БД
        $this->saveOrderInDB($orderField);


        // Письмо заказчику
        /* @var $sendResult Boolean */
        $sendResult = $this->sendUserEmail($orderField);

        // Письмо Админу
        $sendResultAdmin = $this->_sendMangersEmail($orderField);


        if ($sendResult) {
            $returnMessage['text'] = "Ваше сообщение было успешно отправлено";
            $returnMessage['result'] = true;
        } else {
            $returnMessage['text'] = "Заказ сохранён, но письмо отправить не удалось - ошибка почтового сервера";
            $returnMessage['result'] = false;
        }

        $this->_helper->json($returnMessage);
    }

    /**
     * Отправляем письмецо манагерам с уведомлением о заказе
     * тут информации по-больше чем пользователею
     *
     * @param array $orderField Детали заказа
     *
     * @return boolean Удалось или не удалось отправить
     */
    private function _sendMangersEmail($orderField)
    {
        $message = $this->AnotherPages->getDocXml( $this->AnotherPages->getPageId(self::ANOTHER_PAGES_PATH_ADMIN_ORDER), 0, $this->lang_id
        );
        $message = str_replace("##item_name##", $orderField['item_name'],$message);

        $subject = $this->getServiceManager()->getModel()->getTextes()->getSysText(self::TEXT_ORDER_ADMIN_SUBJECT, $this->lang_id);

        // Заглушка на случай если не нашли тему для данного $emailSubjectSysText
        if (!$subject) {
            $subject['DESCRIPTION'] = '';
        }

        return $this->sendEmail(
                $this->getBaseEmailBody($orderField, $message),
                explode(";", $this->getSettingValue(self::EMAIL_MANAGERS)),
                $subject['DESCRIPTION']
        );
    }

    /**
     * Отправляем письмецо заказчику с уведомлением о заказе
     *
     * @param array $orderField
     */
    private function sendUserEmail($orderField)
    {
        $message = $this->AnotherPages->getDocXml($this->AnotherPages->getPageId(self::ANOTHER_PAGES_PATH_ORDER), 0, $this->lang_id);
        $message = str_replace("##item_name##", $orderField['item_name'],$message);

        $sendResult = true;
        if (!empty($orderField['email'])) {
            $sendResult = $this->sendEmail(
                $message, array($orderField['email']), self::USER_TEXT_SUBJECT
            );
        }

        return $sendResult;
    }

    /**
     * Вернуть массив данных полученых из POST и GET
     * get вернут ID карточки товара и ID каталога
     *
     * @return array
     */
    private function getOrderFields()
    {
        $request = $this->getRequest();
        $orderField['name'] = $request->getPost('name');
        $orderField['lastname'] = $request->getPost('lastname');
        $orderField['phone'] = $request->getPost('telmob');
        $orderField['description'] = $request->getPost('description');
        $orderField['email'] = $request->getPost('email');
        $orderField['city'] = $request->getPost('city');
        $orderField['company'] = $request->getPost('company');

        if (null !== $this->getParam('item')) {
            $orderField['item_id'] = $this->getParam('item');
            $item = $this->getServiceManager()->getModel()->getCatalogue()->getItemInfo($orderField['item_id'], $this->lang_id);
            $orderField['item_name'] = $item['NAME'];
        }

        if (null !== $this->getParam('catalogue')) {
            $orderField['catalogue_id'] = $this->getParam('catalogue');
        }

        $orderField['faq_text'] = $request->getPost('faq_text');

        return $orderField;
    }

    /**
     * Ответ на запрос из формы
     *
     */
    public function sendfeedbackAction()
    {
        // TODO: каммент нужен только для отладки POST - в рабочей версии убрать!
        if (!$this->validateCaptcha())
            return false;


        // Получить тему письма
        $subject = $this->getServiceManager()->getModel()->getTextes()->getSysText(self::TEXT_FEEDBACK_SUBJECT,
            $this->lang_id);

        // Заглушка на случай если не нашли тему для данного $emailSubjectSysText
        if (!$subject)
            $subject['DESCRIPTION'] = '';

        $sendResult = $this->sendEmail(
            $this->getBaseEmailBody(
                $this->getOrderFields(),
                $this->AnotherPages->getDocXml(
                    $this->AnotherPages->getPageId(self::ANOTHER_PAGES_PATH_FEEDBACK),
                    0, $this->lang_id)),
            explode(";", $this->getSettingValue(self::EMAIL_FEEDBACK_VAR)),
            $subject['DESCRIPTION']
        );



        if ($sendResult) {
            $returnMessage['text'] = "Ваше сообщение было успешно отправлено";
            $returnMessage['result'] = true;
        } else {
            $returnMessage['text'] = "Письмо отправить не удалось - ошибка почтового сервера";
            $returnMessage['result'] = false;
        }

        echo json_encode($returnMessage);
    }

    /**
     *
     * @param type $pageUrl Получить из БД текст XML
     */
    private function getHeaderAdminLetter($pageUrl)
    {
        $doc_id = $this->AnotherPages->getPageId($pageUrl);
        return $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);
    }

    /**
     * Получить тело письма
     *
     * @param type $orderField Строки из заказа которые заменим
     * @param type $textInjection Текст из БД с плейсхолдерами
     *
     * @return type
     */
    private function getBaseEmailBody($orderField, $textInjection = "")
    {
        $textInjection = str_replace("##name##", $orderField['name'], $textInjection);
        $textInjection = str_replace("##lastname##", $orderField['lastname'], $textInjection);
        $textInjection = str_replace("##phone##", $orderField['phone'], $textInjection);
        $textInjection = str_replace("##city##", $orderField['city'], $textInjection);
        $textInjection = str_replace("##company##", $orderField['company'], $textInjection);
        $textInjection = str_replace("##email##", $orderField['email'], $textInjection);

        if (!empty($orderField['description'])) {
            $textInjection = str_replace("##description##", $orderField['description'], $textInjection);
        } elseif (!empty($orderField['faq_text'])) {
            $textInjection = str_replace("##description##",$orderField['faq_text'], $textInjection);
        } else {
            $textInjection = str_replace("##description##", '', $textInjection);
        }

        return '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
            . $textInjection . '</body></html>';
    }

    /**
     * Записать данные о заказе в БД
     *
     * @param array $orderField
     */
    private function saveOrderInDB($orderField)
    {
        $catalogueModel = $this->getServiceManager()->getModel()->getCatalogue();

        $order['NAME'] = $orderField['name'];
        $order['TELMOB'] = $orderField['phone'];
        $order['COMPANY'] = $orderField['company'];
        $order['DESCRIPTION'] = $orderField['description'];
        $order['CITY'] = $orderField['city'];
        $order['EMAIL'] = $orderField['email'];

        $order['DATA'] = date("Y-m-d H:i:s");

        $zakaz_id = $catalogueModel->insertZakaz($order);
        if ($zakaz_id && !empty($orderField['item_id'])) {

            $item = $catalogueModel->getItemInfo($orderField['item_id'], $this->lang_id);
            $zakaz_item['ZAKAZ_ID'] = $zakaz_id;
            $zakaz_item['CATALOGUE_ID'] = $orderField['catalogue_id'];
            $zakaz_item['NAME'] = $item['NAME'];
            $zakaz_item['ITEM_ID'] = $orderField['item_id'];
            $catalogueModel->insertOrder($zakaz_item);
        }
    }

    /**
     * Отпарвка мыла манагерам и админам
     *
     * @param string $message Текст сообщения
     * @param array $emails
     * @param string $subject тема письма - по умолчанию без темы
     *
     * @return boolean
     */
    private function sendEmail($message, $emails, $subject = '')
    {
        $params['attach'] = '';
        $params['name'] = '';
        $params['attach_type'] = '';

        $email_from = $this->getSettingValue('email_from');
        $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
        preg_match($patrern, $email_from, $arr);

        $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
        $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

        if (!empty($_FILES['feed_attach']['name']) && ($_FILES['feed_attach']['size'] > 0)) {
            $params['attach'] = $_FILES['feed_attach']['tmp_name'];
            $params['name'] = $_FILES['feed_attach']['name'];
            $params['attach_type'] = $_FILES['feed_attach']['type'];
        }

        $params = array_merge($params, $this->getMailTrasportData());

        foreach ($emails as $mm) {
            $mm = trim($mm);
            if (!empty($mm)) {
                $params['to'] = $mm;
                $params['message'] = $message;
                $params['subject'] = $subject;

                Core_Controller_Action_Helper_Mailer::send($params);
            }
        }
        $sendEmail = true;

        // Немного бредово - отправится только успешное уведомление последнего письма
        return $sendEmail;
    }

    public function sokobanAction()
    {
        $level_code = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $level = $request->getPost('level');
            $max_level = $this->AnotherPages->getSokobamMaxLevel();
            $level = $level > $max_level ? 1:$level;

            $level_code = $this->AnotherPages->getSokobamLevelCode($level);
            $level_code = json_decode($level_code, true);
        }

        $this->_helper->json($level_code);
    }

    public function sokobannextAction()
    {
        $result = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $level = $request->getPost('level');
            $level++;
            $max_level = $this->AnotherPages->getSokobamMaxLevel();
            $hasLevel = $level > $max_level ? false:true;

            if ($hasLevel) {
                $result['finish'] = 0;
                $text = $this->getServiceManager()->getModel()->getTextes()->getSysText('sokoban_next_level', $this->lang_id);
                $result['message'] = $text['DESCRIPTION'].'<br/> <a href="#" class="sokoban_next">Следующий</a>';
            } else {
                $result['finish'] = 1;
                $text = $this->getServiceManager()->getModel()->getTextes()->getSysText('sokoban_finish', $this->lang_id);
                $result['message'] = $text['DESCRIPTION'].'<br/> <a href="#" class="sokoban_new">Начать сначало</a>';
            }
        }

        $this->_helper->json($result);
    }
}