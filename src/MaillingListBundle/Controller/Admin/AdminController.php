<?php

namespace MaillingListBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * OVHAdminController controller.
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     * @Method("GET")
     */
    public function adminAction()
    {
        return $this->render('MaillingListBundle:Admin:admin.html.twig');
    }
}
