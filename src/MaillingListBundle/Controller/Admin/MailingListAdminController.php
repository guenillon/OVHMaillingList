<?php

namespace MaillingListBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MaillingListBundle\Entity\MailingList;
use Symfony\Component\HttpFoundation\Request;
use MaillingListBundle\Form\Type\MailingListType;

/**
 * MailinglistAdmin controller.
 *
 */
class MailingListAdminController extends Controller
{
    /**
     * @Route("/mailingList/{nom}", name="mailingList", defaults={"nom":""})
     * @Method({"GET", "POST"})
     */
    public function mailingListAction(Request $request, $nom)
    {
    	$mailingList = new MailingList();
    	
    	if (empty($nom)) {
    		// Récupération de la liste actuellemet sélectionnée
    		$nom = $this->getParameter('ovh_mailing_list');
    	}
    	$mailingList->setNom($nom);
    	
    	$editForm = $this->createForm(MailingListType::class, $mailingList);
    	$editForm->handleRequest($request);
    	
    	if ($editForm->isSubmitted() && $editForm->isValid()) {
    		// Mise à jour de la mailing liste
    		$this->get('manager.parameter')->updateParameter("ovh_mailing_list", $mailingList->getNom());
    		
    		// Message Flash
    		$this->addFlash('success', 'admin.message.updateOk');
    		
    		return $this->redirectToRoute('mailingList', array('nom' => $mailingList->getNom()));
    	}
    	
    	// Récupération des mailings Liste
    	$mailingLists = $this->get('ovh')->get("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList");
    	
    	return $this->render('MaillingListBundle:Admin:mailing_list.html.twig', array(
    			"mailingLists" => $mailingLists,
    			"selectedMailingList" => $nom,
    			'edit_form' => $editForm->createView()
    	));
    }
}
