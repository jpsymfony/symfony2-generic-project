<?php

namespace App\UserBundle\Entity\Manager;

use App\UserBundle\Entity\UserInterface;

interface UserManagerInterface
{
    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function createUser(UserInterface $user);
} 
