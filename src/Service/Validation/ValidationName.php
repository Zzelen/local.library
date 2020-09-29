<?php


namespace App\Service\Validation;


class ValidationName extends AbstractValidation
{

    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Имя не может быть пустым';
        }

        return $this->isValid = true;
    }
}