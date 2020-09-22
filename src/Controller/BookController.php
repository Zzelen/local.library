<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Service\BookService;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{

    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @Template()
     * @Route ("/books")
     */
    public function booksAction()
    {
        $books = $this->bookService->getBooks();
        return ['books' => $books];
    }


    /**
     * @Template ()
     * @Route ("/author/{id}/book/create")
     * @param Request $request
     * @param Author $author
     * @return array|JsonResponse|RedirectResponse
     * @throws EntityNotFoundException
     */
    public function createBookAction(Request $request, Author $author)
    {
        if ($request->getMethod() === 'POST') {
            $result = $this->bookService->createBook($request->request->all(), $author);
            return new JsonResponse($result);
        }

        return [
            'author' => $author
        ];

    }

    /**
     * @Route ("book/{id}/delete")
     * @param Request $request
     * @param Book $book
     * @return RedirectResponse
     */
    public function deleteBookAction(Request $request, Book $book)
    {
        $result = $this->bookService->deleteBook($book);

        if (!$result['success']) {
            return $this->redirectToRoute('app_author_authors');
        }

        return $this->redirectToRoute('app_author_authorpage', ['id' => $book->getAuthor()->getId()]);
    }


    /**
     * @Template ()
     * @Route ("/author/{author}/book/{book}/edit")
     * @param Request $request
     * @param Author $author
     * @param Book $book
     * @return Author[]|JsonResponse
     * @throws EntityNotFoundException
     */
    public function editBookAction(Request $request, Author $author, Book $book)
    {

        if ($request->getMethod() === 'POST') {
            $params = $request->request->all();
            $params['id'] = $book->getId();

            $result = $this->bookService->createBook($params, $author, false );
            return new JsonResponse($result);
        }
        return [
            'author' => $author,
            'book' => $book
        ];

    }

    /**
     * @Template ()
     * @Route ("/books/all")
     * @return array
     */
    public function allBooksAction(): array
    {
        $booksArray = $this->bookService->getABCBooks();
        return [
            'booksArray' => $booksArray
        ];
    }

}