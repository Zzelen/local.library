<?php


namespace App\Service;


use App\Entity\Author;
use DateTime;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\Container;

class AuthorService
{
    private $em;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function getAuthors(): array
    {
        return $this->em->getRepository('App:Author')->findAll();
    }

    public function getAuthorById($id)
    {
        return $this->em->getRepository('App:Author')->find($id);
    }

    public function editAuthor($params, $newAuthor = true): Author
    {
        $params = [
            'id' => isset($params['id']) ? trim($params['id']) : '',
            'name' => $params['name'] ?? '',
            'surname' => $params['surname'] ?? '',
            'middlename' => $params['middlename'] ?? '',
            'birthday' => $params['birthday'] ?? '',
            'deathday' => $params['deathday'] ?? '',
            'content' => $params['content'] ?? ''
        ];

//        $result = $this->validateAuthor($params);

        if ($newAuthor) {
            $author = new Author();
        } else {
            $author = $this->em->getRepository('App:Author')->find($params['id']);

            if (!$author) {
                throw new EntityNotFoundException('Автор не найден ' . $params['id']);
            }
        }

        if (empty($params['birthday'])) {
            throw new \RuntimeException('Неправильная дата рождения');
        }
        $params['birthday'] = new DateTime($params['birthday']);

        if (empty($params['deathday'])) {
            throw new \RuntimeException('Неправильная дата смэрти');
        }
        $params['deathday'] = new DateTime($params['deathday']);


        $author
            ->setSurname($params['surname'])
            ->setMiddlename($params['middlename'])
            ->setName($params['name'])
            ->setBirthday($params['birthday'])
            ->setDeathday($params['deathday'])
            ->setContent($params['content']);

        $this->em->persist($author);
        $this->em->flush();

        return $author;

    }

    public function deleteAuthor(Author $author)
    {
        $this->em->remove($author);
        $this->em->flush();
        return [
            'success' => true
        ];
    }
}