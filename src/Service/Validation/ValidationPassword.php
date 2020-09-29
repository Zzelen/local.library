<?php


namespace App\Service\Validation;


class ValidationPassword extends AbstractValidation
{

    public function validate()
    {

        if (empty($this->param['password'])) {
            return $this->message = 'Пароль не может быть пустым';
        }


        if ((!preg_match('/[1-9]+/u', $this->param['password'])) || (!preg_match('/[a-zа-яё]+/u', $this->param['password'])) || (!preg_match('/[A-ZА-ЯЁ]+/u', $this->param['password']))) {
            return $this->message = 'Пароль не соответствует указанным требованиям';
        }

        if (empty($this->param['repeatedPassword'])) {
            return $this->message = 'Введите пароль повторно';
        }

        if ($this->param['password'] !== $this->param['repeatedPassword']) {
            return $this->message = 'Пароли не совпадают';
        }


        return $this->isValid = true;
    }
}