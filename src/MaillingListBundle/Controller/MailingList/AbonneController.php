<?php

namespace MaillingListBundle\Controller\MailingList;

use MaillingListBundle\Entity\Abonne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use MaillingListBundle\Form\Type\AbonneType;

/**
 * Abonne controller.
 *
 */
class AbonneController extends Controller
{
    /**
     * Lists all abonne entities.
     *
     * @Route("/", name="home")
     * @Method("GET")
     */
	public function indexAction()
    {    
    	$deleteForm = $this->createForm(AbonneType::class, new Abonne(), array('method' => 'DELETE'));
    	$mailingList = $this->getParameter('ovh_mailing_list');
    	
	    // Récupération des abonnes
    	$abonnes = $this->get('ovh')->get("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $mailingList . "/subscriber");

    	return $this->render('MaillingListBundle:MailingList:index.html.twig', array(
            'abonnes' => $abonnes,
    		'mailingList' => $mailingList,
    		'formDelete' => $deleteForm->createView()
        ));
    }

    /**
     * Creates a new abonne entity.
     *
     * @Route("/add", name="abonne_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	// Récupération du mail
        	$email = array('email' => $abonne->getMail());
        	$this->get('ovh')->post("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $this->getParameter('ovh_mailing_list') . "/subscriber", $email);

        	// Affiche le message
        	$this->addFlash('success', 'mailingList.mail.addOk');

        	// Redirection vers home
        	return $this->redirectToRoute('home');
        }

        return $this->render('MaillingListBundle:MailingList:new.html.twig', array(
            'abonne' => $abonne,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing abonne entity.
     *
     * @Route("/edit/{mail}", name="abonne_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $mail)
    {
    	$abonne = new Abonne();
    	$abonne->setMail($mail);
    	
        $editForm = $this->createForm(AbonneType::class, $abonne);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
        	// Suppression de l'ancien mail
        	$this->get('ovh')->delete("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $this->getParameter('ovh_mailing_list') . "/subscriber/" . $mail);
        	// Ajout du nouveau mail
        	$this->get('ovh')->post("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $this->getParameter('ovh_mailing_list') . "/subscriber", array('email' => $abonne->getMail()));

        	// Affiche le message
        	$this->addFlash('success', 'mailingList.mail.editOk');
        	
            return $this->redirectToRoute('home');
        }

        return $this->render('MaillingListBundle:MailingList:edit.html.twig', array(
            'abonne' => $abonne,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a abonne entity.
     *
     * @Route("/", name="abonne_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
    	$abonne = new Abonne();
    	$form = $this->createForm(AbonneType::class, $abonne, array('method' => 'DELETE'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	// Suppression du mail
        	$this->get('ovh')->delete("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $this->getParameter('ovh_mailing_list') . "/subscriber/" . $abonne->getMail());
        	// Affiche le message
        	$this->addFlash('success', 'mailingList.mail.deleteOk');
        }

        return $this->redirectToRoute('home');
    }
}
