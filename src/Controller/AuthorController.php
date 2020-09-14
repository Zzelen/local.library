<?php


namespace App\Controller;


use App\Entity\Author;
use App\Service\AuthorService;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @Template ()
     * @Route ("/authors")
     * @param Request $request
     * @return array
     */
    public function authorsAction(Request $request)
    {
        $authors = $this->authorService->getAuthors();
        return ['authors' => $authors];
    }

    /**
     * @Template ()
     * @Route ("/author/create")
     * @param Request $request
     * @return array|RedirectResponse
     * @throws EntityNotFoundException
     */
    public function createAction(Request $request)
    {
        if ($request->getMethod() === 'POST' && $request->get('submit')) {
            $author = $this->authorService->editAuthor($request->request->all(), true);
            if ($author) {
                return $this->redirectToRoute('app_author_authorpage', ['id' => $author->getId()]);
            }
        }

        return [
            'author' => $author ?? '',
//            'message' => $message ?? ''
        ];
    }


    /**
     * @param Request $request
     * @param Author $author
     * @return RedirectResponse
     * @Route ("/author/{id}/delete")
     */
    public function deleteAction(Request $request, Author $author): RedirectResponse
    {
        $result = $this->authorService->deleteAuthor($author);

        if (!$result['success']) {
            return $this->redirectToRoute('app_author_authorpage', ['id' => $author->getId()]);
        }
        return $this->redirectToRoute('app_author_authors');

    }

    /**
     * @Template ()
     * @Route ("/author/{id}/edit")
     * @param Request $request
     * @param Author $author
     * @return array|RedirectResponse
     * @throws EntityNotFoundException
     */
    public function editAction(Request $request, Author $author)
    {
        if ($request->getMethod() === 'POST' && $request->get('submit')) {
            $params = $request->request->all();
            $params['id'] = $author->getId();
            $author = $this->authorService->editAuthor($params,false);
            if ($author) {
                return $this->redirectToRoute('app_author_authorpage', ['id' => $author->getId()]);
            }
        }

        return [
            'author' => $author
        ];
    }

    /**
     * @Template ()
     * @Route ("/author/{id}")
     * @param Request $request
     * @param Author $author
     * @return array
     */
    public function authorPageAction(Request $request, Author $author)
    {
        return ['author' => $author];
    }




}