<?php

namespace Bajke\BookBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends BaseController {

    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction(){
        $user = $this->checkUser();
        if(!$user){ return new RedirectResponse($this->generateUrl('index')); }

        $clients = $user->getClients();

        return array('clients' => $clients);
    }

    /**
     * @Route("/profile/client", name="create_client")
     *
     */
    public function createClientAction(){
        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array(''));
        $client->setAllowedGrantTypes(array('client_credentials', 'password'));
        $client->setOwner($this->get('security.token_storage')->getToken()->getUser());
        $clientManager->updateClient($client);

        $this->flashMessage(array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Successfully created new Client!'
        ));

        return new RedirectResponse($this->generateUrl('profile'));
    }

    /**
     * @Route("/profile/delete")
     *
     */
    public function clientsDeleteAction(Request $request) {
        $client_id  = $request->get("id");
        $client = $this->getDoctrine()
            ->getRepository('BookBundle:Client')
            ->find($client_id);
        if ($client->getOwner()->getId() !== $this->get('security.token_storage')->getToken()->getUser()->getId())
            throw new AccessDeniedException('Invalid access');
        $em = $this->getDoctrine()->getManager();

        $tokens = $this->getDoctrine()
            ->getRepository('BookBundle:AccessToken')
            ->findBy(array('client' => $client));
        foreach ($tokens as $token) {
            $em->remove($token);
        }
        $em->flush();

        $refreshTokens = $this->getDoctrine()
            ->getRepository('BookBundle:RefreshToken')
            ->findBy(array('client' => $client));
        foreach ($refreshTokens as $token) {
            $em->remove($token);
        }
        $em->flush();

        $em->remove($client);
        $em->flush();

        $this->flashMessage(array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Successfully deleted Client!'
        ));

        return new RedirectResponse($this->generateUrl('profile'));
    }
}
