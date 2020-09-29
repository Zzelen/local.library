<?php


namespace App\Service\Validation;


class ValidationPhone extends AbstractValidation
{

    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Введите номер телефона';
        }

//        TODO: Возможен ввод неправильного телефона. Доделать регу.
//              https://regex101.com/r/qF7vT8/3 для проверки реги.
//              https://habr.com/ru/post/110731/ пример подходящей реги.

        if (!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $this->param)) {
            return $this->message = 'Введите номер телефона в указанном формате';
        }

        return $this->isValid = true;
    }
}