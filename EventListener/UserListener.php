<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Ekyna\Bundle\UserBundle\Event\UserEvent;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
    protected $userManager;

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
        $this->userManager  = $fosUserManager;
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
        if (0 === strlen($user->getPlainPassword())) {
            $this->userManager->generatePassword($user);
            $user->setEnabled(true);

            // Warn about the generated password
            $password = $user->getPlainPassword();
            $event
                ->addMessage(new ResourceMessage(
                    sprintf('Generated password : "%s".', $password),
                    ResourceMessage::TYPE_INFO
                ))
                ->addData('password', $password)
            ;
        }

        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
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
        if (!$user->getSendCreationEmail()) {
            return;
        }

        if (0 < $this->mailer->sendCreationEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.event.credentials_sent'));
        }
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

        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
    }

    /**
     * Post update resource event handler.
     *
     * @param UserEvent $event
     */
    public function onPostUpdate(UserEvent $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getResource();

        if (0 < $this->mailer->sendNewPasswordEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.event.credentials_sent'));
        }
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
            UserEvents::POST_UPDATE  => array('onPostUpdate', 0),
        );
    }
}
