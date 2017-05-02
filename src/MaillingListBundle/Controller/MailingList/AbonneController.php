<?php

namespace MaillingListBundle\Controller\MailingList;

use MaillingListBundle\Entity\Abonne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    	// Récupération des abonnes
    	$abonnes = $this->get('ovh')->get("/email/domain/" . $this->getParameter('ovh_domain') . "/mailingList/" . $this->getParameter('ovh_mailing_list') . "/subscriber");

    	return $this->render('MaillingListBundle:MailingList:index.html.twig', array(
            'abonnes' => $abonnes,
        ));
    }

    /**
     * Creates a new abonne entity.
     *
     * @Route("/new", name="abonne_new")
     * @Method({"GET", "POST"})
     */
 /*   public function newAction(Request $request)
    {
        $abonne = new Abonne();
        $form = $this->createForm('MaillingListBundle\Form\AbonneType', $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abonne);
            $em->flush();

            return $this->redirectToRoute('abonne_show', array('id' => $abonne->getId()));
        }

        return $this->render('abonne/new.html.twig', array(
            'abonne' => $abonne,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a abonne entity.
     *
     * @Route("/{id}", name="abonne_show")
     * @Method("GET")
     */
 /*   public function showAction(Abonne $abonne)
    {
        $deleteForm = $this->createDeleteForm($abonne);

        return $this->render('abonne/show.html.twig', array(
            'abonne' => $abonne,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing abonne entity.
     *
     * @Route("/{id}/edit", name="abonne_edit")
     * @Method({"GET", "POST"})
     */
 /*   public function editAction(Request $request, Abonne $abonne)
    {
        $deleteForm = $this->createDeleteForm($abonne);
        $editForm = $this->createForm('MaillingListBundle\Form\AbonneType', $abonne);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonne_edit', array('id' => $abonne->getId()));
        }

        return $this->render('abonne/edit.html.twig', array(
            'abonne' => $abonne,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a abonne entity.
     *
     * @Route("/{id}", name="abonne_delete")
     * @Method("DELETE")
     */
 /*   public function deleteAction(Request $request, Abonne $abonne)
    {
        $form = $this->createDeleteForm($abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abonne);
            $em->flush();
        }

        return $this->redirectToRoute('abonne_index');
    }

    /**
     * Creates a form to delete a abonne entity.
     *
     * @param Abonne $abonne The abonne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
 /*   private function createDeleteForm(Abonne $abonne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('abonne_delete', array('id' => $abonne->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }*/
}
