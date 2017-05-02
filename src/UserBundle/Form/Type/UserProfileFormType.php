<?php
namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use UserBundle\Form\Type\RegistrationFormType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentUser = $options['user'];
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($currentUser) {
                    // On récupère notre objet user sous-jacent
                    $user = $event->getData();
                    if (null === $user) {
                        return;
                    }
                     
                    if ($currentUser == $user) {
                        $event->getForm()->remove('roles');
                    }
                }
        );
        
        $builder->remove('plainPassword');
    }

    public function getName()
    {
        return 'jpi_user_profile';
    }
    
    public function getParent()
    {
        return RegistrationFormType::class;
    }
}
