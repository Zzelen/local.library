<?php


namespace App\Service\Validation;


class ValidationCaptcha extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Введите капчу';
        }

        if ($_SESSION['phrase'] !== $this->param) {
            return $this->message = 'Неправильно!';
        }

        return $this->isValid = true;
    }

}