<?php

namespace MaillingListBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abonne
 *
 */
class Abonne
{
    /**
     * @var string
     * @Assert\Email()
     * 
     */
    private $mail;


    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Abonne
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
}

