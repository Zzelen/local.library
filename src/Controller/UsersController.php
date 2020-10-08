<?php


namespace App\Controller;


use App\Entity\User;
use App\Service\UsersService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    public $usersService;

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    /**
     * @Template ()
     * @Route ("/users")
     */
    public function allUsersAction()
    {
        $users = $this->usersService->getUsers();
        return [
            'users' => $users
        ];
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array|JsonResponse|RedirectResponse
     * @Template ()
     * @Route ("/profile/{id}")
     */
    public function oneAction(Request $request, User $user)
    {
        if ($request->getMethod() === 'POST') {
            $params = $request->request->all();
            $params['id'] = $user->getId();
            $result = $this->usersService->getOne($params);

            return new JsonResponse($result);
        }

        return [
            'user' => $user
        ];
    }


}