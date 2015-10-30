<?php

namespace Bajke\BookBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Bajke\BookBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends AbstractVoter {

    const VIEW = 'view';
    const EDIT = 'edit';
    const UPDATE = 'update';
    const CREATE = 'create';
    const DELETE = 'delete';

    protected function getSupportedAttributes() {
        return array(self::VIEW, self::EDIT, self::UPDATE, self::CREATE, self::DELETE);
    }
    protected function getSupportedClasses() {
        return array('Bajke\BookBundle\Entity\Book');
    }

    protected function isGranted($attribute, $book, $user = null) {
        if(!$user instanceof UserInterface){
            return false;
        }

        if(!$user instanceof User){
            throw new \LogicException('The user is somehow not our User class!');
        }

        switch($attribute){
            case self::VIEW:{
                if($book->getOwner()->getId() === $user->getId()){
                    return true;
                }
                break;
            }

            case self::EDIT:{
                if($book->getOwner()->getId() === $user->getId()){
                    return true;
                }
                break;
            }

            case self::UPDATE:{
                if($book->getOwner()->getId() === $user->getId()){
                    return true;
                }
                break;
            }

            case self::CREATE:{
                if($book->getOwner()->getId() === $user->getId()){
                    return true;
                }
                break;
            }

            case self::DELETE:{
                if($book->getOwner()->getId() === $user->getId()){
                    return true;
                }
                break;
            }
        }

        return false;
    }

}