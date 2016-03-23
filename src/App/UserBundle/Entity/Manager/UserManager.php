<?php

namespace App\UserBundle\Entity\Manager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\UserBundle\AppUserEvents;
use App\UserBundle\Entity\UserInterface;
use App\UserBundle\Event\UserDataEvent;
use App\UserBundle\Repository\UserRepository;

class UserManager implements UserManagerInterface
{
    /**
     * @var EncoderFactoryInterface $encoderFactory
     */
    protected $encoderFactory;

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    protected $dispatcher;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    protected $encoder;

    /**
     *
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * @param EncoderFactoryInterface       $encoderFactory
     * @param EventDispatcherInterface      $dispatcher
     * @param UserPasswordEncoderInterface  $encoder
     * @param UserRepository                $userRepository
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EventDispatcherInterface $dispatcher,
        UserPasswordEncoderInterface $encoder,
        UserRepository $userRepository
    )
    {
        $this->encoderFactory = $encoderFactory;
        $this->dispatcher = $dispatcher;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function createUser(UserInterface $user)
    {
        $user->setCgvRead(false);
        $user->setRoles(['ROLE_VISITOR']);
        $user->encodePassword($this->encoderFactory->getEncoder($user));
        $this->save($user, true, true);

        $this->dispatcher->dispatch(
            AppUserEvents::NEW_ACCOUNT_CREATED, new UserDataEvent($user)
        );
    }

    public function updateCredentials(UserInterface $user, $newPassword)
    {
        $user->setPlainPassword($newPassword);
        $user->encodePassword($this->encoderFactory->getEncoder($user));
        $this->save($user, false, true);
    }

    public function isPasswordValid(UserInterface $user, $plainPassword)
    {
        return $this->encoder->isPasswordValid($user, $plainPassword);
    }

    public function getUserByIdentifier($identifier)
    {
        return $this->userRepository->getUserByEmailOrUsername($identifier);
    }

    public function sendRequestPassword($user)
    {
        $this->dispatcher->dispatch(
            AppUserEvents::NEW_PASSWORD_REQUESTED, new UserDataEvent($user)
        );
    }

    public function updateConfirmationTokenUser(UserInterface $user, $token) {
        $user->setConfirmationToken($token);
        $user->setIsAlreadyRequested(true);
        $this->save($user, false, true);
    }

    public function getUserByConfirmationToken($token)
    {
        return $this->userRepository->findOneByConfirmationToken($token);
    }

    public function clearConfirmationTokenUser(UserInterface $user) {
        $user->setConfirmationToken(null);
        $user->setIsAlreadyRequested(false);
    }

    /**
     * @param UserInterface $user
     * @param \Datetime $lastConnexion
     */
    public function setLastConnexion(UserInterface $user, \Datetime $lastConnexion)
    {
        $user->setLastConnexion($lastConnexion);
    }

    public function save(UserInterface $user, $persist = false, $flush = true)
    {
        $this->userRepository->save($user, $persist, $flush);
    }
}