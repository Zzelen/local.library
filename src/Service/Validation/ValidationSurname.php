<?php


namespace App\Service\Validation;


class ValidationSurname extends AbstractValidation
{

    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Фамилия не может быть пустой';
        }

        return $this->isValid = true;
    }
}