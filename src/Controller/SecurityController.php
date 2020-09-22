<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $em;
    private $passwordEncoder;

    public function __construct(Container $container, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//         if ($this->getUser()) {
//             return $this->redirectToRoute('app_book_books');
//         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Template ()
     * @Route ("/register")
     * @param Request $request
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        $success = false;
        $errorMsg = [];

        if ($request->getMethod() === 'POST') {
            $params = $request->request->all();

            if (empty($params['loginName'])) {
                $errorMsg['loginName'] = 'Заполните поле Никнейм';
            }

            if (empty($params['email'])) {
                $errorMsg['email'] = 'Заполните поле электронная почта';
            }

            if (empty($params['password'])) {
                $errorMsg['password'] = 'Введите пароль';
            }

            if ($params['password'] !== $params['repeatedPassword']) {
                $errorMsg['repeatedPassword'] = 'Неверно введен пароль';
            }

            if (empty($params['surname'])) {
                $errorMsg['surname'] = 'Заполните поле Фамилия';
            }

            if (empty($params['name'])) {
                $errorMsg['name'] = 'Заполните поле Имя';
            }

            if (empty($params['middlename'])) {
                $errorMsg['middlename'] = 'Заполните поле Отчество';
            }

            if (empty($params['birthday'])) {
                $errorMsg['birthday'] = 'Заполните поле Дата рождения';
            }

            if (empty($params['phone'])) {
                $errorMsg['phone'] = 'Заполните поле Номер телефона';
            }

            $params['birthday'] = new \DateTime($params['birthday']);

            if (empty($errorMsg)) {

//                $user = new User();
//
//                $user
//                    ->setLoginName($params['loginName'])
//                    ->setEmail($params['email'])
//                    ->setPassword($this->passwordEncoder->encodePassword($user, $params['password']))
//                    ->setSurname($params['surname'])
//                    ->setName($params['name'])
//                    ->setMiddlename($params['middlename'])
//                    ->setBirthday($params['birthday'])
//                    ->setPhone($params['phone'])
//                    ->setRoles(["ROLE_USER"])
//                    ->setCreationTime(new \DateTime())
//                    ->setActivate(1)
//                    ->setHidden(0);
//
//                $this->em->persist($user);
//                $this->em->flush();

                $success = true;
            }
            return new JsonResponse([
                'errorMsg' => $errorMsg,
                'success' => $success
            ]);
        }
        return [];
    }
}
