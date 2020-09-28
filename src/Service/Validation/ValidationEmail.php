<?php


namespace App\Service\Validation;


class ValidationEmail extends AbstractValidation
{

    public function validate()
    {
        if (!filter_var($this->param, FILTER_VALIDATE_EMAIL)) {
            $this->message = 'Неправильно введен Email';
        }

        return $this->isValid = true;
    }

}