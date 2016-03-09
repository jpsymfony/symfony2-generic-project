<?php

namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Entity\Contact;

interface ContactManagerInterface
{
    public function sendMail(Contact $data);
} 
