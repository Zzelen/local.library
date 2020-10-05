<?php


namespace App\Service;


use DateTime;
use Symfony\Component\DependencyInjection\Container;

class UsersService
{
    private $em;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');

    }

    public function getUsers():array
    {
        return $this->em->getRepository('App:User')->findBy([],['name' => 'ASC']);
    }

    public function getOne($params)
    {
        $user = $this->em->getRepository('App:User')->find($params['id']);

        $params['birthday'] = new DateTime($params['birthday']);

        $user
            ->setSurname($params['surname']);


        return $user;

    }

}