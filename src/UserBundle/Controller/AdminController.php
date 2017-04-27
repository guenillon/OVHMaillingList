<?php
namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use UserBundle\Form\DeleteType;
use UserBundle\Form\UserProfileFormType;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/user")
 */
class AdminController extends Controller
{
	/**
	 * @Route("/", name="jpi_liste_user")
	 * @Method({"GET"})
	 */
    public function listeUserAction()
    {        
    	$userManager = $this->get('fos_user.user_manager');
    	$lListeUsers = $userManager->findUsers();
    	
        return $this->render('UserBundle:Admin:ListeUser.html.twig', array(
      		'liste_users' => $lListeUsers
   		));
    }
    
    /**
     * @Route("/{id}", name="jpi_show_user_profile", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function showAction(User $user, $id)
    {
    	$form = $this->createForm(DeleteType::class, $user, array('method' => 'DELETE'));
    	return $this->render('UserBundle:Admin:show.html.twig', array(
    			'user' => $user,
    			'formDelete' => $form->createView()
    	));
    }
    
    /**
     * @Route("/{id}/edit", name="jpi_user_edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request, $id)
    {
    	
    	$form = $this->createForm(UserProfileFormType::class, $user, array('user' => $this->getUser()));
    	$form->handleRequest($request);
    	
    	if ($form->isSubmitted() && $form->isValid()) {
        	
        	// L'admin actuel ne peut pas modifier ses propores droits et redevenir simple utilisateur
        	$currentUser= $this->getUser();
        	if($currentUser->getId() == $user->getId()) {
        		$user->setRoles($currentUser->getRoles());
        	}
        	
        	$userManager = $this->get('fos_user.user_manager');
        	$userManager->updateUser($user);
        	
        	$this->addFlash('fos_user_success', 'profile.flash.updated');
            return $this->redirect($this->generateUrl('jpi_show_user_profile', array('id' => $id)));
        }

        return $this->render('FOSUserBundle:Admin:editUser.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }
    
    /**
     * @Route("/{id}/delete", name="jpi_user_delete", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, User $user, $id)
    {
    	$currentUser= $this->getUser();
    	
    	if($currentUser == $user) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	
    	$form = $this->createForm(DeleteType::class, $user, array('method' => 'DELETE'));
    	
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    	
    		$userManager = $this->get('fos_user.user_manager');
	    	$userManager->deleteUser($user);
	    	$this->addFlash('success', 'user.admin.flash.success');
	    	return $this->redirect($this->generateUrl('jpi_liste_user'));
    	}
    	
    	return $this->render('UserBundle:Admin:delete.html.twig', array(
    			'user' => $user,
    			'formDelete' => $form->createView()
    	));
    }
    
    /**
     * @Route("/add", name="jpi_user_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
    	/** @var $formFactory FactoryInterface */
    	$formFactory = $this->get('fos_user.registration.form.factory');
    	/** @var $userManager UserManagerInterface */
    	$userManager = $this->get('fos_user.user_manager');
    	/** @var $dispatcher EventDispatcherInterface */
    	$dispatcher = $this->get('event_dispatcher');
    	
    	$user = $userManager->createUser();
    	$user->setEnabled(true);
    	
    	$event = new GetResponseUserEvent($user, $request);
    	$dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
    	
    	if (null !== $event->getResponse()) {
    		return $event->getResponse();
    	}
    	
    	$form = $formFactory->createForm();
    	$form->setData($user);
    	
    	$form->handleRequest($request);
    	
    	if ($form->isSubmitted()) {
    		if ($form->isValid()) {
    			$event = new FormEvent($form, $request);
    			$dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
    			
    			$userManager->updateUser($user);
    			
    			if (null === $response = $event->getResponse()) {
    				$url = $this->generateUrl('jpi_show_user_profile',array("id" => $user->getId()));
    				$response = new RedirectResponse($url);
    			}
    			
    			return $response;
    		}
    		
    		$event = new FormEvent($form, $request);
    		$dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
    		
    		if (null !== $response = $event->getResponse()) {
    			return $response;
    		}
    	}
    	
    	return $this->render('UserBundle:Registration:register.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
}
