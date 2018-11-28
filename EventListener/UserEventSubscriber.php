<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use Ekyna\Component\Resource\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEventSubscriber
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserEventSubscriber implements EventSubscriberInterface
{
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
     * @param UserManagerInterface          $fosUserManager
     * @param Mailer                        $mailer
     */
    public function __construct(UserManagerInterface $fosUserManager, Mailer $mailer)
    {
        $this->userManager = $fosUserManager;
        $this->mailer = $mailer;
    }

    /**
     * Pre create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreCreate(ResourceEventInterface $event)
    {
        $user = $this->getUserFromEvent($event);

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
                ->addData('password', $password);
        }

        $this->userManager->updateUsername($user);
        // TODO remove ? (done by the fos user listener)
        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
    }

    /**
     * Post create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPostCreate(ResourceEventInterface $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        $user = $this->getUserFromEvent($event);
        if (!$user->getSendCreationEmail()) {
            return;
        }

        if (0 < $this->mailer->sendCreationEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.message.credentials_sent'));
        }
    }

    /**
     * Pre update resource event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreUpdate(ResourceEventInterface $event)
    {
        $user = $this->getUserFromEvent($event);

        $this->userManager->updateUsername($user);
        // TODO remove ? (done by the fos user listener)
        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
    }

    /**
     * Post generate password event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPostGeneratePassword(ResourceEventInterface $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        $user = $this->getUserFromEvent($event);

        if (0 < $this->mailer->sendNewPasswordEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.message.credentials_sent'));
        }
    }

    /**
     * Returns the user form the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return UserInterface
     */
    protected function getUserFromEvent(ResourceEventInterface $event)
    {
        $resource = $event->getResource();

        if (!$resource instanceof UserInterface) {
            throw new InvalidArgumentException("Expected instance of " . UserInterface::class);
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::PRE_CREATE             => ['onPreCreate', 0],
            UserEvents::POST_CREATE            => ['onPostCreate', 0],
            UserEvents::PRE_UPDATE             => ['onPreUpdate', 0],
            UserEvents::POST_GENERATE_PASSWORD => ['onPostGeneratePassword', 0],
        ];
    }
}
