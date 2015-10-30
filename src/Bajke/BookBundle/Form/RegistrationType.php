<?php

namespace Bajke\BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('realname', 'text', array('label' => 'Real Name'))
            ->add('nickname', 'text')
            ->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
//                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
    }

    public function getParent() {
        return 'fos_user_registration';
    }

    public function getName() {
        return 'user_registration';
    }

}