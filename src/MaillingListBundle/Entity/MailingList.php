<?php

namespace MaillingListBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * MailingList
 *
 */
class MailingList
{

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = "0",max = "32")
     */
    private $nom;

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return MailingList
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
}
