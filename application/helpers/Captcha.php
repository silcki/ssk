<?php

/**
 * Проверка валидности и генерация каптчи
 *
 * @author Ruslan Bocharov <helcy1@ya.ru>
 */
class Captcha
{

    const RANDOM_LENGTH = 5;
    const POST_CAPTCHA_NAME = 'captcha';

    private static $_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    /**
     *
     * @param Zend_Controller_Request_Http $httpHeaders
     * @return boolean
     * @throws Exception
     */
    static function validateCaptcha(Zend_Controller_Request_Http $httpHeaders)
    {

        try {

            // Если в POST нет параметра captcha то валидацию не провести - это ошибка
            if (!$httpHeaders->getPost(self::POST_CAPTCHA_NAME))
                throw new Exception('You should take a "captcha" into Post');

            if ($_SESSION[SESSION_CAPTCHA_VAR_NAME] === $httpHeaders->getPost(self::POST_CAPTCHA_NAME))
                return true;

            // Сменим код в сессии для каптчи в сессии сразу после валидации
            // Иначе можно будет долбать через curl пока не подберёт
            $_SESSION[SESSION_CAPTCHA_VAR_NAME] = substr(str_shuffle(self::$_chars),
                                                                     0,
                                                                     self::RANDOM_LENGTH);
            return false;
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

}