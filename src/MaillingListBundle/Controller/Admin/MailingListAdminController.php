<?php

namespace MaillingListBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MaillingListBundle\Entity\MailingList;
use Symfony\Component\HttpFoundation\Request;

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
    	try
    	{
    		$mailingList = new MailingList();
    		
    		if(empty($nom)) {
	    		// Récupération de la liste actuellemet sélectionnée
	    		$nom = $this->getParameter('ovh_mailing_list');
    		}
    		$mailingList->setNom($nom);
    		
    		$editForm = $this->createForm('MaillingListBundle\Form\MailingListType', $mailingList);
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
    	catch(\InvalidArgumentException $e)
    	{
    		// Un des paramètres n'est pas définit
    		$message = $e->getMessage();
    		
    		// Affiche le message d'erreur
    		$this->addFlash('danger raw', $message);
    		
    		// Redirection vers l'admin
    		return $this->redirectToRoute('admin');
    	}
    	catch (\GuzzleHttp\Exception\ClientException $e)
    	{
    		// Exception dans la requête vers OVH
    		$message = json_decode($e->getResponse()->getBody()->getContents());
    		
    		// Affiche le message d'erreur
    		$this->addFlash('danger raw', $message->message);
    		
    		// Redirection vers l'admin
    		return $this->redirectToRoute('admin');
    	}
    }
}