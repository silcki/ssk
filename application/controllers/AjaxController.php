<?php

Zend_Loader::loadClass('Captcha');
Zend_Loader::loadClass('Mailer');

/**
 * Контроллер для отработки AJAX запросов
 *
 */
class AjaxController extends CommonBaseController
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

    /**
     * Начаьлна инициализация
     * первым делом отключаем рендеринг
     *
     */
    function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        parent::init();
    }

    /**
     * Так надо что оно пустой!
     * Переписываем postDispatch из CommonBaseController чтобы избавиться от рендеринга
     * через XSLT Этот контроллер работает только с echo - view для него не делаем
     *
     */
    public function postDispatch()
    {
        // TODO: Написать общий обработчик вывода на экран собранных данных. Сейчас это полный бардка - каждый метод что хочет то и товрит!
    }

    /**
     * Action для проверки каптчи из форм
     */
    public function validatecaptchaAction()
    {
        if (Captcha::validateCaptcha(new Zend_Controller_Request_Http()))
            echo "true";
        else
            echo "false";
    }

    /**
     * Проверка валидности каптчи для внутренних нужд
     * То есть сразу в поток отдаем JSON с резултатом или true
     *
     * @return boolean
     */
    private function validateCaptcha()
    {
        $returnMessage['text'] = "";
        $returnMessage['result'] = false;
        // Проверяем на валидность каптчу
        // Если не корректна - значит взлом
        if (!Captcha::validateCaptcha(new Zend_Controller_Request_Http())) {
            $returnMessage['text'] = "Не верно введен код картинки";
            echo json_encode($returnMessage);
            return false;
        }
        return true;
    }

    /**
     * Запрос bp формы FAQ
     *
     * @return boolean
     */
    public function sendquestionAction()
    {
        // TODO: каммент нужен только для отладки - в рабочей версии убрать!
        if (!$this->validateCaptcha())
            return false;

        $orderField = $this->getOrderFields();

//        $orderField = $this->getFixtureOrderFiels();

        $subject = self::FAQ_TEXT_SUBJECT;

//        $letter_xml = $this->AnotherPages->getDocXml($this->AnotherPages->getPageId('/faq/send/'),
//                                                                                    0,
//                                                                                    $this->lang_id);

        $sendResult = $this->sendEmail(
            $this->getBaseEmailBody(
                $orderField,
                $this->AnotherPages->getDocXml(
                    $this->AnotherPages->getPageId('/faq/send/'), 0,
                    $this->lang_id
                )
            ), explode(";", $this->getSettingValue(self::EMAIL_FAQ_VAR)),
            $subject
        );
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
//        $this->_helper->json($returnMessage);
        echo json_encode($returnMessage);

//        $this->feedbacksProcess($orderField);
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

        $message = $this->AnotherPages->getDocXml(
            $this->AnotherPages->getPageId(self::ANOTHER_PAGES_PATH_ADMIN_ORDER),
            0, $this->lang_id
        );
        $message = str_replace("##item_name##", $orderField['item_name'],$message);
//        $message .= "<table cellspacing='0' cellpadding='2' border='1'>
//                    <tbody>
//                        <tr>
//                            <th>Наименование товара</th>
//                        </tr>
//                        <tr>
//                            <th>" . $orderField['item_name'] . "</th>
//                         </tr>
//                       </tbody>
//                      </table>";

        $subject = $this->Textes->getSysText(self::TEXT_ORDER_ADMIN_SUBJECT, $this->lang_id);

        // Заглушка на случай если не нашли тему для данного $emailSubjectSysText
        if (!$subject) {
            $subject['DESCRIPTION'] = '';
        }

        return $this->sendEmail(
                $this->getBaseEmailBody($orderField, $message),
                explode(";", $this->getSettingValue(self::EMAIL_MANAGERS)),
                $subject['DESCRIPTION']
        );


//        return $sendResult;
    }

    /**
     * Отправляем письмецо заказчику с уведомлением о заказе
     *
     * @param array $orderField
     */
    private function sendUserEmail($orderField)
    {
        $message = $this->AnotherPages->getDocXml($this->AnotherPages->getPageId(self::ANOTHER_PAGES_PATH_ORDER),
            0, $this->lang_id);
        $message = str_replace("##item_name##", $orderField['item_name'],$message);
//        $message .= "<table cellspacing='0' cellpadding='2' border='1'>
//                    <tbody>
//                        <tr>
//                            <th>Наименование товара</th>
//                        </tr>
//                        <tr>
//                            <th>" . $orderField['item_name'] . "</th>
//                         </tr>
//                       </tbody>
//                      </table>";

        $sendResult = true;
        if (!empty($orderField['email'])) {
            $sendResult = $this->sendEmail(
                $message, array($orderField['email']), self::USER_TEXT_SUBJECT
            );
        }

        return $sendResult;
    }

    /**
     * Метод нужен только для тестирования
     *
     * @test
     */
    private function getFixtureOrderFiels()
    {
        $http = new Zend_Controller_Request_Http();

        $orderField['name'] = "Vasya";
        $orderField['lastname'] = 'Pupkin';
        $orderField['phone'] = '234234234';
//        $orderField['description'] = 'Descript';
        $orderField['email'] = 'sdfsdf@sdfsdf.ru';
        $orderField['city'] = 'Kharkov';
        $orderField['company'] = 'KVN';

        if (null !== $http->getParam('item')) {
            $orderField['item_id'] = $this->_getParam('item');
            $item = $this->Catalogue->getItemInfo($orderField['item_id'],
                $this->lang_id);
            $orderField['item_name'] = $item['NAME'];
        }

        if (null !== $http->getParam('catalogue')) {
            $orderField['catalogue_id'] = $this->_getParam('catalogue');
        }


        $orderField['faq_text'] = "Текст ФАК";
        return $orderField;
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

        if (null !== $this->_getParam('item')) {
            $orderField['item_id'] = $this->_getParam('item');
            $item = $this->Catalogue->getItemInfo($orderField['item_id'], $this->lang_id);
            $orderField['item_name'] = $item['NAME'];
        }

        if (null !== $this->_getParam('catalogue')) {
            $orderField['catalogue_id'] = $this->_getParam('catalogue');
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
        $subject = $this->Textes->getSysText(self::TEXT_FEEDBACK_SUBJECT,
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

        if (!empty($orderField['name']))
            $textInjection = str_replace("##name##", $orderField['name'],
                $textInjection);
        else
            $textInjection = str_replace("##name##", '', $textInjection);

        if (!empty($orderField['lastname']))
            $textInjection = str_replace("##lastname##",
                $orderField['lastname'], $textInjection);
        else
            $textInjection = str_replace("##lastname##", '', $textInjection);

        if (!empty($orderField['phone']))
            $textInjection = str_replace("##phone##", $orderField['phone'],
                $textInjection);
        else
            $textInjection = str_replace("##phone##", '', $textInjection);

        if (!empty($orderField['city']))
            $textInjection = str_replace("##city##", $orderField['city'],
                $textInjection);
        else
            $textInjection = str_replace("##city##", '', $textInjection);

        if (!empty($orderField['company']))
            $textInjection = str_replace("##company##", $orderField['company'],
                $textInjection);
        else
            $textInjection = str_replace("##company##", '', $textInjection);

        if (!empty($orderField['email']))
            $textInjection = str_replace("##email##", $orderField['email'],
                $textInjection);
        else
            $textInjection = str_replace("##email##", '', $textInjection);

        if (!empty($orderField['description'])) {
            $textInjection = str_replace("##description##",
                $orderField['description'], $textInjection
            );
        } elseif (!empty($orderField['faq_text'])) {
            $textInjection = str_replace("##description##",
                $orderField['faq_text'], $textInjection
            );
        } else
            $textInjection = str_replace("##description##", '', $textInjection);


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

        $order['NAME'] = $orderField['name'];
        $order['TELMOB'] = $orderField['phone'];
        $order['COMPANY'] = $orderField['company'];
        $order['DESCRIPTION'] = $orderField['description'];
        $order['CITY'] = $orderField['city'];
        $order['EMAIL'] = $orderField['email'];

        $order['DATA'] = date("Y-m-d H:i:s");

        $zakaz_id = $this->Catalogue->insertZakaz($order);
        if ($zakaz_id && !empty($orderField['item_id'])) {
//            $zakaz_item = array();
            $item = $this->Catalogue->getItemInfo($orderField['item_id'],
                $this->lang_id);
            $zakaz_item['ZAKAZ_ID'] = $zakaz_id;
            $zakaz_item['CATALOGUE_ID'] = $orderField['catalogue_id'];
            $zakaz_item['NAME'] = $item['NAME'];
            $zakaz_item['ITEM_ID'] = $orderField['item_id'];
            $this->Catalogue->insertOrder($zakaz_item);
        }
    }

//    private function feedbacksProcess($orderField, $emailSubjectSysText)
//    {
//        $subject = $this->Textes->getSysText($emailSubjectSysText,
//                                             $this->lang_id);
//
//        // Заглушка на случай если не нашли тему для данного $emailSubjectSysText
//        if (!$subject)
//            $subject['DESCRIPTION'] = '';
//
//        return $this->sendEmail(
//                        $this->getBaseEmailBody(
//                                $orderField,
//                                $this->AnotherPages->getDocXml($this->AnotherPages->getPageId('/feedback/'),
//                                                                                              0,
//                                                                                              $this->lang_id)
//                        ),
//                                                                                              explode(";",
//                                                                                                      $this->getSettingValue('email_feedback')),
//                                                                                                                             $subject['DESCRIPTION']
//        );
//    }

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



                Mailer::send($params);
            }
        }
        $sendEmail = true;
//        print_r($sendEmail);
        // Немного бредово - отправится только успешное уведомление последнего письма
        return $sendEmail;
    }

//    private function sendMailToUser($item)
//    {
//        $zakaz_id = $this->Catalogue->insertZakaz($this->order);
//        if (!empty($this->item_id)) {
//            $zakaz_item = array();
//
//            $zakaz_item['ZAKAZ_ID'] = $zakaz_id;
//            $zakaz_item['CATALOGUE_ID'] = $item['CATALOGUE_ID'];
//            $zakaz_item['NAME'] = $item['NAME'];
//            $zakaz_item['ITEM_ID'] = $this->item_id;
//            $this->Catalogue->insertOrder($zakaz_item);
//        }
//
//        $attach = '';
//
//        $table = "<table cellspacing='0' cellpadding='2' border='1'>
//              <tbody>
//                  <tr>
//                      <th>Наименование товара</th>
//                  </tr>
//                  <tr>
//                      <th>" . $item['NAME'] . "</th>
//                   </tr>
//                 </tbody>
//                </table>";
//
//        $to = $this->order['EMAIL'];
//        $subject = 'Оформление заказа';
//        $doc_id = $this->AnotherPages->getPageId('/cat/order/');
//        $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);
//        $message = $letter_xml . $table;
//        if (!empty($to)) {
//            $this->sendMail2($to, $message, $subject);
//        }
//    }
    // FEXME: Надо убрать - через контроллер генерация каптчи работает жутко медленно
//    public function getcapthcaAction()
//    {
//        require ROOT_PATH . '/include/captcha/SimpleCaptcha.php';
//
//        $captcha = new SimpleCaptcha();
//
//        // OPTIONAL Change configuration...
//        //$captcha->wordsFile = 'words/es.php';
//        //$captcha->session_var = 'secretword';
//        //$captcha->imageFormat = 'png';
//        //$captcha->lineWidth = 3;
//        //$captcha->scale = 3; $captcha->blur = true;
//        //$captcha->resourcesPath = "/var/cool-php-captcha/resources";
//
//        $captcha->session_var = 'biz_captcha';
//        $captcha->wordsFile = "words/en.php";
//
//        // Image generation
//        ob_clean();
//        $captcha->CreateImage();
//    }

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
                $text = $this->Textes->getSysText('sokoban_next_level', $this->lang_id);
                $result['message'] = $text['DESCRIPTION'].'<br/> <a href="#" class="sokoban_next">Следующий</a>';
            } else {
                $result['finish'] = 1;
                $text = $this->Textes->getSysText('sokoban_finish', $this->lang_id);
                $result['message'] = $text['DESCRIPTION'].'<br/> <a href="#" class="sokoban_new">Начать сначало</a>';
            }
        }

        $this->_helper->json($result);
    }
}