<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Ekyna\Bundle\UserBundle\Event\UserEvent;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use FOS\UserBundle\Event\UserEvent AS FOSUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class UserListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserListener implements EventSubscriberInterface
{
    /**
     * @var EntityRepository
     */
    protected $groupRepository;

    /**
     * @var UserManagerInterface
     */
    protected $fosUserManager;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * Constructor.
     *
     * @param EntityRepository     $groupRepository
     * @param UserManagerInterface $fosUserManager
     * @param Mailer               $mailer
     */
    public function __construct(EntityRepository $groupRepository, UserManagerInterface $fosUserManager, Mailer $mailer)
    {
        $this->groupRepository = $groupRepository;
        $this->fosUserManager  = $fosUserManager;
        $this->mailer          = $mailer;
    }

    /**
     * Pre create resource event handler.
     *
     * @param UserEvent $event
     */
    public function onPreCreate(UserEvent $event)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getResource();

        // Generates a secured password.
        $password = '';
        if (0 === strlen($user->getPlainPassword())) {
            $generator = new SecureRandom();
            $password = bin2hex($generator->nextBytes(4));
            $user->setPlainPassword($password);
            $user->setEnabled(true);

            // Warn about the generated password
            $event
                ->addMessage(new ResourceMessage(
                    sprintf('Generated password : "%s".', $password),
                    ResourceMessage::TYPE_INFO
                ))
                ->addData('password', $password)
            ;
        }

        $this->fosUserManager->updatePassword($user);
        $this->fosUserManager->updateCanonicalFields($user);
    }

    /**
     * Post create resource event handler.
     *
     * @param UserEvent $event
     */
    public function onPostCreate(UserEvent $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getResource();

        if (0 < $this->mailer->sendCreationEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.event.credentials_sent'));
        }

        // Because it is "re-set" in pre_create event
        $user->eraseCredentials();
    }

    /**
     * Pre update resource event handler.
     *
     * @param UserEvent $event
     */
    public function onPreUpdate(UserEvent $event)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getResource();

        $this->fosUserManager->updatePassword($user);
        $this->fosUserManager->updateCanonicalFields($user);
    }

    /**
     * Registration initialize event handler.
     *
     * @param FOSUserEvent $event
     * @throws \RuntimeException
     */
    public function onRegistrationInitialize(FOSUserEvent $event)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $defaultGroup */
        if(null === $defaultGroup = $this->groupRepository->findOneBy(array('default' => true))) {
            throw new \RuntimeException('Enable to find default group.');
        }

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getUser();
        $user->setGroup($defaultGroup);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::PRE_CREATE  => array('onPreCreate', 0),
            UserEvents::POST_CREATE => array('onPostCreate', 0),
            UserEvents::PRE_UPDATE  => array('onPreUpdate', 0),
            FOSUserEvents::REGISTRATION_INITIALIZE => array('onRegistrationInitialize', 0),
        );
    }
}
