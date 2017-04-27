<?php

namespace MaillingListBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use \Ovh\Api;

/**
 * OVHAdminController controller.
 *
 */
class OVHAdminController extends Controller
{
    /**
     * @Route("/ovh", name="ovh")
     * @Method("GET")
     */
    public function ovhAction()
    {
        try {
            // Les paramètres pour demander le token OVH
            $EN = $this->getParameter('ovh_endpoint_name');
            $AK = $this->getParameter('ovh_application_key');
            $AS = $this->getParameter('ovh_application_secret');
            
            // Les droits
            $rights = array( (object) [
                    'method'    => 'POST',
                    'path'      => '/email/domain*'
            ], [
                    
                    'method'    => 'DELETE',
                    'path'      => '/email/domain*'
            ], [
                    
                    'method'    => 'GET',
                    'path'      => '/email/domain*'
            ]);
            
            // Récupération des accès
            $conn = new Api($AK, $AS, $EN);
            $redirection = $this->generateUrl('ovhCredential', array(), UrlGeneratorInterface::ABSOLUTE_URL);
            $credentials = $conn->requestCredentials($rights, $redirection);
            
            // Sauvegarde de la consumer key et redirection vers la page d'authentification
            $this->container->get('session')->set('consumer_key', $credentials["consumerKey"]);
            
            return $this->redirect($credentials["validationUrl"]);
        } catch (\InvalidArgumentException $e) {
            // Un des paramètres n'est pas définit
            $message = $e->getMessage();
            
            // Affiche le message d'erreur
            $this->addFlash('danger raw', $message);
            
            // Redirection vers l'admin
            return $this->redirectToRoute('admin');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Exception dans la requête vers la page d'authentification
            $message = json_decode($e->getResponse()->getBody()->getContents());
            
            // Affiche le message d'erreur
            $this->addFlash('danger raw', $message->message);
            
            // Redirection vers l'admin
            return $this->redirectToRoute('admin');
        }
    }
    
    /**
     * @Route("/ovhCredential", name="ovhCredential")
     * @Method("GET")
     */
    public function ovhCredentialAction()
    {
        // Récuparation de la consumer KEY
        $CK = $this->container->get('session')->get('consumer_key');
        
        if (!is_null($CK)) { // Si la consumer key est bien présente
            // Enregistrement de la clé
            $this->get('manager.parameter')->updateParameter("ovh_consumer_key", $CK);
            // Message Flash
            $this->addFlash('success', 'admin.message.ohvLinkOk');
        } else {
            // Message Flash
            $this->addFlash('danger', 'admin.message.ohvLinkKo');
        }
        
        // Redirection vers l'accueil de l'admin
        return $this->redirectToRoute('admin');
    }
}
