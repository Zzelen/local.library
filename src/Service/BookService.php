<?php


namespace App\Service;



use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityNotFoundException;
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
        return $this->em->getRepository('App:Book')->findBy([],['name' => 'ASC']);
    }

    public function createBook($params, Author $author, $newBook = true)
    {
        if ($newBook) {
            $book = new Book();
        } else {
            $book = $this->em->getRepository('App:Book')->find($params['id']);
            if (!$book) {
                throw new EntityNotFoundException('Книга не найдена', $params['id']);
            }
        }

        if (empty(trim($params['name']))) {
            return [
                'success' => false,
                'message' => 'Имя не может быть пустым'
            ];
        }


        $book
            ->setName($params['name'])
            ->setDate($params['date'])
            ->setAuthor($author);

        $this->em->persist($book);
        $this->em->flush();


        return [
            'success' => true
        ];
    }

    public function deleteBook(Book $book)
    {
        $this->em->remove($book);
        $this->em->flush();
        return [
            'success' => true
        ];
    }

    public function getABCBooks()
    {
        $books = $this->getBooks();
        $booksArray = [];
        /** @var Book $book */
        foreach ($books as $book) {
            $name = $book->getName();
            preg_match('/^[\\S]{1}/iu', $name, $match);
            if (preg_match('/^[0-9]{1}/iu', $match[0], $match1)) {
                $booksArray['1-9'][] = $book;
            } elseif (preg_match('/^[A-Za-zА-Яа-яЁё]{1}/iu', $match[0], $match2)) {
                $booksArray[mb_strtoupper($match[0])][] = $book;
            } else {
                $booksArray['#'][] = $book;
            }
        }

        return $booksArray;
    }

}