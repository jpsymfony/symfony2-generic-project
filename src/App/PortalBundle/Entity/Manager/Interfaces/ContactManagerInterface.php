<?php

namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\PortalBundle\Entity\Contact;
use Symfony\Component\Translation\TranslatorInterface;

interface ContactManagerInterface
{
    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     * @param $template
     * @param $from
     * @param $to
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, TranslatorInterface $translator, $template, $from, $to);

    /**
     * @param Contact $data
     */
    public function sendMail(Contact $data);
} 
