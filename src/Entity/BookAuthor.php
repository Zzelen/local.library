<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookAuthorRepository::class)
 */
class BookAuthor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="Book")
     */
    private $book;


    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="Author")
     */
    private $author;
}