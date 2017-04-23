<?php

namespace MaillingListBundle\Controller\MailingList;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccesController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('MaillingListBundle:Default:index.html.twig');
    }
}
