<?php

namespace Bajke\BookBundle\AuthProviders;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class FOSUBUserProvider extends BaseClass {

    protected $session;

    public function __construct(UserManagerInterface $userManager, $session, array $properties) {

        $this->session = $session;

        parent::__construct($userManager, $properties);
    }

    public function connect(UserInterface $user, UserResponseInterface $response) {
        echo "FOSUBUserProvider::connect()"; die();
    }

    public function loadUserByUsername($username){
        $user = $this->userManager->findUserBy(array('username' => $username));

        if($user === null) {
            return $this->userManager->createUser();
        } else {
            return $user;
        }
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $username = $response->getUsername();
        $email = $response->getEmail();
        $nickname = $response->getNickname();
        $realname = $response->getRealName();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';

            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->setUsername($username);
            $user->setRealname($realname);
            $user->setNickname($nickname);
            $user->setEmail($email);
            $user->setPlainPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
        }
        return $this->loadUserByUsername($response->getUsername());
    }

    public function supportsClass($class){
        return $class === 'Bajke\\BookBundle\\Entity\\User';
    }

}