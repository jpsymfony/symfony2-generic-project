<?php

namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\PortalBundle\Entity\Contact;

interface ContactManagerInterface
{
    public function sendMail(Contact $data);
} 
