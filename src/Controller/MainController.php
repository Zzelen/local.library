<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Template()
     * @Route ("/")
     */
    public function indexAction()
    {
        return [];
    }


    /**
     * @Template ()
     * @Route ("/news")
     * @return array
     */
    public function newsAction()
    {
        return [];
    }


    /**
     * @Template ()
     * @Route ("/about")
     * @return array
     */
    public function aboutAction()
    {
        return [];
    }


}