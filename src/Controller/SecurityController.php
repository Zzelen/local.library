<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Validation\ValidationLoginName;
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

            $params = [
                'loginName' => trim($request->get('loginName')) ?? '',
                'email' => trim($request->get('email')) ?? '',
                'password' => trim($request->get('password')) ?? '',
                'repeatedPassword' => trim($request->get('repeatedPassword')) ?? '',
                'surname' => trim($request->get('surname')) ?? '',
                'name' => trim($request->get('name')) ?? '',
                'middlename' => trim($request->get('middlename')) ?? '',
                'birthday' => $request->get('birthday') ?? '',
                'phone' => trim($request->get('phone')) ?? ''
            ];

            $errorMsg = $this->validate($params);


            if (empty(trim($params['email']))) {
                $errorMsg['email'] = 'Заполните поле электронная почта';
            } elseif (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
                $errorMsg['email'] = 'Неправильно введен email';
            }


            if (empty($params['password'])) {
                $errorMsg['password'] = 'Введите пароль';
            }


            if ($params['password'] !== $params['repeatedPassword']) {
                $errorMsg['repeatedPassword'] = 'Пароли не совпадают';
            }

            if (empty(trim($params['surname']))) {
                $errorMsg['surname'] = 'Заполните поле Фамилия';
            }

            if (empty(trim($params['name']))) {
                $errorMsg['name'] = 'Заполните поле Имя';
            }

            if (empty(trim($params['middlename']))) {
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

    private function validate($params)
    {
        $result = [];
        $validationLoginName = new ValidationLoginName($params['loginName']);
        if ($validationLoginName->IsValid() === false) {
            $result['loginName'] = $validationLoginName->getMessage();
        }

        if ($this->uniqueValidate($params['loginName'])) {
            $result['loginName'] = 'Пользователь с таким Ником уже существует';
        }


        return $result;
    }
    protected function uniqueValidate($loginName)
    {
        /** @var UserRepository $userRep */
        $userRep = $this->em->getRepository('App:User');
        /** @var User $currentUser */
        $currentUser = $userRep->findOneByLoginName($loginName);
        if ($currentUser && ($currentUser->getLoginName() === $loginName)) {
            return true;
        }
        return false;
    }
}
