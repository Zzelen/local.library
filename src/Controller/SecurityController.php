<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Validation\ValidationBirthday;
use App\Service\Validation\ValidationEmail;
use App\Service\Validation\ValidationLoginName;
use App\Service\Validation\ValidationName;
use App\Service\Validation\ValidationPassword;
use App\Service\Validation\ValidationPhone;
use App\Service\Validation\ValidationSurname;
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

            $result = $this->validate($params);
            $errorMsg = $result['result'];


            $params['birthday'] = new \DateTime($params['birthday']);

            if ($result['status']) {

                $user = new User();

                dump($user);

//                TODO: UCWORDS не работает с кириллицей. пофиксить
                $user
                    ->setLoginName($params['loginName'])
                    ->setEmail($params['email'])
                    ->setPassword($this->passwordEncoder->encodePassword($user, $params['password']))
                    ->setSurname(ucwords(mb_strtolower($params['surname'])))
                    ->setName(ucwords(mb_strtolower($params['name'])))
                    ->setMiddlename(ucwords(mb_strtolower($params['middlename'])))
                    ->setBirthday($params['birthday'])
                    ->setPhone($params['phone'])
                    ->setRoles(["ROLE_USER"])
                    ->setCreationTime(new \DateTime())
                    ->setActivate(1)
                    ->setHidden(0);

                $this->em->persist($user);
                $this->em->flush();

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
        $result = [
            'loginName' => '',
            'email' => '',
            'password' => '',
            'repeatedPassword' => '',
            'surname' => '',
            'name' => '',
            'middlename' => '',
            'birthday' => '',
            'phone' => ''
        ];


        $validationLoginName = new ValidationLoginName($params['loginName']);
        if ($validationLoginName->IsValid() === false) {
            $result['loginName'] = $validationLoginName->getMessage();
            $status = false;
        }

        if ($this->uniqueValidateLoginName($params['loginName'])) {
            $result['loginName'] = 'Пользователь с таким Ником уже существует';
            $status = false;
        }


        $validationEmail = new ValidationEmail($params['email']);
        if ($validationEmail->isValid() === false) {
            $result['email'] = $validationEmail->getMessage();
            $status = false;
        }

        if ($this->uniqueValidateEmail($params['email'])) {
            $result['email'] = 'Пользователь с таким Email уже существует';
            $status = false;
        }


        $passwords = [
            'password' => $params['password'],
            'repeatedPassword' => $params['repeatedPassword']
        ];

        $validationPassword = new ValidationPassword($passwords);
        if ($validationPassword->isValid() === false) {
            $result['password'] = $validationPassword->getMessage();
            $status = false;
        }


        $validationSurname = new ValidationSurname($params['surname']);
        if ($validationSurname->isValid() === false) {
            $result['surname'] = $validationSurname->getMessage();
            $status = false;
        }


        $validationName = new ValidationName($params['name']);
        if ($validationName->isValid() === false) {
            $result['name'] = $validationName->getMessage();
            $status = false;
        }


        $validationBirthday = new ValidationBirthday($params['birthday']);
        if ($validationBirthday->isValid() === false) {
            $result['birthday'] = $validationBirthday->getMessage();
            $status = false;
        }


        $validationPhone = new ValidationPhone($params['phone']);
        if ($validationPhone->isValid() === false) {
            $result['phone'] = $validationPhone->getMessage();
            $status = false;
        }


        return [
            'result' => $result,
            'status' => $status ?? true
        ];
    }
    protected function uniqueValidateLoginName($loginName)
    {
//        TODO: Проверить какого пользователя берет из БД если разный регистр.
        /** @var UserRepository $userRep */
        $userRep = $this->em->getRepository('App:User');
        /** @var User $currentUser */
        $currentUser = $userRep->findOneByLoginName($loginName);
        if ($currentUser && ($currentUser->getLoginName() === $loginName)) {
            return true;
        }
        return false;
    }

    protected function uniqueValidateEmail($email)
    {
        $userRep = $this->em->getRepository('App:User');
        /** @var User $currentUser */
        $currentUser = $userRep->findOneByEmail($email);
        if ($currentUser) {
            return true;
        }
        return false;
    }
}
