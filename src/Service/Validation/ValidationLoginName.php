<?php


namespace App\Service\Validation;


class ValidationLoginName extends AbstractValidation
{

    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Никнейм не может быть пустым';
        }

//        if (preg_match('/[а-яёА-ЯЁ]/', $this->param)) {
//            return $this->message = '';
//        }


        return $this->isValid = true;
    }


}