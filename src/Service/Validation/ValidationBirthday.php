<?php


namespace App\Service\Validation;


use DateTime;

class ValidationBirthday extends AbstractValidation
{
    private const HOUR = 60 * 60;
    private const DAY = self::HOUR * 24;
    private const YEAR = self::DAY * 364.75; // Для нивелирования високосного года
    protected const YEARS_OLD_LIMIT = self::YEAR * 150;



    public function validate()
    {


        $birthday = new DateTime($this->param);

        if (empty($this->param)) {
            return $this->message = 'Заполните дату рождения';
        }

        if (date_timestamp_get($birthday) >= time()) {
            return $this->message = 'Это БУДУЩЕЕ';
        }

        if (time() - date_timestamp_get($birthday) >= round(self::YEARS_OLD_LIMIT)) {
            return $this->message = 'Люди столько не живут';
        }

        return $this->isValid = true;
    }
}