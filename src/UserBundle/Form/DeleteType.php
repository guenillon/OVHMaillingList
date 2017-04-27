<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteType extends AbstractType
{	    
    /**
     * @param OptionsResolverInterface $resolver
     */
	public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        	'method' => 'DELETE'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_userbundle_delete';
    }
}
