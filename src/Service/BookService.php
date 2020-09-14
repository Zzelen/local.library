<?php


namespace App\Service;



use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\DependencyInjection\Container;

class BookService
{

    private $em;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');

    }


    public function getBooks(): array
    {
        return $this->em->getRepository('App:Book')->findAll();
    }

    public function createBook($params, Author $author)
    {

        if (empty(trim($params['name']))) {
            return [
                'success' => false,
                'message' => 'Имя не может быть пустым'
            ];
        }

        $book = new Book();
        $book
            ->setName($params['name'])
            ->setDate($params['date'])
            ->setAuthor($author);

        $this->em->persist($book);
        $this->em->flush();


        return $book;
    }

    public function deleteBook(Book $book)
    {
        $this->em->remove($book);
        $this->em->flush();
        return [
            'success' => true
        ];
    }

}