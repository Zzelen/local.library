<?php


namespace App\Service\Validation;


class ValidationEmail extends AbstractValidation
{

    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Email не может быть пустым';
        }

        if (!filter_var($this->param, FILTER_VALIDATE_EMAIL)) {
            $this->message = 'Неправильно введен Email';
        }

        return $this->isValid = true;
    }

}