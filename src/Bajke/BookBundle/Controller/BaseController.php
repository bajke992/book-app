<?php

namespace Bajke\BookBundle\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller {

    protected function getUserByToken(Request $request){
        $tokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
        $accessToken = $tokenManager->findTokenByToken(
            $request->get("access_token")
        );
        if(!$accessToken){
            return array('error' => "invalid_grant","error_description" => "The access token provided is invalid.");
        }
        $client = $accessToken->getClient();

        return $client->getOwner();
    }

    protected function serialize($data, $format = 'json'){
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->container->get('jms_serializer')
            ->serialize($data, $format, $context);
    }

    protected function createApiResponse($data, $code = 200){
        $json = $this->serialize($data);

        return new Response($json, $code, array(
            'Content-Type' => 'application/json'
        ));
    }

    protected function checkUser(){
        $repo = $this->getDoctrine()->getRepository('BookBundle:User');

        if($this->getUser()) {
            $tmp = $this->getUser();
            $user = $repo->findOneBy(array('id' => $tmp->getId()));
        } else {
            $user = null;
        }

        return $user;
    }

    protected function flashMessage(array $arr){
        $this->get('session')->getFlashBag()->add('flash', $arr);
    }

}