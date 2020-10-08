<?php


namespace App\Service;


use App\Service\Validation\ValidationBirthday;
use DateTime;
use Symfony\Component\DependencyInjection\Container;

class UsersService
{
    private $em;

    private $user;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();

    }

    public function getUsers():array
    {
        return $this->em->getRepository('App:User')->findBy([],['name' => 'ASC']);
    }

    public function getOne($params)
    {

        $result = [
          'success' => true,
          'message' => ''
        ];

        $user = $this->user;

        $params['birthday'] = new DateTime($params['birthday']);

        $user
            ->setSurname($params['surname'])
            ->setName($params['name'])
            ->setMiddlename($params['middlename'])
            ->setUpdatetime(new DateTime());

        if (isset($params['birthday'])) {
            $validationBirthday = new ValidationBirthday($params['birthday']);
            if ($validationBirthday->isValid() === false) {
                $result['message'] = $validationBirthday->getMessage();
                $result['success'] = false;
                return $result;
            }

            $user->setBirthday($params['birthday']);

        }

        $this->em->persist($user);
        $this->em->flush();

        return $result;

    }

}