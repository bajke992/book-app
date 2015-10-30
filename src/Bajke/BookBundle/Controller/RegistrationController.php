<?php

namespace Bajke\BookBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as FOSBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class RegistrationController extends FOSBase {

    public function confirmedAction(){
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        return new RedirectResponse($this->generateUrl('index'));
    }

}