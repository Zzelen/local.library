<?php


namespace App\Service\Validation;


use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractValidation implements ValidateInterface
{
    protected $param;
    protected $message;
    protected $isValid;
    protected $userRepository;

    public function __construct($param)
    {
        $this->param = $param;
        $this->validate();

    }

    public function getMessage()
    {
        return $this->message;
    }

    public function isValid()
    {
        return $this->isValid;
    }
}