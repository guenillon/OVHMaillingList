<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as FOSRegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', ChoiceType::class, array('choices' =>
                array(
                        'user.role.admin' => 'ROLE_ADMIN',
                ),
                'label' => 'user.role.titre',
                'required'  => true,
                'multiple' => true,
                'expanded' => true
            ));
    }

    public function getParent()
    {
        return FOSRegistrationFormType::class;
    }

    public function getName()
    {
        return 'jpi_user_registration';
    }
}
